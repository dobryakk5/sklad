import type { ReactNode } from 'react';

type CabinetPageTitleProps = {
  title: string;
  breadcrumbs?: ReactNode;
};

export function CabinetPageTitle({
  title,
  breadcrumbs,
}: CabinetPageTitleProps) {
  return (
    <header className="mb-6 rounded-[2px] border border-[#e6eaf0] bg-white px-5 py-5 shadow-[0_8px_24px_rgba(15,23,42,0.04)] sm:px-6">
      {breadcrumbs ? <div className="mb-3">{breadcrumbs}</div> : null}
      <h1 className="text-[26px] font-medium text-[#273142]">{title}</h1>
    </header>
  );
}
