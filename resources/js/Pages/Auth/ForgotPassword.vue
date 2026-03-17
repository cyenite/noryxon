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
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full bg-node animate-pulse"></div>
                <span class="font-mono text-[10px] text-node tracking-widest uppercase">ACCOUNT_RECOVERY</span>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-text-primary">Reset your password</h2>
            <p class="mt-1 text-sm text-text-muted">
                Enter your email and we'll send you a reset link. Happens to the best of us.
            </p>
        </div>

        <div v-if="status" class="mb-4 px-4 py-3 border border-pulse/30 bg-pulse/5 text-sm font-medium text-pulse rounded-lg">
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

        <div class="mt-8 pt-6 border-t border-ledger-border text-center text-sm text-text-muted">
            Remember your password?
            <Link :href="route('login')" class="text-pulse font-semibold hover:text-pulse/80 transition-colors ml-1">
                Back to sign in
            </Link>
        </div>
    </GuestLayout>
</template>
