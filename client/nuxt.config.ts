import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],

  vite: {
    plugins: [
      tailwindcss(),
    ],
  },

  modules: ['@nuxtjs/color-mode'],
  colorMode: {
    preference: 'light', // Default theme preference (light or dark)
    classSuffix: '', // (Optional) You can use a suffix for class names if needed
  },
})