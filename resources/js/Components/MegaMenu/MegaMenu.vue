<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import MiniArtikel from "./MiniArtikel.vue";
import MiniEvent from "./MiniEvent.vue";
import MiniLowongan from "./MiniLowongan.vue";



defineOptions({ name: "MegaMenu" });

const menuSections = [
    {
        title: "Tentang Kami",
        links: [
            { href: "/profil", icon: "Building2", label: "Profil Perusahaan" },
            {
                href: "/visi-misi",
                icon: "Binoculars",
                label: "Visi Misi Perusahaan",
            },
            {
                href: "/sejarah",
                icon: "ScrollText",
                label: "Sejarah Perusahaan",
            },
            { href: "/struktur", icon: "Users", label: "Struktur Organisasi" },
        ],
    },
    {
        title: "Informasi",
        links: [
            { href: "/artikel", icon: "FileText", label: "Artikel" },
            { href: "/galeri", icon: "Image", label: "Galeri" },
            { href: "/unduhan", icon: "Download", label: "Unduhan" },
        ],
    },
    {
        title: "Kegiatan",
        links: [
            { href: "/event", icon: "Calendar", label: "Event" },
            { href: "/lowongan", icon: "BriefcaseBusiness", label: "Lowongan" },
        ],
    },
];
</script>

<template>
    <div name="mega-menu" class="w-screen bg-primary shadow-xl py-8">
        <div class="max-w-6xl mx-auto flex flex-row gap-12 px-8 justify-center">
            <!-- Left: Menu Sections (3/4) -->
            <div class="flex-3 w-3/4 flex flex-row gap-32">
                <div v-for="section in menuSections" :key="section.title">
                    <div
                        class="font-bold text-h6-bold mb-6 text-typography-main"
                    >
                        {{ section.title }}
                    </div>
                    <div class="flex flex-col gap-6 text-h5 font-medium">
                        <a
                            v-for="link in section.links"
                            :key="link.href"
                            :href="link.href"
                            class="flex items-center gap-4 text-typography-dark hover:text-typography-hover2 transition"
                        >
                            <component :is="link.icon" class="w-7 h-7" />
                            <span>{{ link.label }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Right: Artikel, Event, Lowongan (1/4) -->
            <div class="flex-1 w-1/4 flex flex-col gap-8">
                <!-- Artikel Populer -->
                <MiniArtikel />
                <!-- Event Terbaru -->
                <MiniEvent />
                <!-- Lowongan Terbaru -->
                <MiniLowongan />
            </div>
        </div>
    </div>
</template>
