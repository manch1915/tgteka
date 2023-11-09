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
import {NLoadingBarProvider} from "naive-ui";

config({
    scrollLock: false,
    animation: 'modal-list',
    backgroundClose: true,
    escClose: true,
})

const appName = import.meta.env.VITE_APP_NAME || 'Tgteka';
Quill.register("modules/emoji", Emoji);
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const RootComponent = {
            components: { App, NLoadingBarProvider },
            render() {
                return h(
                    NLoadingBarProvider,
                    {},
                    {
                        default: () =>
                            h(App, props)
                    }
                );
            },
        };
        return createApp(RootComponent)
            .use(plugin)
            .use(createPinia())
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
});
