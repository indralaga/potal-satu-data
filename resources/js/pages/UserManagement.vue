<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">Manajemen User</h1>
        
        <div v-if="loading" class="text-center py-10">
            Loading...
        </div>

        <div v-else class="overflow-x-auto">
            <table class="w-full bg-white rounded-lg shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ user.name }}</td>
                        <td class="px-4 py-2">{{ user.email }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm" :class="getRoleClass(user.role)">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm" :class="user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                {{ user.is_active ? 'Aktif' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <button v-if="!user.is_active" @click="approveUser(user.id)" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 mr-2">
                                Approve
                            </button>
                            <button @click="deleteUser(user.id)" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
    setup() {
        const users = ref([])
        const loading = ref(true)

        const fetchUsers = async () => {
            try {
                const response = await axios.get('/users')
                users.value = response.data.data
            } catch (error) {
                console.error('Error fetching users:', error)
            } finally {
                loading.value = false
            }
        }

        const approveUser = async (id) => {
            try {
                await axios.post(`/users/${id}/approve`)
                fetchUsers()
            } catch (error) {
                console.error('Error approving user:', error)
            }
        }

        const deleteUser = async (id) => {
            if (confirm('Yakin ingin menghapus user ini?')) {
                try {
                    await axios.delete(`/users/${id}`)
                    fetchUsers()
                } catch (error) {
                    console.error('Error deleting user:', error)
                }
            }
        }

        const getRoleClass = (role) => {
            const classes = {
                'admin_portal': 'bg-red-100 text-red-800',
                'admin_opd': 'bg-blue-100 text-blue-800',
                'viewer': 'bg-gray-100 text-gray-800'
            }
            return classes[role] || 'bg-gray-100 text-gray-800'
        }

        onMounted(fetchUsers)

        return { users, loading, approveUser, deleteUser, getRoleClass }
    }
}
</script>
