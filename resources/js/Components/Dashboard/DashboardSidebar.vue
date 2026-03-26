<template>
  <aside
    class="hidden md:flex fixed left-0 top-0 h-screen bg-surface-container-low flex-col z-40 transition-all duration-300 select-none border-r border-outline-variant/5"
    :class="collapsed ? 'w-[72px] px-3 py-6' : 'w-64 p-6'"
  >
    <!-- Logo -->
    <div class="mb-8" :class="collapsed ? 'flex justify-center' : ''">
      <Link :href="type === 'merchant' ? '/dashboard' : '/developer'" class="flex items-center gap-3 overflow-hidden">
        <div class="w-9 h-9 cta-gradient rounded-lg flex items-center justify-center shrink-0 text-white font-bold text-sm shadow-sm shadow-primary/20">
          N
        </div>
        <div v-if="!collapsed" class="min-w-0">
          <h1 class="text-lg font-extrabold text-on-surface tracking-tighter font-headline">Noryxon Vault</h1>
          <p class="text-[10px] text-on-surface-variant/70 uppercase tracking-widest mt-0.5">
            {{ type === 'merchant' ? 'Premium Digital Assets' : 'Developer Portal' }}
          </p>
        </div>
      </Link>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 space-y-1 overflow-y-auto">
      <Link
        v-for="item in navItems"
        :key="item.href"
        :href="item.href"
        class="flex items-center text-sm font-medium rounded-lg transition-colors duration-200 ease-linear relative group/nav"
        :class="[
          isActive(item.href)
            ? 'bg-surface-container-lowest text-primary shadow-sm'
            : 'text-on-surface-variant/60 hover:bg-surface-container-lowest/50',
          collapsed ? 'justify-center px-0 py-3' : 'gap-3 px-4 py-3'
        ]"
        :title="collapsed ? item.label : ''"
      >
        <span class="material-symbols-outlined text-xl shrink-0">{{ item.icon }}</span>
        <span v-if="!collapsed" class="whitespace-nowrap">{{ item.label }}</span>

        <!-- Tooltip for collapsed mode -->
        <div
          v-if="collapsed"
          class="absolute left-full ml-3 px-3 py-1.5 bg-surface-container-lowest border border-outline-variant/10 text-on-surface text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover/nav:opacity-100 pointer-events-none transition-opacity z-50 font-medium"
        >
          {{ item.label }}
        </div>
      </Link>
    </nav>

    <!-- Bottom Section -->
    <div class="pt-6 mt-6 border-t border-outline-variant/10 space-y-1">
      <!-- CTA Button -->
      <button
        v-if="!collapsed"
        class="w-full cta-gradient text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 mb-4 shadow-lg shadow-primary/10 hover:shadow-xl hover:shadow-primary/20 transition-all active:scale-95"
      >
        <span class="material-symbols-outlined text-sm">add</span>
        Create Invoice
      </button>
      <button
        v-else
        class="w-full cta-gradient text-white py-2.5 rounded-lg flex items-center justify-center mb-4 shadow-lg shadow-primary/10 hover:shadow-xl transition-all active:scale-95 group/cta relative"
      >
        <span class="material-symbols-outlined text-lg">add</span>
        <div class="absolute left-full ml-3 px-3 py-1.5 bg-surface-container-lowest border border-outline-variant/10 text-on-surface text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover/cta:opacity-100 pointer-events-none transition-opacity z-50 font-medium">
          Create Invoice
        </div>
      </button>

      <!-- Support & Settings -->
      <Link
        href="#"
        class="flex items-center text-on-surface-variant/60 hover:bg-surface-container-lowest/50 transition-colors rounded-lg text-sm font-medium relative group/bottom"
        :class="collapsed ? 'justify-center px-0 py-3' : 'gap-3 px-4 py-3'"
      >
        <span class="material-symbols-outlined text-xl">help_outline</span>
        <span v-if="!collapsed">Support</span>
        <div v-if="collapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-surface-container-lowest border border-outline-variant/10 text-on-surface text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover/bottom:opacity-100 pointer-events-none transition-opacity z-50 font-medium">
          Support
        </div>
      </Link>
      <Link
        :href="route('profile.edit')"
        class="flex items-center text-on-surface-variant/60 hover:bg-surface-container-lowest/50 transition-colors rounded-lg text-sm font-medium relative group/bottom"
        :class="collapsed ? 'justify-center px-0 py-3' : 'gap-3 px-4 py-3'"
      >
        <span class="material-symbols-outlined text-xl">settings</span>
        <span v-if="!collapsed">Settings</span>
        <div v-if="collapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-surface-container-lowest border border-outline-variant/10 text-on-surface text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover/bottom:opacity-100 pointer-events-none transition-opacity z-50 font-medium">
          Settings
        </div>
      </Link>

      <!-- Collapse Toggle -->
      <button
        @click="emit('update:collapsed', !collapsed)"
        class="w-full flex items-center justify-center px-3 py-2 text-sm text-on-surface-variant/60 hover:text-on-surface hover:bg-surface-container-lowest/50 rounded-lg transition-colors font-medium mt-2"
        :class="collapsed ? '' : 'gap-2'"
      >
        <span class="material-symbols-outlined text-xl transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''">chevron_left</span>
        <span v-if="!collapsed">Collapse</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useDashboard } from '@/Composables/useDashboard';

const props = defineProps({
  type: { type: String, default: 'merchant' },
  collapsed: { type: Boolean, default: false },
});

const emit = defineEmits(['update:collapsed']);

const { isTestnet } = useDashboard();
const page = usePage();

const isActive = (href) => {
  const url = page.url;
  if (href === '/dashboard' || href === '/developer') {
    return url === href;
  }
  return url.startsWith(href);
};

const merchantNav = [
  { label: 'Dashboard', href: '/dashboard', icon: 'dashboard' },
  { label: 'Invoices', href: '/dashboard/invoices', icon: 'description' },
  { label: 'Wallets', href: '/dashboard/wallets', icon: 'account_balance_wallet' },
  { label: 'Live Monitor', href: '/dashboard/live-monitor', icon: 'monitoring' },
  { label: 'Analytics', href: '/dashboard/analytics', icon: 'assessment' },
];

const developerNav = [
  { label: 'Overview', href: '/developer', icon: 'terminal' },
  { label: 'API Keys', href: '/developer/api-keys', icon: 'key' },
  { label: 'Playground', href: '/developer/playground', icon: 'code' },
  { label: 'Webhooks', href: '/developer/webhooks', icon: 'webhook' },
  { label: 'Sandbox', href: '/developer/sandbox', icon: 'science' },
];

const navItems = computed(() => props.type === 'merchant' ? merchantNav : developerNav);
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
