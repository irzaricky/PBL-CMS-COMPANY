<script setup>
import { ref, onMounted, computed } from "vue";
import { MapPin, Clock, ArrowRight, Briefcase } from "lucide-vue-next";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

// Data state
const lowongan = ref([]);
const departments = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedDepartment = ref(null);

// Fetch job listings
const fetchLowongan = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/lowongan');
        lowongan.value = response.data.data;

        // Extract unique departments for filtering
        const uniqueDepartments = [...new Set(lowongan.value.map(job => job.jenis_lowongan))];
        departments.value = uniqueDepartments;
    } catch (err) {
        error.value = "Gagal memuat lowongan. Silakan coba lagi nanti.";
        console.error("Error fetching job listings:", err);
    } finally {
        loading.value = false;
    }
};

// Filter jobs by department
const filterByDepartment = (department) => {
    selectedDepartment.value = department === selectedDepartment.value ? null : department;

    if (department) {
        fetchFilteredLowongan(department);
    } else {
        fetchLowongan();
    }
};

// Fetch filtered listings
const fetchFilteredLowongan = async (department) => {
    try {
        loading.value = true;
        const response = await axios.get('/api/lowongan/search', {
            params: { jenis_lowongan: department }
        });
        lowongan.value = response.data.data;
    } catch (err) {
        error.value = "Gagal memfilter lowongan. Silakan coba lagi nanti.";
        console.error("Error filtering job listings:", err);
    } finally {
        loading.value = false;
    }
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
const isJobOpen = (closingDate) => {
    const today = new Date();
    const closeDate = new Date(closingDate);
    return closeDate >= today;
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
                            Karier
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

                <!-- Loading State (Skeleton) -->
                <div v-if="loading" class="max-w-3xl mx-auto">
                    <!-- Skeleton Filter Tabs -->
                    <div class="flex flex-wrap justify-center items-center gap-2 mb-8 lg:mb-12">
                        <div class="w-16 h-10 bg-gray-200 animate-pulse rounded-xl border border-gray-100"></div>
                        <div class="w-24 h-10 bg-gray-200 animate-pulse rounded-xl border border-gray-100"></div>
                        <div class="w-20 h-10 bg-gray-200 animate-pulse rounded-xl border border-gray-100"></div>
                        <div class="w-28 h-10 bg-gray-200 animate-pulse rounded-xl border border-gray-100"></div>
                    </div>

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
                <div v-else-if="lowongan.length === 0" class="flex flex-col justify-center items-center py-12 px-4">
                    <Briefcase class="w-16 h-16 text-gray-300 mb-4" />
                    <h3 class="text-xl text-typography-dark font-normal mb-2">Tidak ada lowongan tersedia</h3>
                    <p class="text-typography-dark text-center">Saat ini tidak ada lowongan yang tersedia. Silakan
                        periksa kembali nanti.</p>
                </div>

                <template v-else>
                    <!-- Filter Tabs -->
                    <div v-if="departments.length > 0"
                        class="flex flex-wrap justify-center items-center gap-2 mb-8 lg:mb-12">
                        <!-- View All -->
                        <button @click="filterByDepartment(null)"
                            class="px-4 py-2 rounded-xl font-medium transition border" :class="selectedDepartment === null
                                ? 'bg-black text-white'
                                : 'bg-white text-black border-gray-300 hover:bg-black hover:text-white'">
                            Semua
                        </button>

                        <!-- Department Filters -->
                        <button v-for="department in departments" :key="department"
                            @click="filterByDepartment(department)"
                            class="px-4 py-2 rounded-xl font-medium transition border" :class="selectedDepartment === department
                                ? 'bg-black text-white'
                                : 'bg-white text-black border-gray-300 hover:bg-black hover:text-white'">
                            {{ department }}
                        </button>
                    </div>

                    <!-- Job Listings -->
                    <div class="flex flex-col gap-8 max-w-3xl mx-auto">
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
                                    <span class="px-2.5 py-1 bg-gray-100 rounded-full text-sm font-semibold">
                                        {{ job.jenis_lowongan }}
                                    </span>
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
                </template>
            </div>
        </div>
    </AppLayout>
</template>