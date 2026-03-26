<template>
  <DashboardLayout pageTitle="Dashboard" breadcrumb="SYSTEM > OVERVIEW" dashboardType="merchant">

    <!-- Bento Grid Stats Section -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
      <!-- Large Stats Card (2/3 width) -->
      <div class="lg:col-span-2 glass-card rounded-xl p-8 shadow-sm border border-white/50 flex flex-col justify-between relative overflow-hidden">
        <div class="relative z-10">
          <div class="flex justify-between items-start mb-6">
            <div>
              <p class="text-sm font-label font-medium text-on-surface-variant uppercase tracking-widest">Total Documented Volume</p>
              <h3 class="text-5xl font-headline font-bold text-on-surface mt-2 tracking-tighter">{{ formatCurrency(stats.totalVolume) }}</h3>
            </div>
            <span v-if="stats.totalVolumeChange" class="px-3 py-1 bg-tertiary-container/20 text-on-tertiary-container text-xs font-bold rounded-full flex items-center gap-1">
              <span class="material-symbols-outlined text-xs">trending_up</span>
              +{{ stats.totalVolumeChange }}%
            </span>
          </div>
        </div>
        <!-- Mini Chart -->
        <div class="h-32 w-full mt-4 flex items-end gap-2">
          <div
            v-for="(day, idx) in chartData.slice(-7)"
            :key="idx"
            class="flex-1 bg-primary-fixed/30 rounded-t-lg transition-all hover:bg-primary-container cursor-pointer group/bar relative"
            :style="{ height: (day.volume / maxVolume * 100) + '%' }"
          >
            <!-- Tooltip -->
            <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-surface-container-lowest border border-outline-variant/10 rounded-xl px-3 py-2 opacity-0 group-hover/bar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-10 shadow-lg">
              <div class="text-xs font-medium text-on-surface-variant mb-0.5">{{ day.date }}</div>
              <div class="text-sm text-primary font-bold">{{ formatCurrency(day.volume) }}</div>
            </div>
          </div>
        </div>
        <!-- Abstract Glow -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary-fixed/10 blur-[100px] rounded-full"></div>
      </div>

      <!-- Side Stats Cluster (1/3 width) -->
      <div class="flex flex-col gap-6">
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
            <span class="material-symbols-outlined">pending_actions</span>
          </div>
          <div>
            <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Pending Invoices</p>
            <p class="text-2xl font-headline font-bold">{{ stats.pendingPayments }}</p>
          </div>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-lg bg-tertiary-container/10 flex items-center justify-center text-tertiary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span>
          </div>
          <div>
            <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Verified Transactions</p>
            <p class="text-2xl font-headline font-bold">{{ stats.successfulSettlements?.toLocaleString() }}</p>
          </div>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-lg bg-secondary-container/30 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined">account_balance_wallet</span>
          </div>
          <div>
            <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Connected Wallets</p>
            <p class="text-2xl font-headline font-bold">{{ stats.activeWallets }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
      <!-- Recent Documented Transactions Table -->
      <section class="xl:col-span-3">
        <div class="flex items-center justify-between mb-6">
          <h4 class="text-xl font-headline font-bold text-on-surface">Recent Documented Transactions</h4>
          <Link href="/dashboard/invoices" class="text-primary font-semibold text-sm hover:underline">View All</Link>
        </div>
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
          <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-outline-variant/5">
              <tr>
                <th class="px-6 py-4 text-xs font-label text-on-surface-variant uppercase tracking-widest">TXID</th>
                <th class="px-6 py-4 text-xs font-label text-on-surface-variant uppercase tracking-widest">Chain</th>
                <th class="px-6 py-4 text-xs font-label text-on-surface-variant uppercase tracking-widest text-right">Amount</th>
                <th class="px-6 py-4 text-xs font-label text-on-surface-variant uppercase tracking-widest">Status</th>
                <th class="px-6 py-4 text-xs font-label text-on-surface-variant uppercase tracking-widest">Time</th>
                <th class="px-6 py-4"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/5">
              <tr
                v-for="tx in displayTransactions"
                :key="tx.id || tx.txHash"
                class="hover:bg-surface-container/30 transition-colors"
              >
                <td class="px-6 py-5 font-mono text-sm text-on-surface">{{ tx.txHash }}</td>
                <td class="px-6 py-5">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm" :class="getChainIconColor(tx.chain)">{{ getChainIcon(tx.chain) }}</span>
                    <span class="text-sm font-medium">{{ getChainConfig(tx.chain).name }}</span>
                  </div>
                </td>
                <td class="px-6 py-5 text-right">
                  <div class="font-bold text-on-surface">{{ tx.amount?.toLocaleString() }}</div>
                  <div class="text-[10px] text-on-surface-variant uppercase tracking-tighter">{{ tx.token }}</div>
                </td>
                <td class="px-6 py-5">
                  <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full"
                    :class="getStatusClasses(tx.status)"
                  >
                    <span class="material-symbols-outlined text-[14px]" :style="tx.status === 'confirmed' ? 'font-variation-settings: \'FILL\' 1;' : ''">
                      {{ tx.status === 'confirmed' ? 'check_circle' : tx.status === 'pending' ? 'schedule' : 'error' }}
                    </span>
                    {{ tx.status === 'confirmed' ? 'Verified' : tx.status === 'pending' ? 'Pending' : tx.status }}
                  </span>
                </td>
                <td class="px-6 py-5 text-sm text-on-surface-variant">{{ formatDate(tx.timestamp) }}</td>
                <td class="px-6 py-5 text-right">
                  <button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">more_vert</button>
                </td>
              </tr>
              <tr v-if="!displayTransactions.length">
                <td colspan="6" class="px-6 py-12 text-center text-sm text-on-surface-variant">No transactions yet</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Integrations Hub -->
      <section class="xl:col-span-1">
        <h4 class="text-xl font-headline font-bold text-on-surface mb-6">Integrations Hub</h4>
        <div class="space-y-4">
          <!-- Quick Actions as Integration Cards -->
          <Link
            href="/dashboard/invoices"
            class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/5 flex items-center justify-between group transition-all hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5"
          >
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg cta-gradient text-white flex items-center justify-center font-bold text-xs">
                <span class="material-symbols-outlined text-lg">description</span>
              </div>
              <div>
                <p class="text-sm font-bold text-on-surface">Invoice Engine</p>
                <p class="text-[11px] text-on-surface-variant">Create & manage invoices</p>
              </div>
            </div>
            <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </Link>

          <Link
            href="/dashboard/wallets"
            class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/5 flex items-center justify-between group transition-all hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5"
          >
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg bg-tertiary text-white flex items-center justify-center font-bold text-xs">
                <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
              </div>
              <div>
                <p class="text-sm font-bold text-on-surface">Wallet Vault</p>
                <p class="text-[11px] text-on-surface-variant">{{ stats.activeWallets }} Connected</p>
              </div>
            </div>
            <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </Link>

          <Link
            href="/dashboard/analytics"
            class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/5 flex items-center justify-between group transition-all hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5"
          >
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg bg-secondary text-white flex items-center justify-center font-bold text-xs">
                <span class="material-symbols-outlined text-lg">assessment</span>
              </div>
              <div>
                <p class="text-sm font-bold text-on-surface">Analytics</p>
                <p class="text-[11px] text-on-surface-variant">Reports & insights</p>
              </div>
            </div>
            <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </Link>

          <!-- Add Integration Placeholder -->
          <Link
            href="/developer"
            class="w-full py-4 rounded-xl border-2 border-dashed border-outline-variant/30 text-on-surface-variant text-sm font-medium hover:border-primary/50 hover:text-primary transition-all flex items-center justify-center gap-2"
          >
            <span class="material-symbols-outlined text-lg">add</span>
            Developer Portal
          </Link>
        </div>

        <!-- Verified Seal Promo -->
        <div class="mt-8 p-6 rounded-xl bg-gradient-to-br from-tertiary/10 to-transparent border border-tertiary/20 relative overflow-hidden">
          <div class="relative z-10">
            <span class="material-symbols-outlined text-tertiary mb-2" style="font-variation-settings: 'FILL' 1;">verified_user</span>
            <h5 class="text-sm font-bold text-on-tertiary-container">Immutable Ledgers</h5>
            <p class="text-xs text-on-tertiary-container/80 mt-1">Every transaction documented is cryptographically secured on-chain.</p>
          </div>
          <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-tertiary/5 rounded-full blur-xl"></div>
        </div>
      </section>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useDashboard } from '@/Composables/useDashboard';

