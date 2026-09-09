import { createContext, useCallback, useContext, useMemo, useState, type ReactNode } from 'react';
import type { BookingSelection } from '../types';

interface BookingContextValue {
  selection: BookingSelection;
  setMovie: (movieId: string) => void;
  setCinema: (cinemaId: string) => void;
  setDate: (date: string) => void;
  setShowtime: (showtimeId: string) => void;
  toggleSeat: (seatCode: string) => void;
  setSeats: (seats: string[]) => void;
  reset: () => void;
}

const EMPTY_SELECTION: BookingSelection = {
  movieId: null,
  cinemaId: null,
  date: null,
  showtimeId: null,
  seats: [],
};

const BookingContext = createContext<BookingContextValue | null>(null);

export function BookingProvider({ children }: { children: ReactNode }) {
  const [selection, setSelection] = useState<BookingSelection>(EMPTY_SELECTION);

  const setMovie = useCallback((movieId: string) => {
    setSelection({ ...EMPTY_SELECTION, movieId });
  }, []);

  const setCinema = useCallback((cinemaId: string) => {
    setSelection((prev) => ({ ...prev, cinemaId, date: null, showtimeId: null, seats: [] }));
  }, []);

  const setDate = useCallback((date: string) => {
    setSelection((prev) => ({ ...prev, date, showtimeId: null, seats: [] }));
  }, []);

  const setShowtime = useCallback((showtimeId: string) => {
    setSelection((prev) => ({ ...prev, showtimeId, seats: [] }));
  }, []);

  const toggleSeat = useCallback((seatCode: string) => {
    setSelection((prev) => {
      const isSelected = prev.seats.includes(seatCode);
      if (isSelected) {
        return { ...prev, seats: prev.seats.filter((s) => s !== seatCode) };
      }
      if (prev.seats.length >= 10) {
        return prev;
      }
      return { ...prev, seats: [...prev.seats, seatCode] };
    });
  }, []);

  const setSeats = useCallback((seats: string[]) => {
    setSelection((prev) => ({ ...prev, seats }));
  }, []);

  const reset = useCallback(() => {
    setSelection(EMPTY_SELECTION);
  }, []);

  const value = useMemo(
    () => ({ selection, setMovie, setCinema, setDate, setShowtime, toggleSeat, setSeats, reset }),
    [selection, setMovie, setCinema, setDate, setShowtime, toggleSeat, setSeats, reset],
  );

  return <BookingContext.Provider value={value}>{children}</BookingContext.Provider>;
}

export function useBooking(): BookingContextValue {
  const context = useContext(BookingContext);
  if (!context) {
    throw new Error('useBooking must be used within a BookingProvider');
  }
  return context;
}
