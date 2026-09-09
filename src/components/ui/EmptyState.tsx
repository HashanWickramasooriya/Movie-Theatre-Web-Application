import type { ReactNode } from 'react';

interface EmptyStateProps {
  title: string;
  description?: string;
  action?: ReactNode;
  icon?: ReactNode;
}

export function EmptyState({ title, description, action, icon }: EmptyStateProps) {
  return (
    <div className="flex flex-col items-center justify-center rounded-xl border border-dashed border-ink-600 px-6 py-16 text-center">
      {icon && <div className="mb-4 text-neutral-500">{icon}</div>}
      <h3 className="font-display text-lg font-semibold text-white">{title}</h3>
      {description && <p className="mt-2 max-w-sm text-sm text-neutral-400">{description}</p>}
      {action && <div className="mt-6">{action}</div>}
    </div>
  );
}
