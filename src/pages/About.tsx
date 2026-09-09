import { Link } from 'react-router-dom';
import { useDocumentHead } from '../hooks/useDocumentHead';

export default function About() {
  useDocumentHead({
    title: 'About Us',
    description: "The story behind Savoy Cinema and what makes a night out with us worth it.",
  });

  return (
    <div>
      <section className="border-b border-ink-700 bg-ink-900">
        <div className="container-page py-16 text-center sm:py-24">
          <p className="text-xs font-semibold uppercase tracking-[0.2em] text-gold-400">Our Story</p>
          <h1 className="mx-auto mt-3 max-w-2xl text-balance text-3xl font-bold sm:text-5xl">
            A Colombo cinema built around the movie, not the extras
          </h1>
          <p className="mx-auto mt-5 max-w-xl text-neutral-400">
            We've spent years refining one thing: making sure the picture, the sound, and the seat are right, every single show.
          </p>
        </div>
      </section>

      <section className="container-page grid gap-12 py-16 lg:grid-cols-2 lg:py-20">
        <div className="flex flex-col justify-center gap-4">
          <h2 className="text-2xl font-bold text-white">Why people keep coming back</h2>
          <p className="leading-relaxed text-neutral-300">
            Savoy Cinema started as a single screen in Wellawatte and grew into three locations across Colombo because
            we kept doing the basics well: clean halls, sharp projection, seats that don't creak, and staff who
            actually want you to enjoy the film.
          </p>
          <p className="leading-relaxed text-neutral-300">
            We show the big Hollywood releases the same week as everyone else, but we also carve out room for the
            films people are still talking about weeks later. If you book online, you pick your own seat, no calling
            ahead, no guessing what's left when you arrive.
          </p>
        </div>

        <div className="grid grid-cols-2 gap-4">
          {[
            { label: 'Locations across Colombo', value: '3' },
            { label: 'Screens in total', value: '6' },
            { label: 'Showtimes daily', value: '25+' },
            { label: 'Years in the neighbourhood', value: '10+' },
          ].map((stat) => (
            <div key={stat.label} className="rounded-xl border border-ink-700 bg-ink-900 p-5">
              <p className="font-display text-3xl font-bold text-gold-300">{stat.value}</p>
              <p className="mt-1 text-sm text-neutral-400">{stat.label}</p>
            </div>
          ))}
        </div>
      </section>

      <section className="border-t border-ink-700 bg-ink-900">
        <div className="container-page flex flex-col items-center gap-4 py-14 text-center">
          <h2 className="text-2xl font-bold text-white">Ready for your next movie night?</h2>
          <p className="max-w-md text-neutral-400">Book online in under a minute, pick your own seats, and skip the queue at the counter.</p>
          <Link to="/movies" className="btn-primary">
            Browse Movies
          </Link>
        </div>
      </section>
    </div>
  );
}
