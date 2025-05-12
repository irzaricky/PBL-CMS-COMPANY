<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Hero from "../Components/Hero.vue";
import Benefit from "../Components/Benefit.vue";
import Produk from "@/Components/Produk.vue";
import Artikel from "@/Components/Artikel.vue";
import CallToAction from "../Components/CallToAction.vue";
import Feedback from "../Components/Feedback.vue";
import Galeri from "../Components/Galeri.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

const featureToggles = ref({});

onMounted(async () => {
    const response = await axios.get('/api/feature-toggles');
    featureToggles.value = response.data.data;
});
</script>


<template>
    <AppLayout>
        <Hero />
        <Benefit />
        <div v-if="featureToggles.produk_module">
            <Produk />
        </div>
        <div v-if="featureToggles.artikel_module">
            <Artikel />
        </div>
        <div v-if="featureToggles.produk_module">
            <Galeri />
        </div>
        <CallToAction />
        <Feedback />
    </AppLayout>
</template>
