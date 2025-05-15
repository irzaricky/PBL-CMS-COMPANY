<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { computed } from "vue";

// Reactive variables
const profil_perusahaan = ref(null);
const loading = ref(false);
const error = ref(null);

const maxKalimat = 1

const truncatedSejarah = computed(() => {
    if (!profil_perusahaan.value?.sejarah_perusahaan) return 'Sejarah perusahaan belum tersedia.'

    const kalimat = profil_perusahaan.value.sejarah_perusahaan.split(/(?<=[.!?])\s+/)
    return kalimat.slice(0, maxKalimat).join(' ')
})

const showReadMore = computed(() => {
    if (!profil_perusahaan.value?.sejarah_perusahaan) return false
    return profil_perusahaan.value.sejarah_perusahaan.split(/(?<=[.!?])\s+/).length > maxKalimat
})

onMounted(() => {
    fetchProfilPerusahaan();
});

async function fetchProfilPerusahaan() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/profil-perusahaan/`);
        profil_perusahaan.value = response.data.data;
        loading.value = false;
    } catch (err) {
        error.value = "Event not found or an error occurred";
        loading.value = false;
        console.error("Error fetching profil_perusahaan:", err);
    }
}

function getImageUrl(image) {
    if (!image) return "/image/placeholder.webp";

    if (typeof image === "object" && image !== null) {
        return image[0] ? `/storage/${image[0]}` : "/image/placeholder.webp";
    }

    return `/storage/${image}`;
}
function lihatSelengkapnya() {
    alert(profil_perusahaan.value.sejarah_perusahaan)
}


</script>

<template>
    <footer class="bg-secondary text-white w-full font-custom text-sm">
        <div class="px-5 pt-5 lg:px-10">
            <!-- Wrapper untuk pusat grid -->
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-5 lg:gap-10 mx-auto lg:items-stretch mt-10">
                <!-- Kolom 1 -->
                <div v-if="profil_perusahaan" class="lg:w-[120%] relative z-20 mt-28 sm:mt-32 md:mt-24">
                    <div class="flex items-center justify-center pt-4">
                        <img :src="getImageUrl(profil_perusahaan?.logo_perusahaan)" alt="Logo Perusahaan"
                            class="w-20 sm:w-24 md:w-28 object-contain" />
                    </div>
                    <h4 class="font-bold text-center text-lg">{{ profil_perusahaan?.nama_perusahaan }}</h4>
                    <p class="mt-4 text-center">
                        {{ truncatedSejarah }}
                        <span v-if="showReadMore" class="text-blue-400 cursor-pointer" @click="lihatSelengkapnya">
                            ... Baca selengkapnya
                        </span>
                    </p>

                    <div class="mt-6">
                        <h4 class="font-bold pb-1">Contact Us</h4>
                        <div class="flex items-center gap-2">
                            <Phone class="w-4" />
                            <span>(031) 33101059</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Mail class="w-4" />
                            <span>marketing@biiscorp.com</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="flex flex-col justify-center h-full lg:col-span-2 lg:pl-20">
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="grid grid-cols-2 gap-y-2">
                        <li><a href="#" class="hover:underline">Beranda</a></li>
                        <li><a href="#" class="hover:underline">Galeri</a></li>
                        <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                        <li><a href="#" class="hover:underline">Unduhan</a></li>
                        <li><a href="#" class="hover:underline">Produk</a></li>
                        <li><a href="#" class="hover:underline">Event</a></li>
                        <li><a href="#" class="hover:underline">Artikel</a></li>
                        <li><a href="#" class="hover:underline">Lowongan</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div class="space-y-6 flex flex-col justify-center h-full">
                    <div>
                        <h4 class="font-bold mb-4">Our Location</h4>
                        <div class="flex items-start gap-2">
                            <MapPin class="w-10 lg:w-20 self-center" />
                            <span class="leading-relaxed">
                                {{ profil_perusahaan?.alamat_perusahaan || 'Alamat perusahaan belum tersedia.' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Follow Us</h4>
                        <div class="flex flex-wrap gap-4">
                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="bi bi-tiktok"></i></a>
                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" target="_blank" rel="noopener noreferrer"><i class="bi bi-telegram"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Kolom 4 -->
                <div class="flex flex-col justify-center h-full">
                    <!-- <div -->
                    <div class="w-full lg:aspect-[4/3] rounded-lg overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7910.17971246413!2d110.8504919!3d-7.565182!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a14234667a3fd%3A0xbda63b32997616ad!2sUniversitas%20Sebelas%20Maret%20(UNS)!5e0!3m2!1sid!2sid!4v1746990931583!5m2!1sid!2sid"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-white text-black h-10 mt-10">
            <p class="text-center leading-10">
                © 2025 {{ profil_perusahaan?.nama_perusahaan }}.
            </p>
        </div>
    </footer>
</template>