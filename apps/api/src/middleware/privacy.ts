/**
 * Privacy and Security Middleware
 * Implements GDPR compliance, PII redaction, and audit logging
 */

import { Context, Next } from 'hono';
import { getCookie } from 'hono/cookie';
import crypto from 'crypto';

/**
 * PII Redaction patterns
 */
const PII_PATTERNS = {
  email: /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/g,
  phone: /(\+\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/g,
  creditCard: /\b(?:\d[ -]*?){13,16}\b/g,
  ssn: /\b\d{3}-\d{2}-\d{4}\b/g,
  iban: /\b[A-Z]{2}\d{2}[A-Z0-9]{4}\d{7}([A-Z0-9]?){0,16}\b/g,
  ipv4: /\b(?:\d{1,3}\.){3}\d{1,3}\b/g,
  ipv6: /\b(?:[A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}\b/g
};

/**
 * Sensitive field names that should be redacted in logs
 */
const SENSITIVE_FIELDS = [
  'password',
  'token',
  'api_key',
  'apiKey',
  'secret',
  'authorization',
  'cookie',
  'session',
  'private_key',
  'privateKey'
];

/**
 * PII Redaction Middleware
 * Removes personally identifiable information from requests
 */
export function piiRedaction() {
  return async (c: Context, next: Next) => {
    // Skip redaction if explicitly disabled
    if (process.env.DISABLE_PII_REDACTION === 'true') {
      return next();
    }

    // Store original body for processing
    const originalBody = await c.req.text();
    
    if (originalBody) {
      try {
        const body = JSON.parse(originalBody);
        const redactedBody = redactPII(body);
        
        // Replace request body with redacted version
        c.req.raw = new Request(c.req.raw, {
          body: JSON.stringify(redactedBody)
        });
      } catch (error) {
        // If not JSON, apply text redaction
        const redactedText = redactTextPII(originalBody);
        c.req.raw = new Request(c.req.raw, {
          body: redactedText
        });
      }
    }

    // Redact query parameters
    const url = new URL(c.req.url);
    const params = new URLSearchParams(url.search);
    const redactedParams = new URLSearchParams();
    
    for (const [key, value] of params) {
      if (SENSITIVE_FIELDS.some(field => key.toLowerCase().includes(field))) {
        redactedParams.set(key, '[REDACTED]');
      } else {
        redactedParams.set(key, redactTextPII(value));
      }
    }
    
    if (redactedParams.toString() !== params.toString()) {
      url.search = redactedParams.toString();
      c.req.raw = new Request(url.toString(), c.req.raw);
    }

    return next();
  };
}

/**
 * HMAC Signature Verification
 * Verifies webhook signatures for secure communication
 */
export function hmacVerification(secret?: string) {
  const hmacSecret = secret || process.env.HMAC_SECRET;
  
  if (!hmacSecret) {
    throw new Error('HMAC secret not configured');
  }

  return async (c: Context, next: Next) => {
    // Skip for non-webhook endpoints
    if (!c.req.path.includes('/webhook')) {
      return next();
    }

    const signature = c.req.header('x-hmac-signature');
    const timestamp = c.req.header('x-timestamp');
    
    if (!signature || !timestamp) {
      return c.json({ error: 'Missing signature headers' }, 401);
    }

    // Check timestamp to prevent replay attacks
    const requestTime = parseInt(timestamp);
    const currentTime = Math.floor(Date.now() / 1000);
    const timeDiff = Math.abs(currentTime - requestTime);
    
    if (timeDiff > 300) { // 5 minutes tolerance
      return c.json({ error: 'Request timestamp too old' }, 401);
    }

    // Verify HMAC signature
    const body = await c.req.text();
    const payload = `${timestamp}.${body}`;
    const expectedSignature = crypto
      .createHmac('sha256', hmacSecret)
      .update(payload)
      .digest('hex');

    if (!crypto.timingSafeEqual(
      Buffer.from(signature),
      Buffer.from(expectedSignature)
    )) {
      return c.json({ error: 'Invalid signature' }, 401);
    }

    // Restore body for downstream processing
    c.req.raw = new Request(c.req.raw, { body });
    
    return next();
  };
}

/**
 * Audit Logging Middleware
 * Logs all API access for compliance and security
 */
export function auditLogging() {
  return async (c: Context, next: Next) => {
    const startTime = Date.now();
    const requestId = crypto.randomUUID();
    
    // Add request ID to context
    c.set('requestId', requestId);
    
    // Log request
    const auditEntry = {
      id: requestId,
      timestamp: new Date().toISOString(),
      method: c.req.method,
      path: c.req.path,
      ip: getClientIP(c),
      user_agent: c.req.header('user-agent'),
      api_key_id: c.get('apiKeyId'),
      project_id: c.req.param('project'),
      query: c.req.query()
    };

    // Process request
    await next();
    
    // Log response
    const responseTime = Date.now() - startTime;
    const completeAuditEntry = {
      ...auditEntry,
      response_status: c.res.status,
      response_time_ms: responseTime,
      completed_at: new Date().toISOString()
    };

    // Store audit log (async, don't block response)
    storeAuditLog(completeAuditEntry).catch(error => {
      console.error('Failed to store audit log:', error);
    });
  };
}

/**
 * Data Retention Policy Enforcement
 * Automatically removes old data based on retention policies
 */
export function dataRetention() {
  // Schedule periodic cleanup
  const retentionDays = parseInt(process.env.DATA_RETENTION_DAYS || '90');
  
  setInterval(async () => {
    try {
      await enforceDataRetention(retentionDays);
    } catch (error) {
      console.error('Data retention enforcement failed:', error);
    }
  }, 24 * 60 * 60 * 1000); // Run daily

  // Return middleware that adds retention headers
  return async (c: Context, next: Next) => {
    await next();
    
    // Add data retention headers
    c.header('X-Data-Retention-Days', retentionDays.toString());
    c.header('X-Data-Retention-Policy', 'automatic-deletion');
  };
}

/**
 * Consent Management
 * Ensures user consent for data processing
 */
export function consentManagement() {
  return async (c: Context, next: Next) => {
    // Check for consent header or cookie
    const consent = c.req.header('x-user-consent') || getCookie(c, 'gdpr_consent');
    
    // Skip consent check for public endpoints
    const publicPaths = ['/health', '/v1/search', '/v1/suggest'];
    if (publicPaths.some(path => c.req.path.startsWith(path))) {
      return next();
    }

    // Require consent for data collection endpoints
    const dataCollectionPaths = ['/v1/metrics', '/v1/ask'];
    if (dataCollectionPaths.some(path => c.req.path.startsWith(path))) {
      if (!consent || consent !== 'accepted') {
        return c.json({
          error: 'User consent required',
          consent_url: '/privacy/consent',
          privacy_policy: '/privacy/policy'
        }, 451); // 451 Unavailable For Legal Reasons
      }
    }

    return next();
  };
}

/**
 * Helper Functions
 */

function redactPII(obj: any): any {
  if (typeof obj === 'string') {
    return redactTextPII(obj);
  }
  
  if (Array.isArray(obj)) {
    return obj.map(item => redactPII(item));
  }
  
  if (typeof obj === 'object' && obj !== null) {
    const redacted: any = {};
    
    for (const [key, value] of Object.entries(obj)) {
      // Check if field name is sensitive
      if (SENSITIVE_FIELDS.some(field => key.toLowerCase().includes(field))) {
        redacted[key] = '[REDACTED]';
      } else {
        redacted[key] = redactPII(value);
      }
    }
    
    return redacted;
  }
  
  return obj;
}

function redactTextPII(text: string): string {
  let redacted = text;
  
  // Apply all PII patterns
  for (const [type, pattern] of Object.entries(PII_PATTERNS)) {
    redacted = redacted.replace(pattern, `[${type.toUpperCase()}_REDACTED]`);
  }
  
  return redacted;
}

function getClientIP(c: Context): string {
  // Get real IP from headers
  const forwarded = c.req.header('x-forwarded-for');
  if (forwarded) {
    return forwarded.split(',')[0].trim();
  }
  
  const realIP = c.req.header('x-real-ip');
  if (realIP) {
    return realIP;
  }
  
  // Hash IP for privacy
  const ip = c.env?.ip || '0.0.0.0';
  return hashIP(ip);
}

function hashIP(ip: string): string {
  const salt = process.env.IP_HASH_SALT || 'default-salt';
  return crypto
    .createHash('sha256')
    .update(ip + salt)
    .digest('hex')
    .substring(0, 16);
}

async function storeAuditLog(entry: any): Promise<void> {
  // Store in database or external audit service
  if (process.env.AUDIT_LOG_ENABLED === 'true') {
    const { getDb } = await import('../db.js');
    const db = getDb();
    
    await db.query(`
      INSERT INTO audit_logs 
      (id, timestamp, method, path, ip_hash, user_agent, api_key_id, 
       project_id, response_status, response_time_ms)
      VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
    `, [
      entry.id,
      entry.timestamp,
      entry.method,
      entry.path,
      entry.ip,
      entry.user_agent,
      entry.api_key_id,
      entry.project_id,
      entry.response_status,
      entry.response_time_ms
    ]);
  }
}

async function enforceDataRetention(days: number): Promise<void> {
  const { getDb } = await import('../db.js');
  const db = getDb();
  
  const cutoffDate = new Date();
  cutoffDate.setDate(cutoffDate.getDate() - days);
  
  // Delete old telemetry data
  await db.query(
    'DELETE FROM search_queries WHERE created_at < $1',
    [cutoffDate]
  );
  
  await db.query(
    'DELETE FROM search_clicks WHERE created_at < $1',
    [cutoffDate]
  );
  
  // Delete old audit logs
  await db.query(
    'DELETE FROM audit_logs WHERE timestamp < $1',
    [cutoffDate]
  );
  
  console.log(`Data retention: Deleted records older than ${days} days`);
}

/**
 * Export all privacy middleware as a single composed middleware
 */
export function privacyMiddleware() {
  return async (c: Context, next: Next) => {
    // Apply all privacy features in sequence
    await piiRedaction()(c, async () => {
      await consentManagement()(c, async () => {
        await auditLogging()(c, async () => {
          await dataRetention()(c, next);
        });
      });
    });
  };
}
