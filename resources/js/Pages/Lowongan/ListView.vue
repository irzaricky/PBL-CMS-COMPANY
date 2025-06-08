<script setup>
import { ref, onMounted, computed } from "vue";
import { MapPin, Clock, ArrowRight, Briefcase, Search } from "lucide-vue-next";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

// Data state
const lowongan = ref([]);
const loading = ref(true);
const error = ref(null);
const searchQuery = ref('');
const isLoading = ref(true);
const currentPage = ref(1);
const lastPage = ref(1);
let debounceTimer = null;

// Fetch job listings with pagination and search
const fetchLowongan = async (page = 1, query = '') => {
    try {
        loading.value = true;

        // Gunakan endpoint yang konsisten untuk pencarian
        const url = query ? '/api/lowongan/search' : '/api/lowongan';
        const params = { page };
        if (query) params.query = query;

        const response = await axios.get(url, { params });

        // Handle response berdasarkan struktur API
        if (response.data && response.data.data) {
            lowongan.value = response.data.data;

            // Setup pagination if available
            if (response.data.meta) {
                currentPage.value = response.data.meta.current_page;
                lastPage.value = response.data.meta.last_page;
            }
        } else {
            console.error("Unexpected response format:", response.data);
            lowongan.value = [];
        }
    } catch (err) {
        console.error("Error fetching job listings:", err);
        // Set empty array instead of showing error message to user
        lowongan.value = [];
        // Jangan tampilkan pesan error ke pengguna
        // hanya tampilkan empty state dengan set array kosong
        // error.value = "Gagal memuat lowongan. Silakan coba lagi nanti.";
    } finally {
        loading.value = false;
        isLoading.value = false;
    }
};
// Handle search
const handleSearch = () => {
    if (debounceTimer) clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        currentPage.value = 1; // Reset to first page when searching
        fetchLowongan(1, searchQuery.value);
    }, 500);
};

// Go to specific page
const goToPage = (page) => {
    if (page < 1 || page > lastPage.value) return;
    fetchLowongan(page, searchQuery.value);
};

// Format date (YYYY-MM-DD to readable format)
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(date);
};

// Check if job is still open
const isJobOpen = (openDate, closeDate) => {
    const today = new Date();
    const open = new Date(openDate);
    const close = new Date(closeDate);
    return today >= open && today <= close;
};

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

onMounted(() => {
    fetchLowongan();

    // Set isLoading to false after a delay to show skeleton
    setTimeout(() => {
        isLoading.value = false
    }, 2000);
});
</script>

