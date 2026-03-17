<template>
  <section class="py-24 border-b border-ledger-border bg-void relative overflow-hidden">
    <!-- Noise overlay -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="mb-16 text-center">
        <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-text-primary drop-shadow-lg">Watch It Happen. Live.</h2>
        <p class="text-pulse font-mono mt-4 text-sm tracking-widest uppercase">one_api_call // zero_excuses</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch relative">
        
        <!-- Connecting Line between the two sections -->
        <div class="hidden lg:block absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-px bg-pulse z-0 shadow-[0_0_10px] shadow-pulse"></div>

        <!-- Terminal Side -->
        <div class="bg-ledger border border-ledger-border p-1 shadow-2xl flex flex-col min-h-[450px] relative group">
          <div class="absolute inset-0 border border-pulse/30 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
          
          <div class="bg-ledger-light p-2 flex items-center justify-between border-b border-ledger-border">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-ledger-border rounded-full flex items-center justify-center"><div class="w-1 h-1 bg-void rounded-full"></div></div>
              <div class="w-3 h-3 bg-ledger-border rounded-full flex items-center justify-center"><div class="w-1 h-1 bg-void rounded-full"></div></div>
              <div class="w-3 h-3 bg-ledger-border rounded-full flex items-center justify-center"><div class="w-1 h-1 bg-void rounded-full"></div></div>
            </div>
            <span class="font-mono text-[10px] text-text-muted tracking-widest uppercase">root@noryxon-node:~</span>
          </div>
          
          <div class="font-mono text-sm text-text-primary flex-1 overflow-x-auto p-6 relative">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(0,255,163,0.03)_1px,transparent_1px)] bg-[size:100%_4px] pointer-events-none"></div>
            
            <div class="relative z-10">
              <p class="mb-4 text-text-muted"># CREATE_INVOICE_FOR_TRANSACTION</p>
              <p><span class="text-node font-bold">curl</span> -X POST https://api.noryxon.com/v1/invoices \</p>
              <p class="ml-4">-H <span class="text-pulse/90">"Authorization: Bearer sk_live_7x9a..."</span> \</p>
              <p class="ml-4">-H <span class="text-pulse/90">"Content-Type: application/json"</span> \</p>
              <p class="ml-4">-d '{</p>
              <p class="ml-8 text-text-primary/90">"amount": "{{ currentAmount }}",</p>
              <p class="ml-8 text-text-primary/90">"currency": "{{ currentCurrency }}",</p>
              <p class="ml-8 text-text-primary/90">"chain": "{{ currentChain }}",</p>
              <p class="ml-8 text-text-primary/90">"payer": "client@example.com"</p>
              <p class="ml-4">}'</p>
              
              <div class="mt-8 border-t border-dashed border-ledger-border pt-4">
                <div v-if="!isSimulating && !isComplete" class="text-pulse animate-pulse">
                  <span class="mr-2">_</span>READY_TO_GENERATE_INVOICE
                </div>
                
                <div v-if="isSimulating" class="text-node">
                  <p v-for="(log, idx) in simLogs" :key="idx" class="mb-1 opacity-80">> {{ log }}</p>
                  <p class="animate-pulse">> _</p>
                </div>
                
                <div v-if="isComplete" class="text-pulse text-xs leading-relaxed">
                   > HTTP/1.1 200 OK<br>
                   > <span class="text-text-primary">{</span><br>
                   > &nbsp;&nbsp;"id": "{{ currentSessionId }}",<br>
                   > &nbsp;&nbsp;"amount": "{{ currentAmount }} {{ currentCurrency }}",<br>
                   > &nbsp;&nbsp;"status": "invoice_generated",<br>
                   > &nbsp;&nbsp;"tax_report": "dat_compliant"<br>
                   > <span class="text-text-primary">}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Hosted Checkout Replica -->
        <div class="bg-void border border-ledger-border p-1 flex flex-col justify-center items-center relative min-h-[450px] shadow-2xl group">
          <!-- Scanning beam -->
          <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="w-full h-1 bg-node/40 shadow-[0_0_15px] shadow-node translate-y-[-10px] group-hover:animate-[scan_2s_ease-in-out_infinite]"></div>
          </div>
          
          <div class="absolute top-4 right-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full" :class="isComplete ? 'bg-pulse shadow-[0_0_8px] shadow-pulse' : 'bg-node shadow-[0_0_8px] shadow-node animate-pulse'"></span>
            <span class="font-mono text-[10px] tracking-widest text-text-muted">SESSION_ACTIVE</span>
          </div>
          
          <div class="text-center mt-8 z-10 w-full px-8">
            <h3 class="text-2xl font-black uppercase tracking-widest mb-1 drop-shadow-[2px_2px_0_var(--theme-pulse)] opacity-80" :style="isComplete ? '' : 'filter: drop-shadow(2px 2px 0px var(--theme-pulse)); opacity: 1;'">
              {{ isComplete ? 'Invoice Generated' : 'Awaiting Transaction' }}
            </h3>
            <p class="font-mono text-text-muted mb-8 text-xs border-b border-ledger-border pb-4 w-full">TRANSFER_EXPECTED: {{ currentAmount }} {{ currentCurrency }} ({{ currentChain.toUpperCase() }})</p>
            
            <div class="relative w-48 h-48 mx-auto mb-8 bg-void border border-ledger-border flex items-center justify-center p-2 group-hover:border-node/50 transition-colors">
              <!-- Simulated QR with cypherpunk styling -->
              <div class="absolute inset-2 border border-dotted border-text-muted/30"></div>
              <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-text-primary"></div>
              <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-text-primary"></div>
              <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-text-primary"></div>
              <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-text-primary"></div>
              
              <div class="w-full h-full flex flex-col items-center justify-center opacity-70 transition-opacity" :class="{'opacity-10 blur-sm': isComplete}">
                <div class="grid grid-cols-4 gap-1 w-24 h-24 opacity-80">
                  <div v-for="i in 16" :key="i" class="bg-text-primary" :class="{'opacity-0': Math.random() > 0.6}"></div>
                </div>
              </div>

              <!-- Success Checkmark Overlay over QR block -->
              <div v-if="isComplete" class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 bg-pulse text-void rounded-full flex items-center justify-center animate-[popIn_0.5s_cubic-bezier(0.175,0.885,0.32,1.275)] shadow-[0_0_30px] shadow-pulse">
                  <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
            </div>
            
            <div class="font-mono bg-ledger-light border border-ledger-border px-4 py-3 w-full max-w-sm mx-auto truncate text-text-primary/90 text-xs tracking-wider relative overflow-hidden group/addr">
              <div class="absolute inset-0 bg-pulse/10 translate-y-full group-hover/addr:translate-y-0 transition-transform"></div>
              <span class="relative z-10">{{ isComplete ? 'INVOICE_DOCUMENTED' : currentAddress }}</span>
            </div>
            
            <div class="mt-8 mx-auto font-mono text-[10px] uppercase font-bold px-6 py-3 tracking-widest text-center border" :class="isComplete ? 'border-node text-node shadow-[0_0_15px] shadow-node/30' : 'border-pulse/50 text-pulse/80'">
              {{ isComplete ? '[ INVOICE_COMPLETE ]' : (isSimulating ? '[ VERIFYING_TRANSACTION ]' : '[ REALTIME_MONITORING_ACTIVE ]') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isSimulating = ref(false);
const isComplete = ref(false);
const simLogs = ref([]);

const currentAmount = ref('1250.00');
const currentCurrency = ref('USDC');
const currentChain = ref('ethereum');
const currentSessionId = ref('pay_982x7a');
const currentAddress = ref('0x7A250d5630B4cF539739dF2C5dAcb4c659F2488D');

let simInterval = null;
let resetTimeout = null;
let mainLoopInfo = null;

const protocols = ['ethereum', 'solana', 'polygon', 'arbitrum', 'optimism', 'bitcoin'];
const tokens = ['USDC', 'USDT', 'ETH', 'BTC', 'DAI', 'SOL'];
const hexChars = '0123456789aAbBcCdDeEfF';

const generateMockAddress = () => '0x' + Array.from({length: 40}, () => hexChars[Math.floor(Math.random() * 22)]).join('');
const generateMockId = () => 'inv_' + Math.random().toString(36).substring(2, 8);

const runSimulation = () => {
  if (isSimulating.value) return;
  
  currentChain.value = protocols[Math.floor(Math.random() * protocols.length)];
  currentCurrency.value = tokens[Math.floor(Math.random() * tokens.length)];
  currentAmount.value = (Math.random() * 8500 + 50).toFixed(2);
  currentSessionId.value = generateMockId();
  currentAddress.value = generateMockAddress();
  
  isSimulating.value = true;
  isComplete.value = false;
  simLogs.value = [];
  
  const logs = [
    'Connecting to Noryxon Invoice Engine...',
    'Verifying on-chain transaction...',
    `POST /v1/invoices (Payload: 112 bytes)`,
    `DETECTED: ${currentAmount.value} ${currentCurrency.value} on ${currentChain.value}...`,
    'Generating compliant invoice with payer details...',
    'Computing Digital Asset Tax documentation...',
    '200 OK - Invoice generated. Tax report ready.'
  ];
  
  clearInterval(simInterval);
  clearTimeout(resetTimeout);
  
  let i = 0;
  simInterval = setInterval(() => {
    if (i < logs.length) {
      simLogs.value.push(logs[i]);
      i++;
    } else {
      clearInterval(simInterval);
      resetTimeout = setTimeout(() => {
        isSimulating.value = false;
        isComplete.value = true;
      }, 400);
    }
  }, 400);
};

const loopSim = () => {
  runSimulation();
  
  setTimeout(() => {
     isComplete.value = false;
     simLogs.value = [];
     mainLoopInfo = setTimeout(loopSim, Math.random() * 5000 + 2000);
  }, 5500);
};

onMounted(() => {
  mainLoopInfo = setTimeout(loopSim, 1500);
});

onUnmounted(() => {
  clearInterval(simInterval);
  clearTimeout(resetTimeout);
  clearTimeout(mainLoopInfo);
});
</script>

<style>
@keyframes popIn {
  0% { transform: scale(0); opacity: 0; }
  80% { transform: scale(1.1); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}
</style>
