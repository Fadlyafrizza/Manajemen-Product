import './bootstrap'
import '../css/app.css'

import axios from 'axios'

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'

import FrontendLayout from './layouts/FrontendLayout.vue'
import BackendLayout from './layouts/BackendLayout.vue'

createInertiaApp({
    title: (title) => `Manajemen Produk | ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
        let page = pages[`./pages/${name}.vue`]
        if (name.startsWith('frontend/')) {
            page.default.layout = page.default.layout || FrontendLayout
        } else if (name.startsWith('backend/')) {
            page.default.layout = page.default.layout || BackendLayout
        }
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el)
    },
    progress: false
})
