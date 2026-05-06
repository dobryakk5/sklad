type MailingSettingsProps = {
  emailSubscription: boolean;
  smsSubscription: boolean;
};

export function MailingSettings({
  emailSubscription,
  smsSubscription,
}: MailingSettingsProps) {
  const items = [
    {
      label: 'Email-уведомления',
      value: emailSubscription ? 'Подключены' : 'Отключены',
    },
    {
      label: 'SMS-уведомления',
      value: smsSubscription ? 'Подключены' : 'Отключены',
    },
  ];

  return (
    <div className="px-5 py-6 sm:px-6">
      <h2 className="text-[20px] font-medium text-[#273142]">
        Настройки уведомлений
      </h2>
      <div className="mt-4 grid gap-4 sm:grid-cols-2">
        {items.map((item) => (
          <div key={item.label} className="rounded-[2px] bg-[#f8fafc] px-4 py-4">
            <div className="text-[12px] uppercase tracking-[0.08em] text-[#8a93a1]">
              {item.label}
            </div>
            <div className="mt-2 text-[15px] text-[#273142]">
              {item.value}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
