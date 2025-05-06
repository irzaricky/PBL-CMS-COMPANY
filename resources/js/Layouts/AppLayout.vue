<script setup>
import { ref } from "vue";
import Navbar from "@/Components/Navbar.vue";

    const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <Navbar />
        <main>
            <slot> </slot>
        </main>
    </div>
</template>
