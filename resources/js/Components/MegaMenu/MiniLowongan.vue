<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const lowongans = ref([]);

onMounted(() => {
    fetchLowongan();
});

async function fetchLowongan() {
    try {
        const response = await axios.get("/api/lowongan/newest");
        lowongans.value = response.data.data;
    } catch (error) {
        console.error("Error fetching lowongan:", error);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }
    return `/storage/${image}`;
}
</script>

<template>
    <div>
        <div class="font-bold text-h6-bold mb-4 text-secondary">
            Lowongan Terbaru
        </div>
        <div class="flex flex-col gap-3">
            <div
                v-for="lowongan in lowongans"
                :key="lowongan.id_lowongan"
                class="flex gap-3 bg-white rounded-lg shadow hover:shadow-lg transition p-3 items-center"
            >
                <img
                    :src="getImageUrl(lowongan.thumbnail_lowongan)"
                    :alt="lowongan.judul_lowongan"
                    class="w-12 h-12 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex flex-col overflow-hidden">
                    <a
                        :href="`/lowongan/${lowongan.slug}`"
                        class="text-h6-bold text-secondary truncate hover:underline"
                    >
                        {{ lowongan.judul_lowongan }}
                    </a>
                    <span
                        class="text-xs text-typography-dark line-clamp-2 mt-1"
                        >{{ lowongan.deskripsi_pekerjaan }}</span
                    >
                </div>
            </div>

            <template v-if="!lowongans.length">
                <div
                    v-for="n in 1"
                    :key="n"
                    class="flex gap-3 bg-white rounded-lg shadow transition p-3 items-center animate-pulse"
                >
                    <div
                        class="w-12 h-12 bg-gray-300 rounded-lg flex-shrink-0"
                    ></div>
                    <div class="flex flex-col gap-2 overflow-hidden w-full">
                        <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-full"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
