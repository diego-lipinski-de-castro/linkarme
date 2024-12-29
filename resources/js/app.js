import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import money, { format, unformat } from "v-money3";
import { createPinia } from 'pinia'
import piniaPersist from 'pinia-plugin-persist'
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light.css';
import i18n from "./i18n";
import AppSuspense from '@/Layouts/AppSuspense.vue'
import Spinner from '@/Components/Spinner.vue'
import { autoAnimatePlugin } from '@formkit/auto-animate/vue'

window.tippy = tippy

const pinia = createPinia()
pinia.use(piniaPersist)

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const _app = i18n(createApp({ render: () => h(App, props) }));

        _app.config.globalProperties.$filters = {
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
            },
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
            unformat(v, f = {
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
                return unformat(v, f);
            },
        };

        _app
            .use(plugin)
            .use(money)
            .use(pinia)
            .use(autoAnimatePlugin)
            .component('AppSuspense', AppSuspense)
            .component('Spinner', Spinner)
            .mixin({ methods: { route } });

        return _app.mount(el);
    },
});

InertiaProgress.init({ color: "#0aa1ed" });
