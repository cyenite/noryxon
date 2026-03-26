<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const features = [
  { label: 'Multi-chain invoicing (BTC, ETH, SOL +12)', icon: 'hub' },
  { label: 'Tax-compliant documentation & DAT reports', icon: 'assessment' },
  { label: 'Off-ramp partner redirects (no VASP needed)', icon: 'shield_lock' },
  { label: 'API-first with SDKs in 5 languages', icon: 'terminal' },
];

const stats = [
  { value: '$4.2B+', label: 'DOCUMENTED' },
  { value: '99.99%', label: 'UPTIME' },
  { value: '180+', label: 'COUNTRIES' },
];

const hashLine = ref('');
const hexChars = 'ABCDEFabcdef0123456789';
const generateHash = () => Array.from({ length: 32 }, () => hexChars[Math.floor(Math.random() * hexChars.length)]).join('');

onMounted(() => {
  hashLine.value = generateHash();
  setInterval(() => { hashLine.value = generateHash(); }, 3000);
});
</script>

<template>
  <div class="flex min-h-screen bg-surface font-sans antialiased">

    <!-- Left branding panel -->
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden bg-on-surface flex-col justify-between p-12 xl:p-16">
      <!-- Grid lines -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

      <!-- Gradient mesh blobs -->
      <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full opacity-15 blur-[120px]"
        style="background: radial-gradient(circle, #f59e0b 0%, transparent 70%);"></div>
      <div class="absolute -bottom-40 -left-20 w-[400px] h-[400px] rounded-full opacity-10 blur-[100px]"
        style="background: radial-gradient(circle, #30c88f 0%, transparent 70%);"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full opacity-[0.06] blur-[140px]"
        style="background: radial-gradient(circle, #855300 0%, transparent 60%);"></div>

      <!-- Noise -->
      <div class="absolute inset-0 noise-overlay opacity-[0.03] pointer-events-none mix-blend-overlay"></div>

      <!-- Logo top -->
      <div class="relative z-10">
        <Link href="/" class="inline-flex items-center gap-3 group">
          <span class="font-black tracking-tighter text-xl text-white font-headline">Noryxon</span>
        </Link>
      </div>

      <!-- Center content -->
      <div class="relative z-10 space-y-10">
        <div class="space-y-6">
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
            </span>
            <span class="font-mono text-[10px] text-primary-container tracking-widest uppercase">Invoice Protocol v2</span>
          </div>
          <h1 class="text-4xl xl:text-5xl font-extrabold tracking-tighter text-white leading-[0.95] font-headline">
            Digital Asset<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary-container to-primary">Invoicing</span> Infrastructure
          </h1>
          <p class="text-white/50 text-base max-w-md leading-relaxed">
            The platform that turns "unexplained income" into "properly documented digital assets." Zero custody, zero excuses.
          </p>
        </div>

        <!-- Feature list -->
        <div class="space-y-4">
          <div v-for="item in features" :key="item.label" class="flex items-center gap-3 group/feat">
            <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center group-hover/feat:bg-primary/10 group-hover/feat:border-primary/30 transition-all">
              <span class="material-symbols-outlined text-sm text-primary-container">{{ item.icon }}</span>
            </div>
            <span class="text-sm text-white/60 group-hover/feat:text-white/80 transition-colors">{{ item.label }}</span>
          </div>
        </div>

        <!-- Live hash -->
        <div class="font-mono text-[10px] text-white/20 tracking-widest truncate">
          0x{{ hashLine }}
        </div>
      </div>

      <!-- Bottom stats bar -->
      <div class="relative z-10 border-t border-white/10 pt-8 grid grid-cols-3 gap-6">
        <div v-for="stat in stats" :key="stat.label">
          <div class="text-2xl font-extrabold text-white tracking-tight font-headline">{{ stat.value }}</div>
          <div class="text-[10px] text-white/40 font-mono mt-1 tracking-widest uppercase">{{ stat.label }}</div>
        </div>
      </div>
    </div>

    <!-- Right auth panel -->
    <div class="flex flex-1 flex-col justify-center items-center px-6 py-12 lg:px-12 xl:px-16 relative overflow-hidden">
      <!-- Subtle background mesh -->
      <div class="absolute -top-40 -right-40 w-[400px] h-[400px] bg-primary-container/5 rounded-full blur-[120px] pointer-events-none"></div>
      <div class="absolute -bottom-40 -left-40 w-[300px] h-[300px] bg-tertiary-container/5 rounded-full blur-[100px] pointer-events-none"></div>
      <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--color-outline-variant)_1px,transparent_1px),linear-gradient(to_bottom,var(--color-outline-variant)_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-[0.04] pointer-events-none"></div>

      <!-- Mobile logo -->
      <div class="lg:hidden mb-10">
        <Link href="/" class="inline-flex items-center gap-3">
          <span class="font-extrabold tracking-tighter text-xl text-on-surface font-headline">Noryxon</span>
        </Link>
      </div>

      <div class="w-full max-w-sm relative z-10">
        <slot />
      </div>

      <!-- Bottom nav links -->
      <div class="relative z-10 mt-12 flex items-center gap-6 text-xs text-on-surface-variant font-medium">
        <Link href="/" class="hover:text-primary transition-colors">Home</Link>
        <Link href="/pricing" class="hover:text-primary transition-colors">Pricing</Link>
        <Link href="/docs" class="hover:text-primary transition-colors">Docs</Link>
      </div>
    </div>

  </div>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
