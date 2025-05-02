<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";

const event = ref(null);
const loading = ref(true);
const error = ref(null);

const props = defineProps({
    slug: String,
});

onMounted(() => {
    fetchEvent();
});

//ambil data event melalui api
async function fetchEvent() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/events/${props.slug}`);
        event.value = response.data.data;
        loading.value = false;
    } catch (err) {
        error.value = "Event not found or an error occurred";
        loading.value = false;
        console.error("Error fetching event:", err);
    }
}

//mengambil url gambar
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

// Format time atau jam
function formatTime(date) {
    if (!date) return "";
    return new Date(date).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

// Calculate days remaining until event
const daysRemaining = computed(() => {
    const now = new Date();
    const startTime = new Date(event.value.waktu_start_event);
    const diffTime = startTime - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    return diffDays;
});
</script>

<template>
    <div class="bg-gray-50 min-h-screen py-10">
        <!-- Breadcrumb navigation -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a
                            href="/"
                            class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600"
                        >
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                ></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <a
                                href="/events"
                                class="ml-1 text-sm text-gray-500 hover:text-blue-600 md:ml-2"
                                >Events</a
                            >
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg
                                class="w-6 h-6 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <span
                                class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate max-w-[200px]"
                            >
                                {{ event?.nama_event || "Loading..." }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Loading state with shimmer effect -->
            <div
                v-if="loading"
                class="bg-white rounded-lg shadow-lg overflow-hidden"
            >
                <div class="animate-pulse">
                    <div class="h-64 bg-gray-300"></div>
                    <div class="p-6">
                        <div class="h-8 bg-gray-300 rounded w-2/3 mb-4"></div>
                        <div class="flex gap-2 mb-6">
                            <div class="h-4 bg-gray-300 rounded w-20"></div>
                            <div class="h-4 bg-gray-300 rounded w-24"></div>
                            <div class="h-4 bg-gray-300 rounded w-32"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-4 bg-gray-300 rounded"></div>
                            <div class="h-4 bg-gray-300 rounded"></div>
                            <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                            <div class="h-4 bg-gray-300 rounded"></div>
                            <div class="h-4 bg-gray-300 rounded w-4/6"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error state -->
            <div
                v-else-if="error"
                class="bg-white rounded-lg shadow-lg p-8 text-center"
            >
                <svg
                    class="w-16 h-16 text-red-500 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Event Not Found
                </h2>
                <p class="text-gray-600 mb-6">{{ error }}</p>
                <a
                    href="/events"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Events
                </a>
            </div>

            <!-- Event content -->
            <div
                v-else-if="event"
                class="bg-white rounded-lg shadow-lg overflow-hidden"
            >
                <!-- Event header with featured image -->
                <div class="relative">
                    <img
                        :src="getImageUrl(event.thumbnail_event)"
                        :alt="event.nama_event"
                        class="w-full h-[400px] object-cover object-center"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                    ></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <span
                            class="inline-block px-3 py-1 text-xs font-semibold rounded-full mb-3 bg-blue-500"
                        >
                            Upcoming Event
                        </span>
                        <h1
                            class="text-3xl sm:text-4xl font-bold text-white mb-2"
                        >
                            {{ event.nama_event }}
                        </h1>
                    </div>
                </div>

                <!-- Event details -->
                <div class="p-6 sm:p-8">
                    <!-- Countdown for upcoming events -->
                    <div
                        class="mb-8 bg-blue-50 p-4 rounded-lg border border-blue-100 text-center"
                    >
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">
                            Event Starts In
                        </h3>
                        <div class="text-3xl font-bold text-blue-700">
                            {{ daysRemaining }} days
                        </div>
                        <p class="text-sm text-blue-600 mt-1">
                            {{ formatDate(event.waktu_start_event) }} at
                            {{ formatTime(event.waktu_start_event) }}
                        </p>
                    </div>

                    <!-- Event metadata - Modified to center-align headings with content -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div
                            class="flex flex-col border-r border-gray-200 pr-4 items-center text-center"
                        >
                            <span class="text-sm text-gray-500"
                                >Date & Time</span
                            >
                            <div class="mt-1 flex items-center">
                                <div>
                                    <div class="font-medium">
                                        {{
                                            formatDate(event.waktu_start_event)
                                        }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{
                                            formatTime(event.waktu_start_event)
                                        }}
                                        -
                                        {{ formatTime(event.waktu_end_event) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col border-r border-gray-200 pr-4 items-center text-center"
                        >
                            <span class="text-sm text-gray-500">Location</span>
                            <div class="mt-1 flex items-center">
                                <div>
                                    <a
                                        :href="event.link_lokasi_event"
                                        target="_blank"
                                        class="font-medium text-blue-600 hover:underline flex items-center"
                                    >
                                        {{ event.lokasi_event }}
                                        <svg
                                            class="w-4 h-4 ml-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                            />
                                        </svg>
                                    </a>
                                    <span class="text-sm text-gray-500"
                                        >Click to view map</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="event.link_daftar_event"
                            class="flex flex-col items-center text-center"
                        >
                            <span class="text-sm text-gray-500"
                                >Registration</span
                            >
                            <a
                                :href="event.link_daftar_event"
                                target="_blank"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-green-600 text-black rounded-lg hover:bg-green-700 transition-colors"
                            >
                                Register Now
                            </a>
                        </div>
                    </div>

                    <!-- Event description -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            Event Details
                        </h2>
                        <div class="prose prose-lg max-w-none">
                            <p class="whitespace-pre-line text-justify">
                                {{ event.deskripsi_event }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back button -->
            <div class="mt-8" v-if="!loading && !error">
                <a
                    href="/events"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                    Back to Events
                </a>
            </div>
        </div>
    </div>
</template>
