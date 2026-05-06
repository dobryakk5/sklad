'use client';

import { AlertTriangle, RotateCcw } from 'lucide-react';

type CabinetErrorStateProps = {
  title?: string;
  description?: string;
  onRetry?: () => void;
};

export function CabinetErrorState({
  title = 'Не удалось загрузить раздел',
  description = 'Попробуйте обновить страницу. Если ошибка повторяется, обратитесь к менеджеру.',
  onRetry,
}: CabinetErrorStateProps) {
  return (
    <section className="border border-[#eceff3] bg-white px-6 py-8 md:px-10">
      <div className="flex gap-4">
        <AlertTriangle
          size={32}
          className="mt-1 shrink-0 text-[#f45454]"
          strokeWidth={1.8}
        />

        <div>
          <h2 className="text-[22px] font-medium text-[#333]">
            {title}
          </h2>

          <p className="mt-3 max-w-[680px] text-[15px] leading-7 text-[#777]">
            {description}
          </p>

          {onRetry ? (
            <button
              type="button"
              onClick={onRetry}
              className="mt-6 inline-flex h-[44px] items-center gap-2 bg-[#f45454] px-6 text-[12px] font-semibold uppercase tracking-[0.08em] text-white transition hover:bg-[#e84545]"
            >
              <RotateCcw size={16} />
              Повторить
            </button>
          ) : null}
        </div>
      </div>
    </section>
  );
}
