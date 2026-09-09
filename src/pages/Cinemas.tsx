import { Link } from 'react-router-dom';
import { cinemas } from '../data/cinemas';
import { useDocumentHead } from '../hooks/useDocumentHead';

export default function Cinemas() {
  useDocumentHead({
    title: 'Our Cinemas',
    description: 'Find a Savoy Cinema location near you, with hall types, features, and showtimes.',
  });

  return (
    <div className="container-page py-12 sm:py-16">
      <h1 className="text-3xl font-bold sm:text-4xl">Our Cinemas</h1>
      <p className="mt-2 max-w-2xl text-neutral-400">
        Three locations across Colombo, each with comfortable seating and a screen worth the trip.
      </p>

      <div className="mt-10 grid gap-6 lg:grid-cols-3">
        {cinemas.map((cinema) => (
          <article key={cinema.id} className="flex flex-col rounded-2xl border border-ink-700 bg-ink-900 p-6">
            <h2 className="font-display text-xl font-bold text-white">{cinema.name}</h2>
            <p className="mt-1 text-sm text-neutral-400">{cinema.address}</p>

            <div className="mt-5">
              <p className="text-xs font-semibold uppercase tracking-wide text-neutral-500">Halls</p>
              <ul className="mt-2 flex flex-col gap-1.5">
                {cinema.halls.map((hall) => (
                  <li key={hall.code} className="flex items-center justify-between text-sm">
                    <span className="text-neutral-300">
                      {hall.name} <span className="text-neutral-500">({hall.code})</span>
                    </span>
                    <span className="text-neutral-500">{hall.projection}</span>
                  </li>
                ))}
              </ul>
            </div>

            <div className="mt-5">
              <p className="text-xs font-semibold uppercase tracking-wide text-neutral-500">Features</p>
              <div className="mt-2 flex flex-wrap gap-1.5">
                {cinema.features.map((feature) => (
                  <span key={feature} className="rounded-full border border-ink-600 px-2.5 py-1 text-xs text-neutral-300">
                    {feature}
                  </span>
                ))}
              </div>
            </div>

            <Link to={`/showtimes`} className="btn-secondary mt-6">
              See Showtimes
            </Link>
          </article>
        ))}
      </div>
    </div>
  );
}
