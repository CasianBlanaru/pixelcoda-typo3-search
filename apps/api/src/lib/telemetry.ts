/**
 * Advanced Telemetry System for Search Analytics
 * Tracks queries, clicks, and user behavior for optimization
 */

import { getDb } from '../db.js';

export interface QueryMetric {
  query: string;
  project_id: string;
  results_count: number;
  response_time_ms: number;
  language?: string;
  collections?: string[];
  user_agent?: string;
  ip?: string;
  session_id?: string;
  timestamp: Date;
}

export interface ClickMetric {
  query: string;
  project_id: string;
  document_id: string;
  position: number;
  url?: string;
  collection?: string;
  dwell_time_ms?: number;
  session_id?: string;
  timestamp: Date;
}

export interface SearchSession {
  id: string;
  project_id: string;
  started_at: Date;
  queries: QueryMetric[];
  clicks: ClickMetric[];
  metadata?: Record<string, any>;
}

export interface TelemetryStats {
  totalQueries: number;
  uniqueQueries: number;
  avgResponseTime: number;
  avgResultsCount: number;
  clickThroughRate: number;
  noResultsRate: number;
  topQueries: { query: string; count: number; ctr: number }[];
  topNoResults: { query: string; count: number }[];
  queriesByHour: { hour: number; count: number }[];
  clicksByPosition: { position: number; count: number; rate: number }[];
}

/**
 * Telemetry service for tracking and analyzing search behavior
 */
export class TelemetryService {
  private db: any;
  private batchQueue: Map<string, any[]> = new Map();
  private flushInterval: NodeJS.Timeout | null = null;
  private readonly BATCH_SIZE = 100;
  private readonly FLUSH_INTERVAL = 30000; // 30 seconds

  constructor() {
    this.db = getDb();
    this.startBatchProcessor();
    this.ensureTables();
  }

  /**
   * Ensure telemetry tables exist
   */
  private async ensureTables() {
    try {
      await this.db.query(`
        CREATE TABLE IF NOT EXISTS search_queries (
          id SERIAL PRIMARY KEY,
          project_id VARCHAR(255) NOT NULL,
          query TEXT NOT NULL,
          query_normalized TEXT NOT NULL,
          results_count INTEGER NOT NULL,
          response_time_ms INTEGER NOT NULL,
          language VARCHAR(10),
          collections TEXT[],
          user_agent TEXT,
          ip_hash VARCHAR(64),
          session_id VARCHAR(255),
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_project_query (project_id, query_normalized),
          INDEX idx_created_at (created_at),
          INDEX idx_session (session_id)
        )
      `);

      await this.db.query(`
        CREATE TABLE IF NOT EXISTS search_clicks (
          id SERIAL PRIMARY KEY,
          project_id VARCHAR(255) NOT NULL,
          query TEXT NOT NULL,
          document_id VARCHAR(255) NOT NULL,
          position INTEGER NOT NULL,
          url TEXT,
          collection VARCHAR(255),
          dwell_time_ms INTEGER,
          session_id VARCHAR(255),
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_project_doc (project_id, document_id),
          INDEX idx_query (query),
          INDEX idx_session (session_id)
        )
      `);

      await this.db.query(`
        CREATE TABLE IF NOT EXISTS search_synonyms_discovered (
          id SERIAL PRIMARY KEY,
          project_id VARCHAR(255) NOT NULL,
          term1 VARCHAR(255) NOT NULL,
          term2 VARCHAR(255) NOT NULL,
          confidence DECIMAL(3,2) NOT NULL,
          co_occurrence_count INTEGER DEFAULT 0,
          status VARCHAR(20) DEFAULT 'pending',
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          reviewed_at TIMESTAMP,
          UNIQUE KEY unique_terms (project_id, term1, term2),
          INDEX idx_status (status),
          INDEX idx_confidence (confidence DESC)
        )
      `);

      await this.db.query(`
        CREATE TABLE IF NOT EXISTS search_performance_rules (
          id SERIAL PRIMARY KEY,
          project_id VARCHAR(255) NOT NULL,
          rule_type VARCHAR(50) NOT NULL,
          query_pattern TEXT,
          action VARCHAR(50) NOT NULL,
          action_params JSON,
          priority INTEGER DEFAULT 0,
          enabled BOOLEAN DEFAULT true,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          INDEX idx_project_enabled (project_id, enabled),
          INDEX idx_priority (priority DESC)
        )
      `);
    } catch (error) {
      console.error('Failed to create telemetry tables:', error);
    }
  }

