/**
 * Cross-Encoder Re-Ranking System
 * Implements sophisticated re-ranking using cross-encoder models
 * Reduces Top-50 to Top-10 with higher precision
 */

import { OpenAI } from 'openai';

export interface RerankCandidate {
  id: string;
  title: string;
  content: string;
  score?: number;
  metadata?: Record<string, any>;
}

export interface RerankResult extends RerankCandidate {
  rerankScore: number;
  originalScore?: number;
}

export interface CrossEncoderOptions {
  model?: 'openai' | 'cohere' | 'local';
  maxLength?: number;
  batchSize?: number;
  temperature?: number;
  topK?: number;
}

/**
 * Cross-Encoder for re-ranking search results
 */
export class CrossEncoderReranker {
  private openaiClient?: OpenAI;
  private options: CrossEncoderOptions;

  constructor(options: CrossEncoderOptions = {}) {
    this.options = {
      model: options.model || 'openai',
      maxLength: options.maxLength || 512,
      batchSize: options.batchSize || 10,
      temperature: options.temperature || 0.1,
      topK: options.topK || 10
    };

    // Initialize OpenAI client if needed
    if (this.options.model === 'openai' && process.env.OPENAI_API_KEY) {
      this.openaiClient = new OpenAI({
        apiKey: process.env.OPENAI_API_KEY
      });
    }
  }

  /**
   * Re-rank candidates using cross-encoder
   */
  async rerank(
    query: string,
    candidates: RerankCandidate[],
    topK?: number
  ): Promise<RerankResult[]> {
    const k = topK || this.options.topK || 10;
    
    if (candidates.length === 0) {
      return [];
    }

    // If we have fewer candidates than topK, just score them all
    if (candidates.length <= k) {
      return this.scoreAllCandidates(query, candidates);
    }

    // For larger sets, use batching and early stopping
    const scoredCandidates = await this.scoreWithOptimization(query, candidates, k);
    
    // Sort by rerank score and return top K
    return scoredCandidates
      .sort((a, b) => b.rerankScore - a.rerankScore)
      .slice(0, k);
  }

  /**
   * Score all candidates without optimization
   */
  private async scoreAllCandidates(
    query: string,
    candidates: RerankCandidate[]
  ): Promise<RerankResult[]> {
    const scores = await this.batchScore(query, candidates);
    
    return candidates.map((candidate, index) => ({
      ...candidate,
      rerankScore: scores[index],
      originalScore: candidate.score
    }));
  }

  /**
   * Score with optimization for large candidate sets
   */
  private async scoreWithOptimization(
    query: string,
    candidates: RerankCandidate[],
    topK: number
  ): Promise<RerankResult[]> {
    // First pass: Score top candidates based on original score
    const firstBatch = candidates.slice(0, Math.min(topK * 2, 50));
    const firstScores = await this.batchScore(query, firstBatch);
    
    let results: RerankResult[] = firstBatch.map((candidate, index) => ({
      ...candidate,
      rerankScore: firstScores[index],
      originalScore: candidate.score
    }));

    // If we have more candidates, score additional batches
    if (candidates.length > firstBatch.length) {
      // Get threshold score from current top-K
      results.sort((a, b) => b.rerankScore - a.rerankScore);
      const thresholdScore = results[Math.min(topK - 1, results.length - 1)].rerankScore;
      
      // Score remaining candidates in batches
      const remaining = candidates.slice(firstBatch.length);
      const batches = this.createBatches(remaining, this.options.batchSize || 10);
      
      for (const batch of batches) {
        const batchScores = await this.batchScore(query, batch);
        const batchResults = batch.map((candidate, index) => ({
          ...candidate,
          rerankScore: batchScores[index],
          originalScore: candidate.score
        }));
        
        // Add promising candidates
        const promising = batchResults.filter(r => r.rerankScore > thresholdScore * 0.8);
        if (promising.length > 0) {
          results = results.concat(promising);
        }
        
        // Early stopping if we have enough high-quality results
        if (results.length > topK * 3) {
          break;
        }
      }
    }

    return results;
  }

  /**
   * Batch scoring for efficiency
   */
  private async batchScore(
    query: string,
    candidates: RerankCandidate[]
  ): Promise<number[]> {
    switch (this.options.model) {
      case 'openai':
        return this.scoreWithOpenAI(query, candidates);
      case 'cohere':
        return this.scoreWithCohere(query, candidates);
      case 'local':
        return this.scoreWithLocal(query, candidates);
      default:
        return this.scoreWithSimpleSimilarity(query, candidates);
    }
  }

  /**
   * Score using OpenAI's models
   */
  private async scoreWithOpenAI(
    query: string,
    candidates: RerankCandidate[]
  ): Promise<number[]> {
    if (!this.openaiClient) {
      return this.scoreWithSimpleSimilarity(query, candidates);
    }

    try {
      // Prepare batch prompt
      const prompt = this.createScoringPrompt(query, candidates);
      
      const response = await this.openaiClient.chat.completions.create({
        model: process.env.OPENAI_MODEL || 'gpt-4o-mini',
        messages: [
          {
            role: 'system',
            content: 'Du bist ein Experte für die Bewertung der Relevanz von Suchergebnissen. Bewerte jedes Dokument auf einer Skala von 0-100 basierend auf seiner Relevanz zur Suchanfrage.'
          },
          {
            role: 'user',
            content: prompt
          }
        ],
        temperature: this.options.temperature,
        max_tokens: 500
      });

      const content = response.choices[0]?.message?.content || '';
      return this.parseScores(content, candidates.length);
    } catch (error) {
      console.error('OpenAI reranking error:', error);
      return this.scoreWithSimpleSimilarity(query, candidates);
    }
  }

