<template>
  <DashboardLayout pageTitle="Wallets" breadcrumb="SYSTEM > WALLETS > VAULT" dashboardType="merchant">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <div class="text-sm font-medium text-text-muted mb-1">Link wallets and exchanges for automatic transaction detection and invoice generation. We never touch your funds — pinky promise.</div>
      </div>
      <button
        @click="openConnectModal"
        class="flex items-center gap-2 bg-pulse text-void font-semibold px-5 py-2.5 rounded-lg text-sm hover:shadow-lg hover:shadow-pulse/20 transition-all hover:bg-[#E5C130]"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Connect Wallet
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <StatCard label="Total Wallets" :value="wallets.length" subtitle="WALLETS_LINKED" />
      <StatCard label="Total Received" :value="formatCurrency(totalReceived)" subtitle="ACROSS_ALL_CHAINS" />
      <StatCard label="Active Chains" :value="activeChains" subtitle="NETWORKS_TRACKED" />
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-1 mb-6 p-1 bg-ledger rounded-lg border border-ledger-border w-fit">
      <button
        v-for="tab in filterTabs"
        :key="tab.value"
        @click="activeFilter = tab.value"
        class="px-4 py-2 text-xs font-semibold rounded-md transition-all"
        :class="activeFilter === tab.value
          ? 'bg-void text-text-primary shadow-sm border border-ledger-border'
          : 'text-text-muted hover:text-text-primary'"
      >
        {{ tab.label }}
        <span class="ml-1.5 text-[10px] opacity-60">({{ tab.count }})</span>
      </button>
    </div>

    <!-- Wallet Cards Grid -->
    <div v-if="filteredWallets.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="wallet in filteredWallets"
        :key="wallet.id"
        class="border border-ledger-border bg-void rounded-xl p-5 hover:border-pulse/30 transition-all group/card"
      >
        <!-- Card Header -->
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0" :class="getWalletTypeBg(wallet.type)">
              {{ getWalletIcon(wallet) }}
            </div>
            <div>
              <div class="text-sm font-bold text-text-primary">{{ wallet.label }}</div>
              <div class="flex items-center gap-2 mt-0.5">
                <span
                  class="text-[10px] font-semibold px-2 py-0.5 rounded-md"
                  :class="[getWalletTypeColor(wallet.type), getWalletTypeBg(wallet.type)]"
                >
                  {{ getWalletTypeLabel(wallet.type) }}
                </span>
                <span v-if="wallet.chain" class="text-[10px] text-text-muted font-medium">
                  {{ getChainConfig(wallet.chain).icon }} {{ getChainConfig(wallet.chain).symbol }}
                </span>
                <span v-else class="text-[10px] text-text-muted font-medium">Multi-chain</span>
              </div>
            </div>
          </div>
          <!-- Status Dot -->
          <div class="flex items-center gap-1.5">
            <span
              class="w-2 h-2 rounded-full"
              :class="{
                'bg-green-500': wallet.status === 'connected',
                'bg-blue-500 animate-pulse': wallet.status === 'syncing',
                'bg-amber-500': wallet.status === 'watching',
                'bg-gray-400': wallet.status === 'paused',
                'bg-red-500': wallet.status === 'error',
              }"
            ></span>
            <span class="text-[10px] font-medium capitalize" :class="{
              'text-green-500': wallet.status === 'connected',
              'text-blue-500': wallet.status === 'syncing',
              'text-amber-500': wallet.status === 'watching',
              'text-gray-400': wallet.status === 'paused',
              'text-red-500': wallet.status === 'error',
            }">{{ wallet.status }}</span>
          </div>
        </div>

        <!-- Identifier -->
        <div class="mb-4 p-2.5 bg-ledger rounded-lg border border-ledger-border">
          <div class="text-[10px] text-text-muted font-semibold mb-1 uppercase tracking-wide">
            {{ wallet.type === 'exchange' ? 'API Connection' : wallet.type === 'xpub' ? 'Extended Public Key' : 'Address' }}
          </div>
          <div class="text-xs font-mono text-pulse truncate cursor-pointer hover:underline" @click="copyIdentifier(wallet)">
            {{ wallet.displayIdentifier }}
          </div>
          <div v-if="wallet.derivationPath" class="text-[10px] font-mono text-text-muted mt-1">
            Path: {{ wallet.derivationPath }}
          </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div>
            <div class="text-[10px] text-text-muted font-medium uppercase tracking-wide">Received</div>
            <div class="text-sm font-bold text-text-primary">{{ formatCurrency(wallet.totalReceived) }}</div>
          </div>
          <div>
            <div class="text-[10px] text-text-muted font-medium uppercase tracking-wide">
              {{ wallet.type === 'xpub' ? 'Addrs Generated' : 'Last Synced' }}
            </div>
            <div class="text-sm font-bold text-text-primary">
              {{ wallet.type === 'xpub' ? wallet.addressesGenerated.toLocaleString() : (wallet.lastSyncedAt ? formatDate(wallet.lastSyncedAt) : 'Never') }}
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2 pt-3 border-t border-ledger-border">
          <button
            @click="toggleStatus(wallet)"
            class="flex-1 px-3 py-2 rounded-lg border border-ledger-border text-text-muted hover:border-blue-500 hover:text-blue-500 transition-colors text-xs font-semibold"
          >
            {{ wallet.status === 'paused' ? '▶ Resume' : '⏸ Pause' }}
          </button>
          <button
            @click="deleteWallet(wallet)"
            class="px-3 py-2 rounded-lg border border-ledger-border text-text-muted hover:border-red-500 hover:text-red-500 transition-colors text-xs font-semibold"
          >
            ✕
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="border border-ledger-border border-dashed bg-void rounded-xl p-12 text-center">
      <div class="text-4xl mb-3">💼</div>
      <div class="text-sm font-semibold text-text-primary mb-1">
        {{ activeFilter === 'all' ? 'No wallets connected yet' : `No ${getWalletTypeLabel(activeFilter).toLowerCase()}s connected` }}
      </div>
      <div class="text-xs text-text-muted mb-4">Connect your first wallet to start tracking transactions and generating invoices.</div>
      <button
        @click="openConnectModal"
        class="inline-flex items-center gap-2 bg-pulse text-void font-semibold px-5 py-2.5 rounded-lg text-sm hover:shadow-lg hover:shadow-pulse/20 transition-all"
      >
        Connect Wallet
      </button>
    </div>

    <!-- Connect Wallet Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showConnectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-void/80 backdrop-blur-sm" @click.self="closeModal">
          <div class="bg-ledger border border-ledger-border rounded-xl w-full max-w-2xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-ledger-border bg-void sticky top-0 z-10">
              <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-pulse rounded-full"></span>
                {{ modalStep === 1 ? 'Connect Wallet' : `Connect ${getWalletTypeLabel(form.type)}` }}
              </div>
              <div class="flex items-center gap-2">
                <button v-if="modalStep === 2" @click="modalStep = 1" class="px-3 py-1 rounded-md text-xs font-medium text-text-muted hover:text-text-primary hover:bg-ledger-light transition-colors">
                  ← Back
                </button>
                <button @click="closeModal" class="p-1 rounded-md text-text-muted hover:text-text-primary hover:bg-ledger-light transition-colors">
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>
            </div>

            <!-- Step 1: Choose Type -->
            <div v-if="modalStep === 1" class="p-6">
              <div class="text-xs text-text-muted mb-4 font-medium">Choose how you want to connect:</div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                  v-for="option in connectionTypes"
                  :key="option.type"
                  @click="selectType(option.type)"
                  class="p-5 border border-ledger-border rounded-xl text-left hover:border-pulse hover:bg-void transition-all group/opt"
                >
                  <div class="text-2xl mb-2">{{ option.icon }}</div>
                  <div class="text-sm font-bold text-text-primary group-hover/opt:text-pulse transition-colors">{{ option.title }}</div>
                  <div class="text-xs text-text-muted mt-1 leading-relaxed">{{ option.description }}</div>
                  <div class="text-[10px] text-text-muted/60 mt-2 font-medium">{{ option.examples }}</div>
                </button>
              </div>
            </div>

            <!-- Step 2: Type-Specific Form -->
            <div v-if="modalStep === 2" class="p-6 space-y-5 bg-void/50">

              <!-- EXCHANGE FORM -->
              <template v-if="form.type === 'exchange'">
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Exchange</label>
                  <div class="grid grid-cols-3 gap-2">
                    <button
                      v-for="ex in walletProviders.exchanges"
                      :key="ex.id"
                      @click="form.provider = ex.id"
                      class="p-3 border rounded-lg text-center transition-all text-xs font-semibold"
                      :class="form.provider === ex.id
                        ? 'border-pulse bg-pulse/10 text-pulse'
                        : 'border-ledger-border text-text-muted hover:border-pulse/50'"
                    >
                      <div class="text-lg mb-1">{{ ex.icon }}</div>
                      {{ ex.name }}
                    </button>
                  </div>
                  <div v-if="form.errors.provider" class="text-red-500 text-xs mt-1">{{ form.errors.provider }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. My Binance Account" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.label" class="text-red-500 text-xs mt-1">{{ form.errors.label }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">API Key <span class="text-text-muted font-normal">(read-only recommended)</span></label>
                  <input v-model="form.api_key" type="password" placeholder="Enter your API key" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.api_key" class="text-red-500 text-xs mt-1">{{ form.errors.api_key }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">API Secret</label>
                  <input v-model="form.api_secret" type="password" placeholder="Enter your API secret" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.api_secret" class="text-red-500 text-xs mt-1">{{ form.errors.api_secret }}</div>
                </div>
                <div v-if="selectedExchangeRequiresPassphrase">
                  <label class="block text-xs font-semibold text-text-primary mb-2">API Passphrase</label>
                  <input v-model="form.api_passphrase" type="password" placeholder="Enter your API passphrase" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                </div>
                <div class="flex items-center gap-2 p-3 bg-amber-500/5 border border-amber-500/20 rounded-lg">
                  <span class="text-amber-500 text-sm">⚠️</span>
                  <span class="text-xs text-amber-500/80">Use read-only API keys. We only need to view balances and transactions — never withdrawals.</span>
                </div>
              </template>

              <!-- SOFTWARE WALLET FORM -->
              <template v-if="form.type === 'software'">
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Wallet App</label>
                  <div class="grid grid-cols-3 gap-2">
                    <button
                      v-for="sw in walletProviders.software"
                      :key="sw.id"
                      @click="form.provider = sw.id"
                      class="p-3 border rounded-lg text-center transition-all text-xs font-semibold"
                      :class="form.provider === sw.id
                        ? 'border-pulse bg-pulse/10 text-pulse'
                        : 'border-ledger-border text-text-muted hover:border-pulse/50'"
                    >
                      <div class="text-lg mb-1">{{ sw.icon }}</div>
                      {{ sw.name }}
                    </button>
                  </div>
                  <div v-if="form.errors.provider" class="text-red-500 text-xs mt-1">{{ form.errors.provider }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Blockchain</label>
                  <select v-model="form.chain_id" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all cursor-pointer">
                    <option value="" disabled>Select chain...</option>
                    <option v-for="chain in availableChains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                  </select>
                  <div v-if="form.errors.chain_id" class="text-red-500 text-xs mt-1">{{ form.errors.chain_id }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Wallet Address</label>
                  <input v-model="form.address" type="text" placeholder="0x... or bc1... or So1..." class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. My MetaMask" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.label" class="text-red-500 text-xs mt-1">{{ form.errors.label }}</div>
                </div>
              </template>

              <!-- XPUB FORM -->
              <template v-if="form.type === 'xpub'">
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. Ledger Nano X" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.label" class="text-red-500 text-xs mt-1">{{ form.errors.label }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Blockchain</label>
                  <select v-model="form.chain_id" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all cursor-pointer">
                    <option value="" disabled>Select chain...</option>
                    <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                  </select>
                  <div v-if="form.errors.chain_id" class="text-red-500 text-xs mt-1">{{ form.errors.chain_id }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Extended Public Key</label>
                  <textarea v-model="form.xpub_key" rows="3" placeholder="xpub6CUGRUonZSQ4TWtTMmzXdrXDtypWKi..." class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 font-mono text-xs text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all resize-none"></textarea>
                  <div v-if="form.errors.xpub_key" class="text-red-500 text-xs mt-1">{{ form.errors.xpub_key }}</div>
                  <div class="flex items-center gap-2 mt-2">
                    <div class="w-2 h-2 rounded-full" :class="xpubValid ? 'bg-green-500' : 'bg-text-muted'"></div>
                    <span class="text-[11px] font-medium" :class="xpubValid ? 'text-green-500' : 'text-text-muted'">
                      {{ form.xpub_key.length > 0 ? (xpubValid ? 'Valid format' : 'Invalid format') : 'Awaiting input...' }}
                    </span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Derivation Path</label>
                  <input v-model="form.derivation_path" type="text" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.derivation_path" class="text-red-500 text-xs mt-1">{{ form.errors.derivation_path }}</div>
                </div>
              </template>

              <!-- MANUAL ADDRESS FORM -->
              <template v-if="form.type === 'manual'">
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. Trading Wallet" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.label" class="text-red-500 text-xs mt-1">{{ form.errors.label }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Blockchain</label>
                  <select v-model="form.chain_id" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all cursor-pointer">
                    <option value="" disabled>Select chain...</option>
                    <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                  </select>
                  <div v-if="form.errors.chain_id" class="text-red-500 text-xs mt-1">{{ form.errors.chain_id }}</div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-text-primary mb-2">Wallet Address</label>
                  <input v-model="form.address" type="text" placeholder="Paste any blockchain address" class="w-full bg-void border border-ledger-border rounded-lg px-4 py-2.5 text-sm font-mono text-text-primary placeholder:text-text-muted/50 focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                  <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                </div>
                <div class="flex items-center gap-2 p-3 bg-blue-500/5 border border-blue-500/20 rounded-lg">
                  <span class="text-blue-500 text-sm">ℹ️</span>
                  <span class="text-xs text-blue-500/80">Any valid blockchain address works. We'll watch it for incoming transactions to auto-generate invoices.</span>
                </div>
              </template>
            </div>

            <!-- Modal Footer (Step 2 only) -->
            <div v-if="modalStep === 2" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-ledger-border bg-void sticky bottom-0">
              <button
                @click="closeModal"
                class="px-5 py-2.5 rounded-lg border border-ledger-border text-text-muted text-sm font-semibold hover:bg-ledger transition-colors"
              >
                Cancel
              </button>
              <button
                @click="submitWallet"
                :disabled="!isFormValid || form.processing"
                class="px-5 py-2.5 rounded-lg bg-pulse text-void text-sm font-bold hover:shadow-lg hover:shadow-pulse/20 hover:bg-[#E5C130] transition-all disabled:opacity-30 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Connect Wallet
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
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  initialWallets: Array,
});

const {
  formatCurrency, formatDate, chains, getChainConfig,
  walletProviders, getProviderConfig, getWalletTypeLabel, getWalletTypeColor, getWalletTypeBg
} = useDashboard();
const { addNotification } = useNotifications();

const wallets = computed(() => props.initialWallets || []);
const showConnectModal = ref(false);
const modalStep = ref(1);
const activeFilter = ref('all');

// ─── Computed Stats ───

const totalReceived = computed(() => wallets.value.reduce((sum, w) => sum + w.totalReceived, 0));
const activeChains = computed(() => new Set(wallets.value.filter(w => w.chain).map(w => w.chain)).size);

// ─── Filter Logic ───

const filterTabs = computed(() => [
  { label: 'All', value: 'all', count: wallets.value.length },
  { label: 'Exchanges', value: 'exchange', count: wallets.value.filter(w => w.type === 'exchange').length },
  { label: 'Software', value: 'software', count: wallets.value.filter(w => w.type === 'software').length },
  { label: 'XPub Keys', value: 'xpub', count: wallets.value.filter(w => w.type === 'xpub').length },
  { label: 'Manual', value: 'manual', count: wallets.value.filter(w => w.type === 'manual').length },
]);

const filteredWallets = computed(() => {
  if (activeFilter.value === 'all') return wallets.value;
  return wallets.value.filter(w => w.type === activeFilter.value);
});

// ─── Connection Types ───

const connectionTypes = [
  {
    type: 'exchange',
    icon: '🏦',
    title: 'Exchange (API Key)',
    description: 'Connect via read-only API for automatic balance and transaction tracking.',
    examples: 'Binance, Coinbase, Kraken, KuCoin, Bybit, OKX'
  },
  {
    type: 'software',
    icon: '📱',
    title: 'Software Wallet',
    description: 'Link by wallet address. We just watch — your keys stay with you.',
    examples: 'MetaMask, Trust Wallet, Phantom, Rabby, Rainbow'
  },
  {
    type: 'xpub',
    icon: '🔑',
    title: 'Extended Public Key',
    description: 'Hardware wallets and watch-only via xpub/ypub/zpub. Derive addresses on-chain.',
    examples: 'Ledger, Trezor, Coldcard, any BIP-32 key'
  },
  {
    type: 'manual',
    icon: '✏️',
    title: 'Manual Address',
    description: 'Paste any blockchain address to monitor for incoming transactions.',
    examples: 'Any valid address on any supported chain'
  },
];

// ─── Form ───

const form = useForm({
  type: '',
  provider: '',
  chain_id: '',
  label: '',
  address: '',
  xpub_key: '',
  derivation_path: "m/84'/0'/0'/0/0",
  api_key: '',
  api_secret: '',
  api_passphrase: '',
});

const selectedExchangeRequiresPassphrase = computed(() => {
  const ex = walletProviders.exchanges.find(e => e.id === form.provider);
  return ex?.requiresPassphrase || false;
});

const availableChains = computed(() => {
  if (form.type === 'software' && form.provider) {
    const sw = walletProviders.software.find(s => s.id === form.provider);
    if (sw?.chains) {
      return chains.filter(c => sw.chains.includes(c.id));
    }
  }
  return chains;
});

const xpubValid = computed(() => {
  return form.xpub_key.length > 20 && (
    form.xpub_key.startsWith('xpub') ||
    form.xpub_key.startsWith('tpub') ||
    form.xpub_key.startsWith('ypub') ||
    form.xpub_key.startsWith('zpub')
  );
});

const isFormValid = computed(() => {
  if (!form.label) return false;
  switch (form.type) {
    case 'exchange':
      return form.provider && form.api_key && form.api_secret;
    case 'software':
      return form.provider && form.chain_id && form.address;
    case 'xpub':
      return form.chain_id && xpubValid.value && form.derivation_path;
    case 'manual':
      return form.chain_id && form.address;
    default:
      return false;
  }
});

// ─── Helpers ───

const getWalletIcon = (wallet) => {
  if (wallet.type === 'exchange') {
    return getProviderConfig('exchange', wallet.provider).icon;
  }
  if (wallet.type === 'software') {
    return getProviderConfig('software', wallet.provider).icon;
  }
  if (wallet.type === 'xpub') return '🔑';
  return '📋';
};

const copyIdentifier = (wallet) => {
  navigator.clipboard?.writeText(wallet.displayIdentifier);
  addNotification('Copied', 'Identifier copied to clipboard.', 'success', 2000);
};

// ─── Modal ───

const openConnectModal = () => {
  showConnectModal.value = true;
  modalStep.value = 1;
  form.reset();
};

const closeModal = () => {
  showConnectModal.value = false;
  modalStep.value = 1;
  form.reset();
};

const selectType = (type) => {
  form.type = type;
  form.provider = type === 'manual' ? 'custom' : '';
  form.label = '';
  form.chain_id = '';
  form.address = '';
  form.xpub_key = '';
  form.derivation_path = "m/84'/0'/0'/0/0";
  form.api_key = '';
  form.api_secret = '';
  form.api_passphrase = '';
  modalStep.value = 2;
};

// ─── Actions ───

const submitWallet = () => {
  if (!isFormValid.value) return;
  form.post(route('dashboard.wallets.store'), {
    onSuccess: () => {
      closeModal();
      addNotification('Wallet Connected', `${getWalletTypeLabel(form.type)} has been linked. Monitoring will begin shortly.`, 'success', 4000);
    }
  });
};

const toggleStatus = (wallet) => {
  const newStatus = wallet.status === 'paused' ? 'watching' : 'paused';
  router.patch(route('dashboard.wallets.update', wallet.id), { status: newStatus }, {
    preserveScroll: true,
    onSuccess: () => {
      addNotification('Status Updated', `Wallet is now ${newStatus}.`, 'success', 3000);
    }
  });
};

const deleteWallet = (wallet) => {
  if (confirm('Are you sure you want to disconnect this wallet? Transaction history will be preserved but monitoring will stop.')) {
    router.delete(route('dashboard.wallets.destroy', wallet.id), {
      preserveScroll: true,
      onSuccess: () => {
        addNotification('Wallet Disconnected', 'Wallet has been removed from your vault.', 'success', 3000);
      }
    });
  }
};
</script>
