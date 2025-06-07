<template>
    <div class="relative font-custom antialiased">
        <main class="relative flex flex-col justify-center bg-secondary overflow-hidden">
            <div class="w-full max-w-5xl mx-auto px-4 md:px-6 py-24 text-center">

                <h2 class="text-2xl md:text-3xl font-bold text-white mb-10">
                    Ingin bermitra bersama kami?
                </h2>

                <div v-if="loading" class="text-white text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-white">
                    </div>
                    <p class="mt-2">Memuat daftar mitra...</p>
                </div>

                <div v-else-if="error" class="text-white text-center py-8">
                    <p>{{ error }}</p>
                </div>

                <div v-else class="w-full overflow-hidden"
                    style="mask-image: linear-gradient(to right, transparent 0%, black 20%, black 80%, transparent 100%)">
                    <div ref="carouselTrack" class="flex animate-carousel-infinite">
                        <div v-for="(item, index) in carouselItems" :key="index"
                            class="flex items-center justify-center space-x-16 min-w-max">
                            <img v-for="mitra in item.mitras" :key="mitra.uniqueId" :src="getImageUrl(mitra.logo)"
                                :alt="mitra.nama" class="h-12 max-w-none flex-shrink-0 mx-8" />
                            <div @click="openPartnerForm"
                                class="cursor-pointer h-12 w-24 border-2 border-dashed border-white/50 rounded flex items-center justify-center hover:border-white transition-colors duration-300 flex-shrink-0 mx-8 group">
                                <span
                                    class="text-white/70 group-hover:text-white text-xs md:text-sm font-medium transition-colors duration-300">
                                    Your Logo Here
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="mt-16 bg-white/10 rounded-lg p-6 backdrop-blur-sm max-w-2xl mx-auto">
                    <h3 class="text-xl font-semibold text-white mb-4">Bergabunglah dengan Partner Kami</h3>
                    <p class="text-white/80 mb-6">Perluas jangkauan bisnis Anda dengan bermitra bersama kami. Dapatkan
                        akses ke audiens yang lebih luas dan peluang kolaborasi yang menguntungkan.</p>
                    <button @click="openPartnerForm"
                        class="px-6 py-3 bg-primary text-white font-medium rounded-md hover:bg-primary/80 transition-colors duration-300">
                        Jadi Mitra Sekarang
                    </button>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import axios from 'axios';

const carouselTrack = ref(null);
const mitras = ref([]);
const loading = ref(true);
const error = ref(null);

// Create carousel items with proper duplication for seamless infinite scroll
const carouselItems = computed(() => {
    if (mitras.value.length === 0) {
        return [];
    }

    // Add unique IDs to prevent Vue key conflicts
    const processedMitras = mitras.value.map((mitra, index) => ({
        ...mitra,
        baseId: index
    }));

    // Calculate how many times we need to repeat the items
    // We want at least 8-10 logos visible at any time for smooth scrolling
    const minVisibleItems = 10;
    const totalRepeats = Math.max(3, Math.ceil(minVisibleItems / processedMitras.length));

    // Create multiple sets for truly seamless infinite scroll
    const carouselSets = [];

    // Create 3 identical sets (this ensures seamless loop)
    for (let setIndex = 0; setIndex < 3; setIndex++) {
        const setMitras = [];

        // Repeat the mitras within each set if needed
        for (let repeat = 0; repeat < totalRepeats; repeat++) {
            processedMitras.forEach(mitra => {
                setMitras.push({
                    ...mitra,
                    uniqueId: `set-${setIndex}-repeat-${repeat}-mitra-${mitra.baseId}`
                });
            });
        }

        carouselSets.push({
            setId: setIndex,
            mitras: setMitras
        });
    }

    return carouselSets;
});

// Handle image URLs properly
const getImageUrl = (path) => {
    if (!path) return '/images/placeholder-logo.png';

    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }

    if (path.startsWith('storage/')) {
        return '/' + path;
    }

    return '/storage/' + path;
};

const fetchMitras = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/mitra');
        mitras.value = response.data.data;
        error.value = null;
    } catch (err) {
        console.error('Error fetching mitra data:', err);
        error.value = 'Gagal memuat data mitra. Silahkan coba lagi nanti.';
    } finally {
        loading.value = false;
    }
};

// Calculate and set the proper animation duration
const setupAnimation = async () => {
    await nextTick(); // Wait for DOM to update

    if (!carouselTrack.value || carouselItems.value.length === 0) return;

    const trackElement = carouselTrack.value;
    const firstSet = trackElement.querySelector('div:first-child');

    if (!firstSet) return;

    // Get the width of one complete set
    const setWidth = firstSet.scrollWidth;

    // Calculate duration based on width (adjust speed here)
    // Slower = higher multiplier, faster = lower multiplier
    const speedMultiplier = 1; // Adjust this to change speed
    const duration = setWidth * speedMultiplier / 100;

    // Apply the calculated duration
    trackElement.style.setProperty('--scroll-width', `${setWidth}px`);
    trackElement.style.animationDuration = `${Math.max(duration, 15)}s`;
};

const openPartnerForm = () => {
    window.location.href = '/sejarah-perusahaan';
};

onMounted(async () => {
    await fetchMitras();

    // Setup animation after data is loaded
    if (mitras.value.length > 0) {
        setTimeout(setupAnimation, 100); // Small delay to ensure DOM is fully rendered
    }
});
</script>

<style scoped>
@keyframes carousel-infinite {
    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(calc(-1 * var(--scroll-width, 100%)));
    }
}

.animate-carousel-infinite {
    animation: carousel-infinite 30s linear infinite;
    display: flex;
}

/* Pause animation on hover for better UX */
.animate-carousel-infinite:hover {
    animation-play-state: paused;
}

.bg-primary {
    background-color: #3b82f6;
}

.bg-secondary {
    background-color: #1f2937;
}
</style>