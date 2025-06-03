<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { usePage, Link } from '@inertiajs/vue3'
import { Home, ChevronRight, Briefcase, CalendarDays, Users, MapPin, Clock, Send } from 'lucide-vue-next'

// Props dari route
const { slug } = defineProps({ slug: String })

// Global props dari Inertia
const page = usePage()
const theme = computed(() => page.props.theme)
const user = computed(() => page.props.auth?.user)

// === DATA
const item = ref(null)
const loading = ref(false)
const isLoggedIn = computed(() => !!user.value)
const showSuccessModal = ref(false)
const isApplying = ref(false)
const formData = ref({
    nama_lengkap: user.value?.name || '',
    email: user.value?.email || '',
    nomor_telepon: '',
    cv_file: null,
    cover_letter: '',
})

// === LIFECYCLE
onMounted(() => {
    fetchLowongan()
    document.documentElement.style.setProperty('--color-secondary', theme.value.secondary)
})

// === FUNCTION
async function fetchLowongan() {
    try {
        loading.value = true
        const response = await axios.get(`/api/lowongan/${slug}`)
        
        // Console log to see what structure we're getting
        console.log('Response data structure:', response.data)
        
        if (response.data) {
            // LowonganViewResource structure (probably the whole response is the lowongan)
            if (response.data.id_lowongan) {
                item.value = response.data
            } 
            // Response might be wrapped in a data property
            else if (response.data.data && response.data.data.id_lowongan) {
                item.value = response.data.data
            }
            // Handle error response that might have a message
            else if (response.data.message) {
                console.error('API error message:', response.data.message)
                item.value = null
            } 
            else {
                console.error('Unexpected response structure:', response.data)
                item.value = null
            }
        } else {
            console.error('No data in response')
            item.value = null
        }
    } catch (err) {
        console.error('Lowongan not found or error:', err.response?.data || err.message || err)
        item.value = null
    } finally {
        loading.value = false
    }
}

function handleFileUpload(event) {
    formData.value.cv_file = event.target.files[0]
}

