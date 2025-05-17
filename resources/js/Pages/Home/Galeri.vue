<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'

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

		leftColumn.value = galleries.value.filter((_, i) => i % 2 === 0)
		rightColumn.value = galleries.value.filter((_, i) => i % 2 !== 0)

		isLoaded.value = true
	} catch (err) {
		console.error('Gagal mengambil data galeri:', err)
	}
}

onMounted(() => {
	fetchGaleri()

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
	if (!image) return "/image/placeholder.webp"
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
		<div class="w-full max-w-[768px] flex flex-col items-center gap-6 text-center">
			<h2 class="text-Color-Scheme-1-Text text-4xl lg:text-5xl font-normal leading-tight">Image Gallery</h2>
			<p class="text-Color-Scheme-1-Text text-base lg:text-lg leading-relaxed">Lorem ipsum dolor sit amet,
				consectetur adipiscing elit.</p>
		</div>

		<!-- Kontainer Galeri -->
		<div class="flex flex-col lg:flex-row gap-8 justify-center w-full">

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
