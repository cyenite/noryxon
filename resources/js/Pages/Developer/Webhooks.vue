<template>
  <DashboardLayout pageTitle="Webhooks" breadcrumb="SYSTEM > DEV > WEBHOOKS" dashboardType="developer">
    <!-- Webhook Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <div class="lg:col-span-2 border border-ledger-border bg-void rounded-xl p-6 shadow-sm">
        <div class="text-sm font-semibold text-text-primary mb-4 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-pulse"></span>
          Webhook Configuration
        </div>
        <div class="space-y-5">
          <div>
            <label class="block text-xs font-semibold text-text-muted mb-1.5">Endpoint URL</label>
            <input v-model="form.webhook_url" type="url" class="w-full bg-ledger border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all placeholder:text-text-muted/50" />
            <div v-if="form.errors.webhook_url" class="text-red-500 text-xs mt-1">{{ form.errors.webhook_url }}</div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-text-muted mb-1.5">Events to Subscribe</label>
            <div class="flex flex-wrap gap-2">
              <button 
                v-for="event in availableEvents" 
                :key="event"
                @click="toggleEvent(event)"
                class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all"
                :class="subscribedEvents.includes(event) 
                  ? 'border-pulse text-pulse bg-pulse/10 shadow-sm' 
                  : 'border-ledger-border text-text-muted hover:border-pulse/50'"
              >
                {{ event }}
              </button>
            </div>
          </div>
          <button @click="saveWebhook" :disabled="form.processing" class="px-5 py-2.5 bg-pulse rounded-lg text-void font-bold text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            Save Configuration
          </button>
        </div>
      </div>

      <!-- Signing Secret -->
      <div class="border border-ledger-border bg-void rounded-xl p-6 shadow-sm flex flex-col">
        <div class="text-sm font-semibold text-text-primary mb-4 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-node"></span>
          Signing Secret
        </div>
        <div class="bg-ledger border border-ledger-border rounded-lg p-3 font-mono text-xs break-all mb-4">
          <span class="text-node">whsec_</span><span class="text-text-muted">{{ showSecret ? webhookSecret : '••••••••••••••••••••••••••••' }}</span>
        </div>
        <div class="flex gap-3 mb-4 mt-auto">
          <button @click="showSecret = !showSecret" class="flex-1 px-4 py-2 rounded-lg border border-ledger-border text-xs font-semibold text-text-muted hover:border-pulse/50 hover:text-pulse transition-all">
            {{ showSecret ? 'Hide' : 'Reveal' }}
          </button>
          <button @click="copySecret" class="flex-1 px-4 py-2 rounded-lg border border-ledger-border text-xs font-semibold text-text-muted hover:border-node/50 hover:text-node transition-all">
            Copy
          </button>
        </div>
        <div class="text-xs text-text-muted leading-relaxed font-medium">
          Use this secret to verify webhook signatures. Include the <span class="bg-ledger px-1 py-0.5 rounded text-node font-mono">X-Noryxon-Signature</span> header in your verification logic.
        </div>
      </div>
    </div>

    <!-- Delivery Log -->
    <DataTable
      title="Webhook Delivery Log"
      :columns="columns"
      :rows="deliveries"
      :perPage="10"
    >
      <template #cell-event="{ value }">
        <span class="text-text-primary">{{ value }}</span>
      </template>
      <template #cell-success="{ row }">
        <span 
          class="px-2 py-0.5 rounded-md text-[11px] font-semibold tracking-wide"
          :class="row.status_code >= 200 && row.status_code < 300 
            ? 'text-pulse bg-pulse/10' 
            : 'text-red-500 bg-red-500/10'"
        >
          {{ row.status_code }}
        </span>
      </template>
      <template #cell-responseTime="{ value }">
        <span class="text-text-muted">{{ row.response_time_ms ? row.response_time_ms + 'ms' : 'N/A' }}</span>
      </template>
      <template #cell-attempt="{ value }">
        <span class="text-text-muted">{{ value }}/10</span>
      </template>
      <template #cell-timestamp="{ value }">
        <span class="text-text-muted">{{ formatDate(value) }}</span>
      </template>
      <template #cell-actions="{ row }">
        <div class="flex items-center gap-2">
          <button @click="inspectPayload(row)" class="px-3 py-1.5 rounded-lg border border-ledger-border text-text-muted hover:border-node/50 hover:text-node transition-all text-xs font-semibold">
            Inspect
          </button>
          <button class="px-3 py-1.5 rounded-lg border border-ledger-border text-text-muted hover:border-pulse/50 hover:text-pulse transition-all text-xs font-semibold">
            Replay
          </button>
        </div>
      </template>
    </DataTable>

    <!-- Payload Inspector Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-all duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="inspectedDelivery" class="fixed inset-0 z-50 flex items-center justify-center bg-void/80 backdrop-blur-sm" @click.self="inspectedDelivery = null">
          <div class="bg-ledger border border-ledger-border rounded-2xl w-full max-w-2xl mx-4 shadow-2xl max-h-[80vh] flex flex-col overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-ledger-border bg-void/50 shrink-0">
              <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-pulse"></span>
                Payload Inspector
              </div>
              <button @click="inspectedDelivery = null" class="text-text-muted hover:text-text-primary transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 overflow-y-auto space-y-5">
              <div>
                <div class="text-xs font-semibold text-text-muted mb-1.5">Event</div>
                <div class="text-sm font-medium text-text-primary bg-void border border-ledger-border rounded-lg px-3 py-2">{{ inspectedDelivery.event }}</div>
              </div>
              <div>
                <div class="text-xs font-semibold text-text-muted mb-1.5">Response Body</div>
                <pre class="bg-void border border-ledger-border rounded-lg p-4 font-mono text-xs overflow-auto max-h-40 whitespace-pre-wrap text-text-muted">{{ inspectedDelivery.response_body || 'No response body' }}</pre>
              </div>
              <div>
                <div class="text-xs font-semibold text-text-muted mb-1.5">Payload (JSON)</div>
                <pre class="bg-void border border-ledger-border rounded-lg p-4 font-mono text-xs overflow-auto max-h-60 whitespace-pre-wrap text-text-muted">{{ inspectedDelivery.payload }}</pre>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  webhookUrl: String,
  webhookSecret: String,
  deliveries: Array,
});

