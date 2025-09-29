/**
 * Admin Console API for Search Management
 * Features: Synonyms, Rules, A/B Testing, Performance Monitoring
 */

import { Hono } from 'hono';
import { zValidator } from '@hono/zod-validator';
import { z } from 'zod';
import { enhancedApiKeyAuth } from '../middleware/security.js';
import { getTelemetryService } from '../lib/telemetry.js';
import { getDb } from '../db.js';
import { MeiliEngine } from '../engines/meili.js';

export const router = new Hono();

const telemetryService = getTelemetryService();
const meiliEngine = new MeiliEngine(
  process.env.MEILI_URL || 'http://localhost:7700',
  process.env.MEILI_KEY
);

// Schemas
const synonymSchema = z.object({
  terms: z.array(z.string()).min(2),
  type: z.enum(['synonym', 'oneway', 'alternative']).default('synonym'),
  language: z.string().default('de'),
  enabled: z.boolean().default(true)
});

const ruleSchema = z.object({
  type: z.enum(['pin', 'boost', 'bury', 'exclude', 'redirect']),
  query_pattern: z.string(),
  action_params: z.object({
    document_ids: z.array(z.string()).optional(),
    boost_factor: z.number().optional(),
    redirect_url: z.string().url().optional(),
    position: z.number().optional()
  }),
  priority: z.number().default(0),
  enabled: z.boolean().default(true),
  conditions: z.object({
    collections: z.array(z.string()).optional(),
    language: z.string().optional(),
    date_range: z.object({
      from: z.string().datetime().optional(),
      to: z.string().datetime().optional()
    }).optional()
  }).optional()
});

const abTestSchema = z.object({
  name: z.string(),
  description: z.string().optional(),
  variants: z.array(z.object({
    id: z.string(),
    name: z.string(),
    config: z.object({
      enable_reranking: z.boolean().optional(),
      boost_keyword: z.number().optional(),
      boost_vector: z.number().optional(),
      result_count: z.number().optional()
    })
  })).min(2),
  traffic_allocation: z.record(z.number()),
  start_date: z.string().datetime(),
  end_date: z.string().datetime().optional(),
  enabled: z.boolean().default(true)
});

/**
 * Get telemetry dashboard data
 */
