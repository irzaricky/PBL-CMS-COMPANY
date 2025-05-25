import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

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

        // 🔥 Register commonly used Lucide icons with dynamic imports
        const registerLucideIcons = async () => {
            // Define commonly used icons - add more as needed based on your application
            const commonIcons = [
                "User",
                "LayoutDashboard",
                "LogOut",
                "AlarmClock",
                "Wallet",
                "ChevronRight",
                "ChevronDown",
                "Menu",
                "School",
                "LucideChevronLeft",
                "LucideChevronRight",
            ];

            // Dynamically import only the icons that are commonly used
            for (const iconName of commonIcons) {
                const icon = await import(
                    `lucide-vue-next/dist/esm/icons/${iconName}.js`
                );
                vueApp.component(iconName, icon.default);
            }

            // Setup a function to lazy load additional icons when needed
            // This function can be exposed globally if you need to load icons on demand
            vueApp.config.globalProperties.$loadIcon = async (iconName) => {
                try {
                    const icon = await import(
                        `lucide-vue-next/dist/esm/icons/${iconName}.js`
                    );
                    if (!vueApp.component(iconName)) {
                        vueApp.component(iconName, icon.default);
                    }
                    return true;
                } catch (error) {
                    console.error(`Failed to load icon: ${iconName}`, error);
                    return false;
                }
            };
        };

        // Initialize icon registration
        registerLucideIcons();

        vueApp.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
