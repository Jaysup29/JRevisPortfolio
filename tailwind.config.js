import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/views/livewire/**/*.php",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                'portfolio-dark': '#1e3a5f',
                'portfolio-blue': '#4a90e2',
                'portfolio-green': '#2dd4bf',
                'portfolio-red': '#ef4444',
                'portfolio-yellow': '#fbbf24',
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'slide-up': 'slideUp 0.4s ease-out forwards',
                'bounce-gentle': 'bounceGentle 2s infinite',
            },
            keyframes: {
                fadeIn: {
                '0%': { opacity: '0', transform: 'translateY(20px)' },
                '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                '0%': { transform: 'translateY(100%)' },
                '100%': { transform: 'translateY(0)' },
                },
                bounceGentle: {
                '0%, 100%': { transform: 'translateY(0)' },
                '50%': { transform: 'translateY(-10px)' },
                },
            },
            fontFamily: {
                'sans': ['Inter', 'system-ui', 'sans-serif'],
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-slow': 'bounce 2s infinite',
            },
            backdropBlur: {
                xs: '2px',
            },
            perspective: {
                '1000': '1000px',
            },
            transformStyle: {
                'preserve-3d': 'preserve-3d',
            },
            backfaceVisibility: {
                'hidden': 'hidden',
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        function({ addUtilities }) {
            addUtilities({
                '.transform-style-preserve-3d': {
                'transform-style': 'preserve-3d',
                },
                '.backface-hidden': {
                'backface-visibility': 'hidden',
                },
                '.rotate-y-180': {
                'transform': 'rotateY(180deg)',
                },
                '.perspective-1000': {
                'perspective': '1000px',
                }
            })
        }
    ],
}