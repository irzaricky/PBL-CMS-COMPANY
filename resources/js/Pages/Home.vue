<template>
    <AppLayout>
        <Hero />
        <Benefit />
        <Produk />
        <Artikel />
        <CallToAction />
    </AppLayout>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Hero from "../Components/Hero.vue";
import Benefit from "../Components/Benefit.vue";
import Produk from "@/Components/Produk.vue";
import Artikel from "@/Components/Artikel.vue";
import CallToAction from "../Components/CallToAction.vue";
</script>
