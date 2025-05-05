<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Hero from '@/Components/Hero.vue';
import Benefit from '@/Components/Benefit.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <Navbar />
        <Hero />
        <Benefit /> 
        <main>
            <slot>

            </slot>
        </main>
    </div>
</template>
