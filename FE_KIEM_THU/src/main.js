import './assets/main.css'
import 'bootstrap-icons/font/bootstrap-icons.css'

import { createApp } from 'vue'
import App from './App.vue'
import { toast } from './utils/toast'

const app = createApp(App)

// Cung cấp $toast toàn cục cho mọi component
app.config.globalProperties.$toast = toast
app.provide('$toast', toast)

app.mount('#app')
