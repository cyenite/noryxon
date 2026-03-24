<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8">
            <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 rounded-full bg-tertiary-container/10 border border-tertiary-container/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary-container opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-tertiary-container"></span>
                </span>
                <span class="font-mono text-[10px] text-tertiary tracking-widest uppercase font-bold">Secure Session</span>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tighter text-on-surface font-headline">Welcome back</h2>
            <p class="mt-2 text-sm text-on-surface-variant">Sign in to your invoice dashboard. Your paper trail awaits.</p>
        </div>

        <div v-if="status" class="mb-4 px-4 py-3 border border-primary/30 bg-primary/5 text-sm font-medium text-primary rounded-xl">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@company.com"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Password" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-on-surface-variant hover:text-primary transition-colors font-medium"
                    >
                        Forgot password?
                    </Link>
                </div>
                <div class="relative mt-1.5">
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pr-10"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-on-surface-variant/50 hover:text-on-surface transition-colors"
                    >
                        <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div class="flex items-center">
                <label class="flex items-center cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-on-surface-variant select-none">Remember me</span>
                </label>
            </div>

            <PrimaryButton
                class="w-full justify-center py-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="!form.processing">Sign In</span>
                <span v-else class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Authenticating...
                </span>
            </PrimaryButton>
        </form>

        <div class="mt-8 pt-6 border-t border-outline-variant/20 text-center text-sm text-on-surface-variant">
            Don't have an account?
            <Link :href="route('register')" class="text-primary font-bold hover:text-primary/80 transition-colors ml-1">
                Create one — it's free
            </Link>
        </div>
    </GuestLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
