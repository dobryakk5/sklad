'use client';

import type { FormEvent } from 'react';
import { useState } from 'react';
import type { CabinetRequestType } from '@alfasklad/api-client';
import type { RequestContactDefaults } from './types';
import { ConsentToggle } from './ConsentToggle';
import { Field, SubmitButton, TextareaField } from './RequestFormFields';
import { requestErrorMessage, submitRequest } from './submitRequest';

type GenericRequestFormProps = {
  requestType: CabinetRequestType;
  requestTitle: string;
  defaults: RequestContactDefaults;
  onDone: () => void;
};

export function GenericRequestForm({
  requestType,
  requestTitle,
  defaults,
  onDone,
}: GenericRequestFormProps) {
  const [consent, setConsent] = useState(false);
  const [message, setMessage] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const isInvoiceRequest = ['request_upd', 'request_invoice_email'].includes(requestType);
  const isDeliveryRequest = requestType === 'delivery';
  const isWaitingListRequest = requestType === 'waiting-list';

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!consent) {
      setMessage('Нужно согласие на обработку персональных данных.');
      return;
    }

    setIsSubmitting(true);
    setMessage('');

    try {
      await submitRequest(requestType, event.currentTarget, {
        request_title: requestTitle,
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
        Заполните форму по обращению «{requestTitle}». Менеджер свяжется с вами
        после обработки заявки.
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
        requiredMark={isInvoiceRequest}
        defaultValue={defaults.email ?? ''}
      />

      {isInvoiceRequest ? (
        <>
          <Field label="Номер счета" name="invoice_number" requiredMark />
          <Field label="Номер договора" name="contract_number" requiredMark />
        </>
      ) : null}

      {isDeliveryRequest ? (
        <>
          <Field label="Дата доставки" name="date" type="date" />
          <Field
            label="Тип доставки"
            name="action_type"
            placeholder="Везем на склад"
          />
          <TextareaField label="Откуда забрать" name="location_from" />
          <TextareaField label="Куда ехать" name="location_to" />
          <TextareaField label="Что перевозить" name="cargo" />
        </>
      ) : null}

      {isWaitingListRequest ? (
        <>
          <Field label="Склад" name="warehouse" />
          <Field label="Площадь бокса" name="square" />
        </>
      ) : null}

      <TextareaField label="Сообщение" name="message" requiredMark />

      <ConsentToggle checked={consent} onChange={setConsent} />

      {message ? <div className="mt-4 text-[13px] text-[#777]">{message}</div> : null}

      <SubmitButton disabled={isSubmitting}>
        {isSubmitting ? 'Отправляем...' : 'Отправить'}
      </SubmitButton>
    </form>
  );
}
