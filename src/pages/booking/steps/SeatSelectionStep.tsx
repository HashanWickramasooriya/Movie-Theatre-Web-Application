import { useMemo } from 'react';
import { useBooking } from '../../../context/BookingContext';
import { getShowtimeById } from '../../../data/showtimes';
import { getCinemaById } from '../../../data/cinemas';
import { getMovieById } from '../../../data/movies';
import { buildSeatLayout, getSimulatedOccupiedSeats, isSeatPremium } from '../../../lib/seats';
import { getLocallyTakenSeats } from '../../../lib/bookings';
import { formatMoney } from '../../../lib/format';
import { EmptyState } from '../../../components/ui/EmptyState';
import { StepNav } from '../StepNav';

const MAX_SEATS = 10;

export function SeatSelectionStep({ onNext, onBack }: { onNext: () => void; onBack: () => void }) {
  const { selection, toggleSeat } = useBooking();

  const showtime = selection.showtimeId ? getShowtimeById(selection.showtimeId) : undefined;
  const cinema = showtime ? getCinemaById(showtime.cinemaId) : undefined;
  const hall = cinema?.halls.find((h) => h.code === showtime?.hallCode);
  const movie = showtime ? getMovieById(showtime.movieId) : undefined;

  const layout = useMemo(() => (hall ? buildSeatLayout(hall) : []), [hall]);

  const occupiedSeats = useMemo(() => {
    if (!showtime || layout.length === 0) return new Set<string>();
    const simulated = getSimulatedOccupiedSeats(showtime.id, layout);
    const locallyTaken = getLocallyTakenSeats(showtime.id);
    return new Set([...simulated, ...locallyTaken]);
  }, [showtime, layout]);

  if (!showtime || !cinema || !hall || !movie) {
    return <EmptyState title="Select a showtime first" description="Go back and choose a showtime to see the seat map." />;
  }

  const total = selection.seats.reduce((sum, seatCode) => {
    const premium = isSeatPremium(seatCode, hall.premiumRows);
    return sum + (premium ? showtime.premiumPrice : showtime.standardPrice);
  }, 0);

  return (
    <div>
      <StepNav onBack={onBack} label="Change showtime" />
      <h2 className="text-xl font-bold text-white">Choose your seats</h2>
      <p className="mt-1 text-sm text-neutral-400">
        {movie.title} &middot; {cinema.name}, Hall {hall.code}
      </p>

      <div className="mt-6 overflow-x-auto rounded-2xl bg-ink-900 p-6">
        <div className="mx-auto mb-8 h-2 w-full max-w-md rounded-[50%/6px] border-b-4 border-ink-600" aria-hidden="true" />
        <p className="mb-6 text-center text-xs uppercase tracking-[0.3em] text-neutral-600">Screen this way</p>

        <div className="flex min-w-fit flex-col items-center gap-2" role="group" aria-label="Seat map">
          {layout.map((row) => (
            <div key={row.row} className="flex items-center gap-1.5">
              <span className="w-5 shrink-0 text-center text-xs text-neutral-600">{row.row}</span>
              {row.seats.map((seatNumber) => {
                const code = `${row.row}${seatNumber}`;
                const isOccupied = occupiedSeats.has(code);
                const isSelected = selection.seats.includes(code);

                return (
                  <button
                    key={code}
                    type="button"
                    disabled={isOccupied}
                    aria-pressed={isSelected}
                    aria-label={`Seat ${code}${row.premium ? ', premium' : ''}, ${isOccupied ? 'occupied' : isSelected ? 'selected' : 'available'}`}
                    onClick={() => toggleSeat(code)}
                    className={`h-8 w-8 shrink-0 rounded-md rounded-b-sm text-[10px] font-medium transition-colors ${
                      isOccupied
                        ? 'cursor-not-allowed bg-ink-800 text-ink-600'
                        : isSelected
                          ? 'bg-gold-400 text-ink-950'
                          : row.premium
                            ? 'bg-amber-900/40 text-amber-200/70 hover:bg-amber-800/50'
                            : 'bg-ink-700 text-neutral-300 hover:bg-ink-600'
                    }`}
                  >
                    {seatNumber}
                  </button>
                );
              })}
            </div>
          ))}
        </div>

        <div className="mt-8 flex flex-wrap justify-center gap-5 text-xs text-neutral-400">
          <Legend swatchClass="bg-ink-700" label="Available" />
          <Legend swatchClass="bg-gold-400" label="Selected" />
          <Legend swatchClass="bg-ink-800" label="Occupied" />
          <Legend swatchClass="bg-amber-900/40" label="Premium" />
        </div>
      </div>

      <div className="sticky bottom-0 mt-6 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-ink-700 bg-ink-900 p-5">
        <div>
          <p className="text-sm text-neutral-400">
            {selection.seats.length} of {MAX_SEATS} seats selected
          </p>
          <p className="font-display text-xl font-bold text-white">{formatMoney(total)}</p>
        </div>
        <button type="button" onClick={onNext} disabled={selection.seats.length === 0} className="btn-primary">
          Continue
        </button>
      </div>
    </div>
  );
}

function Legend({ swatchClass, label }: { swatchClass: string; label: string }) {
  return (
    <span className="flex items-center gap-1.5">
      <span className={`h-3.5 w-3.5 rounded ${swatchClass}`} />
      {label}
    </span>
  );
}
