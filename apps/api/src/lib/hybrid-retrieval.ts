/**
 * Hybrid Retrieval System with BM25 + Vector ANN + Reciprocal Rank Fusion
 * Better than T3AS (TYPO3 Advanced Search) through multi-modal retrieval
 */

import { MeiliEngine } from '../engines/meili.js';
import { embed } from '@pixelcoda/llm-adapter';
import { vectorSearch } from '../db.js';

export interface RetrievalResult {
  id: string;
  title: string;
  content: string;
  url: string;
  score: number;
  source: 'keyword' | 'vector' | 'hybrid';
  collection?: string;
  metadata?: Record<string, any>;
}

export interface HybridRetrievalOptions {
  project: string;
  query: string;
  collections?: string[];
  limit?: number;
  language?: string;
  boostKeyword?: number;
  boostVector?: number;
  minScore?: number;
  enableRerank?: boolean;
}

/**
 * Reciprocal Rank Fusion (RRF) algorithm
 * Combines multiple ranked lists into a single ranking
 * k=60 is the standard parameter from the original paper
 */
export function reciprocalRankFusion(
  rankedLists: RetrievalResult[][],
  weights: number[] = [],
  k: number = 60
): RetrievalResult[] {
  const scoreMap = new Map<string, number>();
  const itemMap = new Map<string, RetrievalResult>();
  
  // Apply weights if provided, otherwise equal weight
  const listWeights = weights.length === rankedLists.length 
    ? weights 
    : new Array(rankedLists.length).fill(1.0 / rankedLists.length);

  rankedLists.forEach((list, listIdx) => {
    const weight = listWeights[listIdx];
    
    list.forEach((item, rank) => {
      const id = item.id;
      const rrfScore = weight * (1.0 / (k + rank + 1));
      
      // Accumulate RRF scores
      scoreMap.set(id, (scoreMap.get(id) || 0) + rrfScore);
      
      // Store item if not already present
      if (!itemMap.has(id)) {
        itemMap.set(id, item);
      }
    });
  });

  // Sort by RRF score and return
  return Array.from(scoreMap.entries())
    .sort((a, b) => b[1] - a[1])
    .map(([id, score]) => ({
      ...itemMap.get(id)!,
      score,
      source: 'hybrid' as const
    }));
}

/**
 * Perform hybrid retrieval using keyword (BM25) and vector (ANN) search
 */
export class HybridRetriever {
  private meiliEngine: MeiliEngine;
  
  constructor(
    meiliUrl: string = process.env.MEILI_URL || 'http://localhost:7700',
    meiliKey?: string
  ) {
    this.meiliEngine = new MeiliEngine(meiliUrl, meiliKey);
  }

  /**
   * Main hybrid retrieval method
   */
  async retrieve(options: HybridRetrievalOptions): Promise<RetrievalResult[]> {
    const {
      project,
      query,
      collections = [],
      limit = 50,
      language = 'de',
      boostKeyword = 0.5,
      boostVector = 0.5,
      minScore = 0.0,
      enableRerank = false
    } = options;

    // Ensure weights sum to 1
    const normalizedBoostKeyword = boostKeyword / (boostKeyword + boostVector);
    const normalizedBoostVector = boostVector / (boostKeyword + boostVector);

    // Parallel retrieval from both sources
    const [keywordResults, vectorResults] = await Promise.all([
      this.keywordSearch(project, query, collections, limit * 2, language),
      this.vectorSearch(project, query, collections, limit * 2, language)
    ]);

    // Apply Reciprocal Rank Fusion
    const fusedResults = reciprocalRankFusion(
      [keywordResults, vectorResults],
      [normalizedBoostKeyword, normalizedBoostVector]
    );

    // Filter by minimum score
    const filteredResults = fusedResults.filter(r => r.score >= minScore);

    // Optional re-ranking step (will be implemented with cross-encoder)
    if (enableRerank && filteredResults.length > 0) {
      // This will be enhanced with cross-encoder reranking
      return this.rerankResults(query, filteredResults, limit);
    }

    return filteredResults.slice(0, limit);
  }

