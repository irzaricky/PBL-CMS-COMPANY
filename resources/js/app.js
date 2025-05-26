import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import * as lucide from "lucide-vue-next";

// Get company name from server-side config (set in AppServiceProvider)
const appName =
    document.querySelector('meta[name="app-name"]')?.getAttribute("content") ||
    import.meta.env.VITE_APP_NAME ||
    "Laravel";

createInertiaApp({
    title: (title) => `${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        // 🔥 Register semua icon dari Lucide secara global
        for (const [key, component] of Object.entries(lucide)) {
            vueApp.component(key, component);
        }

        vueApp.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
