<script setup>
import { ChevronRight } from "lucide-vue-next";
</script>

<template>
    <div class="px-6 md:px-20 lg:px-20 font-custom pt-36 md:pt-20 bg-transparent relative">
        <div class="relative w-full max-w-7xl mx-auto">
            <!-- Wrapper with background -->
            <div class="absolute left-1/2 transform -translate-x-1/2 bg-gray-100 rounded-xl py-6 px-4 md:px-12 lg:px-20 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0 -top-16 sm:-top-20 md:-top-16 lg:-top-12 shadow-md w-[95%] z-10">

                <!-- Heading -->
                <div class="text-secondary text-base sm:text-sm md:text-xl font-bold text-center md:text-left">
                    Give Your Feedback to Our Company
                </div>

                <!-- Button -->
                <button type="button"
                    class="relative flex items-center gap-1 bg-secondary text-primary text-sm md:text-lg font-bold px-6 py-3 rounded-lg">
                    Feedback
                    <ChevronsRight class="w-5 h-5 text-white" />
                </button>
            </div>
        </div>
    </div>
</template>
