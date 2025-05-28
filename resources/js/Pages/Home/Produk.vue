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
                <div class="text-base font-semibold text-typography-dark">Mau lihat lebih jauh?</div>
                <div class="text-5xl font-bold text-typography-dark">Jelajahi produk kami</div>
                <div class="text-lg font-normal text-typography-dark">Lihat list lengkap produk kami atau jelajahi lebih
                    lanjut lagi di window shopping.</div>
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full mt-12">
                <div v-for="item in produk" :key="item.id_produk"
                    class="group rounded-2xl bg-secondary shadow-md hover:shadow-lg hover:bg-typography-hover1 transition-all duration-300 overflow-hidden flex flex-col">
                    <img :src="getImageUrl(item.thumbnail_produk)" alt="Thumbnail Produk"
                        class="w-full h-48 object-cover" />

                    <!-- Konten -->
                    <div class="p-4 flex flex-col h-full">
                        <div class="text-xl font-bold text-third mb-4">{{ item.nama_produk }}</div>
                        <div class="text-sm font-normal text-primary line-clamp-3 flex-grow mb-4">{{ item.deskripsi_produk }}</div>

                        <!-- Button Lihat Selengkapnya -->
                        <div class="pt-3 mt-auto">
                            <a :href="`/produk/${item.slug}`"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary text-sm font-semibold rounded-lg hover:bg-typography-dark/40 transition">
                                Lihat Selengkapnya
                                <ChevronsRight class="w-5 h-5" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
