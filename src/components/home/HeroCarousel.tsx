import { useEffect, useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import type { Movie } from '../../types';
import { formatRuntime } from '../../lib/format';

const AUTO_ADVANCE_MS = 7000;

export function HeroCarousel({ movies }: { movies: Movie[] }) {
  const [index, setIndex] = useState(0);
  const [paused, setPaused] = useState(false);
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  useEffect(() => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (paused || prefersReducedMotion || movies.length <= 1) return;

    timerRef.current = setInterval(() => {
      setIndex((prev) => (prev + 1) % movies.length);
    }, AUTO_ADVANCE_MS);

    return () => {
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, [paused, movies.length]);

  if (movies.length === 0) return null;
  const movie = movies[index];

  return (
    <section
      className="relative h-[86vh] min-h-[560px] w-full overflow-hidden"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
      aria-roledescription="carousel"
      aria-label="Featured movies"
    >
      {movies.map((m, i) => (
        <div
          key={m.id}
          className={`absolute inset-0 transition-opacity duration-700 ${i === index ? 'opacity-100' : 'opacity-0'}`}
          aria-hidden={i !== index}
        >
          <img
            src={m.backdropUrl}
            alt=""
            className="h-full w-full object-cover object-center"
            loading={i === 0 ? 'eager' : 'lazy'}
          />
          <div className="absolute inset-0 bg-fade-up" />
          <div className="absolute inset-0 bg-fade-side" />
        </div>
      ))}

      <div className="relative flex h-full items-end">
        <div className="container-page w-full pb-16 sm:pb-20">
          <div key={movie.id} className="max-w-xl animate-rise-in">
            <span className="inline-block rounded-md bg-ember-500 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-white">
              Now Showing
            </span>
            <h1 className="mt-4 text-balance font-display text-4xl font-extrabold leading-tight text-white sm:text-5xl">
              {movie.title}
            </h1>
            <div className="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-neutral-300">
              <span>{movie.genres.slice(0, 3).join(', ')}</span>
              <span aria-hidden="true">&middot;</span>
              <span>{formatRuntime(movie.runtimeMinutes)}</span>
              <span aria-hidden="true">&middot;</span>
              <span className="rounded border border-neutral-500 px-1.5 py-0.5 text-xs">{movie.contentRating}</span>
              {movie.imdbRating && (
                <>
                  <span aria-hidden="true">&middot;</span>
                  <span className="text-gold-300">&#9733; {movie.imdbRating.toFixed(1)}</span>
                </>
              )}
            </div>
            <p className="mt-4 line-clamp-3 max-w-lg text-sm leading-relaxed text-neutral-300 sm:text-base">
              {movie.synopsis}
            </p>
            <div className="mt-7 flex flex-wrap gap-3">
              <Link to={`/booking?movie=${movie.id}`} className="btn-primary">
                Book Tickets
              </Link>
              <Link to={`/movie/${movie.id}`} className="btn-secondary">
                View Details
              </Link>
            </div>
          </div>
        </div>
      </div>

      {movies.length > 1 && (
        <div className="absolute bottom-6 right-6 z-10 flex gap-2 sm:right-10">
          {movies.map((m, i) => (
            <button
              key={m.id}
              type="button"
              onClick={() => setIndex(i)}
              aria-label={`Show featured movie: ${m.title}`}
              aria-current={i === index}
              className={`h-1.5 rounded-full transition-all ${i === index ? 'w-8 bg-gold-400' : 'w-4 bg-white/30 hover:bg-white/50'}`}
            />
          ))}
        </div>
      )}
    </section>
  );
}
