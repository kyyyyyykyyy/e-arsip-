<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3'; // <-- Tambah Link di sini
import { ref, watch } from 'vue';

const props = defineProps({
    documents: Object, // <-- Berubah jadi Object karena pakai Pagination
    filters: Object,
});

const search = ref(props.filters?.search || '');
const category = ref(props.filters?.category || 'Semua Arsip');

let timeout = null;
watch([search, category], ([newSearch, newCategory]) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('arsip.index'), {
            search: newSearch,
            category: newCategory === 'Semua Arsip' ? null : newCategory
        }, { preserveState: true, replace: true });
    }, 300); 
});

const formDelete = useForm({});
const hapusArsip = (id) => {
    if (confirm('⚠️ PERINGATAN: Apakah Anda yakin ingin memusnahkan arsip ini? Data dan file akan hilang permanen!')) {
        formDelete.delete(route('documents.destroy', id), { preserveScroll: true });
    }
};

const getCategoryColor = (cat) => {
    if (cat === 'Surat Masuk') return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    if (cat === 'Surat Keluar') return 'bg-amber-100 text-amber-700 border-amber-200';
    if (cat === 'Internal') return 'bg-purple-100 text-purple-700 border-purple-200';
    return 'bg-slate-100 text-slate-700 border-slate-200';
};

// ==========================================
// LOGIKA MODAL EDIT ARSIP
// ==========================================
const isEditModalOpen = ref(false);
const editForm = useForm({
    id: '',
    document_number: '',
    title: '',
    category: 'Surat Masuk',
    document_date: '',
    origin_office: '',
    destination_office: '',
    destination_field: '',
    file: null, // Kosong saat awal dibuka
});

const openEditModal = (doc) => {
    editForm.id = doc.id;
    editForm.document_number = doc.document_number;
    editForm.title = doc.title;
    editForm.category = doc.category;
    editForm.document_date = doc.document_date;
    editForm.origin_office = doc.origin_office || '';
    editForm.destination_office = doc.destination_office || '';
    editForm.destination_field = doc.destination_field || '';
    editForm.file = null; // Reset file input
    editForm.clearErrors();
    isEditModalOpen.value = true;
};

const closeEditModal = () => { 
    isEditModalOpen.value = false; 
    editForm.reset(); 
};

const handleEditFileChange = (event) => {
    editForm.file = event.target.files[0];
};

const submitEdit = () => {
    editForm.post(route('documents.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => closeEditModal(),
    });
};
// ==========================================
</script>

