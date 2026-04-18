'use client'

import { useState, useEffect } from 'react'
import type { Warehouse } from '@/types/warehouse'
import type { Box } from '@/types/box'
import type { RenterFormData } from '@/components/online/steps/Step3Data'
import { fetchBoxesByWarehouse } from '@/lib/online-api'
import StepIndicator from '@/components/online/StepIndicator'
import Step1Warehouse from '@/components/online/steps/Step1Warehouse'
import Step2Room from '@/components/online/steps/Step2Room'
import Step3Data from '@/components/online/steps/Step3Data'
import Step4Payment from '@/components/online/steps/Step4Payment'

const defaultForm: RenterFormData = {
  payerType: 'individual',
  lastName: '', firstName: '', middleName: '', passport: '',
  phone: '', email: '',
  companyName: '', inn: '', contactPerson: '',
  needCall: false, needDelivery: false,
}

interface Props {
  warehouses: Warehouse[]
}

function createCheckoutAttemptId() {
  if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
    return `online_${crypto.randomUUID()}`
  }

  return `online_${Date.now()}_${Math.random().toString(36).slice(2, 10)}`
}

export default function OnlineWizard({ warehouses }: Props) {
  const [step, setStep] = useState(1)

  // Step 1
  const [selectedWarehouseId, setSelectedWarehouseId] = useState<number | null>(null)

  // Step 2 — boxes загружаются когда выбран склад
  const [boxes, setBoxes]               = useState<Box[]>([])
  const [boxesLoading, setBoxesLoading] = useState(false)
  const [boxesError, setBoxesError]     = useState<string | null>(null)
  const [cart, setCart]                 = useState<Record<number, number>>({})

  // Step 3
  const [form, setForm] = useState<RenterFormData>(defaultForm)
  const [checkoutAttemptId, setCheckoutAttemptId] = useState(createCheckoutAttemptId)

  // Когда выбирается склад — сразу грузим боксы (фоново, не блокируя шаг 1)
  useEffect(() => {
    if (!selectedWarehouseId) return
    setBoxes([])
    setCart({})
    setBoxesError(null)
    setBoxesLoading(true)

    fetchBoxesByWarehouse(selectedWarehouseId)
      .then(data => { setBoxes(data); setBoxesLoading(false) })
      .catch(err => {
        setBoxesError('Не удалось загрузить боксы. Попробуйте позже.')
        setBoxesLoading(false)
        console.error(err)
      })
  }, [selectedWarehouseId])

  const cartKey = Object.entries(cart).sort().map(([k, v]) => `${k}:${v}`).join(',')
  useEffect(() => {
    setCheckoutAttemptId(createCheckoutAttemptId())
  }, [selectedWarehouseId, cartKey, form])

  const selectedWarehouse = warehouses.find(w => w.id === selectedWarehouseId) ?? null
  const cartHasItems = Object.values(cart).some(qty => qty > 0)

  function handleCartChange(id: number, qty: number) {
    setCart(prev => {
      const next = { ...prev }
      if (qty <= 0) delete next[id]
      else next[id] = qty
      return next
    })
  }

  function handleReset() {
    setSelectedWarehouseId(null)
    setCart({})
    setBoxes([])
    setBoxesError(null)
    setForm(defaultForm)
    setStep(1)
  }

  return (
    <div className="online-wizard">
      <StepIndicator
        currentStep={step}
        onStepClick={(s) => s <= step && setStep(s)}
      />

      {step > 1 && selectedWarehouse && (
        <div className="online-selected-warehouse">
          <div className="online-selected-warehouse__info">
            <span className="online-selected-warehouse__label">Выбранный склад:</span>
            <span className="online-selected-warehouse__name">{selectedWarehouse.name}</span>
          </div>
          <button
            type="button"
            className="online-selected-warehouse__reset"
            onClick={handleReset}
          >
            Сбросить
          </button>
        </div>
      )}

      <div className="online-step-card">
        {step === 1 && (
          <Step1Warehouse
            warehouses={warehouses}
            selectedWarehouseId={selectedWarehouseId}
            onSelect={setSelectedWarehouseId}
            onNext={() => setStep(2)}
          />
        )}

        {step === 2 && (
          <Step2Room
            boxes={boxes}
            loading={boxesLoading}
            error={boxesError}
            cart={cart}
            onCartChange={handleCartChange}
            onNext={() => setStep(3)}
            onPrev={() => setStep(1)}
          />
        )}

        {step === 3 && (
          <Step3Data
            formData={form}
            onChange={(d) => setForm(prev => ({ ...prev, ...d }))}
            onNext={() => setStep(4)}
            onPrev={() => setStep(2)}
          />
        )}

        {step === 4 && selectedWarehouse && cartHasItems && (
          <Step4Payment
            warehouse={selectedWarehouse}
            boxes={boxes}
            cart={cart}
            renter={form}
            checkoutAttemptId={checkoutAttemptId}
            onPrev={() => setStep(3)}
          />
        )}
      </div>
    </div>
  )
}
