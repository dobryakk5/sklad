import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

// Пути, доступные без авторизации
const PUBLIC_PATHS = ['/login'];

export function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;

  if (PUBLIC_PATHS.some((p) => pathname.startsWith(p))) {
    return NextResponse.next();
  }

  // UX-редирект: проверяем наличие Sanctum cookie
  // Реальная проверка сессии — на Laravel при каждом /api/cabinet/* запросе
  const sessionCookie =
    request.cookies.get('laravel-session') ?? request.cookies.get('laravel_session');

  if (!sessionCookie) {
    const loginUrl = new URL('/login', request.url);
    // Сохраняем куда вернуть пользователя после входа
    if (pathname !== '/') {
      loginUrl.searchParams.set('from', pathname);
    }
    return NextResponse.redirect(loginUrl);
  }

  return NextResponse.next();
}

export const config = {
  // Не применять middleware к статике и _next
  matcher: ['/((?!_next/static|_next/image|favicon.ico|.*\\..*).*)'],
};
