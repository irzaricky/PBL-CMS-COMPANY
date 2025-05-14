<script setup>
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { ref, onMounted } from "vue";
import { Check, CheckCheck, DollarSign, Wallet } from 'lucide-vue-next';

onMounted(() => {
    fetchProduk();
});

const produk = ref([]);

const fetchProduk = async () => {
    try {
        const response = await axios.get('/api/produk');
        produk.value = response.data.data;
    } catch (error) {
        console.error('Error fetching produk:', error);
    }
};

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}
</script>

<template>
    <Navbar />

    <div class="relative w-full bg-cover bg-center bg-no-repeat px-4 md:px-8 lg:px-16 py-28 flex flex-col justify-start items-center gap-20 overflow-hidden"
        :style="produk.length ? `background-image: url('${getImageUrl(produk[0].thumbnail_produk)}')` : ''">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/60 z-0"></div>

        <!-- Konten -->
        <div
            class="relative z-10 w-full max-w-3xl flex flex-col justify-start items-center gap-8 text-white text-center">
            <!-- Heading -->
            <div class="flex flex-col items-center gap-4">
                <div class="text-base font-semibold font-custom">Tagline</div>
                <div class="flex flex-col gap-6">
                    <h2 class="text-4xl md:text-5xl font-normal font-custom leading-tight">Short heading here</h2>
                    <p class="text-lg font-normal font-custom leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros
                        elementum tristique.
                    </p>
                </div>
            </div>

            <!-- Search form -->
            <div class="w-full flex flex-col items-center gap-4">
                <div class="w-full flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <input type="text" placeholder="Cari produk..."
                        class="flex-1 px-4 py-3 rounded-xl bg-white/10 text-white placeholder-white/60 outline outline-1 outline-transparent focus:outline-white focus:ring-0" />
                    <button
                        class="px-6 py-2.5 rounded-full bg-white text-black text-base font-medium font-custom hover:bg-gray-100 transition">
                        Cari
                    </button>
                </div>
                <p class="text-xs font-normal font-custom leading-none">
                    Gunakan kata kunci produk yang Anda inginkan.
                </p>
            </div>
        </div>
    </div>

    <div
        class="max-w-screen-xl mx-auto px-4 lg:px-8 py-28 bg-Color-Scheme-1-Background flex flex-col items-center gap-20 overflow-hidden">
        <div class="w-full flex flex-col lg:flex-row justify-between items-start lg:items-end">
            <div class="w-full lg:w-3/4 flex flex-col gap-4">
                <div class="text-Color-Scheme-1-Text text-base font-semibold font-custom">Tagline</div>
                <div class="flex flex-col gap-4">
                    <div class="text-Color-Scheme-1-Text text-5xl font-normal font-custom leading-tight">Products</div>
                    <div class="text-Color-Scheme-1-Text text-lg font-normal font-custom leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </div>
                </div>
            </div>
            <div class="mt-8 lg:mt-0">
                <button
                    class="px-6 py-2.5 bg-Opacity-Neutral-Darkest-5/5 rounded-full outline outline-1 text-Color-Neutral-Darkest text-base font-medium font-custom">
                    Lihat di marketplace
                </button>
            </div>
        </div>

        <!-- Card Produk -->
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="(item, index) in produk" :key="index"
                class="flex flex-col overflow-hidden rounded-2xl shadow-lg bg-white/10 transition hover:shadow-xl hover:scale-105 duration-300">
                <!-- Gambar Produk -->
                <img class="w-full h-72 object-cover object-center rounded-t-2xl"
                    :src="getImageUrl(item.thumbnail_produk)" alt="Product image" />

                <!-- Info Produk dengan latar biru -->
                <div class="bg-secondary text-white p-6 flex flex-col gap-4">
                    <!-- Nama Produk -->
                    <div class="text-2xl font-thin font-custom truncate">{{ item.nama_produk }}</div>

                    <!-- Varian dan Harga -->
                    <div class="flex justify-between items-center text-sm font-custom">
                        <!-- Status Tersedia -->
                        <div class="flex items-center gap-2">
                            <CheckCheck class="w-4 h-4 text-green-500" v-if="!item.deleted_at" />
                            <lucide-x class="w-4 h-4 text-red-500" v-else />
                            <span>{{ item.deleted_at || 'Tersedia' }}</span>
                        </div>

                        <!-- Harga Produk -->
                        <div class="flex items-center gap-2 font-semibold whitespace-nowrap">
                            <Wallet class="w-4 h-4" />
                            <span>{{ item.harga_produk.toLocaleString('id-ID') }}</span>
                        </div>
                    </div>


                    <!-- Tombol -->
                    <button
                        class="h-10 mt-2 px-5 py-2 bg-white text-secondary rounded-full text-base font-medium font-custom transition hover:bg-opacity-80">
                        Lihat detail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <Footer />
</template>
