import type {
  ButtonHTMLAttributes,
  InputHTMLAttributes,
  ReactNode,
  TextareaHTMLAttributes,
} from 'react';

type FieldProps = InputHTMLAttributes<HTMLInputElement> & {
  label: string;
  requiredMark?: boolean;
};

export function Field({ label, requiredMark, required, ...props }: FieldProps) {
  return (
    <label className="mt-4 block">
      <span className="mb-1 block text-[12px] text-[#999]">
        {label}
        {requiredMark ? <span className="text-[#f45454]"> *</span> : null}
      </span>

      <input
        {...props}
        required={required ?? requiredMark}
        className="h-[34px] w-full border-b border-[#d8dce2] bg-transparent text-[15px] text-[#444] outline-none transition placeholder:text-[#aaa] focus:border-[#f45454]"
      />
    </label>
  );
}

type TextareaFieldProps = TextareaHTMLAttributes<HTMLTextAreaElement> & {
  label: string;
  requiredMark?: boolean;
};

export function TextareaField({
  label,
  requiredMark,
  required,
  ...props
}: TextareaFieldProps) {
  return (
    <label className="mt-4 block">
      <span className="mb-1 block text-[12px] text-[#999]">
        {label}
        {requiredMark ? <span className="text-[#f45454]"> *</span> : null}
      </span>

      <textarea
        {...props}
        required={required ?? requiredMark}
        className="min-h-[88px] w-full resize-none border-b border-[#d8dce2] bg-transparent py-2 text-[15px] text-[#444] outline-none transition placeholder:text-[#aaa] focus:border-[#f45454]"
      />
    </label>
  );
}

type SubmitButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
  children: ReactNode;
};

export function SubmitButton({
  children,
  disabled,
  ...props
}: SubmitButtonProps) {
  return (
    <button
      {...props}
      type="submit"
      disabled={disabled}
      className="mt-8 h-[48px] bg-[#f45454] px-8 text-[13px] font-semibold uppercase tracking-[0.08em] text-white transition hover:bg-[#e84545] disabled:cursor-not-allowed disabled:bg-[#d8dce2]"
    >
      {children}
    </button>
  );
}
