<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const events = ref([]);

onMounted(() => {
    fetchEvents();
});

async function fetchEvents() {
    try {
        const response = await axios.get("/api/event/newest");
        events.value = response.data.data;
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
        <div class="font-bold text-h6-bold mb-4 text-typography-main">
            Event Terbaru
        </div>
        <div class="flex flex-col gap-3">
            <div
                v-for="event in events"
                :key="event.id_event"
                class="flex gap-3 bg-white rounded-lg shadow hover:shadow-lg transition p-3 items-center"
            >
                <img
                    :src="getImageUrl(event.thumbnail_event)"
                    :alt="event.nama_event"
                    class="w-12 h-12 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex flex-col overflow-hidden">
                    <a
                        :href="`/event/${event.slug}`"
                        class="text-h6-bold text-typography-main truncate hover:underline"
                    >
                        {{ event.nama_event }}
                    </a>
                    <span
                        class="text-xs text-typography-dark line-clamp-2 mt-1"
                        >{{ event.deskripsi_event }}</span
                    >
                </div>
            </div>
            <div v-if="!events.length" class="text-typography-dark text-xs">
                Tidak ada event terbaru.
            </div>
        </div>
    </div>
</template>
