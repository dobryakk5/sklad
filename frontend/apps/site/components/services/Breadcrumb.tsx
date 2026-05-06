import Link from 'next/link'

type BreadcrumbItem = {
  label: string
  href?: string
}

export default function Breadcrumb({ items }: { items: BreadcrumbItem[] }) {
  return (
    <nav className="svc-breadcrumb" aria-label="Хлебные крошки">
      <div className="container svc-breadcrumb-inner">
        {items.map((item, index) => (
          <span key={`${item.label}-${index}`} className="svc-breadcrumb-item">
            {index > 0 ? <span className="svc-breadcrumb-separator">/</span> : null}
            {item.href ? (
              <Link href={item.href} className="svc-breadcrumb-link">
                {item.label}
              </Link>
            ) : (
              <span className="svc-breadcrumb-current">{item.label}</span>
            )}
          </span>
        ))}
      </div>
    </nav>
  )
}
