<template>
    <section class="relative bg-white overflow-hidden py-20 px-6 md:px-16">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/5 z-0"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between max-w-7xl mx-auto gap-12">
            <!-- Kiri -->
            <div class="flex-1 text-center md:text-left z-10">
                <h1 class="text-4xl md:text-5xl font-extrabold text-black mb-4">
                    Selamat Datang di UKMI!
                </h1>
                <p class="text-lg text-gray-700 mb-6 max-w-md">
                    Wadah kegiatan kerohanian Islam yang seru, hangat, dan bermanfaat!
                </p>
                <div class="flex justify-center md:justify-start gap-4">
                    <button class="bg-indigo-900 text-white px-6 py-3 rounded-full hover:bg-indigo-800 transition">
                        Mulai Sekarang
                    </button>
                    <button class="bg-black/10 text-black px-6 py-3 rounded-full hover:bg-black/20 transition">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

           <!-- Kanan: Foto vertical loop -->
<div class="w-full md:w-auto max-w-md h-[300px] md:h-[400px] overflow-hidden relative z-10">
  <div class="flex gap-4 w-fit">
    <!-- Kolom kiri -->
    <div class="flex flex-col gap-4 animate-scroll-up">
      <template v-for="i in 2" :key="'col1-' + i">
        <img
          src="/image/placeholder.webp"
          class="w-32 h-40 md:w-40 md:h-52 object-cover rounded-xl shadow"
        />
        <img
          src="/image/placeholder.webp"
          class="w-32 h-40 md:w-40 md:h-52 object-cover rounded-xl shadow"
        />
        <img
          src="/image/placeholder.webp"
          class="w-32 h-40 md:w-40 md:h-52 object-cover rounded-xl shadow"
        />
      </template>
    </div>
    <!-- Kolom kanan -->
    <div class="flex flex-col gap-4 animate-scroll-down">
      <template v-for="i in 2" :key="'col2-' + i">
        <img
          src="/image/placeholder.webp"
          class="w-32 h-40 md:w-40 md:h-52 object-cover rounded-xl shadow"
        />
        <img
          src="/image/placeholder.webp"
          class="w-32 h-40 md:w-40 md:h-52 object-cover rounded-xl shadow"
        />
        <img
          src="/image/placeholder.webp"
          class="w-32 h-40 md:w-40 md:h-52 object-cover rounded-xl shadow"
        />
      </template>
    </div>
  </div>
</div>
        </div>
    </section>
</template>

<style>
@keyframes scrollUp {
    0% {
        transform: translateY(0%);
    }

    100% {
        transform: translateY(-50%);
    }
}

@keyframes scrollDown {
    0% {
        transform: translateY(-50%);
    }

    100% {
        transform: translateY(0%);
    }
}

.animate-scroll-up {
    animation: scrollUp 20s linear infinite;
}

.animate-scroll-down {
    animation: scrollDown 20s linear infinite;
}
</style>