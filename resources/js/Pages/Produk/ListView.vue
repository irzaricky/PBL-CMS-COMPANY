<script setup>
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { Icon } from "@iconify/vue";
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from "vue";
import axios from "axios";

const produk = ref([]);
const searchQuery = ref('');
const searching = ref(false);
let debounceTimer = null;

onMounted(() => {
    fetchProduk();
    fetchKategori();
});

const fetchProduk = async (query = '') => {
    try {
        searching.value = true;
        let url = query.length > 0 ? '/api/produk/search' : '/api/produk';
        const params = query.length > 0 ? { query } : {};
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
        fetchProduk(searchQuery.value);
    }, 500); // debounce 500ms
};

const categories = ref([]);
async function fetchKategori() {
    try {
        const response = await axios.get("/api/produk/kategori");
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
                    <div class="text-base font-semibold font-custom">Tagline</div>
                    <div class="flex flex-col gap-6">
                        <h2 class="text-4xl md:text-5xl font-normal font-custom leading-tight">Short heading here</h2>
                        <p class="text-lg font-normal font-custom leading-relaxed">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros
                            elementum tristique.
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

        <div
            class="max-w-screen-xl mx-auto px-4 lg:px-8 py-28 bg-Color-Scheme-1-Background flex flex-col items-center gap-20 overflow-hidden">
            <div class="w-full flex flex-col lg:flex-row justify-between items-start lg:items-end">
                <div class="w-full lg:w-3/4 flex flex-col gap-4">
                    <div class="text-Color-Scheme-1-Text text-base font-semibold font-custom">Tagline</div>
                    <div class="flex flex-col gap-4">
                        <div class="text-Color-Scheme-1-Text text-5xl font-normal font-custom leading-tight">Products
                        </div>
                        <div class="text-Color-Scheme-1-Text text-lg font-normal font-custom leading-relaxed">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </div>
                    </div>
                </div>
                <div class="mt-8 lg:mt-0">
                    <button
                        class="px-6 py-2.5 bg-Opacity-Neutral-Darkest-5/5 rounded-full outline outline-1 text-Color-Neutral-Darkest text-base font-medium font-custom">
                        Lihat di marketplace
                    </button>
                </div>
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
        </div>
    </AppLayout>
</template>
