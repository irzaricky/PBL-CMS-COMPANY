<template>
    <!-- Image Metadata Modal -->
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
        @click="$emit('close')"
    >
        <div
            class="bg-white rounded-xl max-w-md w-full max-h-[80vh] overflow-hidden shadow-2xl"
            @click.stop
        >
            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 border-b border-gray-200"
            >
                <h3 class="text-lg font-semibold text-gray-900">Info Gambar</h3>
                <button
                    @click="$emit('close')"
                    class="p-1 text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-4 overflow-y-auto max-h-96">
                <!-- Loading State -->
                <div v-if="loadingMeta" class="text-center py-8">
                    <div
                        class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-secondary"
                    ></div>
                    <p class="mt-2 text-sm text-gray-600">
                        Memuat informasi gambar...
                    </p>
                </div>

                <!-- Metadata Display -->
                <div v-else-if="metadata && !metadata.error" class="space-y-4">
                    <!-- Image Preview -->
                    <div
                        class="aspect-video rounded-lg overflow-hidden bg-gray-100"
                    >
                        <img
                            :src="currentImageUrl"
                            :alt="galleryTitle"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-600">Dimensi</dt>
                            <dd class="text-gray-900">
                                {{ metadata.resolution }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">
                                Ukuran File
                            </dt>
                            <dd class="text-gray-900">
                                {{ metadata.size_formatted }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Format</dt>
                            <dd class="text-gray-900">{{ metadata.type }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">
                                Aspek Rasio
                            </dt>
                            <dd class="text-gray-900">
                                {{ metadata.aspect_ratio }}:1
                            </dd>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div
                        class="border-t border-gray-200 pt-4 space-y-3 text-sm"
                    >
                        <div v-if="metadata.bits">
                            <dt class="font-medium text-gray-600">
                                Kedalaman Bit
                            </dt>
                            <dd class="text-gray-900">
                                {{ metadata.bits }} bit
                            </dd>
                        </div>
                        <div v-if="metadata.channels">
                            <dt class="font-medium text-gray-600">Channel</dt>
                            <dd class="text-gray-900">
                                {{ metadata.channels }}
                            </dd>
                        </div>
                        <div v-if="metadata.file_created">
                            <dt class="font-medium text-gray-600">Dibuat</dt>
                            <dd class="text-gray-900">
                                {{ formatDate(metadata.file_created) }}
                            </dd>
                        </div>
                        <div v-if="metadata.file_modified">
                            <dt class="font-medium text-gray-600">
                                Dimodifikasi
                            </dt>
                            <dd class="text-gray-900">
                                {{ formatDate(metadata.file_modified) }}
                            </dd>
                        </div>

                        <!-- EXIF Data -->
                        <div
                            v-if="
                                metadata.exif &&
                                Object.keys(metadata.exif).length > 0
                            "
                            class="border-t border-gray-200 pt-3"
                        >
                            <h4 class="font-medium text-gray-700 mb-2">
                                Informasi Kamera
                            </h4>
                            <div class="space-y-2">
                                <div v-if="metadata.exif.camera_make">
                                    <dt class="font-medium text-gray-600">
                                        Merek Kamera
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{ metadata.exif.camera_make }}
                                    </dd>
                                </div>
                                <div v-if="metadata.exif.camera_model">
                                    <dt class="font-medium text-gray-600">
                                        Model Kamera
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{ metadata.exif.camera_model }}
                                    </dd>
                                </div>
                                <div v-if="metadata.exif.date_taken">
                                    <dt class="font-medium text-gray-600">
                                        Tanggal Diambil
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{
                                            formatDate(metadata.exif.date_taken)
                                        }}
                                    </dd>
                                </div>
                                <div v-if="metadata.exif.iso">
                                    <dt class="font-medium text-gray-600">
                                        ISO
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{ metadata.exif.iso }}
                                    </dd>
                                </div>
                                <div v-if="metadata.exif.focal_length">
                                    <dt class="font-medium text-gray-600">
                                        Focal Length
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{ metadata.exif.focal_length }}
                                    </dd>
                                </div>
                                <div v-if="metadata.exif.aperture">
                                    <dt class="font-medium text-gray-600">
                                        Aperture
                                    </dt>
                                    <dd class="text-gray-900">
                                        f/{{ metadata.exif.aperture }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Info -->
                        <div
                            v-if="
                                metadata.technical &&
                                Object.keys(metadata.technical).length > 0
                            "
                            class="border-t border-gray-200 pt-3"
                        >
                            <h4 class="font-medium text-gray-700 mb-2">
                                Informasi Teknis
                            </h4>
                            <div class="space-y-2">
                                <div v-if="metadata.technical.compression">
                                    <dt class="font-medium text-gray-600">
                                        Kompresi
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{ metadata.technical.compression }}
                                    </dd>
                                </div>
                                <div v-if="metadata.technical.color_space">
                                    <dt class="font-medium text-gray-600">
                                        Color Space
                                    </dt>
                                    <dd class="text-gray-900">
                                        {{ metadata.technical.color_space }}
                                    </dd>
                                </div>
                                <div v-if="metadata.file_hash">
                                    <dt class="font-medium text-gray-600">
                                        File Hash
                                    </dt>
                                    <dd class="text-gray-900 text-xs font-mono">
                                        {{ metadata.file_hash }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div v-else-if="metadata?.error" class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <Info class="w-12 h-12 mx-auto" />
                    </div>
                    <p class="text-sm text-gray-600">
                        {{
                            metadata.message ||
                            "Tidak dapat memuat informasi gambar"
                        }}
                    </p>
                </div>

                <!-- No Data State -->
                <div v-else class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <Info class="w-12 h-12 mx-auto" />
                    </div>
                    <p class="text-sm text-gray-600">
                        Informasi gambar tidak tersedia
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { X, Info } from "lucide-vue-next";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    metadata: {
        type: Object,
        default: () => ({}),
    },
    loadingMeta: {
        type: Boolean,
        default: false,
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

const emit = defineEmits(["close"]);

function formatDate(date) {
    if (!date) return "";

    return new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}
</script>
