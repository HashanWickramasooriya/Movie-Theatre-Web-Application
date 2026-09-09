import { useMemo } from 'react';
import { useBooking } from '../../../context/BookingContext';
import { showtimes } from '../../../data/showtimes';
import { formatDateLabel, formatDateShort, isDateToday } from '../../../lib/format';
import { EmptyState } from '../../../components/ui/EmptyState';
import { StepNav } from '../StepNav';

export function SelectDateStep({ onNext, onBack }: { onNext: () => void; onBack: () => void }) {
  const { selection, setDate } = useBooking();

  const dates = useMemo(
    () =>
      Array.from(
        new Set(
          showtimes
            .filter((s) => s.movieId === selection.movieId && s.cinemaId === selection.cinemaId)
            .map((s) => s.date),
        ),
      ).sort(),
    [selection.movieId, selection.cinemaId],
  );

  if (dates.length === 0) {
    return <EmptyState title="No dates available" description="Try a different cinema for this movie." />;
  }

  return (
    <div>
      <StepNav onBack={onBack} label="Change cinema" />
      <h2 className="text-xl font-bold text-white">Choose a date</h2>
      <div className="mt-5 flex flex-wrap gap-3">
        {dates.map((date) => (
          <button
            key={date}
            type="button"
            onClick={() => {
              setDate(date);
              onNext();
            }}
            className="flex min-w-[110px] flex-col items-center rounded-xl border border-ink-700 bg-ink-900 px-5 py-4 transition-colors hover:border-gold-400/50"
          >
            <span className="text-xs font-medium uppercase tracking-wide text-neutral-500">
              {isDateToday(date) ? 'Today' : formatDateLabel(date).split(',')[0]}
            </span>
            <span className="mt-1 font-display text-lg font-bold text-white">{formatDateShort(date)}</span>
          </button>
        ))}
      </div>
    </div>
  );
}
