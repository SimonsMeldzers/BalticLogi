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
      },
    },
  },
  plugins: [],
}