<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div>
    <!-- Navbar -->
    <Navbar />

    <!-- Main Content (dynamic content) -->
    <main>
      <slot></slot>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
      <p>&copy; 2025 My Awesome App. All Rights Reserved.</p>
    </footer>
  </div>
</template>
