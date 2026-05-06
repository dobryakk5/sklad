'use client';

import type { ReactNode } from 'react';
import { X } from 'lucide-react';

type RequestModalProps = {
  title: string;
  children: ReactNode;
  onClose: () => void;
};

export function RequestModal({ title, children, onClose }: RequestModalProps) {
  return (
    <div className="fixed inset-0 z-50 flex justify-end bg-black/35 max-sm:block">
      <div className="h-full w-[430px] max-w-full overflow-y-auto bg-white px-12 py-8 shadow-xl max-sm:w-full max-sm:px-6">
        <div className="mb-7 flex items-start justify-between gap-4">
          <h2 className="text-[28px] font-normal leading-tight text-[#333] max-sm:text-[24px]">
            {title}
          </h2>

          <button
            type="button"
            onClick={onClose}
            className="mt-1 text-[#aaa] transition hover:text-[#333]"
            aria-label="Закрыть"
          >
            <X size={22} />
          </button>
        </div>

        {children}
      </div>
    </div>
  );
}
