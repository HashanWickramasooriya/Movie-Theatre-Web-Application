import { useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { MovieCard } from '../components/ui/MovieCard';
import { EmptyState } from '../components/ui/EmptyState';
import { movies, allGenres } from '../data/movies';
import { useDocumentHead } from '../hooks/useDocumentHead';
import type { MovieStatus } from '../types';

type SortKey = 'release_desc' | 'title_asc' | 'rating_desc';

export default function Movies() {
  useDocumentHead({
    title: 'Movies',
    description: 'Search and filter every movie now showing or coming soon at Savoy Cinema.',
  });

  const [searchParams, setSearchParams] = useSearchParams();
  const [search, setSearch] = useState(searchParams.get('q') ?? '');
  const [genre, setGenre] = useState('');
  const [status, setStatus] = useState<MovieStatus | ''>((searchParams.get('status') as MovieStatus | null) ?? '');
  const [sort, setSort] = useState<SortKey>('release_desc');

  const filtered = useMemo(() => {
    const term = search.trim().toLowerCase();
    const result = movies.filter((movie) => {
      const matchesTerm = !term || movie.title.toLowerCase().includes(term);
      const matchesGenre = !genre || movie.genres.includes(genre);
      const matchesStatus = !status || movie.status === status;
      return matchesTerm && matchesGenre && matchesStatus;
    });

    return [...result].sort((a, b) => {
      if (sort === 'title_asc') return a.title.localeCompare(b.title);
      if (sort === 'rating_desc') return (b.imdbRating ?? 0) - (a.imdbRating ?? 0);
      return b.releaseDate.localeCompare(a.releaseDate);
    });
  }, [search, genre, status, sort]);

  function handleReset() {
    setSearch('');
    setGenre('');
    setStatus('');
    setSort('release_desc');
    setSearchParams({});
  }

  return (
    <div className="container-page py-12 sm:py-16">
      <div className="mb-8">
        <h1 className="text-3xl font-bold sm:text-4xl">Movies</h1>
        <p className="mt-2 text-neutral-400">Browse everything playing at Savoy Cinema, and what's on the way.</p>
      </div>

      <div className="mb-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div className="relative sm:col-span-2 lg:col-span-1">
          <label htmlFor="movie-search" className="sr-only">
            Search movies
          </label>
          <input
            id="movie-search"
            type="search"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by title..."
            className="field-input"
          />
        </div>

        <div>
          <label htmlFor="genre-filter" className="sr-only">
            Filter by genre
          </label>
          <select id="genre-filter" value={genre} onChange={(e) => setGenre(e.target.value)} className="field-input">
            <option value="">All genres</option>
            {allGenres.map((g) => (
              <option key={g} value={g}>
                {g}
              </option>
            ))}
          </select>
        </div>

        <div>
          <label htmlFor="status-filter" className="sr-only">
            Filter by status
          </label>
          <select
            id="status-filter"
            value={status}
            onChange={(e) => setStatus(e.target.value as MovieStatus | '')}
            className="field-input"
          >
            <option value="">Now showing &amp; coming soon</option>
            <option value="now_showing">Now showing</option>
            <option value="coming_soon">Coming soon</option>
          </select>
        </div>

        <div>
          <label htmlFor="sort-select" className="sr-only">
            Sort movies
          </label>
          <select id="sort-select" value={sort} onChange={(e) => setSort(e.target.value as SortKey)} className="field-input">
            <option value="release_desc">Newest first</option>
            <option value="title_asc">Title A to Z</option>
            <option value="rating_desc">Highest rated</option>
          </select>
        </div>
      </div>

      <p className="mb-6 text-sm text-neutral-500" aria-live="polite">
        {filtered.length} {filtered.length === 1 ? 'movie' : 'movies'} found
      </p>

      {filtered.length === 0 ? (
        <EmptyState
          title="No movies match your search"
          description="Try a different title, or clear your filters to see everything we're showing."
          action={
            <button type="button" onClick={handleReset} className="btn-secondary">
              Clear filters
            </button>
          }
        />
      ) : (
        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-6 lg:grid-cols-5">
          {filtered.map((movie) => (
            <MovieCard key={movie.id} movie={movie} />
          ))}
        </div>
      )}
    </div>
  );
}
