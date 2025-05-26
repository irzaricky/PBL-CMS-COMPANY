<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <!-- Section 1: Mission Statement -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-secondary text-white">
            <div class="w-full max-w-screen-xl mx-auto flex flex-col justify-start items-center gap-16 overflow-hidden">
                <div class="w-full max-w-3xl flex flex-col justify-start items-center gap-6">
                    <div class="w-full flex flex-col justify-start items-center gap-4 text-center">
                        <h1 class="text-4xl lg:text-6xl font-normal font-custom leading-tight">
                            Describe why your company exists [mission statement]
                        </h1>
                        <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                            Explain what your company is working on and the value you provide to your customers.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Company Story -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-secondary text-white">
            <div
                class="w-full max-w-screen-xl mx-auto flex flex-col lg:flex-row justify-start items-start gap-10 lg:gap-20">
                <div class="flex-1 flex flex-col justify-start items-start gap-4 overflow-hidden">
                    <div class="text-sm lg:text-base font-semibold font-custom leading-normal">Tagline</div>
                    <h2 class="text-3xl lg:text-5xl font-normal font-custom leading-tight">
                        Tell the story of how your company came about
                    </h2>
                </div>
                <div class="flex-1 flex flex-col justify-start items-start gap-6">
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce varius faucibus massa
                        sollicitudin amet
                        augue. Nibh metus a semper purus mauris duis. Lorem eu neque, tristique quis duis. Nibh
                        scelerisque ac
                        adipiscing velit non nulla in amet pellentesque. Sit turpis pretium eget maecenas. Vestibulum
                        dolor mattis
                        consectetur eget commodo vitae. Amet pellentesque sit pulvinar lorem mi a, euismod risus
                        rhoncus. Elementum
                        ullamcorper nec, habitasse vulputate. Eget dictum quis est sed egestas tellus, a lectus. Quam
                        ullamcorper
                        in fringilla arcu aliquet fames arcu. Lacinia eget faucibus urna, nam risus nec elementum cras
                        porta. Sed
                        elementum, sed dolor purus dolor dui. Ut dictum nulla pulvinar vulputate sit sagittis in
                        eleifend
                        dignissim. Natoque mauris cras molestie velit. Maecenas eget adipiscing quisque viverra lectus
                        arcu,
                        tincidunt ultrices pellentesque.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 3: Visi & Misi Grid -->
        <div class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-white text-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 max-w-screen-xl mx-auto">
                <!-- Visi - kiri atas -->
                <div
                    class="order-1 flex flex-col justify-center items-start gap-4 lg:rounded-tl-[100px] bg-secondary py-6 px-10 shadow">
                    <h3 class="text-xl lg:text-2xl font-semibold font-custom">Visi Kami</h3>
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        Menjadi perusahaan terdepan dalam inovasi dan pelayanan yang berdampak positif bagi masyarakat
                        global.
                    </p>
                </div>

                <!-- Gambar atas - kanan atas -->
                <div class="order-2 lg:order-2 lg:rounded-tr-[100px] overflow-hidden shadow">
                    <img class="w-full h-auto object-cover" src="https://placehold.co/600x400" alt="Visi Image" />
                </div>

                <!-- Gambar bawah - kiri bawah -->
                <div class="order-3 lg:order-3 lg:rounded-bl-[100px] overflow-hidden shadow">
                    <img class="w-full h-auto object-cover" src="https://placehold.co/600x400" alt="Misi Image" />
                </div>

                <!-- Misi - kanan bawah -->
                <div
                    class="order-4 flex flex-col justify-center items-start gap-4 lg:rounded-br-[100px] bg-secondary py-6 px-10 shadow">
                    <h3 class="text-xl lg:text-2xl font-semibold font-custom">Misi Kami</h3>
                    <p class="text-base lg:text-lg font-normal font-custom leading-relaxed">
                        Memberikan produk dan layanan terbaik dengan pendekatan inovatif, berkelanjutan, serta
                        mengutamakan
                        kepuasan pelanggan dan kontribusi sosial.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
