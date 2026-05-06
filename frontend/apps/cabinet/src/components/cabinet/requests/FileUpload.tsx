'use client';

import type { ChangeEvent } from 'react';
import { useRef, useState } from 'react';
import { Paperclip, X } from 'lucide-react';

type FileUploadProps = {
  label?: string;
};

export function FileUpload({ label = 'Прикрепить файл' }: FileUploadProps) {
  const inputRef = useRef<HTMLInputElement | null>(null);
  const [files, setFiles] = useState<File[]>([]);

  return (
    <div className="mt-5">
      <input
        ref={inputRef}
        type="file"
        className="hidden"
        multiple
        onChange={(event: ChangeEvent<HTMLInputElement>) => {
          const selectedFiles = Array.from(event.target.files ?? []);
          setFiles((current) => [...current, ...selectedFiles]);
          event.target.value = '';
        }}
      />

      <button
        type="button"
        onClick={() => inputRef.current?.click()}
        className="flex h-[44px] w-full items-center justify-between bg-[#f7f8fa] px-5 text-left text-[14px] text-[#555] transition hover:bg-[#f1f3f6]"
      >
        <span>{label}</span>
        <Paperclip size={18} className="text-[#777]" />
      </button>

      {files.length > 0 ? (
        <div className="mt-3 space-y-2">
          {files.map((file, index) => (
            <div
              key={`${file.name}-${index}`}
              className="flex items-center justify-between border border-[#eceff3] px-3 py-2 text-[13px] text-[#777]"
            >
              <span className="truncate">{file.name}</span>

              <button
                type="button"
                onClick={() =>
                  setFiles((current) => current.filter((_, itemIndex) => itemIndex !== index))
                }
                className="ml-3 shrink-0 text-[#aaa] hover:text-[#f45454]"
                aria-label="Удалить файл"
              >
                <X size={16} />
              </button>
            </div>
          ))}
        </div>
      ) : null}

      <button
        type="button"
        onClick={() => inputRef.current?.click()}
        className="mt-3 text-[11px] uppercase tracking-[0.1em] text-[#aaa] hover:text-[#f45454]"
      >
        + еще один файл
      </button>
    </div>
  );
}
