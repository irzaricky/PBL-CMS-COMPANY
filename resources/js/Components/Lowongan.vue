<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const lowongan = ref([])
const loading = ref(false)
const error = ref(null)
const currentIndex = ref(0) // untuk slider gambar

onMounted(() => {
    fetchLowongan()
})

onMounted(() => {
    // Slider otomatis
    setInterval(() => {
        if (lowongan.value.length > 0) {
            currentIndex.value = (currentIndex.value + 1) % lowongan.value.length
        }
    }, 4000) // 4 detik slide
})

async function fetchLowongan() {
    try {
        loading.value = true
        const response = await axios.get('/api/lowongan')
        console.log(response.data)  // Cek apakah data yang diterima sudah benar
        const allData = response.data.data
        lowongan.value = getRandomItems(allData, 3)
    } catch (err) {
        error.value = 'Lowongan tidak ditemukan atau terjadi kesalahan.'
        console.error('Error fetching lowongan:', err)
    } finally {
        loading.value = false
    }
}

function getRandomItems(array, count) {
    const shuffled = [...array].sort(() => 0.5 - Math.random())
    return shuffled.slice(0, count)
}

function getImage(image) {
    if (!image) return '/image/placeholder.webp'
    if (typeof image === 'object' && image !== null) {
        return image[0] ? `/storage/${image[0]}` : '/image/placeholder.webp'
    }
    return `/storage/${image}`
}



function formatTanggal(tanggal) {
    const options = { day: '2-digit', month: 'long', year: 'numeric' }
    return new Date(tanggal).toLocaleDateString('id-ID', options)
}

function formatGaji(angka) {
    return parseInt(angka).toLocaleString('id-ID')
}
</script>

<template>
    <div
        class="w-full px-6 py-20 lg:px-16 lg:py-28 bg-Color-Scheme-1-Background flex flex-col gap-20 items-center font-custom">
        <!-- Header -->
        <div class="w-full max-w-2xl flex flex-col gap-4 items-center text-center">
            <h4 class="text-base font-semibold text-Color-Scheme-1-Text">Tagline</h4>
            <h2 class="text-5xl font-normal text-Color-Scheme-1-Text leading-[1.2]">Open Positions</h2>
            <p class="text-lg text-Color-Scheme-1-Text leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum
                tristique.
            </p>
        </div>

        <!-- Content -->
        <div class="flex flex-col lg:flex-row w-full max-w-7xl gap-12">
            <!-- List Lowongan -->
            <div class="flex-1 flex flex-col gap-12">
                <div v-for="lowongan in lowongan" :key="lowongan.id"
                    class="border-t border-Color-Scheme-1-Border/20 pt-8 flex flex-col gap-6">
                    <!-- Title & Department -->
                    <div class="flex flex-wrap items-center gap-4">
                        <h3 class="text-2xl text-Color-Scheme-1-Text">{{ lowongan.judul_lowongan }}</h3>
                        <span
                            class="px-2.5 border py-1 bg-Opacity-Neutral-Darkest-5/5 rounded-full text-sm font-semibold text-Color-Neutral-Darkest">
                            {{ lowongan.jenis_lowongan }}
                        </span>
                    </div>

                    <!-- Deskripsi -->
                    <p class="text-base text-Color-Scheme-1-Text">
                        {{ lowongan.deskripsi_pekerjaan }}
                    </p>

                    <!-- Info -->
                    <div class="flex flex-wrap gap-6">
                        <div class="flex items-center gap-3">
                            <AlarmClock class="w-6 h-6 text-Color-Scheme-1-Text" />
                            <span class="text-lg text-Color-Scheme-1-Text">Ditutup pada {{ formatTanggal(lowongan.tanggal_ditutup)
                                }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <Wallet class="w-6 h-6 text-Color-Scheme-1-Text" />
                            <span class="text-lg text-Color-Scheme-1-Text">Rp.{{ formatGaji(lowongan.gaji) }}</span>
                        </div>
                    </div>

                    <!-- Tombol Apply -->
                    <a :href="`/lowongan/${lowongan.slug}`"
                        class="inline-block w-fit px-5 py-2 bg-Opacity-Neutral-Darkest-5/5 rounded-full text-base font-medium text-Color-Neutral-Darkest">
                        Apply Now
                    </a>
                </div>
            </div>

            <!-- Gambar -->
            <div class="flex-1 max-w-full">
                <div class="relative h-full w-full aspect-[1/1] overflow-hidden rounded-2xl">
                    <div class="flex transition-transform duration-700 ease-in-out h-full"
                        :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                        <div v-for="(item, index) in lowongan" :key="'lowongan-slide-' + index"
                            class="w-full h-full flex-shrink-0 bg-cover bg-center"
                            :style="{ backgroundImage: `url(${getImage(item.thumbnail_lowongan)})` }">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
