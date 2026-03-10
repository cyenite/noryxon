<template>
  <section class="relative min-h-[90vh] flex flex-col items-center justify-center overflow-hidden border-b border-ledger-border bg-void">
    <!-- Dynamic grid background with moving light beam -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--theme-ledger-border)_1px,transparent_1px),linear-gradient(to_bottom,var(--theme-ledger-border)_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-pulse/5 to-transparent opacity-50 pointer-events-none"></div>
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-16 pb-16">
      <!-- Left text content -->
      <div class="text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-void font-mono text-xs text-node border border-ledger-border mb-6 select-none relative overflow-hidden group">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-node/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
          <span class="w-1.5 h-1.5 rounded-full bg-pulse animate-pulse shadow-[0_0_8px] shadow-pulse"></span>
          99.99% UPTIME GUARANTEED
        </div>
        
        <h1 class="text-5xl md:text-7xl font-black tracking-tighter text-text-primary mb-6 uppercase leading-[1.1]">
          The Uncompromising <br/> 
          <span class="relative inline-block">
            <span class="absolute -inset-1 blur-xl bg-pulse/20 opacity-50 animate-pulse"></span>
            <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-pulse via-text-primary to-node">On-Chain Bridge.</span>
          </span>
        </h1>
        
        <p class="text-lg md:text-xl text-text-muted max-w-xl mb-8 font-medium leading-relaxed">
          Accept 5,000+ cryptocurrencies seamlessly without intermediaries. Zero custody. Zero fiat constraints. Land payments directly into your cold storage instantly.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-start gap-4">
          <a href="/dashboard" class="w-full sm:w-auto inline-block text-center relative group bg-pulse text-void font-bold px-8 py-4 uppercase tracking-wider overflow-hidden shadow-[0_0_20px] shadow-pulse/30 transition-all hover:shadow-[0_0_40px] hover:shadow-pulse/60 hover:scale-[1.02]">
            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/30 to-white/0 -translate-x-[150%] skew-x-[-45deg] group-hover:animate-[flash_1s_ease-in-out_infinite]"></div>
            Start Accepting Crypto
          </a>
          
          <a href="https://docs.noryxon.com" target="_blank" class="w-full sm:w-auto inline-block text-center relative group bg-void border border-ledger-border text-text-primary font-bold px-8 py-4 uppercase tracking-wider overflow-hidden hover:border-pulse transition-colors">
            <div class="absolute inset-0 bg-ledger/50 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
            <span class="relative z-10 group-hover:text-pulse transition-colors">View Documentation</span>
          </a>
        </div>
      </div>

      <!-- Right: Interactive Platform Simulation -->
      <div class="relative w-full aspect-square md:aspect-video lg:aspect-square max-w-lg mx-auto border border-ledger-border bg-void/80 backdrop-blur-xl p-6 overflow-hidden flex flex-col justify-between group shadow-2xl">
        
        <!-- Scanning line effect over the box -->
        <div class="absolute left-0 right-0 h-1 bg-gradient-to-r from-transparent via-pulse/50 to-transparent -top-1 blur-[1px] opacity-0 group-hover:opacity-100 group-hover:animate-[scan_3s_ease-in-out_infinite]"></div>
        
        <div class="flex justify-between items-center border-b border-ledger-border pb-4 mb-4 relative z-10">
          <div class="font-mono text-xs text-text-muted tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-node" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
            PAYMENT FLOW DEMO
          </div>
          <div class="text-xs font-mono text-pulse/80 px-3 py-1.5 flex items-center gap-2 border border-pulse/30 bg-pulse/5">
            <span class="w-1.5 h-1.5 rounded-full" :class="isSimulating ? 'bg-pulse animate-[ping_1s_cubic-bezier(0,0,0.2,1)_infinite]' : 'bg-text-muted'"></span>
            {{ isSimulating ? '[ ROUTING_FUNDS ]' : '[ SYSTEM_STANDBY ]' }}
          </div>
        </div>

        <div class="flex-1 flex flex-col justify-between relative z-10 pt-4 pb-2">
          
          <!-- Node 1: Customer Wallet -->
          <div class="flex items-center gap-4 transition-all duration-500" :class="{'opacity-100 drop-shadow-lg': step >= 1, 'opacity-40': step < 1}">
            <div class="w-12 h-12 border flex items-center justify-center transition-colors duration-300" :class="step >= 1 ? 'border-text-primary bg-text-primary/10 text-text-primary' : 'border-ledger-border bg-ledger text-text-muted'">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div class="font-mono text-sm">
              <div class="text-text-primary font-bold tracking-wide">User Pays for Product <span v-if="step===1" class="text-xs ml-2 text-pulse animate-pulse">Sending Funds...</span></div>
              <div class="text-xs mt-1 transition-colors" :class="{'text-pulse': step >= 1, 'text-text-muted': step < 1}">
                Customer Wallet: <span :class="{'font-bold': step >= 1}">{{ displayHash }}</span>
              </div>
            </div>
          </div>

          <!-- Vertical connection pipeline -->
          <div class="absolute left-6 top-16 bottom-16 w-px bg-ledger-border">
            <!-- Data packet flowing down -->
            <div v-if="step === 2" class="absolute w-1.5 h-8 bg-pulse -left-[2.5px] rounded-full shadow-[0_0_10px] shadow-pulse animate-[flowDown_1.5s_linear_infinite]"></div>
            <div v-if="step >= 3" class="absolute top-0 bottom-0 left-0 w-full bg-pulse shadow-[0_0_10px] shadow-pulse/50"></div>
          </div>

          <!-- Node 2: Noryxon Bridge -->
          <div class="flex items-center gap-4 pl-16 transition-all duration-500 relative" :class="{'opacity-100 scale-105 transform': step === 2, 'opacity-100': step >= 3, 'opacity-40': step < 2}">
            <div class="w-12 h-12 border flex items-center justify-center relative transition-colors duration-300" :class="step >= 2 ? 'border-pulse bg-pulse/10 text-pulse' : 'border-ledger-border bg-ledger text-text-muted'">
              <!-- Cypherpunk rotating rings -->
              <div v-if="step === 2" class="absolute -inset-2 border border-dashed border-pulse/50 rounded-full animate-[spin_4s_linear_infinite]"></div>
              <div v-if="step === 2" class="absolute -inset-4 border border-dotted border-node/30 rounded-full animate-[spin_3s_linear_infinite_reverse]"></div>
              
              <svg class="w-6 h-6 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <div class="font-mono text-sm relative">
              <div class="font-bold tracking-wide transition-colors" :class="step >= 2 ? 'text-pulse shadow-pulse/50 drop-shadow-md' : 'text-text-muted'">
                Noryxon Gateway (Zero Fees)
              </div>
              <div class="text-xs mt-1" :class="step === 2 ? 'text-node animate-pulse' : 'text-text-muted'">
                {{ step === 2 ? 'Routing Instantly to You >>' : 'Standby' }}
              </div>
            </div>
          </div>

          <!-- Node 3: Cold Storage -->
          <div class="flex items-center gap-4 transition-all duration-500" :class="{'opacity-100 drop-shadow-xl': step >= 3, 'opacity-40': step < 3}">
            <div class="w-12 h-12 border flex items-center justify-center transition-colors duration-300 relative overflow-hidden" :class="step >= 3 ? 'border-node bg-node/10 text-node' : 'border-ledger-border bg-ledger text-text-muted'">
              <div v-if="step === 3" class="absolute inset-0 bg-node/20 animate-ping"></div>
              <svg class="w-6 h-6 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <div class="font-mono text-sm">
              <div class="font-bold tracking-wide transition-colors" :class="step >= 3 ? 'text-node' : 'text-text-primary'">Your Hardware Wallet</div>
              <div class="text-xs mt-1 transition-all duration-300" :class="step >= 3 ? 'text-node font-bold text-[13px]' : 'text-text-muted'">
                {{ step >= 3 ? `+ ${currentAmount} ${currentToken} (NO CUSTODY)` : 'Waiting for funds...' }}
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const step = ref(0);
const isSimulating = ref(false);
const displayHash = ref('awaiting_connection...');
const currentAmount = ref('0.00');
const currentToken = ref('USDC');
let finalHash = 'awaiting...';

let timeoutStep1 = null;
let timeoutStep2 = null;
let timeoutStep3 = null;
let timeoutReset = null;
let mainLoopInfo = null;
let scrambleInterval = null;

const chars = 'ABCDEF0123456789abcdef';
const tokens = ['USDC', 'USDT', 'DAI', 'BTC', 'ETH', 'SOL'];

const generateRandomHash = () => '0x' + Array.from({length: 16}, () => chars[Math.floor(Math.random() * chars.length)]).join('') + '...';

const scrambleHashText = () => {
  let iterations = 0;
  const maxIterations = 20;
  
  clearInterval(scrambleInterval);
  scrambleInterval = setInterval(() => {
    const stringFinal = finalHash || '0xunknown...';
    displayHash.value = stringFinal.split('').map((char, index) => {
      if(index < iterations / 2) return stringFinal[index] || '';
      return chars[Math.floor(Math.random() * chars.length)];
    }).join('');
    
    iterations++;
    if(iterations >= maxIterations) {
      clearInterval(scrambleInterval);
      displayHash.value = finalHash;
    }
  }, 40);
};

const triggerSim = () => {
  if (isSimulating.value) return;
  isSimulating.value = true;
  step.value = 0;
  displayHash.value = 'awaiting_connection...';
  
  currentToken.value = tokens[Math.floor(Math.random() * tokens.length)];
  currentAmount.value = (Math.random() * 9500 + 50).toFixed(2);
  finalHash = generateRandomHash();
  
  clearTimeout(timeoutStep1);
  clearTimeout(timeoutStep2);
  clearTimeout(timeoutStep3);
  clearTimeout(timeoutReset);
  clearInterval(scrambleInterval);
  
  timeoutStep1 = setTimeout(() => {
    step.value = 1;
    scrambleHashText();
  }, 500);
  
  timeoutStep2 = setTimeout(() => {
    step.value = 2;
  }, 1800);
  
  timeoutStep3 = setTimeout(() => {
    step.value = 3;
    timeoutReset = setTimeout(() => {
      isSimulating.value = false;
      step.value = 0;
      displayHash.value = 'awaiting_connection...';
    }, 2500);
  }, 4000);
};

const loopSim = () => {
  triggerSim();
  mainLoopInfo = setTimeout(loopSim, Math.random() * 4000 + 8500);
};

onMounted(() => {
  mainLoopInfo = setTimeout(loopSim, 1000);
});

onUnmounted(() => {
  clearTimeout(timeoutStep1);
  clearTimeout(timeoutStep2);
  clearTimeout(timeoutStep3);
  clearTimeout(timeoutReset);
  clearTimeout(mainLoopInfo);
  clearInterval(scrambleInterval);
});
</script>

<style>
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}
@keyframes flash {
  0%, 100% { transform: translateX(-150%) skewX(-45deg); }
  50% { transform: translateX(150%) skewX(-45deg); }
}
@keyframes scan {
  0% { top: -5%; opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { top: 105%; opacity: 0; }
}
@keyframes flowDown {
  0% { top: 0; opacity: 0; }
  20% { opacity: 1; }
  80% { opacity: 1; }
  100% { top: 100%; opacity: 0; }
}
</style>
