<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Reactive variables
const profil_perusahaan = ref(null);
const loading = ref(false);
const error = ref(null);

onMounted(() => {
  fetchProfilPerusahaan();
});

async function fetchProfilPerusahaan() {
  try {
    loading.value = true;
    const response = await axios.get(`/api/profil-perusahaan`);
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
</script>

<template>
  <footer class="bg-secondary text-white w-full h-[542px] relative">
    <div class="pl-[39px] pr-[20px] pt-[73px] grid grid-cols-[repeat(3,auto)_1fr] gap-16">
      <!-- Company Info -->
      <div class="mt-[73px] mb-[90px] w-[331.56px]">
        <img :src="getImageUrl(profil_perusahaan?.logo_perusahaan)" alt="Logo Perusahaan"
          class=" w-[331.56px] h-[77.66px] object-contain" />
        <p class="mt-5 text-justify text-[16px] font-semibold leading-[22.4px]">
          Perusahaan kami bekerja di bidang pengembangan teknologi yang telah bekerja sama dengan banyak client
        </p>
        <div class="space-y-2 mt-[29px]">
          <h4 class="text-[24px] font-bold leading-[33.6px]">Contact Us</h4>
          <div class="flex items-center space-x-3">
            <Phone />
            <span class="text-[16px] font-semibold leading-[25.2px]">(031) 33101059</span>
          </div>
          <div class="flex items-center space-x-3">
            <Mail />
            <span class="text-[18px] font-semibold leading-[25.2px]">marketing@biiscorp.com</span>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="space-y-2 w-[303px] mt-[158px]">
        <h4 class="text-[24px] font-bold leading-[33.6px] mb-2">Quick Links</h4>
        <ul class="grid grid-cols-2 gap-y-2 text-[18px] font-semibold leading-[25.2px]">
          <li><a href="#" class="hover:underline">Beranda</a></li>
          <li><a href="#" class="hover:underline">Galeri</a></li>
          <li><a href="#" class="hover:underline">Tentang Kami</a></li>
          <li><a href="#" class="hover:underline">Unduhan</a></li>
          <li><a href="#" class="hover:underline">Portofolio</a></li>
          <li><a href="#" class="hover:underline">Event</a></li>
          <li><a href="#" class="hover:underline">Artikel</a></li>
          <li><a href="#" class="hover:underline">Lowongan</a></li>
        </ul>
      </div>

      <!-- Location & Social Media -->
      <div class="w-[307px] mt-[158px] space-y-9">
        <div>
          <h4 class="text-[24px] font-bold leading-[33.6px] mb-2">Our Location</h4>
          <div class="flex items-center space-x-4">
            <i class="bi bi-geo-alt-fill"></i>
            <span class="text-justify text-[16px] font-semibold leading-[22.4px]">Jl. Teluk Kumai Barat, Perak Utara, Kec. Pabean Cantikan, Surabaya, Jawa Timur 60165</span>
          </div>
        </div>

        <div>
          <h4 class="text-[24px] font-bold leading-[33.6px] mb-2">Follow Us</h4>
          <div class="flex space-x-4 text-xl">
            <i class="bi bi-instagram"></i>
            <i class="bi bi-tiktok"></i>
            <i class="bi bi-facebook"></i>
            <i class="bi bi-linkedin"></i>
            <i class="bi bi-twitter-x"></i>
            <i class="bi bi-telegram"></i>
          </div>
        </div>
      </div>

      <!-- Map Embed -->
      <div class="bg-fourth rounded-[10px] w-[308px] h-[293px] mt-[120px] ml-[20px]">
        <iframe class="rounded-[10px] -translate-x-[20px]"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.172995216175!2d110.81382747500246!3d-7.55610799245767!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a176daa5fd6d1%3A0x271738c6ffd2b4f8!2sBIIS%20Corp%20Solo%20-%20Jasa%20Pembuatan%20Software%2C%20ERP%2C%20Website%2C%20dan%20Digital%20Marketing!5e0!3m2!1sid!2sid!4v1746690887263!5m2!1sid!2sid"
          width="315" height="273" style="border:0; margin-top: 10px;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>

    <!-- Copyright -->
    <div class="bg-white h-[40px]">
      <p class="text-center text-typography-dark text-[18px] font-semibold leading-[25.2px] pt-[5px] pb-[6px]">
        Hak Cipta © 2025. Hak Cipta dilindungi. Biiscorp
      </p>
    </div>
  </footer>
</template>