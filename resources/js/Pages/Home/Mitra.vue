<template>
    <div class="relative font-custom antialiased">
        <main class="relative flex flex-col justify-center bg-secondary overflow-hidden">
            <div class="w-full max-w-6xl mx-auto px-4 md:px-6 py-24">
                
                <!-- Header Section -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Ingin bermitra bersama kami?
                    </h2>
                    <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                        Bergabunglah dengan mitra-mitra terpercaya yang telah bekerja sama dengan kami
                    </p>
                </div>

                <!-- Mitra Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8 mb-12">
                    <!-- Existing Mitra -->
                    <div v-for="mitra in mitraList" :key="mitra.nama" 
                         class="group flex items-center justify-center p-6 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <img 
                            :src="getImageUrl(mitra.logo)" 
                            :alt="mitra.nama"
                            :title="mitra.nama"
                            class="h-12 w-auto max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300"
                            @error="handleImageError"
                        />
                    </div>
                    
                    <!-- Call to Action Card -->
                    <div class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-primary/20 to-accent/20 backdrop-blur-sm rounded-2xl border-2 border-dashed border-primary/40 hover:border-primary/60 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-full bg-primary/20 group-hover:bg-primary/30 transition-colors duration-300">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white/80 group-hover:text-white transition-colors duration-300">
                                Logo anda di sini?
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bottom CTA Section -->
                <div class="text-center">
                    <div class="inline-flex items-center space-x-4 px-8 py-4 bg-gradient-to-r from-primary/20 to-accent/20 backdrop-blur-sm rounded-full border border-white/10">
                        <span class="text-white/80">Tertarik untuk bermitra dengan kami?</span>
                        <button class="px-6 py-2 bg-white text-secondary hover:bg-black hover:text-white font-medium rounded-full transition-colors duration-300">
                            Hubungi Kami
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const mitraList = ref([])
const isLoading = ref(true)

// Fetch mitra data from API
const fetchMitraData = async () => {
    try {
        const response = await axios.get('/api/mitra')
        mitraList.value = response.data.data || response.data
        isLoading.value = false
    } catch (error) {
        console.error('Error fetching mitra data:', error)
        isLoading.value = false
        // Fallback to default logos if API fails
        mitraList.value = [
            { nama: 'Facebook', logo: 'facebook.svg' },
            { nama: 'Disney', logo: 'disney.svg' },
            { nama: 'Airbnb', logo: 'airbnb.svg' },
            { nama: 'Apple', logo: 'apple.svg' },
            { nama: 'Spark', logo: 'spark.svg' },
            { nama: 'Samsung', logo: 'samsung.svg' },
            { nama: 'Quora', logo: 'quora.svg' },
            { nama: 'Sass', logo: 'sass.svg' }
        ]
    }
}

// Handle image loading errors
const handleImageError = (event) => {
    console.warn('Failed to load image:', event.target.src)
    event.target.src = '/image/placeholder.webp'
}

// Function to get proper image URL
function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

onMounted(async () => {
    await fetchMitraData()
})
</script>

<style scoped>
/* Custom hover effects */
.group:hover {
    transform: translateY(-2px);
}

/* Smooth animations */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>