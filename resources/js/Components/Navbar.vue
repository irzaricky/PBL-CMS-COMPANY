<template>
    <div class="container mx-auto bg-white p-6 font-custom">

        <div class="flex justify-between items-center mb-6">
            <img src="image/UKMI hitam.png" alt="Logo UKMI" class="h-16" />
            <AlignJustify class="text-black w-10 h-10" />
        </div>

        <nav class="flex flex-col space-y-6 pt-4">
            <div>
                <a href="/" class="block text-black hover:text-gray-500 transition duration-300 text-2xl">
                    Beranda
                </a>
            </div>
            <div>
                <a href="/" class="block text-black hover:text-gray-500 transition duration-300 text-2xl">
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

<script>
import { AlignJustify, Binoculars, Building2, Calendar, Ellipsis } from "lucide-vue-next";
import MegaMenu from "./MegaMenu.vue";

export default {
    name: "NavBar",
    components: { MegaMenu },
    data() {
        return {
            showMegaMenu: false,
            isMobileMenuOpen: false,
        };
    },
    methods: {
        toggleMegaMenu() {
            this.showMegaMenu = !this.showMegaMenu;
        },
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen;
        },
    },
};
</script>

<style scoped>
.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
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
