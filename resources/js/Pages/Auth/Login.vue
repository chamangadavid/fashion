<!-- resources/js/Pages/Auth/Login.vue -->

<template>
    <GuestLayout>

        <Head title="Log in" />


        <div
            class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#FFF7EB] via-[#FFF1D1] to-[#FCE59A]/30 p-4 md:p-8">
            <div
                class="w-full max-w-6xl bg-[#FFF7EB]/95 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row border border-[#D4AF37]/30">

                <!-- LEFT: IMAGE -->
                <div class="hidden md:block md:w-1/2 relative overflow-hidden">

                    <!-- Luxury Gold Overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-black/60 via-[#D4AF37]/20 to-black/70 mix-blend-multiply z-10">
                    </div>

                    <img src="/assets/login-details.jpg" alt="Fashion Style"
                        class="h-full w-full object-cover transform hover:scale-105 transition-transform duration-700" />

                    <!-- Gold Glow -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 z-20"></div>

                    <!-- Decorative Bottom Card -->
                    <div class="absolute bottom-8 left-8 right-8 z-30">
                        <div class="bg-black/60 backdrop-blur-lg rounded-xl p-5 border border-[#FCE59A]/40 shadow-xl">
                            <p class="text-[#FCE59A] text-sm font-bold tracking-[0.25em]">
                                IN STYLE ASARA
                            </p>

                            <p class="text-[#FFF1D1] text-xs mt-2 tracking-wide">
                                Discover Your Signature Style
                            </p>

                            <div class="mt-3 h-[2px] w-16 bg-gradient-to-r from-[#D4AF37] to-[#FCE59A]"></div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: LOGIN FORM -->
                <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16 bg-[#FFF7EB]">

                    <!-- Logo and Title -->
                    <div class="text-center mb-8">

                        <!-- Logo -->
                        <div class="flex justify-center mb-5">
                            <div class="relative group">

                                <div
                                    class="absolute -inset-4 bg-gradient-to-r from-[#D4AF37]/30 via-[#FCE59A]/20 to-[#D4AF37]/30 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>

                                <img src="/assets/aaib.png" alt="Fashion Style" class="relative h-16 w-auto z-10" />
                            </div>
                        </div>

                        <!-- Welcome -->
                        <h2 class="text-3xl font-bold mb-2">
                            <span
                                class="bg-gradient-to-r from-[#B8860B] via-[#D4AF37] to-[#FCE59A] bg-clip-text text-transparent">
                                Welcome Back
                            </span>
                        </h2>

                        <p class="text-gray-600 text-sm">
                            Sign in to your account to continue
                        </p>

                        <!-- Gold Divider -->
                        <div class="flex justify-center mt-4">
                            <div class="h-[2px] w-20 bg-gradient-to-r from-transparent via-[#D4AF37] to-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- FORM -->
                    <form @submit.prevent="submit" class="space-y-6">

                        <!-- Email Field -->
                        <div>
                            <InputLabel for="email" value="Email Address"
                                class="text-sm font-semibold text-gray-800 mb-1 block" />

                            <div class="relative">

                                <!-- Email Icon -->
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-[#B8860B]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </div>

                                <TextInput id="email" type="email"
                                    class="mt-1 block w-full pl-10 rounded-lg border border-[#D4AF37]/40 bg-white p-3 focus:border-[#D4AF37] focus:ring focus:ring-[#FCE59A]/40 focus:ring-opacity-50 transition-all duration-200"
                                    placeholder="example@email.com" v-model="form.email" required autofocus
                                    autocomplete="username" />
                            </div>

                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.email" />
                        </div>

                        <!-- Password Field -->
                        <div>
                            <InputLabel for="password" value="Password"
                                class="text-sm font-semibold text-gray-800 mb-1 block" />

                            <div class="relative">

                                <!-- Lock Icon -->
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-[#B8860B]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V8a4 4 0 00-8 0v3h8z">
                                        </path>
                                    </svg>
                                </div>

                                <TextInput id="password" type="password"
                                    class="mt-1 block w-full pl-10 rounded-lg border border-[#D4AF37]/40 bg-white p-3 focus:border-[#D4AF37] focus:ring focus:ring-[#FCE59A]/40 focus:ring-opacity-50 transition-all duration-200"
                                    placeholder="Enter your password" v-model="form.password" required
                                    autocomplete="current-password" />
                            </div>

                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password" />
                        </div>

                        <!-- Remember Me / Forgot Password -->
                        <div class="flex justify-between items-center">

                            <label class="flex items-center gap-2 cursor-pointer group">
                                <Checkbox name="remember" v-model:checked="form.remember"
                                    class="text-[#D4AF37] focus:ring-[#D4AF37] rounded border-gray-300" />

                                <span class="text-sm text-gray-600 group-hover:text-[#B8860B] transition-colors">
                                    Remember me
                                </span>
                            </label>

                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="text-sm text-[#B8860B] hover:text-[#D4AF37] font-semibold transition-colors duration-200">
                                Forgot Password?
                            </Link>
                        </div>

                        <!-- SIGN IN BUTTON -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#000000] via-[#1c1608] to-[#D4AF37] hover:from-[#17130a] hover:via-[#D4AF37] hover:to-[#FCE59A] text-[#FFF7EB] hover:text-black py-3 rounded-lg font-semibold flex justify-center items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-[#D4AF37]/40 transform hover:-translate-y-0.5"
                            :class="{
                                'opacity-50 cursor-not-allowed':
                                    form.processing
                            }" :disabled="form.processing">

                            <!-- Loading -->
                            <svg v-if="form.processing" class="animate-spin h-5 w-5 text-[#FCE59A]"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            <span v-else>
                                Sign In
                            </span>

                            <!-- Arrow -->
                            <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Sign Up -->
                    <div class="mt-8 pt-6 border-t border-[#D4AF37]/20">
                        <p class="text-sm text-center text-gray-600">
                            Don't have an account?

                            <Link href="/register"
                                class="text-[#B8860B] hover:text-[#D4AF37] font-semibold ml-1 transition-colors duration-200">
                                Create Account
                            </Link>
                        </p>
                    </div>

                    <!-- Bottom Brand -->
                    <div class="text-center mt-6">
                        <p class="text-[10px] tracking-[0.3em] text-[#B8860B]/70 uppercase">
                            Fashion • Wine • Life Style
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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

<style scoped>
/* ================================
   Luxury Fashion Theme
   ================================ */

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

form {
    animation: fadeIn 0.6s ease-out;
}

/* Gold Input Focus */

input:focus {
    outline: none;
    border-color: #D4AF37 !important;

    box-shadow:
        0 0 0 3px rgba(212, 175, 55, 0.15),
        0 0 15px rgba(212, 175, 55, 0.08);
}

/* Loading Animation */

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Gold shimmer on button */

button:not(:disabled) {
    position: relative;
    overflow: hidden;
}

button:not(:disabled)::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;

    background: linear-gradient(90deg,
            transparent,
            rgba(255, 255, 255, 0.18),
            transparent);

    transform: skewX(-20deg);
    transition: left 0.7s ease;
}

button:not(:disabled):hover::before {
    left: 150%;
}

/* Mobile */

@media (max-width: 768px) {
    .min-h-screen {
        padding: 1rem;
    }
}
</style>