<template>
  <DashboardLayout pageTitle="Invoices" breadcrumb="SYSTEM > COMMERCE > INVOICES" dashboardType="merchant">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div class="text-sm font-medium text-text-muted">Generate payment links and crypto invoices for your customers.</div>
      <button @click="showModal = true" class="flex items-center gap-2 bg-pulse text-void font-bold px-4 py-2 rounded-lg text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Invoice
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <StatCard label="Total Invoiced" :value="formatCurrency(totalInvoiced)" subtitle="All Time" />
      <StatCard label="Paid" :value="paidCount" subtitle="Settled on Chain" />
      <StatCard label="Pending" :value="pendingCount" subtitle="Awaiting Payment" />
      <StatCard label="Expired" :value="expiredCount" subtitle="Past Deadline" />
    </div>

    <DataTable title="Invoice Registry" :columns="columns" :rows="initialInvoices" :perPage="10">
      <template #cell-invoiceNumber="{ value }">
        <span class="text-text-primary font-bold">{{ value }}</span>
      </template>
      <template #cell-amount="{ row }">
        <span class="text-text-primary font-bold">{{ row.amount.toLocaleString() }}</span>
        <span class="text-text-muted ml-1">{{ row.currency }}</span>
      </template>
      <template #cell-status="{ value }">
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold tracking-wide capitalize" :class="{
          'text-green-500 bg-green-500/10': value === 'paid',
          'text-amber-500 bg-amber-500/10': value === 'pending',
          'text-red-500 bg-red-500/10': value === 'expired',
          'text-text-muted bg-text-muted/10': value === 'draft',
        }">{{ value }}</span>
      </template>
      <template #cell-memo="{ value }">
        <span class="text-text-muted">{{ value }}</span>
      </template>
      <template #cell-paymentLink="{ value }">
        <button @click="copyLink(value)" class="text-node hover:text-pulse transition-colors">{{ value }}</button>
      </template>
      <template #cell-customerEmail="{ value }">
        <span class="text-text-muted">{{ value }}</span>
      </template>
      <template #cell-createdAt="{ value }">
        <span class="text-text-muted">{{ formatDate(value) }}</span>
      </template>
    </DataTable>

    <!-- Create Invoice Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-all duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-void/80 backdrop-blur-sm" @click.self="showModal = false">
          <div class="bg-ledger border border-ledger-border w-full max-w-lg mx-4 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-ledger-border bg-void/50">
              <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-pulse"></span> Create New Invoice
              </div>
              <button @click="showModal = false" class="text-text-muted hover:text-text-primary transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <div class="p-6 space-y-5">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-text-muted mb-1.5">Amount</label>
                  <input v-model="form.amount" type="number" placeholder="0.00" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.amount" class="text-red-500 text-xs mt-1">{{ form.errors.amount }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-muted mb-1.5">Currency</label>
                  <select v-model="form.currency" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none appearance-none cursor-pointer transition-all">
                    <option v-for="t in ['USDC','USDT','BTC','ETH','SOL']" :key="t" :value="t">{{ t }}</option>
                  </select>
                  <div v-if="form.errors.currency" class="text-red-500 text-xs mt-1">{{ form.errors.currency }}</div>
                </div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Customer Email</label>
                <input v-model="form.customer_email" type="email" placeholder="customer@example.com" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                <div v-if="form.errors.customer_email" class="text-red-500 text-xs mt-1">{{ form.errors.customer_email }}</div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Description</label>
                <input v-model="form.memo" type="text" placeholder="What is this payment for?" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                <div v-if="form.errors.memo" class="text-red-500 text-xs mt-1">{{ form.errors.memo }}</div>
              </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-ledger-border bg-void/50">
              <button @click="showModal = false" class="px-5 py-2.5 rounded-lg border border-ledger-border text-text-muted text-sm font-semibold hover:text-text-primary hover:bg-ledger transition-colors">Cancel</button>
              <button @click="createInvoice" :disabled="!form.amount || form.processing" class="px-5 py-2.5 rounded-lg bg-pulse text-void font-bold text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Generate Link</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  initialInvoices: Array,
});

const { formatDate, formatCurrency } = useDashboard();
const { addNotification } = useNotifications();

const showModal = ref(false);
const form = useForm({ amount: '', currency: 'USDC', customer_email: '', memo: '' });

const totalInvoiced = computed(() => props.initialInvoices.reduce((s, i) => s + i.amount, 0));
const paidCount = computed(() => props.initialInvoices.filter(i => i.status === 'paid').length);
const pendingCount = computed(() => props.initialInvoices.filter(i => i.status === 'pending').length);
const expiredCount = computed(() => props.initialInvoices.filter(i => i.status === 'expired').length);

const columns = [
  { key: 'invoiceNumber', label: 'Invoice' },
  { key: 'amount', label: 'Amount' },
  { key: 'status', label: 'Status' },
  { key: 'memo', label: 'Description' },
  { key: 'paymentLink', label: 'Payment Link' },
  { key: 'customerEmail', label: 'Customer' },
  { key: 'createdAt', label: 'Created' },
];

const copyLink = (link) => {
  navigator.clipboard?.writeText('https://' + link);
  addNotification('Link Copied', 'Payment link copied to clipboard.', 'success', 2000);
};

const createInvoice = () => {
  if (!form.amount) return;
  form.post(route('dashboard.invoices.store'), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
      addNotification('Invoice Created', 'Payment link generated and ready to share.', 'success', 4000);
    }
  });
};
</script>
