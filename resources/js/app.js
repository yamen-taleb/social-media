import '../css/app.css'
import './bootstrap'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import { useToast } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

// Lazy load heavy components
const RichEditor = () => import('@/Components/App/RichEditor.vue')
const PostPreview = () => import('@/Components/App/PostPreview.vue')

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  async setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.use(plugin)
    app.use(ZiggyVue)

    // Register toast as a global property
    app.config.globalProperties.$toast = useToast()

    // Register global components
    app.component('RichEditor', RichEditor)
    app.component('PostPreview', PostPreview)

    // Mount the app
    app.mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})
