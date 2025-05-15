<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import { ShoppingBag, Tag } from 'lucide-vue-next'

const props = defineProps({
    slug: String,
})

const item = ref(null)
const loading = ref(false)
const error = ref(null)
const activeImageIndex = ref(0)

onMounted(() => {
    fetchProduk()
})

async function fetchProduk() {
    try {
        loading.value = true
        const response = await axios.get(`/api/produk/${props.slug}`)
        item.value = response.data.data
    } catch (err) {
        error.value = 'Produk not found or an error occurred'
        console.error('Error fetching produk:', err)
    } finally {
        loading.value = false
    }
}

function getImageUrl(image) {
    return image ? `/storage/${image}` : '/image/placeholder.webp'
}

function prevImage() {
    if (!item.value || !item.value.thumbnail_produk) return
    activeImageIndex.value =
        (activeImageIndex.value - 1 + item.value.thumbnail_produk.length) %
        item.value.thumbnail_produk.length
}

function nextImage() {
    if (!item.value || !item.value.thumbnail_produk) return
    activeImageIndex.value =
        (activeImageIndex.value + 1) % item.value.thumbnail_produk.length
}
</script>

<template>
    <AppLayout>
        <div
            class="w-full px-4 lg:px-16 py-10 lg:py-20 bg-white flex flex-col lg:flex-row justify-start items-start gap-10 lg:gap-20 font-custom">
            <!-- Left: Product Image -->
            <div class="w-full lg:w-1/2 relative">
                <div class="relative w-full pt-[75%] overflow-hidden rounded-2xl">
                    <!-- Gambar -->
                    <img class="absolute top-0 left-0 w-full h-full object-cover rounded-2xl"
                        :src="getImageUrl(item?.thumbnail_produk?.[activeImageIndex])" alt="Product Image" />

                    <!-- Tombol Panah Kiri -->
                    <div @click="prevImage"
                        class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center cursor-pointer transition">
                        <ChevronLeft class="w-5 h-5 text-white" />
                    </div>

                    <!-- Tombol Panah Kanan -->
                    <div @click="nextImage"
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center cursor-pointer transition">
                        <ChevronRight class="w-5 h-5 text-white" />
                    </div>

                    <!-- Pagination Dots -->
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2">
                        <div v-for="(img, i) in item?.thumbnail_produk" :key="i" :class="[
                            'w-2.5 h-2.5 rounded-full transition-all duration-300',
                            i === activeImageIndex
                                ? 'bg-white scale-110'
                                : 'bg-white opacity-30',
                        ]" />
                    </div>
                </div>
            </div>

            <!-- Right: Product Details -->
            <div class="w-full lg:w-1/2 flex flex-col gap-8" v-if="item">
                <!-- Breadcrumbs -->
                <div class="max-w-4xl mb-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <Link href="/"
                                    class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600">
                                <Home class="w-4 h-4 mr-2" />
                                Home
                                </Link>
                            </li>

                            <li>
                                <div class="flex items-center">
                                    <ChevronRight class="w-4 h-4 text-gray-400" />
                                    <Link href="/produk"
                                        class="ml-1 text-sm inline-flex items-center text-gray-500 hover:text-blue-600 md:ml-2">
                                    <ShoppingBag class="w-4 h-4 mr-2" />
                                    Produk
                                    </Link>
                                </div>
                            </li>

                            <li aria-current="page">
                                <div class="flex items-center">
                                    <ChevronRight class="w-4 h-4 text-gray-400" />
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate max-w-[200px]">
                                        {{ item?.nama_produk || "Loading..." }}
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <!-- Title & Price -->
                <h1 class="text-4xl font-custom ">
                    {{ item.nama_produk }}
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-xl  font-custom">
                        {{ item.harga_produk.toLocaleString('id-ID') }}
                    </span>
                    <div class="flex items-center gap-3">
                        <div class="h-6 border-l" />
                        <span class="text-xl inline-flex items-center gap-1">
                            <Tag class="w-4" /> {{ item.kategori_produk.nama_kategori_produk }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-base ">
                    {{ item.deskripsi_produk }}
                </p>
                <ul class="text-base list-disc pl-5 space-y-1">
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                </ul>

                <!-- Buttons -->
                <div class="space-y-4">
                    <button class="w-full px-6 py-2.5 bg-secondary text-white font-medium rounded-full">
                        Beli di marketplace
                    </button>
                    <p class="text-xs text-center ">Anda akan diarahkan ke halaman baru</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
