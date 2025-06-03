<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, computed, onBeforeUnmount } from 'vue'
import axios from 'axios'
import { usePage, Link } from '@inertiajs/vue3'
import LamaranTerkirim from '@/Components/Modal/LamaranTerkirim.vue'
import LowonganDetail from '@/Pages/Lowongan/LowonganDetail.vue'
import FormLamaran from '@/Pages/Lowongan/FormLamaran.vue'
import StatusLamaran from '@/Pages/Lowongan/StatusLamaran.vue'
import LoginOverlay from '@/Pages/Lowongan/LoginOverlay.vue'
import LowonganTutup from '@/Pages/Lowongan/LowonganTutup.vue'
import { Wallet } from 'lucide-vue-next'

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
const userApplication = ref(null)
const checkingApplication = ref(false)

// === LIFECYCLE
onMounted(() => {
    fetchLowongan()
    document.documentElement.style.setProperty('--color-secondary', theme.value.secondary)
    
    if (isLoggedIn.value && slug) {
        checkUserApplication()
    }
    
    // Refresh status saat tab menjadi aktif kembali
    const refreshOnVisibilityChange = () => {
        if (document.visibilityState === 'visible' && userApplication.value) {
            refreshApplicationStatus()
        }
    }
    
    document.addEventListener('visibilitychange', refreshOnVisibilityChange)
    
    // Cleanup event listener
    onBeforeUnmount(() => {
        document.removeEventListener('visibilitychange', refreshOnVisibilityChange)
    })
})

// === FUNCTIONS
async function checkUserApplication() {
    if (!user.value?.id_user) return
    
    try {
        checkingApplication.value = true
        const response = await axios.get(`/api/lamaran/user/${user.value.id_user}`)
        
        // Cek apakah user sudah melamar untuk lowongan ini
        if (response.data && Array.isArray(response.data.data)) {
            const existingApplication = response.data.data.find(
                application => application.lowongan.id_lowongan === item.value?.id_lowongan
            )
            
            if (existingApplication) {
                userApplication.value = existingApplication
                console.log('User already applied:', existingApplication)
            }
        }
    } catch (err) {
        console.error('Error checking user application:', err)
    } finally {
        checkingApplication.value = false
    }
}

async function fetchLowongan() {
    try {
        loading.value = true
        const response = await axios.get(`/api/lowongan/${slug}`)
        
        if (response.data) {
            // LowonganViewResource structure (probably the whole response is the lowongan)
            if (response.data.id_lowongan) {
                item.value = response.data
                // Periksa lamaran user setelah data lowongan didapat
                if (isLoggedIn.value) {
                    checkUserApplication()
                }
            } 
            // Response might be wrapped in a data property
            else if (response.data.data && response.data.data.id_lowongan) {
                item.value = response.data.data
                // Periksa lamaran user setelah data lowongan didapat
                if (isLoggedIn.value) {
                    checkUserApplication()
                }
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

function handleApplicationSuccess() {
    showSuccessModal.value = true
    checkUserApplication()
}

function closeModal() {
    showSuccessModal.value = false
}

function updateUserApplication(newApplication) {
    userApplication.value = newApplication
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
                <!-- Skeleton template here (unchanged) -->
            </div>

            <!-- Actual Lowongan Detail - Show when not loading -->
            <LowonganDetail 
                v-else-if="item" 
                :lowongan="item"
                :formatTanggal="formatTanggal"
                :formatRupiah="formatRupiah"
                :isLowonganActive="isLowonganActive"
            >
                <!-- Right Column Slot -->
                <div class="w-full lg:w-1/3">
                    <div class="sticky top-24 bg-gray-50 border border-gray-100 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-4">Lamar Posisi Ini</h3>
                        
                        <div v-if="isLowonganActive(item.tanggal_dibuka, item.tanggal_ditutup)">
                            <!-- Login Overlay - Show when user is not logged in -->
                            <LoginOverlay v-if="!isLoggedIn" />
                            
                            <!-- Checking Application Status -->
                            <div v-else-if="checkingApplication" class="py-8 flex flex-col items-center justify-center">
                                <svg class="animate-spin h-8 w-8 text-secondary mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-gray-600">Memeriksa status lamaran...</p>
                            </div>
                            
                            <!-- Already Applied - Display Application Status -->
                            <StatusLamaran 
                                v-else-if="userApplication" 
                                :application="userApplication" 
                                :formatTanggal="formatTanggal"
                                @update:application="updateUserApplication" 
                            />
                            
                            <!-- Application Form - Show when user is logged in and hasn't applied -->
                            <FormLamaran 
                                v-else 
                                :lowongan="item" 
                                :user="user" 
                                @success="handleApplicationSuccess" 
                            />
                        </div>
                        
                        <!-- Show when lowongan is not active -->
                        <LowonganTutup v-else />
                    </div>
                </div>
            </LowonganDetail>
            
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

        <!-- Success Modal Component -->
        <LamaranTerkirim 
            :show="showSuccessModal" 
            :auto-close="false"
            @close="closeModal"
        />
    </AppLayout>
</template>