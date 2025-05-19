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

// Format date and time
function formatDate(date) {
    if (!date) return "";
    return new Date(date).toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}
</script>

<template>
    <div
        class="w-full px-6 lg:px-16 py-20 bg-secondary text-white flex flex-col items-center overflow-hidden font-custom">
        <div class="w-full flex flex-col lg:flex-row items-center lg:justify-center lg:items-center gap-10">

            <!-- Left Side: Header dan Panah -->
            <div
                class="w-full h-full justify-center lg:w-1/4 flex flex-col items-center lg:items-start text-center lg:text-left gap-4">
                <h2 class="text-4xl lg:text-6xl font-custom font-thin lg:font-normal text-white">
                    Ikuti Event Kami
                </h2>
                <div class="relative w-full h-10 overflow-hidden hidden lg:block">
                    <img src="image/arrow-right.png" alt="Panah"
                        class="absolute top-0 left-0 w-full h-10 animate-panah-berulang" />
                </div>
            </div>

            <!-- Right Side: Event Cards -->
            <div class="w-full lg:w-3/4 flex flex-col lg:flex-row lg:flex-nowrap gap-8">
                <div v-for="event in events" :key="event.slug"
                    class="relative group rounded-2xl overflow-hidden w-full lg:w-1/3 bg-primary text-typography-dark">

                    <!-- Gambar Event -->
                    <div class="w-full flex justify-center px-3 py-3">
                        <img :src="getImageUrl(event.thumbnail_event)" alt=""
                            class="w-full h-72 lg:h-96 object-cover rounded-2xl" />
                    </div>

                    <!-- Konten Informasi Event -->
                    <div class="p-4 space-y-2">
                        <h3 class="text-lg font-semibold">
                            {{ event.nama_event }}
                        </h3>
                        <div class="text-sm text-gray-600 flex items-center gap-2">
                            <CalendarDays />
                            <span>{{ formatDate(event.waktu_start_event) }}</span>
                        </div>
                        <div class="text-sm text-gray-600 flex items-center gap-2">
                            <MapPin />
                            <span>{{ event.lokasi_event }}</span>
                        </div>

                        <!--Button Lihat Detail dan Daftar Event-->
                        <div class="flex items-center justify-between flex-wrap gap-4 pt-3">

                            <!--Button Lihat Detail-->
                                <a :href="`/event/${event.slug}`"
                                    class="inline-flex items-center gap-2 px-3 py-3 bg-secondary text-white text-sm font-semibold rounded-lg hover:bg-secondary/90 transition">
                                    Lihat Detail
                                    <ChevronsRight class="w-5 h-5 text-white" />
                                </a>

                            <!--Button Daftar Event-->
                                <a :href="`/event/${event.slug}`"
                                    class="block text-center px-3 py-3 bg-secondary text-white text-sm font-semibold rounded-lg hover:bg-secondary/90 transition">
                                    Daftar Event
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