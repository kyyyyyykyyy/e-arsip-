<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingNavigationDropdown = ref(false);
const isArsipOpen = ref(false);
const user = usePage().props.auth.user;
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        
        <aside class="hidden md:flex flex-col w-64 bg-slate-900 text-slate-300 shadow-2xl transition-all duration-300 z-20">
            <div class="h-16 flex items-center justify-center bg-slate-950 border-b border-slate-800">
                <span class="text-xl font-extrabold text-amber-500 tracking-wider uppercase">E-<span class="text-white">Arsip</span></span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                
                <Link :href="route('dashboard')" :class="route().current('dashboard') ? 'bg-slate-800 text-amber-500 border-l-4 border-amber-500' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="flex items-center px-4 py-3 rounded-r-md transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-medium">Dashboard Utama</span>
                </Link>

                <div>
                    <button @click="isArsipOpen = !isArsipOpen" class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-800 hover:text-white rounded-md transition-all duration-200 border-l-4 border-transparent">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
                            <span class="font-medium">Data Arsip</span>
                        </div>
                        <svg :class="isArsipOpen ? 'transform rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div v-show="isArsipOpen" class="mt-2 space-y-1 px-4 border-l-2 border-slate-700 ml-6">
                        <Link :href="route('arsip.index')" class="block px-4 py-2 text-sm text-slate-400 hover:text-amber-500 hover:bg-slate-800 rounded-md">Semua Arsip</Link>
                        <Link :href="route('arsip.index', { category: 'Surat Masuk' })" class="block px-4 py-2 text-sm text-slate-400 hover:text-amber-500 hover:bg-slate-800 rounded-md">Surat Masuk</Link>
                        <Link :href="route('arsip.index', { category: 'Surat Keluar' })" class="block px-4 py-2 text-sm text-slate-400 hover:text-amber-500 hover:bg-slate-800 rounded-md">Surat Keluar</Link>
                        <Link :href="route('arsip.index', { category: 'Internal' })" class="block px-4 py-2 text-sm text-slate-400 hover:text-amber-500 hover:bg-slate-800 rounded-md">Internal</Link>
                    </div>
                </div>

                <Link :href="route('laporan.index')" :class="route().current('laporan.index') ? 'bg-slate-800 text-amber-500 border-l-4 border-amber-500' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="flex items-center px-4 py-3 rounded-r-md transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="font-medium">Laporan</span>
                </Link>

                <hr class="border-slate-800 my-4">

                <div v-if="user.role === 'superadmin'">
                    <div class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administrator</div>
                    
                    <Link :href="route('users.index')" :class="route().current('users.index') ? 'bg-slate-800 text-amber-500 border-l-4 border-amber-500' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="flex items-center px-4 py-3 rounded-r-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-medium">Kelola User</span>
                    </Link>

                    <Link :href="route('logs.index')" :class="route().current('logs.index') ? 'bg-slate-800 text-amber-500 border-l-4 border-amber-500' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="flex items-center px-4 py-3 rounded-r-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">Log Aktivitas</span>
                    </Link>

                    <Link :href="route('settings.index')" :class="route().current('settings.index') ? 'bg-slate-800 text-amber-500 border-l-4 border-amber-500' : 'hover:bg-slate-800 hover:text-white border-l-4 border-transparent'" class="flex items-center px-4 py-3 rounded-r-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-medium">Pengaturan</span>
                    </Link>
                </div>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300">
            <header class="h-16 bg-white shadow-sm border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 relative">
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="md:hidden p-2 rounded-md text-slate-500 hover:text-amber-600 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="hidden md:flex flex-col">
                    <span class="text-sm font-bold text-slate-800">Manajemen Arsip Digital</span>
                    <span class="text-xs text-slate-500">Sistem Penyimpanan Dokumen Personal</span>
                </div>

                <div class="flex items-center">
                    <div class="relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-700 bg-white hover:text-amber-600 focus:outline-none transition ease-in-out duration-150">
                                        <div class="flex flex-col text-right mr-2">
                                            <span class="font-bold">{{ user.name }}</span>
                                            <span class="text-xs text-amber-600 uppercase tracking-wider">{{ user.role }}</span>
                                        </div>
                                        <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profil Saya</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="md:hidden bg-slate-900 border-b border-slate-800 absolute w-full z-50 top-16 shadow-xl">
                <div class="pt-2 pb-3 space-y-1">
                    <Link :href="route('dashboard')" :class="route().current('dashboard') ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-amber-500 bg-slate-800 border-amber-500' : 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-slate-300 border-transparent hover:bg-slate-800 hover:text-white'">Dashboard</Link>
                    <Link :href="route('arsip.index')" :class="route().current('arsip.index') ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-amber-500 bg-slate-800 border-amber-500' : 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-slate-300 border-transparent hover:bg-slate-800 hover:text-white'">Data Arsip</Link>
                    <Link :href="route('laporan.index')" :class="route().current('laporan.index') ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-amber-500 bg-slate-800 border-amber-500' : 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-slate-300 border-transparent hover:bg-slate-800 hover:text-white'">Laporan</Link>
                    
                    <div v-if="user.role === 'superadmin'">
                        <Link :href="route('users.index')" :class="route().current('users.index') ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-amber-500 bg-slate-800 border-amber-500' : 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-slate-300 border-transparent hover:bg-slate-800 hover:text-white'">Kelola User</Link>
                        
                        <Link :href="route('logs.index')" :class="route().current('logs.index') ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-amber-500 bg-slate-800 border-amber-500' : 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-slate-300 border-transparent hover:bg-slate-800 hover:text-white'">Log Aktivitas</Link>
                        
                        <Link :href="route('settings.index')" :class="route().current('settings.index') ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-amber-500 bg-slate-800 border-amber-500' : 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-slate-300 border-transparent hover:bg-slate-800 hover:text-white'">Pengaturan</Link>
                    </div>
                </div>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #f59e0b; }
</style>