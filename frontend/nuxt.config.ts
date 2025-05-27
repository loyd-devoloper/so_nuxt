import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: false },
  alias: {
    '@': '/frontend'
  },
  runtimeConfig: {
    public: {
      'backend': 'http://localhost:8000'
    }
  },
  ssr: false,
  modules: [
    '@nuxt/ui',
    '@nuxt/icon',
    '@nuxt/image',
    '@nuxt/eslint',
    '@nuxt/fonts',
    '@vite-pwa/nuxt',
    '@pinia/nuxt',
    "@hebilicious/vue-query-nuxt",
  ],
  css: ['~/assets/css/main.css'],
  ui: {
    fonts: true,
    colorMode: false
  },
  vite: {
    plugins: [
      tailwindcss()
    ]
  },
  pwa: {
    /* PWA options */
  },

  pinia: {
    storesDirs: ['./stores/**', './custom-folder/stores/**'],
  },
  app: {
    head: {
      title: 'SO SYSTEM', // default fallback title
      htmlAttrs: {
        lang: 'en',
      },
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/LOGOONLY.svg' },
      ]
    }
  }
})