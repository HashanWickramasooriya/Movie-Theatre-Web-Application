import { Link } from 'react-router-dom';

export function Logo({ onClick }: { onClick?: () => void }) {
  return (
    <Link to="/" onClick={onClick} className="flex items-center gap-2 shrink-0" aria-label="Savoy Cinema home">
      <svg width="30" height="30" viewBox="0 0 32 32" fill="none" aria-hidden="true">
        <rect width="32" height="32" rx="7" className="fill-ink-800" />
        <path d="M7 13.5h18v11a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 7 24.5v-11Z" className="fill-gold-400" />
        <path
          d="M7 13.5h3.4L8.2 9.2H7v4.3Zm4.6 0h3.4l-2.2-4.3h-2.6l1.4 4.3Zm4.6 0h3.4l-2.2-4.3h-2.6l1.4 4.3Zm4.6 0h3.6l-2.2-4.3h-2.8l1.4 4.3Z"
          className="fill-ink-950"
        />
      </svg>
      <span className="font-display text-lg font-bold tracking-tight text-white">
        Savoy<span className="text-gold-400">Cinema</span>
      </span>
    </Link>
  );
}
