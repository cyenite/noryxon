<template>
  <DashboardLayout pageTitle="Wallets & Vaults" breadcrumb="SYSTEM > WALLETS" dashboardType="merchant" subtitle="Manage your cryptographic identities and connections.">

    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-10">
      <div class="flex items-center gap-3">
        <button
          v-for="tab in filterTabs"
          :key="tab.value"
          @click="activeFilter = tab.value"
          class="px-4 py-2 text-xs font-semibold rounded-lg transition-all"
          :class="activeFilter === tab.value
            ? 'bg-surface-container-lowest text-primary shadow-sm border border-outline-variant/10'
            : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-lowest/50'"
        >
          {{ tab.label }}
          <span class="ml-1.5 text-[10px] opacity-60">({{ tab.count }})</span>
        </button>
      </div>
      <div class="flex items-center gap-3">
        <button class="bg-surface-container-highest text-on-secondary-container text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-surface-dim transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">sync</span> Sync All
        </button>
        <button
          @click="openConnectModal"
          class="flex items-center gap-2 cta-gradient text-white font-bold px-5 py-2.5 rounded-lg text-sm shadow-lg shadow-primary/10 hover:shadow-xl hover:shadow-primary/20 transition-all active:scale-95"
        >
          <span class="material-symbols-outlined text-sm">add</span>
          Connect Wallet
        </button>
      </div>
    </div>

    <!-- Exchange Wallets Section -->
    <section v-if="exchangeWallets.length || activeFilter === 'all' || activeFilter === 'exchange'" class="mb-16">
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-primary/10 rounded-lg">
            <span class="material-symbols-outlined text-primary">currency_exchange</span>
          </div>
          <h3 class="font-headline text-xl font-bold tracking-tight">Exchange Wallets</h3>
        </div>
        <button @click="selectTypeAndOpen('exchange')" class="text-primary text-sm font-bold flex items-center gap-1 hover:underline">
          <span class="material-symbols-outlined text-lg">add_circle</span> Add Connection
        </button>
      </div>
      <div v-if="exchangeWallets.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="wallet in exchangeWallets"
          :key="wallet.id"
          class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm hover:shadow-md transition-shadow"
        >
          <div class="flex justify-between items-start mb-6">
            <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-2xl">
              {{ getWalletIcon(wallet) }}
            </div>
            <span
              class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full flex items-center gap-1"
              :class="wallet.status === 'connected' || wallet.status === 'watching'
                ? 'bg-tertiary-container/20 text-on-tertiary-container'
                : 'bg-surface-variant/50 text-on-surface-variant'"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="wallet.status === 'connected' || wallet.status === 'watching' ? 'bg-tertiary' : 'bg-outline'"></span>
              {{ wallet.status }}
            </span>
          </div>
          <h4 class="font-headline text-lg font-bold mb-1">{{ wallet.label }}</h4>
          <p class="text-xs text-on-surface-variant mb-4 font-mono tracking-tight">{{ wallet.displayIdentifier }}</p>
          <div class="text-xs text-on-surface-variant mb-6">
            Received: <span class="font-bold text-on-surface">{{ formatCurrency(wallet.totalReceived) }}</span>
          </div>
          <div class="flex gap-2">
            <button
              @click="toggleStatus(wallet)"
              class="flex-1 bg-surface-container-highest text-on-secondary-container font-semibold py-2.5 rounded-lg text-sm transition-all hover:bg-surface-dim"
            >
              {{ wallet.status === 'paused' ? 'Resume' : 'Manage' }}
            </button>
            <button
              @click="deleteWallet(wallet)"
              class="px-3 bg-surface-container-highest text-on-secondary-container rounded-lg hover:bg-error-container hover:text-on-error-container transition-colors"
            >
              <span class="material-symbols-outlined text-sm">delete</span>
            </button>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-8 text-on-surface-variant text-sm">No exchange wallets connected</div>
    </section>

    <!-- Software Wallets Section -->
    <section v-if="softwareWallets.length || activeFilter === 'all' || activeFilter === 'software'" class="mb-16">
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-primary/10 rounded-lg">
            <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
          </div>
          <h3 class="font-headline text-xl font-bold tracking-tight">Software Wallets</h3>
        </div>
      </div>
      <div v-if="softwareWallets.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="wallet in softwareWallets"
          :key="wallet.id"
          class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm relative overflow-hidden hover:shadow-md transition-shadow"
        >
          <!-- Top gradient bar -->
          <div class="absolute top-0 left-0 w-full h-1 cta-gradient"></div>
          <div class="flex justify-between items-start mb-6">
            <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-2xl">
              {{ getWalletIcon(wallet) }}
            </div>
            <span class="px-3 py-1 bg-tertiary-container/20 text-on-tertiary-container text-[10px] font-bold uppercase tracking-wider rounded-full">Active</span>
          </div>
          <h4 class="font-headline text-lg font-bold mb-1">{{ wallet.label }}</h4>
          <p class="text-xs text-on-surface-variant mb-4 font-mono">{{ wallet.displayIdentifier }}</p>
          <div class="text-xs text-on-surface-variant mb-6">
            {{ wallet.chain ? getChainConfig(wallet.chain).name : 'Multi-chain' }} &middot;
            <span class="font-bold text-on-surface">{{ formatCurrency(wallet.totalReceived) }}</span>
          </div>
          <div class="flex gap-2">
            <button
              @click="toggleStatus(wallet)"
              class="flex-1 bg-surface-container-highest text-on-secondary-container font-semibold py-2.5 rounded-lg text-sm"
            >
              {{ wallet.status === 'paused' ? 'Resume' : 'Disconnect' }}
            </button>
            <button
              @click="deleteWallet(wallet)"
              class="px-3 bg-surface-container-highest text-on-secondary-container rounded-lg hover:bg-error-container hover:text-on-error-container transition-colors"
            >
              <span class="material-symbols-outlined text-sm">more_horiz</span>
            </button>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-8 text-on-surface-variant text-sm">No software wallets connected</div>
    </section>

    <!-- Hardware / XPub Wallets Section -->
    <section v-if="xpubWallets.length || activeFilter === 'all' || activeFilter === 'xpub'" class="mb-16">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 items-start">
        <div class="lg:col-span-1 py-4">
          <div class="p-3 bg-primary-container/20 w-fit rounded-xl mb-6">
            <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">security</span>
          </div>
          <h3 class="font-headline text-2xl font-black tracking-tight mb-3">Hardware Isolation</h3>
          <p class="text-sm text-on-surface-variant leading-relaxed mb-6">Connect your cold storage devices for high-value asset tracking and premium verification stamps.</p>
          <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 text-xs font-bold text-tertiary">
              <span class="material-symbols-outlined text-sm">verified_user</span> 256-bit AES Encryption
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-tertiary">
              <span class="material-symbols-outlined text-sm">verified_user</span> Air-gapped Signing Support
            </div>
          </div>
        </div>
        <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Existing xpub wallets -->
          <div
            v-for="wallet in xpubWallets"
            :key="wallet.id"
            class="bg-on-surface text-surface p-8 rounded-2xl shadow-xl flex flex-col justify-between min-h-[240px] relative overflow-hidden"
          >
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary/20 rounded-full blur-3xl"></div>
            <div>
              <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-white/10 rounded flex items-center justify-center">
                  <span class="material-symbols-outlined text-white">developer_board</span>
                </div>
                <span class="text-xs font-bold tracking-widest uppercase text-surface-dim">{{ wallet.label }}</span>
              </div>
              <h4 class="text-3xl font-headline font-extrabold mb-2">Vault</h4>
              <p class="text-surface-dim text-sm font-mono opacity-60">{{ wallet.displayIdentifier }}</p>
            </div>
            <div class="flex items-center justify-between mt-8">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-tertiary-container rounded-full shadow-[0_0_8px_rgba(48,200,143,0.8)]"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Secured</span>
              </div>
              <button @click="toggleStatus(wallet)" class="text-primary-fixed-dim font-bold text-sm flex items-center gap-2">
                Manage <span class="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
          <!-- Add new hardware wallet -->
          <button
            @click="selectTypeAndOpen('xpub')"
            class="bg-surface-container-high p-8 rounded-2xl border-2 border-dashed border-outline-variant flex flex-col items-center justify-center text-center group cursor-pointer hover:border-primary/40 transition-all min-h-[240px]"
          >
            <div class="w-16 h-16 rounded-full bg-surface flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
              <span class="material-symbols-outlined text-4xl text-outline group-hover:text-primary">usb</span>
            </div>
            <h4 class="font-headline text-lg font-bold mb-1">Add Hardware Device</h4>
            <p class="text-xs text-on-surface-variant max-w-[200px]">Link your Ledger, Trezor, or any BIP-32 compatible device.</p>
            <span class="mt-6 font-bold text-sm text-primary group-hover:underline">Setup Hardware Bridge</span>
          </button>
        </div>
      </div>
    </section>

    <!-- Empty State (when nothing at all) -->
    <div v-if="!wallets.length" class="border-2 border-dashed border-outline-variant/30 bg-surface rounded-xl p-12 text-center">
      <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 mb-4">account_balance_wallet</span>
      <div class="text-sm font-semibold text-on-surface mb-1">No wallets connected yet</div>
      <div class="text-xs text-on-surface-variant mb-4">Connect your first wallet to start tracking transactions and generating invoices.</div>
      <button
        @click="openConnectModal"
        class="inline-flex items-center gap-2 cta-gradient text-white font-bold px-5 py-2.5 rounded-lg text-sm shadow-lg shadow-primary/10 active:scale-95 transition-all"
      >
        <span class="material-symbols-outlined text-sm">add</span>
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
        <div v-if="showConnectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/20 backdrop-blur-sm" @click.self="closeModal">
          <div class="bg-surface-container-lowest border border-outline-variant/10 rounded-xl w-full max-w-2xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/10 bg-surface sticky top-0 z-10">
              <div class="text-sm font-bold text-on-surface flex items-center gap-2 font-headline">
                <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                {{ modalStep === 1 ? 'Connect Wallet' : `Connect ${getWalletTypeLabel(form.type)}` }}
              </div>
              <div class="flex items-center gap-2">
                <button v-if="modalStep === 2" @click="modalStep = 1" class="px-3 py-1.5 rounded-lg text-xs font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">arrow_back</span> Back
                </button>
                <button @click="closeModal" class="p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors">
                  <span class="material-symbols-outlined">close</span>
                </button>
              </div>
            </div>

            <!-- Step 1: Choose Type -->
            <div v-if="modalStep === 1" class="p-6">
              <div class="text-xs text-on-surface-variant mb-4 font-medium">Choose how you want to connect:</div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                  v-for="option in connectionTypes"
                  :key="option.type"
                  @click="selectType(option.type)"
                  class="p-5 border border-outline-variant/10 rounded-xl text-left hover:border-primary/30 hover:bg-surface-container-low transition-all group/opt"
                >
                  <div class="text-2xl mb-2">{{ option.icon }}</div>
                  <div class="text-sm font-bold text-on-surface group-hover/opt:text-primary transition-colors">{{ option.title }}</div>
                  <div class="text-xs text-on-surface-variant mt-1 leading-relaxed">{{ option.description }}</div>
                  <div class="text-[10px] text-on-surface-variant/60 mt-2 font-medium">{{ option.examples }}</div>
                </button>
              </div>
            </div>

            <!-- Step 2: Type-Specific Form -->
            <div v-if="modalStep === 2" class="p-6 space-y-5">
              <!-- EXCHANGE FORM -->
              <template v-if="form.type === 'exchange'">
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Exchange</label>
                  <div class="grid grid-cols-3 gap-2">
                    <button
                      v-for="ex in walletProviders.exchanges"
                      :key="ex.id"
                      @click="form.provider = ex.id"
                      class="p-3 border rounded-lg text-center transition-all text-xs font-semibold"
                      :class="form.provider === ex.id
                        ? 'border-primary bg-primary/10 text-primary'
                        : 'border-outline-variant/10 text-on-surface-variant hover:border-primary/30'"
                    >
                      <div class="text-lg mb-1">{{ ex.icon }}</div>
                      {{ ex.name }}
                    </button>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. My Binance Account" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">API Key <span class="normal-case text-on-surface-variant/50 font-normal">(read-only recommended)</span></label>
                  <input v-model="form.api_key" type="password" placeholder="Enter your API key" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm font-mono text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">API Secret</label>
                  <input v-model="form.api_secret" type="password" placeholder="Enter your API secret" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm font-mono text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div v-if="selectedExchangeRequiresPassphrase">
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">API Passphrase</label>
                  <input v-model="form.api_passphrase" type="password" placeholder="Enter your API passphrase" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm font-mono text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div class="flex items-center gap-2 p-3 bg-primary-container/10 border border-primary-container/20 rounded-lg">
                  <span class="material-symbols-outlined text-primary text-sm">warning</span>
                  <span class="text-xs text-primary/80">Use read-only API keys. We only need to view balances and transactions.</span>
                </div>
              </template>

              <!-- SOFTWARE WALLET FORM -->
              <template v-if="form.type === 'software'">
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Wallet App</label>
                  <div class="grid grid-cols-3 gap-2">
                    <button
                      v-for="sw in walletProviders.software"
                      :key="sw.id"
                      @click="form.provider = sw.id"
                      class="p-3 border rounded-lg text-center transition-all text-xs font-semibold"
                      :class="form.provider === sw.id
                        ? 'border-primary bg-primary/10 text-primary'
                        : 'border-outline-variant/10 text-on-surface-variant hover:border-primary/30'"
                    >
                      <div class="text-lg mb-1">{{ sw.icon }}</div>
                      {{ sw.name }}
                    </button>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Blockchain</label>
                  <select v-model="form.chain_id" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all cursor-pointer">
                    <option value="" disabled>Select chain...</option>
                    <option v-for="chain in availableChains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Wallet Address</label>
                  <input v-model="form.address" type="text" placeholder="0x... or bc1... or So1..." class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm font-mono text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. My MetaMask" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
              </template>

              <!-- XPUB FORM -->
              <template v-if="form.type === 'xpub'">
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. Ledger Nano X" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Blockchain</label>
                  <select v-model="form.chain_id" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all cursor-pointer">
                    <option value="" disabled>Select chain...</option>
                    <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Extended Public Key</label>
                  <textarea v-model="form.xpub_key" rows="3" placeholder="xpub6CUGRUonZSQ4TWtTMmzXdrXDtypWKi..." class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 font-mono text-xs text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all resize-none"></textarea>
                  <div class="flex items-center gap-2 mt-2">
                    <div class="w-2 h-2 rounded-full" :class="xpubValid ? 'bg-tertiary' : 'bg-on-surface-variant/30'"></div>
                    <span class="text-[11px] font-medium" :class="xpubValid ? 'text-tertiary' : 'text-on-surface-variant'">
                      {{ form.xpub_key.length > 0 ? (xpubValid ? 'Valid format' : 'Invalid format') : 'Awaiting input...' }}
                    </span>
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Derivation Path</label>
                  <input v-model="form.derivation_path" type="text" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm font-mono text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
              </template>

              <!-- MANUAL ADDRESS FORM -->
              <template v-if="form.type === 'manual'">
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Label</label>
                  <input v-model="form.label" type="text" placeholder="e.g. Trading Wallet" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Blockchain</label>
                  <select v-model="form.chain_id" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all cursor-pointer">
                    <option value="" disabled>Select chain...</option>
                    <option v-for="chain in chains" :key="chain.id" :value="chain.id">{{ chain.icon }} {{ chain.name }} ({{ chain.symbol }})</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-on-surface-variant mb-2 uppercase tracking-wider">Wallet Address</label>
                  <input v-model="form.address" type="text" placeholder="Paste any blockchain address" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm font-mono text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                </div>
              </template>
            </div>

            <!-- Modal Footer (Step 2 only) -->
            <div v-if="modalStep === 2" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-outline-variant/10 bg-surface sticky bottom-0">
              <button @click="closeModal" class="px-5 py-2.5 rounded-lg bg-surface-container-highest text-on-secondary-container text-sm font-semibold hover:bg-surface-dim transition-colors">Cancel</button>
              <button
                @click="submitWallet"
                :disabled="!isFormValid || form.processing"
                class="px-5 py-2.5 rounded-lg cta-gradient text-white text-sm font-bold shadow-lg shadow-primary/10 hover:shadow-xl transition-all disabled:opacity-30 disabled:cursor-not-allowed flex items-center gap-2 active:scale-95"
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

