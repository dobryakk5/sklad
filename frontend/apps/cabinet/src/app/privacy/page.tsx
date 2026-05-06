import Link from 'next/link';

export default function PrivacyPage() {
  return (
    <main className="mx-auto max-w-[900px] px-5 py-10 text-[#333]">
      <Link href="/" className="text-[14px] text-[#f45454] hover:underline">
        ← Вернуться в личный кабинет
      </Link>

      <h1 className="mt-6 text-[36px] font-normal leading-tight">
        Обработка персональных данных
      </h1>

      <p className="mt-5 text-[16px] leading-8 text-[#777]">
        Это временная страница согласия на обработку персональных данных для
        нового кабинета. Юридический текст нужно заменить на боевую редакцию
        перед продакшен-выкладкой.
      </p>
    </main>
  );
}
