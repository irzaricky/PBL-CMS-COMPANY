<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import MiniArtikel from "./MiniArtikel.vue";
import MiniEvent from "./MiniEvent.vue";
import MiniLowongan from "./MiniLowongan.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({ name: "MegaMenu" });

const featureToggles = ref({});

// Menu link lengkap tanpa filter untuk "Tentang Kami"
const tentangKamiSection = {
    title: "Tentang Kami",
    links: [
        { href: "/profil-perusahaan", icon: "Building2", label: "Profil Perusahaan" },
        { href: "/visi-misi", icon: "Binoculars", label: "Visi Misi Perusahaan" },
        { href: "/sejarah-perusahaan", icon: "ScrollText", label: "Sejarah Perusahaan" },
        { href: "/struktur-organisasi", icon: "Users", label: "Struktur Organisasi" },
    ],
};

// Menu sections lain yang perlu toggle
const otherSections = [
    {
        title: "Informasi",
        links: [
            { href: "/artikel", icon: "FileText", label: "Artikel", toggleKey: "artikel_module" },
            { href: "/case-study", icon: "BookOpenCheck", label: "Case Study", toggleKey: "case_study_module" },
            { href: "/galeri", icon: "Image", label: "Galeri", toggleKey: "galeri_module" },
            { href: "/unduhan", icon: "Download", label: "Unduhan", toggleKey: "unduhan_module" },

        ],
    },
    {
        title: "Kegiatan",
        links: [
            { href: "/event", icon: "Calendar", label: "Event", toggleKey: "event_module" },
            { href: "/lowongan", icon: "BriefcaseBusiness", label: "Lowongan", toggleKey: "lowongan_module" },
        ],
    },
];

// Filter menuSections lain (Informasi, Kegiatan) sesuai toggle
const filteredOtherSections = computed(() => {
    return otherSections.map(section => {
        return {
            ...section,
            links: section.links.filter(link => featureToggles.value[link.toggleKey] === 1),
        };
    }).filter(section => section.links.length > 0);
});

// Gabungkan "Tentang Kami" tanpa filter dan yang sudah difilter
const filteredMenuSections = computed(() => [tentangKamiSection, ...filteredOtherSections.value]);

// Fetch feature toggles dari API
async function fetchFeatureToggles() {
    try {
        const res = await axios.get('/api/feature-toggles');
        featureToggles.value = res.data.data || {};
    } catch (err) {
        console.error("Error fetching feature toggles:", err);
        featureToggles.value = {};
    }
}

onMounted(() => {
    fetchFeatureToggles();
});
</script>

<template>
    <div name="mega-menu"
        class="w-full relative z-30 lg:fixed lg:top-[64px] lg:z-50 bg-primary shadow-xl max-h-[calc(100vh-64px)] overflow-y-auto pt-6 pb-8">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-12 px-6 lg:px-8 justify-center">
            <!-- Menu Sections -->
            <div class="flex-3 w-full lg:w-3/4 flex flex-col lg:flex-row gap-12 lg:gap-32">
                <div v-for="section in filteredMenuSections" :key="section.title">
                    <div class="font-bold text-h6-bold mb-6 text-secondary">{{ section.title }}</div>
                    <div class="flex flex-col gap-6 text-h5 font-medium">
                        <Link v-for="link in section.links" :key="link.href" :href="link.href"
                            class="flex items-center gap-4 text-typography-dark hover:text-typography-hover2 transition">
                        <component :is="link.icon" class="w-7" />
                        <span>{{ link.label }}</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right: MiniArtikel, MiniEvent, MiniLowongan -->
            <div class="flex-1 w-full lg:w-1/4 flex flex-col gap-8">
                <MiniArtikel v-if="featureToggles.artikel_module === 1" />
                <MiniEvent v-if="featureToggles.event_module === 1" />
                <MiniLowongan v-if="featureToggles.lowongan_module === 1" />
            </div>
        </div>
    </div>
</template>
