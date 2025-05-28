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
            const response = await axios.get(`/api/profil-perusahaan/navbar`);
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
    <div class="w-full px-6 lg:px-16 py-28 bg-secondary text-white flex flex-col items-center gap-20 overflow-hidden">
        <!-- Header -->
        <div class="w-full max-w-[864px] flex flex-col items-center gap-4 text-center">
            <div class="text-white text-base font-semibold font-custom">Jadi</div>
            <div class="text-white text-5xl font-bold font-custom leading-[57.60px]">
                Kenapa Memilih Kami?
            </div>
            <div class="text-white text-lg font-normal font-custom leading-relaxed">
                Karena perusahaan {{ profil_perusahaan?.nama_perusahaan || "Loading..." }} kami bukan cuma informatif, tapi
                juga nyaman dan seru untuk dijelajahi! 
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full max-w-screen-xl">
            <div v-for="(item, index) in 4" :key="index"
                class="bg-Color-Scheme-1-Foreground rounded-2xl border border-Color-Scheme-1-Border/20 overflow-hidden">
                <div :class="[
                    'flex w-full flex-col-reverse',
                    index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'
                ]">
                    <!-- Text Section -->
                    <div class="w-full lg:w-7/12 p-4 flex items-center">
                        <div class="flex flex-col gap-2">
                            <!-- Judul Text Section -->
                            <div class="text-white text-2xl font-bold font-custom mb-4">
                                Medium length section heading goes here
                            </div>

                            <!-- Penjelasan Text Section -->
                            <div class="text-white text-base font-medium font-custom">
                                Medium length section heading goes here. Lorem ipsum dolor sit amet.
                            </div>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="w-full lg:w-5/12 flex items-center justify-center">
                        <img class="h-80 w-full object-cover" src="https://placehold.co/320x320" />
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>