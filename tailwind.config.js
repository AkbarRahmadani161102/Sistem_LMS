/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    './index.php',
    './client/**/*.php',
    './client/*.php'
  ],
  theme: {
    extend: {
      backgroundImage: {
        'landing-page-quote': "url('../image/landing_page_quote.jpg')",
        'landing-page-biaya-sd': "url('../image/landing_page_biaya_sd.jpg')",
        'landing-page-biaya-smp': "url('../image/landing_page_biaya_smp.jpg')",
        'landing-page-biaya-sma': "url('../image/landing_page_biaya_sma.jpg')",
        'register-siswa': "url('../image/register_siswa.jpg')",
        'login-siswa': "url('../image/login_siswa.png')",
        'login-instruktur': "url('../image/login_instruktur.png')",
        'login-admin': "url('../image/login_admin.png')",
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
  darkMode: 'class'
}
