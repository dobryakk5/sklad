"use client";
import { useState } from "react";

export interface RenterFormData {
  payerType: "individual" | "legal";
  lastName: string;
  firstName: string;
  middleName: string;
  passport: string;
  phone: string;
  email: string;
  companyName: string;
  inn: string;
  contactPerson: string;
  needCall: boolean;
  needDelivery: boolean;
}

interface Step3Props {
  formData: RenterFormData;
  onChange: (data: Partial<RenterFormData>) => void;
  onNext: () => void;
  onPrev: () => void;
}

const inputStyle = {
  width: "100%",
  padding: "12px 16px",
  border: "1.5px solid #e0e0e0",
  borderRadius: 8,
  fontFamily: "'Golos Text', sans-serif",
  fontSize: 14,
  color: "#1a1a2e",
  background: "#fff",
  outline: "none",
  transition: "border-color 0.2s",
};

const labelStyle = {
  fontSize: 13,
  fontWeight: 500 as const,
  color: "#6b7280",
  display: "block" as const,
  marginBottom: 6,
};

function Field({ label, required, children }: { label: string; required?: boolean; children: React.ReactNode }) {
  return (
    <div style={{ display: "flex", flexDirection: "column" as const }}>
      <label style={labelStyle}>{label}{required && <span style={{ color: "#e63946" }}> *</span>}</label>
      {children}
    </div>
  );
}

