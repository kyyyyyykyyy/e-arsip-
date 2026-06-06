<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Selamat Datang - E-Arsip Digital" />

    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans selection:bg-amber-500 selection:text-white flex flex-col">
        
        <nav class="w-full bg-white shadow-sm border-b border-slate-100 z-50 fixed top-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg shadow-slate-900/20">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">E-<span class="text-amber-500">Arsip</span></span>
                    </div>
                    <div v-if="canLogin" class="flex items-center gap-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-semibold text-slate-600 hover:text-slate-900 px-5 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all">
                            Kembali ke Dashboard
                        </Link>

                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                Masuk (Login)
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden mt-20">
            
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full pointer-events-none opacity-40">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
                <div class="absolute top-48 -left-24 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
            </div>

            <div class="relative z-10 text-center max-w-4xl mx-auto mt-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-bold mb-6 border border-amber-200 shadow-sm uppercase tracking-wider">
                    <span class="flex w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    Sistem Enterprise v1.0
                </div>
                
                <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight mb-8">
                    Manajemen Arsip Digital <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-amber-500">Aman, Cepat, & Terintegrasi</span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Tinggalkan cara lama menyimpan dokumen di gudang berdebu. Beralih ke sistem pengarsipan digital untuk pencarian instan, keamanan berlapis, dan pelaporan otomatis.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="px-8 py-4 text-base font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 flex items-center gap-2">
                        Masuk ke Dashboard Sistem
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </Link>
                    <Link v-else :href="route('login')" class="px-8 py-4 text-base font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 flex items-center gap-2">
                        Masuk Menggunakan Akun Pegawai
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mt-24 mb-16 relative z-10">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Pencarian Instan</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Temukan dokumen penting dalam hitungan detik dengan fitur pencarian pintar berdasarkan nomor, perihal, atau kategori surat.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Keamanan Berlapis</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Dilengkapi dengan CCTV (Log Aktivitas) dan manajemen hak akses (Role) untuk menjaga kerahasiaan data penting instansi.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Pelaporan Otomatis</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Cetak laporan rekapitulasi arsip harian atau bulanan langsung ke format Microsoft Excel dalam satu kali klik saja.</p>
                </div>
            </div>
        </main>

        <footer class="w-full py-6 text-center text-slate-500 text-sm border-t border-slate-200 bg-white">
            &copy; {{ new Date().getFullYear() }} E-Arsip Digital Terpadu. All rights reserved.
        </footer>
    </div>
</template>