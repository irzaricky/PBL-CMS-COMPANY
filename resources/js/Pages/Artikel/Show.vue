<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";

const article = ref(null);
const loading = ref(true);
const error = ref(null);
const readingTime = ref("");

const props = defineProps({
    slug: String,
});


const relatedArticles = ref([]);

async function fetchRelatedArticles() {
    try {
        const response = await axios.get('/api/artikel/featured');
        relatedArticles.value = response.data.data;
    } catch (err) {
        console.error("Error fetching related articles:", err);
    }
}

onMounted(() => {
    fetchArticle();
    fetchRelatedArticles();
});

function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert("Link berhasil disalin!");
    });
}

// fungsi untuk mengambil data artikel dari API
async function fetchArticle() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/artikel/${props.slug}`);
        article.value = response.data.data;

        // Calculate reading time (roughly 200 words per minute)
        const wordCount = article.value.konten_artikel
            .replace(/<[^>]*>/g, "")
            .split(/\s+/).length;
        const minutes = Math.ceil(wordCount / 200);
        readingTime.value = `${minutes} min read`;

        loading.value = false;
    } catch (err) {
        error.value = "Article not found or an error occurred";
        loading.value = false;
        console.error("Error fetching article:", err);
    }
}

// fungsi untuk mendapatkan URL gambar
function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

// fungsi untuk memformat tanggal
function formatDate(date) {
    if (!date) return "";

    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}
</script>

<template>
    <AppLayout>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-20 font-custom text-black">
            <div class="flex flex-col lg:flex-row gap-16">
                <!-- Bagian Kiri: Artikel -->
                <div class="flex-1 flex flex-col gap-20">
                    <!-- Breadcrumb -->
                    <div class="flex flex-col gap-12">
                        <div>
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                                    <li>
                                        <Link href="/"
                                            class="inline-flex items-center text-gray-500 hover:text-secondary">
                                        <Home class="w-4 h-4 mr-2" />
                                        Home
                                        </Link>
                                    </li>
                                    <li class="inline-flex items-center">
                                        <ChevronRight class="w-4 h-4 text-gray-400" />
                                        <Link href="/artikel"
                                            class="ml-1 inline-flex items-center text-gray-500 hover:text-secondary">
                                        <FileText class="w-4 h-4 mr-2" />
                                        Artikel
                                        </Link>
                                    </li>
                                    <li class="flex items-center">
                                        <ChevronRight class="w-4 h-4 text-gray-400" />
                                        <span class="ml-1 text-gray-500 font-medium truncate max-w-[200px]"
                                            :title="article?.judul_artikel">
                                            {{ article?.judul_artikel || "Loading..." }}
                                        </span>
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                <div class="px-3 py-1 rounded-full border text-sm font-semibold bg-black/5 text-black">
                                    {{ article?.kategoriArtikel?.nama_kategori_artikel || 'Tanpa Kategori' }}
                                </div>
                                <div class="text-sm font-semibold text-black">
                                    {{ readingTime }}
                                </div>
                            </div>
                            <h1 class="text-4xl sm:text-5xl font-normal leading-tight">
                                {{ article?.judul_artikel || 'Judul tidak tersedia' }}
                            </h1>
                        </div>
                    </div>

                    <!-- Gambar & Info Penulis -->
                    <div class="flex flex-col gap-8">
                        <img class="w-full h-[300px] sm:h-[400px] lg:h-[600px] rounded-2xl object-cover"
                            :src="getImageUrl(article?.thumbnail_artikel)" :alt="article?.judul_artikel" />
                        <div class="flex justify-between items-start flex-wrap gap-8">
                            <!-- Info Penulis -->
                            <!-- Info Penulis -->
                            <div class="flex gap-12 items-center">
                                <img class="w-12 h-12 rounded-full object-cover border"
                                    :src="getImageUrl(article?.user?.foto_profil)" alt="Foto Penulis" />
                                <div class="flex flex-col gap-1">
                                    <span class="text-base font-normal">Ditulis oleh</span>
                                    <span class="text-base font-medium">
                                        {{ article?.user?.name || 'Anonim' }}
                                    </span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-base font-normal">Dirilis pada</span>
                                    <span class="text-base font-medium">
                                        {{ formatDate(article?.created_at) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Icon Actions -->
                            <div class="flex gap-4 items-center">
                                <div class="flex items-center gap-2 text-sm text-black">
                                    <Eye class="w-5 h-5" />
                                    {{ article?.jumlah_view || 0 }}
                                </div>
                                <button class="p-2 rounded-full bg-white border" @click="copyLink" title="Salin Link">
                                    <Copy class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Konten Artikel -->
                    <div class="prose prose-lg max-w-none text-black" v-html="article?.konten_artikel"></div>
                </div>

                <!-- Bagian Kanan: Artikel Lainnya -->
                <div class="w-full lg:w-80 flex-shrink-0">
                    <h2 class="text-xl font-semibold mb-4">Artikel Teratas Lainnya</h2>
                    <div class="flex flex-col gap-4">
                        <div v-for="item in relatedArticles" :key="item.id_artikel"
                            class="flex items-start gap-4 p-3 border rounded-lg hover:shadow transition cursor-pointer"
                            @click="$inertia.visit(`/artikel/${item.slug}`)">
                            <img class="w-16 h-16 rounded object-cover flex-shrink-0"
                                :src="getImageUrl(item.thumbnail_artikel)" :alt="item.judul_artikel" />
                            <div class="flex flex-col justify-center">
                                <h3 class="font-semibold text-sm leading-snug line-clamp-2 pb-1">
                                    {{ item.judul_artikel }}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    {{ Math.ceil(item.konten_artikel.replace(/<[^>]*>/g, '').split(/\s+/).length / 200)
                                    }} min read
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