  /**
   * Score using Cohere's rerank API
   */
  private async scoreWithCohere(
    query: string,
    candidates: RerankCandidate[]
  ): Promise<number[]> {
    // Cohere implementation would go here
    // For now, fallback to simple similarity
    return this.scoreWithSimpleSimilarity(query, candidates);
  }

  /**
   * Score using a local model
   */
  private async scoreWithLocal(
    query: string,
    candidates: RerankCandidate[]
  ): Promise<number[]> {
    // Local model implementation (e.g., using sentence-transformers)
    // For now, fallback to simple similarity
    return this.scoreWithSimpleSimilarity(query, candidates);
  }

  /**
   * Simple similarity-based scoring as fallback
   */
  private scoreWithSimpleSimilarity(
    query: string,
    candidates: RerankCandidate[]
  ): number[] {
    const queryTerms = this.tokenize(query.toLowerCase());
    
    return candidates.map(candidate => {
      const titleTerms = this.tokenize((candidate.title || '').toLowerCase());
      const contentTerms = this.tokenize((candidate.content || '').toLowerCase());
      
      // Calculate term overlap
      const titleOverlap = this.calculateOverlap(queryTerms, titleTerms);
      const contentOverlap = this.calculateOverlap(queryTerms, contentTerms);
      
      // Weight title matches higher
      const score = (titleOverlap * 2 + contentOverlap) / 3;
      
      // Boost exact matches
      const exactMatch = candidate.title?.toLowerCase().includes(query.toLowerCase()) ||
                        candidate.content?.toLowerCase().includes(query.toLowerCase());
      
      return exactMatch ? Math.min(score * 1.5, 1.0) : score;
    });
  }

  /**
   * Create a scoring prompt for LLM-based reranking
   */
  private createScoringPrompt(query: string, candidates: RerankCandidate[]): string {
    const candidateTexts = candidates.map((c, i) => {
      const text = this.truncateText(
        `Titel: ${c.title}\nInhalt: ${c.content}`,
        this.options.maxLength || 512
      );
      return `[${i}] ${text}`;
    }).join('\n\n');

    return `Suchanfrage: "${query}"

Bewerte die Relevanz der folgenden Dokumente für die Suchanfrage.
Gib für jedes Dokument eine Punktzahl von 0-100 zurück.
Formatiere deine Antwort als kommaseparierte Liste von Zahlen in der gleichen Reihenfolge.

Dokumente:
${candidateTexts}

Relevanz-Scores (kommasepariert):`;
  }

  /**
   * Parse scores from LLM response
   */
  private parseScores(response: string, expectedCount: number): number[] {
    try {
      // Extract numbers from response
      const numbers = response.match(/\d+(\.\d+)?/g);
      if (!numbers) {
        throw new Error('No numbers found in response');
      }

      const scores = numbers.map(n => {
        const score = parseFloat(n);
        // Normalize to 0-1 range if needed
        return score > 1 ? score / 100 : score;
      });

      // Ensure we have the right number of scores
      while (scores.length < expectedCount) {
        scores.push(0.5); // Default score
      }

      return scores.slice(0, expectedCount);
    } catch (error) {
      console.error('Score parsing error:', error);
      // Return default scores
      return new Array(expectedCount).fill(0.5);
    }
  }

  /**
   * Tokenize text for similarity calculation
   */
  private tokenize(text: string): Set<string> {
    const tokens = text
      .replace(/[^\w\s]/g, ' ')
      .split(/\s+/)
      .filter(token => token.length > 2);
    return new Set(tokens);
  }

  /**
   * Calculate term overlap between two token sets
   */
  private calculateOverlap(set1: Set<string>, set2: Set<string>): number {
    if (set1.size === 0 || set2.size === 0) {
      return 0;
    }

    let overlap = 0;
    for (const token of set1) {
      if (set2.has(token)) {
        overlap++;
      }
    }

    return overlap / Math.max(set1.size, set2.size);
  }

  /**
   * Truncate text to maximum length
   */
  private truncateText(text: string, maxLength: number): string {
    if (text.length <= maxLength) {
      return text;
    }
    return text.substring(0, maxLength - 3) + '...';
  }

  /**
   * Create batches from array
   */
  private createBatches<T>(items: T[], batchSize: number): T[][] {
    const batches: T[][] = [];
    for (let i = 0; i < items.length; i += batchSize) {
      batches.push(items.slice(i, i + batchSize));
    }
    return batches;
  }
}

/**
 * Factory function to create a reranker
 */
export function createReranker(options?: CrossEncoderOptions): CrossEncoderReranker {
  // Determine best available model
  let model: 'openai' | 'cohere' | 'local' = 'local';
  
  if (process.env.OPENAI_API_KEY) {
    model = 'openai';
  } else if (process.env.COHERE_API_KEY) {
    model = 'cohere';
  }

  return new CrossEncoderReranker({
    ...options,
    model: options?.model || model
  });
}

/**
 * Singleton instance
 */
let defaultReranker: CrossEncoderReranker | null = null;

export function getDefaultReranker(): CrossEncoderReranker {
  if (!defaultReranker) {
    defaultReranker = createReranker();
  }
  return defaultReranker;
}
