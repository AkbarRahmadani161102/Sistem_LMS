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