const { formatDate } = useDashboard();
const { addNotification } = useNotifications();

const form = useForm({
  webhook_url: props.webhookUrl || '',
});

const showSecret = ref(false);
const inspectedDelivery = ref(null);

const availableEvents = ['payment.confirmed', 'payment.pending', 'payment.expired', 'subscription.renewed', 'subscription.cancelled'];
const subscribedEvents = ref(['payment.confirmed', 'payment.pending', 'payment.expired']);

const columns = [
  { key: 'event', label: 'Event' },
  { key: 'status_code', label: 'Status' },
  { key: 'response_time_ms', label: 'Latency' },
  { key: 'attempt', label: 'Attempt' },
  { key: 'created_at', label: 'Time' },
  { key: 'actions', label: '' },
];

const toggleEvent = (event) => {
  const idx = subscribedEvents.value.indexOf(event);
  if (idx > -1) subscribedEvents.value.splice(idx, 1);
  else subscribedEvents.value.push(event);
};

const inspectPayload = (delivery) => {
  inspectedDelivery.value = delivery;
};

const copySecret = () => {
  if (!props.webhookSecret) return;
  navigator.clipboard?.writeText('whsec_' + props.webhookSecret);
  addNotification('Copied', 'Signing secret copied.', 'success', 2000);
};

const saveWebhook = () => {
  form.post(route('developer.webhooks.store'), {
    preserveScroll: true,
    onSuccess: () => {
      addNotification('Saved', 'Webhook configuration updated successfully.', 'success', 3000);
    }
  });
};
</script>
