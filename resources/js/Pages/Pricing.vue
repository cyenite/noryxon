<template>
  <Head title="Pricing - Noryxon" />
  <div class="min-h-screen font-sans bg-void text-text-primary">
    
    <!-- Navigation -->
    <header class="border-b border-ledger-border bg-ledger/80 backdrop-blur-md fixed top-0 w-full z-50">
      <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <Link href="/" class="font-black tracking-tighter text-2xl uppercase flex items-center hover:text-pulse transition-colors">
          <div class="w-6 h-6 border-2 border-pulse mr-2 flex items-center justify-center">
            <div class="w-1.5 h-1.5 bg-pulse"></div>
          </div>
          NORYXON
        </Link>
        <nav class="hidden md:flex items-center gap-8 font-mono text-xs tracking-wider text-text-muted">
          <Link href="/pricing" class="text-pulse transition-colors font-bold">PRICING</Link>
          <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">DOCUMENTATION</a>
          <a href="https://dev.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">DEVELOPER_PORTAL</a>
          <a href="https://app.noryxon.com/login" class="hover:text-pulse transition-colors">DASHBOARD_LOGIN</a>
          <a href="https://app.noryxon.com/register" class="text-void bg-pulse px-4 py-2 font-bold hover:shadow-[0_0_15px_#00ffa3] transition-all">GET_STARTED</a>
        </nav>
      </div>
    </header>

    <main class="pt-32 pb-24 max-w-7xl mx-auto px-6">
      
      <!-- Pricing Header -->
      <div class="mb-20 text-center">
        <div class="inline-flex items-center gap-2 mb-4 justify-center">
          <svg class="w-4 h-4 text-node" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          <span class="font-mono text-xs text-node uppercase tracking-widest">TRANSACTION_COSTS</span>
        </div>
        <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tighter drop-shadow-[0_0_15px_rgba(255,255,255,0.1)]">
          Predictable Settlement.
        </h1>
        <p class="text-text-muted font-mono mt-6 max-w-2xl mx-auto text-sm md:text-base">
          No setup fees. No recurring monthly subscriptions. Zero withdrawal costs because you hold your own keys. 
          Focus on building; we'll handle the infrastructure.
        </p>
      </div>

      <!-- Pricing Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-20">
        <!-- Developer Tier -->
        <div class="bg-[#050505] border border-ledger-border p-1 relative shadow-[0_0_30px_rgba(0,0,0,1)] hover:border-node transition-colors duration-500">
          <div class="border border-ledger-border bg-[#0a0a0a] p-8 h-full flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-black uppercase tracking-widest text-text-primary">Developer</h3>
                <span class="bg-void border border-ledger-border text-text-muted px-2 py-1 font-mono text-[10px]">PAY_AS_YOU_GO</span>
              </div>
              
              <div class="mb-8 border-b border-ledger-border pb-8">
                <div class="flex items-baseline gap-2 mb-2">
                  <span class="text-4xl font-black text-white">Free</span>
                  <span class="text-text-muted font-mono text-xs uppercase tracking-widest">upto $10,000 / mo</span>
                </div>
                <div class="flex items-baseline gap-2">
                  <span class="text-2xl font-black text-white opacity-50">1.0%</span>
                  <span class="text-text-muted font-mono text-[10px] uppercase">/ TXN past threshold</span>
                </div>
                <p class="text-[10px] text-text-muted font-mono mt-3">> ON_SUCCESSFUL_SETTLEMENTS_ONLY</p>
              </div>

              <div class="font-mono text-xs text-text-primary mb-3">CORE FEATURES INCLUDED:</div>
              <ul class="space-y-3 font-mono text-xs text-text-muted mb-12">
                <li class="flex items-center gap-3">
                  <div class="w-1 h-1 bg-node rounded-full"></div>
                  <span>Instant settlement to hardware wallets (XPUB)</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1 h-1 bg-node rounded-full"></div>
                  <span>12+ Blockchains & 5,000+ Assets Supported</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1 h-1 bg-node rounded-full"></div>
                  <span>API Integration & Instant Signed Webhooks</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1 h-1 bg-node rounded-full"></div>
                  <span>Full Testnet Sandbox Access</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1 h-1 bg-node rounded-full"></div>
                  <span>Native Lightning Network (BTC) Capabilities</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1 h-1 bg-node rounded-full"></div>
                  <span>Automated E-commerce Plugins (Shopify, Woo)</span>
                </li>
              </ul>
            </div>

            <a href="https://app.noryxon.com/register" class="block w-full text-center bg-void border border-node text-node font-bold px-6 py-4 uppercase tracking-widest hover:bg-node hover:text-void transition-all shadow-[inset_0_0_10px_rgba(0,209,255,0.2)]">
              [ INITIATE_DEPLOYMENT ]
            </a>
          </div>
        </div>

        <!-- Enterprise Tier -->
        <div class="bg-[#050505] border border-pulse p-1 relative shadow-[0_0_30px_rgba(0,255,163,0.15)] hover:shadow-[0_0_40px_rgba(0,255,163,0.25)] transition-all">
          <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-pulse text-void font-bold text-[10px] px-3 py-1 uppercase tracking-widest z-20">
            RECOMMENDED_FOR_SCALE
          </div>
          <div class="border border-ledger-border bg-[#0a0a0a] p-8 h-full flex flex-col justify-between relative z-10">
            <div>
              <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-black uppercase tracking-widest text-pulse">Node Operator</h3>
                <span class="bg-pulse/10 border border-pulse text-pulse px-2 py-1 font-mono text-[10px] animate-pulse">HIGH_THROUGHPUT</span>
              </div>
              
              <div class="mb-8 border-b border-ledger-border pb-8">
                <div class="flex items-baseline gap-2 mb-2">
                  <span class="text-4xl font-black text-white">Custom</span>
                </div>
                <div class="flex items-baseline gap-2">
                   <span class="text-2xl font-black opacity-0">_</span>
                </div>
                <p class="text-[10px] text-pulse/70 font-mono mt-3">> VOLUME_BASED_TIERING</p>
              </div>

               <div class="font-mono text-xs text-text-primary mb-3">EVERYTHING IN DEVELOPER, PLUS:</div>
              <ul class="space-y-3 font-mono text-xs text-text-muted mb-12">
                <li class="flex items-center gap-3">
                  <div class="w-1.5 h-1.5 border border-pulse"></div>
                  <span class="text-white">Custom transaction fee tiering</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1.5 h-1.5 border border-pulse"></div>
                  <span>Dedicated bare-metal RPC nodes globally</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1.5 h-1.5 border border-pulse"></div>
                  <span>99.99% Guaranteed Setup SLA</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1.5 h-1.5 border border-pulse"></div>
                  <span>Private Slack / Telegram Connect channel</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1.5 h-1.5 border border-pulse"></div>
                  <span>Role-Based Access Control (RBAC) overrides</span>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-1.5 h-1.5 border border-pulse"></div>
                  <span>Priority engineering support</span>
                </li>
              </ul>
            </div>

            <a href="mailto:sales@noryxon.com" class="block w-full text-center bg-pulse text-void font-black px-6 py-4 uppercase tracking-widest hover:bg-white hover:text-void transition-colors shadow-[0_0_15px_rgba(0,255,163,0.4)] hover:shadow-[0_0_25px_rgba(0,255,163,0.6)]">
              [ CONTACT_SYSOPS ]
            </a>
          </div>
        </div>
      </div>

      <!-- Extended Feature Breakdown -->
      <div class="max-w-5xl mx-auto border-t border-ledger-border pt-16 mt-16 pb-12">
        <h2 class="text-3xl font-black uppercase tracking-widest mb-12 text-center text-text-primary">
          Platform Capabilities
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          
          <!-- Category 1 -->
          <div>
            <h4 class="text-pulse font-mono tracking-widest text-sm mb-4 border-b border-ledger-border pb-2">_ZERO CUSTODY ENGINE</h4>
            <ul class="space-y-2 font-mono text-sm text-text-muted">
              <li>- Direct on-chain deposits. No middlemen.</li>
              <li>- BIP-44/49/84 Hardware Wallet Compatibility.</li>
              <li>- Zero withdrawal or pooling fees.</li>
            </ul>
          </div>

          <!-- Category 2 -->
          <div>
            <h4 class="text-node font-mono tracking-widest text-sm mb-4 border-b border-ledger-border pb-2">_MULTI-CHAIN DEPLOYMENTS</h4>
            <ul class="space-y-2 font-mono text-sm text-text-muted">
              <li>- 12+ Native L1s including BTC, ETH, SOL, AVAX.</li>
              <li>- Arbitrum, Optimism, & Base Layer 2 scaling.</li>
              <li>- Any liquid ERC-20, SPL, or TRC-20 token support.</li>
            </ul>
          </div>

          <!-- Category 3 -->
          <div>
            <h4 class="text-node font-mono tracking-widest text-sm mb-4 border-b border-ledger-border pb-2">_DEVELOPER EXPERIENCE</h4>
            <ul class="space-y-2 font-mono text-sm text-text-muted">
              <li>- Idempotent REST API & signed Instant Webhooks.</li>
              <li>- Sandboxed Testnet Environment.</li>
              <li>- Typescript, Go, Python, Node.js, PHP SDKs.</li>
            </ul>
          </div>

          <!-- Category 4 -->
          <div>
            <h4 class="text-pulse font-mono tracking-widest text-sm mb-4 border-b border-ledger-border pb-2">_MERCHANT VAULT DASHBOARD</h4>
            <ul class="space-y-2 font-mono text-sm text-text-muted">
              <li>- Analytics mapping revenue by asset & chain.</li>
              <li>- Live Terminal to watch transaction mempools.</li>
              <li>- Manage subscriptions & recurring billing (crypto-native).</li>
            </ul>
          </div>

        </div>
      </div>

    </main>

    <!-- Footer -->
    <footer class="bg-void border-t border-ledger-border py-8 text-center font-mono text-xs text-text-muted">
      NORYXON BRIDGE PROTOCOL &copy; 2026. ALL EXECUTIONS FINAL.
      <div class="mt-4 flex justify-center gap-6">
         <Link href="/pricing" class="hover:text-pulse transition-colors">Pricing</Link>
         <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">Docs</a>
         <a href="https://dev.noryxon.com" class="hover:text-pulse transition-colors">Dev Portal</a>
         <a href="https://app.noryxon.com" class="hover:text-pulse transition-colors">Dashboard</a>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>
