<template>
  <section class="relative min-h-[95vh] flex flex-col items-center justify-center overflow-hidden border-b border-outline-variant/15 bg-surface">
    <!-- Animated grid with perspective -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--color-ledger-border)_1px,transparent_1px),linear-gradient(to_bottom,var(--color-ledger-border)_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_40%,transparent_100%)] opacity-40"></div>

    <!-- Radial glow behind invoice -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/8 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-[40%] w-[300px] h-[300px] bg-node/5 rounded-full blur-[80px] pointer-events-none"></div>

    <!-- Floating chain particles -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
      <div v-for="(particle, i) in particles" :key="i"
        class="absolute font-mono text-[10px] font-bold opacity-0 tracking-wider"
        :class="particle.color"
        :style="{
          left: particle.x + '%',
          top: particle.y + '%',
          animation: `float-particle ${particle.duration}s ease-in-out ${particle.delay}s infinite`,
        }"
      >{{ particle.label }}</div>
    </div>

    <div class="relative z-10 w-full max-w-6xl mx-auto px-6 flex flex-col items-center text-center pt-20 pb-16">

      <!-- Tagline chip -->
      <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-surface font-mono text-xs text-primary-container border border-outline-variant/15 mb-8 select-none relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-node/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse shadow-[0_0_8px] shadow-pulse"></span>
        ZERO CUSTODY // FULL DOCUMENTATION
      </div>

      <!-- Main headline -->
      <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter text-on-surface mb-6 uppercase leading-[0.95]">
        Your crypto deserves<br/>
        <span class="relative inline-block">
          <span class="absolute -inset-2 blur-2xl bg-primary/15 opacity-60 animate-pulse"></span>
          <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-pulse via-node to-pulse">a paper trail.</span>
        </span>
      </h1>

      <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl mb-12 font-medium leading-relaxed">
        Because "trust me bro" isn't a tax strategy. Generate compliant invoices for every on-chain transaction across 12+ networks. Integrate in minutes. Off-ramp without the audit anxiety.
      </p>

      <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20">
        <a href="/dashboard" class="w-full sm:w-auto inline-block text-center relative group bg-primary text-void font-bold px-10 py-4 uppercase tracking-wider overflow-hidden shadow-[0_0_25px] shadow-pulse/30 transition-all hover:shadow-[0_0_50px] hover:shadow-pulse/60 hover:scale-[1.02]">
          <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/30 to-white/0 -translate-x-[150%] skew-x-[-45deg] group-hover:animate-[flash_1s_ease-in-out_infinite]"></div>
          Start Documenting
        </a>
        <a href="/docs" class="w-full sm:w-auto inline-block text-center relative group bg-surface border border-outline-variant/15 text-on-surface font-bold px-10 py-4 uppercase tracking-wider overflow-hidden hover:border-primary transition-colors">
          <div class="absolute inset-0 bg-surface-container-lowest/50 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
          <span class="relative z-10 group-hover:text-primary transition-colors">Read the Docs</span>
        </a>
      </div>

      <!-- Interactive Invoice Assembly -->
      <div class="relative w-full max-w-3xl mx-auto">
        <!-- Orbiting chain icons -->
        <div class="absolute -inset-8 pointer-events-none hidden lg:block">
          <div v-for="(orbit, i) in orbitChains" :key="'orbit-'+i"
            class="absolute w-10 h-10 border border-outline-variant/15 bg-surface flex items-center justify-center font-mono text-[10px] font-bold shadow-lg"
            :class="orbit.active ? 'border-primary/60 text-primary shadow-pulse/20' : 'text-on-surface-variant'"
            :style="{
              left: orbit.posX + '%',
              top: orbit.posY + '%',
              transition: 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)',
            }"
          >
            {{ orbit.chain }}
            <span v-if="orbit.active" class="absolute -top-1 -right-1 w-2 h-2 bg-primary rounded-full animate-ping"></span>
          </div>
        </div>

        <!-- The Invoice Document -->
        <div class="relative border border-outline-variant/15 bg-surface/90 backdrop-blur-xl shadow-2xl shadow-pulse/5 overflow-hidden group">
          <!-- Scan line -->
          <div class="absolute left-0 right-0 h-px bg-gradient-to-r from-transparent via-pulse/60 to-transparent opacity-0 group-hover:opacity-100 group-hover:animate-[scan_3s_ease-in-out_infinite]" style="top: -1px;"></div>

          <!-- Header bar -->
          <div class="flex items-center justify-between px-6 py-3 bg-surface-container-lowest border-b border-outline-variant/15">
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full bg-red-500/70"></div>
              <div class="w-3 h-3 rounded-full bg-amber-400/70"></div>
              <div class="w-3 h-3 rounded-full bg-green-500/70"></div>
              <span class="text-[10px] font-mono text-on-surface-variant ml-2 tracking-wider">NORYXON_INVOICE_ENGINE</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full" :class="phase > 0 ? 'bg-primary animate-pulse' : 'bg-text-muted'"></span>
              <span class="font-mono text-[10px] text-on-surface-variant tracking-wider">{{ phase > 0 ? 'GENERATING...' : 'STANDBY' }}</span>
            </div>
          </div>

          <!-- Invoice Body -->
          <div class="p-6 md:p-8 font-mono text-sm">
            <!-- Row 1: Invoice ID & Status -->
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-dashed border-outline-variant/15">
              <div>
                <div class="text-[10px] text-on-surface-variant tracking-widest mb-1">INVOICE</div>
                <div class="text-lg font-black tracking-tight transition-all duration-500" :class="phase >= 1 ? 'text-on-surface' : 'text-on-surface-variant/30'">
                  {{ phase >= 1 ? currentInvoiceId : 'INV-XXXXXXXX' }}
                </div>
              </div>
              <div class="text-right">
                <div class="text-[10px] text-on-surface-variant tracking-widest mb-1">STATUS</div>
                <div class="px-3 py-1 text-xs font-bold tracking-wider transition-all duration-500"
                  :class="phase >= 4 ? 'bg-primary/10 text-primary border border-primary/30' : phase >= 1 ? 'bg-node/10 text-primary-container border border-node/30' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/15'">
                  {{ phase >= 4 ? 'DOCUMENTED' : phase >= 2 ? 'PROCESSING' : 'PENDING' }}
                </div>
              </div>
            </div>

            <!-- Row 2: Transaction details -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
              <div class="transition-all duration-500" :class="phase >= 1 ? 'opacity-100' : 'opacity-20'">
                <div class="text-[10px] text-on-surface-variant tracking-widest mb-1">AMOUNT</div>
                <div class="text-on-surface font-bold">{{ currentAmount }} <span class="text-primary">{{ currentToken }}</span></div>
              </div>
              <div class="transition-all duration-500" :class="phase >= 1 ? 'opacity-100' : 'opacity-20'">
                <div class="text-[10px] text-on-surface-variant tracking-widest mb-1">CHAIN</div>
                <div class="text-on-surface font-bold">{{ currentChain }}</div>
              </div>
              <div class="transition-all duration-500" :class="phase >= 2 ? 'opacity-100' : 'opacity-20'">
                <div class="text-[10px] text-on-surface-variant tracking-widest mb-1">TX HASH</div>
                <div class="text-primary-container text-xs truncate">{{ displayHash }}</div>
              </div>
              <div class="transition-all duration-500" :class="phase >= 3 ? 'opacity-100' : 'opacity-20'">
                <div class="text-[10px] text-on-surface-variant tracking-widest mb-1">TAX CLASS</div>
                <div class="text-primary font-bold">DAT</div>
              </div>
            </div>

            <!-- Row 3: Progress bar -->
            <div class="mb-6">
              <div class="flex justify-between text-[10px] text-on-surface-variant tracking-widest mb-2">
                <span>INVOICE GENERATION</span>
                <span>{{ progressPercent }}%</span>
              </div>
              <div class="h-1 bg-surface-container-lowest-border overflow-hidden">
                <div class="h-full bg-primary transition-all duration-700 ease-out shadow-[0_0_10px] shadow-pulse/50"
                  :style="{ width: progressPercent + '%' }"></div>
              </div>
            </div>

            <!-- Row 4: Steps log -->
            <div class="space-y-2">
              <div v-for="(logEntry, idx) in visibleLogs" :key="idx"
                class="flex items-center gap-3 text-xs transition-all duration-300"
                :class="idx === visibleLogs.length - 1 ? 'text-primary' : 'text-on-surface-variant'">
                <span class="w-4 text-center">{{ logEntry.done ? '>' : '~' }}</span>
                <span>{{ logEntry.text }}</span>
                <span v-if="!logEntry.done" class="animate-pulse">_</span>
              </div>
            </div>

            <!-- Row 5: Final output -->
            <div v-if="phase >= 4" class="mt-6 pt-4 border-t border-outline-variant/15 flex items-center justify-between animate-[fadeIn_0.5s_ease-out]">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary/10 border border-primary/30 flex items-center justify-center">
                  <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                  <div class="text-xs text-primary font-bold tracking-wider">INVOICE + TAX REPORT READY</div>
                  <div class="text-[10px] text-on-surface-variant">Compliant documentation generated in {{ latency }}ms</div>
                </div>
              </div>
              <div class="text-[10px] text-on-surface-variant font-mono hidden md:block">
                PDF_URL: /invoices/{{ currentInvoiceId }}.pdf
              </div>
            </div>
          </div>
        </div>

        <!-- Trust badges below invoice -->
        <div class="flex items-center justify-center gap-6 mt-6 font-mono text-[10px] text-on-surface-variant tracking-widest">
          <span class="flex items-center gap-1.5">
            <span class="w-1 h-1 bg-primary rounded-full"></span>
            12+ CHAINS
          </span>
          <span class="flex items-center gap-1.5">
            <span class="w-1 h-1 bg-node rounded-full"></span>
            5,000+ TOKENS
          </span>
          <span class="flex items-center gap-1.5">
            <span class="w-1 h-1 bg-primary rounded-full"></span>
            ZERO CUSTODY
          </span>
          <span class="hidden sm:flex items-center gap-1.5">
            <span class="w-1 h-1 bg-node rounded-full"></span>
            ~45ms LATENCY
          </span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// Floating particles
