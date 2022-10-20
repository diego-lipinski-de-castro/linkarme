import i18next from "i18next";
import I18NextVue from "i18next-vue";
import Backend from "i18next-http-backend";
import LanguageDetector from 'i18next-browser-languagedetector';
import detector from "./detector";

const languageDetector = new LanguageDetector();
languageDetector.addDetector(detector);

export const i18nextPromise = i18next
	// i18next-http-backend
	// loads translations from your server
	// https://github.com/i18next/i18next-http-backend
	.use(Backend)
	// detect user language
	// learn more: https://github.com/i18next/i18next-browser-languageDetector
	.use(languageDetector)
	// init i18next
	// for all options read: https://www.i18next.com/overview/configuration-options
	.init({
		debug: true,
		fallbackLng: 'en',
		supportedLngs: ['en', 'pt'],
        backend: {
            loadPath: '/api/locales/{{lng}}',
        },
		detection: {
			order: ['customSessionStorage'],
			lookupSessionStorage: 'language',
		},
	});

export default function (app) {
	app.use(I18NextVue, { i18next });
	return app;
}
