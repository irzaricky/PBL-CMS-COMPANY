<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { ref, onMounted } from "vue";
import { ChevronLeft, ChevronRight } from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";

const galeries = ref([]);
const categories = ref([]);
const activeSlideIndex = ref({}); // key by galeri id
const loadingGaleries = ref(true);
const loadingCategories = ref(true);
const searchQuery = ref('');
const selectedCategory = ref(null);
const currentPage = ref(1);
const lastPage = ref(1);
let debounceTimer = null;

// Load galeri saat komponen dimuat
onMounted(() => {
    fetchData();
});

async function fetchData() {
    loadingGaleries.value = true;
    loadingCategories.value = true;
    await Promise.all([fetchGaleries(), fetchCategories()]);
    loadingGaleries.value = false;
    loadingCategories.value = false;
}

async function fetchGaleries(query = '', categoryId = null, page = 1) {
    try {
        loadingGaleries.value = true;
        let url = query.length > 0 || categoryId !== null ? '/api/galeri/search' : '/api/galeri';
        const params = { page };
        if (query.length > 0) params.query = query;
        if (categoryId !== null) params.category_id = categoryId;

        const response = await axios.get(url, { params });
        galeries.value = response.data.data || [];
        currentPage.value = response.data.meta?.current_page || 1;
        lastPage.value = response.data.meta?.last_page || 1;

        galeries.value.forEach(galeri => {
            activeSlideIndex.value[galeri.id_galeri] = 0;
        });
    } catch (error) {
        console.error("Error fetching galeries:", error);
        galeries.value = [];
    } finally {
        loadingGaleries.value = false;
    }
}


async function fetchCategories() {
    try {
        const response = await axios.get("/api/galeri/categories");
        categories.value = response.data.data || [];
    } catch (error) {
        console.error("Error fetching categories:", error);
        categories.value = [];
    }
}

const filterByCategory = (categoryId) => {
    selectedCategory.value = categoryId;
    currentPage.value = 1;
    fetchGaleries(searchQuery.value, categoryId, 1);
};

const handleSearch = () => {
    if (debounceTimer) clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        currentPage.value = 1;
        fetchGaleries(searchQuery.value, selectedCategory.value, 1);
    }, 500);
};

const goToPage = (page) => {
    if (page < 1 || page > lastPage.value) return;
    fetchGaleries(searchQuery.value, selectedCategory.value, page);
};

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    return `/storage/${image}`;
}

function prevImage(id) {
    const index = activeSlideIndex.value[id];
    const total =
        galeries.value.find((g) => g.id_galeri === id)?.thumbnail_galeri
            .length || 0;
    activeSlideIndex.value[id] = (index - 1 + total) % total;
}

function nextImage(id) {
    const index = activeSlideIndex.value[id];
    const total =
        galeries.value.find((g) => g.id_galeri === id)?.thumbnail_galeri
            .length || 0;
    activeSlideIndex.value[id] = (index + 1) % total;
}
function stripHtmlTags(html) {
    const div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
}
</script>

