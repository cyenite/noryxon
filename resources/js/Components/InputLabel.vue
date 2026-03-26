<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-[13px] font-bold text-on-surface tracking-tight">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
