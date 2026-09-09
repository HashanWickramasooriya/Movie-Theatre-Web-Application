import type { Cinema } from '../types';

const standardHall = (code: string, name: string, projection: string) => ({
  code,
  name,
  projection,
  totalRows: 8,
  seatsPerRow: 12,
  premiumRows: ['G', 'H'],
});

export const cinemas: Cinema[] = [
  {
    id: 'wellawatte',
    name: 'Savoy Cinema, Wellawatte',
    address: '123 Galle Road, Wellawatte',
    city: 'Colombo',
    features: ['Dolby Atmos', '4K Laser Projection', 'Recliner Seating', 'In-cinema Cafe'],
    halls: [
      standardHall('H1', 'Hall 1', '4K Laser Projection, Dolby Atmos'),
      standardHall('H2', 'Hall 2', '2K Digital Projection'),
    ],
  },
  {
    id: 'city-colombo',
    name: 'Savoy Cinema City, Colombo 03',
    address: '45 Union Place, Colombo 03',
    city: 'Colombo',
    features: ['2K Digital Projection', 'Online Seat Selection', 'Snack Bar'],
    halls: [
      standardHall('A1', 'Hall A', '2K Digital Projection'),
      standardHall('A2', 'Hall B', '2K Digital Projection'),
    ],
  },
  {
    id: 'liberty-plaza',
    name: 'Savoy Cinema, Liberty Plaza',
    address: 'Liberty Plaza, Colombo 03',
    city: 'Colombo',
    features: ['4K Laser Projection', 'Premium Recliners', 'Validated Mall Parking'],
    halls: [
      standardHall('L1', 'Hall 1', '4K Laser Projection'),
      standardHall('L2', 'Hall 2', '2K Digital Projection'),
    ],
  },
];

export function getCinemaById(id: string): Cinema | undefined {
  return cinemas.find((cinema) => cinema.id === id);
}
