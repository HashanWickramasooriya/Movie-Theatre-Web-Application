export type BookingStep = 'movie' | 'cinema' | 'date' | 'showtime' | 'seats' | 'review' | 'confirm';

export const STEP_ORDER: BookingStep[] = ['movie', 'cinema', 'date', 'showtime', 'seats', 'review', 'confirm'];

export const STEP_LABELS: Record<BookingStep, string> = {
  movie: 'Movie',
  cinema: 'Cinema',
  date: 'Date',
  showtime: 'Showtime',
  seats: 'Seats',
  review: 'Review',
  confirm: 'Confirmation',
};
