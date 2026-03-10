<template>
  <header class="h-16 border-b border-ledger-border bg-void/80 backdrop-blur-md flex items-center justify-between px-6 sticky top-0 z-30">
    <!-- Left: Page Title -->
    <div class="flex items-center gap-4">
      <div>
        <h1 class="text-xl font-semibold text-text-primary">{{ pageTitle }}</h1>
        <div v-if="breadcrumb" class="text-xs text-text-muted mt-0.5 font-medium flex items-center gap-1">
          {{ breadcrumb.split(' > ').join(' / ') }}
        </div>
      </div>
    </div>

    <!-- Right: Controls -->
    <div class="flex items-center gap-4">
      <!-- Testnet Toggle -->
      <button 
        @click="toggleTestnet"
        class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300"
        :class="isTestnet 
          ? 'bg-amber-500/10 text-amber-500 hover:bg-amber-500/20' 
          : 'bg-ledger text-text-muted hover:text-text-primary hover:bg-ledger-light'"
      >
        <span class="w-2 h-2 rounded-full transition-colors" :class="isTestnet ? 'bg-amber-500' : 'bg-pulse'"></span>
        {{ isTestnet ? 'Testnet' : 'Mainnet' }}
      </button>

      <!-- Theme Toggle -->
      <ThemeToggle />

      <!-- Notifications Bell -->
      <button class="relative p-2 rounded-full text-text-muted hover:text-text-primary hover:bg-ledger-light transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-pulse rounded-full border-2 border-void"></span>
      </button>

      <!-- User Menu -->
      <div class="flex items-center gap-3 pl-4 border-l border-ledger-border cursor-pointer hover:opacity-80 transition-opacity">
        <div class="w-8 h-8 rounded-full bg-pulse text-void flex items-center justify-center font-bold text-sm">
          S
        </div>
        <div v-if="!compact" class="hidden md:block">
          <div class="text-sm text-text-primary font-semibold leading-none">Satoshi</div>
          <div class="text-[11px] text-text-muted mt-0.5">Admin</div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { useDashboard } from '@/Composables/useDashboard';

defineProps({
  pageTitle: { type: String, default: 'Dashboard' },
  breadcrumb: { type: String, default: '' },
  compact: { type: Boolean, default: false },
});

const { isTestnet, toggleTestnet } = useDashboard();
</script>
