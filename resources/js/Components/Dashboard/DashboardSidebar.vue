<template>
  <aside 
    class="fixed left-0 top-0 h-screen border-r border-ledger-border bg-ledger z-40 flex flex-col transition-all duration-300 select-none shadow-sm"
    :class="collapsed ? 'w-16' : 'w-60'"
  >
    <!-- Logo -->
    <div class="h-16 flex items-center border-b border-ledger-border px-4 shrink-0">
      <Link :href="type === 'merchant' ? '/dashboard' : '/developer'" class="flex items-center gap-2 overflow-hidden w-full">
        <div class="w-7 h-7 bg-pulse rounded-md flex items-center justify-center shrink-0 text-void font-bold text-sm">
          N
        </div>
        <Transition
          enter-active-class="transition-all duration-200 ease-out"
          enter-from-class="opacity-0 -translate-x-4"
          enter-to-class="opacity-100 translate-x-0"
          leave-active-class="transition-all duration-150 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div v-if="!collapsed" class="whitespace-nowrap ml-1">
            <div class="font-semibold text-base text-text-primary leading-tight">Noryxon</div>
            <div class="text-[11px] text-text-muted">
              {{ type === 'merchant' ? 'Merchant Vault' : 'Dev Portal' }}
            </div>
          </div>
        </Transition>
      </Link>
    </div>

    <!-- Testnet Banner -->
    <div 
      v-if="isTestnet"
      class="mx-3 mt-3 py-1.5 text-center text-[11px] font-semibold rounded-md shrink-0 bg-amber-500/10 text-amber-500 border border-amber-500/20"
    >
      {{ collapsed ? '⚠' : 'Testnet Mode' }}
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
      <Link
        v-for="item in navItems"
        :key="item.href"
        :href="item.href"
        class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-200 relative group/nav font-medium"
        :class="isActive(item.href) 
          ? 'text-pulse bg-pulse/10' 
          : 'text-text-muted hover:text-text-primary hover:bg-ledger-light'"
      >
        <div class="w-5 h-5 flex items-center justify-center shrink-0" v-html="item.icon"></div>
        <Transition
          enter-active-class="transition-all duration-200 ease-out"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition-all duration-150 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <span v-if="!collapsed" class="whitespace-nowrap">{{ item.label }}</span>
        </Transition>
        
        <!-- Tooltip for collapsed mode -->
        <div 
          v-if="collapsed"
          class="absolute left-full ml-3 px-3 py-1.5 bg-void border border-ledger-border text-text-primary text-xs rounded shadow-lg whitespace-nowrap opacity-0 group-hover/nav:opacity-100 pointer-events-none transition-opacity z-50"
        >
          {{ item.label }}
        </div>
      </Link>
    </nav>

    <!-- Collapse Toggle -->
    <div class="border-t border-ledger-border p-3 shrink-0">
      <button 
        @click="collapsed = !collapsed"
        class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm text-text-muted hover:text-text-primary hover:bg-ledger-light rounded-lg transition-colors font-medium"
      >
        <svg class="w-4 h-4 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
        </svg>
        <span v-if="!collapsed">Collapse</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useDashboard } from '@/Composables/useDashboard';

const props = defineProps({
  type: { type: String, default: 'merchant' }, // 'merchant' | 'developer'
});

const { isTestnet } = useDashboard();
const collapsed = ref(false);
const page = usePage();

const isActive = (href) => {
  const url = page.url;
  if (href === '/dashboard' || href === '/developer') {
    return url === href;
  }
  return url.startsWith(href);
};

const merchantNav = [
  { label: 'Overview', href: '/dashboard', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>' },
  { label: 'XPUB Vault', href: '/dashboard/xpub-vault', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>' },
  { label: 'Live Monitor', href: '/dashboard/live-monitor', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
  { label: 'Analytics', href: '/dashboard/analytics', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>' },
  { label: 'Invoices', href: '/dashboard/invoices', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' },
  { label: 'Settings', href: '/dashboard/settings', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' },
];

const developerNav = [
  { label: 'Overview', href: '/developer', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>' },
  { label: 'API Keys', href: '/developer/api-keys', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>' },
  { label: 'Playground', href: '/developer/playground', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' },
  { label: 'Webhooks', href: '/developer/webhooks', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>' },
  { label: 'Sandbox', href: '/developer/sandbox', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>' },
];

const navItems = computed(() => props.type === 'merchant' ? merchantNav : developerNav);
</script>
