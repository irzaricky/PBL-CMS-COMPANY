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
    <div
        class="w-full px-6 lg:px-16 py-28 bg-secondary text-white flex flex-col items-center gap-20 overflow-hidden">
        <!-- Header -->
        <div class="w-full max-w-[768px] flex flex-col items-center gap-4 text-center">
            <div class="text-Color-Scheme-1-Text text-base font-semibold font-['Plus_Jakarta_Sans']">Jadi</div>
            <div class="text-Color-Scheme-1-Text text-5xl font-normal font-['Plus_Jakarta_Sans'] leading-[57.60px]">
                Mengapa memilih {{ profil_perusahaan?.nama_perusahaan || "Loading..." }}?
            </div>
            <div class="text-Color-Scheme-1-Text text-lg font-normal font-['Plus_Jakarta_Sans'] leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full max-w-screen-xl">
            <div v-for="(item, index) in 4" :key="index"
                class="bg-Color-Scheme-1-Foreground rounded-2xl border border-Color-Scheme-1-Border/20 overflow-hidden">
                <div :class="[
                    'flex w-full flex-col-reverse',
                    index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'
                ]">
                    <!-- Text Section -->
                    <div class="w-full lg:w-1/2 p-6 flex items-center">
                        <div class="text-Color-Scheme-1-Text text-2xl font-normal font-['Plus_Jakarta_Sans']">
                            Medium length section heading goes here
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2 flex items-center justify-center">
                        <img class="h-80 w-full object-cover" src="https://placehold.co/320x320" />
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>