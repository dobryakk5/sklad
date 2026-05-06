type Profile = {
  lastName: string;
  firstName: string;
  middleName: string;
  login: string;
  email: string;
  phone: string;
  birthDate: string;
  registrationAddress: string;
  actualAddress: string;
  passportSeries: string;
  passportNumber: string;
};

type ProfileReadonlyFormProps = {
  profile: Profile;
};

const fields: Array<{ key: keyof Profile; label: string }> = [
  { key: 'lastName', label: 'Фамилия' },
  { key: 'firstName', label: 'Имя' },
  { key: 'middleName', label: 'Отчество' },
  { key: 'login', label: 'Логин' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Телефон' },
  { key: 'birthDate', label: 'Дата рождения' },
  { key: 'registrationAddress', label: 'Адрес регистрации' },
  { key: 'actualAddress', label: 'Фактический адрес' },
  { key: 'passportSeries', label: 'Серия паспорта' },
  { key: 'passportNumber', label: 'Номер паспорта' },
];

export function ProfileReadonlyForm({
  profile,
}: ProfileReadonlyFormProps) {
  return (
    <div className="grid gap-4 px-5 py-6 sm:grid-cols-2 sm:px-6">
      {fields.map((field) => (
        <div key={field.key} className="rounded-[2px] bg-[#f8fafc] px-4 py-4">
          <div className="text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
            {field.label}
          </div>
          <div className="mt-2 text-[15px] text-[#273142]">
            {profile[field.key] || '—'}
          </div>
        </div>
      ))}
    </div>
  );
}
