import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  reactStrictMode: true,
  // Нет basePath — cabinet живёт в корне lk.alfasklad.ru
  // Multi-Zones не используется
  transpilePackages: ['@alfasklad/api-client'],
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: 'http://localhost:8000/api/:path*',
      },
    ];
  },
};

export default nextConfig;
