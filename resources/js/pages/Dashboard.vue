<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
        
        <div v-if="loading" class="text-center py-10">
            Loading...
        </div>

        <div v-else>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-blue-600">{{ stats.total_datasets }}</div>
                    <div class="text-gray-600">Total Datasets</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="text-3xl font-bold text-green-600">{{ stats.total_records }}</div>
                    <div class="text-gray-600">Total Records</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow" v-if="user.role === 'admin_portal'">
                    <div class="text-3xl font-bold text-purple-600">{{ stats.total_opds }}</div>
                    <div class="text-gray-600">OPD</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow" v-if="user.role === 'admin_portal'">
                    <div class="text-3xl font-bold text-orange-600">{{ stats.total_users }}</div>
                    <div class="text-gray-600">Users</div>
                </div>
            </div>

            <!-- Recent Datasets -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Dataset Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama</th>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">OPD</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dataset in recentDatasets" :key="dataset.id" class="border-t">
                                <td class="px-4 py-2">{{ dataset.name }}</td>
                                <td class="px-4 py-2">{{ dataset.category }}</td>
                                <td class="px-4 py-2">{{ dataset.opd?.name }}</td>
                                <td class="px-4 py-2">{{ formatDate(dataset.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
    setup() {
        const stats = ref({})
        const recentDatasets = ref([])
        const loading = ref(true)
        const user = JSON.parse(localStorage.getItem('user') || '{}')

        const fetchDashboard = async () => {
            try {
                const response = await axios.get('/dashboard')
                stats.value = response.data.stats
                recentDatasets.value = response.data.recent_datasets
            } catch (error) {
                console.error('Error fetching dashboard:', error)
            } finally {
                loading.value = false
            }
        }

        const formatDate = (date) => {
            return new Date(date).toLocaleDateString('id-ID')
        }

        onMounted(fetchDashboard)

        return { stats, recentDatasets, loading, user, formatDate }
    }
}
</script>
