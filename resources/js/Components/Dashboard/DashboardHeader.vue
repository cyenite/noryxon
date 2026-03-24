<template>
  <header class="h-16 border-b border-outline-variant/15 bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-between px-6 sticky top-0 z-30">
    <!-- Left: Page Title -->
    <div class="flex items-center gap-4">
      <div>
        <h1 class="text-xl font-extrabold text-on-surface tracking-tight font-headline">{{ pageTitle }}</h1>
        <div v-if="breadcrumb" class="text-[11px] text-on-surface-variant mt-0.5 font-medium flex items-center gap-1 font-mono tracking-wider">
          {{ breadcrumb.split(' > ').join(' / ') }}
        </div>
      </div>
    </div>

    <!-- Right: Controls -->
    <div class="flex items-center gap-4">
      <!-- Testnet Toggle -->
      <button
        @click="toggleTestnet"
        class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300"
        :class="isTestnet
          ? 'bg-primary-container/15 text-primary border border-primary-container/30'
          : 'bg-surface-container-low text-on-surface-variant hover:text-on-surface hover:bg-surface-container'"
      >
        <span class="w-2 h-2 rounded-full transition-colors" :class="isTestnet ? 'bg-primary-container' : 'bg-tertiary-container'"></span>
        {{ isTestnet ? 'Testnet' : 'Mainnet' }}
      </button>

      <!-- Theme Toggle -->
      <ThemeToggle />

      <!-- Notifications Bell -->
      <button class="relative p-2 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-primary-container rounded-full border-2 border-surface-container-lowest"></span>
      </button>

      <!-- User Menu -->
      <div class="flex items-center gap-3 pl-4 border-l border-outline-variant/15 cursor-pointer group">
        <div class="w-8 h-8 rounded-xl cta-gradient text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-primary/20">
          {{ $page.props.auth.user.name?.charAt(0) || 'U' }}
        </div>
        <div v-if="!compact" class="hidden md:block">
          <div class="text-sm text-on-surface font-bold leading-none group-hover:text-primary transition-colors">{{ $page.props.auth.user.name }}</div>
          <div class="text-[11px] text-on-surface-variant mt-0.5">Admin</div>
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

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
