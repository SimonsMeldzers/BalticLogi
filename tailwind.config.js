/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.php",   
    "./src/**/*.php",       
    "./components/**/*.php" 
  ],
  theme: {
    extend: {
      colors: {
        primary: '#89A8B2',
        secondary: '#B3C8CF',
        tertiary: '#E5E1DA',
        danger: '#E07B39',
      },
      boxShadow: {
        '3xl': '0px 2px 8px rgba(0, 0, 0, 0.15)',
      },
      screens: {
      },
    },
  },
  plugins: [],
}