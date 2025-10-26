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
    // Background colors with opacity
    {
      pattern: /bg-(red|blue|green|yellow)-(50|100|400|500|700)\/(10|20)/,
    },
    // Text colors
    {
      pattern: /text-(red|blue|green|yellow)-(400|700|800)/,
    },
    // Inset ring colors with opacity
    {
      pattern: /inset-ring-(red|blue|green)-(400|600)\/(10|20)/,
    },
    // Dark mode specific classes
    'bg-red-50',
    'dark:bg-red-400/10',
    'dark:text-red-400',
    'dark:bg-blue-400/10',
    'dark:text-blue-400',
    'dark:bg-gray-200',
    // Additional dark mode classes from RoleEnum.php
    'dark:inset-ring-red-400/20',
    'dark:inset-ring-blue-400/20',
  ],

  darkMode: 'class',

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [forms],
}
