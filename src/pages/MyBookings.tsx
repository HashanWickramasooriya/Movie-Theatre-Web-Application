import { useState, type FormEvent } from 'react';
import { Link } from 'react-router-dom';
import { findBooking } from '../lib/bookings';
import { formatDateLong, formatMoney, formatTimeLabel } from '../lib/format';
import { useDocumentHead } from '../hooks/useDocumentHead';
import type { StoredBooking } from '../types';

export default function MyBookings() {
  useDocumentHead({ title: 'My Bookings', description: 'Look up a Savoy Cinema booking using your reference number and email.' });

  const [reference, setReference] = useState('');
  const [email, setEmail] = useState('');
  const [result, setResult] = useState<StoredBooking | null>(null);
  const [searched, setSearched] = useState(false);

  function handleSubmit(event: FormEvent) {
    event.preventDefault();
    setResult(findBooking(reference, email) ?? null);
    setSearched(true);
  }

  return (
    <div className="container-page py-12 sm:py-16">
      <div className="mx-auto max-w-lg text-center">
        <h1 className="text-3xl font-bold sm:text-4xl">Find Your Booking</h1>
        <p className="mt-2 text-neutral-400">Enter the booking reference and email address you used when booking.</p>
      </div>

      <form onSubmit={handleSubmit} className="mx-auto mt-8 flex max-w-lg flex-col gap-4">
        <div>
          <label htmlFor="lookup-reference" className="field-label">
            Booking reference
          </label>
          <input
            id="lookup-reference"
            type="text"
            value={reference}
            onChange={(e) => setReference(e.target.value)}
            placeholder="e.g. SVY-8F2K1A"
            required
            className="field-input"
          />
        </div>
        <div>
          <label htmlFor="lookup-email" className="field-label">
            Email address
          </label>
          <input
            id="lookup-email"
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            className="field-input"
          />
        </div>
        <button type="submit" className="btn-primary">
          Find Booking
        </button>
      </form>

      {searched && (
        <div className="mx-auto mt-10 max-w-lg">
          {result ? (
            <div className="flex gap-4 rounded-xl border border-ink-700 bg-ink-900 p-5">
              <img src={result.posterUrl} alt="" width={80} height={120} className="h-28 w-20 shrink-0 rounded-md object-cover" />
              <div>
                <h3 className="font-display text-lg font-semibold text-white">{result.movieTitle}</h3>
                <p className="mt-1 text-sm text-neutral-400">{result.cinemaName}</p>
                <p className="text-sm text-neutral-400">
                  {formatDateLong(result.date)} at {formatTimeLabel(result.time)}
                </p>
                <p className="mt-2 text-sm text-neutral-300">Seats: {result.seats.join(', ')}</p>
                <p className="mt-3 font-display text-lg font-bold text-white">{formatMoney(result.totalPrice)}</p>
                <p className="mt-1 text-xs text-neutral-500">Reference: {result.reference}</p>
              </div>
            </div>
          ) : (
            <p className="text-center text-sm text-neutral-400">
              We couldn't find a booking with that reference and email on this device. Bookings are only stored in the browser you booked from.
            </p>
          )}
        </div>
      )}

      <p className="mt-10 text-center text-sm text-neutral-500">
        Don't have a booking yet?{' '}
        <Link to="/booking" className="text-gold-300 hover:underline">
          Book seats now
        </Link>
      </p>
    </div>
  );
}
