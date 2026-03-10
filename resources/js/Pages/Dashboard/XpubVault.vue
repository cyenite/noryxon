<template>
  <DashboardLayout pageTitle="XPUB Vault" breadcrumb="SYSTEM > SECURITY > VAULT" dashboardType="merchant">
    <!-- Vault Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <div class="text-sm font-medium text-text-muted mb-1">Registered hardware wallet public keys. Noryxon never stores private keys.</div>
      </div>
      <button 
        @click="showAddModal = true"
        class="flex items-center gap-2 bg-pulse text-void font-semibold px-5 py-2.5 rounded-lg text-sm hover:shadow-lg hover:shadow-pulse/20 transition-all hover:bg-[#E5C130]"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add XPUB
      </button>
    </div>

    <!-- Stats Mini Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <StatCard label="Total XPUBs" :value="xpubs.length" subtitle="WALLETS_LINKED" />
      <StatCard label="Total Received" :value="formatCurrency(totalReceived)" subtitle="ACROSS_ALL_CHAINS" />
      <StatCard label="Addresses Generated" :value="totalAddresses.toLocaleString()" subtitle="DERIVED_ON_CHAIN" />
    </div>

    <!-- XPUB Table -->
    <DataTable 
      title="Extended Public Key Registry"
      :columns="columns"
      :rows="initialXpubs"
      :perPage="10"
    >
      <template #cell-chain="{ row }">
        <span class="flex items-center gap-2">
          <span class="text-sm">{{ getChainConfig(row.chain).icon }}</span>
          <span class="text-text-primary font-bold">{{ getChainConfig(row.chain).symbol }}</span>
        </span>
      </template>
      <template #cell-keyTruncated="{ value }">
        <span class="text-pulse font-mono text-xs cursor-pointer hover:underline transition-colors" @click="copyKey(value)">{{ value }}</span>
      </template>
      <template #cell-label="{ value }">
        <span class="text-text-primary font-medium">{{ value }}</span>
      </template>
      <template #cell-derivationPath="{ value }">
        <span class="text-text-muted font-mono font-medium text-xs">{{ value }}</span>
      </template>
      <template #cell-status="{ value }">
        <span 
          class="px-2.5 py-1 rounded-md text-[11px] font-semibold tracking-wide"
          :class="{
            'text-green-500 bg-green-500/10': value === 'synced',
            'text-blue-500 bg-blue-500/10': value === 'watching',
            'text-text-muted bg-text-muted/10': value === 'paused',
          }"
          style="text-transform: capitalize;"
        >
          {{ value }}
        </span>
      </template>
      <template #cell-totalReceived="{ value }">
        <span class="text-text-primary font-bold">{{ formatCurrency(value) }}</span>
      </template>
      <template #cell-addressesGenerated="{ value }">
        <span class="text-text-muted font-medium">{{ value.toLocaleString() }}</span>
      </template>
      <template #cell-actions="{ row }">
        <div class="flex items-center gap-2">
          <button @click="updateStatus(row)" class="px-3 py-1.5 rounded-md border border-ledger-border text-text-muted hover:border-blue-500 hover:text-blue-500 transition-colors text-xs font-medium">
            {{ row.status === 'paused' ? 'Resume' : 'Pause' }}
          </button>
          <button @click="deleteXpub(row)" class="px-3 py-1.5 rounded-md border border-ledger-border text-text-muted hover:border-red-500 hover:text-red-500 transition-colors text-xs font-medium">
            Delete
          </button>
        </div>
      </template>
    </DataTable>

    <!-- Add XPUB Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-void/80 backdrop-blur-sm" @click.self="showAddModal = false">
          <div class="bg-ledger border border-ledger-border rounded-xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-ledger-border bg-void">
              <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-pulse rounded-full"></span>
                Add Extended Public Key
              </div>
              <button @click="showAddModal = false" class="p-1 rounded-md text-text-muted hover:text-text-primary hover:bg-ledger-light transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <!-- Modal Body -->
            <div class="p-6 space-y-5 bg-void/50">
              <div>
                <label class="block text-xs font-semibold text-text-primary mb-2">Label</label>
                <input 
                  v-model="form.label"
                  type="text" 
                  placeholder="e.g. Main Business Wallet"
                  class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all"
                />
                <div v-if="form.errors.label" class="text-red-500 text-xs mt-1">{{ form.errors.label }}</div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-text-primary mb-2">Blockchain</label>
                <select 
                  v-model="form.chain_id"
                  class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all cursor-pointer"
                >
                  <option value="" disabled>Select chain...</option>
                  <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                </select>
                <div v-if="form.errors.chain_id" class="text-red-500 text-xs mt-1">{{ form.errors.chain_id }}</div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-text-primary mb-2">Extended Public Key (XPUB)</label>
                <textarea 
                  v-model="form.xpub_key"
                  rows="3"
                  placeholder="xpub6CUGRUonZSQ4TWtTMmzXdrXDtypWKi..."
                  class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 font-mono text-xs text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all resize-none"
                ></textarea>
                <div v-if="form.errors.xpub_key" class="text-red-500 text-xs mt-1">{{ form.errors.xpub_key }}</div>
                <div class="flex items-center gap-2 mt-2">
                  <div class="w-2 h-2 rounded-full" :class="xpubValid ? 'bg-green-500' : 'bg-text-muted'"></div>
                  <span class="text-[11px] font-medium" :class="xpubValid ? 'text-green-500' : 'text-text-muted'">
                    {{ form.xpub_key.length > 0 ? (xpubValid ? 'Valid Checksum format' : 'Invalid Format') : 'Awaiting input...' }}
                  </span>
                </div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-text-primary mb-2">Derivation Path</label>
                <input 
                  v-model="form.derivation_path"
                  type="text" 
                  class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all"
                />
                <div v-if="form.errors.derivation_path" class="text-red-500 text-xs mt-1">{{ form.errors.derivation_path }}</div>
              </div>
            </div>
            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-ledger-border bg-void">
              <button 
                @click="showAddModal = false"
                class="px-5 py-2.5 rounded-lg border border-ledger-border text-text-muted text-sm font-semibold hover:bg-ledger transition-colors"
              >
                Cancel
              </button>
              <button 
                @click="addXpub"
                :disabled="!xpubValid || form.processing"
                class="px-5 py-2.5 rounded-lg bg-pulse text-void text-sm font-bold hover:shadow-lg hover:shadow-pulse/20 hover:bg-[#E5C130] transition-all disabled:opacity-30 disabled:cursor-not-allowed"
              >
                Register Key
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  initialXpubs: Array,
});