  /**
   * Start batch processor for efficient database writes
   */
  private startBatchProcessor() {
    this.flushInterval = setInterval(() => {
      this.flushBatches();
    }, this.FLUSH_INTERVAL);
  }

  /**
   * Track a search query
   */
  async trackQuery(metric: QueryMetric): Promise<void> {
    const normalized = this.normalizeQuery(metric.query);
    
    // Add to batch queue
    if (!this.batchQueue.has('queries')) {
      this.batchQueue.set('queries', []);
    }
    
    this.batchQueue.get('queries')!.push({
      ...metric,
      query_normalized: normalized,
      ip_hash: metric.ip ? this.hashIP(metric.ip) : null
    });

    // Flush if batch is full
    if (this.batchQueue.get('queries')!.length >= this.BATCH_SIZE) {
      await this.flushQueries();
    }

    // Real-time analysis for no-results queries
    if (metric.results_count === 0) {
      this.analyzeNoResultsQuery(metric);
    }
  }

  /**
   * Track a click on search result
   */
  async trackClick(metric: ClickMetric): Promise<void> {
    // Add to batch queue
    if (!this.batchQueue.has('clicks')) {
      this.batchQueue.set('clicks', []);
    }
    
    this.batchQueue.get('clicks')!.push(metric);

    // Flush if batch is full
    if (this.batchQueue.get('clicks')!.length >= this.BATCH_SIZE) {
      await this.flushClicks();
    }

    // Update click-through rate cache
    this.updateCTRCache(metric);
  }

  /**
   * Get analytics for a project
   */
  async getAnalytics(
    projectId: string,
    from?: Date,
    to?: Date
  ): Promise<TelemetryStats> {
    const dateFilter = this.buildDateFilter(from, to);

    // Get total and unique queries
    const queryStats = await this.db.query(`
      SELECT 
        COUNT(*) as total_queries,
        COUNT(DISTINCT query_normalized) as unique_queries,
        AVG(response_time_ms) as avg_response_time,
        AVG(results_count) as avg_results_count,
        SUM(CASE WHEN results_count = 0 THEN 1 ELSE 0 END) as no_results_count
      FROM search_queries
      WHERE project_id = $1 ${dateFilter.sql}
    `, [projectId, ...dateFilter.params]);

    // Get top queries with CTR
    const topQueries = await this.db.query(`
      SELECT 
        q.query_normalized as query,
        COUNT(DISTINCT q.id) as query_count,
        COUNT(DISTINCT c.id) as click_count
      FROM search_queries q
      LEFT JOIN search_clicks c ON q.query_normalized = c.query AND q.project_id = c.project_id
      WHERE q.project_id = $1 ${dateFilter.sql}
      GROUP BY q.query_normalized
      ORDER BY query_count DESC
      LIMIT 20
    `, [projectId, ...dateFilter.params]);

    // Get top no-results queries
    const topNoResults = await this.db.query(`
      SELECT 
        query_normalized as query,
        COUNT(*) as count
      FROM search_queries
      WHERE project_id = $1 AND results_count = 0 ${dateFilter.sql}
      GROUP BY query_normalized
      ORDER BY count DESC
      LIMIT 10
    `, [projectId, ...dateFilter.params]);

    // Get queries by hour
    const queriesByHour = await this.db.query(`
      SELECT 
        EXTRACT(HOUR FROM created_at) as hour,
        COUNT(*) as count
      FROM search_queries
      WHERE project_id = $1 ${dateFilter.sql}
      GROUP BY hour
      ORDER BY hour
    `, [projectId, ...dateFilter.params]);

    // Get clicks by position
    const clicksByPosition = await this.db.query(`
      SELECT 
        position,
        COUNT(*) as count
      FROM search_clicks
      WHERE project_id = $1 AND position <= 20 ${dateFilter.sql}
      GROUP BY position
      ORDER BY position
    `, [projectId, ...dateFilter.params]);

    const stats = queryStats.rows[0];
    const totalQueries = parseInt(stats.total_queries) || 0;
    const totalClicks = await this.getTotalClicks(projectId, from, to);

    return {
      totalQueries,
      uniqueQueries: parseInt(stats.unique_queries) || 0,
      avgResponseTime: parseFloat(stats.avg_response_time) || 0,
      avgResultsCount: parseFloat(stats.avg_results_count) || 0,
      clickThroughRate: totalQueries > 0 ? totalClicks / totalQueries : 0,
      noResultsRate: totalQueries > 0 ? parseInt(stats.no_results_count) / totalQueries : 0,
      topQueries: topQueries.rows.map((row: any) => ({
        query: row.query,
        count: parseInt(row.query_count),
        ctr: row.query_count > 0 ? parseInt(row.click_count) / parseInt(row.query_count) : 0
      })),
      topNoResults: topNoResults.rows.map((row: any) => ({
        query: row.query,
        count: parseInt(row.count)
      })),
      queriesByHour: queriesByHour.rows.map((row: any) => ({
        hour: parseInt(row.hour),
        count: parseInt(row.count)
      })),
      clicksByPosition: clicksByPosition.rows.map((row: any) => ({
        position: parseInt(row.position),
        count: parseInt(row.count),
        rate: totalClicks > 0 ? parseInt(row.count) / totalClicks : 0
      }))
    };
  }

