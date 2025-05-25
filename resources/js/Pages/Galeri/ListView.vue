<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { ref, onMounted } from "vue";
import { ChevronLeft, ChevronRight } from "lucide-vue-next";

const galeries = ref([]);
const activeSlideIndex = ref({}); // key by galeri id
const loading = ref(true);

// Load galeri saat komponen dimuat
onMounted(() => {
    fetchGaleries();
});

async function fetchGaleries() {
    try {
        loading.value = true;
        const response = await axios.get("/api/galeri");
        galeries.value = response.data.data;

        // Set slide aktif awal untuk tiap galeri
        response.data.data.forEach((galeri) => {
            activeSlideIndex.value[galeri.id_galeri] = 0;
        });
    } catch (error) {
        console.error("Error fetching galeries:", error);
    } finally {
        loading.value = false;
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    return `/storage/${image}`;
}

function prevImage(id) {
    const index = activeSlideIndex.value[id];
    const total =
        galeries.value.find((g) => g.id_galeri === id)?.thumbnail_galeri
            .length || 0;
    activeSlideIndex.value[id] = (index - 1 + total) % total;
}

function nextImage(id) {
    const index = activeSlideIndex.value[id];
    const total =
        galeries.value.find((g) => g.id_galeri === id)?.thumbnail_galeri
            .length || 0;
    activeSlideIndex.value[id] = (index + 1) % total;
}
</script>

<template>
    <AppLayout>
        <div class="bg-secondary text-white font-custom">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-28">
                <div class="flex flex-col gap-20">
                    <div class="text-center">
                        <h2 class="text-4xl lg:text-5xl font-normal">
                            Image Gallery
                        </h2>
                        <p class="text-lg text-secondary/80 mt-2">
                            Kumpulan dokumentasi visual dalam bentuk galeri.
                        </p>
                    </div>

                    <!-- List Galeri -->
                    <div
                        v-if="!loading"
                        class="flex flex-col divide-y divide-white/20"
                    >
                        <div
                            v-for="(galeri, index) in galeries"
                            :key="galeri.id_galeri"
                            class="flex flex-col lg:flex-row gap-12 items-start lg:items-center py-10"
                        >
                            <!-- Kiri: Judul + Deskripsi -->
                            <div
                                class="lg:w-1/2 flex flex-col justify-center h-full"
                            >
                                <div class="max-w-xl">
                                    <h3
                                        class="text-2xl lg:text-3xl font-normal pb-2"
                                    >
                                        {{ galeri.judul_galeri }}
                                    </h3>
                                    <p class="text-base text-secondary/80 mt-2">
                                        {{ galeri.deskripsi_galeri }}
                                    </p>
                                    <a
                                        :href="`/galeri/${galeri.slug}`"
                                        class="inline-flex items-center justify-center gap-2 px-6 py-2 mt-4 bg-white/30 text-white font-medium text-sm rounded-full hover:bg-white hover:text-black transition-all duration-300"
                                    >
                                        Lihat Selengkapnya
                                    </a>
                                </div>
                            </div>

                            <!-- Kanan: Slider -->
                            <div class="lg:w-1/2 flex flex-col gap-6 w-full">
                                <!-- Gambar utama -->
                                <div
                                    class="aspect-video w-full overflow-hidden rounded-2xl bg-gray-200"
                                >
                                    <img
                                        v-if="galeri.thumbnail_galeri.length"
                                        :src="
                                            getImageUrl(
                                                galeri.thumbnail_galeri[
                                                    activeSlideIndex[
                                                        galeri.id_galeri
                                                    ]
                                                ]
                                            )
                                        "
                                        class="w-full h-full object-cover"
                                        alt="Galeri Image"
                                    />
                                </div>

                                <!-- Navigasi -->
                                <div class="flex justify-between items-center">
                                    <div class="flex gap-4">
                                        <button
                                            @click="prevImage(galeri.id_galeri)"
                                            class="p-3 bg-white border border-secondary rounded-full text-secondary flex items-center justify-center"
                                        >
                                            <ChevronLeft class="w-5 h-5" />
                                        </button>
                                        <button
                                            @click="nextImage(galeri.id_galeri)"
                                            class="p-3 bg-white border border-secondary rounded-full text-secondary flex items-center justify-center"
                                        >
                                            <ChevronRight class="w-5 h-5" />
                                        </button>
                                    </div>

                                    <!-- Dot navigasi -->
                                    <div class="flex gap-2">
                                        <span
                                            v-for="(
                                                img, index
                                            ) in galeri.thumbnail_galeri"
                                            :key="index"
                                            :class="[
                                                'w-2 h-2 rounded-full transition-all',
                                                index ===
                                                activeSlideIndex[
                                                    galeri.id_galeri
                                                ]
                                                    ? 'bg-white'
                                                    : 'bg-white/30',
                                            ]"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center text-white/70">
                        Loading galeri...
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
