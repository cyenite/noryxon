<template>
  <DashboardLayout pageTitle="Analytics" breadcrumb="SYSTEM > INTELLIGENCE > REPORTS" dashboardType="merchant">
    <!-- Time Period Selector -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center bg-surface-container-lowest p-1 rounded-lg">
        <button
          v-for="period in periods"
          :key="period.value"
          @click="selectedPeriod = period.value"
          class="px-4 py-1.5 rounded-md text-xs font-semibold transition-all"
          :class="selectedPeriod === period.value 
            ? 'bg-surface text-on-surface shadow-sm' 
            : 'text-on-surface-variant hover:text-on-surface'"
        >
          {{ period.label }}
        </button>
      </div>
      <div class="flex items-center gap-2">
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg border border-outline-variant/15 text-on-surface-variant text-sm font-semibold hover:border-primary hover:text-primary hover:bg-surface-container-low transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Export CSV
        </button>
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg border border-primary/50 text-primary text-sm font-semibold hover:bg-primary hover:text-void transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Generate Tax Report
        </button>
      </div>
    </div>

    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <StatCard label="Documented Volume" :value="formatCurrency(totalVolume)" :change="12.4" subtitle="ALL_CHAINS_COMBINED" />
      <StatCard label="Invoices Generated" :value="totalTxns.toLocaleString()" :change="8.1" subtitle="TAX_DOCUMENTED" />
      <StatCard label="Avg. Verification" value="4.2 min" subtitle="ACROSS_12_NETWORKS" />
      <StatCard label="Top Asset" value="USDC (38%)" subtitle="BY_DOCUMENTED_VOLUME" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Volume Chart -->
      <div class="lg:col-span-2 border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div class="text-sm font-semibold text-on-surface flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            Documented Volume
          </div>
        </div>
        <div class="flex items-end gap-1 h-48">
          <div 
            v-for="(day, idx) in dailyVolume"
            :key="idx"
            class="flex-1 group/bar relative"
          >
            <div 
              class="w-full transition-all duration-200 cursor-pointer relative overflow-hidden"
              :style="{ height: (day.volume / maxVolume * 100) + '%' }"
              :class="idx === dailyVolume.length - 1 ? 'bg-primary/40' : 'bg-primary/15 hover:bg-primary/30'"
            >
              <div class="absolute inset-x-0 bottom-0 bg-primary/40" :style="{ height: '40%' }"></div>
            </div>
            <div class="absolute -top-14 left-1/2 -translate-x-1/2 bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-3 py-2 opacity-0 group-hover/bar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-10 shadow-lg">
              <div class="text-xs font-medium text-on-surface-variant mb-0.5">{{ day.date }}</div>
              <div class="text-sm text-primary font-bold">{{ formatCurrency(day.volume) }}</div>
            </div>
          </div>
        </div>
        <div class="flex justify-between mt-3 text-[11px] font-medium text-on-surface-variant">
          <span>{{ dailyVolume[0]?.date }}</span>
          <span>{{ dailyVolume[dailyVolume.length - 1]?.date }}</span>
        </div>
      </div>

      <!-- Asset Breakdown -->
      <div class="border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
        <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-node"></span>
          Asset Distribution
        </div>
        
        <!-- Donut approximation with stacked bars -->
        <div class="space-y-4 mb-6">
          <div v-for="asset in assetBreakdown" :key="asset.token" class="group/asset">
            <div class="flex items-center justify-between mb-1.5">
              <span class="text-sm text-on-surface font-semibold">{{ asset.token }}</span>
              <span class="text-xs font-medium text-on-surface-variant">{{ asset.percentage }}%</span>
            </div>
            <div class="w-full h-2 bg-surface-container-lowest-border rounded-full overflow-hidden">
              <div 
                class="h-full rounded-full transition-all duration-700 ease-out"
                :class="assetColors[asset.token] || 'bg-text-muted'"
                :style="{ width: asset.percentage + '%' }"
              ></div>
            </div>
            <div class="text-[11px] font-medium text-on-surface-variant mt-1">{{ formatCurrency(asset.volume) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chain Performance Table -->
    <div class="border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
      <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-primary"></span>
        Chain Performance
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div 
          v-for="chain in chainBreakdown" 
          :key="chain.chain.id"
          class="border border-outline-variant/15 rounded-xl p-5 hover:border-primary/50 hover:shadow-lg hover:bg-primary/5 transition-all group/chain"
        >
          <div class="flex items-center gap-2 mb-3">
            <span class="text-xl">{{ chain.chain.icon }}</span>
            <span class="text-sm text-on-surface font-bold">{{ chain.chain.symbol }}</span>
          </div>
          <div class="text-2xl text-on-surface font-bold">{{ chain.percentage }}%</div>
          <div class="text-xs font-medium text-on-surface-variant mt-1">{{ formatCurrency(chain.volume) }}</div>
          <div class="w-full h-1.5 rounded-full bg-surface-container-lowest-border mt-4 overflow-hidden">
            <div class="h-full rounded-full bg-primary/60 group-hover/chain:bg-primary transition-colors" :style="{ width: chain.percentage + '%' }"></div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import { useDashboard } from '@/Composables/useDashboard';

const { generateDailyVolume, generateChainBreakdown, generateAssetBreakdown, formatCurrency } = useDashboard();

const selectedPeriod = ref('30d');
const periods = [
  { label: '7D', value: '7d' },
  { label: '30D', value: '30d' },
  { label: '90D', value: '90d' },
];

const dailyVolume = generateDailyVolume(30);
const chainBreakdown = generateChainBreakdown();
const assetBreakdown = generateAssetBreakdown();

const maxVolume = computed(() => Math.max(...dailyVolume.map(d => d.volume)));
const totalVolume = computed(() => dailyVolume.reduce((sum, d) => sum + d.volume, 0));
const totalTxns = computed(() => dailyVolume.reduce((sum, d) => sum + d.transactions, 0));

const assetColors = {
  'USDC': 'bg-blue-500 shadow-blue-500',
  'USDT': 'bg-emerald-500 shadow-emerald-500',
  'ETH': 'bg-indigo-400 shadow-indigo-400',
  'BTC': 'bg-amber-500 shadow-amber-500',
  'SOL': 'bg-purple-500 shadow-purple-500',
  'Other': 'bg-text-muted shadow-text-muted',
};
</script>
