<script setup>
import { ChevronRight } from "lucide-vue-next";
</script>

<template>
    <div class="px-20 md:px-20 lg:px-20 font-custom bottom-6 bg-transparent">
        <div class="relative w-full max-w-7xl mx-auto">
            <!-- Wrapper with background -->
            <div
                class="bg-gray-100 rounded-xl py-6 px-6 md:px-12 lg:px-20 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0 mt-14 shadow-md">

                <!-- Heading -->
                <div class="text-secondary text-xl sm:text-2xl md:text-3xl font-bold text-center md:text-left">
                    Give Your feedback to our company
                </div>

                <!-- Button -->
                <button type="button"
                    class="relative flex items-center gap-1 bg-secondary text-primary text-lg md:text-xl font-bold px-6 py-3 rounded-lg">
                    Feedback
                    <ChevronRight class="w-5 h-5 text-white" />
                </button>
            </div>
        </div>
    </div>
</template>