<template>
    <Head title="Data Arsip" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-6 relative">
            
            <div v-if="$page.props.flash && $page.props.flash.message" class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ $page.props.flash.message }}</span>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-800">Manajemen Data Arsip</h2>
                        <p class="text-sm text-slate-500 mt-1">Kelola, cari, dan filter seluruh dokumen arsip digital instansi.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input v-model="search" type="text" placeholder="Cari Nomor / Judul Surat..." class="pl-10 pr-4 py-2 w-full sm:w-64 border border-slate-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <select v-model="category" class="py-2 pl-3 pr-10 w-full sm:w-40 border border-slate-300 rounded-lg text-sm text-slate-700 focus:ring-amber-500 focus:border-amber-500 cursor-pointer">
                            <option value="Semua Arsip">Semua Kategori</option>
                            <option value="Surat Masuk">Surat Masuk</option>
                            <option value="Surat Keluar">Surat Keluar</option>
                            <option value="Internal">Internal</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas Surat</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Tujuan / Asal</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            
                            <tr v-for="(doc, index) in documents.data" :key="doc.id" class="hover:bg-slate-50/80 transition-colors group">
                                <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                                    {{ (documents.current_page - 1) * documents.per_page + index + 1 }}
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800">{{ doc.document_number }}</span>
                                        <span class="text-xs text-slate-500 mt-1 truncate max-w-xs">{{ doc.title }}</span>
                                        <span class="text-xs text-slate-400 mt-1 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ doc.document_date }}
                                        </span>
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    <div class="text-sm text-slate-700 font-medium">
                                        <span v-if="doc.category === 'Surat Masuk'" class="text-emerald-600">Dari: {{ doc.origin_office || '-' }}</span>
                                        <span v-else-if="doc.category === 'Surat Keluar'" class="text-amber-600">Ke: {{ doc.destination_office || '-' }}</span>
                                        <span v-else class="text-purple-600">Bidang: {{ doc.destination_field || '-' }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    <span :class="getCategoryColor(doc.category)" class="px-3 py-1 inline-flex text-xs font-bold rounded-full border">
                                        {{ doc.category }}
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                    <a :href="route('documents.download', doc.id)" class="inline-flex items-center p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-colors border border-emerald-200 hover:border-transparent" title="Download File">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    <button @click="openEditModal(doc)" class="inline-flex items-center p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-200 hover:border-transparent" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>

                                    <button @click="hapusArsip(doc.id)" class="inline-flex items-center p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-200 hover:border-transparent" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="documents.data.length === 0">
                                <td colspan="5" class="py-16 text-center">
                                    <p class="text-slate-500 font-medium text-lg">Belum ada arsip ditemukan.</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div v-if="documents.links && documents.links.length > 3" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-500">
                                Menampilkan <span class="font-bold text-slate-800">{{ documents.from || 0 }}</span> sampai <span class="font-bold text-slate-800">{{ documents.to || 0 }}</span> dari <span class="font-bold text-slate-800">{{ documents.total }}</span> arsip.
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <template v-for="(link, key) in documents.links" :key="key">
                                    <div v-if="link.url === null" class="relative inline-flex items-center px-4 py-2 border border-slate-200 bg-white text-sm font-medium text-slate-400" v-html="link.label"></div>
                                    <Link v-else :href="link.url" class="relative inline-flex items-center px-4 py-2 border border-slate-200 bg-white text-sm font-medium hover:bg-slate-50 transition-colors" :class="{'z-10 bg-amber-50 border-amber-500 text-amber-600': link.active, 'text-slate-600': !link.active}" v-html="link.label"></Link>
                                </template>
                            </nav>
                        </div>
                    </div>
                </div>
                </div>

            <transition name="modal-fade">
                <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeEditModal"></div>
                    
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Dokumen Arsip
                            </h3>
                            <button @click="closeEditModal" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                            <form @submit.prevent="submitEdit" class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Surat</label>
                                        <input v-model="editForm.document_number" type="text" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" required>
                                        <div v-if="editForm.errors.document_number" class="text-red-500 text-xs mt-1">{{ editForm.errors.document_number }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Surat</label>
                                        <input v-model="editForm.document_date" type="date" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" required>
                                        <div v-if="editForm.errors.document_date" class="text-red-500 text-xs mt-1">{{ editForm.errors.document_date }}</div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Perihal / Judul</label>
                                    <input v-model="editForm.title" type="text" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm" required>
                                    <div v-if="editForm.errors.title" class="text-red-500 text-xs mt-1">{{ editForm.errors.title }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Arsip</label>
                                    <select v-model="editForm.category" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm bg-slate-50">
                                        <option value="Surat Masuk">Surat Masuk</option>
                                        <option value="Surat Keluar">Surat Keluar</option>
                                        <option value="Internal">Internal</option>
                                    </select>
                                </div>

                                <transition name="fade" mode="out-in">
                                    <div v-if="editForm.category === 'Surat Masuk'" key="masuk">
                                        <label class="block text-sm font-semibold text-emerald-700 mb-1">Asal Surat Instansi</label>
                                        <input v-model="editForm.origin_office" type="text" class="w-full border-emerald-200 bg-emerald-50 rounded-lg text-sm">
                                    </div>
                                    <div v-else-if="editForm.category === 'Surat Keluar'" key="keluar">
                                        <label class="block text-sm font-semibold text-amber-700 mb-1">Tujuan Instansi</label>
                                        <input v-model="editForm.destination_office" type="text" class="w-full border-amber-200 bg-amber-50 rounded-lg text-sm">
                                    </div>
                                    <div v-else-if="editForm.category === 'Internal'" key="internal">
                                        <label class="block text-sm font-semibold text-purple-700 mb-1">Tujuan Bidang</label>
                                        <input v-model="editForm.destination_field" type="text" class="w-full border-purple-200 bg-purple-50 rounded-lg text-sm">
                                    </div>
                                </transition>

                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                    <label class="block text-sm font-semibold text-blue-800 mb-1">Ganti File Dokumen? (Opsional)</label>
                                    <p class="text-xs text-blue-600 mb-2">Biarkan kosong jika tidak ingin mengganti file yang sudah ada.</p>
                                    <input @change="handleEditFileChange" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,image/*,.zip,.rar" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-colors border border-slate-200 rounded-lg bg-white">
                                    <div v-if="editForm.errors.file" class="text-red-500 text-xs mt-1">{{ editForm.errors.file }}</div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3 mt-6">
                                    <button type="button" @click="closeEditModal" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</button>
                                    <button type="submit" :disabled="editForm.processing" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all flex items-center">
                                        <span v-if="editForm.processing">Menyimpan...</span>
                                        <span v-else>Update Data</span>
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