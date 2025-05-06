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
        <div class="font-bold text-h6-bold mb-4 text-typography-main">
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
                        class="text-h6-bold text-typography-main truncate hover:underline"
                    >
                        {{ lowongan.judul_lowongan }}
                    </a>
                    <span
                        class="text-xs text-typography-dark line-clamp-2 mt-1"
                        >{{ lowongan.deskripsi_pekerjaan }}</span
                    >
                </div>
            </div>
            <div v-if="!lowongans.length" class="text-typography-dark text-xs">
                Tidak ada lowongan terbaru.
            </div>
        </div>
    </div>
</template>
