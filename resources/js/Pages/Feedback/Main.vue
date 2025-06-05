<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, reactive, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const page = usePage()
const isLoggedIn = computed(() => !!page.props.auth.user)
const feedbackList = ref([])

const form = reactive({
    name: '',
    email: '',
    subjek_feedback: '',
    isi_feedback: '',
    tingkat_kepuasan: null,
    acceptTerms: false,
})

const emojis = ['😢', '😕', '😐', '😊', '😄']

onMounted(() => {
    if (isLoggedIn.value) {
        form.name = page.props.auth.user.name
        form.email = page.props.auth.user.email
    }
    fetchFeedback()
})
async function fetchFeedback() {
    try {
        const res = await axios.get('/api/feedback/')
        feedbackList.value = res.data.data
    } catch (err) {
        console.error('Gagal mengambil data feedback', err)
    }
}

async function submitForm() {
    if (!form.acceptTerms) {
        alert('Silakan setujui syarat dan ketentuan terlebih dahulu.')
        return
    }

    if (!isLoggedIn.value || !page.props.auth.user.id_user) {
        alert('Silakan login terlebih dahulu.')
        return
    }

    try {
        await axios.post('/api/feedback', {
            subjek_feedback: form.subjek_feedback,
            isi_feedback: form.isi_feedback,
            tingkat_kepuasan: form.tingkat_kepuasan,
            id_user: page.props.auth.user.id_user,
        })

        alert('Terima kasih atas feedback Anda!')

        Object.assign(form, {
            subjek_feedback: '',
            isi_feedback: '',
            tingkat_kepuasan: null,
            acceptTerms: false,
        })

        await fetchFeedback()
    } catch (err) {
        console.error(err)
        alert('Gagal mengirim feedback.')
    }
}
function getImageUrl(foto_profil) {
    if (!foto_profil) {
        return '/images/default-profile.png'
    }
    return `/storage/${foto_profil}`
}
</script>

<template>
    <AppLayout>
        <div class="w-full px-4 lg:px-16 py-20 bg-secondary text-white font-custom">
            <div class="max-w-screen-lg mx-auto flex flex-col lg:flex-row gap-10 overflow-hidden">
                <!-- Gambar -->
                <img class="w-full lg:w-1/2 object-contain" src="image/Feedback-white.svg" alt="Contact" />

                <!-- Form -->
                <div class="w-full lg:w-1/2 flex flex-col gap-8">
                    <!-- Heading -->
                    <div class="flex flex-col gap-4">
                        <div class="text-custom text-base font-semibold">Hubungi kami lewat</div>
                        <div class="flex flex-col gap-4">
                            <h2 class="text-custom text-3xl lg:text-6xl font-normal leading-tight">Feedback Anda!</h2>
                            <p class="text-custom text-base lg:text-lg">
                                Kami tumbuh lewat feedback Anda. Beri kami masukan untuk meningkatkan layanan kami.
                            </p>
                        </div>
                    </div>

                    <!-- Jika belum login -->
                    <div v-if="!isLoggedIn" class="flex flex-col gap-4 text-center">
                        <p class="text-lg">Mohon login terlebih dahulu untuk memberikan feedback.</p>
                        <a href="/login"
                            class="inline-block bg-white text-secondary px-6 py-2.5 rounded-full font-semibold hover:bg-gray-100 transition">
                            Login
                        </a>
                    </div>

                    <!-- Jika sudah login -->
                    <form v-else @submit.prevent="submitForm" class="flex flex-col gap-6">
                        <!-- Nama -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-medium">Name</label>
                            <input v-model="form.name" type="text" readonly
                                class="w-full px-3 py-2 bg-gray-100 text-black rounded-md focus:outline-none" />
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-medium">Email</label>
                            <input v-model="form.email" type="email" readonly
                                class="w-full px-3 py-2 bg-gray-100 text-black rounded-md focus:outline-none" />
                        </div>

                        <!-- Subjek -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-medium">Subjek</label>
                            <input v-model="form.subjek_feedback" type="text"
                                class="w-full px-3 py-2 bg-white text-black rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="Contoh: Kritik tentang layanan" required />
                        </div>

                        <!-- Message -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-medium">Pesan</label>
                            <textarea v-model="form.isi_feedback" rows="4"
                                class="w-full p-3 bg-white text-black rounded-md focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="Tulis masukan Anda..." required></textarea>
                        </div>

                        <!-- Rating Emoji -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-medium">Seberapa puas Anda?</label>
                            <div class="flex justify-between text-2xl lg:text-3xl px-2">
                                <label v-for="n in 5" :key="n" class="cursor-pointer transition hover:scale-110">
                                    <input type="radio" v-model="form.tingkat_kepuasan" :value="n" class="hidden" />
                                    <span :class="form.tingkat_kepuasan === n ? 'opacity-100' : 'opacity-40'">
                                        {{ emojis[n - 1] }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Checkbox -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.acceptTerms"
                                class="w-4 h-4 rounded border-gray-300 text-secondary focus:ring-secondary" required />
                            <span class="text-custom text-sm">
                                Saya menerima <span class="underline">syarat dan ketentuan</span>
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-white text-secondary px-6 py-2.5 rounded-full font-semibold hover:bg-gray-100 transition">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="w-full px-4 lg:px-16 py-20 bg-white text-secondary font-custom">
            <div class="mt-10 flex flex-col gap-6">
                <h3 class="text-2xl font-bold">Feedback dari Pengguna</h3>

                <div v-if="feedbackList.length === 0" class="text-gray-300">Belum ada feedback.</div>

                <div v-else class="flex flex-col gap-6">
                    <div v-for="item in feedbackList" :key="item.id_feedback"
                        class="bg-white text-black p-4 rounded-xl shadow-md">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <img :src="getImageUrl(item.user.foto_profil)" alt="Foto Profil"
                                    class="w-10 h-10 rounded-full" />
                                <div>
                                    <div class="font-semibold">{{ item.user.name }}</div>
                                    <div class="text-sm text-gray-500">{{ item.user.email }}</div>
                                </div>
                            </div>
                            <div class="text-xl">{{ emojis[item.tingkat_kepuasan - 1] }}</div>
                        </div>
                        <div class="text-lg font-medium">{{ item.subjek_feedback }}</div>
                        <p class="text-gray-700">{{ item.isi_feedback }}</p>

                        <div v-if="item.tanggapan_feedback"
                            class="mt-4 bg-secondary/10 border-l-4 border-secondary p-3 rounded">
                            <div class="text-sm text-secondary font-semibold">Tanggapan Admin:</div>
                            <p class="text-sm text-secondary">{{ item.tanggapan_feedback }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
