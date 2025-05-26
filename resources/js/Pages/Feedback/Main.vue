<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { reactive } from 'vue'

const form = reactive({
    name: '',
    email: '',
    message: '',
    rating: null,
    acceptTerms: false,
})

const emojis = ['😢', '😕', '😐', '😊', '😄']

function submitForm() {
    console.log('Form submitted:', form)
    alert('Thanks for your feedback!')
    Object.assign(form, {
        name: '',
        email: '',
        message: '',
        rating: null,
        acceptTerms: false,
    })
}
</script>
<template>
    <AppLayout>
        <div class="w-full px-4 lg:px-16 py-20 text-white font-custom">
            <div class="max-w-screen-xl mx-auto flex flex-col lg:flex-row overflow-hidden outline outline-2 outline-secondary rounded-lg">
                <!-- Gambar -->
                <img class="w-full lg:w-1/2 object-contain p-9 bg-secondary" src="image/Feedback-white.svg"
                    alt="Contact" />

                <!-- Form -->
                <div class="w-full lg:w-1/2 flex flex-col gap-8 p-8 bg-primary">

                    <!-- Heading -->
                    <div class="flex flex-col gap-8 text-secondary text-custom">
                        <!--<div class="text-custom text-base font-semibold">Hubungi kami lewat</div>-->
                        <div class="flex flex-col gap-4">
                            <h2 class="text-3xl lg:text-5xl font-bold leading-tight"> Berikan Feedback Anda!</h2>
                            <p class="text-base lg:text-lg">
                                Beri kami masukan untuk meningkatkan layanan kami.
                            </p>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" class="flex flex-col gap-6">
                        <!-- Nama -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-secondary text-base font-medium">Name</label>
                            <input v-model="form.name" type="text"
                                class="w-full px-3 py-2 bg-white text-black rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="Your name" required />
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-secondary text-base font-medium">Email</label>
                            <input v-model="form.email" type="email"
                                class="w-full px-3 py-2 bg-white text-black rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="you@example.com" required />
                        </div>

                        <!-- Message -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-secondary text-base font-medium">Message</label>
                            <textarea v-model="form.message" rows="4"
                                class="w-full p-3 bg-white text-black rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary"
                                placeholder="Type your message..." required></textarea>
                        </div>

                        <!-- Rating Emoji -->
                        <div class="flex flex-col gap-2">
                            <label class="text-custom text-secondary text-base font-medium">How satisfied are you?</label>
                            <div class="flex justify-between text-2xl lg:text-3xl px-2">
                                <label v-for="n in 5" :key="n" class="cursor-pointer transition hover:scale-110">
                                    <input type="radio" v-model="form.rating" :value="n" class="hidden" />
                                    <span :class="form.rating === n ? 'opacity-100' : 'opacity-40'">
                                        {{ emojis[n - 1] }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Checkbox -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.acceptTerms"
                                class="w-4 h-4 rounded border-gray-600 text-secondary focus:ring-secondary" required />
                            <span class="text-custom text-sm text-secondary">
                                I accept the <span class="underline">Terms</span>
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-secondary text-primary px-6 py-2.5 rounded-full font-semibold hover:bg-gray-100 hover:text-secondary transition">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
