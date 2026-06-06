#!/usr/bin/env node

import { createServer } from 'http';
import { mkdir, readFile, writeFile } from 'fs/promises';
import { dirname, join } from 'path';
import { URL, fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const PORT = process.env.PORT || 8787;
const HOST = process.env.SEARCH_API_HOST || process.env.HOST || '0.0.0.0';
const IS_DEVELOPMENT = process.env.NODE_ENV !== 'production';
const API_READ_KEY = process.env.API_READ_KEY || (IS_DEVELOPMENT ? 'pc_read_dev_key' : '');
const API_WRITE_KEY = process.env.API_WRITE_KEY || (IS_DEVELOPMENT ? 'pc_write_dev_key' : '');
const DATA_DIR = process.env.SEARCH_DATA_DIR || join(__dirname, '.data');
const INDEX_FILE = join(DATA_DIR, 'search-index.json');
const MAX_REQUEST_BYTES = 2 * 1024 * 1024;
let searchIndex = {};

const DEMO_DOCUMENTS = [
    {
        id: 'demo-page-search',
        collection: 'pages',
        title: 'Pixelcoda Search Administration',
        summary: 'Premium TYPO3 Suche mit Autocomplete, Facetten, Pagination, Headless API und KI-gestuetzten Antworten.',
        content: 'Verwalte Suchindex, Rendering-Modus, API-Verbindung, Headless JSON und TYPO3 Standardausgabe zentral im Backend.',
        keywords: 'search suche autocomplete facets pagination headless typo3 api ki',
        url: '/',
        language: 'de',
        categories: ['Suche', 'Backend', 'Headless'],
        type: 'page',
        date: '2026-06-06'
    },
    {
        id: 'demo-content-autocomplete',
        collection: 'tt_content',
        title: 'Autocomplete und Suggestions',
        summary: 'Schnelle Suchvorschlaege waehrend der Eingabe mit Tastatursteuerung und barrierearmen Listbox-Rollen.',
        content: 'Autocomplete liefert Treffer aus Seiten und Inhaltselementen. Suggestions helfen Redakteuren und Besuchern, relevante Inhalte schneller zu finden.',
        keywords: 'autocomplete suggestions suchvorschlaege tastatur accessibility',
        url: '/#autocomplete',
        language: 'de',
        categories: ['UX', 'Accessibility'],
        type: 'content',
        date: '2026-06-05'
    },
    {
        id: 'demo-content-filters',
        collection: 'tt_content',
        title: 'Faceted Filters fuer TYPO3 Inhalte',
        summary: 'Filter nach Seiten, Inhaltstypen, Kategorien und Datum fuer praezise Suchergebnisse.',
        content: 'Faceted Search reduziert Rauschen und macht umfangreiche TYPO3 Websites schneller durchsuchbar.',
        keywords: 'faceted filters kategorien datum inhaltstyp',
        url: '/#filters',
        language: 'de',
        categories: ['Filter', 'UX'],
        type: 'content',
        date: '2026-06-04'
    },
    {
        id: 'demo-page-pagination',
        collection: 'pages',
        title: 'Pagination und Core Web Vitals',
        summary: 'Serverseitige Pagination haelt Payloads klein, stabilisiert INP und verbessert die wahrgenommene Geschwindigkeit.',
        content: 'Pixelcoda Search liefert Seiteninformationen und limitierte Ergebnislisten fuer performante Frontends.',
        keywords: 'pagination performance lighthouse core web vitals',
        url: '/#pagination',
        language: 'de',
        categories: ['Performance', 'SEO'],
        type: 'page',
        date: '2026-06-03'
    },
    {
        id: 'demo-page-headless',
        collection: 'pages',
        title: 'Headless JSON Output',
        summary: 'Stabile JSON API fuer React, Vue, Next.js und klassische TYPO3 Frontends.',
        content: 'Der Headless-Modus liefert strukturierte Suchdaten ohne das klassische Fluid Rendering zu brechen.',
        keywords: 'headless json api react vue nextjs typo3',
        url: '/#headless',
        language: 'de',
        categories: ['Headless', 'API'],
        type: 'page',
        date: '2026-06-02'
    },
    {
        id: 'demo-content-ai',
        collection: 'tt_content',
        title: 'KI-gestuetzte Antworten mit Quellen',
        summary: 'Antworten werden aus dem Suchindex erzeugt und mit Quellen aus TYPO3 Inhalten belegt.',
        content: 'Die Ask-Funktion nutzt relevante Passagen aus dem Index und bleibt DSGVO-freundlich konfigurierbar.',
        keywords: 'ki ai antwort quellen citations dsgvo',
        url: '/#ai-answer',
        language: 'de',
        categories: ['AI', 'Privacy'],
        type: 'content',
        date: '2026-06-01'
    },
    {
        id: 'demo-page-bitv',
        collection: 'pages',
        title: 'BITV und Accessibility',
        summary: 'Semantische Suche, sichtbare Fokuszustaende, reduzierte Bewegung und Tastaturbedienung.',
        content: 'Die Oberflaeche ist auf Accessibility, reduzierte Bewegung und klare Screenreader-Strukturen ausgelegt.',
        keywords: 'bitv accessibility screenreader tastatur reduced motion',
        url: '/#accessibility',
        language: 'de',
        categories: ['Accessibility', 'BITV'],
        type: 'page',
        date: '2026-05-31'
    },
    {
        id: 'demo-content-railway',
        collection: 'tt_content',
        title: 'Railway Demo Deployment',
        summary: 'TYPO3 Suite mit Search API, GSAP Animation und Frontend Editing in einer Testumgebung.',
        content: 'Railway startet TYPO3, MySQL-Anbindung, Such-API und Demo-Redakteur automatisch.',
        keywords: 'railway deployment typo3 demo redakteur',
        url: '/#railway',
        language: 'de',
        categories: ['Deployment', 'Demo'],
        type: 'content',
        date: '2026-05-30'
    }
];

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

    searchIndex.typo3 ||= {};
    for (const document of DEMO_DOCUMENTS) {
        searchIndex.typo3[document.collection] ||= {};
        searchIndex.typo3[document.collection][document.id] ||= {
            ...document,
            updated_at: new Date().toISOString()
        };
    }

    const hasAllDemoDocuments = DEMO_DOCUMENTS.every(
        document => searchIndex.typo3?.[document.collection]?.[document.id]
    );
    if (hasAllDemoDocuments) {
        await persistIndex();
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
    const category = String(payload.category || '').toLocaleLowerCase();
    const contentType = String(payload.content_type || payload.contentType || 'all');
    const sort = String(payload.sort || 'relevance');
    const page = Math.max(1, Number(payload.page) || 1);
    const perPage = Math.max(1, Math.min(Number(payload.per_page || payload.limit) || 10, 100));
    const ranked = getProjectDocuments(project)
        .filter(document => collections.length === 0 || collections.includes(document.collection))
        .filter(document => !category || (document.categories || []).some(item => String(item).toLocaleLowerCase() === category))
        .filter(document => contentType === 'all' || !contentType || document.type === contentType || document.collection === contentType)
        .map(document => ({ document, score: scoreDocument(document, queryTokens) }))
        .filter(result => queryTokens.length === 0 || result.score > 0)
        .sort((a, b) => {
            if (sort === 'date_desc') {
                return String(b.document.date || '').localeCompare(String(a.document.date || ''));
            }
            if (sort === 'date_asc') {
                return String(a.document.date || '').localeCompare(String(b.document.date || ''));
            }
            if (sort === 'title') {
                return String(a.document.title).localeCompare(String(b.document.title));
            }

            return b.score - a.score || String(a.document.title).localeCompare(String(b.document.title));
        });
    const total = ranked.length;
    const pages = Math.max(1, Math.ceil(total / perPage));
    const offset = (Math.min(page, pages) - 1) * perPage;

    return {
        results: ranked.slice(offset, offset + perPage),
        pagination: { page: Math.min(page, pages), pages, count: Math.min(perPage, Math.max(total - offset, 0)), total },
        facets: buildFacets(ranked.map(result => result.document))
    };
}

function buildFacets(documents) {
    const collections = {};
    const categories = {};
    for (const document of documents) {
        collections[document.collection] = (collections[document.collection] || 0) + 1;
        for (const category of document.categories || []) {
            categories[category] = (categories[category] || 0) + 1;
        }
    }

    return { collections, categories };
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
            sendJson(res, 200, jsonApiResponse(matches.results.map(({ document, score }) => ({
                type: 'searchResult',
                id: document.id,
                attributes: { ...document, score: normalizeScore(score, body.q) },
                meta: { relevance: normalizeScore(score, body.q), collection: document.collection }
            })), [], {
                pagination: matches.pagination,
                facets: matches.facets,
                search: { query: body.q || '', collections: body.collections || [] }
            }));
            return;
        }

        if (path.match(/^\/v1\/suggest\//) && method === 'POST') {
            if (!checkAuth(req, [API_READ_KEY, API_WRITE_KEY])) {
                sendJson(res, 401, { error: 'Valid read API key required' });
                return;
            }
            const project = pathParts[3];
            const body = await getRequestBody(req);
            const queryTokens = tokenize(body.q);
            const limit = Math.max(1, Math.min(Number(body.limit) || 8, 20));
            const suggestions = getProjectDocuments(project)
                .map(document => ({ document, score: scoreDocument(document, queryTokens) }))
                .filter(result => queryTokens.length === 0 || result.score > 0)
                .sort((a, b) => b.score - a.score || String(a.document.title).localeCompare(String(b.document.title)))
                .slice(0, limit)
                .map(({ document }) => ({
                    type: document.collection === 'pages' ? 'page' : 'content',
                    id: document.id,
                    title: document.title,
                    subtitle: document.summary,
                    url: document.url || '/'
                }));
            sendJson(res, 200, jsonApiResponse(suggestions));
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
            const matches = searchDocuments(project, { ...body, q: question, per_page: body.maxPassages || 6 });
            const citations = matches.results.map(({ document }, index) => ({
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
            const answer = matches.results.length > 0
                ? `Zu "${question}" wurden ${matches.results.length} relevante Inhalte gefunden: ${matches.results.map(({ document }, index) => `[${index + 1}] ${document.title}: ${String(document.summary || document.content || '').slice(0, 180)}`).join(' ')}`
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
                generation: { search_method: 'local-keyword', passages_found: matches.results.length, citations_count: citations.length }
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
server.listen(PORT, HOST, () => {
    const displayHost = HOST === '0.0.0.0' ? 'localhost' : HOST;
    console.log(`pixelcoda Search API running on http://${displayHost}:${PORT}`);
});

process.on('SIGTERM', () => server.close());
process.on('SIGINT', () => {
    server.close();
    process.exit(0);
});