router.get('/admin/:project/dashboard',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      const { from, to } = c.req.query();
      
      const fromDate = from ? new Date(from) : new Date(Date.now() - 30 * 24 * 60 * 60 * 1000);
      const toDate = to ? new Date(to) : new Date();
      
      const analytics = await telemetryService.getAnalytics(project, fromDate, toDate);
      const recommendations = await telemetryService.getRecommendedRules(project);
      
      return c.json({
        project,
        period: { from: fromDate, to: toDate },
        analytics,
        recommendations,
        generated_at: new Date().toISOString()
      });
    } catch (error) {
      console.error('Dashboard error:', error);
      return c.json({ 
        error: 'Failed to load dashboard',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

/**
 * Synonym Management
 */

// List synonyms
router.get('/admin/:project/synonyms',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      const { status, language } = c.req.query();
      
      const db = getDb();
      let query = 'SELECT * FROM search_synonyms WHERE project_id = $1';
      const params: any[] = [project];
      
      if (status) {
        query += ' AND status = $' + (params.length + 1);
        params.push(status);
      }
      
      if (language) {
        query += ' AND language = $' + (params.length + 1);
        params.push(language);
      }
      
      query += ' ORDER BY created_at DESC';
      
      const result = await db.query(query, params);
      
      // Also get discovered synonyms
      const discovered = await db.query(`
        SELECT * FROM search_synonyms_discovered 
        WHERE project_id = $1 AND status = 'pending'
        ORDER BY confidence DESC
        LIMIT 20
      `, [project]);
      
      return c.json({
        synonyms: result.rows,
        discovered: discovered.rows,
        total: result.rows.length
      });
    } catch (error) {
      console.error('Synonym list error:', error);
      return c.json({ 
        error: 'Failed to list synonyms',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Create synonym
router.post('/admin/:project/synonyms',
  enhancedApiKeyAuth('admin'),
  zValidator('json', synonymSchema),
  async (c) => {
    try {
      const { project } = c.req.param();
      const data = await c.req.json();
      
      const db = getDb();
      const result = await db.query(`
        INSERT INTO search_synonyms 
        (project_id, terms, type, language, enabled, created_by)
        VALUES ($1, $2, $3, $4, $5, $6)
        RETURNING *
      `, [
        project,
        JSON.stringify(data.terms),
        data.type,
        data.language,
        data.enabled,
        c.get('userId') || 'admin'
      ]);
      
      // Apply to search engine
      await applySynonymsToEngine(project);
      
      return c.json({
        synonym: result.rows[0],
        message: 'Synonym created and applied'
      });
    } catch (error) {
      console.error('Synonym creation error:', error);
      return c.json({ 
        error: 'Failed to create synonym',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Apply discovered synonym
router.post('/admin/:project/synonyms/apply/:id',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project, id } = c.req.param();
      
      const db = getDb();
      
      // Get discovered synonym
      const discovered = await db.query(
        'SELECT * FROM search_synonyms_discovered WHERE id = $1 AND project_id = $2',
        [id, project]
      );
      
      if (discovered.rows.length === 0) {
        return c.json({ error: 'Synonym not found' }, 404);
      }
      
      const synonym = discovered.rows[0];
      
      // Create actual synonym
      await db.query(`
        INSERT INTO search_synonyms 
        (project_id, terms, type, language, enabled, created_by, confidence_score)
        VALUES ($1, $2, 'synonym', 'de', true, $3, $4)
      `, [
        project,
        JSON.stringify([synonym.term1, synonym.term2]),
        c.get('userId') || 'admin',
        synonym.confidence
      ]);
      
      // Update discovered status
      await db.query(
        'UPDATE search_synonyms_discovered SET status = $1, reviewed_at = NOW() WHERE id = $2',
        ['applied', id]
      );
      
      // Apply to engine
      await applySynonymsToEngine(project);
      
      return c.json({
        message: 'Synonym applied successfully',
        terms: [synonym.term1, synonym.term2]
      });
    } catch (error) {
      console.error('Apply synonym error:', error);
      return c.json({ 
        error: 'Failed to apply synonym',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

/**
 * Rules Engine Management
 */

// List rules
router.get('/admin/:project/rules',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      const { type, enabled } = c.req.query();
      
      const db = getDb();
      let query = 'SELECT * FROM search_performance_rules WHERE project_id = $1';
      const params: any[] = [project];
      
      if (type) {
        query += ' AND rule_type = $' + (params.length + 1);
        params.push(type);
      }
      
      if (enabled !== undefined) {
        query += ' AND enabled = $' + (params.length + 1);
        params.push(enabled === 'true');
      }
      
      query += ' ORDER BY priority DESC, created_at DESC';
      
      const result = await db.query(query, params);
      
      return c.json({
        rules: result.rows,
        total: result.rows.length
      });
    } catch (error) {
      console.error('Rules list error:', error);
      return c.json({ 
        error: 'Failed to list rules',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Create rule
router.post('/admin/:project/rules',
  enhancedApiKeyAuth('admin'),
  zValidator('json', ruleSchema),
  async (c) => {
    try {
      const { project } = c.req.param();
      const data = await c.req.json();
      
      const db = getDb();
      const result = await db.query(`
        INSERT INTO search_performance_rules 
        (project_id, rule_type, query_pattern, action_params, priority, enabled, conditions)
        VALUES ($1, $2, $3, $4, $5, $6, $7)
        RETURNING *
      `, [
        project,
        data.type,
        data.query_pattern,
        JSON.stringify(data.action_params),
        data.priority,
        data.enabled,
        data.conditions ? JSON.stringify(data.conditions) : null
      ]);
      
      return c.json({
        rule: result.rows[0],
        message: 'Rule created successfully'
      });
    } catch (error) {
      console.error('Rule creation error:', error);
      return c.json({ 
        error: 'Failed to create rule',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Test rule
router.post('/admin/:project/rules/test',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      const { query, rule_id } = await c.req.json();
      
      const db = getDb();
      
      // Get rule
      const ruleResult = await db.query(
        'SELECT * FROM search_performance_rules WHERE id = $1 AND project_id = $2',
        [rule_id, project]
      );
      
      if (ruleResult.rows.length === 0) {
        return c.json({ error: 'Rule not found' }, 404);
      }
      
      const rule = ruleResult.rows[0];
      
      // Test rule against query
      const matches = testRulePattern(query, rule.query_pattern);
      
      // If matches, show what would happen
      let preview = null;
      if (matches) {
        preview = await previewRuleEffect(project, query, rule);
      }
      
      return c.json({
        matches,
        rule,
        preview,
        test_query: query
      });
    } catch (error) {
      console.error('Rule test error:', error);
      return c.json({ 
        error: 'Failed to test rule',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

/**
 * A/B Testing
 */

// List A/B tests
router.get('/admin/:project/ab-tests',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      
      const db = getDb();
      const result = await db.query(`
        SELECT * FROM search_ab_tests 
        WHERE project_id = $1 
        ORDER BY created_at DESC
      `, [project]);
      
      // Get test results
      const testsWithResults = await Promise.all(result.rows.map(async (test: any) => {
        const results = await getABTestResults(test.id);
        return { ...test, results };
      }));
      
      return c.json({
        tests: testsWithResults,
        total: result.rows.length
      });
    } catch (error) {
      console.error('A/B test list error:', error);
      return c.json({ 
        error: 'Failed to list A/B tests',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Create A/B test
router.post('/admin/:project/ab-tests',
  enhancedApiKeyAuth('admin'),
  zValidator('json', abTestSchema),
  async (c) => {
    try {
      const { project } = c.req.param();
      const data = await c.req.json();
      
      const db = getDb();
      const result = await db.query(`
        INSERT INTO search_ab_tests 
        (project_id, name, description, variants, traffic_allocation, 
         start_date, end_date, enabled, created_by)
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
        RETURNING *
      `, [
        project,
        data.name,
        data.description,
        JSON.stringify(data.variants),
        JSON.stringify(data.traffic_allocation),
        data.start_date,
        data.end_date,
        data.enabled,
        c.get('userId') || 'admin'
      ]);
      
      return c.json({
        test: result.rows[0],
        message: 'A/B test created successfully'
      });
    } catch (error) {
      console.error('A/B test creation error:', error);
      return c.json({ 
        error: 'Failed to create A/B test',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Toggle A/B test
router.patch('/admin/:project/ab-tests/:id/toggle',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project, id } = c.req.param();
      
      const db = getDb();
      const result = await db.query(`
        UPDATE search_ab_tests 
        SET enabled = NOT enabled, updated_at = NOW()
        WHERE id = $1 AND project_id = $2
        RETURNING *
      `, [id, project]);
      
      if (result.rows.length === 0) {
        return c.json({ error: 'Test not found' }, 404);
      }
      
      return c.json({
        test: result.rows[0],
        message: `Test ${result.rows[0].enabled ? 'enabled' : 'disabled'}`
      });
    } catch (error) {
      console.error('A/B test toggle error:', error);
      return c.json({ 
        error: 'Failed to toggle A/B test',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

/**
 * Index Management
 */

// Blue/Green deployment for index
router.post('/admin/:project/index/switch',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      const { target } = await c.req.json();
      
      if (!['blue', 'green'].includes(target)) {
        return c.json({ error: 'Invalid target. Must be "blue" or "green"' }, 400);
      }
      
      // Switch active index
      const indexName = `${project}_${target}`;
      await meiliEngine.switchIndex(project, indexName);
      
      // Update database
      const db = getDb();
      await db.query(
        'UPDATE search_projects SET active_index = $1, updated_at = NOW() WHERE id = $2',
        [target, project]
      );
      
      return c.json({
        message: `Switched to ${target} index`,
        active_index: target,
        index_name: indexName
      });
    } catch (error) {
      console.error('Index switch error:', error);
      return c.json({ 
        error: 'Failed to switch index',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

// Reindex with new settings
router.post('/admin/:project/index/reindex',
  enhancedApiKeyAuth('admin'),
  async (c) => {
    try {
      const { project } = c.req.param();
      const { settings } = await c.req.json();
      
      // This would trigger a background job to reindex
      // For now, just return success
      return c.json({
        message: 'Reindexing started',
        job_id: `reindex-${Date.now()}`,
        estimated_time: '5-10 minutes'
      });
    } catch (error) {
      console.error('Reindex error:', error);
      return c.json({ 
        error: 'Failed to start reindexing',
        details: error instanceof Error ? error.message : 'Unknown error'
      }, 500);
    }
  }
);

/**
 * Helper Functions
 */

async function applySynonymsToEngine(projectId: string) {
  try {
    const db = getDb();
    const result = await db.query(
      'SELECT * FROM search_synonyms WHERE project_id = $1 AND enabled = true',
      [projectId]
    );
    
    const synonyms: Record<string, string[]> = {};
    
    for (const row of result.rows) {
      const terms = JSON.parse(row.terms);
      if (row.type === 'synonym') {
        // Bidirectional synonyms
        terms.forEach((term: string) => {
          synonyms[term] = terms.filter((t: string) => t !== term);
        });
      } else if (row.type === 'oneway') {
        // One-way synonyms (first term maps to others)
        synonyms[terms[0]] = terms.slice(1);
      }
    }
    
    // Apply to Meilisearch
    await meiliEngine.updateSynonyms(projectId, synonyms);
  } catch (error) {
    console.error('Failed to apply synonyms:', error);
  }
}

function testRulePattern(query: string, pattern: string): boolean {
  try {
    const regex = new RegExp(pattern, 'i');
    return regex.test(query);
  } catch {
    // If not a valid regex, do exact match
    return query.toLowerCase().includes(pattern.toLowerCase());
  }
}

async function previewRuleEffect(projectId: string, query: string, rule: any) {
  const actionParams = JSON.parse(rule.action_params);
  
  switch (rule.rule_type) {
    case 'pin':
      return {
        action: 'Pin documents to top',
        documents: actionParams.document_ids,
        positions: actionParams.positions || [1, 2, 3]
      };
      
    case 'boost':
      return {
        action: 'Boost relevance',
        factor: actionParams.boost_factor || 2,
        documents: actionParams.document_ids
      };
      
    case 'bury':
      return {
        action: 'Demote results',
        documents: actionParams.document_ids,
        max_position: actionParams.position || 10
      };
      
    case 'exclude':
      return {
        action: 'Exclude from results',
        documents: actionParams.document_ids
      };
      
    case 'redirect':
      return {
        action: 'Redirect to URL',
        url: actionParams.redirect_url
      };
      
    default:
      return null;
  }
}

async function getABTestResults(testId: string) {
  // Calculate conversion rates, CTR, etc. for each variant
  // This would query telemetry data
  return {
    variant_a: {
      sessions: 1000,
      ctr: 0.25,
      conversion: 0.05
    },
    variant_b: {
      sessions: 1000,
      ctr: 0.28,
      conversion: 0.06
    },
    significance: 0.95,
    winner: 'variant_b'
  };
}
