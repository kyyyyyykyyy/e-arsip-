<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';

const props = defineProps({ documents: Array });
const user = usePage().props.auth.user;
const isAdmin = computed(() => user.role === 'admin');
// Tambahkan computed property untuk cek role viewer
const isViewer = computed(() => user.role === 'viewer');

// Hitungan Statistik SIDEPPA
const totalArsip = computed(() => props.documents ? props.documents.length : 0);
const totalDPA_RKA = computed(() => props.documents ? props.documents.filter(d => ['DPA', 'RKA'].includes(d.category)).length : 0);
const totalRenja = computed(() => props.documents ? props.documents.filter(d => d.category === 'Renja').length : 0);
const totalLaporan = computed(() => props.documents ? props.documents.filter(d => ['Laporan Bulanan', 'Laporan Triwulanan'].includes(d.category)).length : 0);

// Grafik Dinamis (Sinkron dengan Database)
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
const dynamicChartData = computed(() => {
    let dataCounts = new Array(12).fill(0);
    if (props.documents && props.documents.length > 0) {
        props.documents.forEach(doc => {
            const date = new Date(doc.document_date);
            if (date.getFullYear() === new Date().getFullYear()) {
                dataCounts[date.getMonth()]++;
            }
        });
    }
    const maxVal = Math.max(...dataCounts) || 1;
    return dataCounts.map(count => ({
        count: count,
        percent: (count / maxVal) * 100
    }));
});

// Animasi Halaman
const pageLoaded = ref(false);
const chartReady = ref(false);

onMounted(() => {
    setTimeout(() => { pageLoaded.value = true; }, 100);
    setTimeout(() => { chartReady.value = true; }, 400);
});

// ==========================================
// LOGIKA MODAL & FORM UPLOAD (SEKARANG UNTUK ADMIN & VIEWER)
// ==========================================
const isModalOpen = ref(false);

const form = useForm({
    document_number: '',
    title: '',
    category: 'DPA',
    document_date: '',
    bidang: '', // Form input Asal Bidang
    description: '',
    file: null,
});

const openModal = () => { isModalOpen.value = true; };
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const handleFileChange = (event) => {
    form.file = event.target.files[0];
};

const submitArsip = () => {
    form.post(route('documents.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
    });
};
// ==========================================
</script>

