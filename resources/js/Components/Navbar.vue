<template>
    <div class="container mx-auto bg-white p-6 font-custom">

        <div class="flex justify-between items-center mb-6">
            <!-- Kiri: Logo + Tulisan UKMI -->
            <div class="flex items-center space-x-2">
                <div class="h-16 w-16 flex items-center justify-center overflow-hidden">
                    <img :src="getImageUrl(profil_perusahaan?.logo_perusahaan)" alt="Logo Perusahaan"
                        class="h-full w-full object-contain" />
                </div>

                <span class="text-2xl font-bold text-black px-4">{{ profil_perusahaan?.nama_perusahaan ||
                    "Loading..." }}</span>
            </div>

            <!-- Kanan: Ikon -->
            <AlignJustify class="text-black w-10 h-10" />
        </div>

        <nav class="flex flex-col space-y-6 pt-4">
            <div>
                <a href="/" class="block text-black hover:text-gray-500 transition duration-300 text-2xl">
                    Beranda
                </a>
            </div>
            <div>
                <a href="/portofolio" class="block text-black hover:text-gray-500 transition duration-300 text-2xl">
                    Portofolio
                </a>
            </div>
            <div>
                <a href="/" class="block text-black hover:text-gray-500 transition duration-300 text-2xl">
                    Feedback
                </a>
            </div>
            <div class="relative cursor-pointer" @click="toggleMegaMenu">
                <a href="#" class="block text-black hover:text-gray-500 transition duration-300 text-2xl pr-10">
                    Lainnya
                </a>
                <ChevronDown class="absolute right-0 top-1/2 transform -translate-y-1/2 text-black" />
            </div>


            <Transition name="fade-slide" enter-active-class="transition duration-300"
                leave-active-class="transition duration-300">
                <MegaMenu v-if="showMegaMenu" />
            </Transition>
        </nav>
    </div>

</template>

<script setup>
import { AlignJustify, Binoculars, Building2, Calendar, ChevronDown, Ellipsis } from "lucide-vue-next";
import MegaMenu from "./MegaMenu.vue";
import { ref, onMounted, computed } from "vue";
import axios from 'axios';

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}
// Define props
const props = defineProps({
    slug: {
        type: String,
        required: true
    }
});

// Reactive variables
const profil_perusahaan = ref(null);
const showMegaMenu = ref(false);
const isMobileMenuOpen = ref(false);
const loading = ref(false);
const error = ref(null);

// Methods
const toggleMegaMenu = () => {
    showMegaMenu.value = !showMegaMenu.value;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

onMounted(() => {
    fetchProfilPerusahaan();
});

//ambil data event melalui api
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
</script>

<style scoped>
.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: opacity 0.3s, transform 0.3s;
}

.fade-slide-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
