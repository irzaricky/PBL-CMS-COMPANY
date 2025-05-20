<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <!-- Section Header -->
        <div class="w-full px-6 lg:px-16 py-28 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto text-center flex flex-col gap-6 items-center">
                <h1 class="text-4xl lg:text-6xl font-custom font-normal leading-tight">
                    Visi & Misi Perusahaan
                </h1>
                <p class="text-base lg:text-lg font-custom font-normal leading-relaxed max-w-3xl">
                    Nilai-nilai yang menjadi dasar arah langkah dan strategi kami dalam memberikan dampak terbaik bagi pelanggan dan masyarakat.
                </p>
            </div>
        </div>

        <!-- Section: Visi -->
        <div class="w-full px-6 lg:px-16 py-20 bg-white text-black">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row items-center gap-10">
                <!-- Teks Visi -->
                <div class="lg:w-1/2 flex flex-col gap-4">
                    <h2 class="text-3xl lg:text-5xl font-custom font-semibold text-secondary">
                        Visi Kami
                    </h2>
                    <p class="text-base lg:text-lg font-custom font-normal leading-relaxed">
                        Menjadi perusahaan terdepan dalam inovasi dan pelayanan yang berdampak positif bagi masyarakat global,
                        menginspirasi perubahan dan mempercepat kemajuan dengan teknologi, nilai sosial, dan keunggulan layanan.
                    </p>
                </div>

                <!-- Gambar -->
                <div class="lg:w-1/2">
                    <img src="https://placehold.co/600x400" alt="Gambar Visi" class="rounded-2xl shadow w-full h-auto object-cover">
                </div>
            </div>
        </div>

        <!-- Section: Misi -->
        <div class="w-full px-6 lg:px-16 py-20 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row-reverse items-center gap-10">
                <!-- Teks Misi -->
                <div class="lg:w-1/2 flex flex-col gap-4">
                    <h2 class="text-3xl lg:text-5xl font-custom font-semibold">
                        Misi Kami
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-base lg:text-lg font-custom font-normal leading-relaxed">
                        <li>Menghadirkan produk dan layanan berkualitas tinggi yang menjawab kebutuhan pelanggan.</li>
                        <li>Mendorong inovasi berkelanjutan dalam setiap lini bisnis kami.</li>
                        <li>Menjalin kemitraan strategis yang berkelanjutan dan saling menguntungkan.</li>
                        <li>Memberikan kontribusi nyata bagi pembangunan sosial dan lingkungan.</li>
                        <li>Menjadi tempat kerja yang inklusif, inspiratif, dan penuh semangat kolaborasi.</li>
                    </ul>
                </div>

                <!-- Gambar -->
                <div class="lg:w-1/2">
                    <img src="https://placehold.co/600x400" alt="Gambar Misi" class="rounded-2xl shadow w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </AppLayout>
</template>
