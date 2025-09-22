export async function embed(text: string): Promise<number[]> {
  // STUB – replace with real provider call
  // Returns a deterministic pseudo-vector for dev
  const arr = new Array(16).fill(0).map((_,i)=>Math.sin((text.length+i)*0.123));
  return arr;
}

export async function generateAnswer(prompt: string): Promise<string> {
  // STUB – replace with real provider (OpenAI/local/etc.).
  // For dev, just return a trimmed version.
  return `Antwort (stub): ${prompt.slice(0, 300)} ...`;
}

export async function rerank(q: string, passages: {text:string}[]) {
  // STUB – identity ranking
  return passages;
}
