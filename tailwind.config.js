/** @type {import('tailwindcss').Config} */
export default {
  content: ["./src/**/*.{html,js,php}"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Primary font
        display: ['InterDisplay', 'sans-serif'], // Display font
      },
    },
  },
  plugins: [
    {
      tailwindcss: {},
      autoprefixer: {},
      cssnano: {
        preset: 'default',
      },
    },
  ],
}
