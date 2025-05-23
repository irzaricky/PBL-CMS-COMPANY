<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import Navbar from "@/Components/Navbar.vue";

const events = ref([]);
const loading = ref(true);

// fungsi yang dijalankan saat view di muat
onMounted(() => {
    fetchEvents();
});

// Fungsi untuk mengambil event dari API
async function fetchEvents() {
    try {
        const response = await axios.get("/api/event");
        events.value = response.data.data;
        loading.value = false;
    } catch (error) {
        console.error("Error fetching events:", error);
        loading.value = false;
    }
}

// Fungsi untuk mendapatkan URL gambar
function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

// Fungsi untuk memformat tanggal
function formatDate(date) {
    if (!date) return "";
    const eventDate = new Date(date);
    return eventDate.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}

// Fungsi untuk memformat waktu (jam)
function formatTime(date) {
    if (!date) return "";
    const eventTime = new Date(date);
    return eventTime.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

// Mengelompokkan event (hanya yang akan datang)
const upcomingEvents = computed(() => {
    return events.value;
});
</script>

<template>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-28 bg-background text-black font-custom">
        <div class="flex flex-col gap-20 overflow-hidden">
            <!-- Header -->
            <div class="flex flex-col gap-4">
                <div class="text-base font-semibold">Tagline</div>
                <div class="flex flex-col items-center gap-6 text-center">
                    <h1 class="text-5xl leading-[1.2] font-normal">Events</h1>
                    <p class="text-lg leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Suspendisse varius enim in eros elementum tristique.</p>
                </div>
            </div>

            <!-- Featured Event -->
            <div class="flex flex-col lg:flex-row gap-16">
                <div class="relative flex-1">
                    <img class="w-full h-96 object-cover rounded-2xl" src="https://placehold.co/632x420"
                        alt="Event Image" />
                    <div class="absolute left-4 top-4 bg-background rounded-2xl px-2 py-3 text-center">
                        <div class="text-sm">Sat</div>
                        <div class="text-3xl">10</div>
                        <div class="text-sm">Feb 2024</div>
                    </div>
                </div>
                <div class="flex-1 flex flex-col gap-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-wrap gap-4">
                            <span class="bg-secondary text-sm font-semibold px-3 py-1 rounded-full">Category</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h2 class="text-2xl leading-loose">Event title heading</h2>
                            <p class="text-base">Location</p>
                            <p class="text-base">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                varius enim in eros.</p>
                        </div>
                    </div>
                    <button class="bg-secondary px-6 py-2.5 rounded-full font-medium">Save my spot</button>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="flex flex-wrap items-center gap-4">
                <button class="bg-secondary px-4 py-2.5 rounded-full font-medium">View all</button>
                <button class="px-4 py-2.5 rounded-full">Category one</button>
                <button class="px-4 py-2.5 rounded-full">Category two</button>
                <button class="px-4 py-2.5 rounded-full">Category three</button>
                <button class="px-4 py-2.5 rounded-full">Category four</button>
            </div>

            <!-- Other Events List -->
            <div class="flex flex-col divide-y divide-white/20">
                <div v-for="(event, index) in 3" :key="index" class="flex flex-col lg:flex-row gap-8 py-8">
                    <div class="w-28 bg-secondary text-center px-1 py-3 rounded-2xl">
                        <div class="text-base">Fri</div>
                        <div class="text-3xl">09</div>
                        <div class="text-base">Feb 2024</div>
                    </div>
                    <div class="flex-1 flex flex-col gap-4">
                        <div class="flex items-center gap-4 flex-wrap">
                            <h3 class="text-2xl">Event title heading</h3>
                            <span class="bg-secondary px-2.5 py-1 rounded-full text-sm font-semibold">Sold out</span>
                        </div>
                        <p class="text-sm">Location</p>
                        <p class="text-base">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                            enim in eros elementum tristique.</p>
                    </div>
                    <button class="bg-secondary px-5 py-2 rounded-full text-base font-medium">Save my spot</button>
                </div>
            </div>
        </div>
    </div>
</template>