  /**
   * Keyword-based search using BM25
   */
  private async keywordSearch(
    project: string,
    query: string,
    collections: string[],
    limit: number,
    language: string
  ): Promise<RetrievalResult[]> {
    try {
      const searchPayload = {
        q: query,
        limit,
        filters: collections.length > 0 ? { _collection: collections } : undefined,
        attributesToRetrieve: ['id', 'title', 'content', 'url', 'collection', 'summary', 'facets'],
        attributesToSearchOn: ['title', 'content', 'summary', 'keywords'],
        showRankingScore: true
      };

      const results = await this.meiliEngine.search(project, searchPayload);
      const hits = results.hits || [];

      return hits.map((hit: any, index: number) => ({
        id: hit.id || `${hit.collection}:${hit.uid}`,
        title: hit.title || '',
        content: hit.content || hit.summary || '',
        url: hit.url || '',
        score: hit._rankingScore || (1.0 / (index + 1)), // Use ranking score or position-based
        source: 'keyword' as const,
        collection: hit.collection || hit._collection,
        metadata: {
          facets: hit.facets,
          language: hit.lang || language,
          boost: hit.boost || 1.0
        }
      }));
    } catch (error) {
      console.error('Keyword search error:', error);
      return [];
    }
  }

  /**
   * Vector-based semantic search using embeddings
   */
  private async vectorSearch(
    project: string,
    query: string,
    collections: string[],
    limit: number,
    language: string
  ): Promise<RetrievalResult[]> {
    try {
      // Check if vector search is enabled
      if (process.env.ENABLE_VECTOR_SEARCH !== 'true') {
        return [];
      }

      // Generate query embedding
      const queryEmbedding = await embed(query);
      
      // Perform vector similarity search
      const results = await vectorSearch(project, queryEmbedding, {
        collections,
        limit,
        threshold: 0.3 // Lower threshold for broader recall
      });

      return results.map((result: any, index: number) => ({
        id: result.id,
        title: result.title || '',
        content: result.content || '',
        url: result.url || '',
        score: result.similarity || (1.0 / (index + 1)),
        source: 'vector' as const,
        collection: result.collection,
        metadata: {
          language: result.lang || language,
          embedding_model: process.env.OPENAI_EMBEDDING_MODEL || 'unknown'
        }
      }));
    } catch (error) {
      console.error('Vector search error:', error);
      return [];
    }
  }

  /**
   * Re-rank results using a cross-encoder or LLM
   * This is a placeholder for the cross-encoder implementation
   */
  private async rerankResults(
    query: string,
    results: RetrievalResult[],
    limit: number
  ): Promise<RetrievalResult[]> {
    // For now, just return top results
    // This will be enhanced with cross-encoder model
    return results.slice(0, limit);
  }

  /**
   * Calculate diversity score to ensure varied results
   */
  private calculateDiversity(results: RetrievalResult[]): number {
    if (results.length === 0) return 0;
    
    const collections = new Set(results.map(r => r.collection));
    const sources = new Set(results.map(r => r.source));
    
    const collectionDiversity = collections.size / results.length;
    const sourceDiversity = sources.size / 3; // Max 3 sources
    
    return (collectionDiversity + sourceDiversity) / 2;
  }

  /**
   * Get retrieval statistics for debugging
   */
  getRetrievalStats(results: RetrievalResult[]): {
    total: number;
    bySource: Record<string, number>;
    byCollection: Record<string, number>;
    avgScore: number;
    diversity: number;
  } {
    const bySource: Record<string, number> = {};
    const byCollection: Record<string, number> = {};
    let totalScore = 0;

    results.forEach(result => {
      // Count by source
      bySource[result.source] = (bySource[result.source] || 0) + 1;
      
      // Count by collection
      if (result.collection) {
        byCollection[result.collection] = (byCollection[result.collection] || 0) + 1;
      }
      
      totalScore += result.score;
    });

    return {
      total: results.length,
      bySource,
      byCollection,
      avgScore: results.length > 0 ? totalScore / results.length : 0,
      diversity: this.calculateDiversity(results)
    };
  }
}

/**
 * Singleton instance for convenience
 */
let hybridRetriever: HybridRetriever | null = null;

export function getHybridRetriever(): HybridRetriever {
  if (!hybridRetriever) {
    hybridRetriever = new HybridRetriever();
  }
  return hybridRetriever;
}
