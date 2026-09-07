import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'

const app = createApp(App)

// Setup axios
axios.defaults.baseURL = '/api'
const token = localStorage.getItem('token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

app.config.globalProperties.$axios = axios

app.use(router)
app.mount('#app')
