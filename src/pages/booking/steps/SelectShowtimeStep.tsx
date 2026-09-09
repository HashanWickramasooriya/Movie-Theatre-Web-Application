import { useMemo } from 'react';
import { useBooking } from '../../../context/BookingContext';
import { showtimes } from '../../../data/showtimes';
import { formatMoney, formatTimeLabel } from '../../../lib/format';
import { EmptyState } from '../../../components/ui/EmptyState';
import { StepNav } from '../StepNav';

export function SelectShowtimeStep({ onNext, onBack }: { onNext: () => void; onBack: () => void }) {
  const { selection, setShowtime } = useBooking();

  const options = useMemo(
    () =>
      showtimes
        .filter((s) => s.movieId === selection.movieId && s.cinemaId === selection.cinemaId && s.date === selection.date)
        .sort((a, b) => a.time.localeCompare(b.time)),
    [selection.movieId, selection.cinemaId, selection.date],
  );

  if (options.length === 0) {
    return <EmptyState title="No showtimes available" description="Try a different date." />;
  }

  return (
    <div>
      <StepNav onBack={onBack} label="Change date" />
      <h2 className="text-xl font-bold text-white">Choose a showtime</h2>
      <div className="mt-5 flex flex-wrap gap-3">
        {options.map((show) => (
          <button
            key={show.id}
            type="button"
            onClick={() => {
              setShowtime(show.id);
              onNext();
            }}
            className="flex flex-col items-center rounded-xl border border-ink-700 bg-ink-900 px-6 py-4 transition-colors hover:border-gold-400/50"
          >
            <span className="font-display text-lg font-bold text-white">{formatTimeLabel(show.time)}</span>
            <span className="mt-1 text-xs text-neutral-500">Hall {show.hallCode}</span>
            <span className="mt-1 text-xs font-medium text-gold-300">from {formatMoney(show.standardPrice)}</span>
          </button>
        ))}
      </div>
    </div>
  );
}
