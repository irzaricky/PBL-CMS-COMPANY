<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const teamMembers = [
    {
        image: "https://placehold.co/304x296",
        name: "Full name 1",
        jobTitle: "Job title 1",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.",
        icons: [1, 2, 3],
    },
    {
        image: "https://placehold.co/304x296",
        name: "Full name 2",
        jobTitle: "Job title 2",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.",
        icons: [1, 2, 3],
    },
    {
        image: "https://placehold.co/304x296",
        name: "Full name 3",
        jobTitle: "Job title 3",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.",
        icons: [1, 2, 3],
    },
    {
        image: "https://placehold.co/304x296",
        name: "Full name 4",
        jobTitle: "Job title 4",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.",
        icons: [1, 2, 3],
    },
    {
        image: "https://placehold.co/304x296",
        name: "Full name 4",
        jobTitle: "Job title 4",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.",
        icons: [1, 2, 3],
    },
    {
        image: "https://placehold.co/304x296",
        name: "Full name 4",
        jobTitle: "Job title 4",
        description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.",
        icons: [1, 2, 3],
    },
];
</script>

<template>
    <AppLayout>
        <div class="max-w-screen-xl mx-auto px-4 py-28 font-custom text-secondary">
            <div class="max-w-lg mb-20">
                <div class="text-base font-semibold mb-1">Perkenalkan</div>
                <h2 class="text-5xl font-normal mb-4">Tim Kami!</h2>
                <p class="text-lg leading-relaxed">
                    Mereka yang berdedikasi untuk memberikan yang terbaik bagi Anda.
                </p>
            </div>

            <!-- Ganti div flex-wrap jadi grid dengan 4 kolom di lg -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div v-for="(member, index) in teamMembers" :key="index" class="flex flex-col">
                    <img :src="member.image" alt="Member photo" class="w-full h-72 rounded-2xl object-cover" />
                    <div class="flex flex-col items-center gap-4 text-center mt-4">
                        <h3 class="text-xl font-semibold">{{ member.name }}</h3>
                        <p class="text-lg">{{ member.jobTitle }}</p>
                        <p class="text-base">{{ member.description }}</p>
                    </div>
                    <div class="flex justify-center gap-3.5 mt-4">
                        <div v-for="(icon, i) in member.icons" :key="i" class="w-6 h-6 relative overflow-hidden">
                            <div class="w-4 h-4 absolute left-3 top-3 bg-secondary"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-20 max-w-lg">
                <h3 class="text-3xl font-normal mb-4">We’re hiring!</h3>
                <p class="text-lg leading-relaxed mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <button class="px-6 py-2.5 rounded-full bg-secondary text-neutral-darkest font-medium">
                    Open positions
                </button>
            </div>
        </div>
    </AppLayout>
</template>
