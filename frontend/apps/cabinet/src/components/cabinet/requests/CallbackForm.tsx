'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { Field, SubmitButton } from './RequestFormFields';

type CallbackFormProps = {
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function CallbackForm({ defaults, onDone }: CallbackFormProps) {
  const [consent, setConsent] = useState(false);
  const [message, setMessage] = useState('');

  function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!consent) {
      setMessage('Нужно согласие на обработку персональных данных.');
      return;
    }

    setMessage('Отправка обращений пока не подключена. Данные не были отправлены.');
  }

  return (
    <form onSubmit={handleSubmit}>
      <p className="mb-6 text-[14px] leading-6 text-[#777]">
        Наши менеджеры на связи ежедневно с 8:30 до 20:30. Оставьте ваши
        контактные данные, мы обязательно вам позвоним.
      </p>

      <Field
        label="Ваше имя"
        requiredMark
        defaultValue={defaults.name ?? ''}
      />

      <Field
        label="Телефон"
        requiredMark
        defaultValue={defaults.phone ?? ''}
      />

      <Field
        label="Укажите удобное время для звонка"
        placeholder=""
      />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton>Жду звонка!</SubmitButton>
    </form>
  );
}
