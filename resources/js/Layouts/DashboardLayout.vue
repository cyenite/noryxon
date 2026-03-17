<template>
  <Head :title="pageTitle + ' - Noryxon'" />
  <div class="min-h-screen font-sans bg-void text-text-primary">
    <ToastContainer />
    
    <!-- Sidebar -->
    <DashboardSidebar :type="dashboardType" />
    
    <!-- Main Area -->
    <div class="transition-all duration-300 ml-60" id="dashboard-main">
      <DashboardHeader :pageTitle="pageTitle" :breadcrumb="breadcrumb" />
      
      <main class="p-6 min-h-[calc(100vh-4rem)]">
        <slot />
      </main>

      <footer class="border-t border-ledger-border px-6 py-5 flex items-center justify-between text-sm text-text-muted bg-void">
        <div>
          &copy; {{ new Date().getFullYear() }} Noryxon Invoice Systems
        </div>
        <div class="font-medium text-text-primary">
          {{ dashboardType === 'merchant' ? 'Invoice Vault v2.1.0' : 'Dev Portal v2.1.0' }}
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import ToastContainer from '@/Components/ToastContainer.vue';
import DashboardSidebar from '@/Components/Dashboard/DashboardSidebar.vue';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader.vue';

defineProps({
  pageTitle: { type: String, default: 'Dashboard' },
  breadcrumb: { type: String, default: '' },
  dashboardType: { type: String, default: 'merchant' },
});
</script>
