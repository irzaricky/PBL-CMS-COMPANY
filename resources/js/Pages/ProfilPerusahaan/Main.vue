<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
// Import Lucide icons
import { 
    Facebook, 
    Instagram, 
    Linkedin, 
    Twitter, 
    Youtube, 
    Github,
    MessageCircle, // For WhatsApp
    Send, // For Telegram
    Music // For TikTok (closest match)
} from "lucide-vue-next";

// Data
const profil_perusahaan = ref(null)
const loading = ref(false)
const error = ref(null)
const mediaSosial = ref([])

// Jumlah kalimat
const maxKalimat = 1

// Function to strip HTML tags
function stripHtml(html: string): string {
    if (!html) return '';
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}

// Function to truncate text
function truncateText(text: string, length = 150): string {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
}

// Visi
const truncatedVisi = computed(() => {
    if (!profil_perusahaan.value?.visi_perusahaan) return 'Visi perusahaan belum tersedia.'
    const cleanText = stripHtml(profil_perusahaan.value.visi_perusahaan);
    const kalimat = cleanText.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})
const showReadMoreVisi = computed(() => {
    if (!profil_perusahaan.value?.visi_perusahaan) return false
    const cleanText = stripHtml(profil_perusahaan.value.visi_perusahaan);
    return cleanText.split(/(?<=[.!?])\s+/).length > maxKalimat
})

// Misi
const truncatedMisi = computed(() => {
    if (!profil_perusahaan.value?.misi_perusahaan) return 'Misi perusahaan belum tersedia.'
    const cleanText = stripHtml(profil_perusahaan.value.misi_perusahaan);
    const kalimat = cleanText.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})
const showReadMoreMisi = computed(() => {
    if (!profil_perusahaan.value?.misi_perusahaan) return false
    const cleanText = stripHtml(profil_perusahaan.value.misi_perusahaan);
    return cleanText.split(/(?<=[.!?])\s+/).length > maxKalimat
})

// Fetch data
async function fetchProfilPerusahaan() {
    try {
        loading.value = true
        const response = await axios.get(`/api/profil-perusahaan/`)
        profil_perusahaan.value = response.data.data
        loading.value = false
    } catch (err) {
        error.value = "Event not found or an error occurred"
        loading.value = false
        console.error("Error fetching profil_perusahaan:", err)
    }
}

// Fetch media sosial
async function fetchMediaSosial() {
    try {
        const response = await axios.get('/api/media-sosial');
        mediaSosial.value = [];
        
        // Process the response data
        for (const [platform, data] of Object.entries(response.data.data)) {
            if ((data as any).active === 1) {  // Check if active is 1 (true)
                mediaSosial.value.push({
                    name: platform,
                    link: (data as any).link
                });
            }
        }
    } catch (err) {
        console.error("Error fetching social media:", err);
    }
}

// Utility
function getImageUrl(image: string | string[] | null): string {
    if (!image) return "/image/placeholder.webp"
    if (Array.isArray(image)) {
        return image.length > 0 ? `/storage/${image[0]}` : "/image/placeholder.webp"
    }
    return `/storage/${image}`
}

// SLIDER TERPISAH
const topIndex = ref(0)
const bottomIndex = ref(0)

// Bagi thumbnail jadi 2 bagian
const thumbnailTop = computed(() => {
    return profil_perusahaan.value?.thumbnail_perusahaan?.slice(0, 2) || []
})
const thumbnailBottom = computed(() => {
    return profil_perusahaan.value?.thumbnail_perusahaan?.slice(2) || []
})

// Auto slide
let intervalTop: any
let intervalBottom: any

onMounted(() => {
    fetchProfilPerusahaan()
    fetchMediaSosial()

    intervalTop = setInterval(() => {
        if (thumbnailTop.value.length > 1) {
            topIndex.value = (topIndex.value + 1) % thumbnailTop.value.length
        }
    }, 4000)

    intervalBottom = setInterval(() => {
        if (thumbnailBottom.value.length > 1) {
            bottomIndex.value = (bottomIndex.value + 1) % thumbnailBottom.value.length
        }
    }, 4000)
})

onUnmounted(() => {
    clearInterval(intervalTop)
    clearInterval(intervalBottom)
})

