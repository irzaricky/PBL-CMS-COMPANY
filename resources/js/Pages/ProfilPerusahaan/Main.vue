<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, } from "vue";
import axios from "axios";
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const profil_perusahaan = ref(null);
const loading = ref(false);
const error = ref(null);

const maxKalimat = 1

const truncatedVisi = computed(() => {
    if (!profil_perusahaan.value?.visi_perusahaan) return 'Visi perusahaan belum tersedia.'

    const kalimat = profil_perusahaan.value.visi_perusahaan.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})

const showReadMoreVisi = computed(() => {
    if (!profil_perusahaan.value?.visi_perusahaan) return false
    return profil_perusahaan.value.visi_perusahaan.split(/(?<=[.!?])\s+/).length > maxKalimat
})
const truncatedMisi = computed(() => {
    if (!profil_perusahaan.value?.misi_perusahaan) return 'Misi perusahaan belum tersedia.'

    const kalimat = profil_perusahaan.value.misi_perusahaan.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})
const showReadMoreMisi = computed(() => {
    if (!profil_perusahaan.value?.misi_perusahaan) return false
    return profil_perusahaan.value.misi_perusahaan.split(/(?<=[.!?])\s+/).length > maxKalimat
})

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
</script>

<template>
    <AppLayout>
        <!-- Section 1: Mission Statement -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col justify-start items-center gap-16 overflow-hidden">
                <div class="w-full max-w-3xl flex flex-col justify-start items-center gap-6">
                    <div class="w-full flex flex-col justify-start items-center gap-4 text-center">
                        <h1 class="text-4xl lg:text-6xl font-normal font-custom leading-tight">
                            Haloo, kenalan dong!
                        </h1>
                        <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                            Ayo kita cari tahu lebih banyak tentang {{ profil_perusahaan?.nama_perusahaan }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Company Story -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row gap-10 lg:gap-20">
                <!-- KOLOM LOGO + TAGLINE -->
                <div class="flex-1 flex flex-col justify-center items-center text-center gap-4 overflow-hidden">
                    <!-- LOGO PERUSAHAAN -->
                    <div class="mb-2">
                        <img :src="getImageUrl(profil_perusahaan?.logo_perusahaan)" alt="Logo Perusahaan"
                            class="w-80 h-80 object-contain" />
                    </div>

                    <!-- TAGLINE -->
                    <div class="text-sm lg:text-base font-semibold font-custom leading-normal">Profil Perusahaan</div>

                    <!-- TITLE -->
                    <h2 class="text-3xl lg:text-5xl font-normal font-custom leading-tight">
                        {{ profil_perusahaan?.nama_perusahaan }}
                    </h2>
                </div>

                <!-- KOLOM TEKS -->
                <div class="flex-1 flex flex-col justify-center items-center text-center gap-6">
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        {{ profil_perusahaan?.deskripsi_perusahaan
                            ? profil_perusahaan.deskripsi_perusahaan
                            : 'Sejarah perusahaan belum tersedia.' }}
                    </p>
                </div>
            </div>
        </div>


        <!-- Section 3: Visi & Misi Grid -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-white text-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 max-w-screen-xl mx-auto">
                <!-- Visi - kiri atas -->
                <div
                    class="order-1 flex flex-col justify-center items-start lg:items-end gap-4 lg:rounded-tl-[100px] lg:justify-right bg-secondary py-6 px-10 shadow">
                    <h3 class="text-2xl lg:text-4xl font-semibold font-custom lg:text-right">Visi Kami</h3>
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed lg:text-right">
                        {{ profil_perusahaan?.visi_perusahaan
                            ? truncatedVisi
                            : 'Visi perusahaan belum tersedia.' }}
                        <Link v-if="showReadMoreVisi" href="/visi-misi" class="text-blue-400 cursor-pointer">
                        ... Baca selengkapnya
                        </Link>
                    </p>
                </div>

                <!-- Gambar atas - kanan atas -->
                <div class="order-2 lg:order-2 lg:rounded-tr-[100px] overflow-hidden shadow">
                    <img class="w-full h-auto object-cover" src="https://placehold.co/600x400" alt="Visi Image" />
                </div>

                <!-- Gambar bawah - kiri bawah -->
                <div class="order-3 lg:order-3 lg:rounded-bl-[100px] overflow-hidden shadow">
                    <img class="w-full h-auto object-cover" src="https://placehold.co/600x400" alt="Misi Image" />
                </div>

                <!-- Misi - kanan bawah -->
                <div
                    class="order-4 flex flex-col justify-center items-start gap-4 lg:rounded-br-[100px] bg-secondary py-6 px-10 shadow">
                    <h3 class="text-2xl lg:text-4xl font-semibold font-custom">Misi Kami</h3>
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        {{ profil_perusahaan?.misi_perusahaan
                            ? truncatedMisi
                            : 'Misi perusahaan belum tersedia.' }}
                        <Link v-if="showReadMoreMisi" href="/visi-misi" class="text-blue-400 cursor-pointer">
                        ... Baca selengkapnya
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
