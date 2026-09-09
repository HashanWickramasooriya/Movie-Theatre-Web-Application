import { Link } from 'react-router-dom';
import type { Movie } from '../../types';
import { formatRuntime } from '../../lib/format';

export function MovieCard({ movie }: { movie: Movie }) {
  return (
    <article className="group relative flex flex-col overflow-hidden rounded-xl bg-ink-900 ring-1 ring-ink-700 transition-all duration-300 hover:-translate-y-1 hover:ring-gold-400/40 hover:shadow-card">
      <Link to={`/movie/${movie.id}`} className="relative block aspect-[2/3] overflow-hidden bg-ink-800">
        <img
          src={movie.posterUrl}
          alt={`${movie.title} movie poster`}
          loading="lazy"
          width={300}
          height={450}
          className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <span
          className={`absolute left-2.5 top-2.5 rounded-md px-2 py-1 text-[11px] font-semibold uppercase tracking-wide ${
            movie.status === 'now_showing' ? 'bg-ember-500 text-white' : 'border border-gold-400 bg-ink-950/80 text-gold-300'
          }`}
        >
          {movie.status === 'now_showing' ? 'Now Showing' : 'Coming Soon'}
        </span>
        {movie.imdbRating && (
          <span className="absolute bottom-2.5 right-2.5 flex items-center gap-1 rounded-md bg-ink-950/80 px-2 py-1 text-xs font-semibold text-gold-300">
            <StarIcon /> {movie.imdbRating.toFixed(1)}
          </span>
        )}
      </Link>
      <div className="flex flex-1 flex-col gap-1.5 p-4">
        <h3 className="line-clamp-1 font-display text-base font-semibold text-white">
          <Link to={`/movie/${movie.id}`} className="hover:text-gold-300">
            {movie.title}
          </Link>
        </h3>
        <p className="line-clamp-1 text-xs text-neutral-400">{movie.genres.slice(0, 2).join(', ')}</p>
        <p className="text-xs text-neutral-500">
          {formatRuntime(movie.runtimeMinutes)} &middot; {movie.contentRating}
        </p>
        <Link
          to={`/movie/${movie.id}`}
          className="mt-3 inline-flex items-center justify-center rounded-lg border border-ink-600 py-2 text-sm font-medium text-neutral-200 transition-colors group-hover:border-gold-400 group-hover:text-gold-300"
        >
          {movie.status === 'now_showing' ? 'Book Tickets' : 'View Details'}
        </Link>
      </div>
    </article>
  );
}

function StarIcon() {
  return (
    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
      <path d="M12 2.5 14.9 9l7 .6-5.3 4.6 1.6 6.8-6.2-3.7-6.2 3.7 1.6-6.8L2 9.6l7-.6L12 2.5Z" />
    </svg>
  );
}
