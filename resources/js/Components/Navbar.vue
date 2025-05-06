<script setup>
import { ChevronDown } from "lucide-vue-next";
import MegaMenu from "./MegaMenu/MegaMenu.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

// Reactive variables
const profil_perusahaan = ref(null);
const showMegaMenu = ref(false);
const isMobileMenuOpen = ref(false);
const loading = ref(false);
const error = ref(null);
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

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
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

<template>
    <nav class="w-full px-16 bg-primary shadow-sm fixed top-0 left-0 z-50">
        <div class="container mx-auto flex items-center justify-between py-2">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div
                    class="h-12 w-12 flex items-center justify-center overflow-hidden"
                >
                    <img
                        :src="getImageUrl(profil_perusahaan?.logo_perusahaan)"
                        alt="Logo Perusahaan"
                        class="h-full w-full object-contain"
                    />
                </div>
                <a href="/" class="text-h4-bold text-typography-dark px-2">
                    {{ profil_perusahaan?.nama_perusahaan || "Loading..." }}
                </a>
            </div>

            <!-- Menu -->
            <div class="flex items- space-x-8">
                <a
                    href="/"
                    class="text-typography-dark hover:text-typography-hover2 transition duration-300 text-h5-bold"
                >
                    Beranda
                </a>
                <a
                    href="/portofolio"
                    class="text-typography-dark hover:text-typography-hover2 transition duration-300 text-h5-bold"
                >
                    Portofolio
                </a>
                <a
                    href="/"
                    class="text-typography-dark hover:text-typography-hover2 transition duration-300 text-h5-bold"
                >
                    Feedback
                </a>
                <div class="relative cursor-pointer" @click="toggleMegaMenu">
                    <span
                        class="text-typography-dark hover:text-typography-hover2 transition duration-300 text-h5-bold flex items-center"
                    >
                        Lainnya
                        <ChevronDown class="ml-1 w-5 h-5" />
                    </span>
                </div>
            </div>

            <div>
                <a
                    href="/login"
                    class="bg-secondary text-primary px-8 py-2 rounded-xl-figma text-h5-bold shadow hover:bg-typography-hover1 transition"
                >
                    Login
                </a>
            </div>
        </div>

        <!-- Untuk mega menu -->
        <Transition
            name="fade-slide"
            enter-active-class="transition duration-300"
            leave-active-class="transition duration-300"
        >
            <MegaMenu
                v-if="showMegaMenu"
                class="fixed left-0 top-[64px] w-screen z-40"
                @click.self="showMegaMenu = false"
            />
        </Transition>

        <!-- Login Button -->
    </nav>
    <!-- Add padding top to prevent content hidden behind navbar -->
    <div class="pt-16"></div>
</template>
