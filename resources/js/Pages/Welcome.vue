<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Welcome3DScene from '@/Components/Welcome3DScene.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Selamat Datang - SIDePPA" />

    <div class="min-h-screen bg-white text-slate-800 font-sans selection:bg-[#4a553c] selection:text-white flex flex-col relative overflow-hidden">
        
        <div class="absolute inset-0 z-0 mix-blend-multiply opacity-90">
            <Welcome3DScene />
        </div>

        <div class="absolute inset-0 z-0 bg-white/40 backdrop-blur-[1px] pointer-events-none"></div>

        <nav class="w-full bg-[#9ca3af] border-b-2 border-[#808894] z-50 fixed top-0 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center relative">
                    
                    <div class="flex items-center fade-in-left">
                        <div class="flex items-center justify-center flex-shrink-0 border-r border-slate-500 pr-4 mr-4">
                            <img src="/images/logo-utama.png" alt="Logo Utama" class="h-14 sm:h-16 w-auto object-contain max-w-[180px]" />
                        </div>

                        <div class="w-10 h-10 bg-[#4a553c] rounded-xl flex items-center justify-center shadow-md flex-shrink-0 mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        
                        <span class="text-2xl font-black text-slate-900 tracking-tight">SI<span class="text-[#4a553c]">DePPA</span></span>
                    </div>

                    <div class="hidden lg:flex items-center justify-center gap-8 absolute left-1/2 transform -translate-x-1/2">
                        <div class="h-10 w-24 flex items-center justify-center">
                            <img src="/images/logo1.png" alt="Logo 1" class="max-h-full max-w-full object-contain" />
                        </div>
                        <div class="h-10 w-24 flex items-center justify-center">
                            <img src="/images/logo2.png" alt="Logo 2" class="max-h-full max-w-full object-contain" />
                        </div>
                        <div class="h-10 w-24 flex items-center justify-center">
                            <img src="/images/logo3.png" alt="Logo 3" class="max-h-full max-w-full object-contain" />
                        </div>
                    </div>

                    <div v-if="canLogin" class="hidden sm:flex items-center gap-4 fade-in-right z-10">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm font-bold text-slate-800 hover:text-slate-950 px-5 py-2.5 rounded-lg border border-slate-500 hover:bg-slate-300 transition-all shadow-sm">
                            Masuk Dashboard Utama
                        </Link>

                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-bold text-white bg-[#4a553c] hover:bg-[#3d4631] px-5 sm:px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                               login
                            </Link>
                        </template>
                    </div>

                </div>
            </div>
        </nav>

        <main class="flex-1 flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 relative z-10 mt-28 sm:mt-32 pt-10">
            
            <div class="relative z-10 text-center max-w-5xl mx-auto">
                
                <div class="hidden sm:inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#4a553c]/10 text-[#4a553c] text-xs sm:text-sm font-bold mb-6 sm:mb-8 border border-[#4a553c]/20 shadow-sm uppercase tracking-widest fade-in-up">
                    <span class="flex w-2 h-2 rounded-full bg-[#4a553c] animate-ping"></span>
                    SELAMAT DATANG DI SEDePPA
                </div>

                <div class="flex sm:hidden items-center justify-center gap-5 mb-8 fade-in-up">
                    <div class="h-12 w-auto flex items-center justify-center">
                        <img src="/images/logo1.png" alt="Logo 1" class="max-h-full max-w-full object-contain" />
                    </div>
                    <div class="h-12 w-auto flex items-center justify-center border-l border-r border-slate-300 px-4">
                        <img src="/images/logo2.png" alt="Logo 2" class="max-h-full max-w-full object-contain" />
                    </div>
                    <div class="h-12 w-auto flex items-center justify-center">
                        <img src="/images/logo3.png" alt="Logo 3" class="max-h-full max-w-full object-contain" />
                    </div>
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6 sm:mb-8 drop-shadow-sm fade-in-up delay-100">
                    Sistem Informasi Digital <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4a553c] to-[#606e4e]">Perencanaan & Pelaporan</span>
                </h1>
                
                <p class="text-base sm:text-lg md:text-xl text-slate-500 mb-10 sm:mb-12 max-w-3xl mx-auto leading-relaxed fade-in-up delay-200 font-normal">
                    Transformasi digital tata kelola kearsipan pada Satuan Polisi Pamong Praja dan Wilayatul Hisbah Aceh. Mengamankan DPA, RKA, dan Laporan Kinerja dalam infrastruktur tersentralisasi.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 justify-center items-center fade-in-up delay-300 w-full sm:w-auto">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="w-full sm:w-auto px-8 py-4 text-sm sm:text-base font-bold text-white bg-gradient-to-r from-[#4a553c] to-[#5a6848] hover:from-[#3d4631] hover:to-[#4a553c] rounded-xl shadow-md transition-all transform hover:-translate-y-1 hover:scale-105 flex justify-center items-center gap-3 uppercase tracking-wide">
                        Buka Ruang Kendali
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </Link>
                    <Link v-else :href="route('login')" class="w-full sm:w-auto px-8 py-4 text-sm sm:text-base font-bold text-white bg-[#4a553c] hover:bg-[#3d4631] rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1 hover:scale-105 flex justify-center items-center gap-3 uppercase tracking-wide">
                        masuk ke dalam sistem 
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 max-w-7xl mx-auto mt-20 sm:mt-28 mb-16 relative z-10 w-full px-2 sm:px-4">
                <div class="bg-white/80 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-slate-200/60 shadow-md hover:shadow-lg hover:bg-white transition-all duration-500 hover:-translate-y-2 group fade-in-up delay-400">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#4a553c] text-white rounded-2xl flex items-center justify-center mb-5 sm:mb-6 shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4M8 11h12M8 15h12M4 6h2v12H4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">Pencarian Real-time</h3>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">Ekstraksi dokumen DPA dan Renja dari seluruh bidang dalam hitungan detik dengan arsitektur indeks pintar.</p>
                </div>

                <div class="bg-white/80 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-slate-200/60 shadow-md hover:shadow-lg hover:bg-white transition-all duration-500 hover:-translate-y-2 group fade-in-up delay-500">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#566345] text-white rounded-2xl flex items-center justify-center mb-5 sm:mb-6 shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">Audit Log & Keamanan</h3>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">Perekaman CCTV digital untuk setiap aktivitas akses. Dilengkapi pengaturan otorisasi ketat guna menjaga kerahasiaan instansi.</p>
                </div>

                <div class="bg-white/80 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-slate-200/60 shadow-md hover:shadow-lg hover:bg-white transition-all duration-500 hover:-translate-y-2 group fade-in-up delay-600">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#3d4631] text-white rounded-2xl flex items-center justify-center mb-5 sm:mb-6 shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">Laporan Otomatis</h3>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">Generasi laporan kinerja dan kompilasi data anggaran yang tersentralisasi. Ekspor ke Excel dengan sekali klik.</p>
                </div>
            </div>
        </main>

        <footer class="w-full py-5 sm:py-6 text-center text-slate-800 text-xs sm:text-sm border-t border-[#808894] bg-[#9ca3af] font-bold relative z-10">
            &copy; {{ new Date().getFullYear() }} Satuan Polisi Pamong Praja dan Wilayatul Hisbah Aceh. Ditenagai oleh SIDePPA.
        </footer>
    </div>
</template>

<style scoped>
/* Animasi Tetap Utuh */
.fade-in-up { animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(30px); }
.fade-in-left { animation: fadeInLeft 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateX(-30px); }
.fade-in-right { animation: fadeInRight 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateX(30px); }
.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-400 { animation-delay: 0.4s; }
.delay-500 { animation-delay: 0.5s; }
.delay-600 { animation-delay: 0.6s; }
@keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInLeft { to { opacity: 1; transform: translateX(0); } }
@keyframes fadeInRight { to { opacity: 1; transform: translateX(0); } }
</style>