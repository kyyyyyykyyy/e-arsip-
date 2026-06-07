<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    documents: Array,
    filters: Object,
    stats: Object // <-- Menangkap data statistik dari controller
});

const start_date = ref(props.filters?.start_date || '');
const end_date = ref(props.filters?.end_date || '');

const tampilkanData = () => {
    if (!start_date.value || !end_date.value) {
        alert('Bro, isi dulu tanggal dari dan sampainya!');
        return;
    }
    if (start_date.value > end_date.value) {
        alert('Tanggal mulai nggak boleh lebih besar dari tanggal akhir!');
        return;
    }
    router.get(route('laporan.index'), {
        start_date: start_date.value,
        end_date: end_date.value
    }, { preserveState: true });
};

const downloadExcel = () => {
    if (!start_date.value || !end_date.value) {
        alert('Isi dulu tanggalnya bro sebelum download!');
        return;
    }
    window.location.href = route('laporan.export', {
        start_date: start_date.value,
        end_date: end_date.value
    });
};

// Fungsi menghitung persentase untuk bar chart
const getPercent = (value, total) => {
    const v = Number(value) || 0;
    const t = Number(total) || 1; // Hindari pembagian dengan nol
    return Math.round((v / t) * 100);
};
</script>

<template>
    <Head title="Laporan Arsip" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-8 py-6">
                    <h2 class="text-2xl font-extrabold text-white flex items-center">
                        <svg class="w-7 h-7 mr-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Laporan & Ekspor Data
                    </h2>
                    <p class="text-slate-300 mt-2 text-sm">Pilih rentang tanggal untuk melihat ringkasan statistik dan mengunduh laporan.</p>
                </div>

                <!-- Filter Section -->
                <div class="p-6 border-b border-slate-100">
                    <div class="flex flex-col md:flex-row gap-6 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Dari Tanggal Surat</label>
                            <input v-model="start_date" type="date" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 px-4 py-2.5">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sampai Tanggal Surat</label>
                            <input v-model="end_date" type="date" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 px-4 py-2.5">
                        </div>
                        
                        <div class="w-full md:w-auto">
                            <button @click="tampilkanData" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div v-if="documents && documents.length > 0" class="p-6 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-5">Ringkasan Periode Ini</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
                        <!-- Total Arsip -->
                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold">Total Arsip</p>
                                    <p class="text-2xl font-bold text-slate-800">{{ stats.total }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- DPA & RKA -->
                        <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-emerald-600 font-semibold">DPA & RKA</p>
                                    <p class="text-2xl font-bold text-emerald-700">{{ stats.dpa_rka }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Renja -->
                        <div class="bg-amber-50 p-5 rounded-xl border border-amber-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-amber-600 font-semibold">Renja</p>
                                    <p class="text-2xl font-bold text-amber-700">{{ stats.renja }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan -->
                        <div class="bg-purple-50 p-5 rounded-xl border border-purple-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-600 font-semibold">Laporan Kinerja</p>
                                    <p class="text-2xl font-bold text-purple-700">{{ stats.laporan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Chart -->
                    <div class="mt-6">
                        <div class="w-full bg-slate-200 rounded-full h-3.5 overflow-hidden">
                            <div 
                                :style="`width: ${getPercent(stats.dpa_rka, stats.total)}%`"
                                class="h-full bg-emerald-500 transition-all duration-1000 ease-out"
                                title="DPA & RKA ({{ stats.dpa_rka }})"
                            ></div>
                            <div 
                                :style="`width: ${getPercent(stats.renja, stats.total)}%`"
                                class="h-full bg-amber-500 transition-all duration-1000 ease-out"
                                title="Renja ({{ stats.renja }})"
                            ></div>
                            <div 
                                :style="`width: ${getPercent(stats.laporan, stats.total)}%`"
                                class="h-full bg-purple-500 transition-all duration-1000 ease-out"
                                title="Laporan ({{ stats.laporan }})"
                            ></div>
                        </div>
                        <div class="flex flex-wrap gap-4 mt-2 text-xs font-medium">
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>
                                DPA & RKA ({{ getPercent(stats.dpa_rka, stats.total) }}%)
                            </span>
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>
                                Renja ({{ getPercent(stats.renja, stats.total) }}%)
                            </span>
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                                Laporan ({{ getPercent(stats.laporan, stats.total) }}%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Table -->
            <div v-if="documents && documents.length > 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all duration-500">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800">Preview Tabel Arsip</h3>
                    
                    <button @click="downloadExcel" class="mt-3 sm:mt-0 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-300 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Excel File
                    </button>
                </div>
                
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap">No</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap">Nomor Surat</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Judul / Perihal</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase tracking-wider">Kategori</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="(doc, index) in documents" :key="doc.id" class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="py-3 px-4 text-sm text-slate-500 font-medium">{{ index + 1 }}</td>
                                <td class="py-3 px-4 text-sm font-semibold text-slate-800">{{ doc.document_number }}</td>
                                <td class="py-3 px-4 text-sm text-slate-700">{{ doc.title }}</td>
                                <td class="py-3 px-4">
                                    <span v-if="doc.category === 'DPA'" class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">DPA</span>
                                    <span v-else-if="doc.category === 'RKA'" class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">RKA</span>
                                    <span v-else-if="doc.category === 'Renja'" class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">Renja</span>
                                    <span v-else-if="doc.category === 'Laporan Bulanan'" class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Lap. Bulanan</span>
                                    <span v-else-if="doc.category === 'Laporan Triwulanan'" class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">Lap. Triwulan</span>
                                    <span v-else class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">{{ doc.category }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-500 whitespace-nowrap">{{ doc.document_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="documents && documents.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-slate-800">Tidak ada data arsip</h3>
                <p class="text-slate-500 mt-2 text-sm max-w-md mx-auto">Belum ada dokumen dalam rentang tanggal yang dipilih. Coba ubah filter tanggal atau tambahkan dokumen baru.</p>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Enhanced styling for cards & table */
.bg-emerald-50:hover,
.bg-amber-50:hover,
.bg-purple-50:hover,
.bg-white:hover {
    transform: translateY(-2px);
}

/* Table header gradient */
thead tr {
    background: linear-gradient(to right, #f8fafc, #f1f5f9);
}

/* Category badges */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
}
</style>