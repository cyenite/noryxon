<template>
  <DashboardLayout pageTitle="API Playground" breadcrumb="SYSTEM > DEV > PLAYGROUND" dashboardType="developer">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Request Builder -->
      <div class="border border-outline-variant/15 bg-surface rounded-xl overflow-hidden shadow-sm flex flex-col">
        <div class="flex items-center gap-2 px-4 py-3 bg-surface-container-lowest border-b border-outline-variant/15">
          <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
          <div class="w-3 h-3 rounded-full bg-amber-400/80"></div>
          <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
          <span class="text-xs font-semibold text-on-surface-variant ml-2">Request Builder</span>
        </div>

        <div class="p-5 flex-1 flex flex-col gap-5">
          <!-- Endpoint Selector -->
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Endpoint</label>
            <div class="flex gap-2">
              <select v-model="selectedMethod" class="bg-surface border border-outline-variant/15 rounded-lg px-3 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all appearance-none cursor-pointer w-24">
                <option v-for="m in ['GET','POST','PUT','DELETE']" :key="m" :value="m">{{ m }}</option>
              </select>
              <select v-model="selectedEndpoint" class="bg-surface border border-outline-variant/15 rounded-lg px-3 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all appearance-none cursor-pointer flex-1">
                <option v-for="ep in endpoints" :key="ep.path" :value="ep">{{ ep.path }}</option>
              </select>
            </div>
          </div>

          <!-- Headers -->
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Headers</label>
            <div class="bg-surface-container-lowest border border-outline-variant/15 rounded-lg p-3 font-mono text-xs space-y-1 overflow-x-auto">
              <div><span class="text-primary-container">Authorization</span>: <span class="text-amber-500">Bearer sk_test_••••••</span></div>
              <div><span class="text-primary-container">Content-Type</span>: <span class="text-on-surface-variant">application/json</span></div>
            </div>
          </div>

          <!-- Request Body -->
          <div class="flex-1 flex flex-col">
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Request Body</label>
            <textarea 
              v-model="requestBody"
              class="w-full flex-1 min-h-[160px] bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-3 py-2 font-mono text-xs text-on-surface focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all resize-none"
              spellcheck="false"
            ></textarea>
          </div>

          <!-- Execute Button -->
          <button 
            @click="executeRequest"
            :disabled="isExecuting"
            class="w-full py-3 bg-primary text-void font-bold rounded-lg text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all disabled:opacity-50 flex items-center justify-center gap-2"
          >
            <svg v-if="isExecuting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            {{ isExecuting ? 'Executing...' : 'Execute Request' }}
          </button>

          <!-- cURL Preview -->
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">cURL Preview</label>
            <div class="bg-surface-container-lowest border border-outline-variant/15 rounded-lg p-3 font-mono text-[10px] text-on-surface-variant break-all">
              curl -X {{ selectedMethod }} https://api.noryxon.com{{ selectedEndpoint.path }} -H "Authorization: Bearer sk_test_••••" -H "Content-Type: application/json" {{ selectedMethod !== 'GET' ? "-d '" + requestBody.slice(0, 60) + "...'" : '' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Response Viewer -->
      <div class="border border-outline-variant/15 bg-surface rounded-xl overflow-hidden shadow-sm flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 bg-surface-container-lowest border-b border-outline-variant/15">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
            <div class="w-3 h-3 rounded-full bg-amber-400/80"></div>
            <div class="w-3 h-3 rounded-full bg-primary/80"></div>
            <span class="text-xs font-semibold text-on-surface-variant ml-2">Response</span>
          </div>
          <div v-if="response" class="flex items-center gap-3">
            <span class="text-xs font-bold" :class="response.status < 300 ? 'text-primary' : 'text-red-500'">{{ response.status }} {{ response.statusText }}</span>
            <span class="text-xs font-medium text-on-surface-variant">{{ response.time }}ms</span>
          </div>
        </div>

        <div class="p-5 flex-1 flex flex-col">
          <div v-if="!response" class="flex-1 flex items-center justify-center min-h-[400px]">
            <div class="text-center">
              <div class="text-sm font-semibold text-on-surface-variant mb-2">Ready to Execute</div>
              <div class="text-xs text-on-surface-variant/70">Select an endpoint and click Execute to see the response</div>
            </div>
          </div>

          <div v-else class="flex-1 flex flex-col font-mono text-xs">
            <!-- Response Headers -->
            <div class="mb-5 pb-5 border-b border-outline-variant/15">
              <div class="text-xs font-semibold text-on-surface-variant mb-2 font-sans tracking-wide">Response Headers</div>
              <div class="bg-surface-container-lowest border border-outline-variant/15 rounded-lg p-3 space-y-1 text-[11px]">
                <div><span class="text-primary-container">X-Request-Id</span>: <span class="text-on-surface-variant">{{ response.requestId }}</span></div>
                <div><span class="text-primary-container">X-Noryxon-Signature</span>: <span class="text-on-surface-variant">sha256={{ response.signature }}</span></div>
                <div><span class="text-primary-container">Content-Type</span>: <span class="text-on-surface-variant">application/json</span></div>
              </div>
            </div>

            <!-- Response Body -->
            <div class="flex-1 flex flex-col">
              <div class="text-xs font-semibold text-on-surface-variant mb-2 font-sans tracking-wide">Response Body</div>
              <pre class="flex-1 bg-surface-container-lowest border border-outline-variant/15 rounded-lg p-4 overflow-auto text-xs leading-relaxed whitespace-pre-wrap"><span v-html="highlightJSON(response.body)"></span></pre>
            </div>
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

