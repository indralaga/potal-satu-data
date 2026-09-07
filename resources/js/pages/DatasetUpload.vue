<template>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Upload Dataset</h1>
        
        <div v-if="error" class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ error }}
        </div>
        <div v-if="success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ success }}
        </div>

        <form @submit.prevent="uploadDataset" class="bg-white p-6 rounded-lg shadow">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Dataset</label>
                <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                <textarea v-model="form.description" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select v-model="form.category" class="w-full border rounded px-3 py-2" required>
                    <option>Kesehatan</option>
                    <option>Pendidikan</option>
                    <option>Pertanian</option>
                    <option>Ekonomi</option>
                    <option>Lainnya</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">File (CSV, Excel, JSON)</label>
                <input @change="handleFileUpload" type="file" class="w-full border rounded px-3 py-2" accept=".csv,.xlsx,.json" required>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input v-model="form.is_public" type="checkbox" class="mr-2">
                    <span class="text-gray-700">Publikkan dataset ini</span>
                </label>
            </div>

            <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50">
                {{ loading ? 'Uploading...' : 'Upload Dataset' }}
            </button>
        </form>
    </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
    setup() {
        const router = useRouter()
        const form = ref({
            name: '',
            description: '',
            category: '',
            is_public: false,
            file: null
        })
        const error = ref('')
        const success = ref('')
        const loading = ref(false)

        const handleFileUpload = (event) => {
            form.value.file = event.target.files[0]
        }

        const uploadDataset = async () => {
            if (!form.value.file) {
                error.value = 'Pilih file terlebih dahulu'
                return
            }

            loading.value = true
            const formData = new FormData()
            formData.append('name', form.value.name)
            formData.append('description', form.value.description)
            formData.append('category', form.value.category)
            formData.append('is_public', form.value.is_public)
            formData.append('file', form.value.file)

            try {
                const response = await axios.post('/datasets', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                success.value = 'Dataset berhasil diupload'
                setTimeout(() => router.push('/datasets'), 2000)
            } catch (e) {
                error.value = e.response?.data?.message || 'Upload gagal'
            } finally {
                loading.value = false
            }
        }

        return { form, error, success, loading, handleFileUpload, uploadDataset }
    }
}
</script>
