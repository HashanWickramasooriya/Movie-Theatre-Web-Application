export type MovieStatus = 'now_showing' | 'coming_soon';

export interface Movie {
  id: string;
  title: string;
  posterUrl: string;
  backdropUrl: string;
  genres: string[];
  runtimeMinutes: number;
  contentRating: string;
  language: string;
  releaseDate: string;
  status: MovieStatus;
  synopsis: string;
  cast: string[];
  director: string;
  imdbRating: number | null;
  trailerUrl: string;
}

export interface CinemaHall {
  code: string;
  name: string;
  projection: string;
  totalRows: number;
  seatsPerRow: number;
  premiumRows: string[];
}

export interface Cinema {
  id: string;
  name: string;
  address: string;
  city: string;
  features: string[];
  halls: CinemaHall[];
}

export interface Showtime {
  id: string;
  movieId: string;
  cinemaId: string;
  hallCode: string;
  date: string;
  time: string;
  standardPrice: number;
  premiumPrice: number;
}

export type SeatStatus = 'available' | 'selected' | 'occupied' | 'premium';

export interface SeatMapRow {
  row: string;
  seats: number[];
  premium: boolean;
}

export interface BookingSelection {
  movieId: string | null;
  cinemaId: string | null;
  date: string | null;
  showtimeId: string | null;
  seats: string[];
}

export interface StoredBooking {
  reference: string;
  showtimeId: string;
  movieId: string;
  movieTitle: string;
  posterUrl: string;
  cinemaName: string;
  hallCode: string;
  date: string;
  time: string;
  seats: string[];
  totalPrice: number;
  customerName: string;
  customerEmail: string;
  createdAt: string;
}
