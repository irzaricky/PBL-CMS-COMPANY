import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

// Static imports for better tree-shaking and bundler optimization
import {
    User,
    LayoutDashboard,
    LogOut,
    AlarmClock,
    Wallet,
    ChevronRight,
    ChevronLeft,
    ChevronDown,
    Menu,
    School,
    Home,
    ShoppingBag,
    FileText,
    Image,
    Phone,
    Mail,
    UserCog,
    MapPin,
} from "lucide-vue-next";

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

        // Register commonly used Lucide icons statically
        const iconComponents = {
            User,
            LayoutDashboard,
            LogOut,
            AlarmClock,
            Wallet,
            ChevronRight,
            ChevronLeft,
            ChevronDown,
            Menu,
            School,
            Home,
            ShoppingBag,
            FileText,
            Image,
            Phone,
            Mail,
            UserCog,
            MapPin,
        };

        Object.entries(iconComponents).forEach(([name, component]) => {
            vueApp.component(name, component);
        });

        // Lazy load specific Lucide icons by name
        vueApp.config.globalProperties.$loadIcon = async (iconName) => {
            try {
                const icon = await import(
                    /* @vite-ignore */ `lucide-vue-next/dist/esm/icons/${iconName}.js`
                );
                if (!vueApp.component(iconName)) {
                    vueApp.component(iconName, icon.default);
                }
                return true;
            } catch (e) {
                console.error(`Icon "${iconName}" not found`, e);
                return false;
            }
        };

        vueApp.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
