<template>
  <button 
    @click="cycleTheme"
    class="flex items-center justify-center p-2 rounded-md border border-ledger-border hover:border-pulse bg-ledger text-text-muted hover:text-pulse transition-all"
    title="Toggle Theme"
  >
    <svg v-if="currentTheme === 'void'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg v-else-if="currentTheme === 'corporate'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg v-else-if="currentTheme === 'terminal'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
    </svg>
    <svg v-else-if="currentTheme === 'neon'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
    </svg>
    <span class="sr-only">Toggle theme</span>
  </button>
</template>

<script setup>
import { useTheme } from '@/Composables/useTheme';
const { currentTheme, cycleTheme } = useTheme();
</script>
