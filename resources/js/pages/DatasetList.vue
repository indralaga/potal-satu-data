<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">Datasets</h1>
        
        <div class="mb-4 flex gap-2">
            <input v-model="search" type="text" placeholder="Cari dataset..." class="flex-1 border rounded px-3 py-2">
            <button @click="fetchDatasets" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Cari
            </button>
        </div>

        <div v-if="loading" class="text-center py-10">
            Loading...
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="dataset in datasets" :key="dataset.id" class="bg-white p-6 rounded-lg shadow hover:shadow-lg cursor-pointer">
                <h3 class="text-xl font-bold mb-2">{{ dataset.name }}</h3>
                <p class="text-gray-600 mb-2 text-sm">{{ dataset.description }}</p>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>{{ dataset.category }}</span>
                    <span>{{ dataset.row_count }} records</span>
                </div>
                <div class="mt-4 flex gap-2">
                    <button @click="downloadDataset(dataset.id)" class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        Download
                    </button>
                    <button v-if="canEdit(dataset)" @click="editDataset(dataset.id)" class="flex-1 bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
    setup() {
        const datasets = ref([])
        const search = ref('')
        const loading = ref(true)
        const user = JSON.parse(localStorage.getItem('user') || '{}')

        const fetchDatasets = async () => {
            loading.value = true
            try {
                const response = await axios.get('/datasets', {
                    params: { search: search.value }
                })
                datasets.value = response.data.data
            } catch (error) {
                console.error('Error fetching datasets:', error)
            } finally {
                loading.value = false
            }
        }

        const downloadDataset = (id) => {
            window.location.href = `/api/datasets/${id}/download`
        }

        const editDataset = (id) => {
            // Navigate to edit page
        }

        const canEdit = (dataset) => {
            return user.role === 'admin_portal' || (user.role === 'admin_opd' && dataset.opd_id === user.opd_id)
        }

        onMounted(fetchDatasets)

        return { datasets, search, loading, fetchDatasets, downloadDataset, editDataset, canEdit }
    }
}
</script>
