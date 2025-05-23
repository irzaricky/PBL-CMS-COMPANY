<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

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
    <AppLayout>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-20 font-custom text-black">
            <div class="flex flex-col gap-20">

                <!-- Breadcrumb -->
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                        <li>
                            <Link href="/" class="inline-flex items-center text-gray-500 hover:text-secondary">
                            <Home class="w-4 h-4 mr-2" />
                            Home
                            </Link>
                        </li>
                        <li class="inline-flex items-center">
                            <ChevronRight class="w-4 h-4 text-gray-400" />
                            <Link href="/galeri"
                                class="ml-1 inline-flex items-center text-gray-500 hover:text-secondary">
                            <Image class="w-4 h-4 mr-2" />
                            Galeri
                            </Link>
                        </li>
                        <li class="flex items-center max-w-[200px] truncate">
                            <ChevronRight class="w-4 h-4 text-gray-400" />
                            <span class="ml-1 text-gray-500 font-medium" :title="gallery?.judul_galeri">
                                {{ gallery?.judul_galeri || "Loading..." }}
                            </span>
                        </li>
                    </ol>
                </nav>

                <!-- Judul & Kategori -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="px-3 py-1 rounded-full border text-sm font-semibold bg-black/5 text-black">
                            {{ gallery?.kategoriGaleri?.nama_kategori_galeri || 'Tanpa Kategori' }}
                        </div>
                        <div class="text-sm font-semibold text-black">
                            {{ gallery?.thumbnail_galeri.length }} gambar
                        </div>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-normal leading-tight">
                        {{ gallery?.judul_galeri || 'Judul tidak tersedia' }}
                    </h1>
                </div>

                <!-- Gambar Utama -->
                <div class="relative rounded-2xl overflow-hidden shadow-sm aspect-[16/9] bg-white">
                    <img :src="getImageUrl(gallery?.thumbnail_galeri[activeImageIndex])" :alt="gallery?.judul_galeri"
                        class="w-full h-full object-cover" />
                </div>

                <!-- Thumbnails -->
                <div v-if="gallery?.thumbnail_galeri.length > 1" class="flex overflow-x-auto gap-4 py-4">
                    <div v-for="(img, i) in gallery.thumbnail_galeri" :key="i"
                        class="w-20 aspect-square rounded-lg cursor-pointer border-2 overflow-hidden flex-shrink-0"
                        :class="{
                            'border-secondary': activeImageIndex === i,
                            'border-transparent': activeImageIndex !== i
                        }" @click="setActiveImage(i)" :title="`Gambar ${i + 1}`">
                        <img :src="getImageUrl(img)" alt="Thumbnail" class="w-full h-full object-cover" />
                    </div>
                </div>

                <!-- Info Penulis dan Action (compact, sejajar) -->
                <div class="flex justify-between items-center flex-wrap">
                    <div class="flex gap-8 items-center">
                        <img class="w-12 h-12 rounded-full object-cover border"
                            :src="getImageUrl(gallery?.user?.foto_profil)" alt="Foto Penulis" />
                        <div class="flex flex-col gap-1">
                            <span class="text-base font-normal">Dibuat oleh</span>
                            <span class="text-base font-medium">{{ gallery?.user?.name || 'Anonim' }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-base font-normal">Dirilis pada</span>
                            <span class="text-base font-medium">{{ formatDate(gallery?.created_at) }}</span>
                        </div>
                    </div>

                    <div class="flex gap-4 items-center">
                        <div class="flex items-center gap-2 text-sm text-black">
                            <Download class="w-5 h-5" />
                            {{ gallery?.jumlah_unduhan || 0 }}
                        </div>
                        <button class="p-2 rounded-full bg-white border" @click="copyLink" title="Salin Link">
                            <Copy class="w-5 h-5" />
                        </button>
                        <button @click="downloadGallery(gallery.id_galeri)"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-secondary text-white hover:bg-black transition">
                            <Download class="w-5 h-5" />
                            Unduh gambar terpilih
                        </button>
                    </div>
                </div>

                <!-- Deskripsi dengan heading kecil -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
                    <div class="prose max-w-none" v-html="gallery?.deskripsi_galeri"></div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
