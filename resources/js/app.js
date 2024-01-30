import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { config } from 'jenesius-vue-modal'
import {createPinia} from "pinia";
import {Quill} from "@vueup/vue-quill";
import Emoji from "quill-emoji";
import {NLoadingBarProvider, NMessageProvider} from "naive-ui";
import {darkModeKey, styleKey} from "@/config.js";
import {useStyleStore} from "@/stores/style.js";

config({
    scrollLock: false,
    animation: 'modal-list',
    backgroundClose: true,
    escClose: true,
})
const pinia = createPinia();
const appName = import.meta.env.VITE_APP_NAME || 'Tgteka';
Quill.register("modules/emoji", Emoji);
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const RootComponent = {
            components: { App, NLoadingBarProvider, NMessageProvider },
            render() {
                return h(
                    NLoadingBarProvider,
                    {
                        loadingBarStyle: {
                            loading: {
                                backgroundColor: '#7f4fc5',
                                height: '3px'
                            }
                        }
                    },
                    {
                        default: () =>
                            h(
                                NMessageProvider,
                                {
                                    duration: 6000
                                },
                                {
                                    default: () =>
                                        h(App, props)
                                }
                            )
                    }
                );
            },
        };
        return createApp(RootComponent)
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
});

const styleStore = useStyleStore(pinia);

/* App style */
styleStore.setStyle(localStorage[styleKey] ?? "basic");

/* Dark mode */
if (
    (!localStorage[darkModeKey] &&
        window.matchMedia("(prefers-color-scheme: dark)").matches) ||
    localStorage[darkModeKey] === "1"
) {
    styleStore.setDarkMode(true);
}
