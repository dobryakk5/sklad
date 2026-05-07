function withoutTrailingSlash(url: string) {
  return url.replace(/\/+$/, '');
}

function withTrailingSlash(url: string) {
  return `${withoutTrailingSlash(url)}/`;
}

export const SITE_URL = withTrailingSlash(
  process.env.NEXT_PUBLIC_SITE_URL ?? 'https://alfasklad.ru/',
);
