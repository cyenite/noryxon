<template>
  <div class="group relative bg-surface-container-lowest border border-outline-variant/15 p-5 rounded-2xl transition-all duration-300 hover:shadow-lg hover:shadow-primary/5 hover:border-primary/20 hover:-translate-y-0.5 overflow-hidden">
    <!-- Hover glow -->
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>

    <div class="flex items-start justify-between mb-3">
      <div class="text-sm text-on-surface-variant font-medium">{{ label }}</div>
      <div
        v-if="change !== undefined"
        class="text-[11px] font-bold px-2 py-0.5 rounded-lg"
        :class="change >= 0
          ? 'text-tertiary bg-tertiary-container/15'
          : 'text-error bg-error/10'"
      >
        {{ change >= 0 ? '+' : '' }}{{ change }}%
      </div>
    </div>

    <div class="text-3xl font-extrabold text-on-surface mb-1 tracking-tight font-headline">
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
