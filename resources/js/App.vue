<template>
    <div id="app" class="min-h-screen bg-gray-100">
        <nav class="bg-blue-600 text-white p-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="font-bold text-xl">Satu Data TAPUT</div>
                <div class="flex gap-4" v-if="isLoggedIn">
                    <router-link to="/dashboard" class="hover:text-blue-200">Dashboard</router-link>
                    <router-link to="/datasets" class="hover:text-blue-200">Datasets</router-link>
                    <router-link to="/datasets/upload" v-if="hasRole(['admin_opd', 'admin_portal'])" class="hover:text-blue-200">Upload</router-link>
                    <router-link to="/users" v-if="hasRole(['admin_portal'])" class="hover:text-blue-200">Users</router-link>
                    <button @click="logout" class="hover:text-blue-200">Logout</button>
                </div>
            </div>
        </nav>
        <main class="max-w-7xl mx-auto p-4">
            <router-view />
        </main>
    </div>
</template>

<script>
import { useRouter } from 'vue-router'

export default {
    setup() {
        const router = useRouter()
        const user = JSON.parse(localStorage.getItem('user') || '{}')
        const isLoggedIn = !!localStorage.getItem('token')

        const logout = () => {
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            router.push('/login')
        }

        const hasRole = (roles) => {
            return roles.includes(user.role)
        }

        return {
            isLoggedIn,
            logout,
            hasRole
        }
    }
}
</script>
