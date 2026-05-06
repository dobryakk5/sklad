import { getRentalLandingContent } from '@/lib/rentalLandingContent'
import { RentalLandingPage } from '@/components/rental/RentalLandingPage'

export const metadata = getRentalLandingContent('storage').metadata

export default function RentalStoragePage() {
  return <RentalLandingPage mode="storage" />
}
