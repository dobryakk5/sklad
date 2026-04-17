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
  const [selectedBoxId, setSelectedBoxId] = useState<number | null>(null)

  // Step 3
  const [form, setForm] = useState<RenterFormData>(defaultForm)
  const [checkoutAttemptId, setCheckoutAttemptId] = useState(createCheckoutAttemptId)

  // Когда выбирается склад — сразу грузим боксы (фоново, не блокируя шаг 1)
  useEffect(() => {
    if (!selectedWarehouseId) return
    setBoxes([])
    setSelectedBoxId(null)
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

  useEffect(() => {
    setCheckoutAttemptId(createCheckoutAttemptId())
  }, [selectedWarehouseId, selectedBoxId, form])

  const selectedWarehouse = warehouses.find(w => w.id === selectedWarehouseId) ?? null
  const selectedBox       = boxes.find(b => b.id === selectedBoxId) ?? null

  return (
    <div className="online-wizard">
      <StepIndicator
        currentStep={step}
        onStepClick={(s) => s <= step && setStep(s)}
      />

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
            selectedBoxId={selectedBoxId}
            onSelect={setSelectedBoxId}
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

        {step === 4 && selectedWarehouse && selectedBox && (
          <Step4Payment
            warehouse={selectedWarehouse}
            box={selectedBox}
            renter={form}
            checkoutAttemptId={checkoutAttemptId}
            onPrev={() => setStep(3)}
          />
        )}
      </div>
    </div>
  )
}
