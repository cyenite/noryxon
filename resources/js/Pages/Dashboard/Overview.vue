<template>
  <DashboardLayout pageTitle="Dashboard" breadcrumb="SYSTEM > OVERVIEW" dashboardType="merchant">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <StatCard 
        label="Total Volume (30d)"
        :value="formatCurrency(stats.totalVolume)"
        :change="stats.totalVolumeChange"
        subtitle="ACROSS_ALL_CHAINS"
      />
      <StatCard 
        label="Active XPUBs"
        :value="stats.activeXpubs"
        subtitle="HARDWARE_WALLETS_LINKED"
      />
      <StatCard 
        label="Pending Payments"
        :value="stats.pendingPayments"
        :change="stats.pendingPaymentsChange"
        subtitle="AWAITING_CONFIRMATION"
      />
      <StatCard 
        label="Settlements (30d)"
        :value="stats.successfulSettlements.toLocaleString()"
        :change="stats.successfulSettlementsChange"
        subtitle="CONFIRMED_ON_CHAIN"
      />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Revenue Mini Chart -->
      <div class="lg:col-span-2 border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-pulse"></span>
            Volume (30 Days)
          </div>
          <div class="text-xs font-medium text-text-muted">
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
              class="w-full bg-pulse/20 hover:bg-pulse/40 transition-all duration-200 cursor-pointer relative"
              :style="{ height: (day.volume / maxVolume * 100) + '%' }"
            >
              <div class="absolute inset-x-0 bottom-0 bg-pulse/60" :style="{ height: Math.min(100, day.volume / avgDailyVolume * 50) + '%' }"></div>
              <!-- Tooltip -->
              <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-ledger border border-ledger-border rounded-lg px-3 py-2 opacity-0 group-hover/bar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-10 shadow-lg">
                <div class="text-xs font-medium text-text-muted mb-0.5">{{ day.date }}</div>
                <div class="text-sm text-pulse font-bold">{{ formatCurrency(day.volume) }}</div>
                <div class="text-[10px] text-text-muted mt-0.5">{{ day.transactions }} txns</div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-between mt-3 text-[11px] font-medium text-text-muted">
          <span>{{ chartData[0]?.date }}</span>
          <span>{{ chartData[chartData.length - 1]?.date }}</span>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
        <div class="text-sm font-semibold text-text-primary mb-6 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-node"></span>
          Quick Actions
        </div>
        <div class="space-y-3">
          <Link 
            href="/dashboard/invoices" 
            class="flex items-center gap-4 p-3 border border-ledger-border rounded-xl hover:border-pulse hover:bg-ledger transition-all group/action"
          >
            <div class="w-10 h-10 rounded-full border border-ledger-border bg-void flex items-center justify-center text-text-muted group-hover/action:border-pulse group-hover/action:text-pulse group-hover/action:bg-pulse/5 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
              <div class="text-sm text-text-primary font-semibold">Create Invoice</div>
              <div class="text-xs text-text-muted mt-0.5">Generate a payment link</div>
            </div>
          </Link>
          <Link 
            href="/dashboard/vault" 
            class="flex items-center gap-4 p-3 border border-ledger-border rounded-xl hover:border-node hover:bg-ledger transition-all group/action"
          >
            <div class="w-10 h-10 rounded-full border border-ledger-border bg-void flex items-center justify-center text-text-muted group-hover/action:border-node group-hover/action:text-node group-hover/action:bg-node/5 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
              <div class="text-sm text-text-primary font-semibold">Add XPUB</div>
              <div class="text-xs text-text-muted mt-0.5">Link a hardware wallet</div>
            </div>
          </Link>
          <Link 
            href="/dashboard/monitor" 
            class="flex items-center gap-4 p-3 border border-ledger-border rounded-xl hover:border-pulse hover:bg-ledger transition-all group/action"
          >
            <div class="w-10 h-10 rounded-full border border-ledger-border bg-void flex items-center justify-center text-text-muted group-hover/action:border-pulse group-hover/action:text-pulse group-hover/action:bg-pulse/5 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
              <div class="text-sm text-text-primary font-semibold">Live Monitor</div>
              <div class="text-xs text-text-muted mt-0.5">Watch payments arrive</div>
            </div>
          </Link>
        </div>
      </div>
    </div>

    <!-- Recent Transactions -->
    <DataTable 
      title="Recent Settlements"
      :columns="txColumns"
      :rows="recentPayments"
      :perPage="8"
    >
      <template #cell-status="{ value }">
        <span 
          class="px-2.5 py-1 rounded-md text-[11px] font-semibold tracking-wide"
          :class="{
            'text-green-500 bg-green-500/10': value === 'confirmed',
            'text-amber-500 bg-amber-500/10': value === 'pending',
            'text-blue-500 bg-blue-500/10': value === 'processing',
            'text-red-500 bg-red-500/10': value === 'expired',
          }"
          style="text-transform: capitalize;"
        >
          {{ value }}
        </span>
      </template>
      <template #cell-amount="{ row }">
        <span class="text-text-primary font-bold">{{ row.amount.toLocaleString() }}</span>
        <span class="text-text-muted ml-1">{{ row.token }}</span>
      </template>
      <template #cell-chain="{ value }">
        <span class="flex items-center gap-1.5">
          <span class="text-sm">{{ getChainConfig(value).icon }}</span>
          <span class="text-text-muted">{{ getChainConfig(value).name }}</span>
        </span>
      </template>
      <template #cell-txHash="{ value }">
        <span class="text-node hover:text-pulse cursor-pointer transition-colors">{{ value }}</span>
      </template>
      <template #cell-timestamp="{ value }">
        <span class="text-text-muted">{{ formatDate(value) }}</span>
      </template>
      <template #cell-confirmations="{ row }">
        <div class="flex items-center gap-2">
          <div class="w-16 h-1.5 bg-ledger-border rounded-full overflow-hidden">
            <div 
              class="h-full transition-all duration-500 rounded-full"
              :class="row.status === 'confirmed' ? 'bg-green-500' : row.status === 'processing' ? 'bg-blue-500' : 'bg-text-muted'"
              :style="{ width: (row.confirmations / row.requiredConfirmations * 100) + '%' }"
            ></div>
          </div>
          <span class="text-text-muted text-xs font-medium">{{ row.confirmations }}/{{ row.requiredConfirmations }}</span>
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

// In a full implementation, dailyVolume would come from the server
// For the demo visual effect, we still generate it locally if not provided
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
