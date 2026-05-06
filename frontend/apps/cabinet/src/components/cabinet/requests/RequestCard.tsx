import type { LucideIcon } from 'lucide-react';

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
      className="group flex min-h-[200px] flex-col items-center justify-center border-b border-r border-[#eceff3] px-6 py-8 text-center transition hover:bg-[#fafafa] hover:shadow-[0_12px_40px_rgba(0,0,0,0.08)]"
    >
      <Icon
        size={72}
        strokeWidth={1.5}
        className="mb-6 text-[#f45454] transition group-hover:scale-105"
      />

      <span className="max-w-[190px] text-[15px] leading-6 text-[#444]">
        {title}
      </span>
    </button>
  );
}
