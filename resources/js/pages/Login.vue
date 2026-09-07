<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-2xl font-bold mb-6 text-center">Portal Satu Data TAPUT</h1>
            
            <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ error }}
            </div>

            <form @submit.prevent="login">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <input v-model="email" type="email" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Password</label>
                    <input v-model="password" type="password" class="w-full border rounded px-3 py-2" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Login
                </button>
            </form>

            <p class="mt-4 text-center text-gray-600">
                Belum punya akun? <router-link to="/register" class="text-blue-600 hover:underline">Daftar di sini</router-link>
            </p>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
    setup() {
        const router = useRouter()
        const email = ref('')
        const password = ref('')
        const error = ref('')

        const login = async () => {
            try {
                const response = await axios.post('/login', {
                    email: email.value,
                    password: password.value
                })
                localStorage.setItem('token', response.data.token)
                localStorage.setItem('user', JSON.stringify(response.data.user))
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
                router.push('/dashboard')
            } catch (e) {
                error.value = e.response?.data?.message || 'Login gagal'
            }
        }

        return { email, password, error, login }
    }
}
</script>
