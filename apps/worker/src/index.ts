import 'dotenv/config';
import { crawl } from './jobs/crawl.js';
import { extract } from './jobs/extract.js';
import { chunk, smartChunk } from './jobs/chunk.js';
import { embedJob } from './jobs/embed.js';
import { upsert } from './jobs/upsert.js';

export interface IngestOptions {
  collection?: string;
  contentType?: 'article' | 'documentation' | 'code' | 'general';
  enableEmbedding?: boolean;
  chunkOptions?: {
    maxLength?: number;
    overlap?: number;
    preserveSentences?: boolean;
  };
  embedOptions?: {
    batchSize?: number;
    maxRetries?: number;
  };
}

async function ingest(url: string, project: string, options: IngestOptions = {}) {
  const {
    collection = 'pages',
    contentType = 'general',
    enableEmbedding = true,
    chunkOptions = {},
    embedOptions = {}
  } = options;

  console.log(`Starting ingest pipeline for: ${url}`);
  console.log(`Project: ${project}, Collection: ${collection}, Content Type: ${contentType}`);

  try {
    // Step 1: Crawl the URL
    console.log('Step 1: Crawling...');
    const crawlResult = await crawl(url);
    
    // Step 2: Extract content
    console.log('Step 2: Extracting content...');
    const doc = await extract(crawlResult.html, crawlResult.finalUrl || url);
    
    // Step 3: Create chunks
    console.log('Step 3: Creating chunks...');
    const chunks = smartChunk(doc.content, doc.id, contentType, chunkOptions);
    
    if (chunks.length === 0) {
      console.warn('No chunks created, skipping embedding and upsert');
      return;
    }
    
    // Step 4: Generate embeddings (if enabled)
    let embeddedChunks = [];
    if (enableEmbedding) {
      console.log('Step 4: Generating embeddings...');
      embeddedChunks = await embedJob(chunks, embedOptions);
      
      if (embeddedChunks.length === 0) {
        console.warn('No embeddings generated, proceeding without vector search capability');
      }
    } else {
      console.log('Step 4: Skipping embeddings (disabled)');
      // Convert chunks to embedded format without embeddings
      embeddedChunks = chunks.map(chunk => ({
        ...chunk,
        embedding: [],
        embeddingModel: 'none',
        embeddingDimension: 0
      }));
    }
    
    // Step 5: Upsert to API
    console.log('Step 5: Upserting to API...');
    const result = await upsert(project, collection, doc, embeddedChunks, {
      upsertChunks: enableEmbedding && embeddedChunks.length > 0
    });
    
    // Report results
    console.log('\n=== Ingest Results ===');
    console.log(`Document: ${result.document.success ? 'SUCCESS' : 'FAILED'}`);
    if (result.document.error) {
      console.log(`Document Error: ${result.document.error}`);
    }
    
    if (enableEmbedding) {
      console.log(`Chunks: ${result.chunks.successful} successful, ${result.chunks.failed} failed`);
      if (result.chunks.errors.length > 0) {
        console.log('Chunk Errors:');
        result.chunks.errors.forEach(error => {
          console.log(`  - ${error.id}: ${error.error}`);
        });
      }
    }
    
    console.log(`\nIngest completed for: ${url}`);
    
  } catch (error) {
    console.error(`\nIngest failed for ${url}:`, error);
    throw error;
  }
}

// CLI interface
async function main() {
  const args = process.argv.slice(2);
  
  if (args.length === 0 || args.includes('--help') || args.includes('-h')) {
    console.log(`
pixelcoda Search Worker - Ingest Pipeline

Usage:
  npm run dev -- <url> [project] [options]

Arguments:
  url       The URL to crawl and index
  project   Project ID (default: demo)

Options:
  --collection <name>     Collection name (default: pages)
  --content-type <type>   Content type: article, documentation, code, general (default: general)
  --no-embedding         Disable embedding generation
  --batch-size <n>       Embedding batch size (default: 10)
  --max-length <n>       Max chunk length (default: 800)
  --overlap <n>          Chunk overlap (default: 100)

Examples:
  npm run dev -- https://example.com demo
  npm run dev -- https://docs.example.com docs --collection documentation --content-type documentation
  npm run dev -- https://blog.example.com blog --collection articles --content-type article --batch-size 5
    `);
    process.exit(0);
  }

  const url = args[0];
  const project = args[1] || 'demo';
  
  if (!url) {
    console.error('Error: URL is required');
    process.exit(1);
  }

  // Parse options
  const options: IngestOptions = {
    collection: 'pages',
    contentType: 'general',
    enableEmbedding: true,
    chunkOptions: {},
    embedOptions: {}
  };

  for (let i = 2; i < args.length; i++) {
    const arg = args[i];
    const next = args[i + 1];

    switch (arg) {
      case '--collection':
        if (next) options.collection = next;
        i++;
        break;
      case '--content-type':
        if (next && ['article', 'documentation', 'code', 'general'].includes(next)) {
          options.contentType = next as any;
        }
        i++;
        break;
      case '--no-embedding':
        options.enableEmbedding = false;
        break;
      case '--batch-size':
        if (next && !isNaN(parseInt(next))) {
          options.embedOptions!.batchSize = parseInt(next);
        }
        i++;
        break;
      case '--max-length':
        if (next && !isNaN(parseInt(next))) {
          options.chunkOptions!.maxLength = parseInt(next);
        }
        i++;
        break;
      case '--overlap':
        if (next && !isNaN(parseInt(next))) {
          options.chunkOptions!.overlap = parseInt(next);
        }
        i++;
        break;
    }
  }

  try {
    await ingest(url, project, options);
    console.log('\n✅ Ingest pipeline completed successfully');
    process.exit(0);
  } catch (error) {
    console.error('\n❌ Ingest pipeline failed:', error);
    process.exit(1);
  }
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  main();
}
