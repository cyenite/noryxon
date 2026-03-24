<template>
  <DashboardLayout pageTitle="Dashboard" breadcrumb="SYSTEM > OVERVIEW" dashboardType="merchant">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <StatCard
        label="Documented Volume (30d)"
        :value="formatCurrency(stats.totalVolume)"
        :change="stats.totalVolumeChange"
        subtitle="ACROSS_ALL_CHAINS"
      />
      <StatCard
        label="Connected Wallets"
        :value="stats.activeWallets"
        subtitle="WALLETS_LINKED"
      />
      <StatCard
        label="Pending Invoices"
        :value="stats.pendingPayments"
        :change="stats.pendingPaymentsChange"
        subtitle="AWAITING_VERIFICATION"
      />
      <StatCard
        label="Invoices Generated (30d)"
        :value="stats.successfulSettlements.toLocaleString()"
        :change="stats.successfulSettlementsChange"
        subtitle="TAX_DOCUMENTED"
      />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Revenue Mini Chart -->
      <div class="lg:col-span-2 border border-outline-variant/15 bg-surface-container-lowest p-6 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div class="text-sm font-bold text-on-surface flex items-center gap-2 font-headline">
            <span class="w-2 h-2 rounded-full bg-primary-container"></span>
            Volume (30 Days)
          </div>
          <div class="text-xs font-medium text-on-surface-variant">
            Average: {{ formatCurrency(avgDailyVolume) }}/day
          </div>
        </div>
        <!-- CSS Bar Chart -->
        <div class="flex items-end gap-1 h-40">
          <div
            v-for="(day, idx) in chartData"
            :key="idx"
            class="flex-1 group/bar relative"
          >
            <div
              class="w-full rounded-t-sm bg-primary/10 hover:bg-primary/20 transition-all duration-200 cursor-pointer relative"
              :style="{ height: (day.volume / maxVolume * 100) + '%' }"
            >
              <div class="absolute inset-x-0 bottom-0 rounded-t-sm bg-gradient-to-t from-primary/50 to-primary-container/30" :style="{ height: Math.min(100, day.volume / avgDailyVolume * 50) + '%' }"></div>
              <!-- Tooltip -->
              <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-surface-container-lowest border border-outline-variant/20 rounded-xl px-3 py-2 opacity-0 group-hover/bar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-10 shadow-lg">
                <div class="text-xs font-medium text-on-surface-variant mb-0.5">{{ day.date }}</div>
                <div class="text-sm text-primary font-bold">{{ formatCurrency(day.volume) }}</div>
                <div class="text-[10px] text-on-surface-variant mt-0.5">{{ day.transactions }} txns</div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-between mt-3 text-[11px] font-medium text-on-surface-variant">
          <span>{{ chartData[0]?.date }}</span>
          <span>{{ chartData[chartData.length - 1]?.date }}</span>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="border border-outline-variant/15 bg-surface-container-lowest p-6 rounded-2xl shadow-sm">
        <div class="text-sm font-bold text-on-surface mb-6 flex items-center gap-2 font-headline">
          <span class="w-2 h-2 rounded-full bg-tertiary-container"></span>
          Quick Actions
        </div>
        <div class="space-y-3">
          <Link
            href="/dashboard/invoices"
            class="flex items-center gap-4 p-3 border border-outline-variant/15 rounded-xl hover:border-primary/30 hover:bg-primary/3 transition-all group/action"
          >
            <div class="w-10 h-10 rounded-xl border border-outline-variant/20 bg-surface flex items-center justify-center text-on-surface-variant group-hover/action:border-primary/30 group-hover/action:text-primary group-hover/action:bg-primary/5 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
              <div class="text-sm text-on-surface font-bold">Create Invoice</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Generate tax-compliant documentation</div>
            </div>
          </Link>
          <Link
            href="/dashboard/wallets"
            class="flex items-center gap-4 p-3 border border-outline-variant/15 rounded-xl hover:border-tertiary/30 hover:bg-tertiary/3 transition-all group/action"
          >
            <div class="w-10 h-10 rounded-xl border border-outline-variant/20 bg-surface flex items-center justify-center text-on-surface-variant group-hover/action:border-tertiary/30 group-hover/action:text-tertiary group-hover/action:bg-tertiary/5 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 110-6h5.25A2.25 2.25 0 0121 6v6zm0 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6m-3 6h.008v.008H18V12z"/></svg>
            </div>
            <div>
              <div class="text-sm text-on-surface font-bold">Connect Wallet</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Link a wallet or exchange for tracking</div>
            </div>
          </Link>
          <Link
            href="/dashboard/monitor"
            class="flex items-center gap-4 p-3 border border-outline-variant/15 rounded-xl hover:border-primary/30 hover:bg-primary/3 transition-all group/action"
          >
            <div class="w-10 h-10 rounded-xl border border-outline-variant/20 bg-surface flex items-center justify-center text-on-surface-variant group-hover/action:border-primary/30 group-hover/action:text-primary group-hover/action:bg-primary/5 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
              <div class="text-sm text-on-surface font-bold">Live Monitor</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Watch transactions to document</div>
            </div>
          </Link>
        </div>
      </div>
    </div>

    <!-- Recent Transactions -->
    <DataTable
      title="Recent Documented Transactions"
      :columns="txColumns"
      :rows="recentPayments"
      :perPage="8"
    >
      <template #cell-status="{ value }">
        <span
          class="px-2.5 py-1 rounded-lg text-[11px] font-bold tracking-wide"
          :class="{
            'text-tertiary bg-tertiary-container/15': value === 'confirmed',
            'text-primary bg-primary-container/15': value === 'pending',
            'text-blue-600 bg-blue-500/10': value === 'processing',
            'text-error bg-error/10': value === 'expired',
          }"
          style="text-transform: capitalize;"
        >
          {{ value }}
        </span>
      </template>
      <template #cell-amount="{ row }">
        <span class="text-on-surface font-bold">{{ row.amount.toLocaleString() }}</span>
        <span class="text-on-surface-variant ml-1">{{ row.token }}</span>
      </template>
      <template #cell-chain="{ value }">
        <span class="flex items-center gap-1.5">
          <span class="text-sm">{{ getChainConfig(value).icon }}</span>
          <span class="text-on-surface-variant">{{ getChainConfig(value).name }}</span>
        </span>
      </template>
      <template #cell-txHash="{ value }">
        <span class="text-primary hover:text-primary/70 cursor-pointer transition-colors font-mono text-xs">{{ value }}</span>
      </template>
      <template #cell-timestamp="{ value }">
        <span class="text-on-surface-variant">{{ formatDate(value) }}</span>
      </template>
      <template #cell-confirmations="{ row }">
        <div class="flex items-center gap-2">
          <div class="w-16 h-1.5 bg-outline-variant/20 rounded-full overflow-hidden">
            <div
              class="h-full transition-all duration-500 rounded-full"
              :class="row.status === 'confirmed' ? 'bg-tertiary-container' : row.status === 'processing' ? 'bg-blue-500' : 'bg-on-surface-variant/30'"
              :style="{ width: (row.confirmations / row.requiredConfirmations * 100) + '%' }"
            ></div>
          </div>
          <span class="text-on-surface-variant text-xs font-medium">{{ row.confirmations }}/{{ row.requiredConfirmations }}</span>
        </div>
      </template>
    </DataTable>
  </DashboardLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';

const props = defineProps({
  recentPayments: Array,
  stats: Object,
  dailyVolume: Array,
});

const { generateDailyVolume, formatDate, formatCurrency, getChainConfig } = useDashboard();

const chartData = props.dailyVolume || generateDailyVolume(30);

const maxVolume = computed(() => Math.max(...chartData.map(d => d.volume)));
const avgDailyVolume = computed(() => chartData.reduce((sum, d) => sum + d.volume, 0) / chartData.length);

const txColumns = [
  { key: 'txHash', label: 'TX Hash' },
  { key: 'amount', label: 'Amount' },
  { key: 'chain', label: 'Chain' },
  { key: 'status', label: 'Status' },
  { key: 'confirmations', label: 'Confirms' },
  { key: 'timestamp', label: 'Time' },
];
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
