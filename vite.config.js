import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  build: {
    chunkSizeWarningLimit: 1000, // Increase chunk size warning limit (in kB)
    rollupOptions: {
      output: {
        manualChunks: {
          // Group large dependencies
          vendor: ['vue'],
          'rich-editor': ['@/Components/App/RichEditor.vue'],
          'post-preview': ['@/Components/App/PostPreview.vue'],
        },
      },
    },
  },
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
})
