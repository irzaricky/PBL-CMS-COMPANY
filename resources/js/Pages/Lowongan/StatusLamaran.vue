<script setup>
import { ref } from "vue";
import axios from "axios";
import { RefreshCw, CheckCircle } from "lucide-vue-next";

const props = defineProps({
    application: {
        type: Object,
        required: true,
    },
    formatTanggal: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["update:application"]);
const isRefreshing = ref(false);

async function refreshApplicationStatus() {
    if (!props.application || !props.application.id_lamaran) {
        return;
    }

    try {
        isRefreshing.value = true;

        const response = await axios.get(
            `/api/lamaran/${props.application.id_lamaran}`,
            {
                // Add cache busting parameter to ensure we get fresh data
                params: {
                    _: Date.now(),
                },
            }
        );

        if (
            response.data &&
            response.data.status === "success" &&
            response.data.data
        ) {
            emit("update:application", response.data.data);

            // Show success message if status changed
            if (
                response.data.data.status_lamaran !==
                props.application.status_lamaran
            ) {
                console.log(
                    "Status updated:",
                    response.data.data.status_lamaran
                );
            }
        }
    } catch (err) {
        console.error("Error refreshing application status:", err);
        // You might want to show an error message to user here
    } finally {
        isRefreshing.value = false;
    }
}
</script>

<template>
    <div class="bg-blue-50 border border-blue-100 rounded-lg p-5">
        <div class="flex items-center justify-center mb-4">
            <div class="p-2 rounded-full bg-blue-100">
                <CheckCircle class="w-6 h-6 text-blue-600" />
            </div>
        </div>

        <h4 class="text-center text-lg font-medium text-gray-800 mb-2">
            Anda Sudah Melamar
        </h4>
        <p class="text-center text-gray-600 mb-4">
            Anda telah mengirimkan lamaran untuk posisi ini pada
            {{ formatTanggal(application.created_at) }}.
        </p>

        <div class="bg-white rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm text-gray-500">Status Lamaran:</p>
                <button
                    @click="refreshApplicationStatus"
                    :disabled="isRefreshing"
                    class="text-xs text-secondary hover:text-black flex items-center gap-1 transition-colors"
                >
                    <RefreshCw
                        :class="{ 'animate-spin': isRefreshing }"
                        class="w-3 h-3"
                    />
                    {{ isRefreshing ? "Memuat..." : "Perbarui" }}
                </button>
            </div>
            <div class="flex justify-center">
                <span
                    :class="{
                        'px-3 py-1 rounded-full text-sm font-medium': true,
                        'bg-blue-100 text-blue-800':
                            application.status_lamaran === 'Diproses',
                        'bg-green-100 text-green-800':
                            application.status_lamaran === 'Diterima',
                        'bg-red-100 text-red-800':
                            application.status_lamaran === 'Ditolak',
                    }"
                >
                    {{ application.status_lamaran }}
                </span>
            </div>
        </div>

        <div class="text-center">
            <p class="text-sm text-gray-500">
                Tim kami akan menghubungi Anda melalui email jika ada
                perkembangan.
            </p>
        </div>
    </div>
</template>
