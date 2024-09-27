import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from 'ziggy-js';
import money, { format } from "v-money3";
import { createPinia } from 'pinia'
import piniaPersist from 'pinia-plugin-persist'
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // optional for styling
import 'tippy.js/themes/light.css';
import i18n from "./i18n";
import AppSuspense from '@/Layouts/AppSuspense.vue'
import Spinner from '@/Components/Spinner.vue'
import Particles from "@tsparticles/vue3";
import { loadFull } from "tsparticles"

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
    setup({ el, app, props, plugin }) {
        const _app = i18n(createApp({ render: () => h(app, props) }));

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
        };

        return _app
            .use(plugin)
            .use(ZiggyVue)
            .use(money)
            .use(pinia)
            .use(Particles, {
                init: async (engine) => await loadFull(engine)
            })
            .component('AppSuspense', AppSuspense)
            .component('Spinner', Spinner)
            .mount(el);
    },
});

InertiaProgress.init({ color: "#0aa1ed" });