<template>
    <AppLayout>
        <div class="py-16 lg:py-28 px-4 sm:px-6 lg:px-16 font-custom">
            <div class="max-w-screen-xl mx-auto">
                <!-- Header Section -->
                <div class="flex flex-col justify-center items-center gap-4 mb-16 lg:mb-20 max-w-xl mx-auto">
                    <div class="inline-flex justify-start items-center">
                        <div class="text-center text-typography-dark font-semibold">
                            Karir
                        </div>
                    </div>
                    <div class="flex flex-col justify-start items-center gap-6">
                        <h1 class="text-3xl lg:text-5xl text-center text-typography-dark font-normal">
                            Lowongan Tersedia
                        </h1>
                        <p class="text-base lg:text-lg text-center text-typography-dark font-normal">
                            Temukan posisi yang sesuai dengan keterampilan dan minat Anda untuk bergabung dengan tim
                            kami.
                        </p>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="w-full max-w-2xl mx-auto mb-12">
                    <div class="relative flex items-center">
                        <Search class="absolute left-4 text-gray-400 w-5 h-5" />
                        <input v-model="searchQuery" @input="handleSearch" type="text"
                            placeholder="Cari nama lowongan"
                            class="w-full px-12 py-3 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition-all duration-300 font-custom" />
                        <button v-if="searchQuery" @click="() => { searchQuery = ''; handleSearch(); }"
                            class="absolute right-4 p-1 text-gray-500 hover:text-gray-700">
                            <X class="w-4 h-4" aria-hidden="true" />
                            <span class="sr-only">Clear search</span>
                        </button>
                    </div>
                </div>

                <!-- Loading State (Skeleton) -->
                <div v-if="isLoading" class="max-w-3xl mx-auto">
                    <!-- Skeleton Job Cards -->
                    <div class="flex flex-col gap-8">
                        <!-- Skeleton Job Card 1 -->
                        <div class="p-6 lg:p-8 bg-white rounded-2xl shadow flex flex-col gap-6">
                            <!-- Skeleton Thumbnail -->
                            <div class="w-full h-48 mb-2 rounded-lg bg-gray-200 animate-pulse"></div>

                            <div class="flex flex-col gap-6">
                                <div class="flex flex-wrap items-center gap-4">
                                    <div class="h-8 w-48 bg-gray-200 animate-pulse rounded-md"></div>
                                    <div class="h-6 w-20 bg-gray-200 animate-pulse rounded-full"></div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-200 animate-pulse rounded w-full"></div>
                                    <div class="h-4 bg-gray-200 animate-pulse rounded w-5/6"></div>
                                    <div class="h-4 bg-gray-200 animate-pulse rounded w-4/6"></div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-wrap gap-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 animate-pulse"></div>
                                        <div class="h-5 w-20 bg-gray-200 animate-pulse rounded"></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 animate-pulse"></div>
                                        <div class="h-5 w-36 bg-gray-200 animate-pulse rounded"></div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="h-6 w-32 bg-gray-200 animate-pulse rounded-md"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Skeleton Job Card 2 -->
                        <div class="p-6 lg:p-8 bg-white rounded-2xl shadow flex flex-col gap-6">
                            <!-- Skeleton Thumbnail -->
                            <div class="w-full h-48 mb-2 rounded-lg bg-gray-200 animate-pulse"></div>

                            <div class="flex flex-col gap-6">
                                <div class="flex flex-wrap items-center gap-4">
                                    <div class="h-8 w-64 bg-gray-200 animate-pulse rounded-md"></div>
                                    <div class="h-6 w-24 bg-gray-200 animate-pulse rounded-full"></div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-200 animate-pulse rounded w-full"></div>
                                    <div class="h-4 bg-gray-200 animate-pulse rounded w-full"></div>
                                    <div class="h-4 bg-gray-200 animate-pulse rounded w-3/5"></div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-wrap gap-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 animate-pulse"></div>
                                        <div class="h-5 w-24 bg-gray-200 animate-pulse rounded"></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 animate-pulse"></div>
                                        <div class="h-5 w-40 bg-gray-200 animate-pulse rounded"></div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <div class="h-6 w-32 bg-gray-200 animate-pulse rounded-md"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="flex justify-center items-center py-12 px-4">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        {{ error }}
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="lowongan.length === 0"
                    class="col-span-full flex flex-col items-center justify-center gap-6 py-20 text-center">
                    <div class="flex flex-col lg:flex-row items-center gap-6 text-left">
                        <img src="/image/empty.svg" alt="Empty State"
                            class="w-40 h-40 lg:w-96 lg:h-96 object-contain" />
                        <div>
                            <h3 class="text-xl md:text-2xl font-semibold text-gray-700 font-custom">
                                {{ searchQuery ? 'Lowongan tidak ditemukan' : 'Belum ada lowongan tersedia' }}
                            </h3>
                            <p class="text-sm md:text-base text-gray-500 font-custom">
                                {{ searchQuery
                                    ? 'Coba gunakan kata kunci lain atau lihat kembali nanti.'
                                    : 'Lowongan kerja belum tersedia saat ini. Silakan kunjungi kembali halaman ini nanti.'
                                }}
                            </p>

                            <!-- Show "Clear Search" button when filtering yields no results -->
                            <button v-if="searchQuery" @click="() => { searchQuery = ''; handleSearch(); }"
                                class="mt-4 px-6 py-2 bg-secondary hover:bg-black text-white rounded-full transition font-medium text-sm flex items-center gap-2">
                                <X class="w-4 h-4 mr-1" />
                                Hapus Pencarian
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Job Listings -->
                <div v-else class="flex flex-col gap-8 max-w-3xl mx-auto">
                    <!-- Job Card (Dynamic) -->
                    <div v-for="job in lowongan" :key="job.id_lowongan"
                        class="p-6 lg:p-8 bg-white rounded-2xl shadow flex flex-col gap-6">
                        <!-- Job Thumbnail -->
                        <div v-if="job.thumbnail_lowongan" class="w-full h-48 mb-2 rounded-lg overflow-hidden">
                            <img :src="getImageUrl(job.thumbnail_lowongan)" :alt="job.judul_lowongan"
                                class="w-full h-full object-cover"
                                @error="$event.target.src = '/image/placeholder.webp'" />
                        </div>

                        <div class="flex flex-col gap-6">
                            <div class="flex flex-wrap items-center gap-4">
                                <h2 class="text-xl lg:text-2xl text-typography-dark font-normal">
                                    {{ job.judul_lowongan }}
                                </h2>
                                <div class="flex gap-2">
                                    <span class="px-2.5 py-1 bg-gray-100 rounded-full text-sm font-semibold">
                                        {{ job.jenis_lowongan }}
                                    </span>

                                    <!-- Status Badge -->
                                    <span :class="`px-2.5 py-1 rounded-full text-sm font-semibold ${isJobOpen(job.tanggal_dibuka, job.tanggal_ditutup)
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                        }`">
                                        {{ isJobOpen(job.tanggal_dibuka, job.tanggal_ditutup) ? 'Dibuka' : 'Ditutup' }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-typography-dark font-normal line-clamp-3">
                                {{ job.deskripsi_pekerjaan }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-wrap gap-6">
                                <div class="flex items-center gap-3">
                                    <MapPin size="20" class="text-typography-dark" />
                                    <span class="text-typography-dark lg:text-lg font-normal">
                                        {{ job.lokasi || "Remote" }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <Clock size="20" class="text-typography-dark" />
                                    <span class="text-typography-dark lg:text-lg font-normal">
                                        Ditutup: {{ formatDate(job.tanggal_ditutup) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <a :href="'/lowongan/' + job.slug"
                                    class="flex items-center gap-2 text-secondary hover:text-typography-hover font-medium">
                                    Detail Lowongan
                                    <ArrowRight size="18" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="lastPage > 1 && lowongan.length > 0"
                    class="flex justify-center items-center gap-4 mt-10 font-custom text-sm">
                    <!-- Tombol Sebelumnya -->
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 rounded-xl font-medium transition border" :class="currentPage === 1
                            ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-200'
                            : 'bg-white text-black border-gray-300 hover:bg-black hover:text-white'">
                        Sebelumnya
                    </button>

                    <!-- Indikator halaman -->
                    <div class="px-4 py-2 rounded-xl border border-black text-black font-semibold">
                        {{ currentPage }} / {{ lastPage }}
                    </div>

                    <!-- Tombol Selanjutnya -->
                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                        class="px-4 py-2 rounded-xl font-medium transition border" :class="currentPage === lastPage
                            ? 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-200'
                            : 'bg-white text-black border-gray-300 hover:bg-black hover:text-white'">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>