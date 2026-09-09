import { useEffect } from 'react';

interface DocumentHeadOptions {
  title: string;
  description?: string;
  image?: string;
}

function setMetaTag(attribute: 'name' | 'property', key: string, content: string) {
  let element = document.querySelector<HTMLMetaElement>(`meta[${attribute}="${key}"]`);
  if (!element) {
    element = document.createElement('meta');
    element.setAttribute(attribute, key);
    document.head.appendChild(element);
  }
  element.setAttribute('content', content);
}

/** Sets the document title and description/OG meta tags for the current route. */
export function useDocumentHead({ title, description, image }: DocumentHeadOptions) {
  useEffect(() => {
    const fullTitle = `${title} | Savoy Cinema`;
    document.title = fullTitle;
    setMetaTag('property', 'og:title', fullTitle);

    if (description) {
      setMetaTag('name', 'description', description);
      setMetaTag('property', 'og:description', description);
    }

    if (image) {
      setMetaTag('property', 'og:image', image);
    }
  }, [title, description, image]);
}