  /**
   * Discover potential synonyms from search behavior
   */
  async discoverSynonyms(projectId: string): Promise<any[]> {
    // Find queries with similar click patterns
    const similarQueries = await this.db.query(`
      WITH query_clicks AS (
        SELECT 
          q.query_normalized,
          c.document_id,
          COUNT(*) as click_count
        FROM search_queries q
        JOIN search_clicks c ON q.query_normalized = c.query AND q.project_id = c.project_id
        WHERE q.project_id = $1
          AND q.created_at >= NOW() - INTERVAL '30 days'
        GROUP BY q.query_normalized, c.document_id
      ),
      query_pairs AS (
        SELECT 
          q1.query_normalized as term1,
          q2.query_normalized as term2,
          COUNT(DISTINCT q1.document_id) as common_docs,
          AVG(ABS(q1.click_count - q2.click_count)) as click_diff
        FROM query_clicks q1
        JOIN query_clicks q2 ON q1.document_id = q2.document_id 
          AND q1.query_normalized < q2.query_normalized
        GROUP BY q1.query_normalized, q2.query_normalized
        HAVING COUNT(DISTINCT q1.document_id) >= 3
      )
      SELECT 
        term1,
        term2,
        common_docs,
        click_diff,
        common_docs / (1 + click_diff) as confidence
      FROM query_pairs
      ORDER BY confidence DESC
      LIMIT 50
    `, [projectId]);

    // Store discovered synonyms
    for (const pair of similarQueries.rows) {
      await this.db.query(`
        INSERT INTO search_synonyms_discovered 
        (project_id, term1, term2, confidence, co_occurrence_count)
        VALUES ($1, $2, $3, $4, $5)
        ON DUPLICATE KEY UPDATE
          confidence = VALUES(confidence),
          co_occurrence_count = co_occurrence_count + 1
      `, [
        projectId,
        pair.term1,
        pair.term2,
        Math.min(pair.confidence, 1),
        pair.common_docs
      ]);
    }

    return similarQueries.rows;
  }

  /**
   * Get recommended performance rules based on telemetry
   */
  async getRecommendedRules(projectId: string): Promise<any[]> {
    const recommendations = [];

    // Find queries that always click on same results (pin candidates)
    const pinCandidates = await this.db.query(`
      SELECT 
        q.query_normalized as query,
        c.document_id,
        c.url,
        COUNT(*) as click_count,
        AVG(c.position) as avg_position
      FROM search_queries q
      JOIN search_clicks c ON q.query_normalized = c.query AND q.project_id = c.project_id
      WHERE q.project_id = $1
        AND q.created_at >= NOW() - INTERVAL '30 days'
      GROUP BY q.query_normalized, c.document_id, c.url
      HAVING COUNT(*) >= 5 AND AVG(c.position) > 3
      ORDER BY click_count DESC
      LIMIT 20
    `, [projectId]);

    for (const candidate of pinCandidates.rows) {
      recommendations.push({
        type: 'pin',
        query: candidate.query,
        document_id: candidate.document_id,
        url: candidate.url,
        reason: `Dokument wird häufig geklickt (${candidate.click_count}x), aber erscheint im Durchschnitt auf Position ${Math.round(candidate.avg_position)}`,
        impact: 'high'
      });
    }

    // Find queries with low CTR (boost candidates)
    const boostCandidates = await this.db.query(`
      SELECT 
        query_normalized as query,
        COUNT(*) as query_count,
        SUM(CASE WHEN results_count > 0 THEN 1 ELSE 0 END) as with_results,
        COUNT(DISTINCT c.id) as click_count
      FROM search_queries q
      LEFT JOIN search_clicks c ON q.query_normalized = c.query AND q.project_id = c.project_id
      WHERE q.project_id = $1
        AND q.created_at >= NOW() - INTERVAL '30 days'
      GROUP BY query_normalized
      HAVING COUNT(*) >= 10 AND (COUNT(DISTINCT c.id) / COUNT(*)) < 0.2
      ORDER BY query_count DESC
      LIMIT 10
    `, [projectId]);

    for (const candidate of boostCandidates.rows) {
      const ctr = candidate.query_count > 0 ? candidate.click_count / candidate.query_count : 0;
      recommendations.push({
        type: 'boost',
        query: candidate.query,
        reason: `Niedrige Click-Through-Rate (${(ctr * 100).toFixed(1)}%) bei ${candidate.query_count} Suchen`,
        impact: 'medium'
      });
    }

    return recommendations;
  }

