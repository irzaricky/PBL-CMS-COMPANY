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
        <div class="w-full px-4 lg:px-16 py-20 text-white font-custom">
            <div class="max-w-screen-xl mx-auto flex flex-col lg:flex-row overflow-hidden outline outline-2 outline-secondary rounded-lg">
                <!-- Gambar -->
                <img class="w-full lg:w-1/2 bg-secondary object-contain" src="image/Feedback-white.svg"
                    alt="Contact" />

                <!-- Form -->
                <div class="w-full lg:w-1/2 flex flex-col gap-8 p-8 bg-primary">

                    <!-- Heading -->
                    <div class="flex flex-col gap-6 text-secondary">
                        <div class="text-custom text-base font-semibold">Hubungi kami lewat</div>
                        <div class="flex flex-col gap-4">
                            <h2 class="text-custom text-3xl lg:text-5xl font-bold leading-tight">Feedback Anda!</h2>
                            <p class="text-custom text-base lg:text-lg font-normal">
                                Kami tumbuh lewat feedback Anda. Beri kami masukan untuk meningkatkan layanan kami.
                            </p>
                        </div>
                    </div>

                    <!-- Jika belum login -->
                    <div v-if="!isLoggedIn" class="flex flex-col gap-4 text-center text-secondary">
                        <p class="text-lg">Mohon login terlebih dahulu untuk memberikan feedback.</p>
                        <a href="/login"
                            class="inline-block bg-third px-6 py-2.5 rounded-full font-semibold hover:bg-third/50 transition">
                            Login
                        </a>
                    </div>

                    <!-- Jika sudah login -->
                    <form v-else @submit.prevent="submitForm" class="flex flex-col gap-6 text-secondary">
                        <!-- Nama -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-bold">Name</label>
                            <input v-model="form.name" type="text"
                                class="w-full px-3 py-2 bg-white text-typography-dark rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="Your name" required />
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-bold">Email</label>
                            <input v-model="form.email" type="email"
                                class="w-full px-3 py-2 bg-white text-typography-dark rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="you@example.com" required />
                        </div>

                        <!-- Message -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-bold">Message</label>
                            <textarea v-model="form.message" rows="4"
                                class="w-full p-3 bg-white text-typography-dark rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="Type your message..." required></textarea>
                        </div>

                        <!-- Rating Emoji -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-base font-bold">How satisfied are you?</label>
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
                            <span class="text-custom text-sm font-bold">
                                I accept the <span class="underline">Terms</span>
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-secondary text-primary px-6 py-2.5 rounded-full font-semibold hover:bg-secondary/80 transition">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!--Hasil Feedback-->
        <div class="w-full px-4 lg:px-28 py-12 bg-white text-secondary font-custom">
            <div class="mt-2 flex flex-col gap-6">
                <h3 class="text-3xl font-bold">Feedback dari Pengguna</h3>

                <div v-if="feedbackList.length === 0" class="text-gray-300">Belum ada feedback.</div>

                <div v-else class="flex flex-col gap-6">
                    <div v-for="item in feedbackList" :key="item.id_feedback"
                        class="bg-white text-typography-dark p-4 rounded-xl shadow-md">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <img :src="getImageUrl(item.user.foto_profil)" alt="Foto Profil"
                                    class="w-10 h-10 rounded-full" />
                                <div>
                                    <div class="text-base font-bold">{{ item.user.name }}</div>
                                    <div class="text-sm font-normal text-typography-dark">{{ item.user.email }}</div>
                                </div>
                            </div>
                            <div class="text-2xl">{{ emojis[item.tingkat_kepuasan - 1] }}</div>
                        </div>
                        <div class=" text-base font-bold">{{ item.subjek_feedback }}</div>
                        <p class="text-typography-dark text-sm font-normal">{{ item.isi_feedback }}</p>

                        <div v-if="item.tanggapan_feedback"
                            class="mt-4 text-typography-dark border-l-4 border-secondary p-3 rounded">
                            <div class="text-base text-secondary font-bold">Tanggapan Admin:</div>
                            <p class="text-sm font-normal text-secondary">{{ item.tanggapan_feedback }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
