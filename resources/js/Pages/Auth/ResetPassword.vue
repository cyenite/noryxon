<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);

const passwordsMatch = computed(() => {
    return form.password && form.password_confirmation && form.password === form.password_confirmation;
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-8">
            <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 rounded-full bg-primary/5 border border-primary/15">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
                </span>
                <span class="font-mono text-[10px] text-primary tracking-widest uppercase font-bold">Password Reset</span>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tighter text-on-surface font-headline">Choose a new password</h2>
            <p class="mt-2 text-sm text-on-surface-variant">Make it strong. Your invoices are counting on you.</p>
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
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />
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
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-on-surface-variant/50 hover:text-on-surface transition-colors"
                    >
                        <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm New Password" />
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
                    <div class="w-1.5 h-1.5 rounded-full bg-error"></div>
                    <span class="text-xs text-error font-medium">Passwords don't match</span>
                </div>
                <div v-if="passwordsMatch" class="flex items-center gap-1.5 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-tertiary-container"></div>
                    <span class="text-xs text-tertiary font-medium">Passwords match</span>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <PrimaryButton
                class="w-full justify-center py-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="!form.processing">Reset Password</span>
                <span v-else class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Resetting...
                </span>
            </PrimaryButton>
        </form>
    </GuestLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
