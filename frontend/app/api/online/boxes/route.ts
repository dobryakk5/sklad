import { NextRequest, NextResponse } from 'next/server'
import { getBoxes } from '@/lib/api'

function parsePositiveInt(value: string | null): number | null {
  if (!value) {
    return null
  }

  const parsed = Number.parseInt(value, 10)

  if (!Number.isFinite(parsed) || parsed < 1) {
    return null
  }

  return parsed
}

export async function GET(request: NextRequest) {
  const query = request.nextUrl.searchParams
  const stockId = parsePositiveInt(query.get('stock_id'))
  const perPage = Math.min(parsePositiveInt(query.get('per_page')) ?? 100, 100)

  if (!stockId) {
    return NextResponse.json({ error: 'stock_id is required' }, { status: 400 })
  }

  try {
    const result = await getBoxes({
      stock_id: stockId,
      per_page: perPage,
    })

    return NextResponse.json({
      data: result.data,
      meta: {
        total: result.total,
        page: 1,
        per_page: perPage,
      },
    })
  } catch (error) {
    return NextResponse.json(
      {
        error: error instanceof Error ? error.message : 'Failed to fetch boxes',
      },
      { status: 502 },
    )
  }
}
