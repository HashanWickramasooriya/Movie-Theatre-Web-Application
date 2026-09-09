import { STEP_LABELS, STEP_ORDER, type BookingStep } from './steps';

export function Stepper({ current }: { current: BookingStep }) {
  const currentIndex = STEP_ORDER.indexOf(current);

  return (
    <ol className="mb-8 flex flex-wrap gap-2" aria-label="Booking progress">
      {STEP_ORDER.map((step, index) => {
        const isActive = step === current;
        const isComplete = index < currentIndex;
        return (
          <li
            key={step}
            aria-current={isActive ? 'step' : undefined}
            className={`rounded-full border px-3.5 py-1.5 text-xs font-medium sm:text-sm ${
              isActive
                ? 'border-gold-400 bg-gold-400 text-ink-950'
                : isComplete
                  ? 'border-emerald-700 text-emerald-400'
                  : 'border-ink-600 text-neutral-500'
            }`}
          >
            {index + 1}. {STEP_LABELS[step]}
          </li>
        );
      })}
    </ol>
  );
}
