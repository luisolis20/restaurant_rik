import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import VueApexCharts from 'vue3-apexcharts'
import './assets/main.css'
import './assets/styles/bienvenido.css'
import './assets/styles/login.css'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'jsvectormap/dist/jsvectormap.css'
import 'flatpickr/dist/flatpickr.css'
import '@fortawesome/fontawesome-free/css/all.min.css'





createApp(App).use(store).use(router).use(VueApexCharts).mount('#app')

