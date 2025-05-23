<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";

const profil_perusahaan = ref(null);
const loading = ref(false);
const error = ref(null);

onMounted(() => {
    fetchProfilPerusahaan();
});

async function fetchProfilPerusahaan() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/profil-perusahaan/`);
        profil_perusahaan.value = response.data.data;
        loading.value = false;
    } catch (err) {
        error.value = "Event not found or an error occurred";
        loading.value = false;
        console.error("Error fetching profil_perusahaan:", err);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

// Slider index untuk Visi dan Misi, pakai array thumbnail_perusahaan yang sama
const visiIndex = ref(0);
const misiIndex = ref(0);

let visiInterval, misiInterval;

onMounted(() => {
    // auto slide untuk visi
    visiInterval = setInterval(() => {
        if (profil_perusahaan.value?.thumbnail_perusahaan?.length > 0) {
            visiIndex.value = (visiIndex.value + 1) % profil_perusahaan.value.thumbnail_perusahaan.length;
        }
    }, 4000);

    // auto slide untuk misi
    misiInterval = setInterval(() => {
        if (profil_perusahaan.value?.thumbnail_perusahaan?.length > 0) {
            misiIndex.value = (misiIndex.value + 1) % profil_perusahaan.value.thumbnail_perusahaan.length;
        }
    }, 4000);
});

onUnmounted(() => {
    clearInterval(visiInterval);
    clearInterval(misiInterval);
});
</script>

<template>
    <AppLayout>
        <!-- Section Header -->
        <div class="w-full px-6 lg:px-16 py-28 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto text-center flex flex-col gap-6 items-center">
                <h1 class="text-4xl lg:text-6xl font-custom font-normal leading-tight">
                    Visi & Misi Perusahaan
                </h1>
                <p class="text-base lg:text-lg font-custom font-normal leading-relaxed max-w-3xl">
                    Nilai-nilai yang menjadi dasar arah langkah dan strategi kami dalam memberikan dampak terbaik bagi
                    pelanggan dan masyarakat.
                </p>
            </div>
        </div>

        <!-- Section: Visi -->
        <div class="w-full px-6 lg:px-16 py-20 bg-white text-black">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row items-center gap-10">
                <!-- Teks Visi -->
                <div class="lg:w-1/2 flex flex-col gap-4">
                    <h2 class="text-3xl lg:text-5xl font-custom font-semibold text-secondary">
                        Visi Kami
                    </h2>
                    <p class="text-base lg:text-lg font-custom font-normal leading-relaxed">
                        {{ profil_perusahaan?.visi_perusahaan || 'Visi perusahaan belum tersedia.' }}
                    </p>
                </div>

                <!-- Slider Gambar Visi -->
                <div class="lg:w-1/2 overflow-hidden rounded-2xl shadow">
                    <div class="flex transition-transform duration-700 ease-in-out"
                        :style="{ transform: `translateX(-${visiIndex * 100}%)` }">
                        <template v-if="profil_perusahaan?.thumbnail_perusahaan?.length">
                            <img v-for="(img, index) in profil_perusahaan.thumbnail_perusahaan"
                                :key="'visi-img-' + index" :src="getImageUrl(img)" alt="Gambar Visi"
                                class="w-full flex-shrink-0 object-cover" style="height: 400px;" />
                        </template>
                        <template v-else>
                            <img src="https://placehold.co/600x400" alt="Placeholder Visi" class="w-full object-cover"
                                style="height: 400px;" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Misi -->
        <div class="w-full px-6 lg:px-16 py-20 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row-reverse items-center gap-10">
                <!-- Teks Misi -->
                <div class="lg:w-1/2 flex flex-col gap-4">
                    <h2 class="text-3xl lg:text-5xl font-custom font-semibold">
                        Misi Kami
                    </h2>
                    <p v-html="profil_perusahaan?.misi_perusahaan || 'Misi perusahaan belum tersedia.'"
                        class="text-base text-white prose-white lg:text-lg font-custom font-normal leading-relaxed prose prose-invert">
                    </p>
                </div>

                <!-- Slider Gambar Misi -->
                <div class="lg:w-1/2 overflow-hidden rounded-2xl shadow">
                    <div class="flex transition-transform duration-700 ease-in-out"
                        :style="{ transform: `translateX(-${misiIndex * 100}%)` }">
                        <template v-if="profil_perusahaan?.thumbnail_perusahaan?.length">
                            <img v-for="(img, index) in profil_perusahaan.thumbnail_perusahaan"
                                :key="'misi-img-' + index" :src="getImageUrl(img)" alt="Gambar Misi"
                                class="w-full flex-shrink-0 object-cover" style="height: 400px;" />
                        </template>
                        <template v-else>
                            <img src="https://placehold.co/600x400" alt="Placeholder Misi" class="w-full object-cover"
                                style="height: 400px;" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
