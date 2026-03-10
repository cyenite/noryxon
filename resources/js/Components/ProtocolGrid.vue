<template>
  <section class="py-32 bg-void relative overflow-hidden border-b border-ledger-border">
    <!-- Grid lines -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--theme-ledger-border)_1px,transparent_1px),linear-gradient(to_bottom,var(--theme-ledger-border)_1px,transparent_1px)] bg-[size:100px_100px] [mask-image:radial-gradient(ellipse_80%_80%_at_50%_0%,#000_10%,transparent_100%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="mb-20">
        <div class="inline-flex items-center gap-2 mb-4">
          <span class="w-1.5 h-1.5 bg-pulse animate-pulse"></span>
          <span class="font-mono text-xs text-pulse uppercase tracking-widest">System_Architecture</span>
        </div>
        <h2 class="text-4xl md:text-5xl font-black uppercase text-text-primary tracking-tighter drop-shadow-lg">The Protocol Engine</h2>
        <div class="h-1 w-32 bg-pulse mt-8 shadow-[0_0_15px] shadow-pulse"></div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
        <!-- Decoration lines behind grid -->
        <div class="absolute top-1/2 left-0 right-0 h-px bg-ledger-border/50 hidden md:block z-0 pointer-events-none"></div>

        <!-- Feature 1 -->
        <div class="group relative bg-void border border-ledger-border p-8 transition-all duration-300 hover:border-pulse/80 hover:bg-ledger hover:-translate-y-2 hover:shadow-2xl hover:shadow-pulse/10 z-10">
          <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-pulse to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
          
          <div class="flex justify-between items-start mb-8">
            <div class="w-14 h-14 border border-ledger-border flex items-center justify-center text-node group-hover:border-pulse group-hover:bg-pulse/10 group-hover:text-pulse transition-all duration-300 shadow-[inset_0_0_10px_var(--theme-ledger-border)]">
              <!-- Brutalist padlock -->
              <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <div class="font-mono text-[10px] text-text-muted group-hover:text-pulse transition-colors text-right">
              ID: 0x01<br>
              <span class="opacity-0 group-hover:opacity-100 transition-opacity">{{ hash1 }}</span>
            </div>
          </div>

          <h3 class="text-2xl font-black uppercase tracking-widest mb-4 group-hover:text-pulse transition-colors" @mouseenter="decodeText($event, 'Never Hand Over Keys')">Never Hand Over Keys</h3>
          
          <p class="text-text-muted text-sm leading-relaxed font-mono">
            Your funds go directly from the user's wallet to your hardware ledger. We never hold your crypto, meaning zero counterparty risk and instant access to revenue.
          </p>
          
          <div class="mt-8 font-mono text-xs text-text-muted border-t border-ledger-border pt-4 group-hover:border-pulse/30 transition-colors">
            > SECURE_ENCLAVE: <span class="text-text-primary group-hover:text-pulse transition-colors">ACTIVE</span>
          </div>
        </div>

        <!-- Feature 2 -->
        <div class="group relative bg-void border border-ledger-border p-8 transition-all duration-300 hover:border-pulse/80 hover:bg-ledger hover:-translate-y-2 hover:shadow-2xl hover:shadow-pulse/10 z-10">
          <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-pulse to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 delay-75"></div>
          
          <div class="flex justify-between items-start mb-8">
            <div class="w-14 h-14 border border-ledger-border flex items-center justify-center text-node group-hover:border-pulse group-hover:bg-pulse/10 group-hover:text-pulse transition-all duration-300 shadow-[inset_0_0_10px_var(--theme-ledger-border)]">
              <!-- Brutalist network graph -->
              <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <div class="font-mono text-[10px] text-text-muted group-hover:text-pulse transition-colors text-right">
              ID: 0x02<br>
              <span class="opacity-0 group-hover:opacity-100 transition-opacity">{{ hash2 }}</span>
            </div>
          </div>

          <h3 class="text-2xl font-black uppercase tracking-widest mb-4 group-hover:text-pulse transition-colors" @mouseenter="decodeText($event, '99.99% Uptime SLA')">99.99% Uptime SLA</h3>
          
          <p class="text-text-muted text-sm leading-relaxed font-mono">
            Our enterprise-grade infrastructure guarantees your platform stays online. Webhooks fire instantly upon payment detection, letting you deliver products without delay.
          </p>

          <div class="mt-8 font-mono text-xs text-text-muted border-t border-ledger-border pt-4 group-hover:border-pulse/30 transition-colors">
            > LATENCY: <span class="text-text-primary group-hover:text-pulse transition-colors">~45ms</span>
          </div>
        </div>

        <!-- Feature 3 -->
        <div class="group relative bg-void border border-ledger-border p-8 transition-all duration-300 hover:border-pulse/80 hover:bg-ledger hover:-translate-y-2 hover:shadow-2xl hover:shadow-pulse/10 z-10">
          <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-pulse to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 delay-150"></div>
          
          <div class="flex justify-between items-start mb-8">
            <div class="w-14 h-14 border border-ledger-border flex items-center justify-center text-node group-hover:border-pulse group-hover:bg-pulse/10 group-hover:text-pulse transition-all duration-300 shadow-[inset_0_0_10px_var(--theme-ledger-border)]">
              <!-- Brutalist global distribution -->
              <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div class="font-mono text-[10px] text-text-muted group-hover:text-pulse transition-colors text-right">
              ID: 0x03<br>
              <span class="opacity-0 group-hover:opacity-100 transition-opacity">{{ hash3 }}</span>
            </div>
          </div>

          <h3 class="text-2xl font-black uppercase tracking-widest mb-4 group-hover:text-pulse transition-colors" @mouseenter="decodeText($event, 'Accept 5,000+ Assets')">Accept 5,000+ Assets</h3>
          
          <p class="text-text-muted text-sm leading-relaxed font-mono">
            Accept payments in Bitcoin, Ethereum, Solana, and thousands of ERC-20 tokens. No wrapped assets or complex bridging required for your users.
          </p>

          <div class="mt-8 font-mono text-xs text-text-muted border-t border-ledger-border pt-4 group-hover:border-pulse/30 transition-colors">
            > NETWORKS: <span class="text-text-primary group-hover:text-pulse transition-colors">12 SYNCED</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const generateHash = () => {
    return Array.from({length: 8}, () => Math.floor(Math.random() * 16).toString(16)).join('');
};

const hash1 = ref(generateHash());
const hash2 = ref(generateHash());
const hash3 = ref(generateHash());

onMounted(() => {
    setInterval(() => hash1.value = generateHash(), 2000);
    setInterval(() => hash2.value = generateHash(), 2100);
    setInterval(() => hash3.value = generateHash(), 2200);
});

const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";

const decodeText = (event, originalText) => {
    let iteration = 0;
    const el = event.target;
    
    clearInterval(el.dataset.interval);
    
    el.dataset.interval = setInterval(() => {
        el.innerText = originalText
            .split("")
            .map((letter, index) => {
                if(index < iteration) return originalText[index];
                return letters[Math.floor(Math.random() * 48)];
            })
            .join("");
            
        if(iteration >= originalText.length){ 
            clearInterval(el.dataset.interval);
        }
        
        iteration += 1 / 2;
    }, 30);
};
</script>