const props = defineProps({
  recentPayments: Array,
  stats: Object,
  dailyVolume: Array,
});

const { generateDailyVolume, formatDate, formatCurrency, getChainConfig } = useDashboard();

const chartData = props.dailyVolume || generateDailyVolume(30);
const maxVolume = computed(() => Math.max(...chartData.map(d => d.volume)));
const displayTransactions = computed(() => (props.recentPayments || []).slice(0, 8));

const getChainIcon = (chain) => {
  const icons = {
    ethereum: 'eco',
    polygon: 'polyline',
    bitcoin: 'currency_bitcoin',
    solana: 'bolt',
    arbitrum: 'hub',
    optimism: 'circle',
  };
  return icons[chain] || 'token';
};

const getChainIconColor = (chain) => {
  const colors = {
    ethereum: 'text-blue-600',
    polygon: 'text-indigo-500',
    bitcoin: 'text-orange-500',
    solana: 'text-purple-500',
    arbitrum: 'text-blue-400',
    optimism: 'text-red-500',
  };
  return colors[chain] || 'text-on-surface-variant';
};

const getStatusClasses = (status) => {
  switch (status) {
    case 'confirmed':
      return 'bg-tertiary-container text-on-tertiary-container shadow-[0_0_12px_rgba(48,200,143,0.15)]';
    case 'pending':
      return 'bg-surface-container-highest text-on-secondary-container';
    case 'processing':
      return 'bg-primary-container/15 text-primary';
    case 'expired':
      return 'bg-error-container text-on-error-container';
    default:
      return 'bg-surface-container text-on-surface-variant';
  }
};
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
.glass-card {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
}
</style>
