import { lazy, Suspense } from 'react';
import { Routes, Route } from 'react-router-dom';
import { RootLayout } from './components/layout/RootLayout';
import { BookingProvider } from './context/BookingContext';
import { PageLoader } from './components/ui/PageLoader';

const Home = lazy(() => import('./pages/Home'));
const Movies = lazy(() => import('./pages/Movies'));
const MovieDetails = lazy(() => import('./pages/MovieDetails'));
const Showtimes = lazy(() => import('./pages/Showtimes'));
const Cinemas = lazy(() => import('./pages/Cinemas'));
const About = lazy(() => import('./pages/About'));
const Contact = lazy(() => import('./pages/Contact'));
const BookingPage = lazy(() => import('./pages/booking/BookingPage'));
const MyBookings = lazy(() => import('./pages/MyBookings'));
const NotFound = lazy(() => import('./pages/NotFound'));

export default function App() {
  return (
    <Suspense fallback={<PageLoader />}>
      <Routes>
        <Route element={<RootLayout />}>
          <Route index element={<Home />} />
          <Route path="movies" element={<Movies />} />
          <Route path="movie/:id" element={<MovieDetails />} />
          <Route path="showtimes" element={<Showtimes />} />
          <Route path="cinemas" element={<Cinemas />} />
          <Route path="about" element={<About />} />
          <Route path="contact" element={<Contact />} />
          <Route
            path="booking"
            element={
              <BookingProvider>
                <BookingPage />
              </BookingProvider>
            }
          />
          <Route path="my-bookings" element={<MyBookings />} />
          <Route path="*" element={<NotFound />} />
        </Route>
      </Routes>
    </Suspense>
  );
}