// Filtered wallet groups
const exchangeWallets = computed(() => {
  const list = wallets.value.filter(w => w.type === 'exchange');
  return activeFilter.value === 'all' || activeFilter.value === 'exchange' ? list : [];
});
const softwareWallets = computed(() => {
  const list = wallets.value.filter(w => w.type === 'software' || w.type === 'manual');
  return activeFilter.value === 'all' || activeFilter.value === 'software' || activeFilter.value === 'manual' ? list : [];
});
const xpubWallets = computed(() => {
  const list = wallets.value.filter(w => w.type === 'xpub');
  return activeFilter.value === 'all' || activeFilter.value === 'xpub' ? list : [];
});

const filterTabs = computed(() => [
  { label: 'All', value: 'all', count: wallets.value.length },
  { label: 'Exchanges', value: 'exchange', count: wallets.value.filter(w => w.type === 'exchange').length },
  { label: 'Software', value: 'software', count: wallets.value.filter(w => w.type === 'software').length },
  { label: 'Hardware', value: 'xpub', count: wallets.value.filter(w => w.type === 'xpub').length },
]);

const connectionTypes = [
  { type: 'exchange', icon: '🏦', title: 'Exchange (API Key)', description: 'Connect via read-only API for automatic balance and transaction tracking.', examples: 'Binance, Coinbase, Kraken, KuCoin, Bybit, OKX' },
  { type: 'software', icon: '📱', title: 'Software Wallet', description: 'Link by wallet address. We just watch — your keys stay with you.', examples: 'MetaMask, Trust Wallet, Phantom, Rabby, Rainbow' },
  { type: 'xpub', icon: '🔑', title: 'Extended Public Key', description: 'Hardware wallets and watch-only via xpub/ypub/zpub.', examples: 'Ledger, Trezor, Coldcard, any BIP-32 key' },
  { type: 'manual', icon: '✏️', title: 'Manual Address', description: 'Paste any blockchain address to monitor for incoming transactions.', examples: 'Any valid address on any supported chain' },
];

