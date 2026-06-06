<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    setting: Object
});

const form = useForm({
    app_name: props.setting.app_name || '',
    instansi_name: props.setting.instansi_name || '',
    head_office: props.setting.head_office || '',
    address: props.setting.address || '',
});

const submitPengaturan = () => {
    form.post(route('settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Pengaturan Sistem" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 relative">
            
            <div v-if="$page.props.flash && $page.props.flash.message" class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ $page.props.flash.message }}</span>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-8 py-6">
                    <h2 class="text-2xl font-extrabold text-white flex items-center">
                        <svg class="w-7 h-7 mr-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Pengaturan Sistem
                    </h2>
                    <p class="text-slate-300 mt-2 text-sm">Sesuaikan identitas aplikasi dan data instansi untuk keperluan laporan.</p>
                </div>

                <div class="p-8">
                    <form @submit.prevent="submitPengaturan" class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Aplikasi</label>
                                <input v-model="form.app_name" type="text" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <div v-if="form.errors.app_name" class="text-red-500 text-xs mt-1">{{ form.errors.app_name }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Instansi</label>
                                <input v-model="form.instansi_name" type="text" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <div v-if="form.errors.instansi_name" class="text-red-500 text-xs mt-1">{{ form.errors.instansi_name }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kepala Dinas / Pimpinan</label>
                            <input v-model="form.head_office" type="text" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500" placeholder="Beserta Gelar (Opsional)">
                            <div v-if="form.errors.head_office" class="text-red-500 text-xs mt-1">{{ form.errors.head_office }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Instansi</label>
                            <textarea v-model="form.address" rows="3" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500"></textarea>
                            <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" :disabled="form.processing" class="px-6 py-3 text-sm font-bold text-white bg-amber-500 rounded-xl shadow-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-500/30 transition-all transform hover:-translate-y-1 flex items-center">
                                <span v-if="form.processing">Menyimpan...</span>
                                <span v-else>Simpan Pengaturan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>