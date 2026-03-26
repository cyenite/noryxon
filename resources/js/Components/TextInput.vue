<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full px-4 py-3 rounded-xl border border-outline-variant/25 bg-surface-container-low/50 text-on-surface text-sm font-medium placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 focus:bg-surface-container-lowest hover:border-outline-variant/40 transition-all duration-200 outline-none"
        v-model="model"
        ref="input"
    />
</template>
