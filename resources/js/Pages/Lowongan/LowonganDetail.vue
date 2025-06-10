<script setup>
import { Clock, MapPin, DollarSign, Users, Calendar } from 'lucide-vue-next'

const props = defineProps({
    lowongan: Object,
    formatTanggal: Function,
    formatRupiah: Function,
    isLowonganActive: Function
})

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}
</script>

<template>
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <!-- Left Column - Job Details -->
            <div class="w-full lg:w-2/3">
                <!-- Header Section -->
                <div class="mb-8">
                    <!-- Job Title -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        {{ lowongan.judul_lowongan }}
                    </h1>
                    
                    <!-- Job Meta Info -->
                    <div class="flex flex-wrap gap-4 mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ lowongan.jenis_lowongan }}
                        </span>
                        
                        <span :class="`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${
                            isLowonganActive(lowongan.tanggal_dibuka, lowongan.tanggal_ditutup) 
                                ? 'bg-green-100 text-green-800' 
                                : 'bg-red-100 text-red-800'
                        }`">
                            {{ isLowonganActive(lowongan.tanggal_dibuka, lowongan.tanggal_ditutup) ? 'Aktif' : 'Ditutup' }}
                        </span>
                    </div>

                    <!-- Job Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <DollarSign class="w-5 h-5 text-gray-600" />
                            <div>
                                <p class="text-sm text-gray-600">Gaji</p>
                                <p class="font-semibold">{{ formatRupiah(lowongan.gaji) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <MapPin class="w-5 h-5 text-gray-600" />
                            <div>
                                <p class="text-sm text-gray-600">Lokasi</p>
                                <p class="font-semibold">{{ lowongan.lokasi || 'Remote' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <Users class="w-5 h-5 text-gray-600" />
                            <div>
                                <p class="text-sm text-gray-600">Kebutuhan</p>
                                <p class="font-semibold">{{ lowongan.tenaga_dibutuhkan }} orang</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <Calendar class="w-5 h-5 text-gray-600" />
                            <div>
                                <p class="text-sm text-gray-600">Deadline</p>
                                <p class="font-semibold">{{ formatTanggal(lowongan.tanggal_ditutup) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Image/Thumbnail -->
                <div v-if="lowongan.thumbnail_lowongan" class="mb-8">
                    <img 
                        :src="getImageUrl(lowongan.thumbnail_lowongan)" 
                        :alt="lowongan.judul_lowongan"
                        class="w-full h-64 lg:h-80 object-cover rounded-lg shadow-md"
                        @error="$event.target.src = '/image/placeholder.webp'"
                    />
                </div>

                <!-- Job Description with HTML content -->
                <div class="prose prose-lg max-w-none">
                    <div 
                        class="text-gray-700 leading-relaxed"
                        v-html="lowongan.deskripsi_pekerjaan"
                    ></div>
                </div>
            </div>

            <!-- Right Column - Application Form (Slot for parent component) -->
            <slot></slot>
        </div>
    </div>
</template>

<style scoped>
/* Ensure HTML content in description renders properly */
:deep(.prose) {
    max-width: none;
}

:deep(.prose h1) {
    @apply text-2xl font-bold text-gray-900 mt-8 mb-4;
}

:deep(.prose h2) {
    @apply text-xl font-bold text-gray-900 mt-6 mb-3;
}

:deep(.prose h3) {
    @apply text-lg font-semibold text-gray-900 mt-6 mb-3;
}

:deep(.prose p) {
    @apply mb-4 leading-relaxed;
}

:deep(.prose ul) {
    @apply list-disc ml-6 mb-4;
}

:deep(.prose ol) {
    @apply list-decimal ml-6 mb-4;
}

:deep(.prose li) {
    @apply mb-2;
}

:deep(.prose blockquote) {
    @apply border-l-4 border-gray-300 pl-4 italic my-4 bg-gray-50 py-2;
}

:deep(.prose table) {
    @apply w-full border-collapse border border-gray-300 my-4;
}

:deep(.prose th) {
    @apply border border-gray-300 px-4 py-2 bg-gray-100 font-semibold;
}

:deep(.prose td) {
    @apply border border-gray-300 px-4 py-2;
}

:deep(.prose a) {
    @apply text-blue-600 hover:text-blue-800 underline;
}

:deep(.prose strong) {
    @apply font-semibold;
}

:deep(.prose em) {
    @apply italic;
}
</style>