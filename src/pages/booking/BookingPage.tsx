import { useEffect, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { useBooking } from '../../context/BookingContext';
import { useDocumentHead } from '../../hooks/useDocumentHead';
import { getShowtimeById } from '../../data/showtimes';
import { getMovieById } from '../../data/movies';
import { Stepper } from './Stepper';
import type { BookingStep } from './steps';
import { SelectMovieStep } from './steps/SelectMovieStep';
import { SelectCinemaStep } from './steps/SelectCinemaStep';
import { SelectDateStep } from './steps/SelectDateStep';
import { SelectShowtimeStep } from './steps/SelectShowtimeStep';
import { SeatSelectionStep } from './steps/SeatSelectionStep';
import { ReviewStep } from './steps/ReviewStep';
import { ConfirmationStep } from './steps/ConfirmationStep';
import type { StoredBooking } from '../../types';

export default function BookingPage() {
  useDocumentHead({ title: 'Book Tickets', description: 'Book your movie tickets at Savoy Cinema in a few quick steps.' });

  const { reset, setMovie, setCinema, setDate, setShowtime } = useBooking();
  const [searchParams] = useSearchParams();
  const [step, setStep] = useState<BookingStep>('movie');
  const [confirmedBooking, setConfirmedBooking] = useState<StoredBooking | null>(null);

  useEffect(() => {
    const showtimeId = searchParams.get('showtime');
    const movieId = searchParams.get('movie');

    if (showtimeId) {
      const showtime = getShowtimeById(showtimeId);
      if (showtime) {
        setMovie(showtime.movieId);
        setCinema(showtime.cinemaId);
        setDate(showtime.date);
        setShowtime(showtime.id);
        setStep('seats');
        return;
      }
    }

    if (movieId && getMovieById(movieId)) {
      setMovie(movieId);
      setStep('cinema');
    }
    // Only run once on mount, deliberately ignoring linter deps for the setters (stable via useCallback).
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  function handleBookAnother() {
    reset();
    setConfirmedBooking(null);
    setStep('movie');
  }

  return (
    <div className="container-page py-12 sm:py-16">
      <h1 className="mb-2 text-3xl font-bold sm:text-4xl">Book Tickets</h1>
      <p className="mb-8 text-neutral-400">Pick your movie, showtime, and seats in a few quick steps.</p>

      <Stepper current={confirmedBooking ? 'confirm' : step} />

      {step === 'movie' && <SelectMovieStep onNext={() => setStep('cinema')} />}
      {step === 'cinema' && <SelectCinemaStep onNext={() => setStep('date')} onBack={() => setStep('movie')} />}
      {step === 'date' && <SelectDateStep onNext={() => setStep('showtime')} onBack={() => setStep('cinema')} />}
      {step === 'showtime' && <SelectShowtimeStep onNext={() => setStep('seats')} onBack={() => setStep('date')} />}
      {step === 'seats' && <SeatSelectionStep onNext={() => setStep('review')} onBack={() => setStep('showtime')} />}
      {step === 'review' && !confirmedBooking && (
        <ReviewStep onBack={() => setStep('seats')} onConfirmed={(booking) => setConfirmedBooking(booking)} />
      )}
      {confirmedBooking && <ConfirmationStep booking={confirmedBooking} onBookAnother={handleBookAnother} />}
    </div>
  );
}
