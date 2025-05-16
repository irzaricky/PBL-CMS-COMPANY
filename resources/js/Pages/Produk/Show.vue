<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { usePage, Link } from '@inertiajs/vue3'

const props = defineProps({ slug: String })
const item = ref(null)
const loading = ref(false)
const activeImageIndex = ref(0)
const page = usePage()
const isLoggedIn = computed(() => !!page.props.auth.user)
const testimoniList = ref([])
const newTestimoni = ref({ isi_testimoni: '', rating: 5 })

// Fetch produk dan testimoni saat komponen mount
onMounted(() => {
    fetchProduk()
})

async function fetchProduk() {
    try {
        loading.value = true
        const response = await axios.get(`/api/produk/${props.slug}`)
        item.value = response.data.data
        await fetchTestimoni() // langsung fetch testimoni setelah produk didapat
    } catch (err) {
        console.error('Produk not found or error:', err)
    } finally {
        loading.value = false
    }
}

function getImageUrl(image) {
    return image ? `/storage/${image}` : '/image/placeholder.webp'
}

function prevImage() {
    if (!item.value?.thumbnail_produk) return
    activeImageIndex.value =
        (activeImageIndex.value - 1 + item.value.thumbnail_produk.length) % item.value.thumbnail_produk.length
}

function nextImage() {
    if (!item.value?.thumbnail_produk) return
    activeImageIndex.value =
        (activeImageIndex.value + 1) % item.value.thumbnail_produk.length
}

async function fetchTestimoni() {
    if (!item.value) return
    try {
        const response = await axios.get(`/api/testimoni/produk/${item.value.id_produk}`)
        testimoniList.value = response.data.data
    } catch (err) {
        console.error('Gagal muat testimoni:', err)
    }
}

async function submitTestimoni() {
    if (!newTestimoni.value.isi_testimoni.trim()) {
        alert('Isi testimoni tidak boleh kosong')
        return
    }
    try {
        await axios.post(`/api/testimoni/produk/${item.value.id_produk}`, newTestimoni.value)
        alert('Testimoni berhasil dikirim dan menunggu persetujuan')

        // Reset form
        newTestimoni.value.isi_testimoni = ''
        newTestimoni.value.rating = 5

        // Refresh list testimoni agar langsung update
        await fetchTestimoni()
    } catch (err) {
        alert('Gagal mengirim testimoni')
        console.error(err)
    }
}
axios.defaults.withCredentials = true
</script>