<template>
    <Head title="Dashboard SIDEPPA" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto overflow-hidden p-2 relative">

            <div v-if="$page.props.flash && $page.props.flash.message" class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ $page.props.flash.message }}</span>
            </div>

            <div :class="pageLoaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'" class="transition-all duration-700 ease-out mb-8 flex flex-col md:flex-row md:items-center md:justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Selamat datang kembali, <span class="text-amber-500">{{ user.name }}</span>! 👋
                    </h2>
                    <p class="text-slate-500 mt-1 text-sm">
                        Ringkasan aktivitas dokumen Perencanaan & Pelaporan Satpol PP dan WH Aceh.
                    </p>
                </div>
                <!-- Ubah v-if disini -->
                <div v-if="isAdmin || isViewer" class="mt-4 md:mt-0">
                    <button @click="openModal" class="inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-amber-500 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-amber-500/50 transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Upload Dokumen Baru
                    </button>
                </div>
            </div>

            <div :class="pageLoaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'" class="transition-all duration-700 delay-100 ease-out grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white group cursor-default transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/40 hover:scale-[1.02]">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10 blur-2xl group-hover:bg-white/20 transition duration-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider mb-1">Total Dokumen</p>
                            <h3 class="text-4xl font-extrabold transition-all duration-300 group-hover:scale-110 origin-left">{{ totalArsip }}</h3>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white group cursor-default transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-500/40 hover:scale-[1.02]">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10 blur-2xl group-hover:bg-white/20 transition duration-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wider mb-1">DPA & RKA</p>
                            <h3 class="text-4xl font-extrabold transition-all duration-300 group-hover:scale-110 origin-left">{{ totalDPA_RKA }}</h3>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white group cursor-default transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-amber-500/40 hover:scale-[1.02]">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10 blur-2xl group-hover:bg-white/20 transition duration-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-amber-100 text-sm font-semibold uppercase tracking-wider mb-1">Rencana Kerja (Renja)</p>
                            <h3 class="text-4xl font-extrabold transition-all duration-300 group-hover:scale-110 origin-left">{{ totalRenja }}</h3>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-indigo-700 rounded-2xl p-6 text-white group cursor-default transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/40 hover:scale-[1.02]">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10 blur-2xl group-hover:bg-white/20 transition duration-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider mb-1">Laporan Kinerja</p>
                            <h3 class="text-4xl font-extrabold transition-all duration-300 group-hover:scale-110 origin-left">{{ totalLaporan }}</h3>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="pageLoaded ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'" class="transition-all duration-700 delay-200 ease-out bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8 hover:shadow-md">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-bold text-slate-800">Aktivitas Pangkalan Data ({{ new Date().getFullYear() }})</h3>
                    <span v-if="totalArsip === 0" class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Belum Ada Data</span>
                    <span v-else class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Data Tersinkronisasi</span>
                </div>

                <div class="relative h-64 flex items-end justify-between gap-2 sm:gap-4 border-b border-slate-200 pb-2">
                    <div v-for="(data, index) in dynamicChartData" :key="index" class="w-full relative group h-full flex flex-col justify-end">
                        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10 whitespace-nowrap">
                            {{ data.count }} Dokumen
                        </div>
                        <div class="w-full bg-slate-50 rounded-t-md relative overflow-hidden h-full group-hover:bg-slate-100 transition-colors">
                            <div
                                :style="chartReady ? `height: ${data.percent}%` : 'height: 0%'"
                                class="absolute bottom-0 w-full bg-gradient-to-t from-blue-600 to-cyan-400 rounded-t-md transition-all duration-1000 ease-out group-hover:from-amber-500 group-hover:to-yellow-400">
                            </div>
                        </div>
                        <span class="text-center text-xs font-medium text-slate-500 mt-3 hidden sm:block">{{ months[index] }}</span>
                        <span class="text-center text-xs font-medium text-slate-500 mt-3 sm:hidden">{{ months[index].charAt(0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Ubah v-if disini juga untuk modal -->
            <transition name="modal-fade" v-if="isAdmin || isViewer">
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal"></div>

                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Dokumen Perencanaan & Pelaporan
                            </h3>
                            <button @click="closeModal" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                            <form @submit.prevent="submitArsip" class="space-y-5">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Dokumen</label>
                                        <input v-model="form.document_number" type="text" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" placeholder="Contoh: 001/DPA/2026" required>
                                        <div v-if="form.errors.document_number" class="text-red-500 text-xs mt-1">{{ form.errors.document_number }}</div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Dokumen</label>
                                        <input v-model="form.document_date" type="date" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" required>
                                        <div v-if="form.errors.document_date" class="text-red-500 text-xs mt-1">{{ form.errors.document_date }}</div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kegiatan / Judul Laporan</label>
                                    <input v-model="form.title" type="text" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" placeholder="Masukkan judul dokumen..." required>
                                    <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Dokumen</label>
                                        <select v-model="form.category" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm cursor-pointer bg-slate-50">
                                            <option value="DPA">DPA</option>
                                            <option value="RKA">RKA</option>
                                            <option value="Renja">Renja</option>
                                            <option value="Laporan Bulanan">Laporan Bulanan</option>
                                            <option value="Laporan Triwulanan">Laporan Triwulanan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Asal Bidang</label>
                                        <input v-model="form.bidang" type="text" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" placeholder="Contoh: Bidang Trantibum" required>
                                        <div v-if="form.errors.bidang" class="text-red-500 text-xs mt-1">{{ form.errors.bidang }}</div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">File Dokumen (Max 10MB)</label>
                                    <input @change="handleFileChange" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,image/*,.zip,.rar" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-colors cursor-pointer border border-slate-200 rounded-lg" required>
                                    <div v-if="form.errors.file" class="text-red-500 text-xs mt-1">{{ form.errors.file }}</div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3 mt-6">
                                    <button type="button" @click="closeModal" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" :disabled="form.processing" class="px-5 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-amber-500 focus:ring-4 focus:ring-amber-500/30 transition-all disabled:opacity-50 flex items-center">
                                        <span v-if="form.processing" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Menyimpan...
                                        </span>
                                        <span v-else>Simpan Dokumen</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </transition>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: all 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; transform: scale(0.95); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(-10px); }
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #f59e0b; }
</style>