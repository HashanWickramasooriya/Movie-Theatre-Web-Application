import type { CinemaHall, SeatMapRow } from '../types';

export function buildSeatLayout(hall: CinemaHall): SeatMapRow[] {
  const rows: SeatMapRow[] = [];
  for (let i = 0; i < hall.totalRows; i++) {
    const rowLetter = String.fromCharCode('A'.charCodeAt(0) + i);
    rows.push({
      row: rowLetter,
      seats: Array.from({ length: hall.seatsPerRow }, (_, seatIndex) => seatIndex + 1),
      premium: hall.premiumRows.includes(rowLetter),
    });
  }
  return rows;
}

export function isSeatPremium(seatCode: string, premiumRows: string[]): boolean {
  return premiumRows.includes(seatCode.charAt(0));
}

/** Small deterministic PRNG so "already booked" seats are stable for a given showtime. */
function mulberry32(seed: number) {
  let a = seed;
  return () => {
    a |= 0;
    a = (a + 0x6d2b79f5) | 0;
    let t = Math.imul(a ^ (a >>> 15), 1 | a);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

function hashString(input: string): number {
  let hash = 0;
  for (let i = 0; i < input.length; i++) {
    hash = (hash * 31 + input.charCodeAt(i)) | 0;
  }
  return hash;
}

/**
 * Simulates seats already taken by other moviegoers for a given showtime.
 * Deterministic per showtime id, so it looks like a real, partially-filled hall
 * without needing any backend or persisted state.
 */
export function getSimulatedOccupiedSeats(showtimeId: string, layout: SeatMapRow[]): string[] {
  const rng = mulberry32(hashString(showtimeId));
  const allSeats = layout.flatMap((row) => row.seats.map((seat) => `${row.row}${seat}`));
  const occupiedCount = Math.floor(allSeats.length * (0.12 + rng() * 0.28));

  const shuffled = [...allSeats];
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(rng() * (i + 1));
    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
  }

  return shuffled.slice(0, occupiedCount);
}
