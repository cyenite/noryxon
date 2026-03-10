<template>
  <div class="min-h-screen font-sans bg-void text-text-primary">
    <!-- Navigation (Bonus: Simple institutional header) -->
    <header class="border-b border-ledger-border bg-ledger/80 backdrop-blur-md fixed top-0 w-full z-50">
      <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <div class="font-black tracking-tighter text-2xl uppercase flex items-center">
          <!-- Logo symbol -->
          <div class="w-6 h-6 border-2 border-pulse mr-2 flex items-center justify-center">
            <div class="w-1.5 h-1.5 bg-pulse"></div>
          </div>
          NORYXON
        </div>
        <nav class="hidden md:flex items-center gap-8 font-mono text-xs tracking-wider text-text-muted">
          <Link href="/pricing" class="hover:text-pulse transition-colors">PRICING</Link>
          <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">DOCUMENTATION</a>
          <a href="https://dev.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">DEVELOPER_PORTAL</a>
          <a href="https://app.noryxon.com/login" class="hover:text-pulse transition-colors">DASHBOARD_LOGIN</a>
          <a href="https://app.noryxon.com/register" class="text-void bg-pulse px-4 py-2 font-bold hover:shadow-[0_0_15px_#00ffa3] transition-all">GET_STARTED</a>
        </nav>
      </div>
    </header>

    <main class="pt-16">
      <HeroStation />
      <ChainTicker />
      <TerminalDemo />
      <ProtocolGrid />
      <VaultCTA />
    </main>
    
    <footer class="bg-void border-t border-ledger-border py-8 text-center font-mono text-xs text-text-muted">
      NORYXON BRIDGE PROTOCOL &copy; 2026. ALL EXECUTIONS FINAL.
      <div class="mt-4 flex justify-center gap-6">
         <Link href="/pricing" class="hover:text-pulse transition-colors">Pricing</Link>
         <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">Docs</a>
         <a href="https://dev.noryxon.com" class="hover:text-pulse transition-colors">Dev Portal</a>
         <a href="https://app.noryxon.com" class="hover:text-pulse transition-colors">Dashboard</a>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import HeroStation from '@/Components/HeroStation.vue';
import ChainTicker from '@/Components/ChainTicker.vue';
import TerminalDemo from '@/Components/TerminalDemo.vue';
import ProtocolGrid from '@/Components/ProtocolGrid.vue';
import VaultCTA from '@/Components/VaultCTA.vue';
</script>
