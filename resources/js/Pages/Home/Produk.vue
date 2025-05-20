<script setup>
import { ChevronRight } from 'lucide-vue-next';
import { ref, onMounted } from "vue";
import axios from "axios";

const produk = ref([]);
const loading = ref(false);
const error = ref(null);

onMounted(() => {
    fetchProduk();
});

async function fetchProduk() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/produk`);
        const allProduk = response.data.data;
        produk.value = getRandomProduk(allProduk, 4);
    } catch (err) {
        error.value = "Produk not found or an error occurred";
        console.error("Error fetching produk:", err);
    } finally {
        loading.value = false;
    }
    function getRandomProduk(array, count) {
        const shuffled = array.sort(() => 0.5 - Math.random());
        return shuffled.slice(0, count);
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
    <div
        class="w-full px-6 lg:px-16 py-28 bg-Color-Scheme-1-Background flex flex-col items-center gap-20 overflow-hidden font-custom">
        <!-- Wrapper untuk membatasi lebar -->
        <div class="w-full max-w-screen-xl mx-auto">
            <!-- Judul Section -->
            <div class="text-center max-w-[768px] flex flex-col items-center gap-4 mx-auto">
                <div class="text-base font-semibold text-Color-Scheme-1-Text">Mau lihat lebih jauh?</div>
                <div class="text-5xl font-normal text-Color-Scheme-1-Text">Jelajahi produk kami</div>
                <div class="text-lg font-normal text-Color-Scheme-1-Text">Lihat list lengkap produk, atau sekadar Window
                    Shopping.</div>
            </div>

        <!-- Grid Produk -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
            <div v-for="item in produk" :key="item.id_produk"
                class="group rounded-2xl bg-secondary shadow-md hover:shadow-lg hover:bg-typography-hover1 transition-all duration-300 overflow-hidden flex flex-col">
                <img :src="getImageUrl(item.thumbnail_produk)" alt="Thumbnail Produk"
                    class="w-full h-48 object-cover" />

                <!-- Overlay -->
                <div
                    class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                <!-- Konten -->
                <div
                    class="relative z-20 mt-auto text-white flex flex-col gap-2 opacity-0 translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
                    <div class="text-2xl font-normal">{{ item.nama_produk }}</div>
                    <div class="text-sm font-normal leading-snug truncate">{{ item.deskripsi_produk }}</div>
                    <a :href="`/produk/${item.slug}`"
                        class="flex items-center gap-2 text-white font-medium hover:underline">
                        Lihat Selengkapnya
                        <ChevronRight class="w-3" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
