/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                wine: '#722F37',
                pearl: '#E2DFD2',
                dust: '#EDF7F6',
                soft: '#FAF9F6',
                'wheat': {
                    '50': '#fdf9ef',
                    '100': '#fbf0da',
                    '200': '#f5deb3',
                    '300': '#eec683',
                    '400': '#e7a550',
                    '500': '#e18c2e',
                    '600': '#d27324',
                    '700': '#af5a1f',
                    '800': '#8b4721',
                    '900': '#713d1d',
                    '950': '#3d1e0d',
                },
                'armadillo': {
                    '50': '#f6f6f0',
                    '100': '#e7e8d9',
                    '200': '#d1d2b6',
                    '300': '#b7b88c',
                    '400': '#a3a26c',
                    '500': '#94915e',
                    '600': '#7f784f',
                    '700': '#665d42',
                    '800': '#584f3b',
                    '900': '#4e4637',
                    '950': '#2b251d',
                },

            }
        },
    },
    plugins: [],
}

