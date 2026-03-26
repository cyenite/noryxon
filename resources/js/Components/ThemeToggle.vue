<template>
  <button
    @click="cycleTheme"
    class="flex items-center justify-center p-2 rounded-md border border-outline-variant/10 hover:border-primary bg-surface-container-lowest text-on-surface-variant hover:text-primary transition-all"
    title="Toggle Theme"
  >
    <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">
      {{ themeIcon }}
    </span>
    <span class="sr-only">Toggle theme</span>
  </button>
</template>

<script setup>
import { computed } from 'vue';
import { useTheme } from '@/Composables/useTheme';
const { currentTheme, cycleTheme } = useTheme();

const themeIcon = computed(() => {
  switch (currentTheme.value) {
    case 'dark': return 'dark_mode';
    case 'terminal': return 'terminal';
    case 'neon': return 'electric_bolt';
    default: return 'light_mode';
  }
});
</script>
