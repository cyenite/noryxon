<template>
  <DashboardLayout pageTitle="Live Monitor" breadcrumb="SYSTEM > REALTIME > TERMINAL" dashboardType="merchant">
    <!-- Controls Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-pulse animate-pulse shadow-[0_0_8px] shadow-pulse"></span>
          <span class="text-sm font-semibold text-text-primary">Live Feed Active</span>
        </div>
        <span class="text-xs font-medium text-text-muted">{{ events.length }} events captured</span>
      </div>
      <div class="flex items-center gap-3">
        <!-- Chain Filter -->
        <select 
          v-model="chainFilter"
          class="bg-void border border-ledger-border rounded-lg px-3 py-1.5 text-sm font-medium text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all cursor-pointer"
        >
          <option value="all">All Chains</option>
          <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.symbol }}</option>
        </select>
        <!-- Sound Toggle -->
        <button 
          @click="soundEnabled = !soundEnabled"
          class="px-3 py-1.5 rounded-lg border text-sm font-medium transition-colors flex items-center gap-1.5"
          :class="soundEnabled ? 'border-pulse text-pulse bg-pulse/10' : 'border-ledger-border text-text-muted hover:border-pulse hover:text-text-primary'"
        >
          <span v-if="soundEnabled">🔊</span>
          <span v-else>🔇</span>
          {{ soundEnabled ? 'Sound On' : 'Sound Off' }}
        </button>
        <!-- Clear -->
        <button 
          @click="events = []"
          class="px-3 py-1.5 rounded-lg border border-ledger-border text-sm font-medium text-text-muted hover:border-red-500 hover:text-red-500 transition-colors"
        >
          Clear
        </button>
      </div>
    </div>

    <!-- Terminal Feed -->
    <div class="border border-ledger-border rounded-xl bg-void overflow-hidden shadow-sm">
      <!-- Terminal Header -->
      <div class="flex items-center gap-2 px-4 py-3 bg-ledger border-b border-ledger-border">
        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
        <div class="w-3 h-3 rounded-full bg-amber-400/80"></div>
        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
        <span class="font-mono text-xs text-text-muted font-medium ml-2">noryxon-live-monitor ~ %</span>
      </div>

      <!-- Scrollable Feed -->
      <div class="h-[65vh] overflow-y-auto p-4 space-y-0.5 font-mono text-xs" ref="feedContainer">
        <!-- Boot Messages -->
        <div class="text-text-muted opacity-60 mb-4">
          <div>> NORYXON_INDEXER v2.1.0 — CONNECTING TO NODE CLUSTER...</div>
          <div>> WEBSOCKET_HANDSHAKE: <span class="text-pulse">ESTABLISHED</span></div>
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
            class="flex items-center gap-3 py-2 px-3 hover:bg-ledger/50 transition-colors group/evt cursor-pointer rounded-md mx-1 my-0.5"
            :class="{
              'border-l-2 border-green-500 bg-green-500/5': event.status === 'confirmed',
              'border-l-2 border-amber-500 bg-amber-500/5': event.status === 'pending',
              'border-l-2 border-blue-500 bg-blue-500/5': event.status === 'processing',
              'border-l-2 border-red-500 bg-red-500/5': event.status === 'expired',
            }"
          >
            <span class="text-text-muted text-[11px] w-28 shrink-0">{{ formatTime(event.timestamp) }}</span>
            <span class="text-sm shrink-0">{{ getChainConfig(event.chain).icon }}</span>
            <span class="font-medium text-text-muted w-14 shrink-0 text-[11px]">{{ getChainConfig(event.chain).symbol }}</span>
            <span 
              class="px-2 py-0.5 text-[10px] w-20 text-center shrink-0 rounded-md font-semibold tracking-wide capitalize"
              :class="{
                'text-green-500 bg-green-500/10': event.status === 'confirmed',
                'text-amber-500 bg-amber-500/10': event.status === 'pending',
                'text-blue-500 bg-blue-500/10': event.status === 'processing',
                'text-red-500 bg-red-500/10': event.status === 'expired',
              }"
            >
              {{ event.status }}
            </span>
            <span class="text-text-primary font-bold text-sm">{{ Number(event.amount).toFixed(2) }} <span class="text-text-muted font-medium text-xs">{{ event.currency }}</span></span>
            <span class="text-node opacity-0 group-hover/evt:opacity-100 transition-opacity ml-auto text-[10px]">{{ event.tx_hash || event.address }}</span>
            <!-- Confirmations -->
            <div class="flex items-center gap-2 ml-auto shrink-0">
              <div class="w-12 h-1.5 bg-ledger-border rounded-full overflow-hidden">
                <div 
                  class="h-full transition-all rounded-full"
                  :class="event.status === 'confirmed' || event.status === 'paid' ? 'bg-green-500' : event.status === 'processing' ? 'bg-blue-500 animate-pulse' : 'bg-text-muted'"
                  :style="{ width: ((event.confirmations || 0) / (event.required_confirmations || 3) * 100) + '%' }"
                ></div>
              </div>
              <span class="text-[10px] font-medium text-text-muted w-8">{{ event.confirmations || 0 }}/{{ event.required_confirmations || 3 }}</span>
            </div>
          </div>
        </TransitionGroup>

        <!-- Cursor blinking -->
        <div class="flex items-center gap-1 mt-2">
          <span class="text-pulse">></span>
          <span class="w-2 h-4 bg-pulse animate-[blink_1s_step-start_infinite]"></span>
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
      // Find new payments that aren't in our events list yet
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

// Simulate WebSockets by polling for now
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
