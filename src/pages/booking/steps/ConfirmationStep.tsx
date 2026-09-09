import { Link } from 'react-router-dom';
import type { StoredBooking } from '../../../types';
import { formatDateLong, formatMoney, formatTimeLabel } from '../../../lib/format';

export function ConfirmationStep({ booking, onBookAnother }: { booking: StoredBooking; onBookAnother: () => void }) {
  return (
    <div className="mx-auto max-w-lg text-center">
      <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-400">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
          <path d="M20 6 9 17l-5-5" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </div>
      <h2 className="mt-5 text-2xl font-bold text-white">Booking confirmed</h2>
      <p className="mt-2 text-neutral-400">
        Your reference number is <span className="font-semibold text-gold-300">{booking.reference}</span>. Keep it handy, along with the email
        you used, to look up this booking later.
      </p>

      <div className="mt-8 flex gap-4 rounded-xl border border-ink-700 bg-ink-900 p-5 text-left">
        <img src={booking.posterUrl} alt="" width={80} height={120} className="h-28 w-20 shrink-0 rounded-md object-cover" />
        <div>
          <h3 className="font-display text-lg font-semibold text-white">{booking.movieTitle}</h3>
          <p className="mt-1 text-sm text-neutral-400">{booking.cinemaName}</p>
          <p className="text-sm text-neutral-400">
            {formatDateLong(booking.date)} at {formatTimeLabel(booking.time)}
          </p>
          <p className="mt-2 text-sm text-neutral-300">Seats: {booking.seats.join(', ')}</p>
          <p className="mt-3 font-display text-lg font-bold text-white">{formatMoney(booking.totalPrice)}</p>
        </div>
      </div>

      <div className="mt-8 flex flex-wrap justify-center gap-3">
        <button type="button" onClick={onBookAnother} className="btn-primary">
          Book Another Ticket
        </button>
        <Link to="/my-bookings" className="btn-secondary">
          View My Bookings
        </Link>
      </div>
    </div>
  );
}
