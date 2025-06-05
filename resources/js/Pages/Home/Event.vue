<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const events = ref([]);
const loading = ref(false);
const error = ref(null);

onMounted(() => {
    fetchEvent();
});

async function fetchEvent() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/event`);
        const allEvent = response.data.data;
        events.value = getRandomEvent(allEvent, 3);
    } catch (err) {
        error.value = "Event not found or an error occurred";
        console.error("Error fetching event:", err);
    } finally {
        loading.value = false;
    }
}

function getRandomEvent(array, count) {
    const shuffled = [...array].sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}
</script>

<template>
    <div
        class="w-full px-6 lg:px-16 py-28 bg-secondary text-white flex flex-col items-center overflow-hidden font-custom">
        <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row items-center lg:items-start gap-10">

            <!-- Left Side: Header dan Panah -->
            <div class="w-full lg:w-1/4 flex flex-col items-center lg:items-start text-center lg:text-left gap-4">
                <div class="text-Color-Scheme-1-Text text-base font-semibold">
                    Coba lihat!
                </div>
                <h2 class="text-4xl lg:text-6xl font-custom font-thin lg:font-normal text-white">
                    Event Akan Datang
                </h2>
                <div class="relative w-full h-10 overflow-hidden hidden lg:block">
                    <img src="image/arrow-right.png" alt="Panah"
                        class="absolute top-0 left-0 w-full h-10 animate-panah-berulang" />
                </div>
            </div>

            <!-- Right Side: Event Cards -->
            <div class="w-full lg:w-3/4 flex flex-col lg:flex-row lg:flex-nowrap gap-8">
                <div v-for="event in events" :key="event.slug"
                    class="relative group rounded-2xl overflow-hidden w-full lg:w-1/3">

                    <!-- Gambar Event -->
                    <img :src="getImageUrl(event.thumbnail_event)" alt=""
                        class="w-full h-72 lg:h-96 object-cover rounded-2xl" />

                    <!-- Overlay muncul di hover di bagian bawah -->
                    <div
                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent px-4 py-6 opacity-0 group-hover:opacity-100 transition duration-300">

                        <h3 class="text-white text-lg font-semibold mb-3 text-center">
                            {{ event.nama_event }}
                        </h3>

                        <div class="flex justify-center">
                            <a :href="`/event/${event.slug}`"
                                class="px-5 py-2 bg-white/10 text-white text-sm rounded-full hover:bg-white/20 transition">
                                Lihat Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style>
@keyframes panah-berulang {
    0% {
        opacity: 1;
        transform: translateX(-300px);
    }

    15% {
        opacity: 1;
        transform: translateX(0);
    }

    25% {
        opacity: 1;
        transform: translateX(0);
    }

    85% {
        opacity: 1;
        transform: translateX(0px);
    }

    100% {
        opacity: 1;
        transform: translateX(500px);
    }
}

.animate-panah-berulang {
    animation: panah-berulang 3s ease-in-out infinite;
}
</style>