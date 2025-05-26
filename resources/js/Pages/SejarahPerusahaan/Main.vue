<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';

</script>

<template>
    <AppLayout>
        <div
            class="w-full px-4 sm:px-8 lg:px-16 py-20 bg-background text-secondary font-custom max-w-screen-xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-10">
                <!-- LEFT COLUMN -->
                <div class="lg:w-1/2 flex flex-col gap-8">
                    <div class="flex flex-col gap-4">
                        <div class="text-base font-semibold">Tagline</div>
                        <div class="text-4xl sm:text-5xl font-normal leading-snug">
                            Medium length section heading goes here
                        </div>
                        <div class="text-lg leading-relaxed">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros
                            elementum
                            tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam
                            libero vitae erat.
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="lg:w-1/2 flex flex-col gap-10">
                    <div v-for="index in 4" :key="index" class="flex items-start gap-4 relative">
                        <!-- Timeline Line -->
                        <div class="w-12 flex flex-col items-center">
                            <div class="h-full w-px bg-secondary"></div>
                            <div class="w-3.5 h-3.5 bg-secondary rounded-full mt-[-6px]"></div>
                            <div class="h-full w-px bg-secondary flex-1"></div>
                        </div>

                        <!-- Timeline Content -->
                        <div class="flex-1 p-6 bg-foreground border border-border/20 rounded-2xl flex flex-col gap-6 bg-secondary text-white">
                            <div class="flex flex-col gap-2">
                                <div class="text-3xl sm:text-4xl font-normal leading-tight">Date</div>
                                <div class="text-2xl sm:text-3xl font-normal leading-snug">Short heading here</div>
                            </div>
                            <div class="text-lg leading-relaxed">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros
                                elementum tristique.
                                Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero
                                vitae
                                erat.
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
