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
    if (total === 0) return 0;
    return Math.round((value / total) * 100);
};
</script>

<template>
    <Head title="Laporan Arsip" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-8 py-6">
                    <h2 class="text-2xl font-extrabold text-white flex items-center">
                        <svg class="w-7 h-7 mr-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan & Ekspor Data
                    </h2>
                    <p class="text-slate-300 mt-2 text-sm">Pilih rentang tanggal untuk melihat ringkasan statistik dan mengunduh laporan.</p>
                </div>

                <div class="p-8 border-b border-slate-100">
                    <div class="flex flex-col md:flex-row gap-6 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Dari Tanggal Surat</label>
                            <input v-model="start_date" type="date" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-slate-700">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sampai Tanggal Surat</label>
                            <input v-model="end_date" type="date" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-slate-700">
                        </div>
                        
                        <div class="w-full md:w-auto">
                            <button @click="tampilkanData" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="documents && documents.length > 0" class="p-8 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Ringkasan Periode Ini</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <p class="text-xs text-slate-500 font-semibold mb-1">Total Arsip</p>
                            <p class="text-3xl font-extrabold text-slate-800">{{ stats.total }}</p>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 shadow-sm">
                            <p class="text-xs text-emerald-600 font-semibold mb-1">Surat Masuk</p>
                            <p class="text-3xl font-extrabold text-emerald-700">{{ stats.masuk }}</p>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 shadow-sm">
                            <p class="text-xs text-amber-600 font-semibold mb-1">Surat Keluar</p>
                            <p class="text-3xl font-extrabold text-amber-700">{{ stats.keluar }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 shadow-sm">
                            <p class="text-xs text-purple-600 font-semibold mb-1">Internal</p>
                            <p class="text-3xl font-extrabold text-purple-700">{{ stats.internal }}</p>
                        </div>
                    </div>

                    <div class="w-full bg-slate-200 rounded-full h-4 flex overflow-hidden">
                        <div :style="`width: ${getPercent(stats.masuk, stats.total)}%`" class="bg-emerald-500 h-4 transition-all duration-1000" title="Surat Masuk"></div>
                        <div :style="`width: ${getPercent(stats.keluar, stats.total)}%`" class="bg-amber-500 h-4 transition-all duration-1000" title="Surat Keluar"></div>
                        <div :style="`width: ${getPercent(stats.internal, stats.total)}%`" class="bg-purple-500 h-4 transition-all duration-1000" title="Internal"></div>
                    </div>
                    <div class="flex gap-4 mt-2 text-xs font-medium">
                        <span class="text-emerald-600">■ Masuk ({{ getPercent(stats.masuk, stats.total) }}%)</span>
                        <span class="text-amber-600">■ Keluar ({{ getPercent(stats.keluar, stats.total) }}%)</span>
                        <span class="text-purple-600">■ Internal ({{ getPercent(stats.internal, stats.total) }}%)</span>
                    </div>
                </div>
            </div>

            <div v-if="documents && documents.length > 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all duration-500">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800">Preview Tabel Arsip</h3>
                    
                    <button @click="downloadExcel" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-md transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Excel File
                    </button>
                </div>
                
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200">
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">No</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">Nomor Surat</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">Judul / Perihal</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">Kategori</th>
                                <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="(doc, index) in documents" :key="doc.id" class="hover:bg-slate-50">
                                <td class="py-3 px-4 text-sm text-slate-500">{{ index + 1 }}</td>
                                <td class="py-3 px-4 text-sm font-semibold text-slate-800">{{ doc.document_number }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ doc.title }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ doc.category }}</td>
                                <td class="py-3 px-4 text-sm text-slate-600">{{ doc.document_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="documents && documents.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="text-lg font-medium text-slate-800">Tidak ada data arsip</h3>
                <p class="text-slate-500 mt-1 text-sm">Coba cari rentang tanggal yang lain.</p>
            </div>

        </div>
    </AuthenticatedLayout>
</template>     