import { getRentalLandingContent } from '@/lib/rentalLandingContent'
import { RentalLandingPage } from '@/components/rental/RentalLandingPage'

export const metadata = getRentalLandingContent('room').metadata

export default function RentalRoomPage() {
  return <RentalLandingPage mode="room" />
}
