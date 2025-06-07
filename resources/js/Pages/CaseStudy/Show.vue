<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import CopyLink from "@/Components/Modal/CopyLink.vue";
import { Home, FileText, ChevronRight, Copy, Clock, BookOpenCheck } from "lucide-vue-next";

const caseStudy = ref(null);
const loading = ref(true);
const error = ref(null);
const readingTime = ref("");
const showCopyModal = ref(false);

const props = defineProps({
    slug: String,
});

onMounted(() => {
    fetchCaseStudy();
});

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

async function copyLink() {
    const url = window.location.href;
    try {
        if (
            navigator.clipboard &&
            typeof navigator.clipboard.writeText === "function"
        ) {
            await navigator.clipboard.writeText(url);
        } else {
            throw new Error("Clipboard API not available");
        }
        showCopyModal.value = true;
    } catch (err) {
        console.warn("Clipboard write failed, using fallback:", err);
        fallbackCopy(url);
    }
}

function closeCopyModal() {
    showCopyModal.value = false;
}

// Fetch case study data from API
async function fetchCaseStudy() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/case-study/${props.slug}`);
        caseStudy.value = response.data.data;

        // Calculate reading time (roughly 200 words per minute)
        const wordCount = caseStudy.value.isi_case_study
            .replace(/<[^>]*>/g, "")
            .split(/\s+/).length;
        const minutes = Math.ceil(wordCount / 200);
        readingTime.value = `${minutes} menit durasi baca`;

        loading.value = false;
    } catch (err) {
        error.value = "Studi kasus tidak ditemukan atau terjadi kesalahan";
        loading.value = false;
        console.error("Error fetching case study:", err);
    }
}

// Helper function to get image URL
function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

// Format date to Indonesian format
function formatDate(date) {
    if (!date) return "";

    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}
</script>

<template>
    <AppLayout>
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-16 py-20 font-custom text-black">
            <!-- Skeleton Loading -->
            <div v-if="loading" class="flex flex-col gap-16">
                <!-- Breadcrumb Skeleton -->
                <div class="flex flex-col gap-12">
                    <div class="bg-gray-200 h-6 w-3/4 rounded animate-pulse"></div>
                    <div class="flex flex-col gap-4">
                        <div class="bg-gray-200 h-8 w-48 rounded-full animate-pulse"></div>
                        <div class="bg-gray-300 h-14 w-full rounded animate-pulse"></div>
                    </div>
                </div>

                <!-- Featured Image Skeleton -->
                <div class="flex flex-col gap-8">
                    <div class="bg-gray-200 h-[300px] sm:h-[400px] lg:h-[600px] rounded-2xl w-full animate-pulse"></div>

                    <!-- Mitra Info Box Skeleton -->
                    <div class="bg-gray-100 rounded-xl w-full p-6 border border-gray-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-gray-200 w-14 h-14 rounded-full animate-pulse"></div>
                            <div class="flex-1">
                                <div class="bg-gray-200 h-6 w-40 rounded animate-pulse mb-2"></div>
                                <div class="bg-gray-200 h-4 w-32 rounded animate-pulse"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="bg-gray-200 h-5 w-24 rounded animate-pulse"></div>
                            <div class="bg-gray-200 h-8 w-24 rounded animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <!-- Content Skeleton -->
                <div class="flex flex-col gap-4">
                    <div class="bg-gray-200 h-6 w-full rounded animate-pulse"></div>
                    <div class="bg-gray-200 h-6 w-full rounded animate-pulse"></div>
                    <div class="bg-gray-200 h-6 w-3/4 rounded animate-pulse"></div>
                    <div class="bg-gray-200 h-6 w-5/6 rounded animate-pulse"></div>
                    <div class="bg-gray-200 h-6 w-full rounded animate-pulse"></div>
                    <div class="bg-gray-200 h-6 w-4/5 rounded animate-pulse"></div>
                    <div class="bg-gray-200 h-6 w-full rounded animate-pulse"></div>
                </div>
            </div>

            <!-- Actual Content (display when not loading) -->
            <div v-else-if="caseStudy" class="flex flex-col gap-16">
                <!-- Breadcrumb -->
                <div class="flex flex-col gap-12">
                    <div>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                                <li>
                                    <Link href="/" class="inline-flex items-center text-gray-500 hover:text-secondary">
                                    <Home class="w-4 h-4 mr-2" />
                                    Home
                                    </Link>
                                </li>
                                <li class="inline-flex items-center">
                                    <ChevronRight class="w-4 h-4 text-gray-400" />
                                    <Link href="/case-study"
                                        class="ml-1 inline-flex items-center text-gray-500 hover:text-secondary">
                                    <BookOpenCheck class="w-4 h-4 mr-2" />
                                    Case Study
                                    </Link>
                                </li>
                                <li class="flex items-center min-w-0">
                                    <ChevronRight class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                    <span
                                        class="ml-1 text-sm font-medium text-gray-500 truncate max-w-[140px] sm:max-w-[200px] md:max-w-[300px]"
                                        :title="caseStudy.judul_case_study">
                                        {{ caseStudy.judul_case_study }}
                                    </span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4 flex-wrap">
                            <div v-if="caseStudy.mitra"
                                class="px-3 py-1 rounded-full border border-secondary/30 bg-secondary/10 text-sm font-medium text-secondary">
                                {{ caseStudy.mitra.nama_mitra }}
                            </div>
                            <div class="text-sm font-medium text-gray-600 flex items-center gap-2">
                                <Clock class="w-4 h-4" />
                                {{ readingTime }}
                            </div>
                        </div>
                        <h1 class="text-4xl sm:text-5xl font-normal leading-tight">
                            {{ caseStudy.judul_case_study }}
                        </h1>
                    </div>
                </div>

                <!-- Gambar & Info Mitra -->
                <div class="flex flex-col gap-8">
                    <div class="relative">
                        <img class="w-full h-[300px] sm:h-[400px] lg:h-[600px] rounded-2xl object-cover"
                            :src="getImageUrl(caseStudy.thumbnail_case_study)" :alt="caseStudy.judul_case_study" />
                    </div>
                    <div class="flex justify-between items-start flex-wrap gap-8">
                        <!-- Info Mitra - Clean Layout -->
                        <div class="bg-gray-50 rounded-xl w-full p-6 border border-gray-100">
                            <!-- Mitra Profile -->
                            <div class="flex items-center gap-4 mb-4">
                                <img v-if="caseStudy.mitra?.logo" :src="getImageUrl(caseStudy.mitra.logo)"
                                    class="w-16 h-16 object-contain p-1"
                                    alt="Logo Mitra" />
                                <div v-else
                                    class="w-16 h-16 bg-secondary/20 flex items-center justify-center text-secondary font-bold">
                                    {{ caseStudy.mitra?.nama_mitra?.charAt(0) || "M" }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg text-black">
                                        {{ caseStudy.mitra?.nama_mitra || "Mitra" }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ formatDate(caseStudy.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <!-- Stats & Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="text-sm font-medium text-gray-600">
                                    Studi Kasus {{ caseStudy.mitra?.nama_mitra || "Mitra" }}
                                </div>

                                <button
                                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-gray-200 
                                    hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-medium"
                                    @click="copyLink">
                                    <Copy class="w-4 h-4" />
                                    <span class="hidden sm:inline">Salin Link</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Singkat -->
                <div class="bg-gray-50 p-6 rounded-xl border-l-4 border-secondary">
                    <h2 class="text-xl font-semibold mb-3">Ringkasan Studi Kasus</h2>
                    <p class="text-gray-700 leading-relaxed">{{ caseStudy.deskripsi_case_study }}</p>
                </div>

                <!-- Konten Studi Kasus -->
                <div class="prose prose-lg max-w-none text-black" v-html="caseStudy.isi_case_study"></div>

                <!-- Next/Previous Navigation -->
                <div class="border-t border-gray-200 pt-10 mt-10">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <Link href="/studi-kasus"
                            class="px-6 py-3 bg-secondary text-white rounded-full font-medium hover:bg-black transition-colors">
                        Lihat Semua Studi Kasus
                        </Link>

                        <Link v-if="caseStudy.mitra" :href="`/mitra/${caseStudy.mitra.id_mitra}`" class="px-6 py-3 bg-white border border-secondary text-secondary rounded-full font-medium
                            hover:bg-secondary/5 transition-colors">
                        Lihat Profil {{ caseStudy.mitra.nama_mitra }}
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else class="flex flex-col items-center justify-center py-20 gap-4">
                <div class="text-4xl text-gray-300">😕</div>
                <h2 class="text-2xl font-semibold text-gray-800">{{ error }}</h2>
                <p class="text-gray-600">Studi kasus yang Anda cari tidak ditemukan atau terjadi kesalahan.</p>
                <Link href="/studi-kasus"
                    class="mt-4 px-6 py-3 bg-secondary text-white rounded-full font-medium hover:bg-black transition-colors">
                Kembali ke Daftar Studi Kasus
                </Link>
            </div>
        </div>

        <CopyLink :show="showCopyModal" @close="closeCopyModal" :auto-close="true" :auto-close-delay="3000" />
    </AppLayout>
</template>