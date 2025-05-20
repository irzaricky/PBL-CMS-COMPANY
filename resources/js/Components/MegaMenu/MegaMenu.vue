<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import MiniArtikel from "./MiniArtikel.vue";
import MiniEvent from "./MiniEvent.vue";
import MiniLowongan from "./MiniLowongan.vue";
import { Link } from "@inertiajs/vue3";



defineOptions({ name: "MegaMenu" });

const menuSections = [
    {
        title: "Tentang Kami",
        links: [
            { href: "/profilperusahaan", icon: "Building2", label: "Profil Perusahaan" },
            {
                href: "/visi-misi",
                icon: "Binoculars",
                label: "Visi Misi Perusahaan",
            },
            {
                href: "/sejarah-perusahaan",
                icon: "ScrollText",
                label: "Sejarah Perusahaan",
            },
            { 
                href: "/struktur-organisasi", 
                icon: "Users", 
                label: "Struktur Organisasi",
            },
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
    <div name="mega-menu"
        class="w-full relative z-30 lg:fixed lg:top-[64px] lg:z-50 bg-primary shadow-xl max-h-[calc(100vh-64px)] overflow-y-auto pt-6 pb-8">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-12 px-6 lg:px-8 justify-center">
            <!-- Menu Sections -->
            <div class="flex-3 w-full lg:w-3/4 flex flex-col lg:flex-row gap-12 lg:gap-32">
                <div v-for="section in menuSections" :key="section.title">
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

            <!-- Right: Artikel, Event, Lowongan -->
            <div class="flex-1 w-full lg:w-1/4 flex flex-col gap-8">
                <MiniArtikel />
                <MiniEvent />
                <MiniLowongan />
            </div>
        </div>
    </div>
</template>
