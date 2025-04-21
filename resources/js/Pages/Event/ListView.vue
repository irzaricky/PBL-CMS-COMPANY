<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";

const events = ref([]);
const loading = ref(true);

onMounted(() => {
    fetchEvents();
});

async function fetchEvents() {
    try {
        const response = await axios.get("/api/events");
        events.value = response.data.data;
        loading.value = false;
    } catch (error) {
        console.error("Error fetching events:", error);
        loading.value = false;
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.jpeg";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.jpeg";
    }

    return `/storage/${image}`;
}

function formatDate(date) {
    if (!date) return "";
    const eventDate = new Date(date);
    return eventDate.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}

function formatTime(date) {
    if (!date) return "";
    const eventTime = new Date(date);
    return eventTime.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

// Group events by upcoming and past
const groupedEvents = computed(() => {
    const now = new Date();
    const upcoming = [];
    const past = [];

    events.value.forEach((event) => {
        const eventDate = new Date(event.waktu_start_event);
        if (eventDate > now) {
            upcoming.push(event);
        } else {
            past.push(event);
        }
    });

    return { upcoming, past };
});
</script>

<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-blue-900 mb-8">Events</h1>

        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div
                class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"
            ></div>
        </div>

        <div v-else>
            <!-- Upcoming Events Section -->
            <div class="mb-12">
                <h2
                    class="text-2xl font-semibold text-gray-800 mb-6 flex items-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2 text-blue-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    Upcoming Events
                </h2>

                <div
                    v-if="groupedEvents.upcoming.length === 0"
                    class="text-gray-500 text-center py-10 bg-gray-50 rounded-lg"
                >
                    No upcoming events scheduled
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="event in groupedEvents.upcoming"
                        :key="event.id_event"
                        class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100"
                    >
                        <!-- Event image -->
                        <div class="h-48 overflow-hidden">
                            <img
                                :src="getImageUrl(event.thumbnail_event)"
                                :alt="event.nama_event"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <!-- Event tag -->
                        <div class="px-6 pt-4">
                            <span
                                class="inline-block bg-blue-500 text-white text-xs px-3 py-1 rounded-full uppercase font-semibold tracking-wide"
                            >
                                Upcoming
                            </span>
                        </div>

                        <!-- Event content -->
                        <div class="p-6">
                            <h3
                                class="text-xl font-semibold text-gray-800 mb-3 line-clamp-2"
                            >
                                {{ event.nama_event }}
                            </h3>

                            <div
                                class="flex items-center mb-4 text-sm text-gray-600"
                            >
                                <span class="flex items-center">
                                    <svg
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                    {{ event.lokasi_event }}
                                </span>
                            </div>

                            <div
                                class="mb-4 text-sm text-gray-500 line-clamp-3"
                            >
                                {{ event.deskripsi_event }}
                            </div>

                            <div class="flex flex-col space-y-2 mb-5">
                                <div class="flex items-center text-sm">
                                    <svg
                                        class="w-4 h-4 mr-1 text-blue-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                    <span class="text-gray-700">{{
                                        formatDate(event.waktu_start_event)
                                    }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg
                                        class="w-4 h-4 mr-1 text-blue-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <span class="text-gray-700"
                                        >{{
                                            formatTime(event.waktu_start_event)
                                        }}
                                        -
                                        {{
                                            formatTime(event.waktu_end_event)
                                        }}</span
                                    >
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <a
                                    :href="`/events/${event.slug}`"
                                    class="inline-block px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded hover:bg-blue-600 transition-colors duration-300"
                                >
                                    View Details
                                </a>
                                <a
                                    v-if="event.link_daftar_event"
                                    :href="event.link_daftar_event"
                                    target="_blank"
                                    class="inline-block px-4 py-2 bg-green-500 text-white font-medium text-sm rounded hover:bg-green-600 transition-colors duration-300"
                                >
                                    Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Past Events Section -->
            <div v-if="groupedEvents.past.length > 0">
                <h2
                    class="text-2xl font-semibold text-gray-800 mb-6 flex items-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2 text-gray-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    Past Events
                </h2>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="event in groupedEvents.past"
                        :key="event.id_event"
                        class="bg-white rounded-lg overflow-hidden shadow border border-gray-100 opacity-75 hover:opacity-100 transition-opacity duration-300"
                    >
                        <!-- Event image with grayscale effect for past events -->
                        <div class="h-48 overflow-hidden filter grayscale">
                            <img
                                :src="getImageUrl(event.thumbnail_event)"
                                :alt="event.nama_event"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <!-- Event tag -->
                        <div class="px-6 pt-4">
                            <span
                                class="inline-block bg-gray-500 text-white text-xs px-3 py-1 rounded-full uppercase font-semibold tracking-wide"
                            >
                                Past
                            </span>
                        </div>

                        <!-- Event content -->
                        <div class="p-6">
                            <h3
                                class="text-xl font-semibold text-gray-700 mb-3 line-clamp-2"
                            >
                                {{ event.nama_event }}
                            </h3>

                            <div
                                class="flex items-center mb-4 text-sm text-gray-500"
                            >
                                <span class="flex items-center">
                                    <svg
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                    {{ event.lokasi_event }}
                                </span>
                            </div>

                            <div
                                class="mb-4 text-sm text-gray-500 line-clamp-3"
                            >
                                {{ event.deskripsi_event }}
                            </div>

                            <div class="flex flex-col space-y-2 mb-5">
                                <div class="flex items-center text-sm">
                                    <svg
                                        class="w-4 h-4 mr-1 text-gray-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                    <span>{{
                                        formatDate(event.waktu_start_event)
                                    }}</span>
                                </div>
                            </div>

                            <a
                                :href="`/events/${event.slug}`"
                                class="inline-block px-4 py-2 bg-gray-200 text-gray-700 font-medium text-sm rounded hover:bg-gray-300 transition-colors duration-300"
                            >
                                View Summary
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No events message -->
            <div
                v-if="!loading && events.length === 0"
                class="text-center py-12"
            >
                <p class="text-gray-500 text-lg">No events found</p>
            </div>
        </div>
    </div>
</template>
