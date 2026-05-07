'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { FileUpload } from './FileUpload';
import { Field, SubmitButton, TextareaField } from './RequestFormFields';
import { RatingStars } from './RatingStars';
import { requestErrorMessage, submitRequest } from './submitRequest';

type ReviewFormProps = {
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function ReviewForm({ defaults, onDone }: ReviewFormProps) {
  const [consent, setConsent] = useState(false);
  const [rating, setRating] = useState(0);
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
      await submitRequest('review', event.currentTarget, {
        request_title: 'Оставить отзыв',
        rating: rating > 0 ? String(rating) : '',
      });
      setMessage('Отзыв отправлен.');
      window.setTimeout(onDone, 900);
    } catch (error) {
      setMessage(requestErrorMessage(error));
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <form onSubmit={handleSubmit}>
      <Field
        label="Ваше имя"
        name="name"
        requiredMark
        defaultValue={defaults.name ?? ''}
      />

      <Field label="Компания" name="company" />

      <FileUpload />

      <Field label="Склад" name="warehouse" requiredMark />

      <TextareaField label="Отзыв" name="message" requiredMark />

      <RatingStars value={rating} onChange={setRating} />

      <Field label="Id отзыва на Яндекс.Картах" name="yandex_review_id" />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton disabled={isSubmitting}>
        {isSubmitting ? 'Отправляем...' : 'Отправить'}
      </SubmitButton>
    </form>
  );
}
