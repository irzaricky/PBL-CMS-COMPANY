<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, computed } from "vue";
import axios from "axios";

const struktur_organisasi = ref([]);
const loading = ref(false);
const error = ref(null);

onMounted(() => {
    fetchStrukturOrganisasi();
});

async function fetchStrukturOrganisasi() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/struktur-organisasi`);
        struktur_organisasi.value = response.data.data;
        loading.value = false;
    } catch (err) {
        error.value = "Event not found or an error occurred";
        loading.value = false;
        console.error("Error fetching struktur_organisasi:", err);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";
    return `/storage/${image}`;
}

const teamMembers = computed(() => {
    return struktur_organisasi.value.map(item => ({
        image: item.user?.foto_profil ? getImageUrl(item.user.foto_profil) : "/image/placeholder.webp",
        name: item.user?.name || "Nama tidak tersedia",
        jobTitle: item.jabatan || "Jabatan tidak tersedia",
        description: item.deskripsi || "",
        icons: [1, 2, 3],
    }));
});
</script>

<template>
    <AppLayout>
        <div class="max-w-screen-xl mx-auto px-10 py-28 font-custom text-black">
            <div class="max-w-lg mb-20">
                <div class="text-base font-semibold mb-1">Perkenalkan</div>
                <h2 class="text-6xl font-normal mb-4">Tim Kami!</h2>
                <p class="text-lg leading-relaxed">
                    Mereka yang berdedikasi untuk memberikan yang terbaik bagi Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div v-for="(member, index) in teamMembers" :key="index" class="flex flex-col">
                    <img :src="member.image" alt="Member photo" class="w-full h-72 rounded-2xl object-cover" />
                    <div class="flex flex-col items-start text-left mt-4">
                        <h3 class="text-xl font-semibold">{{ member.name }}</h3>
                        <p class="text-lg pb-2">{{ member.jobTitle }}</p>
                        <p class="text-base">{{ member.description }}</p>
                    </div>
                    <div class="flex items-start mt-4">
                        <div v-for="(icon, i) in member.icons" :key="i" class="w-6 h-6 relative overflow-hidden">
                            <div class="w-4 h-4 absolute left-3 top-3 bg-secondary"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="mt-20 max-w-lg">
                <h3 class="text-6xl font-normal mb-4">Kami juga merekrut</h3>
                <p class="text-lg leading-relaxed mb-6">
                    Mari bergabung kami dan menjadi bagian dari tim yang luar biasa ini. Kami mencari individu yang
                    berbakat dan berdedikasi untuk bergabung dengan tim kami.
                </p>
                <button class="px-6 py-2.5 rounded-full bg-secondary text-neutral-darkest font-medium text-white">
                    Buka Lowongan
                </button>
            </div>
        </div>
    </AppLayout>
</template>
