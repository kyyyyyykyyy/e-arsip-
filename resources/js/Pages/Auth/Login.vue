<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Hero3DScene from '@/Components/Hero3DScene.vue'; // IMPORT KOMPONEN 3D

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Enterprise Login" />

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden font-sans">
        
        <Hero3DScene />

        <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-16">
            
            <div class="w-full md:w-1/2 text-left space-y-6 transform transition-all duration-1000 translate-y-0 opacity-100">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900/50 backdrop-blur-md border border-slate-700 text-amber-400 text-xs font-bold tracking-widest uppercase">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Secure Connection
                </div>
                
                <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    E-Arsip <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">Digital</span><br/>
                    <span class="text-3xl md:text-4xl text-slate-300 font-light">Enterprise Data Core</span>
                </h1>
                
                <p class="text-lg text-slate-400 max-w-lg leading-relaxed">
                    Sistem manajemen arsip generasi terbaru. Dilengkapi dengan enkripsi keamanan tingkat tinggi, pelacakan aktivitas pintar, dan komputasi awan.
                </p>
            </div>

            <div class="w-full md:w-5/12">
                <div class="bg-slate-900/40 backdrop-blur-xl p-10 rounded-3xl border border-slate-700/50 shadow-2xl shadow-black/50">
                    
                    <div class="mb-8 text-center">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20 mb-4 transform -rotate-6">
                            <svg class="w-8 h-8 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Otorisasi Pegawai</h2>
                        <p class="text-slate-400 text-sm mt-1">Masukkan kredensial untuk mengakses sistem.</p>
                    </div>

                    <div v-if="status" class="mb-4 font-medium text-sm text-emerald-400 text-center bg-emerald-900/30 p-3 rounded-lg border border-emerald-800">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="email" value="Email Instansi" class="text-slate-300" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full bg-slate-950/50 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500 rounded-xl"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="pegawai@instansi.go.id"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Kata Sandi Keamanan" class="text-slate-300" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full bg-slate-950/50 border-slate-700 text-white focus:border-amber-500 focus:ring-amber-500 rounded-xl"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <InputError class="mt-2 text-red-400" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" class="border-slate-700 bg-slate-950/50 text-amber-500 focus:ring-amber-500" />
                                <span class="ms-2 text-sm text-slate-400">Ingat Sesi Saya</span>
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-amber-500 hover:text-amber-400 transition-colors focus:outline-none"
                            >
                                Lupa sandi?
                            </Link>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-900 font-extrabold rounded-xl shadow-lg shadow-amber-500/25 transition-all transform hover:-translate-y-0.5 flex justify-center items-center" 
                            :class="{ 'opacity-50 cursor-wait': form.processing }" 
                            :disabled="form.processing"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-slate-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            OTENTIKASI MASUK
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>