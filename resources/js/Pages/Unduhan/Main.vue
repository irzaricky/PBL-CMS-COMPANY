<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Download } from 'lucide-vue-next' // pastikan ikon ini ter-import

const unduhanList = ref([])
const categories = ref([])
const mostDownloaded = ref([])
const search = ref('')
const selectedCategory = ref(null)

const fetchUnduhan = async () => {
    const params = {}
    if (search.value) params.query = search.value
    if (selectedCategory.value) params.category_id = selectedCategory.value

    const { data } = await axios.get('/api/unduhan/search', { params })
    unduhanList.value = data.data
}

const fetchCategories = async () => {
    const { data } = await axios.get('/api/unduhan/categories')
    categories.value = data.data
}

const fetchMostDownloaded = async () => {
    const { data } = await axios.get('/api/unduhan/most-downloaded')
    mostDownloaded.value = data.data
}

const filterByCategory = (id) => {
    selectedCategory.value = id
    fetchUnduhan()
}

const downloadFile = async (item) => {
    try {
        // Panggil endpoint yang mengirim file binary
        const response = await axios.get(`/api/unduhan/download/${item.id}`, {
            responseType: 'blob', // penting untuk file binary
        })

        // Update local count
        item.jumlah_unduhan++

        // Buat URL objek dari blob
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url

        // Tentukan nama file unduhan
        const extension = item.lokasi_file.split('.').pop()
        link.setAttribute('download', item.nama_unduhan + '.' + extension)

        document.body.appendChild(link)
        link.click()
        link.remove()
        window.URL.revokeObjectURL(url)

    } catch {
    }
}
const getFileIcon = (ext) => {
    switch (ext) {
        case 'doc':
        case 'docx':
            return '/image/unduhan/doc.svg'
        case 'xls':
        case 'xlsx':
            return '/image/unduhan/xls.svg'
        case 'pdf':
            return '/image/unduhan/pdf.svg'
        case 'ppt':
        case 'pptx':
            return '/image/unduhan/slides.svg'
        default:
            return '/image/unduhan/file.svg'
    }
}



onMounted(() => {
    fetchCategories()
    fetchMostDownloaded()
    fetchUnduhan()
})
</script>

<template>
    <AppLayout>
        <div
            class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-28 text-black font-custom flex flex-col gap-20 overflow-hidden">
            <div class="w-full flex flex-col gap-4">
                <div class="text-base font-semibold">Unduhan</div>
                <div class="flex flex-col items-start gap-4">
                    <h1 class="text-4xl sm:text-5xl font-normal leading-tight">Download File Sesuai Kebutuhanmu</h1>
                    <p class="text-lg leading-relaxed">Temukan dokumen, e-book, panduan, atau file penting lainnya di
                        sini.</p>
                    <p class="text-sm text-gray-500">*Semua file di sini aman dan tidak mengandung file berbahaya</p>    
                </div>
            </div>

            <div class="w-full flex flex-wrap gap-2">
                <button class="px-4 py-2 rounded-xl text-sm font-medium transition border"
                    :class="selectedCategory === null ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-800 border-gray-300'"
                    @click="filterByCategory(null)">
                    Semua
                </button>
                <button v-for="cat in categories" :key="cat.id_kategori_unduhan"
                    class="px-4 py-2 rounded-xl text-sm font-medium transition border"
                    :class="selectedCategory === cat.id_kategori_unduhan ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-800 border-gray-300'"
                    @click="filterByCategory(cat.id_kategori_unduhan)">
                    {{ cat.nama_kategori_unduhan }}
                </button>
            </div>

            <div class="w-full flex flex-col gap-12">
                <div v-for="item in unduhanList" :key="item.id"
                    class="flex flex-col lg:flex-row justify-between gap-16">
                    <div class="flex flex-col lg:flex-row gap-8 items-start lg:items-center">
                        <img :src="item.thumbnail || 'https://placehold.co/296x250?text=No+Image'"
                            class="rounded-2xl object-cover aspect-video w-full lg:w-[296px]" alt="Thumbnail" />
                        <div class="flex-1 flex flex-col gap-6">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-4">
                                    <span class="px-2.5 py-1 bg-white rounded-full border-2 text-sm font-semibold">
                                        {{ item.kategori?.nama_kategori_unduhan || 'Tanpa Kategori' }}
                                    </span>
                                    <span class="text-sm font-semibold">
                                        {{ item.jumlah_unduhan }}x unduh
                                    </span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <h2 class="text-2xl font-normal leading-loose">
                                        {{ item.nama_unduhan }}
                                    </h2>
                                    <p class="text-base font-normal leading-normal">
                                        {{ item.deskripsi }}
                                    </p>
                                    <!-- Icon SVG -->
                                    <img :src="getFileIcon(item.jenis_file)" alt="File icon"
                                        class="w-6 h-6 rounded-full bg-gray-200 p-1" />
                                    <p class="text-sm text-gray-600 mt-1">
                                        Ukuran file: {{ item.ukuran_file || '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full">
                                <button @click="downloadFile(item)" class="rounded-full bg-gray-100 text-black px-4 py-2 text-base inline-flex items-center gap-2
           hover:bg-black hover:text-white transition-colors duration-300">
                                    Unduh sekarang
                                    <Download class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
