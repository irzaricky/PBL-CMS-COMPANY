<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <h2 class="text-4xl font-custom font-bold text-center mb-4">
                Welcome to Our Platform!
            </h2>
            <p class="font-custom text-lg text-center mb-8">
                Discover events, articles, and more!
            </p>

            <div class="flex justify-center space-x-8">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold mb-4">Upcoming Events</h3>
                    <p>Join us for exciting events!</p>
                    <a
                        href="/event"
                        class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                    >
                        Explore Events
                    </a>
                </div>

                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold mb-4">Latest Articles</h3>
                    <p>Read the latest news and insights!</p>
                    <a
                        href="/artikel"
                        class="mt-4 inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Explore Articles
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
</script>
