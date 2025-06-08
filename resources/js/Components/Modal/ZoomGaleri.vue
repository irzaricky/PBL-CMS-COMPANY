<template>
    <!-- Full Screen Image Modal -->
    <div
        v-if="show"
        class="fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4"
        @click="handleClose"
    >
        <div class="relative w-full h-full flex items-center justify-center">
            <!-- Close Button -->
            <button
                @click="handleClose"
                class="absolute top-4 right-4 z-10 p-2 bg-white/10 rounded-full text-white hover:bg-white/20 transition-colors backdrop-blur-sm"
            >
                <X class="w-6 h-6" /></button
            ><!-- Navigation Buttons -->
            <div
                v-if="images && images.length > 1"
                class="absolute inset-0 flex items-center justify-between px-4 z-20"
            >
                <button
                    @click.stop="handlePrev"
                    class="p-3 bg-white/20 hover:bg-white/30 rounded-full text-white transition-all duration-200 backdrop-blur-sm shadow-lg"
                    title="Gambar sebelumnya"
                >
                    <ChevronRight class="w-6 h-6 rotate-180" />
                </button>
                <button
                    @click.stop="handleNext"
                    class="p-3 bg-white/20 hover:bg-white/30 rounded-full text-white transition-all duration-200 backdrop-blur-sm shadow-lg"
                    title="Gambar selanjutnya"
                >
                    <ChevronRight class="w-6 h-6" />
                </button>
            </div>
            <!-- Image Container with proper aspect ratio preservation -->
            <div
                class="relative w-full h-full flex items-center justify-center"
            >
                <!-- Loading Indicator -->
                <div
                    v-if="imageLoading"
                    class="absolute inset-0 flex items-center justify-center z-10"
                >
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"
                    ></div>
                </div>

                <!-- Error State -->
                <div
                    v-else-if="imageError"
                    class="absolute inset-0 flex items-center justify-center text-white text-center p-4 z-10"
                >
                    <div>
                        <div class="text-4xl mb-2">⚠️</div>
                        <p class="text-lg">Gagal memuat gambar</p>
                        <p class="text-sm opacity-75">Silakan coba lagi</p>
                    </div>
                </div>

                <!-- Main Image with preserved aspect ratio -->
                <img
                    v-show="!imageLoading && !imageError"
                    :src="currentImageUrl"
                    :alt="galleryTitle"
                    class="select-none transition-opacity duration-300"
                    :style="imageStyles"
                    @click.stop
                    @dragstart.prevent
                    @load="onImageLoad"
                    @error="onImageError"
                />
            </div>

            <!-- Image Counter -->
            <div
                v-if="images && images.length > 1"
                class="absolute bottom-4 left-1/2 transform -translate-x-1/2"
            >
                <div
                    class="px-3 py-1 bg-white/10 rounded-full text-white text-sm backdrop-blur-sm"
                >
                    {{ activeIndex + 1 }} / {{ images.length }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { X, ChevronRight } from "lucide-vue-next";
import { computed, ref, watch, onMounted, onUnmounted } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    images: {
        type: Array,
        default: () => [],
    },
    activeIndex: {
        type: Number,
        default: 0,
    },
    currentImageUrl: {
        type: String,
        default: "",
    },
    galleryTitle: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["close", "next", "prev"]);

// Navigation methods with debugging
const handlePrev = () => {
    console.log("Prev button clicked");
    emit("prev");
};

const handleNext = () => {
    console.log("Next button clicked");
    emit("next");
};

const handleClose = () => {
    console.log("Close button clicked");
    emit("close");
};

// Image loading states
const imageLoading = ref(true);
const imageError = ref(false);
const imageElement = ref(null);
const naturalDimensions = ref({ width: 0, height: 0 });

// Computed style for proper aspect ratio preservation
const imageStyles = computed(() => {
    if (!naturalDimensions.value.width || !naturalDimensions.value.height) {
        return {
            maxWidth: "90vw",
            maxHeight: "90vh",
            objectFit: "contain",
        };
    }

    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    const padding = 80; // Padding untuk UI elements

    const maxWidth = viewportWidth - padding;
    const maxHeight = viewportHeight - padding;

    const imageAspectRatio =
        naturalDimensions.value.width / naturalDimensions.value.height;
    const viewportAspectRatio = maxWidth / maxHeight;

    let finalWidth, finalHeight;

    if (imageAspectRatio > viewportAspectRatio) {
        // Image lebih lebar, batasi berdasarkan width
        finalWidth = Math.min(naturalDimensions.value.width, maxWidth);
        finalHeight = finalWidth / imageAspectRatio;
    } else {
        // Image lebih tinggi, batasi berdasarkan height
        finalHeight = Math.min(naturalDimensions.value.height, maxHeight);
        finalWidth = finalHeight * imageAspectRatio;
    }

    return {
        width: `${finalWidth}px`,
        height: `${finalHeight}px`,
        objectFit: "contain",
        imageRendering: "auto",
    };
});

// Image event handlers
const onImageLoad = (event) => {
    const img = event.target;
    naturalDimensions.value = {
        width: img.naturalWidth,
        height: img.naturalHeight,
    };
    imageLoading.value = false;
    imageError.value = false;
};

const onImageError = () => {
    imageLoading.value = false;
    imageError.value = true;
    naturalDimensions.value = { width: 0, height: 0 };
};

// Reset loading state when image changes
watch(
    () => props.currentImageUrl,
    () => {
        imageLoading.value = true;
        imageError.value = false;
        naturalDimensions.value = { width: 0, height: 0 };
    }
);

// Handle keyboard navigation
const handleKeyPress = (event) => {
    if (!props.show) return;

    switch (event.key) {
        case "Escape":
            emit("close");
            break;
        case "ArrowLeft":
            event.preventDefault();
            emit("prev");
            break;
        case "ArrowRight":
            event.preventDefault();
            emit("next");
            break;
    }
};

// Lifecycle hooks for keyboard events
onMounted(() => {
    document.addEventListener("keydown", handleKeyPress);
});

onUnmounted(() => {
    document.removeEventListener("keydown", handleKeyPress);
});

// Prevent body scroll when modal is open
watch(
    () => props.show,
    (newValue) => {
        if (newValue) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "";
        }
    }
);
</script>
