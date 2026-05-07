'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { Field, SubmitButton, TextareaField } from './RequestFormFields';
import { requestErrorMessage, submitRequest } from './submitRequest';

type QuestionFormProps = {
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function QuestionForm({ defaults, onDone }: QuestionFormProps) {
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
      await submitRequest('question', event.currentTarget, {
        request_title: 'Задать вопрос',
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
        Наши менеджеры с вами свяжутся, чтобы ответить на ваш вопрос.
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
        defaultValue={defaults.email ?? ''}
      />

      <Field label="Интересующий товар/услуга" name="service" />

      <TextareaField label="Сообщение" name="message" requiredMark />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton disabled={isSubmitting}>
        {isSubmitting ? 'Отправляем...' : 'Отправить'}
      </SubmitButton>
    </form>
  );
}
