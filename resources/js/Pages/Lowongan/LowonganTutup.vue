<script setup>
import { Link } from '@inertiajs/vue3'
import { AlertCircle, Clock, Archive } from 'lucide-vue-next'
</script>

<template>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
        <AlertCircle class="w-12 h-12 text-red-500 mx-auto mb-3" />
        <p class="text-red-800 font-medium">Lowongan ini sudah tidak menerima lamaran.</p>
        <p class="text-sm text-red-700 mt-1">Silakan cek lowongan lainnya yang masih dibuka.</p>
        <Link 
            href="/lowongan" 
            class="mt-3 inline-block px-4 py-2 bg-red-500 hover:bg-black transition duration-200 text-white rounded-full text-sm font-medium items-center justify-center gap-1"
        >
            <Archive class="w-4 h-4" />
            Lihat Lowongan Lainnya
        </Link>
    </div>
</template>