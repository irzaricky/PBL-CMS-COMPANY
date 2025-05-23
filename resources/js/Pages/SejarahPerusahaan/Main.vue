<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, } from "vue";
import axios from "axios";
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

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


</script>

<template>
    <AppLayout>
        <div
            class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-background font-custom text-black max-w-screen-xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-10">
                <!-- LEFT COLUMN -->
                <div class="lg:w-1/2 flex flex-col gap-8">
                    <div class="flex flex-col gap-4">
                        <div class="text-base font-semibold">Sejarah</div>
                        <div class="text-4xl sm:text-5xl font-normal leading-snug">
                            {{ profil_perusahaan?.nama_perusahaan || 'Memuat...' }}
                        </div>
                        <div class="text-lg leading-relaxed">
                            Ikuti perjalanan kami dari awal hingga saat ini. Kami telah melalui banyak tantangan dan pencapaian yang membentuk kami menjadi perusahaan yang kami kenal sekarang.
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="lg:w-1/2 flex flex-col gap-10">
                    <div v-if="profil_perusahaan?.sejarah_perusahaan?.length" v-for="(item, index) in profil_perusahaan.sejarah_perusahaan" :key="index"
                        class="flex items-start gap-4 relative">
                        <!-- Timeline Line -->
                        <div class="w-12 flex flex-col items-center">
                            <div class="h-full w-px bg-secondary"></div>
                            <div class="w-3.5 h-3.5 bg-secondary rounded-full mt-[-6px]"></div>
                            <div class="h-full w-px bg-secondary flex-1"></div>
                        </div>

                        <!-- Timeline Content -->
                        <div
                            class="flex-1 p-6 bg-foreground border border-border/20 rounded-2xl flex flex-col gap-6 bg-secondary text-white">
                            <div class="flex flex-col gap-2">
                                <div class="text-3xl sm:text-4xl font-normal leading-tight">{{ item.tahun }}</div>
                                <div class="text-2xl sm:text-3xl font-normal leading-snug">{{ item.judul }}</div>
                            </div>
                            <div class="text-lg leading-relaxed">
                                {{ item.deskripsi }}
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-white text-lg">Belum ada data sejarah perusahaan.</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>