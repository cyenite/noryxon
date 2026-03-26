<template>
  <Head title="Documentation - Noryxon" />
  <div class="min-h-screen font-sans bg-surface text-on-surface">

    <!-- Top Nav -->
    <header class="border-b border-outline-variant/15 bg-surface-container-lowest/80 backdrop-blur-md fixed top-0 w-full z-50">
      <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <div class="flex items-center gap-8">
          <Link href="/" class="font-extrabold tracking-tighter text-lg text-on-surface font-headline flex items-center gap-2">
            <div class="w-7 h-7 cta-gradient rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm shadow-primary/20">N</div>
            Noryxon
          </Link>
          <div class="hidden md:flex items-center gap-1 bg-surface-container-low rounded-xl px-3 py-2 w-80">
            <svg class="w-4 h-4 text-on-surface-variant/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search documentation..."
              class="bg-transparent text-sm text-on-surface placeholder:text-on-surface-variant/40 outline-none w-full ml-2"
            />
            <kbd class="text-[10px] text-on-surface-variant/40 border border-outline-variant/20 rounded px-1.5 py-0.5 font-mono hidden lg:inline">/</kbd>
          </div>
        </div>
        <nav class="flex items-center gap-4">
          <Link href="/" class="text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors">Home</Link>
          <Link href="/register" class="cta-gradient text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:scale-95 transition-transform">Get Started</Link>
        </nav>
      </div>
    </header>

    <div class="flex pt-16">
      <!-- Sidebar -->
      <aside class="hidden lg:block w-64 shrink-0 fixed top-16 bottom-0 overflow-y-auto border-r border-outline-variant/10 bg-surface-container-lowest px-4 pt-4 pb-8">
        <nav class="space-y-6">
          <div v-for="group in filteredNav" :key="group.title">
            <div class="text-[10px] font-bold text-on-surface-variant/50 uppercase tracking-widest mb-2 px-3">{{ group.title }}</div>
            <div class="space-y-0.5">
              <button
                v-for="item in group.items"
                :key="item.id"
                @click="activeSection = item.id"
                class="w-full text-left px-3 py-2 text-sm rounded-xl transition-all duration-200 font-medium"
                :class="activeSection === item.id
                  ? 'text-primary bg-primary/8 font-bold'
                  : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low'"
              >
                {{ item.label }}
              </button>
            </div>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 lg:ml-64 min-h-[calc(100vh-4rem)]">
        <div class="max-w-4xl mx-auto px-6 md:px-12 py-12">

          <!-- Hero intro -->
          <div v-if="activeSection === 'introduction'" class="animate-fade-in">
            <div class="inline-flex items-center gap-2 mb-6 px-3 py-1.5 rounded-full bg-primary/5 border border-primary/15">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
              </span>
              <span class="font-mono text-[10px] text-primary tracking-widest uppercase font-bold">Documentation</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter text-on-surface mb-4 font-headline">Noryxon API Docs</h1>
            <p class="text-lg text-on-surface-variant leading-relaxed max-w-2xl mb-10">
              Everything you need to integrate digital asset invoicing and compliance documentation into your application. Zero custody, full audit trail.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
              <button @click="activeSection = 'quickstart'" class="group p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5 hover:-translate-y-0.5 transition-all text-left">
                <div class="w-10 h-10 rounded-xl bg-primary/8 flex items-center justify-center mb-4 group-hover:bg-primary/15 transition-colors">
                  <span class="material-symbols-outlined text-primary text-xl">rocket_launch</span>
                </div>
                <h3 class="font-bold text-on-surface mb-1 font-headline">Quickstart</h3>
                <p class="text-xs text-on-surface-variant">Get up and running in under 5 minutes.</p>
              </button>
              <button @click="activeSection = 'authentication'" class="group p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5 hover:-translate-y-0.5 transition-all text-left">
                <div class="w-10 h-10 rounded-xl bg-tertiary/8 flex items-center justify-center mb-4 group-hover:bg-tertiary/15 transition-colors">
                  <span class="material-symbols-outlined text-tertiary text-xl">key</span>
                </div>
                <h3 class="font-bold text-on-surface mb-1 font-headline">Authentication</h3>
                <p class="text-xs text-on-surface-variant">API keys, scopes, and security.</p>
              </button>
              <button @click="activeSection = 'invoices'" class="group p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5 hover:-translate-y-0.5 transition-all text-left">
                <div class="w-10 h-10 rounded-xl bg-primary-container/15 flex items-center justify-center mb-4 group-hover:bg-primary-container/25 transition-colors">
                  <span class="material-symbols-outlined text-primary-container text-xl">receipt_long</span>
                </div>
                <h3 class="font-bold text-on-surface mb-1 font-headline">Invoices API</h3>
                <p class="text-xs text-on-surface-variant">Create and manage compliant invoices.</p>
              </button>
            </div>

            <div class="p-6 rounded-2xl bg-on-surface text-white font-mono text-sm overflow-x-auto">
              <div class="text-white/40 mb-2"># Install the SDK</div>
              <div><span class="text-primary-container font-bold">npm</span> install @noryxon/sdk</div>
              <div class="mt-4 text-white/40"># Or use curl directly</div>
              <div><span class="text-primary-container font-bold">curl</span> -X POST https://api.noryxon.com/v1/invoices \</div>
              <div class="ml-4">-H <span class="text-primary/80">"Authorization: Bearer sk_live_..."</span></div>
            </div>
          </div>

          <!-- Quickstart -->
          <div v-if="activeSection === 'quickstart'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Quickstart Guide</h1>
            <p class="text-on-surface-variant mb-8">Go from zero to your first invoice in under 5 minutes.</p>

            <div class="space-y-8">
              <div v-for="(step, i) in quickstartSteps" :key="i" class="flex gap-5">
                <div class="shrink-0 w-8 h-8 rounded-xl cta-gradient text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-primary/20">{{ i + 1 }}</div>
                <div class="flex-1">
                  <h3 class="font-bold text-on-surface mb-2 font-headline">{{ step.title }}</h3>
                  <p class="text-sm text-on-surface-variant mb-3">{{ step.description }}</p>
                  <div v-if="step.code" class="p-4 rounded-xl bg-on-surface text-white font-mono text-xs overflow-x-auto">
                    <div v-for="(line, j) in step.code" :key="j" :class="line.startsWith('#') ? 'text-white/40' : ''">{{ line }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Authentication -->
          <div v-if="activeSection === 'authentication'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Authentication</h1>
            <p class="text-on-surface-variant mb-8">All API requests require a valid API key sent in the Authorization header.</p>

            <div class="space-y-6">
              <div class="p-4 rounded-xl bg-on-surface text-white font-mono text-xs overflow-x-auto">
                <div class="text-white/40"># Include your API key in every request</div>
                <div>Authorization: Bearer <span class="text-primary-container">sk_live_your_api_key</span></div>
              </div>

              <div class="p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest">
                <h3 class="font-bold text-on-surface mb-3 font-headline">Key Types</h3>
                <div class="space-y-3">
                  <div v-for="key in keyTypes" :key="key.prefix" class="flex items-start gap-3">
                    <code class="text-xs font-bold px-2 py-1 rounded-lg shrink-0" :class="key.color">{{ key.prefix }}</code>
                    <div>
                      <div class="text-sm font-semibold text-on-surface">{{ key.name }}</div>
                      <div class="text-xs text-on-surface-variant">{{ key.description }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="p-4 rounded-xl border-l-4 border-primary bg-primary/5">
                <div class="flex items-center gap-2 mb-1">
                  <span class="material-symbols-outlined text-primary text-sm">info</span>
                  <span class="text-sm font-bold text-primary">Important</span>
                </div>
                <p class="text-sm text-on-surface-variant">Never expose your secret key in client-side code. Use publishable keys for frontend integrations and secret keys only on your server.</p>
              </div>
            </div>
          </div>

          <!-- Invoices API -->
          <div v-if="activeSection === 'invoices'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Invoices API</h1>
            <p class="text-on-surface-variant mb-8">Create, retrieve, and manage compliant digital asset invoices.</p>

            <div class="space-y-8">
              <div v-for="endpoint in invoiceEndpoints" :key="endpoint.method + endpoint.path">
                <div class="flex items-center gap-3 mb-3">
                  <span class="text-[11px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" :class="methodColor(endpoint.method)">{{ endpoint.method }}</span>
                  <code class="text-sm font-bold text-on-surface font-mono">{{ endpoint.path }}</code>
                </div>
                <p class="text-sm text-on-surface-variant mb-4">{{ endpoint.description }}</p>

                <div v-if="endpoint.body" class="mb-4">
                  <div class="text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-widest mb-2">Request Body</div>
                  <div class="overflow-x-auto rounded-xl border border-outline-variant/15">
                    <table class="w-full text-sm">
                      <thead>
                        <tr class="bg-surface-container-low/50 border-b border-outline-variant/10">
                          <th class="text-left px-4 py-2 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Field</th>
                          <th class="text-left px-4 py-2 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Type</th>
                          <th class="text-left px-4 py-2 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="field in endpoint.body" :key="field.name" class="border-b border-outline-variant/10">
                          <td class="px-4 py-2.5 font-mono text-xs font-bold text-on-surface">{{ field.name }} <span v-if="field.required" class="text-error">*</span></td>
                          <td class="px-4 py-2.5 text-xs text-primary">{{ field.type }}</td>
                          <td class="px-4 py-2.5 text-xs text-on-surface-variant">{{ field.description }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div v-if="endpoint.example" class="p-4 rounded-xl bg-on-surface text-white font-mono text-xs overflow-x-auto">
                  <div v-for="(line, j) in endpoint.example" :key="j" :class="line.startsWith('#') || line.startsWith('//') ? 'text-white/40' : ''">{{ line }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Webhooks -->
          <div v-if="activeSection === 'webhooks'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Webhooks</h1>
            <p class="text-on-surface-variant mb-8">Receive real-time notifications when events occur in your Noryxon account.</p>

            <div class="space-y-6">
              <div class="p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest">
                <h3 class="font-bold text-on-surface mb-3 font-headline">Available Events</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <div v-for="event in webhookEvents" :key="event.name" class="flex items-center gap-3 p-3 rounded-xl bg-surface-container-low/50">
                    <code class="text-xs font-bold text-primary">{{ event.name }}</code>
                    <span class="text-xs text-on-surface-variant">{{ event.description }}</span>
                  </div>
                </div>
              </div>

              <div class="p-4 rounded-xl bg-on-surface text-white font-mono text-xs overflow-x-auto">
                <div class="text-white/40"># Example webhook payload</div>
                <div>{</div>
                <div class="ml-4">"event": "invoice.created",</div>
                <div class="ml-4">"data": {</div>
                <div class="ml-8">"id": "<span class="text-primary-container">inv_8x92ka</span>",</div>
                <div class="ml-8">"amount": "1250.00",</div>
                <div class="ml-8">"currency": "USDC",</div>
                <div class="ml-8">"chain": "ethereum",</div>
                <div class="ml-8">"status": "pending"</div>
                <div class="ml-4">},</div>
                <div class="ml-4">"timestamp": 1711324800</div>
                <div>}</div>
              </div>
            </div>
          </div>

          <!-- Chains -->
          <div v-if="activeSection === 'chains'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Supported Chains</h1>
            <p class="text-on-surface-variant mb-8">Noryxon supports invoicing and compliance documentation across 12+ blockchain networks.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
              <div v-for="chain in supportedChains" :key="chain.id" class="flex items-center gap-3 p-4 rounded-xl border border-outline-variant/15 bg-surface-container-lowest hover:border-primary/20 transition-all">
                <span class="text-xl">{{ chain.icon }}</span>
                <div>
                  <div class="text-sm font-bold text-on-surface">{{ chain.name }}</div>
                  <div class="text-[11px] text-on-surface-variant font-mono">{{ chain.id }}</div>
                </div>
                <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-tertiary-container/15 text-tertiary">Live</span>
              </div>
            </div>
          </div>

          <!-- DAT Reports -->
          <div v-if="activeSection === 'dat-reports'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">DAT Reports</h1>
            <p class="text-on-surface-variant mb-8">Digital Asset Transaction reports are automatically generated for every confirmed invoice. These reports are compliant with emerging global tax documentation standards.</p>

            <div class="space-y-6">
              <div class="p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest">
                <h3 class="font-bold text-on-surface mb-3 font-headline">Report Contents</h3>
                <ul class="space-y-2">
                  <li v-for="item in datContents" :key="item" class="flex items-center gap-3 text-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-tertiary text-sm">check_circle</span>
                    {{ item }}
                  </li>
                </ul>
              </div>

              <div class="flex items-center gap-3 mb-3">
                <span class="text-[11px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider bg-blue-500/10 text-blue-600">GET</span>
                <code class="text-sm font-bold text-on-surface font-mono">/v1/invoices/:id/dat-report</code>
              </div>
              <p class="text-sm text-on-surface-variant mb-4">Retrieve the DAT report PDF for a confirmed invoice.</p>

              <div class="p-4 rounded-xl bg-on-surface text-white font-mono text-xs overflow-x-auto">
                <div><span class="text-primary-container font-bold">curl</span> https://api.noryxon.com/v1/invoices/inv_8x92ka/dat-report \</div>
                <div class="ml-4">-H <span class="text-primary/80">"Authorization: Bearer sk_live_..."</span> \</div>
                <div class="ml-4">-o report.pdf</div>
              </div>
            </div>
          </div>

          <!-- SDKs -->
          <div v-if="activeSection === 'sdks'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">SDKs & Libraries</h1>
            <p class="text-on-surface-variant mb-8">Official client libraries for popular languages and frameworks.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="sdk in sdks" :key="sdk.language" class="p-5 rounded-2xl border border-outline-variant/15 bg-surface-container-lowest hover:border-primary/20 transition-all">
                <div class="flex items-center gap-3 mb-3">
                  <span class="text-2xl">{{ sdk.icon }}</span>
                  <div>
                    <div class="font-bold text-on-surface font-headline">{{ sdk.language }}</div>
                    <div class="text-[11px] text-on-surface-variant font-mono">{{ sdk.package }}</div>
                  </div>
                </div>
                <div class="p-3 rounded-lg bg-on-surface text-white font-mono text-xs">{{ sdk.install }}</div>
              </div>
            </div>
          </div>

          <!-- Rate Limits -->
          <div v-if="activeSection === 'rate-limits'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Rate Limits</h1>
            <p class="text-on-surface-variant mb-8">API rate limits are enforced per API key to ensure fair usage.</p>

            <div class="overflow-x-auto rounded-2xl border border-outline-variant/15">
              <table class="w-full text-sm">
                <thead>
                  <tr class="bg-surface-container-low/50 border-b border-outline-variant/10">
                    <th class="text-left px-5 py-3 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Plan</th>
                    <th class="text-left px-5 py-3 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Requests/min</th>
                    <th class="text-left px-5 py-3 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Requests/day</th>
                    <th class="text-left px-5 py-3 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-wider">Burst</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="limit in rateLimits" :key="limit.plan" class="border-b border-outline-variant/10">
                    <td class="px-5 py-3 font-bold text-on-surface">{{ limit.plan }}</td>
                    <td class="px-5 py-3 text-on-surface-variant font-mono">{{ limit.perMin }}</td>
                    <td class="px-5 py-3 text-on-surface-variant font-mono">{{ limit.perDay }}</td>
                    <td class="px-5 py-3 text-on-surface-variant font-mono">{{ limit.burst }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Errors -->
          <div v-if="activeSection === 'errors'" class="animate-fade-in">
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface mb-2 font-headline">Error Codes</h1>
            <p class="text-on-surface-variant mb-8">Standard HTTP status codes with structured JSON error bodies.</p>

            <div class="space-y-3">
              <div v-for="error in errorCodes" :key="error.code" class="flex items-start gap-4 p-4 rounded-xl border border-outline-variant/10 bg-surface-container-lowest">
                <code class="text-sm font-black shrink-0" :class="error.code < 500 ? 'text-primary' : 'text-error'">{{ error.code }}</code>
                <div>
                  <div class="text-sm font-bold text-on-surface">{{ error.name }}</div>
                  <div class="text-xs text-on-surface-variant">{{ error.description }}</div>
                </div>
              </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-on-surface text-white font-mono text-xs overflow-x-auto">
              <div class="text-white/40">// Error response format</div>
              <div>{</div>
              <div class="ml-4">"error": {</div>
              <div class="ml-8">"code": "<span class="text-primary-container">invalid_chain</span>",</div>
              <div class="ml-8">"message": "The chain 'xyz' is not supported.",</div>
              <div class="ml-8">"doc_url": "https://noryxon.com/docs#chains"</div>
              <div class="ml-4">}</div>
              <div>}</div>
            </div>
          </div>

        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const searchQuery = ref('');
const activeSection = ref('introduction');

const navGroups = [
  {
    title: 'Getting Started',
    items: [
      { id: 'introduction', label: 'Introduction' },
      { id: 'quickstart', label: 'Quickstart' },
      { id: 'authentication', label: 'Authentication' },
      { id: 'sdks', label: 'SDKs & Libraries' },
    ],
  },
  {
    title: 'Core API',
    items: [
      { id: 'invoices', label: 'Invoices' },
      { id: 'webhooks', label: 'Webhooks' },
      { id: 'chains', label: 'Supported Chains' },
      { id: 'dat-reports', label: 'DAT Reports' },
    ],
  },
  {
    title: 'Reference',
    items: [
      { id: 'rate-limits', label: 'Rate Limits' },
      { id: 'errors', label: 'Error Codes' },
    ],
  },
];

const filteredNav = computed(() => {
  if (!searchQuery.value) return navGroups;
  const q = searchQuery.value.toLowerCase();
  return navGroups
    .map(g => ({ ...g, items: g.items.filter(i => i.label.toLowerCase().includes(q)) }))
    .filter(g => g.items.length > 0);
});

const quickstartSteps = [
  {
    title: 'Create an account',
    description: 'Sign up at noryxon.com and navigate to the Developer Portal to generate your API keys.',
    code: null,
  },
  {
    title: 'Install the SDK',
    description: 'Add the Noryxon SDK to your project using your preferred package manager.',
    code: ['npm install @noryxon/sdk', '# or', 'pip install noryxon'],
  },
  {
    title: 'Create your first invoice',
    description: 'Generate a compliant invoice linked to an on-chain transaction.',
    code: [
      'import Noryxon from "@noryxon/sdk";',
      '',
      'const noryxon = new Noryxon("sk_live_...");',
      '',
      'const invoice = await noryxon.invoices.create({',
      '  amount: "1250.00",',
      '  currency: "USDC",',
      '  chain: "ethereum",',
      '  payer: "client@example.com",',
      '});',
      '',
      'console.log(invoice.id); // inv_8x92ka',
    ],
  },
  {
    title: 'Receive webhook confirmation',
    description: 'Set up a webhook endpoint to get notified when the payment is detected and the invoice is documented.',
    code: [
      '# Configure webhook in Developer Portal',
      '# POST https://your-app.com/webhooks/noryxon',
      '',
      '# Payload received:',
      '{ "event": "invoice.confirmed", "data": { ... } }',
    ],
  },
];

const keyTypes = [
  { prefix: 'sk_live_', name: 'Secret Key (Live)', description: 'Full API access. Server-side only. Never expose in client code.', color: 'bg-error/10 text-error' },
  { prefix: 'pk_live_', name: 'Publishable Key (Live)', description: 'Limited access for client-side integrations (checkout, status checks).', color: 'bg-primary/10 text-primary' },
  { prefix: 'sk_test_', name: 'Secret Key (Test)', description: 'Full API access on testnet. Safe for development.', color: 'bg-primary-container/15 text-primary-container' },
  { prefix: 'pk_test_', name: 'Publishable Key (Test)', description: 'Client-side testnet key for sandbox development.', color: 'bg-tertiary-container/15 text-tertiary' },
];

const invoiceEndpoints = [
  {
    method: 'POST',
    path: '/v1/invoices',
    description: 'Create a new invoice. The invoice will begin monitoring the specified chain for payment.',
    body: [
      { name: 'amount', type: 'string', required: true, description: 'Invoice amount in the specified currency.' },
      { name: 'currency', type: 'string', required: true, description: 'Token symbol (USDC, USDT, ETH, BTC, etc.).' },
      { name: 'chain', type: 'string', required: true, description: 'Blockchain network ID (ethereum, solana, polygon, etc.).' },
      { name: 'payer', type: 'string', required: false, description: 'Payer email or wallet address for documentation.' },
      { name: 'metadata', type: 'object', required: false, description: 'Arbitrary key-value pairs attached to the invoice.' },
    ],
    example: [
      'curl -X POST https://api.noryxon.com/v1/invoices \\',
      '  -H "Authorization: Bearer sk_live_..." \\',
      '  -H "Content-Type: application/json" \\',
      '  -d \'{"amount":"1250.00","currency":"USDC","chain":"ethereum"}\'',
    ],
  },
  {
    method: 'GET',
    path: '/v1/invoices/:id',
    description: 'Retrieve an invoice by ID, including current status and payment details.',
    body: null,
    example: [
      'curl https://api.noryxon.com/v1/invoices/inv_8x92ka \\',
      '  -H "Authorization: Bearer sk_live_..."',
    ],
  },
  {
    method: 'GET',
    path: '/v1/invoices',
    description: 'List all invoices with optional filtering by status, chain, and date range.',
    body: null,
    example: [
      'curl "https://api.noryxon.com/v1/invoices?status=confirmed&limit=25" \\',
      '  -H "Authorization: Bearer sk_live_..."',
    ],
  },
];

const webhookEvents = [
  { name: 'invoice.created', description: 'Invoice was created' },
  { name: 'invoice.pending', description: 'Payment detected, awaiting confirmations' },
  { name: 'invoice.confirmed', description: 'Payment confirmed on-chain' },
  { name: 'invoice.expired', description: 'Invoice expired without payment' },
  { name: 'dat.generated', description: 'DAT report ready for download' },
  { name: 'wallet.connected', description: 'New wallet linked to account' },
];

const supportedChains = [
  { id: 'ethereum', name: 'Ethereum', icon: 'Ξ' },
  { id: 'bitcoin', name: 'Bitcoin', icon: '₿' },
  { id: 'solana', name: 'Solana', icon: '◎' },
  { id: 'polygon', name: 'Polygon', icon: '⬡' },
  { id: 'arbitrum', name: 'Arbitrum', icon: '⟐' },
  { id: 'optimism', name: 'Optimism', icon: '⊕' },
  { id: 'base', name: 'Base', icon: '◉' },
  { id: 'bnb', name: 'BNB Chain', icon: '◆' },
  { id: 'avalanche', name: 'Avalanche', icon: '▲' },
  { id: 'tron', name: 'Tron', icon: '◈' },
  { id: 'near', name: 'Near', icon: '◐' },
  { id: 'stellar', name: 'Stellar', icon: '✦' },
];

const datContents = [
  'Transaction hash and block confirmation details',
  'Payer and payee wallet addresses (anonymized option available)',
  'Amount, token, and chain with USD equivalent at time of settlement',
  'Digital Asset Tax classification per jurisdiction',
  'Timestamp, invoice reference, and unique audit ID',
  'Cryptographic signature for tamper-proof verification',
];

const sdks = [
  { language: 'JavaScript / Node.js', icon: '🟨', package: '@noryxon/sdk', install: 'npm install @noryxon/sdk' },
  { language: 'Python', icon: '🐍', package: 'noryxon', install: 'pip install noryxon' },
  { language: 'Go', icon: '🔵', package: 'github.com/noryxon/go-sdk', install: 'go get github.com/noryxon/go-sdk' },
  { language: 'PHP', icon: '🐘', package: 'noryxon/sdk', install: 'composer require noryxon/sdk' },
  { language: 'Ruby', icon: '💎', package: 'noryxon', install: 'gem install noryxon' },
  { language: 'Rust', icon: '🦀', package: 'noryxon', install: 'cargo add noryxon' },
];

const rateLimits = [
  { plan: 'Developer', perMin: '60', perDay: '10,000', burst: '10' },
  { plan: 'Node Operator', perMin: '600', perDay: '100,000', burst: '50' },
  { plan: 'Enterprise', perMin: 'Custom', perDay: 'Custom', burst: 'Custom' },
];

const errorCodes = [
  { code: 400, name: 'Bad Request', description: 'The request body is malformed or missing required fields.' },
  { code: 401, name: 'Unauthorized', description: 'Invalid or missing API key.' },
  { code: 403, name: 'Forbidden', description: 'The API key does not have permission for this action.' },
  { code: 404, name: 'Not Found', description: 'The requested resource does not exist.' },
  { code: 409, name: 'Conflict', description: 'Duplicate invoice or conflicting state.' },
  { code: 422, name: 'Validation Error', description: 'One or more fields failed validation.' },
  { code: 429, name: 'Rate Limited', description: 'Too many requests. Retry after the indicated delay.' },
  { code: 500, name: 'Internal Error', description: 'Something went wrong on our end. Contact support.' },
];

const methodColor = (method) => {
  return {
    GET: 'bg-blue-500/10 text-blue-600',
    POST: 'bg-tertiary-container/15 text-tertiary',
    PUT: 'bg-primary-container/15 text-primary',
    PATCH: 'bg-primary-container/15 text-primary',
    DELETE: 'bg-error/10 text-error',
  }[method] || 'bg-surface-container-low text-on-surface-variant';
};
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }

@keyframes fade-in {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
