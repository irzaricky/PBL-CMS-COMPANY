<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const articles = ref([]);
const categories = ref([]);
const selectedCategory = ref(null);
const loading = ref(true);
const searchQuery = ref("");
const searching = ref(false);
let debounceTimer = null;

// fungsi yang dijalankan saat view di muat
onMounted(() => {
    fetchCategories();
    fetchArticles();
});

// Fungsi untuk mengambil kategori
async function fetchCategories() {
    try {
        const response = await axios.get("/api/artikel/categories");
        categories.value = response.data.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}

// Fungsi filter artikel berdasarkan kategori
function filterByCategory(categoryId) {
    selectedCategory.value = categoryId;
    if (searchQuery.value.length > 0) {
        searchArticles(searchQuery.value);
    } else {
        fetchArticles();
    }
}

// Fungsi untuk menangani input pencarian dengan debounce
function handleSearch() {
    // Bersihkan timer sebelumnya jika ada
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    searching.value = true;

    // Buat timer baru
    debounceTimer = setTimeout(() => {
        if (searchQuery.value.length === 0) {
            fetchArticles();
        } else {
            searchArticles(searchQuery.value);
        }
    }, 500); // Tunggu 500ms setelah user berhenti mengetik
}

// Fungsi untuk mengambil artikel melalui api
async function fetchArticles() {
    try {
        loading.value = true;
        const params = {};
        if (selectedCategory.value) {
            params.category_id = selectedCategory.value;
        }

        const response = await axios.get("/api/artikel", { params });
        articles.value = response.data.data;
    } catch (error) {
        console.error("Error fetching articles:", error);
    } finally {
        loading.value = false;
        searching.value = false;
    }
}

// Fungsi untuk mencari artikel berdasarkan query melalui api
async function searchArticles(query) {
    try {
        loading.value = true;
        const params = {
            query: query,
        };

        if (selectedCategory.value) {
            params.category_id = selectedCategory.value;
        }

        const response = await axios.get("/api/artikel/search", { params });
        articles.value = response.data.data || []; //jika tidak ada data, set ke array kosong
    } catch (error) {
        console.error("Error searching articles:", error);
        articles.value = []; // Reset artikel jika error
    } finally {
        loading.value = false;
        searching.value = false;
    }
}

// Fungsi untuk mendapatkan URL gambar
function getImageUrl(image) {
    if (!image) return "/image/placeholder.jpeg";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.jpeg";
    }

    return `/storage/${image}`;
}

// Fungsi untuk memformat tanggal
function formatDate(date) {
    return new Date(date).toLocaleDateString();
}
</script>

<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8"
        >
            <h1 class="text-3xl font-bold text-red-900 mb-4 md:mb-0">
                Artikel
            </h1>

            <!-- Search box -->
            <div class="w-full md:w-1/3 relative">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search articles..."
                    @input="handleSearch"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                />
            </div>
        </div>

        <!-- Category filter -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button
                    @click="filterByCategory(null)"
                    class="px-3 py-1 rounded-full text-sm font-medium"
                    :class="
                        selectedCategory === null
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                    "
                >
                    Semua
                </button>
                <button
                    v-for="category in categories"
                    :key="category.id_kategori_artikel"
                    @click="filterByCategory(category.id_kategori_artikel)"
                    class="px-3 py-1 rounded-full text-sm font-medium"
                    :class="
                        selectedCategory === category.id_kategori_artikel
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                    "
                >
                    {{ category.nama_kategori_artikel }}
                </button>
            </div>
        </div>

        <!-- Loading state -->
        <div
            v-if="loading || searching"
            class="flex justify-center items-center py-12"
        >
            <div
                class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"
            ></div>
        </div>

        <!-- Articles grid -->
        <div
            v-else-if="articles.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="article in articles"
                :key="article.id_artikel"
                class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300"
            >
                <!-- Article image -->
                <div class="h-48 overflow-hidden">
                    <img
                        :src="getImageUrl(article.thumbnail_artikel)"
                        :alt="article.judul_artikel"
                        class="w-full h-full object-cover"
                    />
                </div>

                <!-- Article content -->
                <div class="p-6">
                    <h2
                        class="text-xl font-semibold text-gray-800 mb-2 line-clamp-2"
                    >
                        {{ article.judul_artikel }}
                    </h2>

                    <div class="flex items-center mb-3 text-sm text-gray-600">
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                            {{ article.user.name }}
                        </span>
                    </div>

                    <div
                        class="flex justify-between items-center text-sm text-gray-500"
                    >
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                />
                            </svg>
                            {{ article.kategoriArtikel.nama_kategori_artikel }}
                        </span>

                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            {{ formatDate(article.created_at) }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <a
                            :href="`/artikel/${article.slug}`"
                            class="inline-block px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded hover:bg-blue-600 transition-colors duration-300"
                        >
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- No articles found -->
        <div v-else class="text-center py-12">
            <p class="text-gray-500 text-lg">
                {{
                    searchQuery
                        ? `No articles found matching "${searchQuery}"`
                        : "No articles found"
                }}
                {{ selectedCategory !== null ? " in this category." : "" }}
            </p>
        </div>
    </div>
</template>
