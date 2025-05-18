<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const articles = ref([]);
const categories = ref([]);
const selectedCategory = ref(null);
const loading = ref(true);
const searching = ref(false);

// Fungsi dijalankan saat komponen dimount
onMounted(() => {
    fetchCategories();
    fetchArticles();
});

// Ambil kategori artikel dari API
async function fetchCategories() {
    try {
        const response = await axios.get("/api/artikel/categories");
        categories.value = response.data.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}

// Ambil artikel dari API dan tambahkan properti readingTime ke tiap artikel
async function fetchArticles() {
    try {
        loading.value = true;
        const params = {};
        if (selectedCategory.value) {
            params.category_id = selectedCategory.value;
        }

        const response = await axios.get("/api/artikel", { params });
        const allArticles = response.data.data.map((item) => {
            const text = stripHtmlTags(item.konten_artikel || "");
            const wordCount = text.split(/\s+/).filter(Boolean).length;
            const minutes = Math.max(1, Math.ceil(wordCount / 200)); // minimal 1 menit
            return {
                ...item,
                readingTime: `${minutes} min read`,
            };
        });

        articles.value = getRandomArticles(allArticles, 3);
    } catch (error) {
        console.error("Error fetching articles:", error);
    } finally {
        loading.value = false;
        searching.value = false;
    }
}

// Ambil gambar dari storage atau tampilkan placeholder
function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    // Jika image adalah array (seperti thumbnail_artikel)
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    // Jika image adalah string langsung (seperti foto_profil)
    return `/storage/${image}`;
}


// Ambil N artikel secara acak dari array
function getRandomArticles(array, count) {
    const shuffled = array.sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
}

// Format tanggal menjadi lokal string
function formatDate(date) {
    return new Date(date).toLocaleDateString();
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
        class="w-full px-6 lg:px-16 py-28 bg-Color-Scheme-1-Background flex flex-col gap-20 overflow-hidden font-custom bg-secondary">
        <!-- Header -->
        <div class="w-full flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
            <div class="flex flex-col gap-4 text-white">
                <div class="text-Color-Scheme-1-Text text-base font-semibold leading-normal">
                    Artikel
                </div>
                <div class="flex flex-col gap-6">
                    <div class="text-Color-Scheme-1-Text text-5xl font-normal leading-[57.60px]">
                        Wawasan terbaru dari kami
                    </div>
                    <div class="text-Color-Scheme-1-Text text-lg font-normal leading-relaxed">
                        Baca artikel-artikel pilihan seputar bisnis, inovasi, dan kegiatan terbaru perusahaan.
                    </div>
                </div>
            </div>
            <a href="/artikel">
                <div
                    class="px-6 py-2.5 bg-Opacity-Neutral-Darkest-5/5 rounded-full outline outline-1 outline-white flex justify-center items-center gap-2">
                    <div class="text-white text-base font-medium leading-normal">
                        Lihat semua
                    </div>
                </div>
            </a>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
            <div v-for="article in articles" :key="article.id"
                class="bg-Color-Scheme-1-Foreground rounded-2xl border border-Color-Scheme-1-Border/20 flex flex-col overflow-hidden">
                <img class="w-full h-72 object-cover" :src="getImageUrl(article.thumbnail_artikel)" />
                <div class="p-6 flex flex-col gap-6 bg-white">
                    <div class="flex flex-col gap-2">
                        <div
                            class="inline-flex items-center rounded-full border border-Color-Scheme-1-Border/20 px-4 py-1 w-fit">
                            <span class="text-Color-Neutral-Darkest text-base font-semibold leading-tight">
                                {{ article.kategoriArtikel?.nama_kategori_artikel || 'Tanpa Kategori' }}
                            </span>
                        </div>
                        <a :href="`/artikel/${article.slug}`"
                            class="text-Color-Scheme-1-Text text-2xl font-normal pb-2 hover:underline">
                            {{ article.judul_artikel }}
                        </a>
                        <div class="text-Color-Scheme-1-Text text-base font-normal line-clamp-3">
                            {{ stripHtmlTags(article.konten_artikel) || 'Tidak ada ringkasan konten.' }}
                        </div>

                    </div>
                    <div class="flex items-center gap-4">
                        <img class="w-12 h-12 rounded-full" :src="getImageUrl(article.user.foto_profil)" />

                        <div class="flex flex-col">
                            <div class="text-Color-Scheme-1-Text text-sm font-semibold leading-tight">
                                {{ article.user.name || 'Anonim' }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-Color-Scheme-1-Text">
                                <span>{{ formatDate(article.created_at) }}</span>
                                <span class="text-lg font-normal leading-relaxed">•</span>
                                <span>{{ article.readingTime }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>