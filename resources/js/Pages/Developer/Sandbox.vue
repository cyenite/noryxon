<template>
  <DashboardLayout pageTitle="Sandbox" breadcrumb="SYSTEM > DEV > TESTNET" dashboardType="developer">
    <!-- Testnet Banner -->
    <div class="border border-dashed border-amber-500/30 bg-amber-500/10 rounded-xl p-5 mb-8 flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-500">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
          <div class="text-sm font-bold text-amber-500">Testnet Sandbox Active</div>
          <div class="text-xs font-medium text-on-surface-variant mt-0.5">All operations here use testnet data. No real transactions are documented.</div>
        </div>
      </div>
      <div class="flex items-center gap-2 bg-surface px-3 py-1.5 rounded-full border border-amber-500/20">
        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
        <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wider">Sandboxed</span>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Payment Simulator -->
      <div class="lg:col-span-2 border border-outline-variant/15 bg-surface rounded-xl p-6 shadow-sm">
        <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          Invoice Simulator
        </div>
        
        <div class="grid grid-cols-2 gap-5 mb-6">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Amount</label>
            <input v-model="simAmount" type="number" placeholder="100.00" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none transition-all" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Token</label>
            <select v-model="simToken" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none transition-all appearance-none cursor-pointer">
              <option v-for="t in ['USDC','USDT','ETH','BTC','SOL']" :key="t" :value="t">{{ t }} (Testnet)</option>
            </select>
          </div>
        </div>
        
        <button 
          @click="simulatePayment"
          :disabled="simulating"
          class="w-full py-3 rounded-lg bg-amber-500/10 border-2 border-dashed border-amber-500/50 text-amber-500 font-bold text-sm hover:bg-amber-500 hover:text-void hover:border-amber-500 hover:shadow-[0_0_15px] hover:shadow-amber-500/40 transition-all disabled:opacity-50 flex items-center justify-center gap-2 mb-6"
        >
          <svg v-if="simulating" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
          {{ simulating ? simulationStatus : 'Simulate Invoice Generation' }}
        </button>

        <!-- Simulation Log -->
        <div v-if="simLog.length > 0" class="border border-outline-variant/15 rounded-lg bg-surface-container-lowest p-4 font-mono text-xs space-y-1.5 max-h-48 overflow-y-auto">
          <div v-for="(log, idx) in simLog" :key="idx" :class="log.color">
            <span class="text-on-surface-variant mr-2">{{ log.time }}</span>{{ log.message }}
          </div>
        </div>
      </div>

      <!-- Testnet Faucets -->
      <div class="space-y-6">
        <div class="border border-outline-variant/15 bg-surface rounded-xl p-6 shadow-sm">
          <div class="text-sm font-semibold text-on-surface mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-node"></span>
            Testnet Faucets
          </div>
          <div class="space-y-3">
            <a v-for="faucet in faucets" :key="faucet.chain" :href="faucet.url" target="_blank" class="flex items-center gap-3 p-3 border border-outline-variant/15 rounded-xl hover:border-node/50 hover:bg-node/5 hover:shadow-lg transition-all group/f">
              <span class="w-8 h-8 rounded-full border border-outline-variant/15 bg-surface-container-lowest flex items-center justify-center text-sm font-bold group-hover/f:border-node group-hover/f:text-primary-container transition-colors">{{ faucet.icon }}</span>
              <div>
                <div class="text-sm font-bold text-on-surface group-hover/f:text-primary-container transition-colors">{{ faucet.chain }}</div>
                <div class="text-xs font-medium text-on-surface-variant mt-0.5">Get test {{ faucet.symbol }}</div>
              </div>
              <svg class="w-4 h-4 text-on-surface-variant ml-auto group-hover/f:text-primary-container transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
        </div>

        <!-- Test XPUB -->
        <div class="border border-outline-variant/15 bg-surface rounded-xl p-6 shadow-sm">
          <div class="text-sm font-semibold text-on-surface mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            Test XPUB
          </div>
          <div class="bg-surface-container-lowest border border-outline-variant/15 rounded-lg p-3 font-mono text-xs text-amber-500 break-all mb-3 text-center">
            tpub6CUGRonZSQ4TWtT...{{ generateHash(8) }}
          </div>
          <div class="text-xs font-medium text-on-surface-variant text-center leading-relaxed">
            Use testnet XPUBs (tpub/upub) in sandbox mode. No real funds.
          </div>
        </div>
      </div>
    </div>

    <!-- Test Webhook Deliveries -->
    <DataTable
      title="Test Webhook Deliveries"
      :columns="webhookColumns"
      :rows="testWebhooks"
      :perPage="8"
    >
      <template #cell-event="{ value }">
        <span class="text-on-surface">{{ value }}</span>
      </template>
      <template #cell-success="{ row }">
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold tracking-wide" :class="row.status_code >= 200 && row.status_code < 300 ? 'text-primary bg-primary/10' : 'text-red-500 bg-red-500/10'">{{ row.status_code }}</span>
      </template>
      <template #cell-responseTime="{ row }">
        <span class="text-on-surface-variant">{{ row.response_time_ms ? row.response_time_ms + 'ms' : 'N/A' }}</span>
      </template>
      <template #cell-timestamp="{ value }">
        <span class="text-on-surface-variant">{{ formatDate(value) }}</span>
      </template>
    </DataTable>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  testWebhooks: Array,
});

