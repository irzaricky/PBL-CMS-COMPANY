<script setup>
import { ref } from "vue";
import Navbar from "@/Components/Navbar.vue";
import Feedback from "@/Components/Feedback.vue";

    const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <Navbar />
        <main>
            <slot> </slot>
        </main>
        <Feedback />
    </div>
</template>
