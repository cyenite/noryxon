<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-8">
            <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 rounded-full bg-primary/5 border border-primary/15">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
                </span>
                <span class="font-mono text-[10px] text-primary tracking-widest uppercase font-bold">Account Recovery</span>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tighter text-on-surface font-headline">Reset your password</h2>
            <p class="mt-2 text-sm text-on-surface-variant">
                Enter your email and we'll send you a reset link. Happens to the best of us.
            </p>
        </div>

        <div v-if="status" class="mb-4 px-4 py-3 border border-tertiary/30 bg-tertiary/5 text-sm font-medium text-tertiary rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="email" value="Email Address" />
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

            <PrimaryButton
                class="w-full justify-center py-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="!form.processing">Send Reset Link</span>
                <span v-else class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Sending...
                </span>
            </PrimaryButton>
        </form>

        <div class="mt-8 pt-6 border-t border-outline-variant/20 text-center text-sm text-on-surface-variant">
            Remember your password?
            <Link :href="route('login')" class="text-primary font-bold hover:text-primary/80 transition-colors ml-1">
                Back to sign in
            </Link>
        </div>
    </GuestLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
