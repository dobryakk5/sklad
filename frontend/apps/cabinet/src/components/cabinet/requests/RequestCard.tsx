import type { LucideIcon } from 'lucide-react';
import { ChevronRight } from 'lucide-react';

type RequestCardProps = {
  title: string;
  icon: LucideIcon;
  onClick: () => void;
};

export function RequestCard({ title, icon: Icon, onClick }: RequestCardProps) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="group flex min-h-[76px] w-full items-center gap-4 px-5 py-4 text-left transition hover:bg-[#fafafa]"
    >
      <span className="flex size-11 shrink-0 items-center justify-center rounded-md bg-[#fff1f1] text-[#f45454] transition group-hover:bg-[#f45454] group-hover:text-white">
        <Icon
          size={24}
          strokeWidth={1.8}
        />
      </span>

      <span className="min-w-0 flex-1 text-[15px] leading-6 text-[#444]">
        {title}
      </span>

      <ChevronRight
        size={20}
        strokeWidth={1.8}
        className="shrink-0 text-[#b8bec8] transition group-hover:translate-x-0.5 group-hover:text-[#f45454]"
        aria-hidden="true"
      />
    </button>
  );
}
