<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    logs: Array
});

// Fungsi untuk merapikan format tanggal & jam
const formatTanggal = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    }).format(date);
};

// Fungsi untuk memberi warna badge berdasarkan aksi
const getActionColor = (action) => {
    if (action.includes('Hapus')) return 'bg-red-100 text-red-700 border-red-200';
    if (action.includes('Edit') || action.includes('Update')) return 'bg-amber-100 text-amber-700 border-amber-200';
    if (action.includes('Upload') || action.includes('Tambah')) return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    return 'bg-blue-100 text-blue-700 border-blue-200';
};
</script>

<template>
    <Head title="Log Aktivitas" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-6 relative">
            
            <div class="bg-slate-900 p-8 rounded-2xl shadow-xl mb-8 flex flex-col md:flex-row md:items-center justify-between relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-extrabold text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Sistem Pemantauan (CCTV)
                    </h2>
                    <p class="text-slate-400 mt-2 text-sm max-w-xl">Halaman khusus Super Admin. Semua aktivitas pengguna dalam menambah, mengubah, atau menghapus data terekam secara otomatis di sini.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Kejadian</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Pelaku (User)</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tindakan</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="log in logs" :key="log.id" class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-slate-600 font-medium whitespace-nowrap">
                                    {{ formatTanggal(log.created_at) }} WIB
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs mr-3">
                                            {{ log.user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800">{{ log.user.name }}</span>
                                            <span class="text-xs text-slate-500">{{ log.user.role }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span :class="getActionColor(log.action)" class="px-3 py-1 border text-xs font-bold rounded-full uppercase tracking-wide">
                                        {{ log.action }}
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-sm text-slate-600">
                                    {{ log.description }}
                                </td>
                            </tr>

                            <tr v-if="logs.length === 0">
                                <td colspan="4" class="py-16 text-center">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-slate-500 font-medium text-lg">Belum ada aktivitas terekam.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>