<template>
    <AppLayout>
        <div class="w-full px-4 lg:px-16 py-10 lg:py-20 bg-white flex flex-col items-start gap-20 font-custom">

            <!-- Product Detail -->
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-20 w-full max-w-7xl mx-auto">

                <!-- Left: Product Image -->
                <div class="w-full lg:w-1/2 flex justify-center">
                    <div class="relative w-full max-w-[600px] aspect-[4/3] overflow-hidden rounded-2xl">
                        <img class="absolute inset-0 w-full h-full object-cover"
                            :src="getImageUrl(item?.thumbnail_produk?.[activeImageIndex])" alt="Product Image" />

                        <!-- Nav Arrows -->
                        <div @click="prevImage"
                            class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-black/40 hover:bg-black/60 rounded-full cursor-pointer transition">
                            <ChevronLeft class="w-5 h-5 text-white" />
                        </div>
                        <div @click="nextImage"
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-black/40 hover:bg-black/60 rounded-full cursor-pointer transition">
                            <ChevronRight class="w-5 h-5 text-white" />
                        </div>

                        <!-- Pagination Dots -->
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                            <div v-for="(img, i) in item?.thumbnail_produk" :key="i"
                                :class="i === activeImageIndex ? 'w-2.5 h-2.5 rounded-full bg-white scale-110' : 'w-2.5 h-2.5 rounded-full bg-white opacity-30'" />
                        </div>
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="w-full lg:w-1/2 flex flex-col gap-8" v-if="item">
                    <!-- Breadcrumbs -->
                    <div>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li>
                                    <Link href="/"
                                        class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600">
                                    <Home class="w-4 h-4 mr-2" />
                                    Home
                                    </Link>
                                </li>
                                <li class="inline-flex items-center">
                                    <ChevronRight class="w-4 h-4 text-gray-400" />
                                    <Link href="/produk"
                                        class="ml-1 inline-flex items-center text-sm text-gray-500 hover:text-blue-600">
                                    <ShoppingBag class="w-4 h-4 mr-2" />
                                    Produk
                                    </Link>
                                </li>
                                <li class="flex items-center">
                                    <ChevronRight class="w-4 h-4 text-gray-400" />
                                    <span class="ml-1 text-sm font-medium text-gray-500 truncate max-w-[200px]">
                                        {{ item?.nama_produk || "Loading..." }}
                                    </span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Title & Price -->
                    <h1 class="text-4xl font-bold">{{ item.nama_produk }}</h1>
                    <div class="flex items-center gap-4">
                        <span class="text-xl font-semibold">{{ item.harga_produk.toLocaleString('id-ID') }}</span>
                        <div class="flex items-center gap-3">
                            <div class="h-6 border-l" />
                            <span class="text-xl flex items-center gap-1">
                                <Tag class="w-4" /> {{ item.kategori_produk.nama_kategori_produk }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-base text-gray-700">{{ item.deskripsi_produk }}</p>

                    <!-- Buy Button -->
                    <div class="space-y-4">
                        <button class="w-full px-6 py-2.5 bg-secondary text-white font-medium rounded-full">
                            Beli di marketplace
                        </button>
                        <p class="text-xs text-center text-gray-500">Anda akan diarahkan ke halaman baru</p>
                    </div>
                </div>
            </div>

            <!-- TESTIMONI LIST -->
            <div v-if="testimoniList.length" class="w-full max-w-3xl mx-auto">
                <span class="text-sm text-gray-500">Ulasan dari</span>
                <h2 class="text-2xl font-semibold mb-4">{{ item.nama_produk }}</h2>
                <div class="space-y-6">
                    <div v-for="testimoni in testimoniList" :key="testimoni.id_testimoni"
                        class="p-4 border rounded-xl shadow bg-white">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-3">
                                <img v-if="testimoni.user?.foto_profil" :src="`/storage/${testimoni.user.foto_profil}`"
                                    class="w-8 h-8 rounded-full object-cover" alt="Foto Profil" />
                                <div>
                                    <p class="font-bold">{{ testimoni.user?.name || 'Anonim' }}</p>
                                    <p class="text-xs text-gray-500">{{ testimoni.user?.email || '' }}</p>
                                </div>
                            </div>

                            <!-- Bintang Rating -->
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1">
                                    <Star v-for="i in 5" :key="i"
                                        :class="i <= testimoni.rating ? 'text-secondary' : 'text-gray-300'"
                                        class="w-5 h-5" />
                                </div>
                                <span class="text-sm text-gray-500">{{ testimoni.rating }}/5</span>
                            </div>
                        </div>
                        <p class="text-gray-700">{{ testimoni.isi_testimoni }}</p>
                    </div>
                </div>
            </div>

            <!-- FORM TESTIMONI -->
            <div v-if="isLoggedIn" class="w-full max-w-3xl mx-auto mt-10">
                <span class="text-sm text-gray-500">Sudah membeli dan menggunakan?</span>
                <h2 class="text-xl font-semibold mb-3">Tulis pengalamanmu sendiri</h2>
                <form @submit.prevent="submitTestimoni" class="space-y-4 border rounded-xl shadow bg-white p-4">
                    <textarea v-model="newTestimoni.isi_testimoni"
                        class="w-full rounded-md border p-3 focus:outline-none focus:ring-0 focus:border-gray-300"
                        rows="4" placeholder="Tulis testimoni kamu di sini..." required></textarea>

                    <!-- Rating Star Selector -->
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Rating:</span>
                        <div class="flex items-center gap-1">
                            <button v-for="i in 5" :key="i" type="button" @click="newTestimoni.rating = i"
                                class="focus:outline-none">
                                <Star :class="i <= newTestimoni.rating ? 'text-secondary' : 'text-gray-300'"
                                    class="w-6 h-6" />
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-full px-5 py-2 bg-secondary text-white hover:bg-black transition">
                        Kirim
                    </button>
                </form>
            </div>

            <!-- LOGIN WARNING -->
            <div v-else class="w-full max-w-3xl mx-auto text-sm text-gray-500 italic mt-4">
                Login terlebih dahulu untuk menulis testimoni.
            </div>

        </div>
    </AppLayout>
</template>
