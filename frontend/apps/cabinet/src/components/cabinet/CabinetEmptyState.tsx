import type { ReactNode } from 'react';
import { Inbox } from 'lucide-react';

type CabinetEmptyStateProps = {
  title: string;
  description?: string;
  action?: ReactNode;
};

export function CabinetEmptyState({
  title,
  description,
  action,
}: CabinetEmptyStateProps) {
  return (
    <section className="border border-[#eceff3] bg-[#fafbfc] px-6 py-8 text-center">
      <Inbox
        size={36}
        className="mx-auto text-[#f45454]"
        strokeWidth={1.7}
      />

      <h3 className="mt-4 text-[20px] font-medium text-[#333]">
        {title}
      </h3>

      {description ? (
        <p className="mx-auto mt-2 max-w-[520px] text-[15px] leading-7 text-[#777]">
          {description}
        </p>
      ) : null}

      {action ? <div className="mt-5">{action}</div> : null}
    </section>
  );
}
