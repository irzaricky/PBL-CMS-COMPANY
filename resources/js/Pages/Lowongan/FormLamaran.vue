<script setup>
import { ref } from 'vue'
import { Send } from 'lucide-vue-next'

const props = defineProps({
    lowongan: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['success'])

const isApplying = ref(false)
const formData = ref({
    nama_lengkap: props.user?.name || '',
    email: props.user?.email || '',
    surat_lamaran: null,
    cv: null,
    portfolio: null,
    pesan_pelamar: '',
})

function handleFileUpload(fieldName) {
    return (event) => {
        formData.value[fieldName] = event.target.files[0]
    }
}

async function submitApplication() {
    if (!formData.value.nama_lengkap || !formData.value.email || !formData.value.surat_lamaran || !formData.value.cv) {
        alert('Silakan lengkapi semua field yang diperlukan')
        return
    }
    
    try {
        isApplying.value = true
        
        const submitData = new FormData()
        submitData.append('id_lowongan', props.lowongan.id_lowongan)
        
        if (props.user?.id_user) {
            submitData.append('id_user', props.user.id_user)
        } else {
            alert('Anda harus login terlebih dahulu untuk melamar')
            return
        }
        
        submitData.append('surat_lamaran', formData.value.surat_lamaran)
        submitData.append('cv', formData.value.cv)
        
        if (formData.value.portfolio) {
            submitData.append('portfolio', formData.value.portfolio)
        }
        
        submitData.append('pesan_pelamar', formData.value.pesan_pelamar)
        
        await axios.post('/api/lamaran', submitData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        
        // Reset form
        formData.value = {
            nama_lengkap: props.user?.name || '',
            email: props.user?.email || '',
            surat_lamaran: null,
            cv: null,
            portfolio: null,
            pesan_pelamar: '',
        }
        
        // Reset file inputs
        document.getElementById('surat_lamaran').value = ''
        document.getElementById('cv').value = ''
        if (document.getElementById('portfolio')) {
            document.getElementById('portfolio').value = ''
        }
        
        // Emit success event for parent component
        emit('success')
        
    } catch (err) {
        alert('Gagal mengirim lamaran: ' + (err.response?.data?.message || err.message))
        console.error(err)
    } finally {
        isApplying.value = false
    }
}
</script>

<template>
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
        
        <!-- Surat Lamaran -->
        <div>
            <label for="surat_lamaran" class="block text-sm font-medium text-gray-700 mb-1">
                Surat Lamaran (PDF/DOC/DOCX) <span class="text-red-500">*</span>
            </label>
            <input
                id="surat_lamaran"
                type="file"
                @change="handleFileUpload('surat_lamaran')($event)"
                accept=".pdf,.doc,.docx"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
                required
            />
        </div>
        
        <!-- CV Upload -->
        <div>
            <label for="cv" class="block text-sm font-medium text-gray-700 mb-1">
                CV (PDF/DOC/DOCX) <span class="text-red-500">*</span>
            </label>
            <input
                id="cv"
                type="file"
                @change="handleFileUpload('cv')($event)"
                accept=".pdf,.doc,.docx"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
                required
            />
        </div>
        
        <!-- Portfolio (optional) -->
        <div>
            <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1">
                Portfolio (PDF/DOC/DOCX/ZIP) <span class="text-gray-500 text-xs">(opsional)</span>
            </label>
            <input
                id="portfolio"
                type="file"
                @change="handleFileUpload('portfolio')($event)"
                accept=".pdf,.doc,.docx,.zip"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
            />
        </div>
        
        <!-- Pesan Pelamar -->
        <div>
            <label for="pesan_pelamar" class="block text-sm font-medium text-gray-700 mb-1">
                Pesan Lamaran <span class="text-gray-500 text-xs">(opsional)</span>
            </label>
            <textarea
                id="pesan_pelamar"
                v-model="formData.pesan_pelamar"
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
</template>