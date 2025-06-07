<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from "vue";
import axios from "axios";

// State variables
const caseStudies = ref([]);
const featuredCase = ref(null);
const searchQuery = ref('');
const isLoading = ref(true);
const currentPage = ref(1);
const lastPage = ref(1);
let debounceTimer = null;

// Fetch data when component mounts
onMounted(() => {
    fetchCaseStudies();
    fetchLatestCaseStudy();
});

// Fetch all case studies
async function fetchCaseStudies(query = '', page = 1) {
    isLoading.value = true;
    try {
        let url = query ? '/api/case-study/search' : '/api/case-study';
        const params = { page };
        if (query) params.query = query;

        const response = await axios.get(url, { params });
        caseStudies.value = response.data.data;
        currentPage.value = response.data.meta?.current_page || 1;
        lastPage.value = response.data.meta?.last_page || 1;
    } catch (error) {
        console.error('Error fetching case studies:', error);
        caseStudies.value = [];
    } finally {
        isLoading.value = false;
    }
}

// Fetch latest case study
async function fetchLatestCaseStudy() {
    try {
        const response = await axios.get('/api/case-study/latest');
        featuredCase.value = response.data.data;
    } catch (error) {
        console.error('Error fetching latest case study:', error);
        featuredCase.value = null;
    }
}

// Handle search with debounce
const handleSearch = () => {
    if (debounceTimer) clearTimeout(debounceTimer);
    
    debounceTimer = setTimeout(() => {
        currentPage.value = 1;
        fetchCaseStudies(searchQuery.value, 1);
    }, 500);
};

// Navigate to a specific page
const goToPage = (page) => {
    if (page < 1 || page > lastPage.value) return;
    fetchCaseStudies(searchQuery.value, page);
};

// Helper function to get image URL
function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}

// Format date to Indonesian format
function formatDate(dateString) {
    if (!dateString) return "";
    
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}
</script>

