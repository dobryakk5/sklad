import { getRentalLandingContent } from '@/lib/rentalLandingContent'
import { RentalLandingPage } from '@/components/rental/RentalLandingPage'

export const metadata = getRentalLandingContent('box').metadata

export default function RentalCatalogPage() {
  return <RentalLandingPage mode="box" />
}
