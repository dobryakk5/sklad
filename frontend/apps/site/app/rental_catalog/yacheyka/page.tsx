import { getRentalLandingContent } from '@/lib/rentalLandingContent'
import { RentalLandingPage } from '@/components/rental/RentalLandingPage'

export const metadata = getRentalLandingContent('cell').metadata

export default function RentalCellPage() {
  return <RentalLandingPage mode="cell" />
}
