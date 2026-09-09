import { Link } from 'react-router-dom';

interface SectionHeaderProps {
  eyebrow?: string;
  title: string;
  viewAllTo?: string;
}

export function SectionHeader({ eyebrow, title, viewAllTo }: SectionHeaderProps) {
  return (
    <div className="mb-6 flex items-end justify-between gap-4">
      <div>
        {eyebrow && <p className="mb-1 text-xs font-semibold uppercase tracking-[0.2em] text-gold-400">{eyebrow}</p>}
        <h2 className="text-2xl font-bold sm:text-3xl">{title}</h2>
      </div>
      {viewAllTo && (
        <Link to={viewAllTo} className="hidden shrink-0 text-sm font-medium text-neutral-400 hover:text-gold-300 sm:inline-flex sm:items-center sm:gap-1">
          View all
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M9 6l6 6-6 6" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </Link>
      )}
    </div>
  );
}
