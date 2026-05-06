import type { ReactNode } from 'react';

type CabinetCardProps = {
  children: ReactNode;
};

export function CabinetCard({ children }: CabinetCardProps) {
  return (
    <section className="overflow-hidden rounded-[2px] border border-[#e6eaf0] bg-white shadow-[0_8px_24px_rgba(15,23,42,0.04)]">
      {children}
    </section>
  );
}
