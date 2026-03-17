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
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full bg-node animate-pulse"></div>
                <span class="font-mono text-[10px] text-node tracking-widest uppercase">EMAIL_VERIFICATION</span>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-text-primary">Check your inbox</h2>
            <p class="mt-2 text-sm text-text-muted leading-relaxed">
                We sent a verification link to your email. Click it to activate your account and start generating invoices. If you don't see it, check your spam folder.
            </p>
        </div>

        <div v-if="verificationLinkSent" class="mb-6 px-4 py-3 border border-pulse/30 bg-pulse/5 rounded-lg">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-pulse shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-sm font-medium text-pulse">A fresh verification link has been sent to your email.</span>
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
                    class="w-full text-center py-3 rounded-md border border-ledger-border text-sm text-text-muted font-medium hover:border-text-muted hover:text-text-primary transition-colors"
                >
                    Sign Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
