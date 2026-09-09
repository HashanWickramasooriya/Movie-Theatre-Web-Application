import { useMemo, useState } from 'react';
import { Link, Navigate, useParams } from 'react-router-dom';
import { getMovieById } from '../data/movies';
import { getShowtimesForMovie } from '../data/showtimes';
import { getCinemaById } from '../data/cinemas';
import { formatDateLabel, formatDateLong, formatRuntime, formatTimeLabel, formatMoney } from '../lib/format';
import { useDocumentHead } from '../hooks/useDocumentHead';
import { EmptyState } from '../components/ui/EmptyState';

export default function MovieDetails() {
  const { id } = useParams<{ id: string }>();
  const movie = id ? getMovieById(id) : undefined;

  useDocumentHead({
    title: movie ? movie.title : 'Movie not found',
    description: movie?.synopsis,
    image: movie?.posterUrl,
  });

  const showtimes = useMemo(() => (movie ? getShowtimesForMovie(movie.id) : []), [movie]);
  const dates = useMemo(() => Array.from(new Set(showtimes.map((s) => s.date))), [showtimes]);
  const [selectedDate, setSelectedDate] = useState(dates[0]);

  if (!id) return <Navigate to="/movies" replace />;
  if (!movie) {
    return (
      <div className="container-page py-20">
        <EmptyState
          title="Movie not found"
          description="This movie doesn't exist or may have been removed."
          action={
            <Link to="/movies" className="btn-secondary">
              Browse all movies
            </Link>
          }
        />
      </div>
    );
  }

  const activeDate = selectedDate && dates.includes(selectedDate) ? selectedDate : dates[0];
  const showtimesForDate = showtimes.filter((s) => s.date === activeDate);

  return (
    <div>
      <section
        className="relative min-h-[420px] w-full bg-cover bg-center pt-24 sm:min-h-[520px]"
        style={{ backgroundImage: `url(${movie.backdropUrl})` }}
      >
        <div className="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/85 to-ink-950/40" />
        <div className="container-page relative flex flex-col gap-8 pb-10 pt-6 sm:flex-row sm:items-end sm:pb-14">
          <img
            src={movie.posterUrl}
            alt={`${movie.title} movie poster`}
            width={220}
            height={330}
            className="mx-auto w-40 shrink-0 rounded-xl shadow-card ring-1 ring-white/10 sm:mx-0 sm:w-56"
          />
          <div className="text-center sm:text-left">
            <span
              className={`inline-block rounded-md px-2.5 py-1 text-xs font-semibold uppercase tracking-wide ${
                movie.status === 'now_showing' ? 'bg-ember-500 text-white' : 'border border-gold-400 text-gold-300'
              }`}
            >
              {movie.status === 'now_showing' ? 'Now Showing' : 'Coming Soon'}
            </span>
            <h1 className="mt-3 font-display text-3xl font-extrabold text-white sm:text-4xl">{movie.title}</h1>
            <div className="mt-3 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm text-neutral-300 sm:justify-start">
              <span>{movie.genres.join(', ')}</span>
              <span aria-hidden="true">&middot;</span>
              <span>{formatRuntime(movie.runtimeMinutes)}</span>
              <span aria-hidden="true">&middot;</span>
              <span className="rounded border border-neutral-500 px-1.5 py-0.5 text-xs">{movie.contentRating}</span>
              {movie.imdbRating && (
                <>
                  <span aria-hidden="true">&middot;</span>
                  <span className="text-gold-300">&#9733; {movie.imdbRating.toFixed(1)} IMDb</span>
                </>
              )}
            </div>
            <p className="mt-2 text-sm text-neutral-400">
              {movie.status === 'now_showing' ? 'Showing now' : `In cinemas ${formatDateLong(movie.releaseDate)}`} &middot; {movie.language}
            </p>
            <div className="mt-6 flex flex-wrap justify-center gap-3 sm:justify-start">
              {movie.status === 'now_showing' && showtimes.length > 0 && (
                <a href="#showtimes" className="btn-primary">
                  Book Tickets
                </a>
              )}
              <a href={movie.trailerUrl} target="_blank" rel="noopener noreferrer" className="btn-secondary">
                Watch Trailer
              </a>
            </div>
          </div>
        </div>
      </section>

      <div className="container-page grid gap-12 py-12 lg:grid-cols-3 lg:py-16">
        <div className="lg:col-span-2">
          <h2 className="text-xl font-bold text-white">Synopsis</h2>
          <p className="mt-3 max-w-2xl leading-relaxed text-neutral-300">{movie.synopsis}</p>

          <dl className="mt-8 grid grid-cols-2 gap-x-6 gap-y-4 sm:max-w-lg">
            <DetailItem label="Director" value={movie.director} />
            <DetailItem label="Cast" value={movie.cast.join(', ')} />
            <DetailItem label="Release Date" value={formatDateLong(movie.releaseDate)} />
            <DetailItem label="Language" value={movie.language} />
          </dl>
        </div>

        {movie.status === 'now_showing' && (
          <aside id="showtimes" className="scroll-mt-24">
            <h2 className="text-xl font-bold text-white">Showtimes</h2>
            {dates.length === 0 ? (
              <p className="mt-4 text-sm text-neutral-400">No upcoming showtimes are scheduled for this movie right now. Please check back soon.</p>
            ) : (
              <>
                <div className="mt-4 flex gap-2 overflow-x-auto pb-2">
                  {dates.map((date) => (
                    <button
                      key={date}
                      type="button"
                      onClick={() => setSelectedDate(date)}
                      aria-pressed={date === activeDate}
                      className={`shrink-0 rounded-lg px-4 py-2 text-sm font-medium transition-colors ${
                        date === activeDate ? 'bg-gold-400 text-ink-950' : 'bg-ink-800 text-neutral-300 hover:bg-ink-700'
                      }`}
                    >
                      {formatDateLabel(date)}
                    </button>
                  ))}
                </div>

                <div className="mt-4 flex flex-col gap-3">
                  {showtimesForDate.map((show) => {
                    const cinema = getCinemaById(show.cinemaId);
                    return (
                      <Link
                        key={show.id}
                        to={`/booking?showtime=${show.id}`}
                        className="flex items-center justify-between rounded-lg border border-ink-700 bg-ink-900 px-4 py-3 transition-colors hover:border-gold-400/50"
                      >
                        <div>
                          <p className="font-semibold text-white">{formatTimeLabel(show.time)}</p>
                          <p className="text-xs text-neutral-400">
                            {cinema?.name} &middot; Hall {show.hallCode}
                          </p>
                        </div>
                        <span className="text-sm font-medium text-gold-300">from {formatMoney(show.standardPrice)}</span>
                      </Link>
                    );
                  })}
                </div>
              </>
            )}
          </aside>
        )}
      </div>
    </div>
  );
}

function DetailItem({ label, value }: { label: string; value: string }) {
  return (
    <div>
      <dt className="text-xs font-semibold uppercase tracking-wide text-neutral-500">{label}</dt>
      <dd className="mt-1 text-sm text-neutral-200">{value}</dd>
    </div>
  );
}
