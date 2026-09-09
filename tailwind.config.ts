import type { Config } from 'tailwindcss';

export default {
  content: ['./index.html', './src/**/*.{ts,tsx}'],
  theme: {
    extend: {
      colors: {
        ink: {
          950: '#0a0a0c',
          900: '#111114',
          800: '#18181c',
          700: '#232328',
          600: '#2f2f36',
          500: '#3d3d45',
        },
        gold: {
          50: '#fbf3e3',
          100: '#f5e2b8',
          200: '#eccd85',
          300: '#e3b859',
          400: '#d6a13a',
          500: '#c28c2c',
          600: '#9e6f22',
        },
        ember: {
          500: '#c9432f',
          600: '#a8351f',
        },
      },
      fontFamily: {
        sans: ['"Inter"', 'system-ui', 'sans-serif'],
        display: ['"Plus Jakarta Sans"', '"Inter"', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        card: '0 20px 45px -20px rgba(0, 0, 0, 0.65)',
        pop: '0 10px 30px -10px rgba(214, 161, 58, 0.35)',
      },
      backgroundImage: {
        'fade-up': 'linear-gradient(180deg, rgba(10,10,12,0) 0%, rgba(10,10,12,0.85) 65%, #0a0a0c 100%)',
        'fade-side': 'linear-gradient(90deg, rgba(10,10,12,0.95) 0%, rgba(10,10,12,0.35) 55%, rgba(10,10,12,0.05) 100%)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-out both',
        'rise-in': 'riseIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both',
      },
      keyframes: {
        fadeIn: {
          from: { opacity: '0' },
          to: { opacity: '1' },
        },
        riseIn: {
          from: { opacity: '0', transform: 'translateY(16px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
      },
    },
  },
  plugins: [],
} satisfies Config;