const { generateHash, generateShortHash } = useDashboard();

const endpoints = [
  { path: '/v1/invoices', method: 'POST', desc: 'Create invoice' },
  { path: '/v1/invoices/:id', method: 'GET', desc: 'Get invoice' },
  { path: '/v1/invoices/:id/tax-report', method: 'GET', desc: 'Get tax report' },
  { path: '/v1/webhooks', method: 'GET', desc: 'List webhooks' },
  { path: '/v1/wallets', method: 'GET', desc: 'List tracked wallets' },
];

const selectedMethod = ref('POST');
const selectedEndpoint = ref(endpoints[0]);
const isExecuting = ref(false);
const response = ref(null);

const requestBody = ref(JSON.stringify({
  amount: 99.99,
  currency: "USDC",
  chain: "ethereum",
  payer: "client@example.com",
  purpose: "Digital asset payment",
  webhook_url: "https://your-site.com/webhook",
  metadata: {
    order_id: "ORD-12345",
    tx_hash: "0x..."
  }
}, null, 2));

const executeRequest = async () => {
  isExecuting.value = true;
  response.value = null;

  // Since we don't have a real external API yet, we'll route this to our Sandbox
  // simulation endpoint or just mock a successful response based on the endpoint.
  
  await new Promise(r => setTimeout(r, 800 + Math.random() * 600));
  
  if (selectedEndpoint.value.path === '/v1/invoices' && selectedMethod.value === 'POST') {
    const invoiceId = 'INV-' + generateHash(8).toUpperCase();
    response.value = {
      status: 200,
      statusText: 'OK',
      time: Math.floor(Math.random() * 150) + 30,
      requestId: generateHash(16),
      signature: generateHash(32),
      body: JSON.stringify({
        id: invoiceId,
        object: "invoice",
        status: "generated",
        amount: 99.99,
        currency: "USDC",
        chain: "ethereum",
        payer: "client@example.com",
        purpose: "Digital asset payment",
        tax_report_url: `https://api.noryxon.com/v1/invoices/${invoiceId}/tax-report`,
        pdf_url: `https://api.noryxon.com/v1/invoices/${invoiceId}/pdf`,
        offramp_redirect: "https://yellowcard.io/sell",
        created_at: new Date().toISOString(),
      }, null, 2),
    };
  } else {
    response.value = {
      status: 200,
      statusText: 'OK',
      time: Math.floor(Math.random() * 50) + 10,
      requestId: generateHash(16),
      signature: generateHash(32),
      body: JSON.stringify({
        success: true,
        message: `Mock response for ${selectedMethod.value} ${selectedEndpoint.value.path}`
      }, null, 2)
    };
  }
  
  isExecuting.value = false;
};

const highlightJSON = (json) => {
  return json
    .replace(/"([^"]+)":/g, '<span class="text-primary-container">"$1"</span>:')
    .replace(/: "([^"]+)"/g, ': <span class="text-amber-400">"$1"</span>')
    .replace(/: (\d+\.?\d*)/g, ': <span class="text-primary">$1</span>')
    .replace(/: (true|false|null)/g, ': <span class="text-purple-400">$1</span>');
};
</script>
