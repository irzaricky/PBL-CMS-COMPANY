<template>
    <div class="relative h-[600px] w-full overflow-hidden font-custom">
        <div v-for="(image, index) in images" :key="index"
            class="absolute inset-0 bg-center bg-cover transition-opacity duration-[2000ms]"
            :style="{ backgroundImage: `url(${image})` }" :class="[
                index === currentImage ? 'opacity-100 z-0 animate-zoomPan' : 'opacity-0 z-0'
            ]"></div>

        <div
            class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center text-white text-center px-4 z-10">
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-4 sm:mb-6">Judul Utama</h1>
            <p class="text-lg sm:text-xl mb-6 sm:mb-8">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel
                quidem eum possimus? Totam quia soluta incidunt</p>
            <div class="flex sm:flex-row gap-6 sm:gap-8">
                <button class="bg-white text-black px-6 py-3 rounded-lg text-lg sm:text-xl"> <a
                        href="/admin">Admin</a></button>
                <button class="bg-transparent border border-white px-6 py-3 rounded-lg text-lg sm:text-xl"><a
                        href="/admin/register">Register Admin Test</a></button>
            </div>
        </div>
    </div>
</template>

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