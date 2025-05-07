<script setup>
import { ChevronDown, Menu } from "lucide-vue-next";
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

const isMobile = ref(false);

onMounted(() => {
    updateIsMobile();
    window.addEventListener("resize", updateIsMobile);
});

function updateIsMobile() {
    isMobile.value = window.innerWidth < 1024; // Tailwind `lg`
}

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
    <!-- Navbar -->
    <nav class="w-full px-6 py-2 lg:px-16 bg-primary shadow-sm fixed top-0 left-0 z-50 font-custom">
        <div class="container mx-auto flex items-center justify-between py-2">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div class="h-12 w-12 flex items-center justify-center overflow-hidden">
                    <img :src="getImageUrl(profil_perusahaan?.logo_perusahaan)" alt="Logo Perusahaan"
                        class="h-full w-full object-contain" />
                </div>
                <a href="/" class="text-h4-bold text-typography-dark px-2">
                    {{ profil_perusahaan?.nama_perusahaan || "Loading..." }}
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="/" class="text-typography-dark hover:text-typography-hover2 transition text-lg">Beranda</a>
                <a href="/portofolio"
                    class="text-typography-dark hover:text-typography-hover2 transition text-lg">Portofolio</a>
                <a href="/" class="text-typography-dark hover:text-typography-hover2 transition text-lg">Feedback</a>
                <div class="relative cursor-pointer" @click="toggleMegaMenu">
                    <span
                        class="text-typography-dark hover:text-typography-hover2 transition text-lg flex items-center">
                        Lainnya
                        <ChevronDown class="w-4" />
                    </span>
                </div>
            </div>

            <!-- Desktop Login -->
            <div class="hidden lg:block">
                <a href="/login"
                    class="bg-secondary text-primary px-8 py-2 rounded-xl-figma shadow hover:bg-typography-hover1 transition">
                    Login
                </a>
            </div>

            <!-- Burger icon -->
            <div class="lg:hidden flex items-center">
                <Menu class="w-7 h-7 text-black cursor-pointer" @click="toggleMobileMenu" />
            </div>
        </div>

        <!-- Desktop MegaMenu (overlay) -->
        <Transition name="fade-slide">
            <MegaMenu v-if="showMegaMenu && !isMobile" class="fixed left-0 top-[64px] w-screen z-40"
                @click.self="showMegaMenu = false" />
        </Transition>
    </nav>

    <!-- Mobile Menu Dropdown -->
    <Transition name="fade-slide">
        <div v-if="isMobileMenuOpen"
            class="lg:hidden fixed top-[64px] left-0 w-full bg-white px-6 pt-8 pb-8 flex flex-col space-y-4 shadow z-40 font-custom text-black max-h-[calc(100vh-64px)] overflow-y-auto">
            <a href="/" class="text-2xl py-1">Beranda</a>
            <a href="/portofolio" class="text-2xl py-1">Portofolio</a>
            <a href="/" class="text-2xl py-1">Feedback</a>

            <!-- Tombol Lainnya -->
            <div class="text-2xl py-1 flex justify-between items-center cursor-pointer" @click="toggleMegaMenu">
                <span>Lainnya</span>
                <ChevronDown class="w-5 h-5" />
            </div>

            <!-- Mobile MegaMenu (inline inside dropdown) -->
            <div v-if="showMegaMenu && isMobile" class="pt-4">
                <MegaMenu class="w-full bg-primary rounded-xl p-4" />
            </div>

            <a href="/login"
                class="mt-4 bg-secondary text-primary px-6 py-2 rounded-xl-figma text-center shadow hover:bg-typography-hover1 transition">
                Login
            </a>
        </div>
    </Transition>


    <!-- Spacer -->
    <div class="pt-16"></div>
</template>
