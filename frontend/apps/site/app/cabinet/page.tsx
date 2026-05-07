import { redirect } from 'next/navigation'
import { CABINET_URL } from '@/lib/constants'

export default function CabinetRedirectPage() {
  redirect(CABINET_URL)
}
