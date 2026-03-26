<template>
  <div class="group relative bg-surface-container-lowest border border-outline-variant/10 p-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-primary/5 overflow-hidden">
    <div class="flex items-start justify-between mb-3">
      <div class="text-xs font-label text-on-surface-variant uppercase tracking-wider">{{ label }}</div>
      <div
        v-if="change !== undefined"
        class="text-[10px] font-bold px-2 py-0.5 rounded-full"
        :class="change >= 0
          ? 'text-on-tertiary-container bg-tertiary-container/20'
          : 'text-on-error-container bg-error-container/20'"
      >
        {{ change >= 0 ? '+' : '' }}{{ change }}%
      </div>
    </div>

    <div class="text-2xl font-bold text-on-surface tracking-tight font-headline">
      {{ displayValue }}
    </div>

    <div v-if="subtitle" class="text-[10px] text-on-surface-variant/60 mt-2 font-mono font-bold tracking-widest uppercase">
      {{ subtitle }}
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  change: { type: Number, default: undefined },
  subtitle: { type: String, default: '' },
  prefix: { type: String, default: '' },
});

const displayValue = props.prefix ? `${props.prefix}${props.value}` : props.value;
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
