<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const articles = ref([]);
const featuredArticles = ref([]);
const categories = ref([]);
const selectedCategory = ref(null);
const searchQuery = ref("");
const loading = ref(true);
const searching = ref(false);
const currentPage = ref(1);
const lastPage = ref(1);
let debounceTimer = null;

onMounted(() => {
    fetchCategories();
    fetchFeaturedArticles();
    fetchArticles();
});

async function fetchCategories() {
    try {
        const response = await axios.get("/api/artikel/categories");
        categories.value = response.data.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}

async function fetchFeaturedArticles() {
    try {
        const response = await axios.get("/api/artikel/featured");
        featuredArticles.value = response.data.data || [];
    } catch (error) {
        console.error("Error fetching featured articles:", error);
    }
}

async function fetchArticles(page = 1) {
    try {
        loading.value = true;
        const params = {
            page,
        };
        if (selectedCategory.value) {
            params.category_id = selectedCategory.value;
        }
        const response = await axios.get("/api/artikel", { params });

        articles.value = response.data.data;
        currentPage.value = response.data.meta?.current_page || 1;
        lastPage.value = response.data.meta?.last_page || 1;
    } catch (error) {
        console.error("Error fetching articles:", error);
    } finally {
        loading.value = false;
        searching.value = false;
    }
}


async function searchArticles(query) {
    try {
        loading.value = true;
        const params = { query };
        if (selectedCategory.value) {
            params.category_id = selectedCategory.value;
        }
        const response = await axios.get("/api/artikel/search", { params });
        articles.value = response.data.data || [];
    } catch (error) {
        console.error("Error searching articles:", error);
        articles.value = [];
    } finally {
        loading.value = false;
        searching.value = false;
    }
}

function filterByCategory(categoryId) {
    selectedCategory.value = categoryId;
    if (searchQuery.value.length > 0) {
        searchArticles(searchQuery.value);
    } else {
        fetchArticles();
    }
}

function handleSearch() {
    if (debounceTimer) clearTimeout(debounceTimer);
    searching.value = true;
    debounceTimer = setTimeout(() => {
        if (searchQuery.value.length === 0) {
            fetchArticles();
        } else {
            searchArticles(searchQuery.value);
        }
    }, 500);
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}

function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

// Bersihkan tag HTML untuk ditampilkan dalam preview
function stripHtmlTags(html) {
    const div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
}

function goToPage(page) {
    if (page < 1 || page > lastPage.value) return;
    fetchArticles(page);
}
</script>

<template>
    <AppLayout>
        <div
            class="w-full px-4 sm:px-6 lg:px-6 py-28 max-w-screen-xl mx-auto flex flex-col gap-20 overflow-hidden font-custom text-black">
            <!-- Heading Section -->
            <div class="w-full max-w-3xl flex flex-col gap-4">
                <div class="text-base font-semibold">Artikel</div>
                <div class="flex flex-col items-start gap-6">
                    <h1 class="text-4xl lg:text-6xl font-normal leading-tight">
                        Jelajahi Beragam Cerita dan Tips Seru
                    </h1>
                    <p class="text-lg font-normal leading-relaxed">
                        Mulai petualangan pengetahuan dengan artikel-artikel penuh ide segar dan cerita menarik yang
                        membangkitkan semangat.
                    </p>
                </div>
            </div>

            <!-- Featured Blog Section -->
            <div class="flex flex-col gap-10">
                <h2 class="text-4xl font-normal leading-loose">Artikel terpopuler</h2>
                <div class="flex flex-col lg:flex-row gap-8" v-if="featuredArticles.length">
                    <!-- Large Featured Blog -->
                    <div class="flex-1 flex flex-col gap-6" v-if="featuredArticles[0]">
                        <img class="w-full h-96 object-cover rounded-2xl"
                            :src="getImageUrl(featuredArticles[0].thumbnail_artikel)" alt="thumbnail" />
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                <span class="px-2.5 py-1 bg-opacity-50 rounded-full border text-sm font-semibold">
                                    {{ featuredArticles[0].kategoriArtikel?.nama_kategori_artikel || 'Tanpa Kategori' }}
                                </span>
                                <span class="flex items-center gap-1 text-sm font-semibold">
                                    <LucideEye class="w-4 h-4" />
                                    {{ featuredArticles[0].jumlah_view ?? '—' }}
                                </span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <h3 class="text-3xl font-normal">
                                    {{ featuredArticles[0].judul_artikel }}
                                </h3>
                                <p class="text-base font-normal leading-normal">
                                    {{ stripHtmlTags(featuredArticles[0].konten_artikel) }}
                                </p>
                            </div>
                        </div>
                        <a :href="`/artikel/${featuredArticles[0].slug}`"
                            class="inline-flex items-center gap-2 text-base font-medium hover:underline">
                            Baca selengkapnya
                            <ChevronRight class="w-5 h-5" />
                        </a>
                        <div class="flex items-center gap-4 mt-4">
                            <img class="w-12 h-12 rounded-full object-cover"
                                :src="getImageUrl(featuredArticles[0].user.foto_profil)" />
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold leading-tight">
                                    {{ featuredArticles[0].user.name || 'Anonim' }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span> {{
                                        new Date(featuredArticles[0].created_at).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                        })
                                    }}</span>
                                    <span class="text-lg font-normal leading-relaxed">•</span>
                                    <span>{{ featuredArticles[0].readingTime }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3 Artikel Teratas Lainnya -->
                    <div class="flex-1 flex flex-col gap-8">
                        <div v-for="(post, i) in featuredArticles.slice(1, 4)" :key="i"
                            class="flex flex-col lg:flex-row items-start gap-6">
                            <img class="w-full lg:w-64 h-64 object-cover rounded-2xl"
                                :src="getImageUrl(post.thumbnail_artikel)" alt="thumbnail" />
                            <div class="flex-1 flex flex-col gap-4">
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-center gap-4">
                                        <span
                                            class="px-2.5 py-1 bg-opacity-50 rounded-full border text-sm font-semibold">
                                            {{ post.kategoriArtikel?.nama_kategori_artikel || 'Tanpa Kategori' }}
                                        </span>
                                        <span class="flex items-center gap-1 text-sm font-semibold">
                                            <LucideEye class="w-4 h-4" />
                                            {{ post.jumlah_view ?? '—' }}
                                        </span>
                                    </div>
                                    <h3 class="text-2xl font-normal">
                                        {{ post.judul_artikel }}
                                    </h3>
                                </div>
                                <a :href="`/artikel/${post.slug}`"
                                    class="inline-flex items-center gap-2 text-base font-medium hover:underline">
                                    Baca selengkapnya
                                    <ChevronRight class="w-5 h-5" />
                                </a>
                                <div class="flex items-center gap-4 mt-2">
                                    <img class="w-12 h-12 rounded-full object-cover"
                                        :src="getImageUrl(post.user.foto_profil)" />
                                    <div class="flex flex-col">
                                        <div class="text-sm font-semibold leading-tight">
                                            {{ post.user.name || 'Anonim' }}
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <span> {{
                                                new Date(post.created_at).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'long',
                                                    year: 'numeric',
                                                    hour: '2-digit',
                                                minute: '2-digit',
                                                })
                                                }}</span>
                                            <span class="text-lg font-normal leading-relaxed">•</span>
                                            <span>{{ post.readingTime }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-6 py-20 font-custom text-black">
            <div class="flex flex-col lg:flex-row gap-10">

                <!-- Artikel: di kiri desktop (order-1), di bawah mobile (order-2) -->
                <div class="lg:w-3/4 w-full flex flex-col gap-6 order-2 lg:order-1">
                    <h2 class="text-4xl mb-4">Semua Artikel</h2>

                    <!-- Skeleton saat loading -->
                    <div v-if="loading" class="grid gap-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
                        <div v-for="i in 4" :key="i" class="animate-pulse flex flex-col gap-4">
                            <div class="bg-gray-300 rounded-2xl h-64 w-full"></div>
                            <div class="h-5 bg-gray-300 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-full"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                        </div>
                    </div>

                    <!-- Tidak ada artikel ditemukan -->
                    <div v-else-if="articles.length === 0" class="text-gray-500">
                        Tidak ada artikel ditemukan.
                    </div>

                    <!-- Daftar artikel -->
                    <div class="grid gap-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2" v-else>
                        <article v-for="(post, index) in articles" :key="index" class="flex flex-col gap-6">
                            <img :src="getImageUrl(post.thumbnail_artikel)" alt="Blog post image"
                                class="rounded-2xl h-64 w-full object-cover" />
                            <div class="flex items-center gap-4">
                                <span class="px-2.5 py-1 bg-opacity-50 rounded-full border text-sm font-semibold">
                                    {{ post.kategoriArtikel?.nama_kategori_artikel || 'Tanpa Kategori' }}
                                </span>
                                <span class="flex items-center gap-1 text-sm font-semibold">
                                    <LucideEye class="w-4 h-4" />
                                    {{ post.jumlah_view ?? '—' }}
                                </span>
                            </div>
                            <h3 class="text-2xl leading-snug">{{ post.judul_artikel }}</h3>
                            <p class="text-base leading-normal">
                                {{ stripHtmlTags(post.konten_artikel).slice(0, 70) }}...
                            </p>
                            <a :href="`/artikel/${post.slug}`"
                                class="inline-flex items-center gap-2 font-medium hover:underline">
                                Baca selengkapnya
                                <ChevronRight class="w-6 h-6" />
                            </a>
                            <div class="flex items-center gap-4 mt-2">
                                <img class="w-12 h-12 rounded-full object-cover"
                                    :src="getImageUrl(post.user.foto_profil, true)" alt="Foto profil" />
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold leading-tight">
                                        {{ post.user.name || 'Anonim' }}
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <span> {{
                                            new Date(post.created_at).toLocaleDateString('id-ID', {
                                                day: 'numeric',
                                                month: 'long',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                            })
                                        }}</span>
                                        <span class="text-lg font-normal leading-relaxed">•</span>
                                        <span>{{ post.readingTime }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <div v-if="lastPage > 1" class="flex justify-center items-center gap-2 mt-10">
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-3 py-1 border rounded disabled:opacity-50">
                            Sebelumnya
                        </button>

                        <span class="px-3 py-1">{{ currentPage }} / {{ lastPage }}</span>

                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                            class="px-3 py-1 border rounded disabled:opacity-50">
                            Selanjutnya
                        </button>
                    </div>
                </div>


                <!-- Sidebar: Search & Kategori (di kanan desktop order-2, di atas mobile order-1) -->
                <aside class="lg:w-1/4 w-full flex flex-col gap-6 order-1 lg:order-2">
                    <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Cari artikel..."
                        class="w-full px-4 py-2 border rounded-lg" />
                    <div class="flex flex-col gap-2">
                        <button :class="[
                            'text-left px-4 py-2 rounded-lg border',
                            selectedCategory === null
                                ? 'bg-secondary text-white'
                                : 'bg-gray-200 text-gray-800 hover:bg-black hover:text-white'
                        ]" @click="filterByCategory(null)">
                            Semua Kategori
                        </button>

                        <button v-for="cat in categories" :key="cat.id_kategori_artikel" :class="[
                            'text-left px-4 py-2 rounded-lg border',
                            selectedCategory === cat.id_kategori_artikel
                                ? 'bg-secondary text-white'
                                : 'bg-gray-200 text-gray-800 hover:bg-black hover:text-white'
                        ]" @click="filterByCategory(cat.id_kategori_artikel)">
                            {{ cat.nama_kategori_artikel }}
                        </button>
                    </div>
                </aside>
            </div>
        </section>
    </AppLayout>
</template>
