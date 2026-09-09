# Savoy Cinema

A premium, frontend-only movie theatre website. Browse now showing and coming soon movies, check showtimes across three Colombo cinemas, and book seats with a real seat map, all running entirely in the browser.

## Stack

- React 18 + TypeScript
- Vite (build tool and dev server)
- React Router v6 (client-side routing)
- Tailwind CSS
- No backend, no database, no API server. Movie, cinema, and showtime data is local and typed; bookings are simulated and saved to `localStorage`.

## Getting started

```sh
npm install
npm run dev
```

Open the printed local URL. To type-check, lint, and build for production:

```sh
npm run typecheck
npm run lint
npm run build
npm run preview   # serve the production build locally
```

The production build outputs to `dist/`.

## Deploying

This is a static single-page app. Deploy the `dist/` folder to any static host.

- **Netlify**: connect the repo, build command `npm run build`, publish directory `dist`. `public/_redirects` (copied into `dist/_redirects` at build time) handles the SPA fallback so deep links like `/movies` or `/movie/tron-ares` don't 404 on refresh.
- **Vercel**: connect the repo, framework preset "Vite" (build command `npm run build`, output directory `dist`). `vercel.json` at the repo root rewrites all routes to `index.html` for the same reason.

No environment variables are required for either platform.

## How the data works

- `src/data/movies.ts`: a fixed set of real, currently relevant movies with posters and backdrops hotlinked from TMDB's public image CDN, used for demo purposes.
- `src/data/cinemas.ts`: three fictional Savoy Cinema locations, each with two halls.
- `src/data/showtimes.ts`: showtimes are generated at load time for the next 5 days relative to the visitor's actual system clock, so the schedule never looks stale.
- `src/lib/seats.ts`: builds each hall's seat grid and deterministically simulates a realistic set of already-occupied seats per showtime (seeded by the showtime id, so it's stable across reloads without needing a server).
- `src/lib/bookings.ts`: booking confirmations are saved to `localStorage` on the device that made them. There is no shared backend, so a booking made on one device or browser will not be visible from another.

## Limitations (by design)

- No real payment processing, this is a simulated checkout.
- No real account system. "My Bookings" looks up bookings stored in the current browser's `localStorage` by reference number and email.
- Seat occupancy is simulated for realism, not tracked against real inventory.
- Trailer links open a YouTube search for the movie's official trailer rather than a specific embedded video, to avoid linking to a video ID that could be wrong, removed, or region-locked.
