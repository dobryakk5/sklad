import type { NextConfig } from 'next';

const apiBaseUrl = (process.env.API_BASE_URL ?? 'http://localhost:8000/api').replace(/\/+$/, '');

const nextConfig: NextConfig = {
  reactStrictMode: true,
  // Нет basePath — cabinet живёт в корне lk.alfasklad.ru
  // Multi-Zones не используется
  transpilePackages: ['@alfasklad/api-client'],
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: `${apiBaseUrl}/:path*`,
      },
    ];
  },
};

export default nextConfig;
