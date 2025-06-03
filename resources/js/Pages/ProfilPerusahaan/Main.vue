<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'

// Data
const profil_perusahaan = ref(null)
const loading = ref(false)
const error = ref(null)

// Jumlah kalimat
const maxKalimat = 1

// Visi
const truncatedVisi = computed(() => {
    if (!profil_perusahaan.value?.visi_perusahaan) return 'Visi perusahaan belum tersedia.'
    const kalimat = profil_perusahaan.value.visi_perusahaan.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})
const showReadMoreVisi = computed(() => {
    if (!profil_perusahaan.value?.visi_perusahaan) return false
    return profil_perusahaan.value.visi_perusahaan.split(/(?<=[.!?])\s+/).length > maxKalimat
})

// Misi
const truncatedMisi = computed(() => {
    if (!profil_perusahaan.value?.misi_perusahaan) return 'Misi perusahaan belum tersedia.'
    const kalimat = profil_perusahaan.value.misi_perusahaan.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})
const showReadMoreMisi = computed(() => {
    if (!profil_perusahaan.value?.misi_perusahaan) return false
    return profil_perusahaan.value.misi_perusahaan.split(/(?<=[.!?])\s+/).length > maxKalimat
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
            
            <!-- Modern background elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-bl-full"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-tr-full"></div>
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
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-2xl border border-white/10 shadow-lg">
                        <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                            {{ profil_perusahaan?.deskripsi_perusahaan
                                ? profil_perusahaan.deskripsi_perusahaan
                                : 'Sejarah perusahaan belum tersedia.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Visi & Misi Grid with Petal Design -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-white text-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 max-w-screen-xl mx-auto">
                <!-- Visi - top-left petal -->
                <div class="order-1 flex flex-col justify-center items-start lg:items-end gap-4 bg-secondary py-8 px-10 shadow-lg 
                     rounded-lg lg:rounded-tl-[100px] lg:rounded-bl-[100px] lg:rounded-tr-[100px] lg:rounded-br-[20px]
                     transform hover:translate-y-[-5px] transition-all duration-300">
                    <h3 class="text-2xl lg:text-4xl font-semibold font-custom lg:text-right">
                        Visi Kami
                    </h3>
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed lg:text-right">
                        {{ profil_perusahaan?.visi_perusahaan
                            ? truncatedVisi
                            : 'Visi perusahaan belum tersedia.' }}
                        <Link v-if="showReadMoreVisi" href="/visi-misi" class="text-blue-400 cursor-pointer hover:underline">
                        ... Baca selengkapnya
                        </Link>
                    </p>
                </div>

                <!-- Gambar atas - top-right petal (Slider 1) -->
                <div class="order-2 overflow-hidden shadow-lg rounded-lg lg:rounded-tr-[100px] lg:rounded-tl-[100px] lg:rounded-br-[100px] lg:rounded-bl-[20px] h-96">
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
                <div class="order-3 overflow-hidden shadow-lg rounded-lg lg:rounded-bl-[100px] lg:rounded-tl-[100px] lg:rounded-br-[100px] lg:rounded-tr-[20px] h-96">
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
                <div class="order-4 flex flex-col justify-center items-start gap-4 bg-secondary py-8 px-10 shadow-lg
                     rounded-lg lg:rounded-br-[100px] lg:rounded-tr-[100px] lg:rounded-bl-[100px] lg:rounded-tl-[20px]
                     transform hover:translate-y-[-5px] transition-all duration-300">
                    <h3 class="text-2xl lg:text-4xl font-semibold font-custom">Misi Kami</h3>
                    <div class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        <div v-if="profil_perusahaan?.misi_perusahaan" v-html="truncatedMisi"
                            class="prose prose-invert"></div>
                        <p v-else>Misi perusahaan belum tersedia.</p>

                        <Link v-if="showReadMoreMisi" href="/visi-misi" class="text-blue-400 cursor-pointer hover:underline">
                        ... Baca selengkapnya
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional decorative element at the bottom -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-10 bg-secondary">
            <div class="max-w-screen-xl mx-auto flex justify-center">
                <div class="w-24 h-1 bg-white/20 rounded-full"></div>
            </div>
        </div>

    </AppLayout>
</template>
