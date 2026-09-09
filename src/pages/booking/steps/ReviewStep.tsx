import { useState, type FormEvent } from 'react';
import { useBooking } from '../../../context/BookingContext';
import { getShowtimeById } from '../../../data/showtimes';
import { getCinemaById } from '../../../data/cinemas';
import { getMovieById } from '../../../data/movies';
import { isSeatPremium } from '../../../lib/seats';
import { formatDateLong, formatMoney, formatTimeLabel } from '../../../lib/format';
import { generateBookingReference, saveBooking } from '../../../lib/bookings';
import { EmptyState } from '../../../components/ui/EmptyState';
import { StepNav } from '../StepNav';
import type { StoredBooking } from '../../../types';

interface ReviewStepProps {
  onBack: () => void;
  onConfirmed: (booking: StoredBooking) => void;
}

export function ReviewStep({ onBack, onConfirmed }: ReviewStepProps) {
  const { selection } = useBooking();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [errors, setErrors] = useState<{ name?: string; email?: string }>({});
  const [submitting, setSubmitting] = useState(false);

  const showtime = selection.showtimeId ? getShowtimeById(selection.showtimeId) : undefined;
  const cinema = showtime ? getCinemaById(showtime.cinemaId) : undefined;
  const hall = cinema?.halls.find((h) => h.code === showtime?.hallCode);
  const movie = showtime ? getMovieById(showtime.movieId) : undefined;

  if (!showtime || !cinema || !hall || !movie || selection.seats.length === 0) {
    return <EmptyState title="Nothing to review yet" description="Go back and select your seats first." />;
  }

  const total = selection.seats.reduce((sum, seatCode) => {
    const premium = isSeatPremium(seatCode, hall.premiumRows);
    return sum + (premium ? showtime.premiumPrice : showtime.standardPrice);
  }, 0);

  function handleSubmit(event: FormEvent) {
    event.preventDefault();
    const nextErrors: { name?: string; email?: string } = {};
    if (!name.trim()) nextErrors.name = 'Please enter your full name.';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) nextErrors.email = 'Please enter a valid email address.';
    setErrors(nextErrors);
    if (Object.keys(nextErrors).length > 0) return;

    setSubmitting(true);
    window.setTimeout(() => {
      const booking: StoredBooking = {
        reference: generateBookingReference(),
        showtimeId: showtime!.id,
        movieId: movie!.id,
        movieTitle: movie!.title,
        posterUrl: movie!.posterUrl,
        cinemaName: `${cinema!.name} (Hall ${hall!.code})`,
        hallCode: hall!.code,
        date: showtime!.date,
        time: showtime!.time,
        seats: selection.seats,
        totalPrice: total,
        customerName: name.trim(),
        customerEmail: email.trim(),
        createdAt: new Date().toISOString(),
      };
      saveBooking(booking);
      setSubmitting(false);
      onConfirmed(booking);
    }, 500);
  }

  return (
    <div>
      <StepNav onBack={onBack} label="Change seats" />
      <h2 className="text-xl font-bold text-white">Review and confirm</h2>

      <div className="mt-5 grid gap-8 lg:grid-cols-2">
        <div className="flex gap-4 rounded-xl border border-ink-700 bg-ink-900 p-5">
          <img src={movie.posterUrl} alt="" width={80} height={120} className="h-28 w-20 shrink-0 rounded-md object-cover" />
          <div>
            <h3 className="font-display text-lg font-semibold text-white">{movie.title}</h3>
            <p className="mt-1 text-sm text-neutral-400">
              {cinema.name}, Hall {hall.code}
            </p>
            <p className="text-sm text-neutral-400">
              {formatDateLong(showtime.date)} at {formatTimeLabel(showtime.time)}
            </p>
            <p className="mt-2 text-sm text-neutral-300">Seats: {selection.seats.join(', ')}</p>
            <p className="mt-3 font-display text-lg font-bold text-white">{formatMoney(total)}</p>
          </div>
        </div>

        <form onSubmit={handleSubmit} noValidate className="flex flex-col gap-4">
          <div>
            <label htmlFor="review-name" className="field-label">
              Full name
            </label>
            <input
              id="review-name"
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="field-input"
              autoComplete="name"
              aria-invalid={Boolean(errors.name)}
              aria-describedby={errors.name ? 'review-name-error' : undefined}
            />
            {errors.name && (
              <p id="review-name-error" className="mt-1.5 text-xs text-ember-500">
                {errors.name}
              </p>
            )}
          </div>

          <div>
            <label htmlFor="review-email" className="field-label">
              Email address
            </label>
            <input
              id="review-email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="field-input"
              autoComplete="email"
              aria-invalid={Boolean(errors.email)}
              aria-describedby={errors.email ? 'review-email-error' : undefined}
            />
            {errors.email && (
              <p id="review-email-error" className="mt-1.5 text-xs text-ember-500">
                {errors.email}
              </p>
            )}
            <p className="mt-1.5 text-xs text-neutral-500">You'll need this along with your reference number to look up this booking later.</p>
          </div>

          <button type="submit" disabled={submitting} className="btn-primary mt-2">
            {submitting ? 'Confirming...' : `Confirm Booking, ${formatMoney(total)}`}
          </button>
        </form>
      </div>
    </div>
  );
}