export default function Step3Data({ formData, onChange, onNext, onPrev }: Step3Props) {
  const [focused, setFocused] = useState<string | null>(null);

  const isValid = formData.payerType === "individual"
    ? formData.lastName && formData.firstName && formData.passport && formData.phone && formData.email
    : formData.companyName && formData.inn && formData.contactPerson && formData.phone && formData.email;

  const getInputStyle = (name: string) => ({
    ...inputStyle,
    borderColor: focused === name ? "#e63946" : "#e0e0e0",
  });

  return (
    <div>
      <h2 style={{ fontFamily: "'Unbounded', sans-serif", fontSize: 20, fontWeight: 700, marginBottom: 24 }}>
        3. Ваши данные
      </h2>

      <div style={{ background: "#fff", borderRadius: 12, border: "1px solid #e0e0e0", padding: "28px 32px" }}>
        {/* Payer type */}
        <div style={{ marginBottom: 28 }}>
          <p style={{ fontSize: 13, fontWeight: 600, color: "#6b7280", marginBottom: 12, textTransform: "uppercase", letterSpacing: "0.06em" }}>
            Тип плательщика
          </p>
          <div style={{ display: "flex", gap: 0, background: "#f5f5f5", borderRadius: 10, padding: 4, width: "fit-content" }}>
            {(["individual", "legal"] as const).map((type) => (
              <button key={type} onClick={() => onChange({ payerType: type })} style={{
                padding: "10px 24px",
                borderRadius: 8,
                border: "none",
                background: formData.payerType === type ? "#e63946" : "transparent",
                color: formData.payerType === type ? "#fff" : "#555",
                fontFamily: "'Golos Text', sans-serif",
                fontWeight: 600, fontSize: 14, cursor: "pointer",
                transition: "all 0.2s",
              }}>
                {type === "individual" ? "👤 Физическое лицо" : "🏢 Юридическое лицо"}
              </button>
            ))}
          </div>
        </div>

        {formData.payerType === "individual" ? (
          <>
            {/* Passport section */}
            <div style={{ marginBottom: 24 }}>
              <p style={{ fontFamily: "'Unbounded', sans-serif", fontSize: 13, fontWeight: 600, marginBottom: 16, color: "#1a1a2e" }}>
                Паспортные данные
              </p>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr 1fr", gap: 16 }}>
                <Field label="Фамилия" required>
                  <input
                    type="text"
                    value={formData.lastName}
                    onChange={e => onChange({ lastName: e.target.value })}
                    onFocus={() => setFocused("lastName")}
                    onBlur={() => setFocused(null)}
                    placeholder="Иванов"
                    style={getInputStyle("lastName")}
                  />
                </Field>
                <Field label="Имя" required>
                  <input
                    type="text"
                    value={formData.firstName}
                    onChange={e => onChange({ firstName: e.target.value })}
                    onFocus={() => setFocused("firstName")}
                    onBlur={() => setFocused(null)}
                    placeholder="Иван"
                    style={getInputStyle("firstName")}
                  />
                </Field>
                <Field label="Отчество">
                  <input
                    type="text"
                    value={formData.middleName}
                    onChange={e => onChange({ middleName: e.target.value })}
                    onFocus={() => setFocused("middleName")}
                    onBlur={() => setFocused(null)}
                    placeholder="Иванович"
                    style={getInputStyle("middleName")}
                  />
                </Field>
              </div>
              <div style={{ marginTop: 16 }}>
                <Field label="Серия и номер паспорта" required>
                  <input
                    type="text"
                    value={formData.passport}
                    onChange={e => onChange({ passport: e.target.value })}
                    onFocus={() => setFocused("passport")}
                    onBlur={() => setFocused(null)}
                    placeholder="0000 000000"
                    style={getInputStyle("passport")}
                  />
                </Field>
              </div>
            </div>
          </>
        ) : (
          <div style={{ marginBottom: 24 }}>
            <p style={{ fontFamily: "'Unbounded', sans-serif", fontSize: 13, fontWeight: 600, marginBottom: 16, color: "#1a1a2e" }}>
              Данные компании
            </p>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 16 }}>
              <Field label="Наименование компании" required>
                <input type="text" value={formData.companyName} onChange={e => onChange({ companyName: e.target.value })}
                  onFocus={() => setFocused("company")} onBlur={() => setFocused(null)}
                  placeholder='ООО "Ромашка"' style={getInputStyle("company")} />
              </Field>
              <Field label="ИНН" required>
                <input type="text" value={formData.inn} onChange={e => onChange({ inn: e.target.value })}
                  onFocus={() => setFocused("inn")} onBlur={() => setFocused(null)}
                  placeholder="7700000000" style={getInputStyle("inn")} />
              </Field>
              <div style={{ gridColumn: "1 / -1" }}>
                <Field label="ФИО контактного лица" required>
                  <input type="text" value={formData.contactPerson} onChange={e => onChange({ contactPerson: e.target.value })}
                    onFocus={() => setFocused("contact")} onBlur={() => setFocused(null)}
                    placeholder="Иванов Иван Иванович" style={getInputStyle("contact")} />
                </Field>
              </div>
            </div>
          </div>
        )}

        {/* Contact */}
        <div style={{ marginBottom: 24 }}>
          <p style={{ fontFamily: "'Unbounded', sans-serif", fontSize: 13, fontWeight: 600, marginBottom: 16, color: "#1a1a2e" }}>
            Контактные данные
          </p>
          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 16 }}>
            <Field label="Телефон" required>
              <input type="tel" value={formData.phone} onChange={e => onChange({ phone: e.target.value })}
                onFocus={() => setFocused("phone")} onBlur={() => setFocused(null)}
                placeholder="+7 (___) ___-__-__" style={getInputStyle("phone")} />
            </Field>
            <Field label="Электронная почта" required>
              <input type="email" value={formData.email} onChange={e => onChange({ email: e.target.value })}
                onFocus={() => setFocused("email")} onBlur={() => setFocused(null)}
                placeholder="example@mail.ru" style={getInputStyle("email")} />
            </Field>
          </div>
        </div>

        {/* Additional options */}
        <div style={{ marginBottom: 8 }}>
          <p style={{ fontFamily: "'Unbounded', sans-serif", fontSize: 13, fontWeight: 600, marginBottom: 14, color: "#1a1a2e" }}>
            Дополнительно
          </p>
          <div style={{ display: "flex", flexDirection: "column", gap: 12 }}>
            {[
              { key: "needCall", label: "📞 Нужен звонок менеджера для консультации" },
              { key: "needDelivery", label: "🚚 Нужна доставка" },
            ].map(opt => (
              <label key={opt.key} style={{ display: "flex", alignItems: "center", gap: 12, cursor: "pointer" }}>
                <input
                  type="checkbox"
                  checked={formData[opt.key as keyof RenterFormData] as boolean}
                  onChange={e => onChange({ [opt.key]: e.target.checked })}
                  style={{ width: 18, height: 18, accentColor: "#e63946", cursor: "pointer" }}
                />
                <span style={{ fontSize: 14, color: "#1a1a2e" }}>{opt.label}</span>
              </label>
            ))}
          </div>
        </div>
      </div>

      <p style={{ fontSize: 12, color: "#aaa", marginTop: 12 }}>* поля обязательны к заполнению</p>

      {/* Navigation */}
      <div style={{ marginTop: 24, display: "flex", justifyContent: "space-between" }}>
        <button onClick={onPrev} style={{
          padding: "12px 28px", borderRadius: 10, border: "1.5px solid #e0e0e0",
          background: "#fff", color: "#555", fontSize: 14, fontWeight: 600,
          fontFamily: "'Golos Text', sans-serif", cursor: "pointer",
        }}>← Назад</button>
        <button onClick={onNext} disabled={!isValid} style={{
          padding: "14px 36px", background: isValid ? "#e63946" : "#ddd",
          color: "#fff", border: "none", borderRadius: 10,
          fontFamily: "'Golos Text', sans-serif", fontWeight: 700, fontSize: 15,
          cursor: isValid ? "pointer" : "not-allowed", transition: "all 0.2s",
        }}>К оплате →</button>
      </div>
    </div>
  );
}
