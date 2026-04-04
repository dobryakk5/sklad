import type { NextConfig } from 'next'

const nextConfig: NextConfig = {
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'alfasklad.ru',
      },
    ],
  },
}

export default nextConfig
