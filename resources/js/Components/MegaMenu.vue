<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const articles = ref([]);

onMounted(() => {
    fetchArticles();
});

async function fetchArticles() {
    try {
        const response = await axios.get("/api/artikel/most-viewed");
        articles.value = response.data.data;
    } catch (error) {
        console.error("Error fetching articles:", error);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}

defineOptions({ name: "MegaMenu" });
</script>

<template>
    <div
        name="mega-menu"
        class="w-screen bg-gray-100 shadow-xl"
        style="padding: 2rem 0"
    >
        <div class="max-w-6xl mx-auto flex flex-row gap-16 px-8 justify-center">
            <!-- Tentang Kami -->
            <div>
                <div class="font-bold text-xl mb-6">Tentang Kami</div>
                <div class="flex flex-col gap-6 text-lg font-medium">
                    <a
                        href="/profil"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <Building2 class="w-7 h-7" />
                        <span>Profil Perusahaan</span>
                    </a>
                    <a
                        href="/visi-misi"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <Binoculars class="w-7 h-7" />
                        <span>Visi Misi Perusahaan</span>
                    </a>
                    <a
                        href="/sejarah"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <ScrollText class="w-7 h-7" />
                        <span>Sejarah Perusahaan</span>
                    </a>
                    <a
                        href="/struktur"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <Users class="w-7 h-7" />
                        <span>Struktur Organisasi</span>
                    </a>
                </div>
            </div>
            <!-- Informasi -->
            <div>
                <div class="font-bold text-xl mb-6">Informasi</div>
                <div class="flex flex-col gap-6 text-lg font-medium">
                    <a
                        href="/artikel"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <FileText class="w-7 h-7" />
                        <span>Artikel</span>
                    </a>
                    <a
                        href="/galeri"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <Image class="w-7 h-7" />
                        <span>Galeri</span>
                    </a>
                    <a
                        href="/unduhan"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <Download class="w-7 h-7" />
                        <span>Unduhan</span>
                    </a>
                </div>
            </div>
            <!-- Kegiatan -->
            <div>
                <div class="font-bold text-xl mb-6">Kegiatan</div>
                <div class="flex flex-col gap-6 text-lg font-medium">
                    <a
                        href="/event"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <Calendar class="w-7 h-7" />
                        <span>Event</span>
                    </a>
                    <a
                        href="/lowongan"
                        class="flex items-center gap-4 text-black hover:text-gray-500 transition"
                    >
                        <BriefcaseBusiness class="w-7 h-7" />
                        <span>Lowongan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
