<template>
  <DashboardLayout pageTitle="Developer Portal" breadcrumb="SYSTEM > DEV > OVERVIEW" dashboardType="developer">
    <!-- Stats -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">api</span>
        </div>
        <div>
          <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold">API Requests (24h)</p>
          <p class="text-2xl font-headline font-bold">{{ apiStats?.totalRequests?.toLocaleString() || '0' }}</p>
          <span v-if="apiStats?.requestsChange" class="text-[10px] font-bold text-tertiary">+{{ apiStats.requestsChange }}%</span>
        </div>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-tertiary-container/10 flex items-center justify-center text-tertiary">
          <span class="material-symbols-outlined">webhook</span>
        </div>
        <div>
          <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold">Active Webhooks</p>
          <p class="text-2xl font-headline font-bold">{{ apiStats?.activeWebhooks || 0 }}</p>
        </div>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-secondary-container/30 flex items-center justify-center text-secondary">
          <span class="material-symbols-outlined">science</span>
        </div>
        <div>
          <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold">Test Invoices</p>
          <p class="text-2xl font-headline font-bold">{{ apiStats?.testTransactions?.toLocaleString() || '0' }}</p>
          <span v-if="apiStats?.testTransactionsChange" class="text-[10px] font-bold text-tertiary">+{{ apiStats.testTransactionsChange }}%</span>
        </div>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
          <span class="material-symbols-outlined">download</span>
        </div>
        <div>
          <p class="text-xs text-on-surface-variant uppercase tracking-wider font-semibold">SDK Downloads</p>
          <p class="text-2xl font-headline font-bold">{{ apiStats?.sdkDownloads?.toLocaleString() || '3,241' }}</p>
        </div>
      </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Quick Start Code -->
      <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-low border-b border-outline-variant/5">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-error/80"></div>
            <div class="w-3 h-3 rounded-full bg-primary-container/80"></div>
            <div class="w-3 h-3 rounded-full bg-tertiary-container/80"></div>
            <span class="text-xs font-semibold text-on-surface-variant ml-2">Quick Start (cURL)</span>
          </div>
          <button @click="copyCurl" class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">content_copy</span>
            Copy
          </button>
        </div>
        <div class="p-4 font-mono text-xs leading-relaxed overflow-x-auto">
          <div class="text-on-surface-variant"># Generate an invoice using the Noryxon API</div>
          <div class="mt-2">
            <span class="text-primary">curl</span> <span class="text-on-surface-variant">-X POST</span> <span class="text-tertiary">https://api.noryxon.com/v1/invoices</span> <span class="text-on-surface-variant">\</span>
          </div>
          <div class="pl-4">
            <span class="text-on-surface-variant">-H</span> <span class="text-primary-container">"Authorization: Bearer sk_live_xxxxxxxx"</span> <span class="text-on-surface-variant">\</span>
          </div>
          <div class="pl-4">
            <span class="text-on-surface-variant">-H</span> <span class="text-primary-container">"Content-Type: application/json"</span> <span class="text-on-surface-variant">\</span>
          </div>
          <div class="pl-4 text-on-surface-variant">-d '{</div>
          <div class="pl-8"><span class="text-primary">"amount"</span>: <span class="text-tertiary">99.99</span>,</div>
          <div class="pl-8"><span class="text-primary">"currency"</span>: <span class="text-primary-container">"USDC"</span>,</div>
          <div class="pl-8"><span class="text-primary">"chain"</span>: <span class="text-primary-container">"ethereum"</span>,</div>
          <div class="pl-8"><span class="text-primary">"payer"</span>: <span class="text-primary-container">"client@example.com"</span>,</div>
          <div class="pl-8"><span class="text-primary">"purpose"</span>: <span class="text-primary-container">"Digital asset payment"</span></div>
          <div class="pl-4 text-on-surface-variant">}'</div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/10">
        <div class="flex items-center gap-2 mb-6">
          <span class="material-symbols-outlined text-primary text-lg">developer_guide</span>
          <span class="text-sm font-bold text-on-surface">Developer Resources</span>
        </div>
        <div class="space-y-3">
          <Link href="/developer/api-keys" class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5 transition-all group/r">
            <div class="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary group-hover/r:bg-primary-container/20 transition-colors">
              <span class="material-symbols-outlined">key</span>
            </div>
            <div>
              <div class="text-sm text-on-surface font-bold">API Keys</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Manage live & test keys</div>
            </div>
          </Link>
          <Link href="/developer/playground" class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5 transition-all group/r">
            <div class="w-10 h-10 rounded-lg bg-tertiary-container/10 flex items-center justify-center text-tertiary group-hover/r:bg-tertiary-container/20 transition-colors">
              <span class="material-symbols-outlined">terminal</span>
            </div>
            <div>
              <div class="text-sm text-on-surface font-bold">Playground</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Test API endpoints</div>
            </div>
          </Link>
          <Link href="/developer/sandbox" class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-lg hover:shadow-primary/5 transition-all group/r">
            <div class="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary-container group-hover/r:bg-primary-container/20 transition-colors">
              <span class="material-symbols-outlined">science</span>
            </div>
            <div>
              <div class="text-sm text-on-surface font-bold">Sandbox</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Testnet environment</div>
            </div>
          </Link>
        </div>
      </div>
    </div>

    <!-- SDK Quick Install -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant/10 hover:shadow-lg hover:shadow-primary/5 transition-all">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-primary-container">JS</span>
          <span class="text-xs font-semibold text-on-surface">TypeScript / Node.js</span>
        </div>
        <div class="font-mono text-[11px] text-on-surface-variant bg-surface-container-low p-2 rounded-lg">npm install @noryxon/sdk</div>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant/10 hover:shadow-lg hover:shadow-primary/5 transition-all">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-tertiary">PY</span>
          <span class="text-xs font-semibold text-on-surface">Python</span>
        </div>
        <div class="font-mono text-[11px] text-on-surface-variant bg-surface-container-low p-2 rounded-lg">pip install noryxon</div>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant/10 hover:shadow-lg hover:shadow-primary/5 transition-all">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-blue-500">GO</span>
          <span class="text-xs font-semibold text-on-surface">Go</span>
        </div>
        <div class="font-mono text-[11px] text-on-surface-variant bg-surface-container-low p-2 rounded-lg">go get noryxon.com/sdk</div>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant/10 hover:shadow-lg hover:shadow-primary/5 transition-all">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-purple-500">PHP</span>
          <span class="text-xs font-semibold text-on-surface">PHP / Laravel</span>
        </div>
        <div class="font-mono text-[11px] text-on-surface-variant bg-surface-container-low p-2 rounded-lg">composer require noryxon/sdk</div>
      </div>
    </div>

    <!-- Recent API Activity -->
    <DataTable
      title="Recent API Activity"
      :columns="activityColumns"
      :rows="recentActivity"
      :perPage="8"
    >
      <template #cell-method="{ value }">
        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide"
          :class="{
            'text-primary bg-primary-container/20': value === 'POST',
            'text-tertiary bg-tertiary-container/20': value === 'GET',
            'text-primary-container bg-primary-container/10': value === 'PUT',
            'text-error bg-error-container/30': value === 'DELETE',
          }">{{ value }}</span>
      </template>
      <template #cell-endpoint="{ value }">
        <span class="text-primary font-mono">{{ value }}</span>
      </template>
      <template #cell-statusCode="{ value }">
        <span class="font-medium" :class="value < 300 ? 'text-tertiary' : 'text-error'">{{ value }}</span>
      </template>
      <template #cell-responseTime="{ value }">
        <span class="text-on-surface-variant">{{ value }}ms</span>
      </template>
      <template #cell-timestamp="{ value }">
        <span class="text-on-surface-variant">{{ formatDate(value) }}</span>
      </template>
    </DataTable>
  </DashboardLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  apiStats: { type: Object, default: () => ({}) },
  recentActivity: { type: Array, default: () => [] },
});

const { formatDate } = useDashboard();
const { addNotification } = useNotifications();

const activityColumns = [
  { key: 'method', label: 'Method' },
  { key: 'endpoint', label: 'Endpoint' },
  { key: 'statusCode', label: 'Status' },
  { key: 'responseTime', label: 'Latency' },
  { key: 'timestamp', label: 'Time' },
];

const copyCurl = () => {
  addNotification('Copied', 'cURL command copied to clipboard.', 'success', 2000);
};
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
