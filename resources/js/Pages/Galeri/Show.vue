<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import Navbar from "@/Components/Navbar.vue";

const gallery = ref(null);
const loading = ref(true);
const error = ref(null);
const activeImageIndex = ref(0);

const props = defineProps({
    slug: String,
});

onMounted(() => {
    fetchGallery();
});

async function fetchGallery() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/galeri/${props.slug}`);
        gallery.value = response.data.data;
        loading.value = false;
    } catch (err) {
        error.value = "Gallery not found or an error occurred";
        loading.value = false;
        console.error("Error fetching gallery:", err);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

function formatDate(date) {
    if (!date) return "";

    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}

async function downloadGallery(galleryId) {
    try {
        const response = await axios.get(`/api/galeri/download/${galleryId}`);

        // Update the download count locally
        if (gallery.value) {
            gallery.value.jumlah_unduhan++;
        }

        // Get the first image if available
        const image = gallery.value.thumbnail_galeri;
        if (image && typeof image === "object" && image.length > 0) {
            // Create a download link
            const link = document.createElement("a");
            link.href = `/storage/${image[activeImageIndex.value]}`;
            link.download = `gallery-${galleryId}-${activeImageIndex.value}.jpg`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    } catch (error) {
        console.error("Error downloading gallery:", error);
    }
}

function setActiveImage(index) {
    activeImageIndex.value = index;
}
</script>

<template>
    <Navbar />
    <div class="bg-gray-50 min-h-screen py-10 font-custom">
        <!-- Breadcrumb navigation -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a
                            href="/"
                            class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600"
                        >
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                ></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <a
                                href="/galeri"
                                class="ml-1 text-sm text-gray-500 hover:text-blue-600 md:ml-2"
                                >Gallery</a
                            >
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <span
                                class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate max-w-[200px]"
                            >
                                {{ gallery?.judul_galeri || "Loading..." }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Loading state with shimmer effect -->
            <div
                v-if="loading"
                class="bg-white rounded-lg shadow-lg overflow-hidden"
            >
                <div class="animate-pulse">
                    <div class="h-64 bg-gray-300"></div>
                    <div class="p-6">
                        <div class="h-8 bg-gray-300 rounded w-2/3 mb-4"></div>
                        <div class="flex gap-2 mb-6">
                            <div class="h-4 bg-gray-300 rounded w-20"></div>
                            <div class="h-4 bg-gray-300 rounded w-24"></div>
                            <div class="h-4 bg-gray-300 rounded w-32"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-4 bg-gray-300 rounded"></div>
                            <div class="h-4 bg-gray-300 rounded"></div>
                            <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error state -->
            <div
                v-else-if="error"
                class="bg-white rounded-lg shadow-lg p-8 text-center"
            >
                <svg
                    class="w-16 h-16 text-red-500 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Gallery Not Found
                </h2>
                <p class="text-gray-600 mb-6">{{ error }}</p>
                <a
                    href="/galeri"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Gallery
                </a>
            </div>

            <!-- Gallery content -->
            <div
                v-else-if="gallery"
                class="bg-white rounded-lg shadow-lg overflow-hidden"
            >
                <!-- Gallery main image -->
                <div
                    class="relative"
                    v-if="
                        gallery.thumbnail_galeri &&
                        gallery.thumbnail_galeri.length > 0
                    "
                >
                    <img
                        :src="`/storage/${gallery.thumbnail_galeri[activeImageIndex]}`"
                        :alt="gallery.judul_galeri"
                        class="w-full h-[400px] object-contain bg-gray-100"
                    />
                </div>

                <!-- Thumbnail navigation -->
                <div
                    class="p-4 bg-gray-100"
                    v-if="
                        gallery.thumbnail_galeri &&
                        gallery.thumbnail_galeri.length > 1
                    "
                >
                    <div class="flex overflow-x-auto gap-2 pb-2">
                        <div
                            v-for="(image, index) in gallery.thumbnail_galeri"
                            :key="index"
                            class="flex-shrink-0 w-20 h-20 cursor-pointer border-2"
                            :class="
                                activeImageIndex === index
                                    ? 'border-blue-500'
                                    : 'border-transparent'
                            "
                            @click="setActiveImage(index)"
                        >
                            <img
                                :src="`/storage/${image}`"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>
                </div>

                <!-- Gallery info -->
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p
                                class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full mb-3"
                                v-if="gallery.kategoriGaleri"
                            >
                                {{
                                    gallery.kategoriGaleri.nama_kategori_galeri
                                }}
                            </p>
                            <h1
                                class="text-3xl sm:text-4xl font-bold text-gray-800"
                            >
                                {{ gallery.judul_galeri }}
                            </h1>
                        </div>
                        <button
                            @click="downloadGallery(gallery.id_galeri)"
                            class="flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                />
                            </svg>
                            Download ({{ gallery.jumlah_unduhan }})
                        </button>
                    </div>

                    <!-- Author and metadata bar -->
                    <div
                        class="flex items-center justify-between flex-wrap border-b border-gray-200 pb-4 mb-6"
                    >
                        <div
                            class="flex items-center mb-2 sm:mb-0"
                            v-if="gallery.user"
                        >
                            <div
                                class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 mr-3"
                            >
                                {{ gallery.user.name.charAt(0) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ gallery.user.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ formatDate(gallery.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery description -->
                    <div
                        class="prose prose-lg prose-blue max-w-none mb-8"
                        v-html="gallery.deskripsi_galeri"
                    ></div>
                </div>
            </div>

            <!-- Back button and navigation -->
            <div
                class="mt-10 flex justify-between items-center"
                v-if="!loading && !error"
            >
                <a
                    href="/galeri"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Gallery
                </a>
            </div>
        </div>
    </div>
</template>
