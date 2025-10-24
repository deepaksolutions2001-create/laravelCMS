// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.js'], // app.css is imported by app.js
      refresh: true,
    }),
    tailwindcss(),
  ],
});