<!-- <script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    slug: String
});

const produk = ref(null);
const loading = ref(false);
const error = ref(null);
const activeImageIndex = ref(0);

onMounted(() => {
    fetchProduk();
});

async function fetchProduk() {
    try {
        loading.value = true;
        const response = await axios.get(`/api/produk/${props.slug}`);
        produk.value = response.data.data;
    } catch (err) {
        error.value = 'Produk not found or an error occurred';
        console.error('Error fetching produk:', err);
    } finally {
        loading.value = false;
    }
}

function getImageUrl(image) {
    return image ? `/storage/${image}` : '/image/placeholder.webp';
}
</script> -->

<template>
    <AppLayout>
        <div
            class="w-full px-4 lg:px-16 py-10 lg:py-20 bg-white flex flex-col lg:flex-row justify-start items-start gap-10 lg:gap-20 font-custom">
            <!-- Left: Product Image -->
            <div class="w-full lg:w-1/2 relative">
                <img class="w-full h-auto rounded-2xl object-cover" src="https://placehold.co/600x700"
                    alt="Product Image" />
                <!-- Left & Right Arrows -->
                <div data-direction="Left"
                    class="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-Color-Scheme-1-Foreground rounded-full outline outline-1 outline-offset-[-1px] outline-Color-Scheme-1-Background flex items-center justify-center">
                    <div class="w-6 h-6 relative overflow-hidden">
                        <div class="w-3.5 h-3.5 bg-Color-Scheme-1-Text absolute left-[4.59px] top-[5.29px]" />
                    </div>
                </div>
                <div data-direction="Right"
                    class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-Color-Scheme-1-Foreground rounded-full outline outline-1 outline-offset-[-1px] outline-Color-Scheme-1-Background flex items-center justify-center">
                    <div class="w-6 h-6 relative overflow-hidden">
                        <div class="w-3.5 h-3.5 bg-Color-Scheme-1-Text absolute left-[6px] top-[5.29px]" />
                    </div>
                </div>
                <!-- Pagination Dots -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                    <div class="w-2 h-2 bg-Color-Scheme-1-Text rounded-full" />
                    <div class="w-2 h-2 opacity-20 bg-Color-Scheme-1-Text rounded-full" />
                    <div class="w-2 h-2 opacity-20 bg-Color-Scheme-1-Text rounded-full" />
                    <div class="w-2 h-2 opacity-20 bg-Color-Scheme-1-Text rounded-full" />
                </div>
            </div>

            <!-- Right: Product Details -->
            <div class="w-full lg:w-1/2 flex flex-col gap-8">
                <!-- Breadcrumbs -->
                <div class="flex flex-wrap items-center gap-2 text-sm text-Color-Scheme-1-Text font-custom">
                    <span>Shop all</span>
                    <span class="w-4 h-4 relative">
                        <div class="w-[4.75px] h-2 absolute left-[5.53px] top-[3.69px] bg-Color-Scheme-1-Text" />
                    </span>
                    <span>Category</span>
                    <span class="w-4 h-4 relative">
                        <div class="w-[4.75px] h-2 absolute left-[5.53px] top-[3.69px] bg-Color-Scheme-1-Text" />
                    </span>
                    <span class="font-semibold">Product name</span>
                </div>

                <!-- Title & Price -->
                <h1 class="text-4xl font-custom text-Color-Scheme-1-Text">Nama Produk</h1>
                <div class="flex items-center gap-4">
                    <span class="text-2xl text-Color-Scheme-1-Text font-custom">$55</span>
                    <div class="h-6 border-l border-Color-Scheme-1-Border/20" />
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1">
                            <div class="w-4 h-4 bg-Color-Scheme-1-Text" />
                            <div class="w-4 h-4 bg-Color-Scheme-1-Text" />
                            <div class="w-4 h-4 bg-Color-Scheme-1-Text" />
                            <div class="w-4 h-4 bg-Color-Scheme-1-Text border border-Color-Scheme-1-Text" />
                            <div class="w-4 h-4 bg-Color-Scheme-1-Text" />
                        </div>
                        <span class="text-sm">(3.5 stars) • 10 reviews</span>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-base text-Color-Scheme-1-Text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum
                    tristique...
                </p>
                <ul class="text-base text-Color-Scheme-1-Text list-disc pl-5 space-y-1">
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                </ul>

                <!-- Variant Selectors -->
                <div class="space-y-6">
                    <!-- Dropdown -->
                    <div class="space-y-2">
                        <label class="block text-base text-Color-Scheme-1-Text">Variant</label>
                        <div
                            class="px-3 py-2 bg-Opacity-Neutral-Darkest-5/5 rounded-xl flex items-center justify-between">
                            <span class="text-base text-Color-Neutral-Darkest">Select</span>
                            <div class="w-3 h-2 bg-Opacity-Neutral-Darkest-60/60" />
                        </div>
                    </div>

                    <!-- Option Buttons -->
                    <div class="space-y-2">
                        <label class="block text-base text-Color-Scheme-1-Text">Variant</label>
                        <div class="flex flex-wrap gap-4">
                            <button
                                class="px-4 py-2 bg-Color-Neutral-Darkest text-Color-White font-custom outline outline-1">Option
                                one</button>
                            <button
                                class="px-4 py-2 bg-Opacity-Neutral-Darkest-5/5 text-Color-Neutral-Darkest font-custom outline outline-1">Option
                                Two</button>
                            <button
                                class="px-4 py-2 opacity-25 bg-Opacity-Neutral-Darkest-5/5 text-Color-Neutral-Darkest font-custom outline outline-1"
                                disabled>Option Three</button>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="space-y-2">
                        <label class="block text-base text-Color-Scheme-1-Text">Quantity</label>
                        <div class="w-16 px-3 py-2 bg-Opacity-Neutral-Darkest-5/5 rounded-xl text-Color-Scheme-1-Text">1
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-4">
                        <button class="w-full px-6 py-2.5 bg-Color-Chambray text-Color-White font-medium rounded-full">
                            Add To Cart
                        </button>
                        <button
                            class="w-full px-6 py-2.5 bg-Opacity-Neutral-Darkest-5/5 text-Color-Neutral-Darkest font-medium rounded-full">
                            Buy Now
                        </button>
                        <p class="text-xs text-center text-Color-Scheme-1-Text">Free shipping over $50</p>
                    </div>
                </div>

                <!-- Detail Sections -->
                <div class="space-y-6 mt-6">
                    <div>
                        <h2 class="text-lg font-semibold text-Color-Scheme-1-Text">Details</h2>
                        <p class="text-base text-Color-Scheme-1-Text mt-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim...
                        </p>
                    </div>
                    <div class="border-t border-Color-Scheme-1-Border/20" />
                    <div>
                        <h2 class="text-lg font-semibold text-Color-Scheme-1-Text">Shipping</h2>
                        <p class="text-base text-Color-Scheme-1-Text mt-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim...
                        </p>
                    </div>
                    <div class="border-t border-Color-Scheme-1-Border/20" />
                    <div>
                        <h2 class="text-lg font-semibold text-Color-Scheme-1-Text">Returns</h2>
                        <p class="text-base text-Color-Scheme-1-Text mt-2">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
