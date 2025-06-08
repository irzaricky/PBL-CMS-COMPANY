<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { ref, onMounted } from "vue";
import { MapPin, Clock } from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";

const events = ref([]);
const featuredEvent = ref(null);
const loading = ref(true);

onMounted(() => {
    fetchEvents();
});

// Ambil semua event
async function fetchEvents() {
    try {
        const [all, newest] = await Promise.all([
            axios.get("/api/event"),
            axios.get("/api/event/newest"),
        ]);

        events.value = all.data.data;
        featuredEvent.value = newest.data.data;

        // Filter featured dari daftar lainnya
        events.value = events.value.filter(e => e.id_event !== featuredEvent.value?.id_event);

    } catch (error) {
        console.error("Error fetching events:", error);
    } finally {
        loading.value = false;
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}

function formatDate(date) {
    if (!date) return "";
    const d = new Date(date);
    return d.toLocaleDateString("id-ID", { year: "numeric", month: "long", day: "numeric" });
}

function getDay(date) {
    return new Date(date).toLocaleDateString("id-ID", { weekday: "short" });
}

function getDate(date) {
    return new Date(date).getDate();
}

function getMonthYear(date) {
    return new Date(date).toLocaleDateString("id-ID", { month: "short", year: "numeric" });
}

function formatTimeRange(start, end) {
    const startTime = new Date(start);
    const endTime = new Date(end);

    const startStr = startTime.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });

    const endStr = endTime.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });

    return `${startStr} - ${endStr}`;
}
</script>

<template>
    <AppLayout>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-28 bg-background text-black font-custom">
            <div class="flex flex-col gap-20 overflow-hidden">
                <!-- Header -->
                <div class="flex flex-col gap-4">
                    <div class="text-base font-semibold">Datangi</div>
                    <div class="flex flex-col gap-6 text-left">
                        <h1 class="text-5xl leading-[1.2] font-normal">Event kami!</h1>
                        <p class="text-lg leading-relaxed text-gray-700">Jangan sampai ketinggalan event seru dari kami!
                        </p>
                    </div>
                </div>

                <!-- Featured Event -->
                <div v-if="featuredEvent" class="flex flex-col gap-8">
                    <h2 class="text-4xl font-normal">Event Terbaru</h2>
                    <div class="flex flex-col lg:flex-row gap-12">
                        <div class="relative flex-1">
                            <img class="w-full h-96 object-cover rounded-2xl"
                                :src="getImageUrl(featuredEvent.thumbnail_event)" alt="Event Image" />
                            <div
                                class="absolute left-4 top-4 bg-white text-black rounded-2xl px-3 py-2 text-center shadow-lg">
                                <div class="text-sm">{{ getDay(featuredEvent.waktu_start_event) }}</div>
                                <div class="text-3xl font-semibold">{{ getDate(featuredEvent.waktu_start_event) }}</div>
                                <div class="text-sm">{{ getMonthYear(featuredEvent.waktu_start_event) }}</div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col gap-6">
                            <div class="flex flex-col gap-2">
                                <h3 class="text-3xl font-normal">{{ featuredEvent.nama_event }}</h3>
                                <p class="text-base text-gray-700 flex items-center gap-2">
                                    <MapPin class="w-5 h-5 text-secondary" />
                                    {{ featuredEvent.lokasi_event }}
                                </p>
                                <p class="text-base text-gray-700 flex items-center gap-2">
                                    <Clock class="w-5 h-5 text-secondary" />
                                    {{ formatTimeRange(featuredEvent.waktu_start_event, featuredEvent.waktu_end_event)
                                    }}
                                </p>
                                <p class="text-base text-gray-600">{{ featuredEvent.deskripsi_event }}</p>
                            </div>
                            <a :href="`/event/${featuredEvent.slug}`"
                                class="bg-secondary text-white px-6 py-2.5 rounded-full font-medium text-center w-max hover:bg-black transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Other Events -->
                <div v-if="events.length" class="flex flex-col gap-10">
                    <h2 class="text-4xl font-normal">Event Lainnya</h2>
                    <div class="flex flex-col divide-y divide-gray-200">
                        <div v-for="(event, index) in events" :key="index" class="flex flex-col lg:flex-row gap-8 py-8">
                            <div class="relative w-full h-72 lg:w-64 lg:h-48 flex-shrink-0">
                                <img class="w-full h-full object-cover rounded-2xl"
                                    :src="getImageUrl(event.thumbnail_event)" alt="Event Thumbnail" />
                                <div
                                    class="absolute left-4 top-4 bg-white text-black rounded-2xl px-2 py-3 text-center shadow-md">
                                    <div class="text-xs">{{ getDay(event.waktu_start_event) }}</div>
                                    <div class="text-2xl font-bold">{{ getDate(event.waktu_start_event) }}</div>
                                    <div class="text-xs">{{ getMonthYear(event.waktu_start_event) }}</div>
                                </div>
                            </div>
                            <div class="flex-1 flex flex-col gap-4">
                                <h3 class="text-2xl font-semibold">{{ event.nama_event }}</h3>
                                <p class="text-sm text-gray-700 flex items-center gap-1.5">
                                    <MapPin class="w-4 h-4 text-secondary" />
                                    {{ event.lokasi_event }}
                                </p>
                                <p class="text-sm text-gray-700 flex items-center gap-1.5">
                                    <Clock class="w-4 h-4 text-secondary" />
                                    {{ formatTimeRange(event.waktu_start_event, event.waktu_end_event) }} WIB
                                </p>
                                <p class="text-base text-gray-600">{{ event.deskripsi_event }}</p>
                            </div>
                            <div class="flex lg:items-start">
                                <Link :href="`/event/${event.slug}`"
                                    class="bg-secondary text-white px-5 py-2 h-10 rounded-full text-base font-medium hover:bg-black hover:text-white transition">
                                Lihat Detail
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
