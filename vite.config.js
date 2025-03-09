import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  css: {
    preprocessorOptions: {
      less: {
        javascriptEnabled: true, // Enable JavaScript in Less for Ant Design
      },
    },
  },
  resolve: {
    alias: {
      'antd/dist/antd.css': 'antd/dist/reset.css', // Map old path if needed
    },
  },
  base:"./",
})
