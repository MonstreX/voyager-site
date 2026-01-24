import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  base: '/vendor/voyager-site/',
  build: {
    outDir: 'publishable/assets',
    emptyOutDir: false,
    cssCodeSplit: true,
    rollupOptions: {
      input: {
        app: 'resources/assets/js/app.js'
      },
      output: {
        entryFileNames: 'js/[name].js',
        format: 'es',
        manualChunks: undefined,
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            const baseName = assetInfo.name.replace(/\.css$/, '');
            return `css/${baseName}.css`;
          }
          return 'assets/[name].[ext]';
        }
      }
    }
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/assets/js')
    }
  }
});
