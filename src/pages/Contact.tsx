import { useState, type FormEvent } from 'react';
import { useDocumentHead } from '../hooks/useDocumentHead';

interface FormState {
  name: string;
  email: string;
  message: string;
}

const INITIAL_STATE: FormState = { name: '', email: '', message: '' };

export default function Contact() {
  useDocumentHead({
    title: 'Contact Us',
    description: 'Get in touch with Savoy Cinema for bookings, feedback, or partnership enquiries.',
  });

  const [form, setForm] = useState<FormState>(INITIAL_STATE);
  const [errors, setErrors] = useState<Partial<FormState>>({});
  const [status, setStatus] = useState<'idle' | 'submitting' | 'sent'>('idle');

  function validate(values: FormState): Partial<FormState> {
    const next: Partial<FormState> = {};
    if (!values.name.trim()) next.name = 'Please enter your name.';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email.trim())) next.email = 'Please enter a valid email address.';
    if (values.message.trim().length < 10) next.message = 'Tell us a bit more, at least 10 characters.';
    return next;
  }

  function handleSubmit(event: FormEvent) {
    event.preventDefault();
    const validationErrors = validate(form);
    setErrors(validationErrors);
    if (Object.keys(validationErrors).length > 0) return;

    setStatus('submitting');
    window.setTimeout(() => {
      setStatus('sent');
      setForm(INITIAL_STATE);
    }, 600);
  }

  return (
    <div className="container-page py-12 sm:py-16">
      <div className="mx-auto max-w-xl text-center">
        <h1 className="text-3xl font-bold sm:text-4xl">Contact Us</h1>
        <p className="mt-3 text-neutral-400">
          Questions about a booking, a group visit, or just want to tell us what you thought of the popcorn? Send us a note.
        </p>
      </div>

      <div className="mx-auto mt-10 max-w-xl">
        {status === 'sent' ? (
          <div className="rounded-xl border border-emerald-800 bg-emerald-950/40 p-6 text-center" role="status">
            <p className="font-display text-lg font-semibold text-emerald-300">Message received</p>
            <p className="mt-2 text-sm text-emerald-200/80">
              Thanks for reaching out. Our team usually replies within a day or two.
            </p>
            <button type="button" onClick={() => setStatus('idle')} className="btn-ghost mt-4 text-emerald-300 hover:text-emerald-200">
              Send another message
            </button>
          </div>
        ) : (
          <form onSubmit={handleSubmit} noValidate className="flex flex-col gap-5">
            <div>
              <label htmlFor="contact-name" className="field-label">
                Name
              </label>
              <input
                id="contact-name"
                type="text"
                value={form.name}
                onChange={(e) => setForm((prev) => ({ ...prev, name: e.target.value }))}
                className="field-input"
                aria-invalid={Boolean(errors.name)}
                aria-describedby={errors.name ? 'contact-name-error' : undefined}
              />
              {errors.name && (
                <p id="contact-name-error" className="mt-1.5 text-xs text-ember-500">
                  {errors.name}
                </p>
              )}
            </div>

            <div>
              <label htmlFor="contact-email" className="field-label">
                Email
              </label>
              <input
                id="contact-email"
                type="email"
                value={form.email}
                onChange={(e) => setForm((prev) => ({ ...prev, email: e.target.value }))}
                className="field-input"
                aria-invalid={Boolean(errors.email)}
                aria-describedby={errors.email ? 'contact-email-error' : undefined}
              />
              {errors.email && (
                <p id="contact-email-error" className="mt-1.5 text-xs text-ember-500">
                  {errors.email}
                </p>
              )}
            </div>

            <div>
              <label htmlFor="contact-message" className="field-label">
                Message
              </label>
              <textarea
                id="contact-message"
                rows={5}
                value={form.message}
                onChange={(e) => setForm((prev) => ({ ...prev, message: e.target.value }))}
                className="field-input resize-none"
                aria-invalid={Boolean(errors.message)}
                aria-describedby={errors.message ? 'contact-message-error' : undefined}
              />
              {errors.message && (
                <p id="contact-message-error" className="mt-1.5 text-xs text-ember-500">
                  {errors.message}
                </p>
              )}
            </div>

            <button type="submit" disabled={status === 'submitting'} className="btn-primary">
              {status === 'submitting' ? 'Sending...' : 'Send Message'}
            </button>
          </form>
        )}
      </div>
    </div>
  );
}
