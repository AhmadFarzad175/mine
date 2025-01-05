import { createI18n } from 'vue-i18n';
import en from './locales/en.json';
import fa from './locales/fa.json';

// Retrieve saved language from localStorage or default to English
const savedLanguage = localStorage.getItem('language') || 'en';

const messages = {
  en,
  fa,
};

const i18n = createI18n({
  legacy: false, // Enable Composition API mode
  locale: savedLanguage, // Set the saved language or default
  fallbackLocale: 'en', // Fallback language
  messages,
});

export default i18n;
