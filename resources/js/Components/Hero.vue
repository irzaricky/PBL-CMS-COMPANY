<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'


const images = [
    '/images/swisnl/filament-backgrounds/curated-by-swis/01.jpg',
    '/images/swisnl/filament-backgrounds/curated-by-swis/02.jpg',
    '/images/swisnl/filament-backgrounds/curated-by-swis/03.jpg',
    '/images/swisnl/filament-backgrounds/curated-by-swis/04.jpg',
    '/images/swisnl/filament-backgrounds/curated-by-swis/05.jpg',
    '/images/swisnl/filament-backgrounds/curated-by-swis/06.jpg',
];

const currentImage = ref(0)
let intervalId

onMounted(() => {
    intervalId = setInterval(() => {
        currentImage.value = (currentImage.value + 1) % images.length
    }, 6000)
})

onBeforeUnmount(() => {
    clearInterval(intervalId)
})
</script>

<style scoped>
@keyframes zoomPan {
    0% {
        transform: scale(1) translate(0, 0);
    }

    100% {
        transform: scale(1.25) translate(20%, 20%);
    }
}

.animate-zoomPan {
    animation: zoomPan 40s ease-in-out forwards;
}
</style>
<template>
    <div class="relative w-full h-full">
        <!-- Gambar Latar -->
        <div v-for="(image, index) in images" :key="index"
            class="absolute inset-0 bg-center bg-cover transition-opacity duration-[2000ms]"
            :style="{ backgroundImage: `url(${image})` }"
            :class="[index === currentImage ? 'opacity-100 z-0 animate-zoomPan' : 'opacity-0 z-0']">
        </div>

        <!-- Overlay hitam transparan -->
        <div class="absolute inset-0 bg-black bg-opacity-50 z-10 pointer-events-none"></div>

        <!-- Konten hero -->
        <div class="relative z-20">
            <div
                class="w-full min-h-screen bg-black/50 px-6 py-16 lg:px-8 flex flex-col lg:flex-row items-start lg:items-center justify-center lg:justify-start font-custom">
                <div class="w-full lg:w-[560px] flex flex-col justify-start items-start gap-8">
                    <div class="flex flex-col gap-6">
                        <h1 class="text-white text-4xl lg:text-6xl leading-[48px] lg:leading-[67.2px] font-normal">
                            Medium length hero headline goes here
                        </h1>
                        <p class="text-white text-base lg:text-lg leading-normal lg:leading-relaxed font-normal">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros
                            elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut
                            commodo diam libero vitae erat.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <button
                            class="px-6 py-2.5 bg-white text-black font-medium text-base rounded-full shadow hover:opacity-90 transition">
                            Button
                        </button>
                        <button
                            class="px-6 py-2.5 bg-white/10 text-white font-medium text-base rounded-full border border-transparent hover:bg-white/20 transition">
                            Button
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>