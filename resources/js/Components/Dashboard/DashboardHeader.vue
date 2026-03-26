<template>
  <header class="flex justify-between items-center mb-12">
    <div>
      <h2 class="text-3xl font-headline font-extrabold text-on-surface tracking-tight">{{ greeting }}, {{ userName }}</h2>
      <p class="text-on-surface-variant font-medium mt-1">{{ subtitle }}</p>
    </div>
    <div class="flex items-center gap-3">
      <!-- Testnet/Mainnet Toggle -->
      <div class="flex items-center bg-surface-container-lowest px-4 py-2 rounded-full shadow-sm border border-outline-variant/10">
        <span class="material-symbols-outlined text-primary mr-2 text-lg">account_balance</span>
        <span class="text-sm font-semibold">{{ isTestnet ? 'TESTNET' : 'MAINNET' }}</span>
        <button @click="toggleTestnet" class="ml-3 w-10 h-5 rounded-full relative cursor-pointer transition-colors" :class="isTestnet ? 'bg-primary-container/30' : 'bg-surface-container-highest'">
          <div class="absolute top-0.5 w-4 h-4 bg-primary rounded-full shadow-sm transition-all" :class="isTestnet ? 'right-0.5' : 'left-0.5'"></div>
        </button>
      </div>

      <!-- Dark Mode Toggle -->
      <button
        @click="toggleDark"
        class="p-2 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
      >
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">
          {{ isDark ? 'light_mode' : 'dark_mode' }}
        </span>
      </button>

      <!-- Notifications -->
      <button class="relative p-2 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors">
        <span class="material-symbols-outlined">notifications</span>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-primary-container rounded-full border-2 border-surface"></span>
      </button>

      <!-- User Avatar -->
      <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary-fixed shadow-inner cta-gradient flex items-center justify-center text-white font-bold text-lg">
        {{ userInitial }}
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useDashboard } from '@/Composables/useDashboard';
import { useTheme } from '@/Composables/useTheme';

defineProps({
  pageTitle: { type: String, default: 'Dashboard' },
  breadcrumb: { type: String, default: '' },
  subtitle: { type: String, default: 'Your financial ecosystem is fully synchronized.' },
  compact: { type: Boolean, default: false },
});

const { isTestnet, toggleTestnet } = useDashboard();
const { isDark, toggleDark } = useTheme();
const page = usePage();

const userName = computed(() => page.props.auth?.user?.name?.split(' ')[0] || 'User');
const userInitial = computed(() => page.props.auth?.user?.name?.charAt(0) || 'U');

const greeting = computed(() => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Good morning';
  if (hour < 17) return 'Good afternoon';
  return 'Good evening';
});
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
