'use client';

type ConsentToggleProps = {
  checked: boolean;
  onChange: (checked: boolean) => void;
};

export function ConsentToggle({ checked, onChange }: ConsentToggleProps) {
  return (
    <label className="mt-5 flex cursor-pointer items-center gap-3 text-[13px] text-[#777]">
      <button
        type="button"
        onClick={() => onChange(!checked)}
        className={[
          'relative h-[14px] w-[26px] rounded-full transition',
          checked ? 'bg-[#f45454]' : 'bg-[#d5dce4]',
        ].join(' ')}
        aria-pressed={checked}
      >
        <span
          className={[
            'absolute top-[2px] size-[10px] rounded-full bg-white transition',
            checked ? 'left-[14px]' : 'left-[2px]',
          ].join(' ')}
        />
      </button>

      <span>
        Я согласен на{' '}
        <a href="/privacy" className="text-[#f45454] hover:underline">
          обработку персональных данных
        </a>
      </span>
    </label>
  );
}
