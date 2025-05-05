<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Hero from '@/Components/Hero.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <Navbar />
        <Hero />

        <main>
            <slot>

            </slot>
        </main>
    </div>
</template>
