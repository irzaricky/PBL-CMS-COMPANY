<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Hero from "../Components/Home/Hero.vue";
import Benefit from "../Components/Home/Benefit.vue";
import Produk from "@/Components/Home/Produk.vue";
import Artikel from "@/Components/Home/Artikel.vue";
import CallToAction from "../Components/Home/CallToAction.vue";
import Feedback from "../Components/Home/Feedback.vue";
import Galeri from "../Components/Home/Galeri.vue";
import Event from "@/Components/Home/Event.vue";
import Lowongan from "@/Components/Home/Lowongan.vue";
import Mitra from "@/Components/Home/Mitra.vue";
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
        <div v-if="featureToggles.galeri_module">
            <Galeri />
        </div>
        <div v-if="featureToggles.event_module">
            <Event />
        </div>
        <div v-if="featureToggles.lowongan_module">
            <Lowongan />
        </div>
        <div v-if="featureToggles.mitra_module">
            <Mitra /> 
        </div>
        <CallToAction />
    </AppLayout>
</template>
