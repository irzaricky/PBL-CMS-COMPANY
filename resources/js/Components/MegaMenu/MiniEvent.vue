<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { megaMenuCache } from "./MegaMenuStore";

const events = ref([]);

onMounted(() => {
    fetchEvents();
});

async function fetchEvents() {
    try {
        // Check if we have valid cached data
        if (megaMenuCache.isValid("events")) {
            events.value = megaMenuCache.events;
            return;
        }

        const response = await axios.get("/api/event/newest");
        events.value = response.data.data;

        // Cache the response
        megaMenuCache.setCache("events", response.data.data);
    } catch (error) {
        console.error("Error fetching events:", error);
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

<template>
    <div>
        <div class="font-bold text-h5-bold mb-4 text-typography-main">
            Event Terbaru
        </div>
        <div class="flex flex-col gap-3">
            <div v-for="event in events" :key="event.id_event"
                class="flex gap-3 bg-white rounded-lg shadow hover:shadow-lg transition p-3 items-center">
                <img :src="getImageUrl(event.thumbnail_event)" :alt="event.nama_event"
                    class="w-12 h-12 object-cover rounded-lg flex-shrink-0" />
                <div class="flex flex-col overflow-hidden">
                    <a :href="`/event/${event.slug}`"
                        class="text-h6-bold text-typography-main truncate hover:underline">
                        {{ event.nama_event }}
                    </a>
                    <span class="text-xs text-typography-dark line-clamp-2 mt-1">{{ event.deskripsi_event }}</span>
                </div>
            </div>

            <!-- Loading skeleton jika belum ada data -->
            <template v-if="!events.length">
                <div v-for="n in 1" :key="n"
                    class="flex gap-3 bg-white rounded-lg shadow transition p-3 items-center animate-pulse">
                    <div class="w-12 h-12 bg-gray-300 rounded-lg flex-shrink-0"></div>
                    <div class="flex flex-col gap-2 overflow-hidden w-full">
                        <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-full"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>