const { formatCurrency, chains, getChainConfig } = useDashboard();
const { addNotification } = useNotifications();

const showAddModal = ref(false);

const form = useForm({
  label: '',
  chain_id: '',
  xpub_key: '',
  derivation_path: "m/84'/0'/0'/0/0"
});

const totalReceived = computed(() => props.initialXpubs.reduce((sum, x) => sum + x.totalReceived, 0));
const totalAddresses = computed(() => props.initialXpubs.reduce((sum, x) => sum + x.addressesGenerated, 0));

const xpubValid = computed(() => {
  return form.xpub_key.length > 20 && (
    form.xpub_key.startsWith('xpub') || 
    form.xpub_key.startsWith('tpub') || 
    form.xpub_key.startsWith('ypub') || 
    form.xpub_key.startsWith('zpub')
  );
});

const columns = [
  { key: 'chain', label: 'Chain' },
  { key: 'label', label: 'Label' },
  { key: 'keyTruncated', label: 'Public Key' },
  { key: 'derivationPath', label: 'Path' },
  { key: 'status', label: 'Status' },
  { key: 'totalReceived', label: 'Received', align: 'right' },
  { key: 'addressesGenerated', label: 'Addrs', align: 'right' },
  { key: 'actions', label: '' },
];

const copyKey = (key) => {
  navigator.clipboard?.writeText(key);
  addNotification('Copied', 'Public key fragment copied to clipboard.', 'success', 2000);
};

const addXpub = () => {
  if (!xpubValid.value) return;
  form.post(route('dashboard.xpub-vault.store'), {
    onSuccess: () => {
      showAddModal.value = false;
      form.reset();
      addNotification('XPUB Registered', 'Extended public key has been added to your vault. Monitoring will begin shortly.', 'success', 4000);
    }
  });
};

const updateStatus = (xpub) => {
  const newStatus = xpub.status === 'paused' ? 'watching' : 'paused';
  router.patch(route('dashboard.xpub-vault.update', xpub.id), { status: newStatus }, {
    preserveScroll: true,
    onSuccess: () => {
      addNotification('Status Updated', `XPUB is now ${newStatus}.`, 'success', 3000);
    }
  });
};

const deleteXpub = (xpub) => {
  if (confirm('Are you sure you want to delete this XPUB? All associated un-confirmed payments may be lost.')) {
    router.delete(route('dashboard.xpub-vault.destroy', xpub.id), {
      preserveScroll: true,
      onSuccess: () => {
        addNotification('XPUB Deleted', 'Extended public key has been removed from your vault.', 'success', 3000);
      }
    });
  }
};
</script>
