<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { megaMenuCache } from "./MegaMenuStore";

const articles = ref([]);

onMounted(() => {
    fetchArticles();
});

async function fetchArticles() {
    try {
        // Check if we have valid cached data
        if (megaMenuCache.isValid("articles")) {
            articles.value = megaMenuCache.articles;
            return;
        }

        // Otherwise fetch from API
        const response = await axios.get("/api/artikel/most-viewed");
        articles.value = response.data.data;

        // Cache the response
        megaMenuCache.setCache("articles", response.data.data);
    } catch (error) {
        console.error("Error fetching articles:", error);
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
    <div>
        <div class="font-bold text-h6-bold mb-4 text-typography-main">
            Artikel Populer
        </div>
        <div class="flex flex-col gap-3">
            <div
                v-for="artikel in articles"
                :key="artikel.id_artikel"
                class="flex gap-3 bg-white rounded-lg shadow hover:shadow-lg transition p-3 items-center"
            >
                <img
                    :src="getImageUrl(artikel.thumbnail_artikel)"
                    :alt="artikel.judul_artikel"
                    class="w-12 h-12 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex flex-col overflow-hidden">
                    <a
                        :href="`/artikel/${artikel.slug}`"
                        class="text-h6-bold text-typography-main truncate hover:underline"
                    >
                        {{ artikel.judul_artikel }}
                    </a>
                    <span
                        class="text-xs text-typography-dark line-clamp-2 mt-1"
                        v-html="artikel.konten_artikel"
                    ></span>
                </div>
            </div>
            <div v-if="!articles.length" class="text-typography-dark text-xs">
                Tidak ada artikel populer.
            </div>
        </div>
    </div>
</template>
