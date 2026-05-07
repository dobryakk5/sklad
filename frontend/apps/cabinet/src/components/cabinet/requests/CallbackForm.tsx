'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { Field, SubmitButton } from './RequestFormFields';
import { requestErrorMessage, submitRequest } from './submitRequest';

type CallbackFormProps = {
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function CallbackForm({ defaults, onDone }: CallbackFormProps) {
  const [consent, setConsent] = useState(false);
  const [message, setMessage] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!consent) {
      setMessage('Нужно согласие на обработку персональных данных.');
      return;
    }

    setIsSubmitting(true);
    setMessage('');

    try {
      await submitRequest('callback', event.currentTarget, {
        request_title: 'Заявка на обратный звонок',
      });
      setMessage('Обращение отправлено.');
      window.setTimeout(onDone, 900);
    } catch (error) {
      setMessage(requestErrorMessage(error));
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <form onSubmit={handleSubmit}>
      <p className="mb-6 text-[14px] leading-6 text-[#777]">
        Наши менеджеры на связи ежедневно с 8:30 до 20:30. Оставьте ваши
        контактные данные, мы обязательно вам позвоним.
      </p>

      <Field
        label="Ваше имя"
        name="name"
        requiredMark
        defaultValue={defaults.name ?? ''}
      />

      <Field
        label="Телефон"
        name="phone"
        requiredMark
        defaultValue={defaults.phone ?? ''}
      />

      <Field
        label="E-mail"
        name="email"
        type="email"
        requiredMark
        defaultValue={defaults.email ?? ''}
      />

      <Field
        label="Укажите удобное время для звонка"
        name="callback_time"
        placeholder=""
      />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton disabled={isSubmitting}>
        {isSubmitting ? 'Отправляем...' : 'Жду звонка!'}
      </SubmitButton>
    </form>
  );
}
