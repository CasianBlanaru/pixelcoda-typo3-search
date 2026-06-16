/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    remotePatterns: [
      { protocol: 'http', hostname: 'localhost' },
      { protocol: 'https', hostname: 'localhost' },
      { protocol: 'https', hostname: 'web-production-e607a.up.railway.app' },
      { protocol: 'https', hostname: 'typo3-inst.localhost' },
      { protocol: 'http', hostname: 'typo3-inst.localhost' },
      // Support for TYPO3 assets in production and local dev
      { protocol: 'https', hostname: '**' },
    ],
    deviceSizes: [640, 750, 828, 1080, 1200, 1920, 2048, 3840],
    imageSizes: [16, 32, 48, 64, 96, 128, 256, 384],
  },
  // Explicitly support the skin environment variable
  env: {
    NEXT_PUBLIC_SKIN: process.env.NEXT_PUBLIC_SKIN || 'default',
  },
}
module.exports = nextConfig