<script setup>
import { Home, ChevronRight, Briefcase, CalendarDays, Users, MapPin, Clock, BriefcaseBusiness } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    lowongan: {
        type: Object,
        required: true
    },
    formatTanggal: {
        type: Function,
        required: true
    },
    formatRupiah: {
        type: Function,
        required: true
    },
    isLowonganActive: {
        type: Function,
        required: true
    }
})
</script>

<template>
    <div class="flex flex-col gap-10 w-full max-w-7xl mx-auto">
        <!-- Breadcrumbs -->
        <div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li>
                        <Link href="/"
                            class="inline-flex items-center text-sm text-gray-500 hover:text-secondary">
                            <Home class="w-4 h-4 mr-2" />
                            Home
                        </Link>
                    </li>
                    <li class="inline-flex items-center">
                        <ChevronRight class="w-4 h-4 text-gray-400" />
                        <Link href="/lowongan"
                            class="ml-1 inline-flex items-center text-sm text-gray-500 hover:text-secondary">
                            <BriefcaseBusiness class="w-4 h-4 mr-2" />
                            Lowongan
                        </Link>
                    </li>
                    <li class="flex items-center min-w-0">
                        <ChevronRight class="w-4 h-4 text-gray-400 flex-shrink-0" />
                        <span
                            class="ml-1 text-sm font-medium text-gray-500 truncate max-w-[140px] sm:max-w-[300px]"
                            :title="lowongan.judul_lowongan">
                            {{ lowongan.judul_lowongan }}
                        </span>
                    </li>
                </ol>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Left Column: Lowongan Details -->
            <div class="w-full lg:w-2/3">
                <!-- Header with Status Badge -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl lg:text-4xl font-bold text-secondary">{{ lowongan.judul_lowongan }}</h1>
                        <span v-if="isLowonganActive(lowongan.tanggal_dibuka, lowongan.tanggal_ditutup)" 
                            class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                            Aktif
                        </span>
                        <span v-else 
                            class="px-3 py-1 text-sm font-medium bg-red-100 text-red-700 rounded-full">
                            Ditutup
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 text-sm font-medium bg-gray-100 border-gray-200 border-1 text-secondary rounded-full">
                            {{ lowongan.jenis_lowongan }}
                        </span>
                    </div>
                </div>
                
                <!-- Info Cards: 2x2 Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    <!-- Card 1: Gaji -->
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                        <div class="p-2 bg-secondary/10 rounded-lg">
                            <Wallet class="w-5 h-5 text-secondary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Gaji</p>
                            <p class="font-medium">{{ formatRupiah(lowongan.gaji) }}</p>
                        </div>
                    </div>
                    
                    <!-- Card 2: Tenaga Dibutuhkan -->
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                        <div class="p-2 bg-secondary/10 rounded-lg">
                            <Users class="w-5 h-5 text-secondary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tenaga Dibutuhkan</p>
                            <p class="font-medium">{{ lowongan.tenaga_dibutuhkan }} orang</p>
                        </div>
                    </div>
                    
                    <!-- Card 3: Tanggal Dibuka -->
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                        <div class="p-2 bg-secondary/10 rounded-lg">
                            <CalendarDays class="w-5 h-5 text-secondary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dibuka Sejak</p>
                            <p class="font-medium">{{ formatTanggal(lowongan.tanggal_dibuka) }}</p>
                        </div>
                    </div>
                    
                    <!-- Card 4: Tanggal Ditutup -->
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                        <div class="p-2 bg-secondary/10 rounded-lg">
                            <Clock class="w-5 h-5 text-secondary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ditutup Pada</p>
                            <p class="font-medium">{{ formatTanggal(lowongan.tanggal_ditutup) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Lowongan Description -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Deskripsi Pekerjaan</h2>
                    <div class="prose max-w-none" v-html="lowongan.deskripsi_pekerjaan"></div>
                </div>
            </div>
            
            <!-- Slot for right column -->
            <slot></slot>
        </div>
    </div>
</template>