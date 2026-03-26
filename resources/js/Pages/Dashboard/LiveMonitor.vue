<template>
  <DashboardLayout pageTitle="Live Monitor" breadcrumb="SYSTEM > REALTIME > TERMINAL" dashboardType="merchant">
    <!-- Controls Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 bg-tertiary-container/20 px-3 py-1.5 rounded-full">
          <span class="w-2.5 h-2.5 rounded-full bg-tertiary-container animate-pulse"></span>
          <span class="text-sm font-bold text-on-tertiary-container">Live Feed Active</span>
        </div>
        <span class="text-xs font-medium text-on-surface-variant">{{ events.length }} events captured</span>
      </div>
      <div class="flex items-center gap-3">
        <!-- Chain Filter -->
        <select
          v-model="chainFilter"
          class="bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-3 py-1.5 text-sm font-medium text-on-surface focus:border-primary focus:ring-1 focus:ring-primary/30 focus:outline-none transition-all cursor-pointer"
        >
          <option value="all">All Chains</option>
          <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.symbol }}</option>
        </select>
        <!-- Sound Toggle -->
        <button
          @click="soundEnabled = !soundEnabled"
          class="px-3 py-1.5 rounded-lg border text-sm font-medium transition-colors flex items-center gap-1.5"
          :class="soundEnabled ? 'border-primary text-primary bg-primary-container/20' : 'border-outline-variant/10 text-on-surface-variant hover:border-primary hover:text-on-surface'"
        >
          <span class="material-symbols-outlined text-lg">{{ soundEnabled ? 'volume_up' : 'volume_off' }}</span>
          {{ soundEnabled ? 'Sound On' : 'Sound Off' }}
        </button>
        <!-- Clear -->
        <button
          @click="events = []"
          class="px-3 py-1.5 rounded-lg border border-outline-variant/10 text-sm font-medium text-on-surface-variant hover:border-error hover:text-error transition-colors flex items-center gap-1.5"
        >
          <span class="material-symbols-outlined text-lg">delete_sweep</span>
          Clear
        </button>
      </div>
    </div>

    <!-- Terminal Feed -->
    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
      <!-- Terminal Header -->
      <div class="flex items-center gap-2 px-4 py-3 bg-surface-container-low border-b border-outline-variant/5">
        <div class="w-3 h-3 rounded-full bg-error/80"></div>
        <div class="w-3 h-3 rounded-full bg-primary-container/80"></div>
        <div class="w-3 h-3 rounded-full bg-tertiary-container/80"></div>
        <span class="font-mono text-xs text-on-surface-variant font-medium ml-2">noryxon-live-monitor ~ %</span>
        <div class="ml-auto flex items-center gap-2">
          <span class="material-symbols-outlined text-on-surface-variant/40 text-sm">terminal</span>
        </div>
      </div>

      <!-- Scrollable Feed -->
      <div class="h-[65vh] overflow-y-auto p-4 space-y-0.5 font-mono text-xs" ref="feedContainer">
        <!-- Boot Messages -->
        <div class="text-on-surface-variant/60 mb-4">
          <div>> NORYXON_INDEXER v2.1.0 — CONNECTING TO NODE CLUSTER...</div>
          <div>> WEBSOCKET_HANDSHAKE: <span class="text-primary font-bold">ESTABLISHED</span></div>
          <div>> MONITORING {{ chainFilter === 'all' ? '12' : '1' }} CHAIN(S) — MEMPOOL + CONFIRMED — LIVE</div>
          <div>> ─────────────────────────────────────────────────────────</div>
        </div>

        <!-- Events -->
        <TransitionGroup
          enter-active-class="transition-all duration-300"
          enter-from-class="opacity-0 -translate-x-4"
          enter-to-class="opacity-100 translate-x-0"
        >
          <div
            v-for="event in filteredEvents"
            :key="event.id"
            class="flex items-center gap-3 py-2 px-3 hover:bg-surface-container-low/50 transition-colors group/evt cursor-pointer rounded-lg mx-1 my-0.5"
            :class="{
              'border-l-2 border-tertiary-container bg-tertiary-container/5': event.status === 'confirmed',
              'border-l-2 border-primary-container bg-primary-container/5': event.status === 'pending',
              'border-l-2 border-secondary bg-secondary/5': event.status === 'processing',
              'border-l-2 border-error bg-error/5': event.status === 'expired',
            }"
          >
            <span class="text-on-surface-variant text-[11px] w-28 shrink-0">{{ formatTime(event.timestamp) }}</span>
            <span class="material-symbols-outlined text-sm" :class="getChainColor(event.chain)">{{ getChainIcon(event.chain) }}</span>
            <span class="font-medium text-on-surface-variant w-14 shrink-0 text-[11px]">{{ getChainConfig(event.chain).symbol }}</span>
            <span
              class="px-2 py-0.5 text-[10px] w-20 text-center shrink-0 rounded-full font-bold tracking-wide capitalize"
              :class="{
                'text-on-tertiary-container bg-tertiary-container': event.status === 'confirmed',
                'text-on-surface-variant bg-surface-container-highest': event.status === 'pending',
                'text-secondary bg-secondary/10': event.status === 'processing',
                'text-on-error-container bg-error-container': event.status === 'expired',
              }"
            >
              {{ event.status === 'confirmed' ? 'verified' : event.status }}
            </span>
            <span class="text-on-surface font-bold text-sm">{{ Number(event.amount).toFixed(2) }} <span class="text-on-surface-variant font-medium text-xs">{{ event.currency }}</span></span>
            <span class="text-primary opacity-0 group-hover/evt:opacity-100 transition-opacity ml-auto text-[10px]">{{ event.tx_hash || event.address }}</span>
            <!-- Confirmations -->
            <div class="flex items-center gap-2 ml-auto shrink-0">
              <div class="w-12 h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                <div
                  class="h-full transition-all rounded-full"
                  :class="event.status === 'confirmed' || event.status === 'paid' ? 'bg-tertiary-container' : event.status === 'processing' ? 'bg-secondary animate-pulse' : 'bg-on-surface-variant/20'"
                  :style="{ width: ((event.confirmations || 0) / (event.required_confirmations || 3) * 100) + '%' }"
                ></div>
              </div>
              <span class="text-[10px] font-medium text-on-surface-variant w-8">{{ event.confirmations || 0 }}/{{ event.required_confirmations || 3 }}</span>
            </div>
          </div>
        </TransitionGroup>

        <!-- Cursor blinking -->
        <div class="flex items-center gap-1 mt-2">
          <span class="text-primary font-bold">></span>
          <span class="w-2 h-4 bg-primary animate-[blink_1s_step-start_infinite]"></span>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useDashboard } from '@/Composables/useDashboard';

