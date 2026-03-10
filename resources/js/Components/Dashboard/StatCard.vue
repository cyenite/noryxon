<template>
  <div class="group bg-void border border-ledger-border p-5 rounded-xl transition-all duration-300 hover:shadow-lg hover:border-pulse/30">
    <div class="flex items-start justify-between mb-3">
      <div class="text-sm text-text-muted font-medium">{{ label }}</div>
      <div 
        v-if="change !== undefined" 
        class="text-[11px] font-semibold px-2 py-0.5 rounded-md"
        :class="change >= 0 
          ? 'text-green-500 bg-green-500/10' 
          : 'text-red-500 bg-red-500/10'"
      >
        {{ change >= 0 ? '+' : '' }}{{ change }}%
      </div>
    </div>

    <div class="text-3xl font-bold text-text-primary mb-1">
      {{ displayValue }}
    </div>
    
    <div v-if="subtitle" class="text-xs text-text-muted mt-2 font-medium">
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