const form = useForm({
  type: '', provider: '', chain_id: '', label: '', address: '',
  xpub_key: '', derivation_path: "m/84'/0'/0'/0/0",
  api_key: '', api_secret: '', api_passphrase: '',
});

const selectedExchangeRequiresPassphrase = computed(() => {
  const ex = walletProviders.exchanges.find(e => e.id === form.provider);
  return ex?.requiresPassphrase || false;
});

const availableChains = computed(() => {
  if (form.type === 'software' && form.provider) {
    const sw = walletProviders.software.find(s => s.id === form.provider);
    if (sw?.chains) return chains.filter(c => sw.chains.includes(c.id));
  }
  return chains;
});

const xpubValid = computed(() => {
  return form.xpub_key.length > 20 && (
    form.xpub_key.startsWith('xpub') || form.xpub_key.startsWith('tpub') ||
    form.xpub_key.startsWith('ypub') || form.xpub_key.startsWith('zpub')
  );
});

const isFormValid = computed(() => {
  if (!form.label) return false;
  switch (form.type) {
    case 'exchange': return form.provider && form.api_key && form.api_secret;
    case 'software': return form.provider && form.chain_id && form.address;
    case 'xpub': return form.chain_id && xpubValid.value && form.derivation_path;
    case 'manual': return form.chain_id && form.address;
    default: return false;
  }
});

