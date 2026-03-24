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
        class="rounded-xl border border-outline-variant/30 bg-surface-container-lowest text-on-surface shadow-sm placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-primary/20 focus:ring-2 transition-colors"
        v-model="model"
        ref="input"
    />
</template>
