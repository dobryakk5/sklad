'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { Field, SubmitButton, TextareaField } from './RequestFormFields';

type QuestionFormProps = {
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function QuestionForm({ defaults, onDone }: QuestionFormProps) {
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
        Наши менеджеры с вами свяжутся, чтобы ответить на ваш вопрос.
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
        label="E-mail"
        defaultValue={defaults.email ?? ''}
      />

      <Field label="Интересующий товар/услуга" />

      <TextareaField label="Сообщение" requiredMark />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton>Отправить</SubmitButton>
    </form>
  );
}