const particles = [
  { label: 'ETH', x: 8, y: 20, color: 'text-on-surface-variant/40', duration: 6, delay: 0 },
  { label: 'BTC', x: 88, y: 15, color: 'text-primary/30', duration: 7, delay: 1 },
  { label: 'SOL', x: 15, y: 70, color: 'text-primary-container/30', duration: 5, delay: 2 },
  { label: 'USDC', x: 82, y: 65, color: 'text-on-surface-variant/30', duration: 8, delay: 0.5 },
  { label: 'DAI', x: 5, y: 45, color: 'text-primary/20', duration: 6, delay: 3 },
  { label: 'ARB', x: 92, y: 40, color: 'text-primary-container/20', duration: 7, delay: 1.5 },
  { label: 'TRX', x: 25, y: 85, color: 'text-on-surface-variant/20', duration: 5.5, delay: 2.5 },
  { label: 'POL', x: 75, y: 80, color: 'text-primary/20', duration: 6.5, delay: 0.8 },
];

// Orbiting chain badges
const orbitChains = ref([
  { chain: 'ETH', posX: -5, posY: 20, active: false },
  { chain: 'BTC', posX: 95, posY: 15, active: false },
  { chain: 'SOL', posX: -5, posY: 70, active: false },
  { chain: 'ARB', posX: 95, posY: 60, active: false },
  { chain: 'POL', posX: 40, posY: -8, active: false },
  { chain: 'OPT', posX: 60, posY: 105, active: false },
]);

