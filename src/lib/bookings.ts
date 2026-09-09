import type { StoredBooking } from '../types';
import { readJSON, writeJSON } from './storage';

const BOOKINGS_KEY = 'savoy_bookings_v1';
const SESSION_TAKEN_KEY = 'savoy_taken_seats_v1';

export function generateBookingReference(): string {
  const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  let code = '';
  for (let i = 0; i < 6; i++) {
    code += alphabet[Math.floor(Math.random() * alphabet.length)];
  }
  return `SVY-${code}`;
}

export function getStoredBookings(): StoredBooking[] {
  return readJSON<StoredBooking[]>(BOOKINGS_KEY, []);
}

export function saveBooking(booking: StoredBooking): void {
  const existing = getStoredBookings();
  writeJSON(BOOKINGS_KEY, [booking, ...existing]);
  recordLocallyTakenSeats(booking.showtimeId, booking.seats);
}

export function findBooking(reference: string, email: string): StoredBooking | undefined {
  return getStoredBookings().find(
    (b) => b.reference.toLowerCase() === reference.trim().toLowerCase() && b.customerEmail.toLowerCase() === email.trim().toLowerCase(),
  );
}

/** Seats this browser has already booked for a given showtime this session, layered on top of the simulated occupancy. */
export function getLocallyTakenSeats(showtimeId: string): string[] {
  const taken = readJSON<Record<string, string[]>>(SESSION_TAKEN_KEY, {});
  return taken[showtimeId] ?? [];
}

export function recordLocallyTakenSeats(showtimeId: string, seats: string[]): void {
  const taken = readJSON<Record<string, string[]>>(SESSION_TAKEN_KEY, {});
  taken[showtimeId] = [...(taken[showtimeId] ?? []), ...seats];
  writeJSON(SESSION_TAKEN_KEY, taken);
}