  /**
   * Normalize query for comparison
   */
  private normalizeQuery(query: string): string {
    return query
      .toLowerCase()
      .trim()
      .replace(/[^\w\s]/g, ' ')
      .replace(/\s+/g, ' ');
  }

  /**
   * Hash IP address for privacy
   */
  private hashIP(ip: string): string {
    const crypto = require('crypto');
    return crypto.createHash('sha256').update(ip + process.env.TELEMETRY_SALT || 'default').digest('hex');
  }

  /**
   * Build date filter for SQL queries
   */
  private buildDateFilter(from?: Date, to?: Date): { sql: string; params: any[] } {
    const conditions = [];
    const params = [];

    if (from) {
      conditions.push(`AND created_at >= $${params.length + 2}`);
      params.push(from);
    }

    if (to) {
      conditions.push(`AND created_at <= $${params.length + 2}`);
      params.push(to);
    }

    return {
      sql: conditions.join(' '),
      params
    };
  }

  /**
   * Get total clicks for a project
   */
  private async getTotalClicks(projectId: string, from?: Date, to?: Date): Promise<number> {
    const dateFilter = this.buildDateFilter(from, to);
    const result = await this.db.query(`
      SELECT COUNT(*) as total
      FROM search_clicks
      WHERE project_id = $1 ${dateFilter.sql}
    `, [projectId, ...dateFilter.params]);

    return parseInt(result.rows[0]?.total) || 0;
  }

  /**
   * Analyze no-results query in real-time
   */
  private async analyzeNoResultsQuery(metric: QueryMetric) {
    // Could trigger alerts or auto-suggestions
    console.log(`No results for query: "${metric.query}" in project ${metric.project_id}`);
  }

  /**
   * Update CTR cache for real-time monitoring
   */
  private updateCTRCache(metric: ClickMetric) {
    // Implementation for real-time CTR tracking
  }

  /**
   * Flush query batch to database
   */
  private async flushQueries() {
    const queries = this.batchQueue.get('queries');
    if (!queries || queries.length === 0) return;

    try {
      const values = queries.map(q => [
        q.project_id,
        q.query,
        q.query_normalized,
        q.results_count,
        q.response_time_ms,
        q.language,
        q.collections ? JSON.stringify(q.collections) : null,
        q.user_agent,
        q.ip_hash,
        q.session_id
      ]);

      await this.db.query(`
        INSERT INTO search_queries 
        (project_id, query, query_normalized, results_count, response_time_ms, 
         language, collections, user_agent, ip_hash, session_id)
        VALUES ?
      `, [values]);

      this.batchQueue.set('queries', []);
    } catch (error) {
      console.error('Failed to flush queries:', error);
    }
  }

  /**
   * Flush click batch to database
   */
  private async flushClicks() {
    const clicks = this.batchQueue.get('clicks');
    if (!clicks || clicks.length === 0) return;

    try {
      const values = clicks.map(c => [
        c.project_id,
        c.query,
        c.document_id,
        c.position,
        c.url,
        c.collection,
        c.dwell_time_ms,
        c.session_id
      ]);

      await this.db.query(`
        INSERT INTO search_clicks
        (project_id, query, document_id, position, url, collection, dwell_time_ms, session_id)
        VALUES ?
      `, [values]);

      this.batchQueue.set('clicks', []);
    } catch (error) {
      console.error('Failed to flush clicks:', error);
    }
  }

  /**
   * Flush all batches
   */
  private async flushBatches() {
    await Promise.all([
      this.flushQueries(),
      this.flushClicks()
    ]);
  }

  /**
   * Cleanup on shutdown
   */
  async shutdown() {
    if (this.flushInterval) {
      clearInterval(this.flushInterval);
    }
    await this.flushBatches();
  }
}

// Singleton instance
let telemetryService: TelemetryService | null = null;

export function getTelemetryService(): TelemetryService {
  if (!telemetryService) {
    telemetryService = new TelemetryService();
  }
  return telemetryService;
}
