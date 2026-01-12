import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { Link, Head } from '@inertiajs/vue3';

// Create a simple route helper for now
const route = (name, params = {}) => {
    const routes = {
        'dashboard': '/dashboard',
        'chatbots.index': '/chatbots',
        'chatbots.create': '/chatbots/create',
        'chatbots.store': '/chatbots/store',
        'chatbots.show': (params) => typeof params === 'object' ? `/chatbots/${params.id || params}` : `/chatbots/${params}`,
        'chatbots.edit': (params) => typeof params === 'object' ? `/chatbots/${params.id || params}/edit` : `/chatbots/${params}/edit`,
        'chatbots.update': (params) => typeof params === 'object' ? `/chatbots/${params.id || params}` : `/chatbots/${params}`,
        'chatbots.destroy': (params) => typeof params === 'object' ? `/chatbots/${params.id || params}` : `/chatbots/${params}`,
        'chatbots.analytics': (params) => typeof params === 'object' ? `/chatbots/${params.id || params}/analytics` : `/chatbots/${params}/analytics`,
        'analytics.index': '/analytics',
        'widget.show': (params) => typeof params === 'object' ? `/widget/${params.uuid || params}` : `/widget/${params}`,
        'widget.send': (params) => typeof params === 'object' ? `/widget/${params.uuid || params}/send` : `/widget/${params}/send`,
        'widget.info': (params) => typeof params === 'object' ? `/widget/${params.uuid || params}/info` : `/widget/${params}/info`,
        'login': '/login',
        'logout': '/logout',
        'register': '/register',
        'profile.edit': '/profile',
        'profile.update': '/profile',
        'profile.destroy': '/profile',
        'password.request': '/forgot-password',
    };

    const r = routes[name];
    if (typeof r === 'function') {
        return r(params);
    }

    return r || '#';
};

createInertiaApp({
    title: (title) => `${title} - ChatBot SaaS`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');
        const page = pages[`./Pages/${name}.vue`];
        if (!page) {
            console.error(`Page ${name} not found`);
            return () => Promise.resolve({ template: '<div>Page not found</div>' });
        }
        return page();
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Link', Link)
            .component('Head', Head)
            .provide('route', route);

        // Make route available globally
        app.config.globalProperties.$route = route;
        window.route = route;

        app.mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
