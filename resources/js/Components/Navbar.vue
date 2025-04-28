<template>
    <nav class="bg-[#F2F2F2] border-b border-gray-300 w-full font-custom">
        <div
            class="flex justify-between items-center px-16 py-4 w-full text-base min-h-[72px] max-md:px-6 max-sm:px-4"
        >
            <!-- Left side (Logo + Links) -->
            <div class="flex items-center gap-8 max-md:gap-4">
                <img
                    src="image/UKMI hitam.png"
                    alt="Logo"
                    class="h-14 w-auto object-contain"
                />

                <!-- Desktop Links -->
                <div
                    class="hidden justify-between md:flex gap-8 items-center font-bold text-black"
                >
                    <a
                        href="/"
                        class="relative font-bold text-black after:block after:content-[''] after:absolute after:h-[2px] after:bg-indigo-900 after:w-0 after:left-0 after:bottom-0 hover:after:w-full after:transition-all after:duration-300"
                    >
                        Home
                    </a>
                    <a
                        href="/artikel"
                        class="relative font-bold text-black after:block after:content-[''] after:absolute after:h-[2px] after:bg-indigo-900 after:w-0 after:left-0 after:bottom-0 hover:after:w-full after:transition-all after:duration-300"
                    >
                        Artikel
                    </a>
                    <a
                        href="/event"
                        class="relative font-bold text-black after:block after:content-[''] after:absolute after:h-[2px] after:bg-indigo-900 after:w-0 after:left-0 after:bottom-0 hover:after:w-full after:transition-all after:duration-300"
                    >
                        Event
                    </a>
                    <div
                        class="flex items-center gap-1 cursor-pointer"
                        @click="toggleMegaMenu"
                    >
                        <a
                            href="/"
                            class="relative font-bold text-black after:block after:content-[''] after:absolute after:h-[2px] after:bg-indigo-900 after:w-0 after:left-0 after:bottom-0 hover:after:w-full after:transition-all after:duration-300"
                        >
                            Lainnya
                        </a>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Right side (Buttons) -->
            <div class="hidden md:flex gap-4 font-medium">
                <button
                    class="px-5 py-2 text-black bg-black/10 rounded-full hover:bg-black/20 transition"
                >
                    Lihat lebih banyak
                </button>
                <button
                    class="px-5 py-2 text-white bg-indigo-900 rounded-full hover:bg-indigo-800 transition"
                >
                    Login
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <button
                class="md:hidden flex items-center text-black"
                @click="toggleMobileMenu"
            >
                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div
            v-if="isMobileMenuOpen"
            class="flex flex-col gap-6 px-6 py-4 bg-[#F2F2F2] md:hidden"
        >
            <a
                href="/"
                class="font-bold text-black hover:text-indigo-900 transition link-anim"
                >Home</a
            >
            <a
                href="/artikel"
                class="font-bold text-black hover:text-indigo-900 transition"
                >Artikel</a
            >
            <a
                href="/event"
                class="font-bold text-black hover:text-indigo-900 transition"
                >Event</a
            >
            <div
                @click="toggleMegaMenu"
                class="flex items-center gap-1 cursor-pointer"
            >
                <a
                    href="#"
                    class="font-bold text-black hover:text-indigo-900 transition"
                    >Lainnya</a
                >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </div>

            <div class="flex gap-4 mt-4">
                <button
                    class="px-5 py-2 text-black bg-black/10 rounded-full w-full hover:bg-black/20 transition"
                >
                    Lihat lebih banyak lagi
                </button>
                <button
                    class="px-5 py-2 text-white bg-indigo-900 rounded-full w-full hover:bg-indigo-800 transition"
                >
                    Login
                </button>
            </div>
        </div>

        <!-- Mega Menu -->
        <Transition
            name="fade-slide"
            enter-active-class="transition duration-300"
            leave-active-class="transition duration-300"
        >
            <MegaMenu v-if="showMegaMenu" />
        </Transition>
    </nav>
</template>

<script>
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
