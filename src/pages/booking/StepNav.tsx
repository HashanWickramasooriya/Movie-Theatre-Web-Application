export function StepNav({ onBack, label }: { onBack: () => void; label: string }) {
  return (
    <button type="button" onClick={onBack} className="btn-ghost mb-4 -ml-4 gap-1.5">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
        <path d="M15 6l-6 6 6 6" strokeLinecap="round" strokeLinejoin="round" />
      </svg>
      {label}
    </button>
  );
}
