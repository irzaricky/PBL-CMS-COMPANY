<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'
import AOS from 'aos'
import 'aos/dist/aos.css'

const galleries = ref([])

const leftColumn = ref([])
const rightColumn = ref([])

const currentIndexLeftTop = ref(0)
const currentIndexLeftBottom = ref(0)
const currentIndexRightTop = ref(0)
const currentIndexRightBottom = ref(0)

let intervalLeftTop, intervalLeftBottom, intervalRightTop, intervalRightBottom

const isLoaded = ref(false)

const fetchGaleri = async () => {
	try {
		const response = await axios.get('/api/galeri')
		galleries.value = response.data.data || []

		if (galleries.value.length === 0) {
			const placeholders = [
				{
					id_galeri: 'placeholder-1',
					judul_galeri: 'Galeri akan segera hadir',
					deskripsi_galeri: 'Tim kami sedang mempersiapkan konten galeri yang menarik untuk Anda.',
					thumbnail_galeri: null,
					slug: '#'
				},
				{
					id_galeri: 'placeholder-2',
					judul_galeri: 'Galeri akan segera hadir',
					deskripsi_galeri: 'Tim kami sedang mempersiapkan konten galeri yang menarik untuk Anda.',
					thumbnail_galeri: null,
					slug: '#'
				}
			]
			galleries.value = placeholders
		}

		leftColumn.value = galleries.value.filter((_, i) => i % 2 === 0)
		rightColumn.value = galleries.value.filter((_, i) => i % 2 !== 0)

		// Ensure at least one item in each column
		if (leftColumn.value.length === 0) leftColumn.value = [galleries.value[0]]
		if (rightColumn.value.length === 0) rightColumn.value = [galleries.value[0]]

		isLoaded.value = true
	} catch (err) {
		console.error('Gagal mengambil data galeri:', err)
		// Add fallback on error
		handleEmptyGallery()
	}
}
function handleEmptyGallery() {
    const placeholders = [
        {
            id_galeri: 'error-1',
            judul_galeri: 'Konten galeri tidak tersedia',
            deskripsi_galeri: 'Silakan coba lagi nanti.',
            thumbnail_galeri: null,
            slug: '#'
        },
        {
            id_galeri: 'error-2',
            judul_galeri: 'Konten galeri tidak tersedia',
            deskripsi_galeri: 'Silakan coba lagi nanti.',
            thumbnail_galeri: null,
            slug: '#'
        }
    ]
    galleries.value = placeholders
    leftColumn.value = [placeholders[0]]
    rightColumn.value = [placeholders[1]]
    isLoaded.value = true
}

onMounted(() => {
	fetchGaleri()
	AOS.init({
		duration: 1000,
		once: false
	})

	intervalLeftTop = setInterval(() => {
		currentIndexLeftTop.value = (currentIndexLeftTop.value + 1) % (leftColumn.value.length || 1)
	}, 4000)

	intervalLeftBottom = setInterval(() => {
		currentIndexLeftBottom.value = (currentIndexLeftBottom.value + 1) % (leftColumn.value.length || 1)
	}, 5000)

	intervalRightTop = setInterval(() => {
		currentIndexRightTop.value = (currentIndexRightTop.value + 1) % (rightColumn.value.length || 1)
	}, 6000)

	intervalRightBottom = setInterval(() => {
		currentIndexRightBottom.value = (currentIndexRightBottom.value + 1) % (rightColumn.value.length || 1)
	}, 7000)
})

onBeforeUnmount(() => {
	clearInterval(intervalLeftTop)
	clearInterval(intervalLeftBottom)
	clearInterval(intervalRightTop)
	clearInterval(intervalRightBottom)
})

function getImage(image) {
    if (!image) {
        return "/image/placeholder.webp"
    }
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp"
    }
    return `/storage/${image}`
}
</script>

