import { Link } from 'react-router-dom';
import { HeroCarousel } from '../components/home/HeroCarousel';
import { MovieCard } from '../components/ui/MovieCard';
import { SectionHeader } from '../components/ui/SectionHeader';
import { nowShowingMovies, comingSoonMovies } from '../data/movies';
import { cinemas } from '../data/cinemas';
import { useDocumentHead } from '../hooks/useDocumentHead';

export default function Home() {
  useDocumentHead({
    title: 'Book Movie Tickets Online',
    description: 'Browse now showing and coming soon movies at Savoy Cinema and book your seats online.',
  });

  const popularMovies = [...nowShowingMovies].sort((a, b) => (b.imdbRating ?? 0) - (a.imdbRating ?? 0)).slice(0, 4);

  return (
    <div>
      <HeroCarousel movies={nowShowingMovies.slice(0, 4)} />

      <div className="container-page py-14 sm:py-20">
        <section aria-labelledby="now-showing-heading">
          <SectionHeader eyebrow="In Theatres" title="Now Showing" viewAllTo="/movies?status=now_showing" />
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-6 lg:grid-cols-6">
            {nowShowingMovies.slice(0, 6).map((movie) => (
              <MovieCard key={movie.id} movie={movie} />
            ))}
          </div>
        </section>

        <section className="mt-16" aria-labelledby="popular-heading">
          <SectionHeader eyebrow="Fan Favourites" title="Most Popular" />
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-6">
            {popularMovies.map((movie) => (
              <MovieCard key={movie.id} movie={movie} />
            ))}
          </div>
        </section>

        <section className="mt-16" aria-labelledby="coming-soon-heading">
          <SectionHeader eyebrow="Mark Your Calendar" title="Coming Soon" viewAllTo="/movies?status=coming_soon" />
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-6 lg:grid-cols-4">
            {comingSoonMovies.map((movie) => (
              <MovieCard key={movie.id} movie={movie} />
            ))}
          </div>
        </section>

        <section className="mt-16 overflow-hidden rounded-2xl bg-gradient-to-br from-ink-800 to-ink-900 ring-1 ring-ink-700">
          <div className="grid gap-8 p-8 sm:grid-cols-2 sm:p-12 lg:p-16">
            <div className="flex flex-col justify-center">
              <p className="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-gold-400">Savoy Rewards</p>
              <h2 className="text-2xl font-bold sm:text-3xl">Every 6th ticket is on us</h2>
              <p className="mt-3 max-w-md text-sm text-neutral-400">
                Book your seats online and every booking counts toward your next free ticket. No card to carry, we track it against your email.
              </p>
              <Link to="/booking" className="btn-primary mt-6 w-fit">
                Start Booking
              </Link>
            </div>
            <div className="flex flex-col justify-center gap-4">
              {[
                { label: 'Screens across Colombo', value: `${cinemas.reduce((sum, c) => sum + c.halls.length, 0)}` },
                { label: 'Cinema locations', value: `${cinemas.length}` },
                { label: 'Movies playing this week', value: `${nowShowingMovies.length}` },
              ].map((stat) => (
                <div key={stat.label} className="flex items-center justify-between border-b border-ink-700 pb-4 last:border-0 last:pb-0">
                  <span className="text-sm text-neutral-400">{stat.label}</span>
                  <span className="font-display text-2xl font-bold text-white">{stat.value}</span>
                </div>
              ))}
            </div>
          </div>
        </section>

        <section className="mt-16" aria-labelledby="cinemas-heading">
          <SectionHeader eyebrow="Find Us" title="Our Cinemas" viewAllTo="/cinemas" />
          <div className="grid gap-5 sm:grid-cols-3">
            {cinemas.map((cinema) => (
              <Link
                key={cinema.id}
                to="/cinemas"
                className="group rounded-xl border border-ink-700 bg-ink-900 p-6 transition-colors hover:border-gold-400/40"
              >
                <h3 className="font-display text-lg font-semibold text-white group-hover:text-gold-300">{cinema.name}</h3>
                <p className="mt-1 text-sm text-neutral-400">{cinema.address}</p>
                <p className="mt-3 text-xs text-neutral-500">{cinema.halls.length} screens</p>
              </Link>
            ))}
          </div>
        </section>
      </div>
    </div>
  );
}