const { generateHash, formatDate } = useDashboard();
const { addNotification } = useNotifications();

const simAmount = ref(100);
const simToken = ref('USDC');
const simulating = ref(false);
const simulationStatus = ref('');
const simLog = ref([]);

const faucets = [
  { chain: 'Ethereum Sepolia', symbol: 'ETH', icon: 'Ξ', url: 'https://sepoliafaucet.com' },
  { chain: 'Bitcoin Testnet', symbol: 'tBTC', icon: '₿', url: 'https://testnet-faucet.com/btc-testnet' },
  { chain: 'Solana Devnet', symbol: 'SOL', icon: '◎', url: 'https://faucet.solana.com' },
  { chain: 'Polygon Mumbai', symbol: 'MATIC', icon: '⬡', url: 'https://faucet.polygon.technology' },
];

const webhookColumns = [
  { key: 'event', label: 'Event' },
  { key: 'status_code', label: 'Status' },
  { key: 'response_time_ms', label: 'Latency' },
  { key: 'created_at', label: 'Time' },
];

const simulatePayment = async () => {
  simulating.value = true;
  simLog.value = [];
  const now = () => new Date().toLocaleTimeString('en-US', { hour12: false });
  const hash = generateHash(32);
  
  const steps = [
    { msg: `> POST /v1/invoices {"amount": ${simAmount.value}, "currency": "${simToken.value}"}`, color: 'text-primary-container', delay: 300 },
    { msg: `> Monitoring wallet for transaction...`, color: 'text-on-surface', delay: 500 },
    { msg: `> Scanning mempool on testnet...`, color: 'text-on-surface-variant', delay: 1200 },
    { msg: `> TX DETECTED: ${hash}`, color: 'text-amber-400', delay: 800 },
    { msg: `> Confirmation 1/3 — Block #${Math.floor(Math.random() * 1000000 + 19000000)}`, color: 'text-on-surface-variant', delay: 600 },
    { msg: `> Confirmation 2/3 — Block #${Math.floor(Math.random() * 1000000 + 19000001)}`, color: 'text-on-surface-variant', delay: 600 },
    { msg: `> Confirmation 3/3 — CONFIRMED`, color: 'text-primary', delay: 400 },
    { msg: `> Webhook dispatched → invoice.generated`, color: 'text-primary-container', delay: 300 },
    { msg: `> Invoice created: ${simAmount.value} ${simToken.value} documented with tax report`, color: 'text-primary font-bold', delay: 200 },
  ];

  for (const step of steps) {
    simulationStatus.value = step.msg.slice(2, 40) + '...';
    await new Promise(r => setTimeout(r, step.delay));
    simLog.value.push({ time: now(), message: step.msg, color: step.color });
  }

  // Actually hit the backend to record the simulated payment
  router.post(route('developer.sandbox.simulate'), {
    amount: simAmount.value,
    currency: simToken.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      simulating.value = false;
      addNotification('Test Invoice Generated', `${simAmount.value} ${simToken.value} invoice documented in sandbox.`, 'success', 5000);
    },
    onError: () => {
      simulating.value = false;
      simLog.value.push({ time: now(), message: '> ERROR: Simulation failed on backend', color: 'text-red-500' });
    }
  });
};
</script>
