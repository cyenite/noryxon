<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-8">
            <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 rounded-full bg-primary/5 border border-primary/15">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
                </span>
                <span class="font-mono text-[10px] text-primary tracking-widest uppercase font-bold">Email Verification</span>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tighter text-on-surface font-headline">Check your inbox</h2>
            <p class="mt-2 text-sm text-on-surface-variant leading-relaxed">
                We sent a verification link to your email. Click it to activate your account and start generating invoices. If you don't see it, check your spam folder.
            </p>
        </div>

        <div v-if="verificationLinkSent" class="mb-6 px-4 py-3 border border-tertiary/30 bg-tertiary/5 rounded-xl">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-tertiary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-sm font-medium text-tertiary">A fresh verification link has been sent to your email.</span>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="flex flex-col gap-4">
                <PrimaryButton
                    class="w-full justify-center py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Resend Verification Email</span>
                    <span v-else class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Sending...
                    </span>
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full text-center py-3 rounded-xl border border-outline-variant/30 text-sm text-on-surface-variant font-medium hover:border-primary/30 hover:text-on-surface transition-colors"
                >
                    Sign Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
