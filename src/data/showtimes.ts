import type { Showtime } from '../types';
import { nowShowingMovies } from './movies';
import { cinemas } from './cinemas';

const TIME_SLOTS = ['11:00', '15:30', '19:30', '22:00'];
const DAYS_AHEAD = 5;

function formatDate(date: Date): string {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

function priceForCinema(cinemaId: string): { standard: number; premium: number } {
  if (cinemaId === 'wellawatte') return { standard: 1200, premium: 1600 };
  return { standard: 950, premium: 1300 };
}

function generateShowtimes(): Showtime[] {
  const showtimes: Showtime[] = [];
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  let hallCursor = 0;
  const hallSlots = cinemas.flatMap((cinema) => cinema.halls.map((hall) => ({ cinema, hall })));

  for (let dayOffset = 0; dayOffset < DAYS_AHEAD; dayOffset++) {
    const date = new Date(today);
    date.setDate(date.getDate() + dayOffset);
    const dateStr = formatDate(date);

    nowShowingMovies.forEach((movie, movieIndex) => {
      // Each movie plays 2 slots per day, rotating across cinema halls so the
      // schedule looks like a real multiplex rather than everything at once.
      const slotsForMovie = movieIndex % 2 === 0 ? [TIME_SLOTS[0], TIME_SLOTS[2]] : [TIME_SLOTS[1], TIME_SLOTS[3]];

      slotsForMovie.forEach((time) => {
        const { cinema, hall } = hallSlots[hallCursor % hallSlots.length];
        hallCursor++;
        const price = priceForCinema(cinema.id);

        showtimes.push({
          id: `${movie.id}__${cinema.id}__${hall.code}__${dateStr}__${time}`,
          movieId: movie.id,
          cinemaId: cinema.id,
          hallCode: hall.code,
          date: dateStr,
          time,
          standardPrice: price.standard,
          premiumPrice: price.premium,
        });
      });
    });
  }

  return showtimes;
}

export const showtimes: Showtime[] = generateShowtimes();

export function getShowtimesForMovie(movieId: string): Showtime[] {
  return showtimes
    .filter((s) => s.movieId === movieId)
    .sort((a, b) => (a.date + a.time).localeCompare(b.date + b.time));
}

export function getShowtimeById(id: string): Showtime | undefined {
  return showtimes.find((s) => s.id === id);
}

export function getAvailableDatesForMovie(movieId: string): string[] {
  return Array.from(new Set(getShowtimesForMovie(movieId).map((s) => s.date))).sort();
}
