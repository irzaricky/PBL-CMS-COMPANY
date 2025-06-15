<script setup>
import { ref } from "vue";
import axios from "axios";
import { Send, Loader, Eye, X, FileText, File, Archive } from "lucide-vue-next";

const props = defineProps({
    lowongan: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["success"]);

const isApplying = ref(false);
const previewFile = ref(null);
const previewType = ref("");
const showPreview = ref(false);
const formData = ref({
    nama_lengkap: props.user?.name || "",
    email: props.user?.email || "",
    surat_lamaran: null,
    cv: null,
    portfolio: null,
    pesan_pelamar: "",
});

// File preview URLs
const filePreviewUrls = ref({
    surat_lamaran: null,
    cv: null,
    portfolio: null,
});

function handleFileUpload(fieldName) {
    return (event) => {
        const file = event.target.files[0];
        if (file) {
            formData.value[fieldName] = file;

            // Create preview URL
            if (filePreviewUrls.value[fieldName]) {
                URL.revokeObjectURL(filePreviewUrls.value[fieldName]);
            }
            filePreviewUrls.value[fieldName] = URL.createObjectURL(file);
        }
    };
}

function openPreview(fieldName) {
    const file = formData.value[fieldName];
    if (file) {
        previewFile.value = filePreviewUrls.value[fieldName];
        previewType.value = file.type;
        showPreview.value = true;
    }
}

function closePreview() {
    showPreview.value = false;
    previewFile.value = null;
    previewType.value = "";
}

function removeFile(fieldName) {
    formData.value[fieldName] = null;
    if (filePreviewUrls.value[fieldName]) {
        URL.revokeObjectURL(filePreviewUrls.value[fieldName]);
        filePreviewUrls.value[fieldName] = null;
    }
    document.getElementById(fieldName).value = "";
}

function getFileIcon(file) {
    if (!file) return FileText;
    const extension = file.name.split(".").pop().toLowerCase();
    switch (extension) {
        case "pdf":
            return FileText;
        case "doc":
        case "docx":
            return File;
        case "zip":
            return Archive;
        default:
            return FileText;
    }
}

async function submitApplication() {
    if (
        !formData.value.nama_lengkap ||
        !formData.value.email ||
        !formData.value.surat_lamaran ||
        !formData.value.cv
    ) {
        alert("Silakan lengkapi semua field yang diperlukan");
        return;
    }

    try {
        isApplying.value = true;

        const submitData = new FormData();
        submitData.append("id_lowongan", props.lowongan.id_lowongan);

        if (props.user?.id_user) {
            submitData.append("id_user", props.user.id_user);
        } else {
            alert("Anda harus login terlebih dahulu untuk melamar");
            return;
        }

        submitData.append("surat_lamaran", formData.value.surat_lamaran);
        submitData.append("cv", formData.value.cv);

        if (formData.value.portfolio) {
            submitData.append("portfolio", formData.value.portfolio);
        }

        submitData.append("pesan_pelamar", formData.value.pesan_pelamar);

        await axios.post("/api/lamaran", submitData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        // Cleanup preview URLs
        Object.values(filePreviewUrls.value).forEach((url) => {
            if (url) URL.revokeObjectURL(url);
        });

        // Reset form
        formData.value = {
            nama_lengkap: props.user?.name || "",
            email: props.user?.email || "",
            surat_lamaran: null,
            cv: null,
            portfolio: null,
            pesan_pelamar: "",
        };

        filePreviewUrls.value = {
            surat_lamaran: null,
            cv: null,
            portfolio: null,
        };

        // Reset file inputs
        document.getElementById("surat_lamaran").value = "";
        document.getElementById("cv").value = "";
        if (document.getElementById("portfolio")) {
            document.getElementById("portfolio").value = "";
        }

        // Emit success event for parent component with response data
        emit("success", response.data);

        console.log("Lamaran berhasil dikirim:", response.data);
    } catch (err) {
        alert(
            "Gagal mengirim lamaran: " +
                (err.response?.data?.message || err.message)
        );
        console.error(err);
    } finally {
        isApplying.value = false;
    }
}
</script>

<template>
    <form @submit.prevent="submitApplication" class="space-y-4">
        <!-- Nama Lengkap -->
        <div>
            <label
                for="nama_lengkap"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
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
            <label
                for="email"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
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
            <label
                for="surat_lamaran"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Surat Lamaran (.pdf/.doc/.docx)<span class="text-red-500"
                    >*</span
                >
            </label>
            <input
                id="surat_lamaran"
                type="file"
                @change="handleFileUpload('surat_lamaran')($event)"
                accept=".pdf,.doc,.docx"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
                required
            />
            <!-- File Preview Card -->
            <div
                v-if="formData.surat_lamaran"
                class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <component
                            :is="getFileIcon(formData.surat_lamaran)"
                            class="w-5 h-5 text-gray-600"
                        />
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ formData.surat_lamaran.name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{
                                    (
                                        formData.surat_lamaran.size /
                                        1024 /
                                        1024
                                    ).toFixed(2)
                                }}
                                MB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="openPreview('surat_lamaran')"
                            class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                            title="Preview file"
                        >
                            <Eye class="w-4 h-4" />
                        </button>
                        <button
                            type="button"
                            @click="removeFile('surat_lamaran')"
                            class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded"
                            title="Hapus file"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CV Upload -->
        <div>
            <label
                for="cv"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                CV (.pdf/.doc/.docx)<span class="text-red-500">*</span>
            </label>
            <input
                id="cv"
                type="file"
                @change="handleFileUpload('cv')($event)"
                accept=".pdf,.doc,.docx"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
                required
            />
            <!-- File Preview Card -->
            <div
                v-if="formData.cv"
                class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <component
                            :is="getFileIcon(formData.cv)"
                            class="w-5 h-5 text-gray-600"
                        />
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ formData.cv.name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{
                                    (formData.cv.size / 1024 / 1024).toFixed(2)
                                }}
                                MB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="openPreview('cv')"
                            class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                            title="Preview file"
                        >
                            <Eye class="w-4 h-4" />
                        </button>
                        <button
                            type="button"
                            @click="removeFile('cv')"
                            class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded"
                            title="Hapus file"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio (optional) -->
        <div>
            <label
                for="portfolio"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Portfolio (.pdf/.doc/.docx)
                <span class="text-gray-500 text-xs">(opsional)</span>
            </label>
            <input
                id="portfolio"
                type="file"
                @change="handleFileUpload('portfolio')($event)"
                accept=".pdf,.doc,.docx,.zip"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-secondary focus:border-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20"
            />
            <!-- File Preview Card -->
            <div
                v-if="formData.portfolio"
                class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <component
                            :is="getFileIcon(formData.portfolio)"
                            class="w-5 h-5 text-gray-600"
                        />
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ formData.portfolio.name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{
                                    (
                                        formData.portfolio.size /
                                        1024 /
                                        1024
                                    ).toFixed(2)
                                }}
                                MB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="openPreview('portfolio')"
                            class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                            title="Preview file"
                        >
                            <Eye class="w-4 h-4" />
                        </button>
                        <button
                            type="button"
                            @click="removeFile('portfolio')"
                            class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded"
                            title="Hapus file"
                        >
                            <X class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesan Pelamar -->
        <div>
            <label
                for="pesan_pelamar"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Pesan Lamaran
                <span class="text-gray-500 text-xs">(opsional)</span>
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
            <Loader v-else class="animate-spin h-5 w-5" />
            {{ isApplying ? "Mengirim..." : "Kirim Lamaran" }}
        </button>
    </form>

    <!-- File Preview Modal -->
    <div
        v-if="showPreview"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
        <div
            class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden"
        >
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-medium">Preview File</h3>
                <button
                    @click="closePreview"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full"
                >
                    <X class="w-5 h-5" />
                </button>
            </div>
            <div class="p-4 h-[70vh] overflow-auto">
                <!-- PDF Preview -->
                <iframe
                    v-if="previewType === 'application/pdf'"
                    :src="previewFile"
                    class="w-full h-full border-0"
                    title="PDF Preview"
                ></iframe>

                <!-- DOC/DOCX Preview (fallback message) -->
                <div
                    v-else-if="
                        previewType.includes('document') ||
                        previewType.includes('word')
                    "
                    class="flex flex-col items-center justify-center h-full text-gray-500"
                >
                    <FileText class="w-16 h-16 mb-4" />
                    <p class="text-lg font-medium mb-2">
                        Preview tidak tersedia
                    </p>
                    <p class="text-sm text-center">
                        File Word/DOC tidak dapat di-preview di browser.<br />Silakan
                        download untuk melihat isi file.
                    </p>
                    <a
                        :href="previewFile"
                        :download="true"
                        class="mt-4 px-4 py-2 bg-secondary text-white rounded-lg hover:bg-black transition-colors"
                    >
                        Download File
                    </a>
                </div>

                <!-- Other file types -->
                <div
                    v-else
                    class="flex flex-col items-center justify-center h-full text-gray-500"
                >
                    <FileText class="w-16 h-16 mb-4" />
                    <p class="text-lg font-medium mb-2">
                        Preview tidak tersedia
                    </p>
                    <p class="text-sm text-center">
                        Tipe file ini tidak dapat di-preview di browser.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <p class="text-xs text-gray-500 mt-4">
        Field dengan tanda <span class="text-red-500">*</span> wajib diisi
    </p>
</template>
