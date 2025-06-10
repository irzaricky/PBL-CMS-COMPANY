<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const featuredArticles = ref([]);
const loading = ref(true);

onMounted(() => {
    fetchFeaturedArticles();
});

async function fetchFeaturedArticles() {
    try {
        const response = await axios.get("/api/artikel/featured");
        featuredArticles.value = response.data.data || [];
    } catch (error) {
        console.error("Error fetching featured articles:", error);
    } finally {
        loading.value = false;
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}

// Bersihkan tag HTML untuk ditampilkan dalam preview
function stripHtmlTags(html) {
    const div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
}
</script>

<template>
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
        <div class="flex flex-col gap-10" v-if="!loading">
            <h2 v-if="featuredArticles.length"class="text-4xl font-normal leading-loose">Artikel terpopuler</h2>
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
                                {{ featuredArticles[0].jumlah_view ?? '—' }}× dilihat
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a :href="`/artikel/${featuredArticles[0].slug}`" class="hover:underline">
                                <h3 class="text-3xl font-normal">
                                    {{ featuredArticles[0].judul_artikel }}
                                </h3>
                            </a>
                            <p class="text-base font-normal leading-normal">
                                {{ stripHtmlTags(featuredArticles[0].konten_artikel) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-4">
                        <img class="w-12 h-12 rounded-full object-cover"
                            :src="getImageUrl(featuredArticles[0].user.foto_profil)" />
                        <div class="flex flex-col">
                            <div class="text-sm font-semibold leading-tight">
                                {{ featuredArticles[0].user.name || 'Anonim' }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span>{{ featuredArticles[0].user.email }}</span>
                                <span class="text-xs font-normal leading-loose py-1">•</span>
                                <span> {{
                                    new Date(featuredArticles[0].created_at).toLocaleDateString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        year: 'numeric',
                                    })
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Artikel Teratas Lainnya -->
                <div class="flex-1 flex flex-col gap-8">
                    <h3 class="lg:hidden text-3xl py-5 font-normal">Artikel Populer Lainnya</h3>
                    <div v-for="(post, i) in featuredArticles.slice(1, 4)" :key="i"
                        class="flex flex-col lg:flex-row items-start gap-6">
                        <img class="w-full lg:w-48 h-72 lg:h-48 object-cover rounded-2xl"
                            :src="getImageUrl(post.thumbnail_artikel)" alt="thumbnail" />
                        <div class="flex-1 flex flex-col gap-4">
                            <div class="flex flex-col gap-4">
                                <div class="flex items-center gap-4">
                                    <span class="px-2.5 py-1 bg-opacity-50 rounded-full border text-sm font-semibold">
                                        {{ post.kategoriArtikel?.nama_kategori_artikel || 'Tanpa Kategori' }}
                                    </span>
                                    <span class="flex items-center gap-1 text-sm font-semibold">
                                        <LucideEye class="w-4 h-4" />
                                        {{ post.jumlah_view ?? '—' }}× dilihat
                                    </span>
                                </div>
                                <a :href="`/artikel/${post.slug}`" class="hover:underline">
                                    <h3 class="text-2xl font-normal">
                                        {{ post.judul_artikel }}
                                    </h3>
                                </a>
                            </div>
                            <div class="flex items-center gap-4 mt-2">
                                <img class="w-12 h-12 rounded-full object-cover"
                                    :src="getImageUrl(post.user.foto_profil)" />
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold leading-tight">
                                        {{ post.user.name || 'Anonim' }}
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <span>{{ post.user.email }}</span>
                                        <span class="text-xs font-normal leading-loose py-1">•</span>
                                        <span> {{
                                            new Date(post.created_at).toLocaleDateString('id-ID', {
                                                day: 'numeric',
                                                month: 'short',
                                                year: 'numeric',
                                            })
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-else class="flex flex-col gap-10">
            <h2 class="text-4xl font-normal leading-loose">Artikel terpopuler</h2>
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="flex-1 animate-pulse">
                    <div class="bg-gray-300 rounded-2xl h-96 w-full mb-6"></div>
                    <div class="h-6 bg-gray-300 rounded w-3/4 mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                </div>
                <div class="flex-1 flex flex-col gap-8">
                    <div v-for="i in 3" :key="i" class="animate-pulse flex gap-6">
                        <div class="bg-gray-300 rounded-2xl h-48 w-48"></div>
                        <div class="flex-1">
                            <div class="h-5 bg-gray-300 rounded w-3/4 mb-4"></div>
                            <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>