async function submitApplication() {
    if (!formData.value.nama_lengkap || !formData.value.email || !formData.value.nomor_telepon || !formData.value.cv_file) {
        alert('Silakan lengkapi semua field yang diperlukan')
        return
    }
    
    try {
        isApplying.value = true
        
        // Create FormData object for file upload
        const submitData = new FormData()
        submitData.append('nama_lengkap', formData.value.nama_lengkap)
        submitData.append('email', formData.value.email)
        submitData.append('nomor_telepon', formData.value.nomor_telepon)
        submitData.append('cv_file', formData.value.cv_file)
        submitData.append('cover_letter', formData.value.cover_letter)
        submitData.append('id_lowongan', item.value.id_lowongan)
        
        if (user.value?.id_user) {
            submitData.append('id_user', user.value.id_user)
        }
        
        await axios.post('/api/lamaran', submitData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        
        // Show success modal
        showSuccessModal.value = true
        
        // Reset form
        formData.value = {
            nama_lengkap: user.value?.name || '',
            email: user.value?.email || '',
            nomor_telepon: '',
            cv_file: null,
            cover_letter: '',
        }
        
        // Reset file input
        document.getElementById('cv_file').value = ''
        
    } catch (err) {
        alert('Gagal mengirim lamaran')
        console.error(err)
    } finally {
        isApplying.value = false
    }
}

function formatTanggal(tanggal) {
    if (!tanggal) return ''
    const options = { year: 'numeric', month: 'long', day: 'numeric' }
    return new Date(tanggal).toLocaleDateString('id-ID', options)
}

function formatRupiah(value) {
    if (!value) return 'Tidak disebutkan'
    const numberValue = Number(value)
    if (isNaN(numberValue)) return value
    return `Rp${numberValue.toLocaleString('id-ID')},00`
}

function closeModal() {
    showSuccessModal.value = false
}

function isLowonganActive(tanggalDibuka, tanggalDitutup) {
    const now = new Date()
    const dibuka = new Date(tanggalDibuka)
    const ditutup = new Date(tanggalDitutup)
    
    return now >= dibuka && now <= ditutup
}
</script>

<template>
    <AppLayout>
        <div class="w-full px-4 lg:px-16 py-10 lg:py-20 bg-white flex flex-col items-start gap-10 font-custom">

            <!-- Skeleton Loading -->
            <div v-if="loading" class="flex flex-col gap-10 w-full max-w-7xl mx-auto">
                <!-- Breadcrumbs Skeleton -->
                <div>
                    <div class="flex items-center gap-2">
                        <div class="h-4 w-24 bg-gray-200 animate-pulse rounded"></div>
                        <div class="h-4 w-4 bg-gray-200 animate-pulse rounded-full"></div>
                        <div class="h-4 w-24 bg-gray-200 animate-pulse rounded"></div>
                        <div class="h-4 w-4 bg-gray-200 animate-pulse rounded-full"></div>
                        <div class="h-4 w-32 bg-gray-200 animate-pulse rounded"></div>
                    </div>
                </div>
                
                <!-- Main Content Skeleton -->
                <div class="flex flex-col lg:flex-row gap-10">
                    <!-- Left Column Skeleton -->
                    <div class="w-full lg:w-2/3">
                        <!-- Header Skeleton -->
                        <div class="mb-8">
                            <div class="h-10 bg-gray-200 animate-pulse rounded w-3/4 mb-4"></div>
                            <div class="flex flex-wrap gap-2">
                                <div class="h-6 w-24 bg-gray-200 animate-pulse rounded-full"></div>
                                <div class="h-6 w-32 bg-gray-200 animate-pulse rounded-full"></div>
                            </div>
                        </div>
                        
                        <!-- Info Cards Skeleton -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="h-24 bg-gray-100 animate-pulse rounded-lg"></div>
                            <div class="h-24 bg-gray-100 animate-pulse rounded-lg"></div>
                            <div class="h-24 bg-gray-100 animate-pulse rounded-lg"></div>
                            <div class="h-24 bg-gray-100 animate-pulse rounded-lg"></div>
                        </div>
                        
                        <!-- Description Skeleton -->
                        <div class="space-y-2">
                            <div class="h-6 bg-gray-200 animate-pulse rounded w-1/4 mb-4"></div>
                            <div class="h-4 bg-gray-200 animate-pulse rounded w-full"></div>
                            <div class="h-4 bg-gray-200 animate-pulse rounded w-full"></div>
                            <div class="h-4 bg-gray-200 animate-pulse rounded w-5/6"></div>
                            <div class="h-4 bg-gray-200 animate-pulse rounded w-3/4"></div>
                        </div>
                    </div>
                    
                    <!-- Right Column Skeleton -->
                    <div class="w-full lg:w-1/3">
                        <div class="sticky top-24 p-6 bg-gray-100 animate-pulse rounded-xl h-96"></div>
                    </div>
                </div>
            </div>

            <!-- Actual Lowongan Detail - Show when not loading -->
            <div v-else-if="item" class="flex flex-col gap-10 w-full max-w-7xl mx-auto">
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
                                    <Briefcase class="w-4 h-4 mr-2" />
                                    Lowongan
                                </Link>
                            </li>
                            <li class="flex items-center min-w-0">
                                <ChevronRight class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                <span
                                    class="ml-1 text-sm font-medium text-gray-500 truncate max-w-[140px] sm:max-w-[300px]"
                                    :title="item.judul_lowongan">
                                    {{ item.judul_lowongan }}
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
                                <h1 class="text-3xl lg:text-4xl font-bold text-secondary">{{ item.judul_lowongan }}</h1>
                                <span v-if="isLowonganActive(item.tanggal_dibuka, item.tanggal_ditutup)" 
                                    class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                    Aktif
                                </span>
                                <span v-else 
                                    class="px-3 py-1 text-sm font-medium bg-red-400 text-red-800 rounded-full">
                                    Ditutup
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 text-sm font-medium bg-gray-100 border-gray-200 border-1 text-secondary rounded-full">
                                    {{ item.jenis_lowongan }}
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
                                    <p class="font-medium">{{ formatRupiah(item.gaji) }}</p>
                                </div>
                            </div>
                            
                            <!-- Card 2: Tenaga Dibutuhkan -->
                            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                                <div class="p-2 bg-secondary/10 rounded-lg">
                                    <Users class="w-5 h-5 text-secondary" />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tenaga Dibutuhkan</p>
                                    <p class="font-medium">{{ item.tenaga_dibutuhkan }} orang</p>
                                </div>
                            </div>
                            
                            <!-- Card 3: Tanggal Dibuka -->
                            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                                <div class="p-2 bg-secondary/10 rounded-lg">
                                    <CalendarDays class="w-5 h-5 text-secondary" />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Dibuka Sejak</p>
                                    <p class="font-medium">{{ formatTanggal(item.tanggal_dibuka) }}</p>
                                </div>
                            </div>
                            
                            <!-- Card 4: Tanggal Ditutup -->
                            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
                                <div class="p-2 bg-secondary/10 rounded-lg">
                                    <Clock class="w-5 h-5 text-secondary" />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Ditutup Pada</p>
                                    <p class="font-medium">{{ formatTanggal(item.tanggal_ditutup) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lowongan Description -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Deskripsi Pekerjaan</h2>
                            <div class="prose max-w-none" v-html="item.deskripsi_pekerjaan"></div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Apply Form -->
                    <div class="w-full lg:w-1/3">
                        <div class="sticky top-24 bg-gray-50 border border-gray-100 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold mb-4">Lamar Posisi Ini</h3>
                            
                            <div v-if="isLowonganActive(item.tanggal_dibuka, item.tanggal_ditutup)">
                                <form @submit.prevent="submitApplication" class="space-y-4">
                                    <!-- Nama Lengkap -->
                                    <div>
                                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="nama_lengkap"
                                            v-model="formData.nama_lengkap"
                                            type="text"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary"
                                            required
                                        />
                                    </div>
                                    
                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="email"
                                            v-model="formData.email"
                                            type="email"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary"
                                            required
                                        />
                                    </div>
                                    
                                    <!-- Nomor Telepon -->
                                    <div>
                                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nomor Telepon <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="nomor_telepon"
                                            v-model="formData.nomor_telepon"
                                            type="tel"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary"
                                            required
                                        />
                                    </div>
                                    
                                    <!-- CV Upload -->
                                    <div>
                                        <label for="cv_file" class="block text-sm font-medium text-gray-700 mb-1">
                                            Upload CV (PDF/DOC/DOCX) <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            id="cv_file"
                                            type="file"
                                            @change="handleFileUpload"
                                            accept=".pdf,.doc,.docx"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
                                            required
                                        />
                                    </div>
                                    
                                    <!-- Cover Letter -->
                                    <div>
                                        <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-1">
                                            Cover Letter
                                        </label>
                                        <textarea
                                            id="cover_letter"
                                            v-model="formData.cover_letter"
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary"
                                        ></textarea>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <button
                                        type="submit"
                                        :disabled="isApplying"
                                        class="w-full bg-secondary hover:bg-black transition-colors duration-300 text-white py-2.5 px-4 rounded-full font-medium flex items-center justify-center gap-2"
                                    >
                                        <Send v-if="!isApplying" class="w-4 h-4" />
                                        <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ isApplying ? 'Mengirim...' : 'Kirim Lamaran' }}
                                    </button>
                                </form>
                                <p class="text-xs text-gray-500 mt-4">
                                    Field dengan tanda <span class="text-red-500">*</span> wajib diisi
                                </p>
                            </div>
                            
                            <!-- Show when lowongan is not active -->
                            <div v-else class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <p class="text-red-800 font-medium">Lowongan ini sudah tidak menerima lamaran.</p>
                                <p class="text-sm text-red-700 mt-1">Silakan cek lowongan lainnya yang masih dibuka.</p>
                                <Link 
                                    href="/lowongan" 
                                    class="mt-3 inline-block px-4 py-2 bg-red-500 hover:bg-black transition duration-200 text-white rounded-full text-sm font-medium">
                                    Lihat Lowongan Lainnya
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- No Data Found -->
            <div v-else class="w-full max-w-3xl mx-auto text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-xl font-medium text-gray-900">Lowongan tidak ditemukan</h3>
                <p class="mt-1 text-gray-500">Lowongan mungkin sudah tidak tersedia atau telah dihapus.</p>
                <div class="mt-6">
                    <Link href="/lowongan" class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-secondary hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                        Kembali ke Daftar Lowongan
                    </Link>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Success icon -->
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Lamaran Berhasil Dikirim!
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Terima kasih telah mengirimkan lamaran Anda. Tim kami akan meninjau lamaran Anda dan akan menghubungi jika memenuhi kualifikasi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="closeModal" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-4 py-2 bg-secondary text-base font-medium text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>