<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
        <p class="text-red-800 font-medium">Lowongan ini sudah tidak menerima lamaran.</p>
        <p class="text-sm text-red-700 mt-1">Silakan cek lowongan lainnya yang masih dibuka.</p>
        <Link 
            href="/lowongan" 
            class="mt-3 inline-block px-4 py-2 bg-red-500 hover:bg-black transition duration-200 text-white rounded-full text-sm font-medium">
            Lihat Lowongan Lainnya
        </Link>
    </div>
</template>