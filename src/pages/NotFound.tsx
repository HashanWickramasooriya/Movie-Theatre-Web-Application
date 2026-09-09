import { Link } from 'react-router-dom';
import { useDocumentHead } from '../hooks/useDocumentHead';

export default function NotFound() {
  useDocumentHead({ title: 'Page Not Found' });

  return (
    <div className="container-page flex min-h-[70vh] flex-col items-center justify-center py-20 text-center">
      <p className="font-display text-7xl font-extrabold text-ink-600">404</p>
      <h1 className="mt-4 text-2xl font-bold text-white">This screen is empty</h1>
      <p className="mt-2 max-w-sm text-neutral-400">The page you're looking for doesn't exist or may have moved.</p>
      <Link to="/" className="btn-primary mt-8">
        Back to Home
      </Link>
    </div>
  );
}
