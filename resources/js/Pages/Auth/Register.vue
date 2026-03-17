<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);

const passwordStrength = computed(() => {
    const p = form.password;
    if (!p) return { score: 0, label: '', color: '' };
    let score = 0;
    if (p.length >= 8) score++;
    if (p.length >= 12) score++;
    if (/[A-Z]/.test(p)) score++;
    if (/[0-9]/.test(p)) score++;
    if (/[^A-Za-z0-9]/.test(p)) score++;

    if (score <= 1) return { score: 1, label: 'Weak', color: 'bg-red-500' };
    if (score <= 2) return { score: 2, label: 'Fair', color: 'bg-amber-500' };
    if (score <= 3) return { score: 3, label: 'Good', color: 'bg-blue-500' };
    return { score: 4, label: 'Strong', color: 'bg-green-500' };
});

const passwordsMatch = computed(() => {
    return form.password && form.password_confirmation && form.password === form.password_confirmation;
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full bg-node animate-pulse"></div>
                <span class="font-mono text-[10px] text-node tracking-widest uppercase">NEW_ACCOUNT</span>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-text-primary">Create your account</h2>
            <p class="mt-1 text-sm text-text-muted">Join Noryxon and start documenting transactions. Free up to $10K/mo.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="name" value="Full Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Jane Nakamura"
                />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Work Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="you@company.com"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />
                <div class="relative mt-1.5">
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pr-10"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Min. 8 characters"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-text-muted hover:text-text-primary transition-colors"
                    >
                        <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                <!-- Password strength meter -->
                <div v-if="form.password" class="mt-2">
                    <div class="flex gap-1">
                        <div v-for="i in 4" :key="i" class="h-1 flex-1 rounded-full transition-all duration-300" :class="i <= passwordStrength.score ? passwordStrength.color : 'bg-ledger-border'"></div>
                    </div>
                    <span class="text-[11px] font-medium mt-1 block" :class="passwordStrength.color.replace('bg-', 'text-')">{{ passwordStrength.label }}</span>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    :type="showPassword ? 'text' : 'password'"
                    class="mt-1.5 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Re-enter your password"
                />
                <div v-if="form.password_confirmation && !passwordsMatch" class="flex items-center gap-1.5 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                    <span class="text-xs text-red-500 font-medium">Passwords don't match</span>
                </div>
                <div v-if="passwordsMatch" class="flex items-center gap-1.5 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                    <span class="text-xs text-green-500 font-medium">Passwords match</span>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <PrimaryButton
                class="w-full justify-center py-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="!form.processing">Create Account</span>
                <span v-else class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Creating account...
                </span>
            </PrimaryButton>

            <p class="text-[11px] text-text-muted text-center leading-relaxed">
                By creating an account you agree to our Terms of Service. Noryxon generates documentation only — we never custody funds.
            </p>
        </form>

        <div class="mt-8 pt-6 border-t border-ledger-border text-center text-sm text-text-muted">
            Already have an account?
            <Link :href="route('login')" class="text-pulse font-semibold hover:text-pulse/80 transition-colors ml-1">
                Sign in
            </Link>
        </div>
    </GuestLayout>
</template>
