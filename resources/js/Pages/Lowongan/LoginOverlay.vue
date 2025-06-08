<script setup>
import { Link } from '@inertiajs/vue3'
import { UserPlus } from 'lucide-vue-next'
</script>

<template>
    <div class="relative">
        <!-- Blurred Form Background (for visual effect) -->
        <div class="opacity-25 blur-sm pointer-events-none">
            <div class="space-y-4">
                <div class="h-12 bg-gray-200 rounded-lg"></div>
                <div class="h-12 bg-gray-200 rounded-lg"></div>
                <div class="h-12 bg-gray-200 rounded-lg"></div>
                <div class="h-12 bg-gray-200 rounded-lg"></div>
                <div class="h-24 bg-gray-200 rounded-lg"></div>
                <div class="h-12 bg-secondary rounded-full"></div>
            </div>
        </div>
        
        <!-- Login Required Message -->
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-white bg-opacity-95 rounded-xl">
            <UserPlus class="w-36 h-36 mb-4 text-secondary" />
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Mau Mendaftar?</h4>
            <p class="text-gray-600 mb-4">Silakan login terlebih dahulu untuk mengirim lamaran Anda</p>
            <Link 
                href="/login"
                class="px-6 py-2 bg-secondary hover:bg-black transition-colors duration-300 text-white rounded-full font-medium"
            >
                Login Sekarang
            </Link>
        </div>
    </div>
</template>