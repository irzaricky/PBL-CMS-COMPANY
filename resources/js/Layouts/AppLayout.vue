<script setup>
import { ref } from "vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";

    const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <Navbar />
        <main>
            <slot> </slot>
        </main>
        <Footer />
    </div>
</template>
