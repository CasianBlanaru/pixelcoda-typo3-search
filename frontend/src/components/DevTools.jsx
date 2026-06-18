'use client';

import dynamic from 'next/dynamic';
import { useEffect, useState } from 'react';
import { normalizeContentColumns } from '../lib/typo3';

const HeadlessDevTools = dynamic(
  () => import('@pixelcoda/headless-nextjs').then((module) => module.HeadlessDevTools),
  { ssr: false }
);

export default function DevTools({ page }) {
  const enabled = process.env.NEXT_PUBLIC_HEADLESS_DEVTOOLS === 'true';
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  if (!enabled || !mounted) return null;

  const pageData = page
    ? {
        ...page,
        content: normalizeContentColumns(page.content),
      }
    : page;

  return <HeadlessDevTools pageData={pageData} />;
}
