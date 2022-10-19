import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import money, { format } from "v-money3";
import { i18nVue } from "laravel-vue-i18n";
import { createPinia } from 'pinia'
import piniaPersist from 'pinia-plugin-persist'
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling

window.tippy = tippy

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

const pinia = createPinia()
pinia.use(piniaPersist)

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, app, props, plugin }) {
        const _app = createApp({ render: () => h(app, props) });

        _app.config.globalProperties.$filters = {
            currency(v, f = {
                prefix: 'R$ ',
                suffix: '',
                thousands: '.',
                decimal: ',',
                precision: 2,
                disableNegative: false,
                disabled: false,
                min: null,
                max: null,
                allowBlank: false,
                minimumNumberOfCharacters: 0,
            }) {
                return format(v, f);
            },
        };

        return _app
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(money)
            .use(i18nVue, {
                // lang: 'pt-BR',
                lang: window.localStorage.getItem("language") || "en",
                // fallbackLang: 'pt-BR',
                resolve: (lang) => {
                    const langs = import.meta.globEager("../../lang/*.json");
                    return langs[`../../lang/${lang}.json`].default;
                },
            })
            .use(pinia)
            .mount(el);
    },
});

InertiaProgress.init({ color: "#0aa1ed" });