const getWalletIcon = (wallet) => {
  if (wallet.type === 'exchange') return getProviderConfig('exchange', wallet.provider).icon;
  if (wallet.type === 'software') return getProviderConfig('software', wallet.provider).icon;
  if (wallet.type === 'xpub') return '🔑';
  return '📋';
};

const openConnectModal = () => { showConnectModal.value = true; modalStep.value = 1; form.reset(); };
const closeModal = () => { showConnectModal.value = false; modalStep.value = 1; form.reset(); };
const selectType = (type) => {
  form.type = type;
  form.provider = type === 'manual' ? 'custom' : '';
  form.label = ''; form.chain_id = ''; form.address = ''; form.xpub_key = '';
  form.derivation_path = "m/84'/0'/0'/0/0"; form.api_key = ''; form.api_secret = ''; form.api_passphrase = '';
  modalStep.value = 2;
};
const selectTypeAndOpen = (type) => { openConnectModal(); selectType(type); };

const submitWallet = () => {
  if (!isFormValid.value) return;
  form.post(route('dashboard.wallets.store'), {
    onSuccess: () => { closeModal(); addNotification('Wallet Connected', `${getWalletTypeLabel(form.type)} has been linked.`, 'success', 4000); }
  });
};

const toggleStatus = (wallet) => {
  const newStatus = wallet.status === 'paused' ? 'watching' : 'paused';
  router.patch(route('dashboard.wallets.update', wallet.id), { status: newStatus }, {
    preserveScroll: true,
    onSuccess: () => { addNotification('Status Updated', `Wallet is now ${newStatus}.`, 'success', 3000); }
  });
};

const deleteWallet = (wallet) => {
  if (confirm('Disconnect this wallet? Transaction history will be preserved.')) {
    router.delete(route('dashboard.wallets.destroy', wallet.id), {
      preserveScroll: true,
      onSuccess: () => { addNotification('Wallet Disconnected', 'Wallet has been removed.', 'success', 3000); }
    });
  }
};
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
