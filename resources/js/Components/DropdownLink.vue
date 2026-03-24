<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-on-surface-variant transition duration-150 ease-in-out hover:bg-surface-container-low hover:text-on-surface focus:bg-surface-container-low focus:outline-none rounded-lg"
    >
        <slot />
    </Link>
</template>
