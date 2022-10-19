import { defineStore } from "pinia";
import { loadLanguageAsync } from 'laravel-vue-i18n';

const languages = ['en', 'pt-BR']

export const useLanguageStore = defineStore("language", {
    state: () => ({
        _language: 'en',
    }),
    getters: {
        language: (state) => state._language
    },
    actions: {
        async loadLanguage(language) {
            if(!languages.includes(language)) return

            await loadLanguageAsync(language)
            this._language = language
        },
    },
    persist: {
        enabled: true
    },
});