<template>
    <AppLayout>
        <!-- Hero Section with Search -->
        <div class="relative w-full bg-cover bg-center bg-no-repeat px-4 lg:px-16 py-28 flex flex-col justify-start items-center gap-16 overflow-hidden"
            style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/image/placeholder.webp')">

            <!-- Content -->
            <div class="relative z-10 w-full max-w-4xl flex flex-col justify-start items-center gap-10 text-white text-center">
                <!-- Heading -->
                <div class="flex flex-col items-center gap-6">
                    <div class="text-base font-semibold font-custom uppercase tracking-wider">Studi Kasus</div>
                    <h1 class="text-4xl lg:text-6xl font-normal font-custom leading-tight">
                        Kisah Sukses yang<br>Menginspirasi Perubahan
                    </h1>
                    <p class="text-lg font-normal font-custom leading-relaxed max-w-3xl">
                        Jelajahi bagaimana kami membantu klien dari berbagai industri mengubah tantangan menjadi peluang
                        dengan solusi inovatif dan hasil yang terukur.
                    </p>
                </div>

                <!-- Search form -->
                <div class="w-full max-w-2xl flex flex-col items-center gap-4">
                    <div class="w-full relative">
                        <input v-model="searchQuery" @input="handleSearch" type="text" 
                            placeholder="Cari studi kasus berdasarkan industri, tantangan, atau solusi..." 
                            class="w-full px-6 py-4 rounded-full bg-white/10 backdrop-blur-sm text-white 
                                placeholder-white/60 outline outline-1 outline-white/30 focus:outline-white/70 
                                focus:ring-0 font-custom pr-16" />
                        <button @click="handleSearch" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-secondary text-white hover:bg-opacity-80 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm font-normal font-custom leading-none opacity-70">
                        Coba cari "keuangan", "kesehatan", atau "transformasi digital"
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-screen-xl mx-auto px-4 lg:px-8 py-16 flex flex-col items-center gap-20 overflow-hidden">
            
            <!-- Featured Case Study -->
            <section v-if="featuredCase" class="w-full">
                <div class="w-full mb-12 flex flex-col gap-4">
                    <div class="text-secondary text-base font-semibold font-custom uppercase tracking-wider">Studi Kasus Unggulan</div>
                    <h2 class="text-3xl lg:text-5xl font-normal font-custom leading-tight">
                        Kisah Sukses Terbaru Kami
                    </h2>
                </div>

                <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-8 bg-gray-50 rounded-3xl overflow-hidden">
                    <div class="h-[400px] lg:h-[500px] overflow-hidden">
                        <img :src="getImageUrl(featuredCase.thumbnail_case_study)" :alt="featuredCase.judul_case_study" 
                            class="w-full h-full object-cover transition duration-500 hover:scale-105" />
                    </div>
                    <div class="flex flex-col justify-center p-8 lg:p-12 gap-6">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1.5 bg-secondary/10 text-secondary rounded-full text-sm font-semibold">
                                {{ featuredCase.mitra?.nama_mitra || 'Mitra' }}
                            </span>
                        </div>
                        <h3 class="text-2xl lg:text-4xl font-custom font-normal">{{ featuredCase.judul_case_study }}</h3>
                        <p class="text-base lg:text-lg font-custom text-gray-600">
                            {{ featuredCase.deskripsi_case_study }}
                        </p>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex items-center gap-2 text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" 
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                    <path d="M16 2v4"></path>
                                    <path d="M8 2v4"></path>
                                    <path d="M3 10h18"></path>
                                </svg>
                                <span>{{ formatDate(featuredCase.created_at) }}</span>
                            </div>
                        </div>
                        <a :href="`/studi-kasus/${featuredCase.slug_case_study}`" class="mt-6 inline-flex items-center justify-center gap-2 
                            px-6 py-3 bg-secondary text-white font-medium rounded-full 
                            hover:bg-black transition-all duration-300 w-fit">
                            Baca Studi Kasus
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                stroke-linejoin="round" class="ml-1">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Loading State for Featured Case -->
            <section v-if="isLoading" class="w-full">
                <div class="w-full mb-12 flex flex-col gap-4">
                    <div class="h-6 bg-gray-200 rounded w-40 animate-pulse"></div>
                    <div class="h-12 bg-gray-300 rounded w-96 animate-pulse"></div>
                </div>

                <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-8 bg-gray-50 rounded-3xl overflow-hidden p-6">
                    <div class="h-[400px] bg-gray-200 animate-pulse rounded-xl"></div>
                    <div class="flex flex-col justify-center gap-6">
                        <div class="h-6 bg-gray-200 rounded w-40 animate-pulse"></div>
                        <div class="h-10 bg-gray-300 rounded w-full animate-pulse"></div>
                        <div class="h-20 bg-gray-200 rounded w-full animate-pulse"></div>
                        <div class="h-6 bg-gray-200 rounded w-40 animate-pulse"></div>
                        <div class="h-10 bg-gray-200 rounded w-48 animate-pulse"></div>
                    </div>
                </div>
            </section>

            <!-- Case Studies List -->
            <div class="w-full">
                <div class="mb-12">
                    <h2 class="text-3xl font-normal font-custom mb-8">Jelajahi Semua Studi Kasus</h2>
                </div>

                <!-- No Case Studies -->
                <div v-if="!isLoading && caseStudies.length === 0" class="text-center py-12">
                    <div class="flex flex-col items-center gap-6">
                        <img src="/image/empty.svg" alt="Tidak ada data" class="w-40 h-40 object-contain" />
                        <h3 class="text-xl font-medium text-gray-700">Tidak ada studi kasus yang ditemukan</h3>
                        <p class="text-gray-500">Coba ubah kata kunci pencarian Anda</p>
                    </div>
                </div>

                <!-- Case Studies Grid -->
                <div v-if="!isLoading && caseStudies.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div v-for="item in caseStudies" :key="item.case_study_id" 
                        class="group bg-white rounded-2xl overflow-hidden border border-gray-100 transition-all duration-300 hover:border-secondary">
                        <!-- Image -->
                        <div class="h-64 overflow-hidden">
                            <img :src="getImageUrl(item.thumbnail_case_study)" :alt="item.judul_case_study" 
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6 flex flex-col gap-4">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 bg-secondary/10 text-secondary rounded-full text-sm font-semibold">
                                    {{ item.mitra?.nama_mitra || 'Mitra' }}
                                </span>
                            </div>
                            
                            <a :href="`/case-study/${item.slug_case_study}`" class="group-hover:text-secondary">
                                <h3 class="text-xl font-custom font-medium transition-colors">
                                    {{ item.judul_case_study }}
                                </h3>
                            </a>
                            
                            <p class="text-gray-600 line-clamp-2">{{ item.deskripsi_case_study }}</p>
                            
                            <div class="mt-2 text-sm text-gray-500">
                                {{ formatDate(item.created_at) }}
                            </div>
                            
                            <a :href="`/case-study/${item.slug_case_study}`" class="mt-4 text-secondary font-medium flex items-center group-hover:underline">
                                Lihat studi kasus
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" 
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                    stroke-linejoin="round" class="ml-1">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Loading State for Grid -->
                <div v-if="isLoading" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div v-for="i in 6" :key="i" class="bg-white rounded-2xl overflow-hidden border border-gray-100">
                        <!-- Skeleton Image -->
                        <div class="h-64 bg-gray-200 animate-pulse"></div>
                        
                        <!-- Skeleton Content -->
                        <div class="p-6 flex flex-col gap-4">
                            <div class="h-6 bg-gray-200 rounded w-32 animate-pulse"></div>
                            <div class="h-8 bg-gray-300 rounded w-full animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-full animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                            <div class="h-4 bg-gray-200 rounded w-24 mt-2 animate-pulse"></div>
                            <div class="h-6 bg-gray-200 rounded w-40 mt-4 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="!isLoading && lastPage > 1" class="flex justify-center items-center gap-4 mt-16 font-custom">
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                        class="px-4 py-2 rounded-full font-medium transition border" 
                        :class="currentPage === 1 ? 
                            'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' :
                            'bg-white text-black border-gray-300 hover:bg-secondary hover:text-white hover:border-secondary'">
                        Sebelumnya
                    </button>

                    <div class="flex gap-2">
                        <button v-for="page in Math.min(3, lastPage)" :key="page"
                            @click="goToPage(page)"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-medium transition"
                            :class="currentPage === page ? 
                                'bg-secondary text-white' : 
                                'bg-white text-black border border-gray-300 hover:border-secondary hover:text-secondary'">
                            {{ page }}
                        </button>
                        <span v-if="lastPage > 3" class="flex items-center px-2">...</span>
                        <button v-if="lastPage > 3" 
                            @click="goToPage(lastPage)"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-medium transition"
                            :class="currentPage === lastPage ? 
                                'bg-secondary text-white' : 
                                'bg-white text-black border border-gray-300 hover:border-secondary hover:text-secondary'">
                            {{ lastPage }}
                        </button>
                    </div>

                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                        class="px-4 py-2 rounded-full font-medium transition border"
                        :class="currentPage === lastPage ? 
                            'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 
                            'bg-white text-black border-gray-300 hover:bg-secondary hover:text-white hover:border-secondary'">
                        Selanjutnya
                    </button>
                </div>
            </div>

            <!-- CTA Section -->
            <section class="w-full bg-gray-50 rounded-3xl p-8 lg:p-16 text-center">
                <div class="max-w-3xl mx-auto flex flex-col items-center gap-8">
                    <h2 class="text-3xl lg:text-4xl font-normal font-custom">Siap untuk membuat kisah sukses Anda sendiri?</h2>
                    <p class="text-lg text-gray-600">
                        Mari bekerja sama untuk mengubah tantangan bisnis Anda menjadi kisah sukses yang terukur.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mt-4">
                        <a href="/kontak" class="px-8 py-3 bg-secondary text-white rounded-full font-medium hover:bg-black transition-colors">
                            Hubungi Kami
                        </a>
                        <a href="/layanan" class="px-8 py-3 bg-white text-secondary border border-secondary rounded-full font-medium hover:bg-secondary/5 transition-colors">
                            Lihat Layanan Kami
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>