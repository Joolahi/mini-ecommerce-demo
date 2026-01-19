import { defineConfig } from 'vite'
import tailwindcss from 'tailwindcss'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue(), tailwindcss()],
})
