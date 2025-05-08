<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Hero from "../Components/Hero.vue";
import Benefit from "../Components/Benefit.vue";
import Produk from "@/Components/Produk.vue";
import Artikel from "@/Components/Artikel.vue";
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
        <div v-if="featureToggles.produk">
            <Produk />
        </div>
        <div v-if="featureToggles.artikel">
            <Artikel />
        </div>
    </AppLayout>
</template>
