<template>
  <DashboardLayout pageTitle="Analytics" breadcrumb="SYSTEM > INTELLIGENCE > REPORTS" dashboardType="merchant">
    <!-- Time Period Selector -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center bg-surface-container-low p-1 rounded-lg">
        <button
          v-for="period in periods"
          :key="period.value"
          @click="selectedPeriod = period.value"
          class="px-4 py-1.5 rounded-md text-xs font-semibold transition-all"
          :class="selectedPeriod === period.value
            ? 'bg-surface-container-lowest text-primary shadow-sm'
            : 'text-on-surface-variant hover:text-on-surface'"
        >
          {{ period.label }}
        </button>
      </div>
      <div class="flex items-center gap-2">
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg border border-outline-variant/10 text-on-surface-variant text-sm font-semibold hover:border-primary hover:text-primary hover:bg-surface-container-low transition-colors">
          <span class="material-symbols-outlined text-lg">download</span>
          Export CSV
        </button>
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg cta-gradient text-white text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.02] transition-all">
          <span class="material-symbols-outlined text-lg">description</span>
          Generate Tax Report
        </button>
      </div>
    </div>

    <!-- Top Stats Bento -->
    <section class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">monitoring</span>
        </div>
        <div>
          <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Documented Volume</p>
          <p class="text-2xl font-headline font-bold">{{ formatCurrency(totalVolume) }}</p>
          <span class="text-[10px] font-bold text-tertiary">+12.4%</span>
        </div>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-tertiary-container/10 flex items-center justify-center text-tertiary">
          <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span>
        </div>
        <div>
          <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Invoices Generated</p>
          <p class="text-2xl font-headline font-bold">{{ totalTxns.toLocaleString() }}</p>
          <span class="text-[10px] font-bold text-tertiary">+8.1%</span>
        </div>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-secondary-container/30 flex items-center justify-center text-secondary">
          <span class="material-symbols-outlined">speed</span>
        </div>
        <div>
          <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Avg. Verification</p>
          <p class="text-2xl font-headline font-bold">4.2 min</p>
        </div>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">token</span>
        </div>
        <div>
          <p class="text-xs font-label text-on-surface-variant uppercase tracking-wider">Top Asset</p>
          <p class="text-2xl font-headline font-bold">USDC</p>
          <span class="text-[10px] font-bold text-on-surface-variant">38% of volume</span>
        </div>
      </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Volume Chart -->
      <div class="lg:col-span-2 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div class="text-sm font-semibold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-lg">bar_chart</span>
            Documented Volume
          </div>
        </div>
        <div class="flex items-end gap-1.5 h-48">
          <div
            v-for="(day, idx) in dailyVolume"
            :key="idx"
            class="flex-1 group/bar relative"
          >
            <div
              class="w-full rounded-t-lg transition-all duration-200 cursor-pointer"
              :style="{ height: (day.volume / maxVolume * 100) + '%' }"
              :class="idx === dailyVolume.length - 1 ? 'bg-primary-container' : 'bg-primary-fixed/30 hover:bg-primary-container'"
            ></div>
            <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-surface-container-lowest border border-outline-variant/10 rounded-xl px-3 py-2 opacity-0 group-hover/bar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-10 shadow-lg">
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
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
        <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary text-lg">pie_chart</span>
          Asset Distribution
        </div>

        <div class="space-y-4 mb-6">
          <div v-for="asset in assetBreakdown" :key="asset.token" class="group/asset">
            <div class="flex items-center justify-between mb-1.5">
              <span class="text-sm text-on-surface font-semibold">{{ asset.token }}</span>
              <span class="text-xs font-medium text-on-surface-variant">{{ asset.percentage }}%</span>
            </div>
            <div class="w-full h-2 bg-surface-container-low rounded-full overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-700 ease-out"
                :class="assetColors[asset.token] || 'bg-on-surface-variant/30'"
                :style="{ width: asset.percentage + '%' }"
              ></div>
            </div>
            <div class="text-[11px] font-medium text-on-surface-variant mt-1">{{ formatCurrency(asset.volume) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chain Performance Table -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
      <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-lg">hub</span>
        Chain Performance
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div
          v-for="chain in chainBreakdown"
          :key="chain.chain.id"
          class="bg-surface-container-low rounded-xl p-5 hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5 transition-all group/chain border border-outline-variant/5"
        >
          <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-lg" :class="chainIconColors[chain.chain.id] || 'text-on-surface-variant'">{{ chainIcons[chain.chain.id] || 'token' }}</span>
            <span class="text-sm text-on-surface font-bold">{{ chain.chain.symbol }}</span>
          </div>
          <div class="text-2xl text-on-surface font-headline font-bold">{{ chain.percentage }}%</div>
          <div class="text-xs font-medium text-on-surface-variant mt-1">{{ formatCurrency(chain.volume) }}</div>
          <div class="w-full h-1.5 rounded-full bg-surface-container-high mt-4 overflow-hidden">
            <div class="h-full rounded-full bg-primary-fixed/50 group-hover/chain:bg-primary-container transition-colors" :style="{ width: chain.percentage + '%' }"></div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
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
  'USDC': 'bg-blue-500',
  'USDT': 'bg-emerald-500',
  'ETH': 'bg-indigo-400',
  'BTC': 'bg-amber-500',
  'SOL': 'bg-purple-500',
  'Other': 'bg-on-surface-variant/30',
};

const chainIcons = {
  ethereum: 'eco',
  polygon: 'polyline',
  bitcoin: 'currency_bitcoin',
  solana: 'bolt',
  arbitrum: 'hub',
  optimism: 'circle',
};

const chainIconColors = {
  ethereum: 'text-blue-600',
  polygon: 'text-indigo-500',
  bitcoin: 'text-orange-500',
  solana: 'text-purple-500',
  arbitrum: 'text-blue-400',
  optimism: 'text-red-500',
};
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
