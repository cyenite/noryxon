<template>
  <DashboardLayout pageTitle="Developer Portal" breadcrumb="SYSTEM > DEV > OVERVIEW" dashboardType="developer">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <StatCard label="API Requests (24h)" :value="apiStats?.totalRequests?.toLocaleString() || '0'" :change="apiStats?.requestsChange || 0" subtitle="CALLS_TO_INVOICE_API" />
      <StatCard label="Active Webhooks" :value="apiStats?.activeWebhooks || 0" subtitle="ENDPOINTS_REGISTERED" />
      <StatCard label="Test Invoices" :value="apiStats?.testTransactions?.toLocaleString() || '0'" :change="apiStats?.testTransactionsChange || 0" subtitle="SANDBOX_INVOICES" />
      <StatCard label="SDK Downloads" :value="apiStats?.sdkDownloads?.toLocaleString() || '3,241'" subtitle="NPM_+_PYPI_+_GO" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Quick Start Code -->
      <div class="lg:col-span-2 border border-ledger-border bg-void rounded-xl overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-4 py-3 bg-ledger border-b border-ledger-border">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
            <div class="w-3 h-3 rounded-full bg-amber-400/80"></div>
            <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
            <span class="text-xs font-semibold text-text-muted ml-2">Quick Start (cURL)</span>
          </div>
          <button @click="copyCurl" class="text-xs font-semibold text-text-muted hover:text-pulse transition-colors">Copy</button>
        </div>
        <div class="p-4 font-mono text-xs leading-relaxed overflow-x-auto">
          <div class="text-text-muted"># Generate an invoice using the Noryxon API</div>
          <div class="mt-2">
            <span class="text-node">curl</span> <span class="text-text-muted">-X POST</span> <span class="text-pulse">https://api.noryxon.com/v1/invoices</span> <span class="text-text-muted">\</span>
          </div>
          <div class="pl-4">
            <span class="text-text-muted">-H</span> <span class="text-amber-400">"Authorization: Bearer sk_live_xxxxxxxx"</span> <span class="text-text-muted">\</span>
          </div>
          <div class="pl-4">
            <span class="text-text-muted">-H</span> <span class="text-amber-400">"Content-Type: application/json"</span> <span class="text-text-muted">\</span>
          </div>
          <div class="pl-4 text-text-muted">-d '{</div>
          <div class="pl-8"><span class="text-node">"amount"</span>: <span class="text-pulse">99.99</span>,</div>
          <div class="pl-8"><span class="text-node">"currency"</span>: <span class="text-amber-400">"USDC"</span>,</div>
          <div class="pl-8"><span class="text-node">"chain"</span>: <span class="text-amber-400">"ethereum"</span>,</div>
          <div class="pl-8"><span class="text-node">"payer"</span>: <span class="text-amber-400">"client@example.com"</span>,</div>
          <div class="pl-8"><span class="text-node">"purpose"</span>: <span class="text-amber-400">"Digital asset payment"</span></div>
          <div class="pl-4 text-text-muted">}'</div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
        <div class="text-sm font-semibold text-text-primary mb-6 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-node"></span>
          Developer Resources
        </div>
        <div class="space-y-3">
          <Link href="/developer/api-keys" class="flex items-center gap-3 p-3 border border-ledger-border rounded-xl hover:border-pulse/50 hover:bg-pulse/5 hover:shadow-lg transition-all group/r">
            <div class="w-10 h-10 border border-ledger-border rounded-full flex items-center justify-center text-text-muted group-hover/r:border-pulse group-hover/r:text-pulse transition-colors bg-ledger">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <div>
              <div class="text-sm text-text-primary font-bold">API Keys</div>
              <div class="text-xs font-medium text-text-muted mt-0.5">Manage live & test keys</div>
            </div>
          </Link>
          <Link href="/developer/playground" class="flex items-center gap-3 p-3 border border-ledger-border rounded-xl hover:border-node/50 hover:bg-node/5 hover:shadow-lg transition-all group/r">
            <div class="w-10 h-10 border border-ledger-border rounded-full flex items-center justify-center text-text-muted group-hover/r:border-node group-hover/r:text-node transition-colors bg-ledger">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <div class="text-sm text-text-primary font-bold">Playground</div>
              <div class="text-xs font-medium text-text-muted mt-0.5">Test API endpoints</div>
            </div>
          </Link>
          <Link href="/developer/sandbox" class="flex items-center gap-3 p-3 border border-ledger-border rounded-xl hover:border-amber-400/50 hover:bg-amber-400/5 hover:shadow-lg transition-all group/r">
            <div class="w-10 h-10 border border-ledger-border rounded-full flex items-center justify-center text-text-muted group-hover/r:border-amber-400 group-hover/r:text-amber-400 transition-colors bg-ledger">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div>
              <div class="text-sm text-text-primary font-bold">Sandbox</div>
              <div class="text-xs font-medium text-text-muted mt-0.5">Testnet environment</div>
            </div>
          </Link>
        </div>
      </div>
    </div>

    <!-- SDK Quick Install -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="border border-ledger-border bg-void p-4 rounded-xl shadow-sm hover:border-pulse/50 transition-colors">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-amber-400">JS</span>
          <span class="text-xs font-semibold text-text-primary">TypeScript / Node.js</span>
        </div>
        <div class="font-mono text-[11px] text-text-muted bg-ledger p-2 rounded">npm install @noryxon/sdk</div>
      </div>
      <div class="border border-ledger-border bg-void p-4 rounded-xl shadow-sm hover:border-node/50 transition-colors">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-node">PY</span>
          <span class="text-xs font-semibold text-text-primary">Python</span>
        </div>
        <div class="font-mono text-[11px] text-text-muted bg-ledger p-2 rounded">pip install noryxon</div>
      </div>
      <div class="border border-ledger-border bg-void p-4 rounded-xl shadow-sm hover:border-blue-400/50 transition-colors">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-blue-400">GO</span>
          <span class="text-xs font-semibold text-text-primary">Go</span>
        </div>
        <div class="font-mono text-[11px] text-text-muted bg-ledger p-2 rounded">go get noryxon.com/sdk</div>
      </div>
      <div class="border border-ledger-border bg-void p-4 rounded-xl shadow-sm hover:border-purple-400/50 transition-colors">
        <div class="flex items-center gap-2 mb-3">
          <span class="text-xs font-bold text-purple-400">PHP</span>
          <span class="text-xs font-semibold text-text-primary">PHP / Laravel</span>
        </div>
        <div class="font-mono text-[11px] text-text-muted bg-ledger p-2 rounded">composer require noryxon/sdk</div>
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
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold tracking-wide"
          :class="{
            'text-pulse bg-pulse/10': value === 'POST',
            'text-node bg-node/10': value === 'GET',
            'text-amber-500 bg-amber-500/10': value === 'PUT',
            'text-red-500 bg-red-500/10': value === 'DELETE',
          }">{{ value }}</span>
      </template>
      <template #cell-endpoint="{ value }">
        <span class="text-node">{{ value }}</span>
      </template>
      <template #cell-statusCode="{ value }">
        <span class="font-medium" :class="value < 300 ? 'text-pulse' : 'text-red-500'">{{ value }}</span>
      </template>
      <template #cell-responseTime="{ value }">
        <span class="text-text-muted">{{ value }}ms</span>
      </template>
      <template #cell-timestamp="{ value }">
        <span class="text-text-muted">{{ formatDate(value) }}</span>
      </template>
    </DataTable>
  </DashboardLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import DataTable from '@/Components/Dashboard/DataTable.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  apiStats: Object,
  recentActivity: Array,
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
