import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { IMaskDirective } from 'vue-imask';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import 'vue-toastification/dist/index.css';
import Toast from 'vue-toastification';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, {
                // opções globais (idem v1; a sintaxe não mudou)
                position: 'top-right',
                timeout: 3000,
            })
            .directive('imask', IMaskDirective)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
