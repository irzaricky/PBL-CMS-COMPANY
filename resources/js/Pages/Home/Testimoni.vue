<template>
    <div class="w-full px-4 sm:px-8 lg:px-16 py-16 sm:py-20 lg:py-28 bg-white font-custom">
        <div class="max-w-screen-xl mx-auto">
            <!-- Header Section -->
            <div class="w-full mb-12 lg:mb-16">
                <div class="max-w-2xl">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-normal leading-tight mb-4">
                        Kumpulan Testimoni
                    </h2>
                    <p class="text-base sm:text-lg font-normal leading-relaxed text-gray-600">
                        Apa kata mereka tentang layanan kami
                    </p>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div class="w-full">
                <!-- Loading State -->
                <div v-if="loading"
                    class="w-full p-8 lg:p-12 rounded-2xl border border-gray-200 flex justify-center items-center">
                    <div class="text-lg font-normal">Loading testimonials...</div>
                </div>

                <!-- Error State -->
                <div v-else-if="error"
                    class="w-full p-8 lg:p-12 rounded-2xl border border-red-200 bg-red-50 flex justify-center items-center">
                    <div class="text-lg font-normal text-red-600">{{ error }}</div>
                </div>

                <!-- Testimonials Container -->
                <div v-else class="w-full">
                    <!-- Slider Container -->
                    <div class="relative overflow-hidden rounded-2xl">
                        <div class="flex transition-transform duration-500 ease-in-out"
                            :style="{ transform: `translateX(-${currentSlide * 100}%)` }">

                            <!-- Testimoni Artikel -->
                            <div class="w-full flex-shrink-0">
                                <div
                                    class="p-6 sm:p-8 lg:p-10 border border-gray-200 bg-white h-full flex flex-col gap-6">
                                    <!-- Header Badge -->
                                    <div class="inline-flex w-fit px-4 py-2 bg-blue-50 rounded-lg">
                                        <span class="text-sm font-semibold text-blue-700">ARTIKEL</span>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 flex flex-col gap-6">
                                        <div v-if="testimoniData.isi_testimoni_artikel" class="space-y-6">
                                            <!-- Testimonial Text -->
                                            <blockquote
                                                class="text-lg lg:text-xl font-normal leading-relaxed text-gray-800">
                                                "{{ testimoniData.isi_testimoni_artikel }}"
                                            </blockquote>

                                            <!-- Rating -->
                                            <div class="flex items-center gap-1">
                                                <Star v-for="i in 5" :key="i" :class="[
                                                    'w-5 h-5',
                                                    i <= testimoniData.rating_artikel ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'
                                                ]" />
                                            </div>

                                            <!-- User Info -->
                                            <div class="flex items-center gap-4">
                                                <img class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100"
                                                    :src="testimoniData.user_artikel?.foto_profil ? `/storage/${testimoniData.user_artikel.foto_profil}` : 'https://placehold.co/48x48'"
                                                    :alt="testimoniData.user_artikel?.name || 'User'" />
                                                <div>
                                                    <div class="font-semibold text-gray-900">
                                                        {{ testimoniData.user_artikel?.name || 'Anonim' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ formatDate(testimoniData.created_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else class="flex-1 flex items-center justify-center py-12">
                                            <div class="text-center text-gray-500">
                                                <div class="text-lg mb-2">Belum ada testimoni artikel</div>
                                                <div class="text-sm">Testimoni akan muncul di sini</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div v-if="testimoniData.isi_testimoni_artikel && testimoniData.artikel_slug"
                                        class="pt-4 border-t border-gray-100">
                                        <Link :href="getTestimoniLink('artikel')"
                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium transition-colors">
                                        <span>Lihat artikel yang dikomentari</span>
                                        <ArrowRight class="w-4 h-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimoni Produk -->
                            <div class="w-full flex-shrink-0">
                                <div
                                    class="p-6 sm:p-8 lg:p-10 border border-gray-200 bg-white h-full flex flex-col gap-6">
                                    <div class="inline-flex w-fit px-4 py-2 bg-green-50 rounded-lg">
                                        <span class="text-sm font-semibold text-green-700">PRODUK</span>
                                    </div>

                                    <div class="flex-1 flex flex-col gap-6">
                                        <div v-if="testimoniData.isi_testimoni_produk" class="space-y-6">
                                            <blockquote
                                                class="text-lg lg:text-xl font-normal leading-relaxed text-gray-800">
                                                "{{ testimoniData.isi_testimoni_produk }}"
                                            </blockquote>

                                            <div class="flex items-center gap-1">
                                                <Star v-for="i in 5" :key="i" :class="[
                                                    'w-5 h-5',
                                                    i <= testimoniData.rating_produk ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'
                                                ]" />
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <img class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100"
                                                    :src="testimoniData.user_produk?.foto_profil ? `/storage/${testimoniData.user_produk.foto_profil}` : 'https://placehold.co/48x48'"
                                                    :alt="testimoniData.user_produk?.name || 'User'" />
                                                <div>
                                                    <div class="font-semibold text-gray-900">
                                                        {{ testimoniData.user_produk?.name || 'Anonim' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ formatDate(testimoniData.created_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else class="flex-1 flex items-center justify-center py-12">
                                            <div class="text-center text-gray-500">
                                                <div class="text-lg mb-2">Belum ada testimoni produk</div>
                                                <div class="text-sm">Testimoni akan muncul di sini</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="testimoniData.isi_testimoni_produk && testimoniData.produk_slug"
                                        class="pt-4 border-t border-gray-100">
                                        <Link :href="getTestimoniLink('produk')"
                                            class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium transition-colors">
                                        <span>Lihat produk yang dikomentari</span>
                                        <ArrowRight class="w-4 h-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimoni Event -->
                            <div class="w-full flex-shrink-0">
                                <div
                                    class="p-6 sm:p-8 lg:p-10 border border-gray-200 bg-white h-full flex flex-col gap-6">
                                    <div class="inline-flex w-fit px-4 py-2 bg-purple-50 rounded-lg">
                                        <span class="text-sm font-semibold text-purple-700">EVENT</span>
                                    </div>

                                    <div class="flex-1 flex flex-col gap-6">
                                        <div v-if="testimoniData.isi_testimoni_event" class="space-y-6">
                                            <blockquote
                                                class="text-lg lg:text-xl font-normal leading-relaxed text-gray-800">
                                                "{{ testimoniData.isi_testimoni_event }}"
                                            </blockquote>

                                            <div class="flex items-center gap-1">
                                                <Star v-for="i in 5" :key="i" :class="[
                                                    'w-5 h-5',
                                                    i <= testimoniData.rating_event ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'
                                                ]" />
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <img class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100"
                                                    :src="testimoniData.user_event?.foto_profil ? `/storage/${testimoniData.user_event.foto_profil}` : 'https://placehold.co/48x48'"
                                                    :alt="testimoniData.user_event?.name || 'User'" />
                                                <div>
                                                    <div class="font-semibold text-gray-900">
                                                        {{ testimoniData.user_event?.name || 'Anonim' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ formatDate(testimoniData.created_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else class="flex-1 flex items-center justify-center py-12">
                                            <div class="text-center text-gray-500">
                                                <div class="text-lg mb-2">Belum ada testimoni event</div>
                                                <div class="text-sm">Testimoni akan muncul di sini</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="testimoniData.isi_testimoni_event && testimoniData.event_slug"
                                        class="pt-4 border-t border-gray-100">
                                        <Link :href="getTestimoniLink('event')"
                                            class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-medium transition-colors">
                                        <span>Lihat event yang dikomentari</span>
                                        <ArrowRight class="w-4 h-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimoni Lowongan -->
                            <div class="w-full flex-shrink-0">
                                <div
                                    class="p-6 sm:p-8 lg:p-10 border border-gray-200 bg-white h-full flex flex-col gap-6">
                                    <div class="inline-flex w-fit px-4 py-2 bg-orange-50 rounded-lg">
                                        <span class="text-sm font-semibold text-orange-700">LOWONGAN</span>
                                    </div>

                                    <div class="flex-1 flex flex-col gap-6">
                                        <div v-if="testimoniData.isi_testimoni_lowongan" class="space-y-6">
                                            <blockquote
                                                class="text-lg lg:text-xl font-normal leading-relaxed text-gray-800">
                                                "{{ testimoniData.isi_testimoni_lowongan }}"
                                            </blockquote>

                                            <div class="flex items-center gap-1">
                                                <Star v-for="i in 5" :key="i" :class="[
                                                    'w-5 h-5',
                                                    i <= testimoniData.rating_lowongan ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'
                                                ]" />
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <img class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100"
                                                    :src="testimoniData.user_lowongan?.foto_profil ? `/storage/${testimoniData.user_lowongan.foto_profil}` : 'https://placehold.co/48x48'"
                                                    :alt="testimoniData.user_lowongan?.name || 'User'" />
                                                <div>
                                                    <div class="font-semibold text-gray-900">
                                                        {{ testimoniData.user_lowongan?.name || 'Anonim' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ formatDate(testimoniData.created_at) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else class="flex-1 flex items-center justify-center py-12">
                                            <div class="text-center text-gray-500">
                                                <div class="text-lg mb-2">Belum ada testimoni lowongan</div>
                                                <div class="text-sm">Testimoni akan muncul di sini</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="testimoniData.isi_testimoni_lowongan && testimoniData.lowongan_slug"
                                        class="pt-4 border-t border-gray-100">
                                        <Link :href="getTestimoniLink('lowongan')"
                                            class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 font-medium transition-colors">
                                        <span>Lihat lowongan yang dikomentari</span>
                                        <ArrowRight class="w-4 h-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div v-if="totalSlides > 1" class="flex items-center justify-between mt-8">
                        <!-- Dots Indicator -->
                        <div class="flex items-center gap-2">
                            <button v-for="(_, index) in totalSlides" :key="index" @click="goToSlide(index)" :class="[
                                'w-3 h-3 rounded-full transition-all duration-200',
                                currentSlide === index ? 'bg-blue-600 scale-110' : 'bg-gray-300 hover:bg-gray-400'
                            ]" />
                        </div>

                        <!-- Arrow Navigation -->
                        <div class="flex items-center gap-3">
                            <button @click="prevSlide"
                                class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <ChevronLeft class="w-5 h-5" />
                            </button>
                            <button @click="nextSlide"
                                class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <ChevronRight class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { ArrowRight, ChevronLeft, ChevronRight, Star } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'

// Reactive data
const currentSlide = ref(0)
const loading = ref(true)
const error = ref(null)
const testimoniData = ref({})

// Computed properties
const availableSlides = computed(() => {
    const slides = []
    if (testimoniData.value.available_types) {
        testimoniData.value.available_types.forEach((type, index) => {
            slides.push(index)
        })
    }
    // If no available types, show all 4 slides for navigation consistency
    return slides.length > 0 ? slides : [0, 1, 2, 3]
})

const totalSlides = computed(() => availableSlides.value.length)

// Methods
const nextSlide = () => {
    if (totalSlides.value > 0) {
        currentSlide.value = (currentSlide.value + 1) % totalSlides.value
    }
}

const prevSlide = () => {
    if (totalSlides.value > 0) {
        currentSlide.value = currentSlide.value === 0 ? totalSlides.value - 1 : currentSlide.value - 1
    }
}

const goToSlide = (index) => {
    currentSlide.value = index
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }
    return new Date(dateString).toLocaleDateString('id-ID', options)
}

const getTestimoniLink = (type) => {
    let slug = null
    let routeName = null

    switch (type) {
        case 'artikel':
            slug = testimoniData.value.artikel_slug
            routeName = 'artikel.show'
            break
        case 'produk':
            slug = testimoniData.value.produk_slug
            routeName = 'produk.show'
            break
        case 'event':
            slug = testimoniData.value.event_slug
            routeName = 'event.show'
            break
        case 'lowongan':
            slug = testimoniData.value.lowongan_slug
            routeName = 'lowongan.show'
            break
    }

    if (slug && routeName) {
        try {
            return route(routeName, { slug })
        } catch (error) {
            return `/${type}/${slug}`
        }
    }

    return null
}

// Fetch testimonials from API
const fetchTestimonials = async () => {
    try {
        loading.value = true
        error.value = null

        const response = await axios.get('/api/testimoni')

        if (response.data.status === 'success') {
            testimoniData.value = response.data.data
            console.log('Testimoni data loaded:', testimoniData.value)
        } else {
            throw new Error('Failed to fetch testimonials')
        }
    } catch (err) {
        error.value = 'Gagal memuat testimoni. Silakan coba lagi.'
        console.error('Error fetching testimonials:', err)
    } finally {
        loading.value = false
    }
}

// Lifecycle
onMounted(() => {
    fetchTestimonials()

    // Auto-slide every 5 seconds
    setInterval(() => {
        if (!loading.value && !error.value && totalSlides.value > 1) {
            nextSlide()
        }
    }, 5000)
})
</script>