'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { FileUpload } from './FileUpload';
import { Field, SubmitButton, TextareaField } from './RequestFormFields';
import { RatingStars } from './RatingStars';

type ReviewFormProps = {
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function ReviewForm({ defaults, onDone }: ReviewFormProps) {
  const [consent, setConsent] = useState(false);
  const [rating, setRating] = useState(0);
  const [message, setMessage] = useState('');

  function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!consent) {
      setMessage('Нужно согласие на обработку персональных данных.');
      return;
    }

    setMessage('Отправка отзывов пока не подключена. Данные не были отправлены.');
  }

  return (
    <form onSubmit={handleSubmit}>
      <Field
        label="Ваше имя"
        requiredMark
        defaultValue={defaults.name ?? ''}
      />

      <Field label="Компания" />

      <FileUpload />

      <Field label="Склад" requiredMark />

      <TextareaField label="Отзыв" requiredMark />

      <RatingStars value={rating} onChange={setRating} />

      <Field label="Id отзыва на Яндекс.Картах" />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton>Отправить</SubmitButton>
    </form>
  );
}
