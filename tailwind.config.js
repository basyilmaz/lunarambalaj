import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                // Mevcut renkler (backward compatibility)
                'lunar-gold': '#debea9',
                'lunar-gold-dark': '#c8aa95',
                // Referans tema renkleri
                'primary-yellow': '#f7bc28',
                'dark-charcoal': '#1e1e1e',
                'light-gray': '#a1a2a4',
                'dark-brown': '#221e14',
                'success-green': '#61CE70',
                'info-blue': '#6EC1E4',
                'text-gray': '#54595F',
            },
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
                heading: ['Montserrat', 'sans-serif'],
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out',
                'slide-up': 'slideUp 0.6s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },
    plugins: [],
};
