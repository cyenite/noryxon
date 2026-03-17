<template>
  <div class="min-h-screen font-sans bg-void text-text-primary">
    <!-- Navigation (Bonus: Simple institutional header) -->
    <header class="border-b border-ledger-border bg-ledger/80 backdrop-blur-md fixed top-0 w-full z-50">
      <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <div 
          class="font-black tracking-tighter text-2xl uppercase flex items-center cursor-pointer select-none"
          @click="handleLogoClick"
          title="Noryxon Core"
        >
          <!-- Logo symbol -->
          <div class="w-6 h-6 border-2 border-pulse mr-2 flex items-center justify-center transition-all duration-300" :class="{'rotate-180 scale-110': clickCount > 0 && clickCount % 5 === 0}">
            <div class="w-1.5 h-1.5 bg-pulse transition-transform" :class="{'scale-150': clickCount > 0}"></div>
          </div>
          NORYXON
        </div>
        <nav class="hidden md:flex items-center gap-8 font-mono text-xs tracking-wider text-text-muted">
          <ThemeToggle />
          <Link href="/pricing" class="hover:text-pulse transition-colors">PRICING</Link>
          <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">DOCUMENTATION</a>
          <a href="/developer" class="hover:text-pulse transition-colors">DEVELOPER_PORTAL</a>
          <a href="/login" class="hover:text-pulse transition-colors">LOGIN</a>
          <a href="/register" class="hover:text-pulse transition-colors">REGISTER</a>
          <a href="/dashboard" class="text-void bg-pulse px-4 py-2 font-bold hover:shadow-[0_0_15px] hover:shadow-pulse transition-all">GET_STARTED</a>
        </nav>
      </div>
    </header>

    <main class="pt-16">
      <ToastContainer />
      <HeroStation />
      <ChainTicker />
      <TerminalDemo />
      <ProtocolGrid />
      <VaultCTA />
    </main>
    
    <footer class="bg-void border-t border-ledger-border py-8 text-center font-mono text-xs text-text-muted">
      NORYXON INVOICE PROTOCOL &copy; {{ new Date().getFullYear() }}. DOCUMENTATION ONLY — NO CUSTODY.
      <div class="mt-4 flex justify-center gap-6">
         <Link href="/pricing" class="hover:text-pulse transition-colors">Pricing</Link>
         <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">Docs</a>
         <a href="/developer" class="hover:text-pulse transition-colors">Dev Portal</a>
         <a href="/dashboard" class="hover:text-pulse transition-colors">Dashboard</a>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import HeroStation from '@/Components/HeroStation.vue';
import ChainTicker from '@/Components/ChainTicker.vue';
import TerminalDemo from '@/Components/TerminalDemo.vue';
import ProtocolGrid from '@/Components/ProtocolGrid.vue';
import VaultCTA from '@/Components/VaultCTA.vue';

const { unlockTheme } = useTheme();
const clickCount = ref(0);

const handleLogoClick = () => {
    clickCount.value++;
    
    if (clickCount.value === 5) {
        unlockTheme('terminal');
    } else if (clickCount.value === 10) {
        unlockTheme('neon');
        // Reset after finding the last one
        clickCount.value = 0; 
    }
};
</script>
