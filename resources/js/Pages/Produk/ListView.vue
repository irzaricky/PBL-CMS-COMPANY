<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from "vue";
import axios from "axios";
import { usePage } from '@inertiajs/vue3';

const produk = ref([]);
const searchQuery = ref('');
const searching = ref(false);
let debounceTimer = null;
const { props } = usePage();

onMounted(() => {
    fetchProduk();
    fetchKategori();
    document.documentElement.style.setProperty('--color-secondary', props.theme.secondary);
});

const selectedCategory = ref(null);

const filterByCategory = (categoryId) => {
    selectedCategory.value = categoryId;
    fetchProduk(searchQuery.value, categoryId);
};

const fetchProduk = async (query = '', categoryId = null) => {
    try {
        searching.value = true;
        let url = query.length > 0 || categoryId !== null ? '/api/produk/search' : '/api/produk';
        const params = {};
        if (query.length > 0) params.query = query;
        if (categoryId !== null) params.category_id = categoryId;

        const response = await axios.get(url, { params });
        produk.value = response.data.data;
    } catch (error) {
        console.error('Error fetching produk:', error);
        produk.value = [];
    } finally {
        searching.value = false;
    }
};

const handleSearch = () => {
    if (debounceTimer) clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        fetchProduk(searchQuery.value, selectedCategory.value);
    }, 500);
};

