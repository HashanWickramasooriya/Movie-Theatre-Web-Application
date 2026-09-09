export function PageLoader() {
  return (
    <div className="flex min-h-[60vh] items-center justify-center" role="status" aria-label="Loading">
      <div className="h-9 w-9 animate-spin rounded-full border-2 border-ink-600 border-t-gold-400" />
      <span className="sr-only">Loading...</span>
    </div>
  );
}