<template>
    <AppLayout>
        <div class="bg-secondary text-white font-custom">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-28">
                <div class="flex flex-col gap-20">
                    <!-- Heading & Search Form -->
                    <div class="text-center">
                        <h2 class="text-4xl lg:text-5xl font-normal">Image Gallery</h2>
                        <p class="text-lg text-secondary/80 mt-2 mb-10">Kumpulan dokumentasi visual dalam bentuk galeri.
                        </p>

                        <!-- Search form -->
                        <div class="w-full max-w-2xl mx-auto flex flex-col items-center gap-4">
                            <div class="w-full flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                                <input v-model="searchQuery" @input="handleSearch" type="text"
                                    placeholder="Cari galeri..."
                                    class="flex-1 px-4 py-3 rounded-xl bg-white/10 text-white placeholder-white/60 outline outline-1 outline-transparent focus:outline-white focus:ring-0 font-custom" />
                                <button @click="fetchGaleries(searchQuery)"
                                    class="px-6 py-2.5 rounded-full bg-white text-black text-base font-medium font-custom hover:bg-gray-100 transition">
                                    Cari
                                </button>
                            </div>
                            <p class="text-xs font-normal font-custom leading-none">
                                Gunakan kata kunci untuk mencari galeri yang Anda inginkan.
                            </p>
                        </div>
                    </div>

                    <!-- Category Filters -->
                    <div class="w-full overflow-x-auto">
                        <div class="flex gap-2 font-custom text-sm whitespace-nowrap">
                            <!-- Skeleton Kategori -->
                            <template v-if="loadingCategories">
                                <div v-for="n in 4" :key="n"
                                    class="px-4 py-2 rounded-xl bg-white/20 animate-pulse w-24 h-9">
                                </div>
                            </template>

                            <!-- Tombol Kategori asli -->
                            <template v-else>
                                <button @click="filterByCategory(null)"
                                    class="px-4 py-2 rounded-xl font-medium transition border" :class="selectedCategory === null
                                        ? 'bg-white text-secondary'
                                        : 'bg-transparent text-white border-white/30 hover:bg-white/10'">
                                    Semua
                                </button>

                                <button v-for="category in categories" :key="category.id_kategori_galeri"
                                    @click="filterByCategory(category.id_kategori_galeri)"
                                    class="px-4 py-2 rounded-xl font-medium transition border" :class="selectedCategory === category.id_kategori_galeri
                                        ? 'bg-white text-secondary'
                                        : 'bg-transparent text-white border-white/30 hover:bg-white/10'">
                                    {{ category.nama_kategori_galeri }}
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- List Galeri -->
                    <div class="flex flex-col divide-y divide-white/20">
                        <!-- Skeleton Loading -->
                        <div v-if="loadingGaleries" v-for="i in 3" :key="`skeleton-${i}`"
                            class="flex flex-col lg:flex-row gap-12 items-start lg:items-center py-10">
                            <!-- Kiri: Judul + Deskripsi Skeleton -->
                            <div class="lg:w-1/2 flex flex-col justify-center h-full">
                                <div class="max-w-xl">
                                    <div class="h-8 w-2/3 bg-white/30 animate-pulse rounded-md mb-4"></div>
                                    <div class="h-4 w-full bg-white/20 animate-pulse rounded-md mb-2"></div>
                                    <div class="h-4 w-4/5 bg-white/20 animate-pulse rounded-md mb-2"></div>
                                    <div class="h-4 w-3/4 bg-white/20 animate-pulse rounded-md mb-4"></div>
                                    <div class="h-8 w-32 bg-white/30 animate-pulse rounded-full mt-4"></div>
                                </div>
                            </div>

                            <!-- Kanan: Slider Skeleton -->
                            <div class="lg:w-1/2 flex flex-col gap-6 w-full">
                                <div class="aspect-video w-full overflow-hidden rounded-2xl bg-white/20 animate-pulse">
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 rounded-full bg-white/30 animate-pulse"></div>
                                        <div class="w-12 h-12 rounded-full bg-white/30 animate-pulse"></div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div v-for="dot in 4" :key="dot"
                                            class="w-2 h-2 rounded-full bg-white/30 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else-if="galeries.length === 0" class="py-20 flex flex-col items-center text-center">
                            <img src="/image/empty.svg" alt="No galleries found" class="w-40 h-40 mb-6" />
                            <h3 class="text-2xl font-semibold mb-2">Tidak ada galeri ditemukan</h3>
                            <p class="text-secondary/70 mb-6 max-w-md">
                                Coba kata kunci pencarian lain atau pilih kategori berbeda.
                            </p>
                            <button @click="fetchData"
                                class="px-6 py-2 rounded-full bg-white text-secondary hover:bg-white/90 transition font-medium">
                                Muat Ulang
                            </button>
                        </div>

                        <!-- Actual Galleries -->
                        <div v-else v-for="(galeri, index) in galeries" :key="galeri.id_galeri"
                            class="flex flex-col lg:flex-row gap-12 items-start lg:items-center py-10">
                            <!-- Kiri: Judul + Deskripsi -->
                            <div
                                class="lg:w-1/2 flex flex-col justify-center h-full"
                            >
                                <div class="max-w-xl">
                                    <h3
                                        class="text-2xl lg:text-3xl font-normal pb-2"
                                    >
                                        {{ galeri.judul_galeri }}
                                    </h3>
                                    <p class="text-base text-secondary/80 mt-2">
                                        {{ stripHtmlTags(galeri.deskripsi_galeri) }}
                                    </p>
                                    <Link :href="`/galeri/${galeri.slug}`"
                                        class="inline-flex items-center justify-center gap-2 px-6 py-2 mt-4 bg-white/30 text-white font-medium text-sm rounded-full hover:bg-white hover:text-black transition-all duration-300">
                                    Lihat Selengkapnya
                                    </Link>
                                </div>
                            </div>

                            <!-- Kanan: Slider -->
                            <div class="lg:w-1/2 flex flex-col gap-6 w-full">
                                <!-- Gambar utama -->
                                <div
                                    class="aspect-video w-full overflow-hidden rounded-2xl bg-gray-200"
                                >
                                    <img
                                        v-if="galeri.thumbnail_galeri.length"
                                        :src="
                                            getImageUrl(
                                                galeri.thumbnail_galeri[
                                                    activeSlideIndex[
                                                        galeri.id_galeri
                                                    ]
                                                ]
                                            )
                                        "
                                        class="w-full h-full object-cover"
                                        alt="Galeri Image"
                                    />
                                </div>

                                <!-- Navigasi -->
                                <div class="flex justify-between items-center">
                                    <div class="flex gap-4">
                                        <button
                                            @click="prevImage(galeri.id_galeri)"
                                            class="p-3 bg-white border border-secondary rounded-full text-secondary flex items-center justify-center"
                                        >
                                            <ChevronLeft class="w-5 h-5" />
                                        </button>
                                        <button
                                            @click="nextImage(galeri.id_galeri)"
                                            class="p-3 bg-white border border-secondary rounded-full text-secondary flex items-center justify-center"
                                        >
                                            <ChevronRight class="w-5 h-5" />
                                        </button>
                                    </div>

                                    <!-- Dot navigasi -->
                                    <div class="flex gap-2">
                                        <span
                                            v-for="(
                                                img, index
                                            ) in galeri.thumbnail_galeri"
                                            :key="index"
                                            :class="[
                                                'w-2 h-2 rounded-full transition-all',
                                                index ===
                                                activeSlideIndex[
                                                    galeri.id_galeri
                                                ]
                                                    ? 'bg-white'
                                                    : 'bg-white/30',
                                            ]"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="!loadingGaleries && lastPage > 1"
                        class="flex justify-center items-center gap-4 mt-10 font-custom text-sm">
                        <!-- Tombol Sebelumnya -->
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-4 py-2 rounded-xl font-medium transition border" :class="currentPage === 1
                                ? 'bg-white/10 text-white/40 cursor-not-allowed border-white/10'
                                : 'bg-transparent text-white border-white/30 hover:bg-white/10'">
                            Sebelumnya
                        </button>

                        <!-- Indikator halaman -->
                        <div class="px-4 py-2 rounded-xl border border-white text-white font-semibold">
                            {{ currentPage }} / {{ lastPage }}
                        </div>

                        <!-- Tombol Selanjutnya -->
                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                            class="px-4 py-2 rounded-xl font-medium transition border" :class="currentPage === lastPage
                                ? 'bg-white/10 text-white/40 cursor-not-allowed border-white/10'
                                : 'bg-transparent text-white border-white/30 hover:bg-white/10'">
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>