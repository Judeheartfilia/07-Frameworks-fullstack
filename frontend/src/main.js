import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000/api'

axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token')
            store.dispatch('auth/logout')
            router.push('/login')
        }
        return Promise.reject(error)
    }
)

const app = createApp(App)

app.config.globalProperties.$http = axios
app.use(store)
app.use(router)
app.mount('#app')