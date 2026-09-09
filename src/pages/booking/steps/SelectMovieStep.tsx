import { nowShowingMovies } from '../../../data/movies';
import { formatRuntime } from '../../../lib/format';
import { useBooking } from '../../../context/BookingContext';
import { EmptyState } from '../../../components/ui/EmptyState';

export function SelectMovieStep({ onNext }: { onNext: () => void }) {
  const { setMovie } = useBooking();

  if (nowShowingMovies.length === 0) {
    return <EmptyState title="No movies open for booking" description="Please check back soon." />;
  }

  return (
    <div>
      <h2 className="text-xl font-bold text-white">Choose a movie</h2>
      <div className="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
        {nowShowingMovies.map((movie) => (
          <button
            key={movie.id}
            type="button"
            onClick={() => {
              setMovie(movie.id);
              onNext();
            }}
            className="group flex flex-col overflow-hidden rounded-xl border border-ink-700 bg-ink-900 text-left transition-colors hover:border-gold-400/50"
          >
            <img src={movie.posterUrl} alt={`${movie.title} movie poster`} width={200} height={300} className="aspect-[2/3] w-full object-cover" loading="lazy" />
            <div className="p-3">
              <p className="line-clamp-1 text-sm font-semibold text-white group-hover:text-gold-300">{movie.title}</p>
              <p className="mt-0.5 text-xs text-neutral-500">
                {formatRuntime(movie.runtimeMinutes)} &middot; {movie.contentRating}
              </p>
            </div>
          </button>
        ))}
      </div>
    </div>
  );
}
