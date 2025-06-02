<script setup>
import axios from "axios";
import { ref, onMounted, computed, watch, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Home,
    Calendar,
    MapPin,
    ChevronRight,
    Pencil,
    User,
    CheckCircle,
    Copy,
} from "lucide-vue-next";
import AppLayout from "@/Layouts/AppLayout.vue";
import CopyLink from "@/Components/Modal/CopyLink.vue";
import EventTimer from "@/Pages/Event/EventTimer.vue";

const event = ref(null);
const loading = ref(true);
const error = ref(null);
const registering = ref(false);
const page = usePage();
const showCopyModal = ref(false);

const props = defineProps({
    slug: String,
});

onMounted(() => {
    fetchEvent();
});

async function fetchEvent() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/event/${props.slug}`);
        event.value = response.data.data;
    } catch (err) {
        error.value = "Event not found or an error occurred";
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
    return new Date(date).toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}

function formatTime(date) {
    if (!date) return "";
    return new Date(date).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

const timeRemaining = ref({
    total: 0,
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
});

let countdownInterval;

watch(event, () => {
    if (event.value) {
        clearInterval(countdownInterval);
        startCountdown(new Date(event.value.waktu_start_event));
    }
});

function startCountdown(targetDate) {
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance <= 0) {
            clearInterval(countdownInterval);
            timeRemaining.value = {
                total: 0,
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
            };
            return;
        }

        timeRemaining.value = {
            total: distance,
            days: Math.floor(distance / (1000 * 60 * 60 * 24)),
            hours: Math.floor(
                (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
            ),
            minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
            seconds: Math.floor((distance % (1000 * 60)) / 1000),
        };
    }

    updateCountdown();
    countdownInterval = setInterval(updateCountdown, 1000);
}

onUnmounted(() => {
    clearInterval(countdownInterval);
});

// Check if user is authenticated
const isAuthenticated = computed(() => {
    return page.props.auth && page.props.auth.user;
});

// Register for event
async function registerForEvent() {
    if (!isAuthenticated.value) {
        alert("Silakan login terlebih dahulu untuk mendaftar event.");
        return;
    }

    try {
        registering.value = true;
        const response = await axios.post(`/api/event/${props.slug}/register`);

        if (response.data.status === "success") {
            // Update event data
            event.value.jumlah_pendaftar = response.data.jumlah_pendaftar;
            event.value.is_registered = response.data.is_registered;
            alert(
                "Berhasil mendaftar event! Anda akan mendapat pengingat melalui email dan notifikasi."
            );
        }
    } catch (err) {
        if (err.response && err.response.data) {
            alert(err.response.data.message || "Gagal mendaftar event");
        } else {
            alert("Terjadi kesalahan saat mendaftar event");
        }
    } finally {
        registering.value = false;
    }
}

// Unregister from event
async function unregisterFromEvent() {
    if (!isAuthenticated.value) {
        return;
    }

    if (
        !confirm("Apakah Anda yakin ingin membatalkan pendaftaran event ini?")
    ) {
        return;
    }

    try {
        registering.value = true;
        const response = await axios.delete(
            `/api/event/${props.slug}/register`
        );

        if (response.data.status === "success") {
            // Update event data
            event.value.jumlah_pendaftar = response.data.jumlah_pendaftar;
            event.value.is_registered = response.data.is_registered;
            alert("Pendaftaran event berhasil dibatalkan.");
        }
    } catch (err) {
        if (err.response && err.response.data) {
            alert(err.response.data.message || "Gagal membatalkan pendaftaran");
        } else {
            alert("Terjadi kesalahan saat membatalkan pendaftaran");
        }
    } finally {
        registering.value = false;
    }
}

// Copy event link
async function copyEventLink() {
    const url = window.location.href;
    try {
        if (navigator.clipboard && typeof navigator.clipboard.writeText === "function") {
            await navigator.clipboard.writeText(url);
            showCopyModal.value = true;
        } else {
            throw new Error("Clipboard API not available");
        }
    } catch (err) {
        console.warn("Clipboard write failed, using fallback:", err);
        fallbackCopy(url);
    }
}

function fallbackCopy(text) {
    const ta = document.createElement("textarea");
    ta.value = text;
    ta.style.position = "fixed";
    ta.style.opacity = "0";
    document.body.appendChild(ta);
    ta.select();
    document.execCommand("copy");
    document.body.removeChild(ta);
    showCopyModal.value = true;
}

function closeCopyModal() {
    showCopyModal.value = false;
}
</script>

<template>
    <AppLayout>
        <div
            class="bg-white min-h-screen py-6 px-4 md:px-8 text-black font-custom"
        >
            <div class="max-w-6xl mx-auto px-2 sm:px-4 space-y-10">
                <!-- Breadcrumb -->
                <div class="mt-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-3"
                        >
                            <li>
                                <Link
                                    href="/"
                                    class="inline-flex items-center text-sm text-gray-500 hover:text-secondary"
                                >
                                    <Home class="w-4 h-4 mr-2" /> Home
                                </Link>
                            </li>
                            <li class="inline-flex items-center">
                                <ChevronRight class="w-4 h-4 text-gray-400" />
                                <Link
                                    href="/event"
                                    class="ml-1 inline-flex items-center text-sm text-gray-500 hover:text-secondary"
                                >
                                    <Calendar class="w-4 h-4 mr-2" /> Event
                                </Link>
                            </li>
                            <li class="flex items-center min-w-0">
                                <ChevronRight
                                    class="w-4 h-4 text-gray-400 flex-shrink-0"
                                />
                                <span
                                    class="ml-1 text-sm font-medium text-gray-500 truncate max-w-[140px] sm:max-w-[200px] md:max-w-[300px]"
                                    :title="event?.nama_event"
                                >
                                    {{ event?.nama_event || "Loading..." }}
                                </span>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Loading/Error -->
                <div v-if="loading" class="text-center py-20">
                    Loading event...
                </div>
                <div v-else-if="error" class="text-center py-20 text-red-600">
                    {{ error }}
                </div>

                <!-- Detail -->
                <div v-else class="space-y-10">
                    <!-- Featured Image -->
                    <div class="w-full aspect-[3/1] bg-gray-100">
                        <img
                            :src="getImageUrl(event.thumbnail_event)"
                            :alt="event.nama_event"
                            class="w-full h-full object-cover rounded-xl"
                        />
                    </div>

                    <!-- Title and Date -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-4">
                            <div
                                class="px-3 py-1 rounded-full border text-sm font-semibold bg-black/5 text-black"
                            >
                                Event
                            </div>
                        </div>
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-semibold">
                            {{ event.nama_event }}
                        </h1>
                        <div class="text-sm text-gray-600">
                            {{ formatDate(event.waktu_start_event) }} |
                            {{ formatTime(event.waktu_start_event) }} -
                            {{ formatTime(event.waktu_end_event) }}
                        </div>
                    </div>

                    <!-- Info Blocks in Card -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <h3 class="font-semibold text-lg mb-4">Informasi Event</h3>
                        <div class="flex flex-col md:grid md:grid-cols-3 gap-y-4 md:gap-y-6 md:gap-x-6">
                            <div class="flex items-start gap-3 pb-4 md:pb-0 border-b md:border-b-0 border-gray-200">
                                <Calendar class="w-5 h-5 text-secondary mt-1 flex-shrink-0" />
                                <div>
                                    <div class="font-medium">Tanggal & Waktu</div>
                                    <div>
                                        {{ formatDate(event.waktu_start_event) }}
                                    </div>
                                    <div>
                                        {{ formatTime(event.waktu_start_event) }} -
                                        {{ formatTime(event.waktu_end_event) }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 py-4 md:py-0 border-b md:border-b-0 border-gray-200">
                                <MapPin class="w-5 h-5 text-secondary mt-1 flex-shrink-0" />
                                <div>
                                    <div class="font-medium">Lokasi</div>
                                    <a
                                        :href="event.link_lokasi_event"
                                        target="_blank"
                                        class="text-secondary hover:underline"
                                    >
                                        {{ event.lokasi_event }}
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 pt-4 md:pt-0">
                                <User class="w-5 h-5 text-secondary mt-1 flex-shrink-0" />
                                <div>
                                    <div class="font-medium">Pendaftar</div>
                                    <div class="text-sm text-gray-600">
                                        {{ event.jumlah_pendaftar || 0 }} orang
                                        terdaftar
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stats & Actions -->
                        <div class="flex items-center justify-end pt-4 border-t border-gray-200 mt-4">
                            <div class="flex items-center gap-2">
                                <button
                                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-medium"
                                    @click="copyEventLink"
                                >
                                    <Copy class="w-4 h-4" />
                                    <span class="hidden sm:inline">Salin Link</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Countdown -->
                    <div class="space-y-4">
                        <h3 class="text-center font-semibold text-lg sm:text-xl text-black">
                            Event akan dimulai dalam:
                        </h3>
                        <EventTimer 
                            :target-date="event.waktu_start_event" 
                            finished-text="Event telah selesai"
                        />
                    </div>

                    <!-- Registration Section -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-lg mb-1">
                                    Daftarkan Diri Anda
                                </h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    Dapatkan pengingat event melalui email dan notifikasi
                                </p>
                                <div
                                    v-if="event.is_registered"
                                    class="flex items-center gap-2 text-green-600"
                                >
                                    <CheckCircle class="w-4 h-4" />
                                    <span class="text-sm font-medium">Anda sudah terdaftar</span>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button
                                    v-if="!event.is_registered"
                                    @click="registerForEvent"
                                    :disabled="registering || !isAuthenticated"
                                    class="px-6 py-2 bg-secondary text-white rounded-lg hover:bg-secondary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    {{ registering ? "Mendaftar..." : "Daftar Event" }}
                                </button>
                                <button
                                    v-else
                                    @click="unregisterFromEvent"
                                    :disabled="registering"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    {{ registering ? "Membatalkan..." : "Batal Daftar" }}
                                </button>
                                <Link
                                    v-if="!isAuthenticated"
                                    href="/admin/login"
                                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-center"
                                >
                                    Login untuk Daftar
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Event -->
                    <div class="space-y-3">
                        <h2 class="text-xl font-semibold">Deskripsi Event</h2>
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="prose prose-lg max-w-none text-black">
                                <p class="whitespace-pre-line text-left leading-relaxed">
                                    {{ event.deskripsi_event }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Copy Link Modal -->
    <CopyLink
        :show="showCopyModal"
        @close="closeCopyModal"
        :auto-close="true"
        :auto-close-delay="3000"
    />
</template>