// Invoice simulation state
const phase = ref(0);
const currentInvoiceId = ref('INV-00000000');
const currentAmount = ref('0.00');
const currentToken = ref('USDC');
const currentChain = ref('Ethereum');
const displayHash = ref('0x0000...0000');
const latency = ref('0');
const visibleLogs = ref([]);

const progressPercent = computed(() => {
  if (phase.value === 0) return 0;
  if (phase.value === 1) return 25;
  if (phase.value === 2) return 50;
  if (phase.value === 3) return 75;
  return 100;
});

const hexChars = 'ABCDEFabcdef0123456789';
const tokens = ['USDC', 'USDT', 'ETH', 'BTC', 'DAI', 'SOL'];
const chains = ['Ethereum', 'Solana', 'Polygon', 'Arbitrum', 'Optimism', 'Bitcoin'];

const generateHash = () => '0x' + Array.from({ length: 8 }, () => hexChars[Math.floor(Math.random() * hexChars.length)]).join('') + '...' + Array.from({ length: 4 }, () => hexChars[Math.floor(Math.random() * hexChars.length)]).join('');
const generateInvoiceId = () => 'INV-' + Math.random().toString(36).substring(2, 10).toUpperCase();

const logs = [
  'Detecting on-chain transaction...',
  'Verifying block confirmations...',
  'Cross-referencing wallet addresses...',
  'Generating compliant invoice document...',
  'Computing Digital Asset Tax classification...',
  'Attaching tax report to invoice...',
];

