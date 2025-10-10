import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],

  safelist: [
    {
      pattern: /bg-(red|green|blue|yellow)-(50|100|400|500|700)/,
    },
    {
      pattern: /text-(red|green|blue|yellow)-(400|700|800)/,
    },
    {
      pattern: /inset-ring-(red|green|blue)-(400|600)\/(10|20)/,
    },
    'dark:bg-red-400/10',
    'dark:text-red-400',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [forms],
}
