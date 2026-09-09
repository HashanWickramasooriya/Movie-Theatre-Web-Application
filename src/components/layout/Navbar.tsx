import { useEffect, useRef, useState } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { Logo } from './Logo';
import { MobileMenu } from './MobileMenu';

const NAV_LINKS = [
  { to: '/', label: 'Home', end: true },
  { to: '/movies', label: 'Movies' },
  { to: '/showtimes', label: 'Showtimes' },
  { to: '/cinemas', label: 'Cinemas' },
  { to: '/about', label: 'About' },
  { to: '/contact', label: 'Contact' },
];

export function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const searchInputRef = useRef<HTMLInputElement>(null);
  const navigate = useNavigate();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    if (searchOpen) searchInputRef.current?.focus();
  }, [searchOpen]);

  function handleSearchSubmit(event: React.FormEvent) {
    event.preventDefault();
    const query = searchTerm.trim();
    setSearchOpen(false);
    navigate(query ? `/movies?q=${encodeURIComponent(query)}` : '/movies');
  }

  return (
    <header
      className={`sticky top-0 z-40 transition-colors duration-300 ${
        scrolled ? 'bg-ink-950/95 backdrop-blur-sm shadow-[0_1px_0_0_rgba(255,255,255,0.06)]' : 'bg-gradient-to-b from-ink-950/90 to-transparent'
      }`}
    >
      <div className="container-page flex h-16 items-center justify-between gap-4 lg:h-20">
        <Logo />

        <nav aria-label="Primary" className="hidden lg:block">
          <ul className="flex items-center gap-1">
            {NAV_LINKS.map((link) => (
              <li key={link.to}>
                <NavLink
                  to={link.to}
                  end={link.end}
                  className={({ isActive }) =>
                    `rounded-md px-3.5 py-2 text-sm font-medium transition-colors ${
                      isActive ? 'text-gold-400' : 'text-neutral-300 hover:text-white'
                    }`
                  }
                >
                  {link.label}
                </NavLink>
              </li>
            ))}
          </ul>
        </nav>

        <div className="flex items-center gap-2">
          <div className="hidden items-center sm:flex">
            {searchOpen ? (
              <form onSubmit={handleSearchSubmit} role="search" className="flex items-center">
                <label htmlFor="nav-search" className="sr-only">
                  Search movies
                </label>
                <input
                  ref={searchInputRef}
                  id="nav-search"
                  type="search"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  onBlur={() => !searchTerm && setSearchOpen(false)}
                  placeholder="Search movies..."
                  className="w-48 rounded-l-lg border border-ink-500 bg-ink-800 px-3 py-2 text-sm text-white placeholder:text-neutral-500 focus:border-gold-400"
                />
                <button type="submit" className="rounded-r-lg border border-l-0 border-ink-500 bg-ink-800 px-3 py-2 text-neutral-300 hover:text-gold-400" aria-label="Search">
                  <SearchIcon />
                </button>
              </form>
            ) : (
              <button
                type="button"
                onClick={() => setSearchOpen(true)}
                className="rounded-lg p-2 text-neutral-300 transition-colors hover:text-gold-400"
                aria-label="Open movie search"
              >
                <SearchIcon />
              </button>
            )}
          </div>

          <NavLink to="/booking" className="btn-primary hidden sm:inline-flex">
            Book Now
          </NavLink>

          <button
            type="button"
            className="rounded-lg p-2 text-neutral-200 lg:hidden"
            aria-label="Open menu"
            aria-expanded={menuOpen}
            onClick={() => setMenuOpen(true)}
          >
            <MenuIcon />
          </button>
        </div>
      </div>

      <MobileMenu open={menuOpen} onClose={() => setMenuOpen(false)} links={NAV_LINKS} />
    </header>
  );
}

function SearchIcon() {
  return (
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
      <circle cx="11" cy="11" r="7" />
      <path d="m20 20-3.5-3.5" strokeLinecap="round" />
    </svg>
  );
}

function MenuIcon() {
  return (
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
      <path d="M4 7h16M4 12h16M4 17h16" strokeLinecap="round" />
    </svg>
  );
}
