<template>
    <div name="mega-menu">
        <div class="mt-6">
            <span class="font-bold text-sm">Informasi</span>
            <div class="space-y-4 p-4 text-xl font-semibold">
                <a href="/artikel"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <FileText class="w-5 h-5" />
                    <div>Artikel</div>
                </a>

                <a href="/galeri"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <Image class="w-5 h-5" />
                    <div>Galeri</div>
                </a>

                <a href="/unduhan"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <Download class="w-5 h-5" />
                    <div>Unduhan</div>
                </a>
            </div>
        </div>

        <div class="mt-6">
            <span class="font-bold text-sm">Kegiatan</span>
            <div class="space-y-4 p-4 text-xl font-semibold">
                <a href="/event" class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <Calendar class="w-5 h-5" />
                    <div>Event</div>
                </a>

                <a href="/lowongan"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <BriefcaseBusiness class="w-5 h-5" />
                    <div>Lowongan</div>
                </a>
            </div>
        </div>

        <div class="mt-6">
            <span class="font-bold text-sm">Tentang kami</span>
            <div class="space-y-4 p-4 text-xl font-semibold">
                <a href="/profil"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <Building2 class="w-5 h-5" />
                    <div>Profil Perusahaan</div>
                </a>

                <a href="/visi-misi"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <Binoculars class="w-5 h-5" />
                    <div>Visi Misi Perusahaan</div>
                </a>

                <a href="/struktur"
                    class="flex items-center gap-3 text-black hover:text-gray-500 transition duration-300">
                    <Users class="w-5 h-5" />
                    <div>Struktur Organisasi</div>
                </a>
            </div>
        </div>

        <div
            className=" p-6 flex-col justify-start items-start gap-4 bg-gray-100 mt-6 rounded-xl shadow-xl shadow-radius-2xl">
            <div className=" justify-start text-sm font-semibold leading-tight">
                Artikel terbaik kami</div>
            <div className=" py-2 flex flex-col justify-start items-start gap-4">
                <img className="w-full h-48 rounded-2xl" src="https://placehold.co/287x188" />
                <div className="w-full flex flex-col justify-start items-start gap-2">
                    <div className=" flex flex-col justify-start items-start gap-1">
                        <div className=" justify-start text-base font-semibold leading-normal">
                            {{ articles?.judul_artikel ||
                    "Loading..." }}</div>
                        <div className=" justify-start text-sm font-normal leading-tight">
                            Pelajari lebih lanjut tentang artikel ini.</div>
                    </div>
                    <div className="justify-start text-sm font-normal underline leading-tight">
                        Read more</div>
                </div>
            </div>
            <div data-alternate="False" data-icon-position="Trailing" data-small="False" data-style="Link"
                className="py-1 rounded-[100px] inline-flex justify-center items-center gap-2">
                <div className="justify-start text-base font-medium leading-normal">
                    Join</div>
                <div className="w-6 h-6 relative overflow-hidden">
                    <div className="w-2 h-3 left-[8.29px] top-[5.54px] absolute bg-Color-Scheme-2-Text" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from 'axios';
const articles = ref([]);
onMounted(() => {
    fetchArticles();
});

async function fetchArticles() {
    try {
        const response = await axios.get("/api/artikel/id/1");
        articles.value = response.data.data;
    } catch (error) {
        console.error("Error fetching articles:", error);
    }   
}


defineOptions({ name: "MegaMenu" });
</script>
