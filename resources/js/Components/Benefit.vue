<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
const profil_perusahaan = ref(null);
const loading = ref(false);
const error = ref(null);
onMounted(() => {
    fetchProfilPerusahaan();
    async function fetchProfilPerusahaan() {
        try {
            loading.value = true;
            const response = await axios.get(`/api/profil-perusahaan`);
            profil_perusahaan.value = response.data.data;
            loading.value = false;
        } catch (err) {
            error.value = "Event not found or an error occurred";
            loading.value = false;
            console.error("Error fetching profil_perusahaan:", err);
        }
    }
});
</script>
<template>
    <div class="w-full px-16 py-28 bg-Color-Scheme-1-Background flex flex-col items-center gap-20 overflow-hidden">
        <!-- Header -->
        <div class="w-[768px] flex flex-col items-center gap-4">
            <div class="text-center text-Color-Scheme-1-Text text-base font-semibold font-['Plus_Jakarta_Sans']">Lalu
            </div>
            <div
                class="text-center text-Color-Scheme-1-Text text-5xl font-normal font-['Plus_Jakarta_Sans'] leading-[57.60px]">
                Mengapa memilih {{ profil_perusahaan?.nama_perusahaan || "Loading..." }}?</div>
            <div
                class="text-center text-Color-Scheme-1-Text text-lg font-normal font-['Plus_Jakarta_Sans'] leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-2 gap-8">
            <div v-for="(item, index) in 4" :key="index"
                class="bg-Color-Scheme-1-Foreground rounded-2xl border border-Color-Scheme-1-Border/20 flex overflow-hidden">
                <div :class="index % 2 === 0 ? 'flex flex-row' : 'flex flex-row-reverse'" class="w-full">
                    <!-- Text Section -->
                    <div class="w-1/2 p-6 flex items-center">
                        <div class="text-Color-Scheme-1-Text text-2xl font-normal font-['Plus_Jakarta_Sans']">
                            Medium length section heading goes here
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="w-1/2 flex items-center justify-center">
                        <img class="h-80 object-cover" src="https://placehold.co/320x320" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>