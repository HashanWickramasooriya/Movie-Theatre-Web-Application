import { useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { nowShowingMovies, getMovieById } from '../data/movies';
import { cinemas, getCinemaById } from '../data/cinemas';
import { showtimes as allShowtimes } from '../data/showtimes';
import { formatDateLabel, formatMoney, formatTimeLabel } from '../lib/format';
import { useDocumentHead } from '../hooks/useDocumentHead';
import { EmptyState } from '../components/ui/EmptyState';

export default function Showtimes() {
  useDocumentHead({
    title: 'Showtimes',
    description: 'Pick a movie, cinema, and date to see available showtimes at Savoy Cinema.',
  });

  const [movieId, setMovieId] = useState(nowShowingMovies[0]?.id ?? '');
  const [cinemaId, setCinemaId] = useState('');

  const dates = useMemo(() => {
    const relevant = allShowtimes.filter((s) => s.movieId === movieId && (!cinemaId || s.cinemaId === cinemaId));
    return Array.from(new Set(relevant.map((s) => s.date))).sort();
  }, [movieId, cinemaId]);

  const [date, setDate] = useState(dates[0] ?? '');
  const activeDate = dates.includes(date) ? date : dates[0];

  const results = useMemo(
    () =>
      allShowtimes
        .filter((s) => s.movieId === movieId && (!cinemaId || s.cinemaId === cinemaId) && s.date === activeDate)
        .sort((a, b) => a.time.localeCompare(b.time)),
    [movieId, cinemaId, activeDate],
  );

  const movie = getMovieById(movieId);

  return (
    <div className="container-page py-12 sm:py-16">
      <h1 className="text-3xl font-bold sm:text-4xl">Showtimes</h1>
      <p className="mt-2 text-neutral-400">Choose a movie, cinema, and date to see what's playing.</p>

      <div className="mt-8 grid gap-3 sm:grid-cols-3">
        <div>
          <label htmlFor="showtime-movie" className="field-label">
            Movie
          </label>
          <select
            id="showtime-movie"
            value={movieId}
            onChange={(e) => {
              setMovieId(e.target.value);
              setDate('');
            }}
            className="field-input"
          >
            {nowShowingMovies.map((m) => (
              <option key={m.id} value={m.id}>
                {m.title}
              </option>
            ))}
          </select>
        </div>

        <div>
          <label htmlFor="showtime-cinema" className="field-label">
            Cinema
          </label>
          <select
            id="showtime-cinema"
            value={cinemaId}
            onChange={(e) => {
              setCinemaId(e.target.value);
              setDate('');
            }}
            className="field-input"
          >
            <option value="">All cinemas</option>
            {cinemas.map((c) => (
              <option key={c.id} value={c.id}>
                {c.name}
              </option>
            ))}
          </select>
        </div>

        <div>
          <span className="field-label">Date</span>
          <div className="flex flex-wrap gap-2">
            {dates.map((d) => (
              <button
                key={d}
                type="button"
                onClick={() => setDate(d)}
                aria-pressed={d === activeDate}
                className={`rounded-lg px-3 py-2 text-sm font-medium transition-colors ${
                  d === activeDate ? 'bg-gold-400 text-ink-950' : 'bg-ink-800 text-neutral-300 hover:bg-ink-700'
                }`}
              >
                {formatDateLabel(d)}
              </button>
            ))}
          </div>
        </div>
      </div>

      <div className="mt-10">
        {results.length === 0 ? (
          <EmptyState
            title="No showtimes available"
            description="There are no scheduled showtimes for this combination. Try a different movie, cinema, or date."
          />
        ) : (
          <div className="flex flex-col gap-3">
            {results.map((show) => {
              const cinema = getCinemaById(show.cinemaId);
              return (
                <Link
                  key={show.id}
                  to={`/booking?showtime=${show.id}`}
                  className="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-ink-700 bg-ink-900 p-4 transition-colors hover:border-gold-400/50 sm:p-5"
                >
                  <div className="flex items-center gap-4">
                    {movie && (
                      <img src={movie.posterUrl} alt="" width={48} height={72} className="hidden h-16 w-11 rounded-md object-cover sm:block" />
                    )}
                    <div>
                      <p className="font-semibold text-white">{formatTimeLabel(show.time)}</p>
                      <p className="text-sm text-neutral-400">
                        {cinema?.name} &middot; Hall {show.hallCode}
                      </p>
                    </div>
                  </div>
                  <span className="text-sm font-medium text-gold-300">from {formatMoney(show.standardPrice)}</span>
                </Link>
              );
            })}
          </div>
        )}
      </div>
    </div>
  );
}