const categories = ref([]);
async function fetchKategori() {
    try {
        const response = await axios.get("/api/produk/categories");
        categories.value = response.data.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}

const item = ref(null);
const activeImageIndex = ref(0);

const fetchLatestProduct = async () => {
    try {
        const response = await axios.get('/api/produk/latest');
        item.value = response.data.data;
    } catch (error) {
        console.error("Gagal mengambil produk terbaru:", error);
        item.value = null;
    }
};

const nextImage = () => {
    if (!item.value || !item.value.thumbnail_produk) return;
    activeImageIndex.value = (activeImageIndex.value + 1) % item.value.thumbnail_produk.length;
};

const prevImage = () => {
    if (!item.value || !item.value.thumbnail_produk) return;
    activeImageIndex.value =
        (activeImageIndex.value - 1 + item.value.thumbnail_produk.length) % item.value.thumbnail_produk.length;
};

onMounted(() => {
    fetchLatestProduct();
});
</script>

<template>
    <AppLayout>
        <div class="relative w-full bg-cover bg-center bg-no-repeat px-4 md:px-8 lg:px-16 py-28 flex flex-col justify-start items-center gap-20 overflow-hidden"
            :style="produk.length ? `background-image: url('${getImageUrl(produk[0].thumbnail_produk)}')` : ''">

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60 z-0"></div>

            <!-- Konten -->
            <div
                class="relative z-10 w-full max-w-3xl flex flex-col justify-start items-center gap-8 text-white text-center">
                <!-- Heading -->
                <div class="flex flex-col items-center gap-4">
                    <div class="text-base font-semibold font-custom">Produk</div>
                    <div class="flex flex-col gap-6">
                        <h2 class="text-4xl md:text-5xl font-normal font-custom leading-tight">Belanja Praktis, Hasil
                            Maksimal</h2>
                        <p class="text-lg font-normal font-custom leading-relaxed">
                            Produk pilihan dengan harga bersahabat. Temukan kebutuhanmu dengan mudah dan cepat hanya
                            dalam satu tempat.
                        </p>
                    </div>
                </div>

                <!-- Search form -->
                <div class="w-full flex flex-col items-center gap-4">
                    <div class="w-full flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Cari produk..."
                            class="flex-1 px-4 py-3 rounded-xl bg-white/10 text-white placeholder-white/60 outline outline-1 outline-transparent focus:outline-white focus:ring-0 font-custom" />
                        <button @click="fetchProduk(searchQuery)"
                            class="px-6 py-2.5 rounded-full bg-white text-black text-base font-medium font-custom hover:bg-gray-100 transition">
                            Cari
                        </button>
                    </div>
                    <p class="text-xs font-normal font-custom leading-none">
                        Gunakan kata kunci produk yang Anda inginkan.
                    </p>
                </div>

            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 lg:px-8 py-8 flex flex-col items-center gap-20 overflow-hidden">

            <!-- Filter Kategori -->
            <div class="w-full flex flex-wrap gap-2 mt-6 font-custom">
                <!-- Tombol 'Semua' -->
                <button @click="filterByCategory(null)" class="px-4 py-2 rounded-xl text-sm font-medium transition"
                    :class="selectedCategory === null
                        ? 'bg-secondary text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-black hover:text-white'">
                    Semua
                </button>

                <!-- Tombol per kategori -->
                <button v-for="category in categories" :key="category.id_kategori_produk"
                    @click="filterByCategory(category.id_kategori_produk)"
                    class="px-4 py-2 rounded-xl text-sm font-medium transition" :class="selectedCategory === category.id_kategori_produk
                        ? 'bg-secondary text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-black hover:text-white'">
                    {{ category.nama_kategori_produk }}
                </button>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full font-custom">
                <div v-for="item in produk" :key="item.id_produk"
                    class="relative p-6 rounded-2xl bg-cover bg-center bg-no-repeat flex flex-col h-[480px] overflow-hidden transform transition duration-300 hover:scale-105"
                    :style="item.thumbnail_produk && item.thumbnail_produk.length > 0
                        ? `background-image: url('${getImageUrl(item.thumbnail_produk)}')`
                        : 'background-color: #ccc'">

                    <!-- Overlay -->
                    <div
                        class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent z-10" />

                    <!-- Konten -->
                    <div class="relative z-20 mt-auto text-white flex flex-col gap-2">
                        <!-- Nama Produk -->
                        <div class="text-2xl font-normal">{{ item.nama_produk }}</div>

                        <!-- Varian dan Harga -->
                        <div class="flex flex-col gap-2">
                            <!-- Varian -->
                            <div class="flex items-center gap-2 text-sm font-normal">
                                <Tag class="w-4 h-4" />
                                <span>Kategori: {{ item.kategori_produk.nama_kategori_produk }}</span>
                            </div>

                            <!-- Harga -->
                            <div class="flex items-center gap-2 text-sm font-normal">
                                <Wallet class="w-4 h-4" />
                                <span>Harga: {{ item.harga_produk.toLocaleString('id-ID') }}</span>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <a :href="`/produk/${item.slug}`"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2 mt-4 bg-white/30 text-white font-medium text-sm rounded-full hover:bg-white hover:text-black transition-all duration-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col lg:flex-row justify-between items-start lg:items-end">
                <div class="w-full lg:w-3/4 flex flex-col gap-4">
                    <div class="text-Color-Scheme-1-Text text-base font-semibold font-custom">Belum puas?</div>
                    <div class="flex flex-col gap-4">
                        <div class="text-Color-Scheme-1-Text text-5xl font-normal font-custom leading-tight">Produk
                            Terbaru
                        </div>
                        <div class="text-Color-Scheme-1-Text text-lg font-normal font-custom leading-relaxed">
                            Coba lihat keluaran terbaru kami.
                        </div>
                    </div>
                </div>
                <div class="mt-8 lg:mt-0">
                    <p v-if="item?.created_at"
                        class="px-6 py-2.5 bg-Opacity-Neutral-Darkest-5/5 rounded-full outline outline-1 text-Color-Neutral-Darkest text-base font-medium font-custom">
                        Rilis:
                        {{
                            new Date(item.created_at).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        })
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Product Terbaru Detail -->
        <div v-if="item"
            class="w-full px-4 lg:px-16 py-10 lg:py-20 bg-white flex flex-col items-start gap-20 font-custom">
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-20 w-full max-w-7xl mx-auto">
                <!-- Left: Product Image -->
                <div class="w-full lg:w-1/2 flex justify-center">
                    <div class="relative w-full max-w-[600px] aspect-[4/3] overflow-hidden rounded-2xl">
                        <img class="absolute inset-0 w-full h-full object-cover"
                            :src="getImageUrl(item.thumbnail_produk?.[activeImageIndex])" alt="Product Image" />

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
                            <div v-for="(img, i) in item.thumbnail_produk" :key="i"
                                :class="i === activeImageIndex ? 'w-2.5 h-2.5 rounded-full bg-white scale-110' : 'w-2.5 h-2.5 rounded-full bg-white opacity-30'" />
                        </div>
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="w-full lg:w-1/2 flex flex-col gap-8">
                    <!-- Title & Price -->
                    <h1 class="text-4xl text-secondary font-bold">{{ item.nama_produk }}</h1>
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
        </div>
    </AppLayout>
</template>
