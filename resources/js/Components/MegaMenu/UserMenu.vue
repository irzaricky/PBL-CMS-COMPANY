<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { User, LayoutDashboard, LogOut } from "lucide-vue-next";

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour >= 5 && hour < 12) return "Selamat pagi";
    if (hour >= 12 && hour < 17) return "Selamat siang";
    if (hour >= 17 && hour < 21) return "Selamat sore";
    return "Selamat malam";
});

const canAccessPanel = computed(() => {
    const allowedStatuses = ["Tetap", "Kontrak", "Magang"];
    return allowedStatuses.includes(user.value?.status_kepegawaian ?? "");
});
</script>

<template>
    <div
        class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-lg z-40 p-4 font-custom"
    >
        <div class="mb-4">
            <p class="text-xs text-gray-500">{{ greeting }},</p>
            <p class="text-base font-semibold text-gray-800">
                {{ user?.name ?? "Pengguna" }}
            </p>
        </div>
        <ul class="space-y-3 text-sm text-black">
            <li class="flex items-center gap-2">
                <User class="w-4 h-4" />
                <a href="/admin/profile" class="hover:underline">Ubah Profil</a>
            </li>
            <li v-if="canAccessPanel" class="flex items-center gap-2">
                <LayoutDashboard class="w-4 h-4" />
                <a href="/admin" class="hover:underline">Panel Admin</a>
            </li>
            <li class="flex items-center gap-2">
                <LogOut class="w-4 h-4 text-red-600" />
                <a href="/logout" class="text-red-600 hover:underline"
                    >Logout</a
                >
            </li>
        </ul>
    </div>
</template>
