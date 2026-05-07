'use client';

import { useEffect, useMemo, useState } from 'react';
import {
  CircleHelp,
  ClipboardList,
  ClipboardX,
  FileText,
  MessageSquareText,
  Package,
  PhoneCall,
  RadioTower,
  ReceiptText,
  Speech,
  Truck,
  UserRoundCheck,
  Wrench,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import type { CabinetRequestType } from '@alfasklad/api-client';
import { CallbackForm } from './CallbackForm';
import { GenericRequestForm } from './GenericRequestForm';
import { QuestionForm } from './QuestionForm';
import { RequestCard } from './RequestCard';
import { RequestModal } from './RequestModal';
import { ReviewForm } from './ReviewForm';
import type { RequestContactDefaults } from './types';

export type RequestTypeCode =
  | 'callback'
  | 'manager_order'
  | 'question'
  | 'review'
  | 'delivery'
  | 'request_upd'
  | 'request_invoice_email'
  | 'video'
  | 'storage'
  | 'repair'
  | 'cancel-contract'
  | 'board'
  | 'waiting-list';

type RequestItem = {
  code: RequestTypeCode;
  title: string;
  modalTitle: string;
  icon: LucideIcon;
};

const requestItems: RequestItem[] = [
  {
    code: 'callback',
    title: 'Заявка на обратный звонок',
    modalTitle: 'Заказать звонок',
    icon: PhoneCall,
  },
  {
    code: 'question',
    title: 'Задать вопрос',
    modalTitle: 'Задать вопрос',
    icon: CircleHelp,
  },
  {
    code: 'delivery',
    title: 'Заказать доставку',
    modalTitle: 'Заказать доставку',
    icon: Truck,
  },
  {
    code: 'request_upd',
    title: 'Запросить УПД',
    modalTitle: 'Запросить УПД',
    icon: FileText,
  },
  {
    code: 'request_invoice_email',
    title: 'Запросить счет на E-mail',
    modalTitle: 'Запросить счет на E-mail',
    icon: ReceiptText,
  },
  {
    code: 'manager_order',
    title: 'Заявка через менеджера',
    modalTitle: 'Заявка через менеджера',
    icon: UserRoundCheck,
  },
  {
    code: 'review',
    title: 'Оставить отзыв',
    modalTitle: 'Оставьте свой отзыв',
    icon: Speech,
  },
  {
    code: 'video',
    title: 'Заявка на установку видеонаблюдения',
    modalTitle: 'Заявка на установку видеонаблюдения',
    icon: RadioTower,
  },
  {
    code: 'storage',
    title: 'Хранение отдельных предметов',
    modalTitle: 'Хранение отдельных предметов',
    icon: Package,
  },
  {
    code: 'repair',
    title: 'Заявка на ремонт',
    modalTitle: 'Заявка на ремонт',
    icon: Wrench,
  },
  {
    code: 'cancel-contract',
    title: 'Заявка на расторжение договора',
    modalTitle: 'Заявка на расторжение договора',
    icon: ClipboardX,
  },
  {
    code: 'board',
    title: 'Доска объявлений',
    modalTitle: 'Доска объявлений',
    icon: MessageSquareText,
  },
  {
    code: 'waiting-list',
    title: 'Лист ожидания',
    modalTitle: 'Лист ожидания',
    icon: ClipboardList,
  },
];

type RequestGridProps = {
  initialType?: string;
  contactDefaults: RequestContactDefaults;
};

function normalizeType(type?: string): RequestTypeCode | null {
  if (!type) {
    return null;
  }

  const item = requestItems.find((requestItem) => requestItem.code === type);

  return item?.code ?? null;
}

export function RequestGrid({
  initialType,
  contactDefaults,
}: RequestGridProps) {
  const [activeType, setActiveType] = useState<RequestTypeCode | null>(null);

  useEffect(() => {
    setActiveType(normalizeType(initialType));
  }, [initialType]);

  const activeItem = useMemo(() => {
    if (!activeType) {
      return null;
    }

    return requestItems.find((item) => item.code === activeType) ?? null;
  }, [activeType]);

  function closeModal() {
    setActiveType(null);
  }

  return (
    <>
      <section className="divide-y divide-[#eceff3] border border-[#eceff3] bg-white">
        {requestItems.map((item) => (
          <RequestCard
            key={item.code}
            title={item.title}
            icon={item.icon}
            onClick={() => setActiveType(item.code)}
          />
        ))}
      </section>

      {activeItem ? (
        <RequestModal title={activeItem.modalTitle} onClose={closeModal}>
          {activeItem.code === 'callback' ? (
            <CallbackForm defaults={contactDefaults} onDone={closeModal} />
          ) : null}
          {activeItem.code === 'question' ? (
            <QuestionForm defaults={contactDefaults} onDone={closeModal} />
          ) : null}
          {activeItem.code === 'review' ? (
            <ReviewForm defaults={contactDefaults} onDone={closeModal} />
          ) : null}

          {!['callback', 'question', 'review'].includes(activeItem.code) ? (
            <GenericRequestForm
              requestType={activeItem.code as CabinetRequestType}
              requestTitle={activeItem.title}
              defaults={contactDefaults}
              onDone={closeModal}
            />
          ) : null}
        </RequestModal>
      ) : null}
    </>
  );
}
