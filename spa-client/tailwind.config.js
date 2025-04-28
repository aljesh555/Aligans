/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  // Make sure these classes are always included
  safelist: [
    'bg-blue-500',
    'text-white',
    'rounded-lg',
    'shadow-lg',
    'p-4',
    'p-6',
    'm-4',
    'text-2xl',
    'font-bold',
    'mb-2',
    'mb-4',
    'bg-white',
    'text-blue-500',
    'hover:bg-blue-100',
    'px-4',
    'py-2',
    'rounded',
    'transition-colors',
    'tailwind-test',
    'bg-red-500'
  ]
}