<template>
	<div
		class="w-full max-w-[1440px] mx-auto px-4 md:px-10 lg:px-16 py-28 flex flex-col items-center gap-20 overflow-x-hidden font-custom">

		<!-- Judul -->
		<div class="w-full max-w-[768px] flex flex-col items-center gap-6 text-center" data-aos="fade-up">
			<h2 class="text-Color-Scheme-1-Text text-4xl lg:text-5xl font-normal leading-tight">Cerita dalam galeri</h2>
			<p class="text-Color-Scheme-1-Text text-base lg:text-lg leading-relaxed">Setiap foto punya cerita. Yuk,
				lihat keseruan dan kebersamaan tim kami dari balik lensa.</p>
		</div>

		<!-- Kontainer Galeri -->
		<div class="flex flex-col lg:flex-row gap-8 justify-center w-full" data-aos="zoom-in">

			<!-- Kolom Kiri -->
			<div class="flex flex-col gap-8 items-center lg:items-start w-full lg:w-1/2">

				<!-- Kiri Atas -->
				<div class="relative w-full aspect-[1/1] overflow-hidden rounded-2xl">
					<div class="flex transition-transform duration-700 ease-in-out"
						:style="{ transform: `translateX(-${currentIndexLeftTop * 100}%)` }">

						<div v-for="(item, index) in leftColumn" :key="'left1-' + index"
							class="w-full aspect-[1/1] flex-shrink-0 bg-cover bg-center relative group"
							:style="{ backgroundImage: `url(${getImage(item.thumbnail_galeri)})` }">

							<!-- Overlay -->
							<div
								class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

							<!-- Konten -->
							<div
								class="absolute bottom-4 left-4 right-4 z-20 text-white flex flex-col gap-1 opacity-0 translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
								<div class="text-4xl lg:text-4xl font-thin pb-1">{{ item.judul_galeri }}</div>
								<div class="text-sm font-light pb-1 leading-snug line-clamp-2">{{ item.deskripsi_galeri
								}}</div>
								<a :href="`/galeri/${item.slug}`"
									class="flex items-center gap-2 text-white hover:underline">
									Lihat Selengkapnya
									<ChevronRight class="w-3" />
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Kiri Bawah -->
				<div class="relative w-full aspect-[5/3] overflow-hidden rounded-2xl">
					<div class="flex transition-transform duration-700 ease-in-out"
						:style="{ transform: `translateX(-${currentIndexLeftBottom * 100}%)` }">

						<div v-for="(item, index) in leftColumn" :key="'left2-' + index"
							class="w-full aspect-[5/3] flex-shrink-0 bg-cover bg-center relative group"
							:style="{ backgroundImage: `url(${getImage(item.thumbnail_galeri)})` }">

							<!-- Overlay -->
							<div
								class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

							<!-- Konten -->
							<div
								class="absolute bottom-4 left-4 right-4 z-20 text-white flex flex-col gap-1 opacity-0 translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
								<div class="text-4xl lg:text-4xl font-thin pb-1">{{ item.judul_galeri }}</div>
								<div class="text-sm font-light pb-1 leading-snug line-clamp-2">{{ item.deskripsi_galeri
								}}</div>
								<a :href="`/galeri/${item.slug}`"
									class="flex items-center gap-2 text-white hover:underline">
									Lihat Selengkapnya
									<ChevronRight class="w-3" />
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Kolom Kanan -->
			<div class="flex flex-col gap-8 items-center lg:items-start w-full lg:w-1/2">

				<!-- Kanan Atas -->
				<div class="relative w-full aspect-[5/3] overflow-hidden rounded-2xl">
					<div class="flex transition-transform duration-700 ease-in-out"
						:style="{ transform: `translateX(-${currentIndexRightTop * 100}%)` }">

						<div v-for="(item, index) in rightColumn" :key="'right1-' + index"
							class="w-full aspect-[5/3] flex-shrink-0 bg-cover bg-center relative group"
							:style="{ backgroundImage: `url(${getImage(item.thumbnail_galeri)})` }">

							<!-- Overlay -->
							<div
								class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

							<!-- Konten -->
							<div
								class="absolute bottom-4 left-4 right-4 z-20 text-white flex flex-col gap-1 opacity-0 translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
								<div class="text-4xl lg:text-4xl font-thin pb-1">{{ item.judul_galeri }}</div>
								<div class="text-sm font-light pb-1 leading-snug line-clamp-2">{{ item.deskripsi_galeri
								}}</div>
								<a :href="`/galeri/${item.slug}`"
									class="flex items-center gap-2 text-white hover:underline">
									Lihat Selengkapnya
									<ChevronRight class="w-3" />
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Kanan Bawah -->
				<div class="relative w-full aspect-[1/1] overflow-hidden rounded-2xl">
					<div class="flex transition-transform duration-700 ease-in-out"
						:style="{ transform: `translateX(-${currentIndexRightBottom * 100}%)` }">

						<div v-for="(item, index) in rightColumn" :key="'right2-' + index"
							class="w-full aspect-[1/1] flex-shrink-0 bg-cover bg-center relative group"
							:style="{ backgroundImage: `url(${getImage(item.thumbnail_galeri)})` }">

							<!-- Overlay -->
							<div
								class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

							<!-- Konten -->
							<div
								class="absolute bottom-4 left-4 right-4 z-20 text-white flex flex-col gap-1 opacity-0 translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
								<div class="text-4xl lg:text-4xl font-thin pb-1">{{ item.judul_galeri }}</div>
								<div class="text-sm font-light pb-1 leading-snug line-clamp-2">{{ item.deskripsi_galeri
								}}</div>
								<a :href="`/galeri/${item.slug}`"
									class="flex items-center gap-2 text-white hover:underline">
									Lihat Selengkapnya
									<ChevronRight class="w-3" />
								</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</template>
