<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;

const images = [
    "/image/21.webp",
    "/image/22.webp",
    "/image/23.webp",
    "/image/24.webp",
    "/image/25.webp",
    "/image/26.webp",
];

const currentImage = ref(0);
let intervalId;

onMounted(() => {
    intervalId = setInterval(() => {
        currentImage.value = (currentImage.value + 1) % images.length;
    }, 6000);
});

onBeforeUnmount(() => {
    clearInterval(intervalId);
});
</script>

<style scoped>
@keyframes zoomPan {
    0% {
        transform: scale(1) translate(0, 0);
    }

    100% {
        transform: scale(3.25) translate(0, 0);
    }
}

.background-image {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-position: center;
    background-size: cover;
    transition: opacity 2s ease-in-out;
    z-index: 0;
    /* Ensure it's behind the content */
    overflow: hidden;
    /* Prevent image from overflowing */
}

.animate-zoomPan {
    animation: zoomPan 40s ease-in-out forwards;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10;
    /* Ensure it's above the background */
}

.content {
    position: relative;
    z-index: 20;
    /* Ensure it's above the overlay */
}
</style>

<template>
    <div class="relative w-full h-full overflow-hidden">
        <!-- Gambar Latar -->
        <div
            v-for="(image, index) in images"
            :key="index"
            class="background-image transition-opacity"
            :style="{
                backgroundImage: `url(${image})`,
                opacity: index === currentImage ? 1 : 0,
            }"
            :class="{ 'animate-zoomPan': index === currentImage }"
        ></div>

        <!-- Overlay hitam transparan -->
        <div
            class="absolute inset-0 bg-black bg-opacity-50 z-10 pointer-events-none"
        ></div>

        <!-- Konten hero -->
        <div class="relative z-20">
            <div
                class="w-full min-h-screen bg-black/50 px-6 py-16 lg:px-8 flex flex-col lg:flex-row items-start lg:items-center justify-center lg:justify-start font-custom"
            >
                <div
                    class="w-full lg:w-[560px] flex flex-col justify-start items-start gap-8"
                >
                    <div class="flex flex-col gap-6">
                        <h1
                            class="text-white text-4xl lg:text-6xl leading-[48px] lg:leading-[67.2px] font-normal"
                        >
                            Selamat datang {{ user?.name ?? "pengunjung" }}!
                        </h1>
                        <p
                            class="text-white text-base lg:text-lg leading-normal lg:leading-relaxed font-normal"
                        >
                            Temukan informasi terkini seputar produk, event,
                            lowongan kerja, hingga kisah menarik lewat artikel
                            dan galeri kami.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <a
                            href="/produk"
                            class="px-6 py-2.5 bg-white text-black font-medium text-base rounded-full shadow hover:opacity-90 transition inline-block text-center"
                        >
                            Lihat semua produk kami
                        </a>
                        <a
                            href="/event"
                            class="px-6 py-2.5 bg-white/10 text-white font-medium text-base rounded-full border border-transparent hover:bg-white/20 transition inline-block text-center"
                        >
                            Event terdekat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
