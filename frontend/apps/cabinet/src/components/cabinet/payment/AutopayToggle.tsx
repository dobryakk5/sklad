type AutopayToggleProps = {
  checked: boolean;
};

export function AutopayToggle({ checked }: AutopayToggleProps) {
  return (
    <div className="flex items-center gap-3">
      <div
        className={[
          'relative h-[24px] w-[46px] rounded-full',
          checked ? 'bg-[#f45454]' : 'bg-[#d5dce4]',
        ].join(' ')}
        aria-hidden="true"
      >
        <span
          className={[
            'absolute top-[3px] size-[18px] rounded-full bg-white shadow-sm',
            checked ? 'left-[25px]' : 'left-[3px]',
          ].join(' ')}
        />
      </div>
      <span className="text-[13px] text-[#8a93a1]">Только просмотр</span>
    </div>
  );
}
