import { getRentalLandingContent } from '@/lib/rentalLandingContent'
import { RentalLandingPage } from '@/components/rental/RentalLandingPage'

export const metadata = getRentalLandingContent('container').metadata

export default function RentalContainersPage() {
  return <RentalLandingPage mode="container" />
}
