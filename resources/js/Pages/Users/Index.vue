<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Array,
    filters: Object,
});

// Fitur Pencarian Real-time
const search = ref(props.filters?.search || '');
let timeout = null;
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('users.index'), { search: value }, { preserveState: true, replace: true });
    }, 300);
});

// ==========================================
// MODAL TAMBAH USER
// ==========================================
const isAddModalOpen = ref(false);
const addForm = useForm({
    name: '',
    email: '',
    password: '',
    role: 'user',
});

const openAddModal = () => { isAddModalOpen.value = true; };
const closeAddModal = () => {
    isAddModalOpen.value = false;
    addForm.reset();
    addForm.clearErrors();
};

const submitAdd = () => {
    addForm.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => closeAddModal(),
    });
};

// ==========================================
// MODAL EDIT USER
// ==========================================
const isEditModalOpen = ref(false);
const editForm = useForm({
    id: '',
    name: '',
    email: '',
    password: '', // Kosong kalau nggak mau ganti password
    role: 'user',
});

const openEditModal = (user) => {
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    editForm.password = ''; 
    editForm.clearErrors();
    isEditModalOpen.value = true;
};

const closeEditModal = () => {
    isEditModalOpen.value = false;
    editForm.reset();
};

const submitEdit = () => {
    editForm.put(route('users.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => closeEditModal(),
    });
};

// ==========================================
// FUNGSI HAPUS USER
// ==========================================
const deleteForm = useForm({});
const hapusUser = (id) => {
    if (confirm('⚠️ PERINGATAN: Yakin ingin menghapus akun pegawai ini? Mereka tidak akan bisa login lagi!')) {
        deleteForm.delete(route('users.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Kelola User" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto py-6 relative">
            
            <div v-if="$page.props.flash && $page.props.flash.message" :class="$page.props.flash.message.includes('Error') ? 'bg-red-50 border-red-200 text-red-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700'" class="mb-6 p-4 rounded-lg border flex items-center shadow-sm">
                <svg v-if="$page.props.flash.message.includes('Error')" class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <svg v-else class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ $page.props.flash.message }}</span>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800">Manajemen Akun Pegawai</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola data login dan hak akses pegawai ke dalam sistem E-Arsip.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input v-model="search" type="text" placeholder="Cari nama atau email..." class="pl-10 pr-4 py-2 w-full sm:w-64 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button @click="openAddModal" class="px-5 py-2 bg-slate-900 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-md transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Pegawai
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pegawai</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Hak Akses (Role)</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="(u, index) in users" :key="u.id" class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-slate-500 font-medium">{{ index + 1 }}</td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800">{{ u.name }}</span>
                                        <span class="text-xs text-slate-500 mt-1">{{ u.email }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    <span v-if="u.role === 'superadmin'" class="px-3 py-1 bg-amber-100 text-amber-700 border border-amber-200 text-xs font-bold rounded-full uppercase tracking-wide">
                                        Super Admin
                                    </span>
                                    <span v-else class="px-3 py-1 bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold rounded-full uppercase tracking-wide">
                                        Pegawai (User)
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                    <button @click="openEditModal(u)" class="inline-flex items-center p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-200 hover:border-transparent" title="Edit Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    
                                    <button v-if="u.id !== $page.props.auth.user.id" @click="hapusUser(u.id)" class="inline-flex items-center p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-200 hover:border-transparent" title="Hapus Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td colspan="4" class="py-10 text-center text-slate-500">Belum ada akun lain yang terdaftar.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <transition name="modal-fade">
                <div v-if="isAddModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeAddModal"></div>
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all relative z-10">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center rounded-t-2xl">
                            <h3 class="text-lg font-bold text-slate-800">Tambah Akun Pegawai</h3>
                            <button @click="closeAddModal" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="p-6">
                            <form @submit.prevent="submitAdd" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input v-model="addForm.name" type="text" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                    <div v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                                    <input v-model="addForm.email" type="email" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                    <div v-if="addForm.errors.email" class="text-red-500 text-xs mt-1">{{ addForm.errors.email }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                                    <input v-model="addForm.password" type="password" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required minlength="8">
                                    <div v-if="addForm.errors.password" class="text-red-500 text-xs mt-1">{{ addForm.errors.password }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hak Akses (Role)</label>
                                    <select v-model="addForm.role" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="user">Pegawai Biasa (User)</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                                <div class="pt-4 flex justify-end gap-3">
                                    <button type="button" @click="closeAddModal" class="px-4 py-2 text-sm font-medium text-slate-600 border rounded-lg hover:bg-slate-50">Batal</button>
                                    <button type="submit" :disabled="addForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan Akun</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </transition>

            <transition name="modal-fade">
                <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeEditModal"></div>
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all relative z-10">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center rounded-t-2xl">
                            <h3 class="text-lg font-bold text-slate-800">Edit Akun Pegawai</h3>
                            <button @click="closeEditModal" class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="p-6">
                            <form @submit.prevent="submitEdit" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input v-model="editForm.name" type="text" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500" required>
                                    <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                                    <input v-model="editForm.email" type="email" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500" required>
                                    <div v-if="editForm.errors.email" class="text-red-500 text-xs mt-1">{{ editForm.errors.email }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hak Akses (Role)</label>
                                    <select v-model="editForm.role" class="w-full border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                        <option value="user">Pegawai Biasa (User)</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mt-2">
                                    <label class="block text-sm font-semibold text-blue-800 mb-1">Ganti Password (Opsional)</label>
                                    <input v-model="editForm.password" type="password" placeholder="Kosongkan jika tidak ingin ganti" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <div v-if="editForm.errors.password" class="text-red-500 text-xs mt-1">{{ editForm.errors.password }}</div>
                                </div>
                                <div class="pt-4 flex justify-end gap-3">
                                    <button type="button" @click="closeEditModal" class="px-4 py-2 text-sm font-medium text-slate-600 border rounded-lg hover:bg-slate-50">Batal</button>
                                    <button type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600">Update Akun</button>
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
</style>