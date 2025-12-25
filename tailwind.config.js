/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",           // Memindai semua file PHP
    "./assets/js/**/*.js",  // Memindai semua file JS
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#5271ff', // Warna biru request Anda
          dark: '#3b52d9',    // Biru lebih tua untuk hover
          light: '#eff4ff',   // Biru sangat muda untuk background
        },
      },
      fontFamily: {
        sans: ['Poppins', 'Montserrat', 'sans-serif'],
      },
      borderRadius: {
        '4xl': '2rem',
      }
    },
  },
  plugins: [],
}