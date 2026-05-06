'use client';

import type { ChangeEvent } from 'react';
import { Search, X } from 'lucide-react';

type WarehouseSearchProps = {
  value: string;
  onChange: (value: string) => void;
};

export function WarehouseSearch({ value, onChange }: WarehouseSearchProps) {
  return (
    <div className="relative">
      <Search
        size={18}
        className="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#aaa]"
      />

      <input
        value={value}
        onChange={(event: ChangeEvent<HTMLInputElement>) => onChange(event.target.value)}
        placeholder="Поиск по адресу, району или метро"
        className="h-[52px] w-full border border-[#d8dce2] bg-white pl-12 pr-12 text-[15px] outline-none transition placeholder:text-[#aaa] focus:border-[#f45454]"
      />

      {value ? (
        <button
          type="button"
          onClick={() => onChange('')}
          className="absolute right-4 top-1/2 -translate-y-1/2 text-[#aaa] transition hover:text-[#f45454]"
          aria-label="Очистить поиск"
        >
          <X size={18} />
        </button>
      ) : null}
    </div>
  );
}