const props = defineProps({
  initialPayments: Array,
});

const { chains, getChainConfig } = useDashboard();

const events = ref([...props.initialPayments].reverse());
const chainFilter = ref('all');
const soundEnabled = ref(false);
const feedContainer = ref(null);
let interval = null;

const filteredEvents = computed(() => {
  if (chainFilter.value === 'all') return events.value;
  return events.value.filter(e => e.chain === chainFilter.value);
});

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

const getChainColor = (chain) => {
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

const formatTime = (iso) => {
  const d = new Date(iso);
  return d.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' }) + '.' + d.getMilliseconds().toString().padStart(3, '0');
};

const fetchNetworkEvents = () => {
  router.reload({
    only: ['initialPayments'],
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      const currentIds = new Set(events.value.map(e => e.id));
      const newPayments = page.props.initialPayments.filter(p => !currentIds.has(p.id)).reverse();

      if (newPayments.length > 0) {
        events.value.push(...newPayments);
        if (events.value.length > 200) events.value = events.value.slice(events.value.length - 200);

        scrollToBottom();
      }
    }
  });
};

const scrollToBottom = () => {
  nextTick(() => {
    if (feedContainer.value) {
      feedContainer.value.scrollTop = feedContainer.value.scrollHeight;
    }
  });
};

onMounted(() => {
  scrollToBottom();
  interval = setInterval(fetchNetworkEvents, 5000);
});

onUnmounted(() => {
  clearInterval(interval);
});
</script>

<style scoped>
@keyframes blink {
  50% { opacity: 0; }
}
</style>