let timeouts = [];

const clearAllTimeouts = () => {
  timeouts.forEach(t => clearTimeout(t));
  timeouts = [];
};

const runSimulation = () => {
  clearAllTimeouts();
  phase.value = 0;
  visibleLogs.value = [];

  const chainIdx = Math.floor(Math.random() * chains.length);
  currentToken.value = tokens[Math.floor(Math.random() * tokens.length)];
  currentChain.value = chains[chainIdx];
  currentAmount.value = (Math.random() * 9500 + 100).toFixed(2);
  currentInvoiceId.value = generateInvoiceId();
  displayHash.value = '0x0000...0000';
  latency.value = (Math.random() * 30 + 35).toFixed(0);

  // Activate a random orbit chain
  orbitChains.value.forEach((o, i) => o.active = i === chainIdx % orbitChains.value.length);

  // Phase 1: Transaction detected
  timeouts.push(setTimeout(() => {
    phase.value = 1;
    visibleLogs.value = [{ text: logs[0], done: false }];
  }, 600));

  // Hash scramble
  timeouts.push(setTimeout(() => {
    scrambleHash();
    visibleLogs.value = [{ text: logs[0], done: true }, { text: logs[1], done: false }];
  }, 1200));

  // Phase 2
  timeouts.push(setTimeout(() => {
    phase.value = 2;
    visibleLogs.value = [
      { text: logs[0], done: true },
      { text: logs[1], done: true },
      { text: logs[2], done: false },
    ];
  }, 2200));

  // Phase 3
  timeouts.push(setTimeout(() => {
    phase.value = 3;
    visibleLogs.value = [
      { text: logs[0], done: true },
      { text: logs[1], done: true },
      { text: logs[2], done: true },
      { text: logs[3], done: true },
      { text: logs[4], done: false },
    ];
  }, 3500));

  // Phase 4: Complete
  timeouts.push(setTimeout(() => {
    phase.value = 4;
    visibleLogs.value = [
      { text: logs[0], done: true },
      { text: logs[1], done: true },
      { text: logs[2], done: true },
      { text: logs[3], done: true },
      { text: logs[4], done: true },
      { text: logs[5], done: true },
    ];
    orbitChains.value.forEach(o => o.active = false);
  }, 5000));

  // Reset and loop
  timeouts.push(setTimeout(() => {
    runSimulation();
  }, 9000));
};

let scrambleInterval = null;
const scrambleHash = () => {
  const finalHash = generateHash();
  let iterations = 0;
  clearInterval(scrambleInterval);
  scrambleInterval = setInterval(() => {
    displayHash.value = finalHash.split('').map((c, i) => {
      if (i < iterations / 2 || c === '.' || c === 'x') return c;
      return hexChars[Math.floor(Math.random() * hexChars.length)];
    }).join('');
    iterations++;
    if (iterations >= 30) {
      clearInterval(scrambleInterval);
      displayHash.value = finalHash;
    }
  }, 35);
};

onMounted(() => {
  timeouts.push(setTimeout(runSimulation, 800));
});

onUnmounted(() => {
  clearAllTimeouts();
  clearInterval(scrambleInterval);
});
</script>

<style>
@keyframes shimmer {
  100% { transform: translateX(100%); }
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
@keyframes float-particle {
  0%, 100% { opacity: 0; transform: translateY(0px); }
  20% { opacity: 0.6; }
  50% { transform: translateY(-20px); opacity: 0.4; }
  80% { opacity: 0.6; }
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
