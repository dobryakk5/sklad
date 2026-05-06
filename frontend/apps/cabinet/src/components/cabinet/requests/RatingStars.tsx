'use client';

import { Star } from 'lucide-react';

type RatingStarsProps = {
  value: number;
  onChange: (value: number) => void;
};

export function RatingStars({ value, onChange }: RatingStarsProps) {
  return (
    <div className="mt-4 flex items-center gap-2">
      <div className="flex items-center gap-1">
        {[1, 2, 3, 4, 5].map((item) => (
          <button
            key={item}
            type="button"
            onClick={() => onChange(item)}
            className={item <= value ? 'text-[#f45454]' : 'text-[#d8d8d8]'}
            aria-label={`Оценка ${item}`}
          >
            <Star size={22} fill="currentColor" />
          </button>
        ))}
      </div>

      <span className="text-[14px] text-[#aaa]">
        — {value > 0 ? `${value} из 5` : 'Без оценки'}
      </span>
    </div>
  );
}
