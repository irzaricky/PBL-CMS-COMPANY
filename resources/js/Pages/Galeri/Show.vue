<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import {
    Home,
    Image,
    ChevronRight,
    Download,
    Copy,
    Eye,
} from "lucide-vue-next";
import CopyLink from "@/Components/Modal/CopyLink.vue";

const gallery = ref(null);
const loading = ref(true);
const error = ref(null);
const activeImageIndex = ref(0);
const showCopyModal = ref(false);

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

function fallbackCopy(text) {
    const ta = document.createElement("textarea");
    ta.value = text;
    ta.style.position = "fixed";
    ta.style.opacity = "0";
    document.body.appendChild(ta);
    ta.select();
    document.execCommand("copy");
    document.body.removeChild(ta);
    showCopyModal.value = true;
}

async function copyLink() {
    const url = window.location.href;
    try {
        // Only call writeText if the API exists and is a function
        if (
            navigator.clipboard &&
            typeof navigator.clipboard.writeText === "function"
        ) {
            await navigator.clipboard.writeText(url);
        } else {
            throw new Error("Clipboard API not available");
        }
        showCopyModal.value = true;
    } catch (err) {
        console.warn("Clipboard write failed, using fallback:", err);
        fallbackCopy(url);
    }
}

function closeCopyModal() {
    showCopyModal.value = false;
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
    <AppLayout>
        <div
            class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-20 font-custom text-black"
        >
            <!-- Loading Skeleton -->
            <div v-if="loading" class="flex flex-col gap-20">
                <!-- Skeleton Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                        <li>
                            <div class="inline-flex items-center">
                                <div class="w-4 h-4 mr-2 bg-gray-200 animate-pulse rounded"></div>
                                <div class="w-16 h-4 bg-gray-200 animate-pulse rounded"></div>
                            </div>
                        </li>
                        <li class="inline-flex items-center">
                            <div class="w-4 h-4 bg-gray-200 animate-pulse rounded mx-1"></div>
                            <div class="inline-flex items-center">
                                <div class="w-4 h-4 mr-2 bg-gray-200 animate-pulse rounded"></div>
                                <div class="w-16 h-4 bg-gray-200 animate-pulse rounded"></div>
                            </div>
                        </li>
                        <li class="flex items-center min-w-0">
                            <div class="w-4 h-4 bg-gray-200 animate-pulse rounded mx-1"></div>
                            <div class="w-24 h-4 bg-gray-200 animate-pulse rounded ml-1"></div>
                        </li>
                    </ol>
                </nav>

                <!-- Skeleton Judul & Kategori -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-8 bg-gray-200 animate-pulse rounded-full"></div>
                        <div class="w-20 h-6 bg-gray-200 animate-pulse rounded"></div>
                    </div>
                    <div class="w-3/4 h-12 bg-gray-200 animate-pulse rounded"></div>
                </div>

                <!-- Skeleton Gambar Utama -->
                <div class="relative rounded-2xl overflow-hidden shadow-sm aspect-[16/9] bg-gray-200 animate-pulse">
                </div>

                <!-- Skeleton Thumbnails -->
                <div class="flex overflow-x-auto gap-4 py-4">
                    <div class="w-20 aspect-square rounded-lg bg-gray-200 animate-pulse flex-shrink-0"></div>
                    <div class="w-20 aspect-square rounded-lg bg-gray-200 animate-pulse flex-shrink-0"></div>
                    <div class="w-20 aspect-square rounded-lg bg-gray-200 animate-pulse flex-shrink-0"></div>
                    <div class="w-20 aspect-square rounded-lg bg-gray-200 animate-pulse flex-shrink-0"></div>
                </div>

                <!-- Skeleton Info Penulis -->
                <div class="bg-gray-50 rounded-xl w-full p-6 border border-gray-100">
                    <!-- Skeleton Author Profile -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-gray-200 animate-pulse"></div>
                        <div class="flex-1">
                            <div class="w-40 h-6 bg-gray-200 animate-pulse rounded mb-2"></div>
                            <div class="w-32 h-4 bg-gray-200 animate-pulse rounded"></div>
                        </div>
                    </div>

                    <!-- Skeleton Stats & Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-gray-200 animate-pulse rounded"></div>
                                <div class="w-24 h-4 bg-gray-200 animate-pulse rounded"></div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <div class="w-24 h-10 bg-gray-200 animate-pulse rounded-lg"></div>
                            <div class="w-32 h-10 bg-gray-200 animate-pulse rounded-lg"></div>
                        </div>
                    </div>
                </div>

                <!-- Skeleton Deskripsi -->
                <div>
                    <div class="w-32 h-6 bg-gray-200 animate-pulse rounded mb-4"></div>
                    <div class="space-y-3">
                        <div class="w-full h-4 bg-gray-200 animate-pulse rounded"></div>
                        <div class="w-full h-4 bg-gray-200 animate-pulse rounded"></div>
                        <div class="w-full h-4 bg-gray-200 animate-pulse rounded"></div>
                        <div class="w-5/6 h-4 bg-gray-200 animate-pulse rounded"></div>
                        <div class="w-3/4 h-4 bg-gray-200 animate-pulse rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Actual Content (Existing) -->
            <div v-else-if="!error" class="flex flex-col gap-20">
                <!-- Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol
                        class="inline-flex items-center space-x-1 md:space-x-3 text-sm"
                    >
                        <li>
                            <Link
                                href="/"
                                class="inline-flex items-center text-gray-500 hover:text-secondary"
                            >
                                <Home class="w-4 h-4 mr-2" />
                                Home
                            </Link>
                        </li>
                        <li class="inline-flex items-center">
                            <ChevronRight class="w-4 h-4 text-gray-400" />
                            <Link
                                href="/galeri"
                                class="ml-1 inline-flex items-center text-gray-500 hover:text-secondary"
                            >
                                <Image class="w-4 h-4 mr-2" />
                                Galeri
                            </Link>
                        </li>
                        <li class="flex items-center min-w-0">
                            <ChevronRight
                                class="w-4 h-4 text-gray-400 flex-shrink-0"
                            />
                            <span
                                class="ml-1 text-sm font-medium text-gray-500 truncate max-w-[140px] sm:max-w-[200px] md:max-w-[300px]"
                                :title="gallery?.judul_galeri"
                            >
                                {{ gallery?.judul_galeri || "Loading..." }}
                            </span>
                        </li>
                    </ol>
                </nav>

                <!-- Judul & Kategori -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="px-3 py-1 rounded-full border text-sm font-semibold bg-black/5 text-black"
                        >
                            {{
                                gallery?.kategoriGaleri?.nama_kategori_galeri ||
                                "Tanpa Kategori"
                            }}
                        </div>
                        <div class="text-sm font-semibold text-black">
                            {{ gallery?.thumbnail_galeri?.length || 0 }} gambar
                        </div>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-normal leading-tight">
                        {{ gallery?.judul_galeri || "Judul tidak tersedia" }}
                    </h1>
                </div>

                <!-- Gambar Utama -->
                <div
                    class="relative rounded-2xl overflow-hidden shadow-sm aspect-[16/9] bg-white"
                >
                    <img
                        :src="
                            getImageUrl(
                                gallery?.thumbnail_galeri?.[activeImageIndex]
                            )
                        "
                        :alt="gallery?.judul_galeri"
                        class="w-full h-full object-cover"
                    />
                </div>

                <!-- Thumbnails -->
                <div
                    v-if="gallery?.thumbnail_galeri?.length > 1"
                    class="flex overflow-x-auto gap-4 py-4"
                >
                    <div
                        v-for="(img, i) in gallery.thumbnail_galeri"
                        :key="i"
                        class="w-20 aspect-square rounded-lg cursor-pointer border-2 overflow-hidden flex-shrink-0"
                        :class="{
                            'border-secondary': activeImageIndex === i,
                            'border-transparent': activeImageIndex !== i,
                        }"
                        @click="setActiveImage(i)"
                        :title="`Gambar ${i + 1}`"
                    >
                        <img
                            :src="getImageUrl(img)"
                            alt="Thumbnail"
                            class="w-full h-full object-cover"
                        />
                    </div>
                </div>

                <!-- Info Penulis dengan style dari Artikel -->
                <div
                    class="bg-gray-50 rounded-xl w-full p-6 border border-gray-100"
                >
                    <!-- Author Profile -->
                    <div class="flex items-center gap-4 mb-4">
                        <img
                            class="w-14 h-14 rounded-full object-cover ring-2 ring-white shadow-sm"
                            :src="getImageUrl(gallery?.user?.foto_profil)"
                            alt="Foto Penulis"
                        />
                        <div class="flex-1">
                            <h4 class="font-semibold text-lg text-black">
                                {{ gallery?.user?.name || "Anonim" }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ formatDate(gallery?.created_at) }}
                            </p>
                        </div>
                    </div>

                    <!-- Stats & Actions -->
                    <div
                        class="flex items-center justify-between pt-4 border-t border-gray-200"
                    >
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 text-gray-600">
                                <Download class="w-4 h-4" />
                                <span class="text-sm font-medium"
                                    >{{ gallery?.jumlah_unduhan || 0 }}×
                                    diunduh</span
                                >
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button
                                class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-medium"
                                @click="copyLink"
                            >
                                <Copy class="w-4 h-4" />
                                <span class="hidden sm:inline">Salin Link</span>
                            </button>

                            <button
                                @click="downloadGallery(gallery.id_galeri)"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-secondary text-white hover:bg-black transition text-sm font-medium"
                            >
                                <Download class="w-4 h-4" />
                                <span class="hidden sm:inline"
                                    >Unduh Gambar</span
                                >
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi dengan heading kecil -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
                    <div
                        class="prose max-w-none"
                        v-html="gallery?.deskripsi_galeri"
                    ></div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else class="flex flex-col items-center justify-center py-20">
                <div class="text-center">
                    <Image class="w-16 h-16 mx-auto text-gray-300 mb-4" />
                    <h2 class="text-2xl font-medium text-gray-900 mb-2">
                        Gallery tidak ditemukan
                    </h2>
                    <p class="text-gray-600 mb-8">
                        {{ error }}
                    </p>
                    <Link
                        href="/galeri"
                        class="inline-flex items-center px-6 py-3 bg-secondary text-white rounded-xl hover:bg-black transition"
                    >
                        Kembali ke Galeri
                    </Link>
                </div>
            </div>
        </div>

        <!-- Copy Link Modal -->
        <CopyLink
            :show="showCopyModal"
            @close="closeCopyModal"
            :auto-close="true"
            :auto-close-delay="3000"
        />
    </AppLayout>
</template>
