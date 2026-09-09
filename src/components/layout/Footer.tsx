import { Link } from 'react-router-dom';
import { Logo } from './Logo';

export function Footer() {
  return (
    <footer className="border-t border-ink-700 bg-ink-900">
      <div className="container-page grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <Logo />
          <p className="mt-4 max-w-xs text-sm leading-relaxed text-neutral-400">
            Colombo's home for the movies everyone's talking about. Premium screens, comfortable seats, no queueing required.
          </p>
        </div>

        <FooterColumn title="Explore">
          <FooterLink to="/movies">Now Showing</FooterLink>
          <FooterLink to="/movies?status=coming_soon">Coming Soon</FooterLink>
          <FooterLink to="/showtimes">Showtimes</FooterLink>
          <FooterLink to="/cinemas">Our Cinemas</FooterLink>
        </FooterColumn>

        <FooterColumn title="Company">
          <FooterLink to="/about">About Savoy</FooterLink>
          <FooterLink to="/contact">Contact Us</FooterLink>
          <FooterLink to="/booking">Book Tickets</FooterLink>
          <FooterLink to="/my-bookings">My Bookings</FooterLink>
        </FooterColumn>

        <FooterColumn title="Visit Us">
          <p className="text-sm text-neutral-400">123 Galle Road, Wellawatte, Colombo</p>
          <a href="mailto:hello@savoycinema.lk" className="block text-sm text-neutral-400 hover:text-gold-300">
            hello@savoycinema.lk
          </a>
          <a href="tel:+94112345678" className="block text-sm text-neutral-400 hover:text-gold-300">
            +94 11 234 5678
          </a>
        </FooterColumn>
      </div>

      <div className="border-t border-ink-700">
        <div className="container-page flex flex-col items-center justify-between gap-2 py-6 text-center text-xs text-neutral-500 sm:flex-row sm:text-left">
          <p>&copy; {new Date().getFullYear()} Savoy Cinema. All rights reserved.</p>
          <p>
            Developed by{' '}
            <a
              href="https://hashanjanithwickramasooriya.netlify.app/"
              target="_blank"
              rel="noopener noreferrer"
              className="font-medium text-neutral-300 underline decoration-gold-400/50 underline-offset-4 transition-colors hover:text-gold-300"
            >
              Hashan Wickramasooriya
            </a>
          </p>
        </div>
      </div>
    </footer>
  );
}

function FooterColumn({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div>
      <h3 className="font-display text-sm font-semibold uppercase tracking-wide text-neutral-300">{title}</h3>
      <div className="mt-4 flex flex-col gap-2.5">{children}</div>
    </div>
  );
}

function FooterLink({ to, children }: { to: string; children: React.ReactNode }) {
  return (
    <Link to={to} className="text-sm text-neutral-400 transition-colors hover:text-gold-300">
      {children}
    </Link>
  );
}
