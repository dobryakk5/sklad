type BreadcrumbItem = {
  label: string;
};

type CabinetBreadcrumbsProps = {
  items: BreadcrumbItem[];
};

export function CabinetBreadcrumbs({ items }: CabinetBreadcrumbsProps) {
  if (items.length === 0) {
    return <span className="text-[13px] text-[#8a93a1]">Личный кабинет</span>;
  }

  return (
    <nav className="flex flex-wrap items-center gap-2 text-[13px] text-[#8a93a1]">
      <span>Личный кабинет</span>
      {items.map((item) => (
        <span key={item.label} className="flex items-center gap-2">
          <span>/</span>
          <span>{item.label}</span>
        </span>
      ))}
    </nav>
  );
}
