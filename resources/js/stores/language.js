import { defineStore } from "pinia";
import i18next from 'i18next';

const languages = ['en', 'pt']

export const useLanguageStore = defineStore("language", {
    state: () => ({
        language: 'en',
    }),
    actions: {
        async loadLanguage(language) {
            if(!languages.includes(language)) return

            await i18next.changeLanguage(language);
            this.language = language
        },
    },
    persist: {
        enabled: true
    },
});