// Social media icons
function getMediaSosialComponent(platform) {
    // Map platform names to Lucide components
    const iconMap = {
        'Facebook': Facebook,
        'Instagram': Instagram,
        'LinkedIn': Linkedin,
        'Twitter': Twitter,
        'YouTube': Youtube,
        'TikTok': Music,
        'WhatsApp Business': MessageCircle,
        'Telegram': Send,
        'GitHub': Github
    };
    
    return iconMap[platform] || null;
}
</script>

<template>
    <AppLayout>
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-secondary text-white relative overflow-hidden">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col justify-start items-center gap-16 overflow-hidden relative z-10">
                <div class="w-full max-w-3xl flex flex-col justify-start items-center gap-6">
                    <div class="w-full flex flex-col justify-start items-center gap-4 text-center">
                        <h1 class="text-4xl lg:text-6xl font-normal font-custom leading-tight">
                            Haloo, kenalan dong!
                        </h1>
                        <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                            Ayo kita cari tahu lebih banyak tentang {{ profil_perusahaan?.nama_perusahaan }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Company Story with Enhanced Design -->
        <div class="w-full px-4 sm:px-8 lg:px-16 pb-20 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row gap-10 lg:gap-20">
                <!-- KOLOM LOGO + TAGLINE -->
                <div class="flex-1 flex flex-col justify-center items-center text-center gap-4 overflow-hidden">
                    <!-- LOGO PERUSAHAAN with enhanced presentation -->
                    <div class="mb-2 relative group">
                        <div class="absolute inset-0 bg-white/10 rounded-full scale-90 group-hover:scale-100 transition-all duration-300"></div>
                        <img :src="getImageUrl(profil_perusahaan?.logo_perusahaan)" alt="Logo Perusahaan"
                            class="w-80 h-80 object-contain relative z-10" />
                    </div>

                    <!-- TAGLINE with modern styling -->
                    <div class="text-sm lg:text-base font-semibold font-custom leading-normal px-4 py-1 bg-white/10 backdrop-blur-sm rounded-full">
                        Profil Perusahaan
                    </div>

                    <!-- TITLE with enhanced typography -->
                    <h2 class="text-3xl lg:text-5xl font-normal font-custom leading-tight">
                        {{ profil_perusahaan?.nama_perusahaan }}
                    </h2>
                </div>

                <!-- KOLOM TEKS with modern card styling -->
                <div class="flex-1 flex flex-col justify-center items-center gap-6">
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-2xl border border-white/10">
                        <!-- Use v-html for rich content display -->
                        <div 
                            v-if="profil_perusahaan?.deskripsi_perusahaan"
                            v-html="profil_perusahaan.deskripsi_perusahaan"
                            class="text-base lg:text-lg font-normal font-custom leading-relaxed prose prose-invert max-w-none"
                        ></div>
                        <p 
                            v-else
                            class="text-base lg:text-lg font-normal font-custom leading-relaxed"
                        >
                            Sejarah perusahaan belum tersedia.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Visi & Misi Grid with Petal Design -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-white text-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 max-w-screen-xl mx-auto">
                <!-- Visi - top-left petal -->
                <div class="order-1 flex flex-col justify-center items-start lg:items-end gap-4 bg-secondary py-8 px-10 
                     rounded-lg lg:rounded-tl-[100px] lg:rounded-bl-[100px] lg:rounded-tr-[100px] lg:rounded-br-[20px]
                     transform hover:translate-y-[-5px] transition-all duration-300">
                    <h3 class="text-2xl lg:text-4xl font-semibold font-custom lg:text-right">
                        Visi Kami
                    </h3>
                    <div class="text-base lg:text-lg font-normal font-custom leading-relaxed lg:text-right">
                        <!-- Strip HTML for preview, show plain text -->
                        <p>{{ truncatedVisi }}</p>
                        <Link v-if="showReadMoreVisi" href="/visi-misi" class="text-blue-400 cursor-pointer hover:underline">
                            ... Baca selengkapnya
                        </Link>
                    </div>
                </div>

                <!-- Gambar atas - top-right petal (Slider 1) -->
                <div class="order-2 overflow-hidden rounded-lg lg:rounded-tr-[100px] lg:rounded-tl-[100px] lg:rounded-br-[100px] lg:rounded-bl-[20px] h-96">
                    <div v-if="thumbnailTop.length" class="flex h-full transition-transform duration-700 ease-in-out"
                        :style="{ transform: `translateX(-${topIndex * 100}%)` }">
                        <div v-for="(img, i) in thumbnailTop" :key="'top-slide-' + i"
                            class="w-full h-full flex-shrink-0">
                            <img :src="getImageUrl(img)" class="w-full h-full object-cover" />
                        </div>
                    </div>
                    <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-600">
                        Gambar belum tersedia.
                    </div>
                </div>

                <!-- Gambar bawah - bottom-left petal (Slider 2) -->
                <div class="order-3 overflow-hidden rounded-lg lg:rounded-bl-[100px] lg:rounded-tl-[100px] lg:rounded-br-[100px] lg:rounded-tr-[20px] h-96">
                    <div v-if="thumbnailBottom.length" class="flex h-full transition-transform duration-700 ease-in-out"
                        :style="{ transform: `translateX(-${bottomIndex * 100}%)` }">
                        <div v-for="(img, i) in thumbnailBottom" :key="'bottom-slide-' + i"
                            class="w-full h-full flex-shrink-0">
                            <img :src="getImageUrl(img)" class="w-full h-full object-cover" />
                        </div>
                    </div>
                    <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-600">
                        Gambar belum tersedia.
                    </div>
                </div>

                <!-- Misi - bottom-right petal -->
                <div class="order-4 flex flex-col justify-center items-start gap-4 bg-secondary py-8 px-10
                     rounded-lg lg:rounded-br-[100px] lg:rounded-tr-[100px] lg:rounded-bl-[100px] lg:rounded-tl-[20px]
                     transform hover:translate-y-[-5px] transition-all duration-300">
                    <h3 class="text-2xl lg:text-4xl font-semibold font-custom">Misi Kami</h3>
                    <div class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        <!-- Strip HTML for preview, show plain text -->
                        <p>{{ truncatedMisi }}</p>
                        <Link v-if="showReadMoreMisi" href="/visi-misi" class="text-blue-400 cursor-pointer hover:underline">
                            ... Baca selengkapnya
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-16 bg-secondary text-white relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-bl-full"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-tr-full"></div>
            
            <div class="max-w-screen-xl mx-auto flex flex-col justify-center items-center gap-10 relative z-10">
                <!-- Modern heading with accent line -->
                <div class="text-center">
                    <h2 class="text-2xl lg:text-4xl font-semibold font-custom">
                        Ikuti Kami di Media Sosial
                    </h2>
                    <div class="w-16 h-1 bg-primary mx-auto mt-3"></div>
                </div>

                <!-- Social media icons - modern grid layout -->
                <div v-if="mediaSosial.length > 0" class="flex flex-wrap justify-center gap-6">
                    <a 
                        v-for="(platform, index) in mediaSosial" 
                        :key="index"
                        :href="platform.link" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="group flex items-center justify-center w-12 h-12 rounded-full bg-white/10 border border-white/20 
                               hover:bg-white hover:text-secondary hover:border-primary transition-all duration-300"
                        :title="platform.name"
                    >
                        <component 
                            :is="getMediaSosialComponent(platform.name)" 
                            class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" 
                        />
                    </a>
                </div>

                <!-- Call to action text -->
                <p class="text-sm lg:text-base font-normal font-custom leading-relaxed text-center max-w-2xl px-4 
                          bg-white/5 backdrop-blur-sm py-4 rounded-xl border border-white/10">
                    Bergabunglah dengan kami dan dapatkan update terbaru tentang perusahaan, produk, dan penawaran menarik lainnya.
                </p>
            </div>
        </div>

    </AppLayout>
</template>

<style scoped>
/* Ensure HTML content in description renders properly */
:deep(.prose) {
    max-width: none;
}

:deep(.prose h1) {
    @apply text-2xl font-bold text-white mt-6 mb-4;
}

:deep(.prose h2) {
    @apply text-xl font-bold text-white mt-6 mb-3;
}

:deep(.prose h3) {
    @apply text-lg font-semibold text-white mt-4 mb-3;
}

:deep(.prose p) {
    @apply mb-4 leading-relaxed text-white;
}

:deep(.prose ul) {
    @apply list-disc ml-6 mb-4;
}

:deep(.prose ol) {
    @apply list-decimal ml-6 mb-4;
}

:deep(.prose li) {
    @apply mb-2 text-white;
}

:deep(.prose blockquote) {
    @apply border-l-4 border-white/30 pl-4 italic my-4 bg-white/10 py-2 text-white;
}

:deep(.prose strong) {
    @apply font-semibold text-white;
}

:deep(.prose em) {
    @apply italic text-white;
}

:deep(.prose a) {
    @apply text-blue-300 hover:text-blue-100 underline;
}
</style>
