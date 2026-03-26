<template>
  <Head :title="pageTitle + ' - Noryxon'" />
  <div class="min-h-screen font-sans bg-surface text-on-surface">
    <ToastContainer />

    <!-- Sidebar -->
    <DashboardSidebar :type="dashboardType" v-model:collapsed="sidebarCollapsed" />

    <!-- Main Canvas -->
    <main class="min-h-screen p-8 lg:p-12 transition-all duration-300" :class="sidebarCollapsed ? 'md:ml-[72px]' : 'md:ml-64'">
      <DashboardHeader :pageTitle="pageTitle" :breadcrumb="breadcrumb" :subtitle="subtitle" />

      <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t border-outline-variant/10 px-12 py-8 flex flex-col md:flex-row items-center justify-between gap-4 bg-surface transition-all duration-300" :class="sidebarCollapsed ? 'md:ml-[72px]' : 'md:ml-64'">
      <div class="text-xs text-on-surface-variant/60 font-medium uppercase tracking-wider">
        &copy; {{ new Date().getFullYear() }} Noryxon. All rights reserved.
      </div>
      <div class="flex items-center gap-6">
        <a href="#" class="text-xs text-on-surface-variant/60 hover:text-primary transition-colors uppercase tracking-wider font-medium">Legal</a>
        <a href="#" class="text-xs text-on-surface-variant/60 hover:text-primary transition-colors uppercase tracking-wider font-medium">Privacy</a>
        <div class="flex items-center gap-2 pl-6 border-l border-outline-variant/20">
          <span class="w-1.5 h-1.5 bg-tertiary-container rounded-full animate-pulse"></span>
          <span class="text-xs text-primary font-bold uppercase tracking-wider">SDK Status: Operational</span>
        </div>
      </div>
    </footer>

    <!-- Mobile Bottom Nav -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-surface-container-lowest/90 backdrop-blur-xl border-t border-outline-variant/10 flex justify-around items-center px-4 py-3 z-50">
      <Link :href="dashboardType === 'merchant' ? '/dashboard' : '/developer'" class="flex flex-col items-center gap-1 text-primary">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-[10px] font-bold">Dash</span>
      </Link>
      <Link href="/dashboard/invoices" class="flex flex-col items-center gap-1 text-on-surface-variant/50">
        <span class="material-symbols-outlined">description</span>
        <span class="text-[10px]">Invoices</span>
      </Link>
      <Link href="/dashboard/wallets" class="flex flex-col items-center gap-1 text-on-surface-variant/50">
        <span class="material-symbols-outlined">account_balance_wallet</span>
        <span class="text-[10px]">Wallets</span>
      </Link>
      <Link href="/dashboard/settings" class="flex flex-col items-center gap-1 text-on-surface-variant/50">
        <span class="material-symbols-outlined">settings</span>
        <span class="text-[10px]">Settings</span>
      </Link>
    </nav>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ToastContainer from '@/Components/ToastContainer.vue';
import DashboardSidebar from '@/Components/Dashboard/DashboardSidebar.vue';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader.vue';

defineProps({
  pageTitle: { type: String, default: 'Dashboard' },
  breadcrumb: { type: String, default: '' },
  dashboardType: { type: String, default: 'merchant' },
  subtitle: { type: String, default: 'Your financial ecosystem is fully synchronized.' },
});

const sidebarCollapsed = ref(false);
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
