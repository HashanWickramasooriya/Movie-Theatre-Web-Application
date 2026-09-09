import { useMemo } from 'react';
import { useBooking } from '../../../context/BookingContext';
import { cinemas } from '../../../data/cinemas';
import { showtimes } from '../../../data/showtimes';
import { EmptyState } from '../../../components/ui/EmptyState';
import { StepNav } from '../StepNav';

export function SelectCinemaStep({ onNext, onBack }: { onNext: () => void; onBack: () => void }) {
  const { selection, setCinema } = useBooking();

  const availableCinemas = useMemo(() => {
    const cinemaIds = new Set(showtimes.filter((s) => s.movieId === selection.movieId).map((s) => s.cinemaId));
    return cinemas.filter((c) => cinemaIds.has(c.id));
  }, [selection.movieId]);

  if (availableCinemas.length === 0) {
    return <EmptyState title="No cinemas available" description="This movie isn't currently scheduled at any of our cinemas." />;
  }

  return (
    <div>
      <StepNav onBack={onBack} label="Change movie" />
      <h2 className="text-xl font-bold text-white">Choose a cinema</h2>
      <div className="mt-5 grid gap-4 sm:grid-cols-3">
        {availableCinemas.map((cinema) => (
          <button
            key={cinema.id}
            type="button"
            onClick={() => {
              setCinema(cinema.id);
              onNext();
            }}
            className="rounded-xl border border-ink-700 bg-ink-900 p-5 text-left transition-colors hover:border-gold-400/50"
          >
            <p className="font-semibold text-white">{cinema.name}</p>
            <p className="mt-1 text-sm text-neutral-400">{cinema.address}</p>
            <p className="mt-3 text-xs text-neutral-500">{cinema.halls.length} screens</p>
          </button>
        ))}
      </div>
    </div>
  );
}
