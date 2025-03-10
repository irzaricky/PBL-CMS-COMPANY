<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Articles</h1>

        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div
                class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"
            ></div>
        </div>

        <!-- Articles grid -->
        <div
            v-else
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div
                v-for="article in articles"
                :key="article.id"
                class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300"
            >
                <!-- Article image -->
                <div class="h-48 overflow-hidden">
                    <img
                        :src="getImageUrl(article.gambar_cover)"
                        :alt="article.judul"
                        class="w-full h-full object-cover"
                    />
                </div>

                <!-- Article content -->
                <div class="p-6">
                    <h2
                        class="text-xl font-semibold text-gray-800 mb-2 line-clamp-2"
                    >
                        {{ article.judul }}
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
                            {{ article.kategori.nama }}
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
                            {{ formatDate(article.tanggal_upload) }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <a
                            href="#"
                            class="inline-block px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded hover:bg-blue-600 transition-colors duration-300"
                        >
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- No articles message -->
        <div v-if="!loading && articles.length === 0" class="text-center py-12">
            <p class="text-gray-500 text-lg">No articles found</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            articles: [],
            loading: true,
        };
    },
    mounted() {
        this.fetchArticles();
    },
    methods: {
        async fetchArticles() {
            try {
                const response = await axios.get("/api/articles");
                this.articles = response.data.data.data;
                this.loading = false;
            } catch (error) {
                console.error("Error fetching articles:", error);
                this.loading = false;
            }
        },
        getImageUrl(image) {
            return image ? `/storage/${image}` : "/placeholder.jpg";
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
    },
};
</script>
