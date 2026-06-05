#!/usr/bin/env node

import { createServer } from 'http';
import { mkdir, readFile, writeFile } from 'fs/promises';
import { dirname, join } from 'path';
import { URL, fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const PORT = process.env.PORT || 8787;
const IS_DEVELOPMENT = process.env.NODE_ENV !== 'production';
const API_READ_KEY = process.env.API_READ_KEY || (IS_DEVELOPMENT ? 'pc_read_dev_key' : '');
const API_WRITE_KEY = process.env.API_WRITE_KEY || (IS_DEVELOPMENT ? 'pc_write_dev_key' : '');
const DATA_DIR = process.env.SEARCH_DATA_DIR || join(__dirname, '.data');
const INDEX_FILE = join(DATA_DIR, 'search-index.json');
const MAX_REQUEST_BYTES = 2 * 1024 * 1024;
let searchIndex = {};

function checkAuth(req, requiredKey) {
    const authHeader = req.headers.authorization || req.headers['x-api-key'] || '';
    const providedKey = authHeader.replace('Bearer ', '').replace('ApiKey ', '');
    const acceptedKeys = Array.isArray(requiredKey) ? requiredKey : [requiredKey];
    return acceptedKeys.filter(Boolean).includes(providedKey) || IS_DEVELOPMENT;
}

function jsonApiResponse(data, included = [], meta = {}) {
    return { data, included, meta, jsonapi: { version: '1.0' } };
}

function getRequestBody(req) {
    return new Promise((resolve, reject) => {
        let body = '';
        req.on('data', chunk => {
            body += chunk;
            if (Buffer.byteLength(body) > MAX_REQUEST_BYTES) {
                reject(new Error('Request body exceeds 2 MB limit'));
                req.destroy();
            }
        });
        req.on('end', () => {
            try {
                resolve(body ? JSON.parse(body) : {});
            } catch (error) {
                reject(error);
            }
        });
        req.on('error', reject);
    });
}

async function loadIndex() {
    try {
        searchIndex = JSON.parse(await readFile(INDEX_FILE, 'utf8'));
    } catch {
        searchIndex = {};
    }
}

async function persistIndex() {
    await mkdir(DATA_DIR, { recursive: true });
    await writeFile(INDEX_FILE, JSON.stringify(searchIndex, null, 2));
}

function tokenize(value) {
    return String(value || '')
        .toLocaleLowerCase()
        .normalize('NFKD')
        .replace(/\p{Diacritic}/gu, '')
        .split(/[^\p{Letter}\p{Number}]+/u)
        .filter(Boolean);
}

function getProjectDocuments(project) {
    return Object.values(searchIndex[project] || {}).flatMap(collection => Object.values(collection));
}

function scoreDocument(document, queryTokens) {
    const titleTokens = tokenize(document.title);
    const contentTokens = tokenize(`${document.summary || ''} ${document.content || ''} ${document.keywords || ''}`);
    return queryTokens.reduce((score, token) => {
        const titleMatches = titleTokens.filter(word => word.includes(token)).length;
        const contentMatches = contentTokens.filter(word => word.includes(token)).length;
        return score + (titleMatches * 5) + contentMatches;
    }, 0);
}

function searchDocuments(project, payload) {
    const queryTokens = tokenize(payload.q);
    const collections = Array.isArray(payload.collections) ? payload.collections : [];
    const limit = Math.max(1, Math.min(Number(payload.limit) || 10, 100));
    return getProjectDocuments(project)
        .filter(document => collections.length === 0 || collections.includes(document.collection))
        .map(document => ({ document, score: scoreDocument(document, queryTokens) }))
        .filter(result => queryTokens.length === 0 || result.score > 0)
        .sort((a, b) => b.score - a.score || String(a.document.title).localeCompare(String(b.document.title)))
        .slice(0, limit);
}

function normalizeScore(score, query) {
    return Math.min(1, score / Math.max(5, tokenize(query).length * 5));
}

function sendJson(res, status, payload) {
    res.writeHead(status);
    res.end(JSON.stringify(payload, null, 2));
}

function setCorsHeaders(req, res) {
    const configuredOrigins = (process.env.CORS_ALLOWED_ORIGINS || process.env.CORS_ORIGINS || '*')
        .split(',')
        .map(origin => origin.trim())
        .filter(Boolean);
    const requestOrigin = req.headers.origin;
    if (configuredOrigins.includes('*')) {
        res.setHeader('Access-Control-Allow-Origin', '*');
    } else if (requestOrigin && configuredOrigins.includes(requestOrigin)) {
        res.setHeader('Access-Control-Allow-Origin', requestOrigin);
        res.setHeader('Vary', 'Origin');
    }
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-API-Key');
}

const server = createServer(async (req, res) => {
    setCorsHeaders(req, res);
    res.setHeader('Content-Type', 'application/vnd.api+json');

    if (req.method === 'OPTIONS') {
        res.writeHead(204);
        res.end();
        return;
    }

    const url = new URL(req.url, `http://localhost:${PORT}`);
    const path = url.pathname;
    const method = req.method;
    const pathParts = path.split('/');
    console.log(`${new Date().toISOString()} ${method} ${path}`);

    try {
        if (path === '/' && method === 'GET') {
            res.setHeader('Content-Type', 'text/html; charset=utf-8');
            res.writeHead(200);
            res.end(await readFile(join(__dirname, 'index.html'), 'utf8'));
            return;
        }

        if (path === '/health' && method === 'GET') {
            sendJson(res, 200, {
                ok: true,
                service: 'pixelcoda-search-api',
                mode: 'persistent-local',
                documents: getProjectDocuments('typo3').length,
                timestamp: new Date().toISOString()
            });
            return;
        }

        if (path.match(/^\/v1\/search\//) && method === 'POST') {
            if (!checkAuth(req, [API_READ_KEY, API_WRITE_KEY])) {
                sendJson(res, 401, { error: 'Valid read API key required' });
                return;
            }
            const project = pathParts[3];
            const body = await getRequestBody(req);
            const matches = searchDocuments(project, body);
            sendJson(res, 200, jsonApiResponse(matches.map(({ document, score }) => ({
                type: 'searchResult',
                id: document.id,
                attributes: { ...document, score: normalizeScore(score, body.q) },
                meta: { relevance: normalizeScore(score, body.q), collection: document.collection }
            })), [], {
                pagination: { page: 1, pages: 1, count: matches.length, total: matches.length },
                search: { query: body.q || '', collections: body.collections || [] }
            }));
            return;
        }

        if (path.match(/^\/v1\/ask\//) && method === 'POST') {
            if (!checkAuth(req, [API_READ_KEY, API_WRITE_KEY])) {
                sendJson(res, 401, { error: 'Valid read API key required' });
                return;
            }
            const project = pathParts[3];
            const body = await getRequestBody(req);
            const question = body.q || '';
            const matches = searchDocuments(project, { ...body, q: question, limit: body.maxPassages || 6 });
            const citations = matches.map(({ document }, index) => ({
                type: 'citation',
                id: `citation-${index}`,
                attributes: {
                    title: document.title,
                    url: document.url || '/',
                    snippet: String(document.summary || document.content || '').slice(0, 240),
                    collection: document.collection,
                    reference: `[${index + 1}]`
                }
            }));
            const answer = matches.length > 0
                ? `Zu "${question}" wurden ${matches.length} relevante Inhalte gefunden: ${matches.map(({ document }, index) => `[${index + 1}] ${document.title}: ${String(document.summary || document.content || '').slice(0, 180)}`).join(' ')}`
                : `Zu "${question}" wurden im aktuellen Suchindex keine relevanten Inhalte gefunden.`;
            sendJson(res, 200, jsonApiResponse({
                type: 'answer',
                id: `answer-${Date.now()}`,
                attributes: {
                    text: answer,
                    query: question,
                    generated_at: new Date().toISOString(),
                    confidence: matches.length > 0 ? 0.8 : 0,
                    search_method: 'local-keyword'
                },
                relationships: {
                    citations: { data: citations.map(citation => ({ type: citation.type, id: citation.id })) }
                }
            }, citations, {
                generation: { search_method: 'local-keyword', passages_found: matches.length, citations_count: citations.length }
            }));
            return;
        }

        if (path.match(/^\/v1\/index\//) && method === 'POST') {
            if (!checkAuth(req, API_WRITE_KEY)) {
                sendJson(res, 401, { error: 'Valid write API key required' });
                return;
            }
            const project = pathParts[3];
            const collection = pathParts[4];
            const body = await getRequestBody(req);
            searchIndex[project] ||= {};
            searchIndex[project][collection] ||= {};
            for (const document of body.documents || []) {
                searchIndex[project][collection][String(document.id)] = {
                    ...document,
                    id: String(document.id),
                    collection,
                    updated_at: new Date().toISOString()
                };
            }
            await persistIndex();
            sendJson(res, 200, { success: true, processed: body.documents?.length || 0 });
            return;
        }

        if (path.match(/^\/v1\/index\//) && method === 'DELETE') {
            if (!checkAuth(req, API_WRITE_KEY)) {
                sendJson(res, 401, { error: 'Valid write API key required' });
                return;
            }
            const project = pathParts[3];
            const collection = pathParts[4];
            const body = await getRequestBody(req);
            if (body.all === true) {
                searchIndex[project] ||= {};
                searchIndex[project][collection] = {};
            } else {
                for (const id of body.ids || []) {
                    delete searchIndex[project]?.[collection]?.[String(id)];
                }
            }
            await persistIndex();
            sendJson(res, 200, { success: true });
            return;
        }

        if (path.match(/^\/v1\/index\//) && method === 'GET') {
            const project = pathParts[3];
            const collection = pathParts[4];
            const documents = Object.values(searchIndex[project]?.[collection] || {});
            sendJson(res, 200, { project, collection, documents: documents.length });
            return;
        }

        sendJson(res, 404, { error: `Route ${path} not found` });
    } catch (error) {
        console.error('Request error:', error);
        sendJson(res, 500, { error: error.message });
    }
});

if (!IS_DEVELOPMENT && (!API_READ_KEY || !API_WRITE_KEY)) {
    throw new Error('API_READ_KEY and API_WRITE_KEY are required in production');
}

await loadIndex();
server.listen(PORT, () => {
    console.log(`pixelcoda Search API running on http://localhost:${PORT}`);
});

process.on('SIGTERM', () => server.close());
process.on('SIGINT', () => {
    server.close();
    process.exit(0);
});
