// profile/page.tsx
// Профиль — API для профиля пока не реализован, используем mock
// TODO: добавить GET /api/cabinet/profile в Laravel когда понадобится

import { cookies } from 'next/headers';
import { getMe } from '@alfasklad/api-client';
import { CabinetBreadcrumbs } from '@/components/cabinet/CabinetBreadcrumbs';
import { CabinetPageTitle } from '@/components/cabinet/CabinetPageTitle';
import { ProfileReadonlyForm } from '@/components/cabinet/profile/ProfileReadonlyForm';
import { ProfileEditNotice } from '@/components/cabinet/profile/ProfileEditNotice';
import { MailingSettings } from '@/components/cabinet/profile/MailingSettings';
import { CabinetCard } from '@/components/cabinet/CabinetCard';
import { fetchProtected } from '@/lib/protectedApi';

export default async function ProfilePage() {
  const cookieStore = await cookies();
  const user = await fetchProtected(getMe({ cookieHeader: cookieStore.toString() }));

  // Поля которые есть в API
  const profile = {
    lastName: user.name.split(' ')[1] ?? '',
    firstName: user.name.split(' ')[0] ?? '',
    middleName: user.name.split(' ')[2] ?? '',
    login: user.email,
    email: user.email,
    phone: user.phone,
    // Расширенные данные — TODO: получать из Bitrix через API
    birthDate: '—',
    registrationAddress: '—',
    actualAddress: '—',
    passportSeries: '—',
    passportNumber: '—',
    emailSubscription: false,
    smsSubscription: false,
  };

  return (
    <>
      <CabinetPageTitle
        title="Личные данные"
        breadcrumbs={
          <CabinetBreadcrumbs items={[{ label: 'Личные данные' }]} />
        }
      />

      <div className="space-y-6">
        <CabinetCard>
          <ProfileEditNotice />
          <ProfileReadonlyForm profile={profile} />
        </CabinetCard>

        <CabinetCard>
          <MailingSettings
            emailSubscription={profile.emailSubscription}
            smsSubscription={profile.smsSubscription}
          />
        </CabinetCard>
      </div>
    </>
  );
}
