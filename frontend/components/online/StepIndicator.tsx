'use client'

const steps = [
  {
    num: 1,
    icon: 'https://alfasklad.ru/images/icons/x60/choose-warehouse_x60.svg',
    lead: 'Выберите',
    accent: 'склад',
  },
  {
    num: 2,
    icon: 'https://alfasklad.ru/images/icons/x60/size_x60.svg',
    lead: 'Выберите',
    accent: 'помещение',
  },
  {
    num: 3,
    icon: 'https://alfasklad.ru/images/icons/x60/yours-data_x60.svg',
    lead: 'Введите',
    accent: 'данные',
  },
  {
    num: 4,
    icon: 'https://alfasklad.ru/images/icons/x60/payment_x60.svg',
    lead: 'Внесите',
    accent: 'оплату',
  },
  {
    num: 5,
    icon: 'https://alfasklad.ru/images/icons/x60/get-access_x60.svg',
    lead: 'Получите',
    accent: 'доступ',
  },
]

function Divider() {
  return (
    <div className="online-steps-divider" aria-hidden="true">
      <svg width="29" height="132" viewBox="0 0 29 133" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 0.5C1.75023 0.5 3.32282 1.56921 3.96646 3.19679L27.8366 63.5581C28.584 65.4481 28.584 67.5519 27.8366 69.442L3.96646 129.803C3.32282 131.431 1.75023 132.5 0 132.5"
          stroke="#EF5A54"
        />
      </svg>
    </div>
  )
}

interface StepIndicatorProps {
  currentStep: number
  onStepClick: (step: number) => void
}

export default function StepIndicator({ currentStep, onStepClick }: StepIndicatorProps) {
  return (
    <section className="online-steps-card">
      <p className="online-steps-subtitle">
        Здесь Вы можете выбрать и арендовать онлайн подходящий под Ваши нужды бокс, пройдя всего несколько шагов:
      </p>

      <div className="online-steps-list">
        {steps.map((step, index) => {
          const isCurrent = step.num === currentStep
          const isComplete = step.num < currentStep
          const isClickable = step.num <= currentStep

          return (
            <div key={step.num} className="online-steps-item">
              <button
                type="button"
                className={[
                  'online-steps-button',
                  isCurrent ? 'is-current' : '',
                  isComplete ? 'is-complete' : '',
                  isClickable ? 'is-clickable' : '',
                ]
                  .filter(Boolean)
                  .join(' ')}
                onClick={() => isClickable && onStepClick(step.num)}
              >
                <span className="online-steps-icon-wrap">
                  <img className="online-steps-icon" src={step.icon} alt="" />
                  {isComplete && (
                    <span className="online-steps-done-badge" aria-hidden="true">
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="8" fill="#ef5a54" />
                        <path
                          d="M4.5 8.2l2.2 2.2 4.3-4.3"
                          stroke="#fff"
                          strokeWidth="1.6"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </span>
                  )}
                </span>
                <span className="online-steps-text">
                  <strong>{step.num}.</strong>
                  {step.lead}
                  <br />
                  <span>{step.accent}</span>
                </span>
              </button>
              {index < steps.length - 1 && <Divider />}
            </div>
          )
        })}
      </div>
    </section>
  )
}
