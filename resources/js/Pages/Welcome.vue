<template>
  <div class="bg-surface text-on-surface antialiased overflow-x-hidden">
    <Head title="Noryxon | Digital Asset Invoicing &amp; Compliance Engine" />

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- SECTION 0: Floating Pill Nav                           -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <nav
      class="fixed top-4 left-1/2 -translate-x-1/2 z-50 transition-all duration-700 ease-out"
    >
      <div class="rounded-full px-2 py-2 flex items-center gap-1 border transition-all duration-700 ease-out"
        :class="navScrolled ? 'glass-nav shadow-lg shadow-on-surface/5 border-outline-variant/15' : 'bg-transparent border-transparent shadow-none'"
      >
        <Link href="/" class="px-4 py-2 text-sm font-extrabold text-on-surface tracking-tighter font-headline">Noryxon</Link>
        <div class="h-4 w-px bg-outline-variant/30 hidden md:block"></div>
        <a href="#features" class="hidden md:block px-3 py-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors rounded-full hover:bg-primary/5">Features</a>
        <a href="#how-it-works" class="hidden md:block px-3 py-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors rounded-full hover:bg-primary/5">How It Works</a>
        <a href="#pricing" class="hidden md:block px-3 py-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors rounded-full hover:bg-primary/5">Pricing</a>
        <Link href="/docs" class="hidden md:block px-3 py-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors rounded-full hover:bg-primary/5">Docs</Link>
        <div class="h-4 w-px bg-outline-variant/30 hidden md:block"></div>
        <Link href="/login" class="hidden md:block px-3 py-1.5 text-xs font-semibold text-on-surface-variant hover:text-primary transition-colors rounded-full">Log In</Link>
        <Link href="/register" class="cta-gradient text-white px-5 py-2 rounded-full text-xs font-bold shadow-sm hover:scale-95 transition-transform duration-200">Get Started</Link>
      </div>
    </nav>

    <main>
      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 1: Hero — Full Viewport, Animated Mesh        -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section class="relative min-h-screen flex items-center overflow-hidden bg-surface">
        <!-- Animated gradient mesh blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
          <div class="absolute w-[800px] h-[800px] -top-40 -left-40 bg-primary-container/8 rounded-full blur-[160px] animate-drift-1"></div>
          <div class="absolute w-[600px] h-[600px] top-1/3 right-0 bg-surface-container-high/40 rounded-full blur-[120px] animate-drift-2"></div>
          <div class="absolute w-[500px] h-[500px] bottom-0 left-1/3 bg-tertiary-container/6 rounded-full blur-[100px] animate-drift-3"></div>
        </div>
        <!-- Grid lines -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--color-outline-variant)_1px,transparent_1px),linear-gradient(to_bottom,var(--color-outline-variant)_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-[0.06] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_40%,transparent_100%)] pointer-events-none"></div>
        <!-- Noise overlay -->
        <div class="absolute inset-0 noise-overlay opacity-[0.02] pointer-events-none mix-blend-overlay"></div>

        <!-- Floating chain particles -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden hidden md:block">
          <div v-for="(particle, i) in particles" :key="'p-'+i"
            class="absolute font-mono text-[10px] font-bold opacity-0 tracking-wider"
            :class="particle.color"
            :style="{ left: particle.x + '%', top: particle.y + '%', animation: `float-particle ${particle.duration}s ease-in-out ${particle.delay}s infinite` }"
          >{{ particle.label }}</div>
        </div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-8 py-24 md:py-0">
          <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">
            <!-- Left: Text content -->
            <div class="space-y-8">
              <!-- Badge pill -->
              <div class="animate-fade-up inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-tertiary-container/10 border border-tertiary-container/20">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary-container opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-tertiary-container"></span>
                </span>
                <span class="font-mono text-xs font-bold tracking-widest uppercase text-tertiary">Compliant Ledger Technology</span>
              </div>

              <!-- Headline -->
              <h1 class="animate-fade-up delay-100 text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold tracking-tighter leading-[0.92] font-headline">
                Digital Asset
                <span class="relative inline-block">
                  <span class="absolute -inset-2 blur-2xl bg-primary-container/20 animate-pulse-slow"></span>
                  <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary-container to-primary">Invoicing</span>
                </span>
                <br/>& Compliance
              </h1>

              <!-- Subheadline -->
              <p class="animate-fade-up delay-200 text-lg md:text-xl text-on-surface-variant/80 leading-relaxed max-w-xl">
                Scale your institutional crypto payments with Zero-Custody architecture and automated DAT classification. No VASP required, fully audited, and cryptographic proof as standard.
              </p>

              <!-- CTA buttons -->
              <div class="animate-fade-up delay-300 flex flex-wrap gap-4 pt-2">
                <Link href="/register" class="group relative cta-gradient text-white px-8 py-4 rounded-xl font-bold text-lg flex items-center gap-2 shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:scale-[1.02] transition-all duration-300 overflow-hidden">
                  <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/25 to-white/0 -translate-x-[200%] group-hover:animate-shimmer"></div>
                  <span class="relative z-10">Get Started</span>
                  <span class="material-symbols-outlined relative z-10">arrow_forward</span>
                </Link>
                <Link href="/docs" class="border border-outline-variant/30 text-on-surface px-8 py-4 rounded-xl font-bold text-lg hover:bg-surface-container-low hover:border-outline-variant/50 transition-all duration-300">
                  Read Docs
                </Link>
              </div>

              <!-- Animated counters -->
              <div class="animate-fade-up delay-400 flex gap-8 pt-6 border-t border-outline-variant/15 mt-4">
                <div>
                  <p class="text-2xl md:text-3xl font-black text-on-surface tracking-tight font-headline">{{ formatNumber(invoicesGenerated) }}</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mt-1">Invoices Generated</p>
                </div>
                <div>
                  <p class="text-2xl md:text-3xl font-black text-primary tracking-tight font-headline">{{ chainsMonitored }}+</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mt-1">Chains Monitored</p>
                </div>
                <div>
                  <p class="text-2xl md:text-3xl font-black text-tertiary tracking-tight font-headline">~{{ avgLatency }}ms</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mt-1">Avg Latency</p>
                </div>
              </div>
            </div>

            <!-- Right: Live Invoice Simulation Card -->
            <div class="relative hidden md:block">
              <!-- Orbiting chain badges -->
              <div class="absolute -inset-8 pointer-events-none hidden lg:block">
                <div v-for="(orbit, i) in orbitChains" :key="'orbit-'+i"
                  class="absolute w-10 h-10 border bg-surface-container-lowest rounded-lg flex items-center justify-center font-mono text-[10px] font-bold shadow-md transition-all duration-800"
                  :class="orbit.active ? 'border-primary/60 text-primary shadow-primary/20' : 'border-outline-variant/30 text-on-surface-variant'"
                  :style="{ left: orbit.posX + '%', top: orbit.posY + '%' }"
                >
                  {{ orbit.chain }}
                  <span v-if="orbit.active" class="absolute -top-1 -right-1 w-2 h-2 bg-primary rounded-full animate-ping"></span>
                </div>
              </div>

              <!-- Glow -->
              <div class="absolute -inset-4 bg-primary-container/8 rounded-3xl blur-3xl"></div>

              <!-- The Invoice Document -->
              <div class="relative bg-surface-container-lowest rounded-2xl shadow-2xl border border-outline-variant/10 overflow-hidden group h-[520px] flex flex-col">
                <!-- Scan line -->
                <div class="absolute left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent opacity-0 group-hover:opacity-100 group-hover:animate-scan" style="top: -1px; z-index: 20;"></div>

                <!-- Header bar -->
                <div class="flex items-center justify-between px-6 py-3 bg-surface-container-low border-b border-outline-variant/15 shrink-0">
                  <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-error/60"></div>
                    <div class="w-3 h-3 rounded-full bg-primary-container/80"></div>
                    <div class="w-3 h-3 rounded-full bg-tertiary-container/80"></div>
                    <span class="text-[10px] font-mono text-on-surface-variant/60 ml-2 tracking-wider">NORYXON_INVOICE_ENGINE</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full" :class="phase > 0 ? 'bg-primary animate-pulse' : 'bg-on-surface-variant/30'"></span>
                    <span class="font-mono text-[10px] text-on-surface-variant/60 tracking-wider">{{ phase > 0 ? 'GENERATING...' : 'STANDBY' }}</span>
                  </div>
                </div>

                <!-- Invoice Body — fixed height, content fills -->
                <div class="p-6 md:p-8 font-mono text-sm flex-1 flex flex-col">
                  <!-- Row 1: Invoice ID & Status -->
                  <div class="flex justify-between items-start mb-5 pb-4 border-b border-dashed border-outline-variant/20 shrink-0">
                    <div>
                      <div class="text-[10px] text-on-surface-variant/60 tracking-widest mb-1">INVOICE</div>
                      <div class="text-lg font-black tracking-tight transition-all duration-500" :class="phase >= 1 ? 'text-on-surface' : 'text-on-surface-variant/20'">
                        {{ phase >= 1 ? currentInvoiceId : 'NX-XXXXXXXX' }}
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="text-[10px] text-on-surface-variant/60 tracking-widest mb-1">STATUS</div>
                      <div class="px-3 py-1 text-xs font-bold tracking-wider transition-all duration-500 rounded-md"
                        :class="phase >= 4 ? 'bg-primary/10 text-primary border border-primary/30' : phase >= 1 ? 'bg-primary-container/10 text-primary-container border border-primary-container/30' : 'bg-surface-container-low text-on-surface-variant/40 border border-outline-variant/20'">
                        {{ phase >= 4 ? 'DOCUMENTED' : phase >= 2 ? 'PROCESSING' : 'PENDING' }}
                      </div>
                    </div>
                  </div>

                  <!-- Row 2: Transaction details -->
                  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5 shrink-0">
                    <div class="transition-all duration-500" :class="phase >= 1 ? 'opacity-100' : 'opacity-20'">
                      <div class="text-[10px] text-on-surface-variant/60 tracking-widest mb-1">AMOUNT</div>
                      <div class="text-on-surface font-bold">{{ heroAmount }} <span class="text-primary">{{ heroToken }}</span></div>
                    </div>
                    <div class="transition-all duration-500" :class="phase >= 1 ? 'opacity-100' : 'opacity-20'">
                      <div class="text-[10px] text-on-surface-variant/60 tracking-widest mb-1">CHAIN</div>
                      <div class="text-on-surface font-bold">{{ heroChain }}</div>
                    </div>
                    <div class="transition-all duration-500" :class="phase >= 2 ? 'opacity-100' : 'opacity-20'">
                      <div class="text-[10px] text-on-surface-variant/60 tracking-widest mb-1">TX HASH</div>
                      <div class="text-primary-container text-xs truncate">{{ displayHash }}</div>
                    </div>
                    <div class="transition-all duration-500" :class="phase >= 3 ? 'opacity-100' : 'opacity-20'">
                      <div class="text-[10px] text-on-surface-variant/60 tracking-widest mb-1">TAX CLASS</div>
                      <div class="text-primary font-bold">DAT</div>
                    </div>
                  </div>

                  <!-- Row 3: Progress bar -->
                  <div class="mb-5 shrink-0">
                    <div class="flex justify-between text-[10px] text-on-surface-variant/60 tracking-widest mb-2">
                      <span>INVOICE GENERATION</span>
                      <span>{{ progressPercent }}%</span>
                    </div>
                    <div class="h-1 bg-outline-variant/20 rounded-full overflow-hidden">
                      <div class="h-full bg-gradient-to-r from-primary to-primary-container transition-all duration-700 ease-out rounded-full shadow-[0_0_10px] shadow-primary/30"
                        :style="{ width: progressPercent + '%' }"></div>
                    </div>
                  </div>

                  <!-- Row 4: Fixed-height log area — all 6 slots always rendered -->
                  <div class="flex-1 flex flex-col justify-between">
                    <div class="space-y-2">
                      <div v-for="(logText, idx) in heroLogs" :key="idx"
                        class="flex items-center gap-3 text-xs transition-all duration-500"
                        :class="logState(idx)"
                      >
                        <span class="w-4 text-center font-bold">{{ logIcon(idx) }}</span>
                        <span>{{ logText }}</span>
                        <span v-if="logIcon(idx) === '~'" class="animate-blink">_</span>
                      </div>
                    </div>

                    <!-- Bottom: Final output / idle stats -->
                    <div class="pt-4 border-t border-outline-variant/15 mt-auto">
                      <!-- Phase 4: Completion -->
                      <div v-if="phase >= 4" class="flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 bg-primary/10 border border-primary/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                          </div>
                          <div>
                            <div class="text-xs text-primary font-bold tracking-wider">INVOICE + TAX REPORT READY</div>
                            <div class="text-[10px] text-on-surface-variant/60">Compliant documentation generated in {{ heroLatency }}ms</div>
                          </div>
                        </div>
                        <div class="flex gap-2">
                          <div class="w-7 h-7 bg-surface-container-low rounded-md flex items-center justify-center" title="Download PDF">
                            <svg class="w-3.5 h-3.5 text-on-surface-variant" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                          </div>
                          <div class="w-7 h-7 bg-surface-container-low rounded-md flex items-center justify-center" title="View on-chain">
                            <svg class="w-3.5 h-3.5 text-on-surface-variant" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                          </div>
                        </div>
                      </div>
                      <!-- Phase 0: Idle — show system stats -->
                      <div v-else-if="phase === 0" class="flex items-center justify-between text-on-surface-variant/40">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 bg-surface-container-low rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-on-surface-variant/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                          </div>
                          <div>
                            <div class="text-xs font-bold tracking-wider">SYSTEM IDLE</div>
                            <div class="text-[10px]">Awaiting next transaction...</div>
                          </div>
                        </div>
                        <div class="text-[10px] font-mono tracking-wider">UPTIME: 99.99%</div>
                      </div>
                      <!-- Phase 1-3: Processing stats -->
                      <div v-else class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                          <div class="text-center">
                            <div class="text-[10px] text-on-surface-variant/50 tracking-widest">BLOCKS</div>
                            <div class="text-xs font-bold text-on-surface tabular-nums">{{ heroBlockConfirms }}</div>
                          </div>
                          <div class="w-px h-6 bg-outline-variant/15"></div>
                          <div class="text-center">
                            <div class="text-[10px] text-on-surface-variant/50 tracking-widest">GAS</div>
                            <div class="text-xs font-bold text-on-surface tabular-nums">{{ heroGasPrice }} gwei</div>
                          </div>
                          <div class="w-px h-6 bg-outline-variant/15"></div>
                          <div class="text-center">
                            <div class="text-[10px] text-on-surface-variant/50 tracking-widest">LATENCY</div>
                            <div class="text-xs font-bold text-primary tabular-nums">{{ heroLatency }}ms</div>
                          </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                          <span class="w-1.5 h-1.5 rounded-full bg-primary-container animate-pulse"></span>
                          <span class="text-[10px] font-bold text-primary-container tracking-wider">LIVE</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Trust badges below invoice -->
              <div class="flex items-center justify-center gap-6 mt-4 font-mono text-[10px] text-on-surface-variant/60 tracking-widest">
                <span class="flex items-center gap-1.5"><span class="w-1 h-1 bg-primary rounded-full"></span>12+ CHAINS</span>
                <span class="flex items-center gap-1.5"><span class="w-1 h-1 bg-primary-container rounded-full"></span>5,000+ TOKENS</span>
                <span class="flex items-center gap-1.5"><span class="w-1 h-1 bg-primary rounded-full"></span>ZERO CUSTODY</span>
                <span class="hidden sm:flex items-center gap-1.5"><span class="w-1 h-1 bg-tertiary-container rounded-full"></span>~45ms LATENCY</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Chain Ticker Marquee — pinned to bottom of hero -->
        <div class="absolute bottom-0 left-0 right-0 z-20 bg-surface-container-low/80 backdrop-blur-sm border-t border-outline-variant/10 overflow-hidden py-3">
          <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-surface-container-low to-transparent z-10 pointer-events-none"></div>
          <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-surface-container-low to-transparent z-10 pointer-events-none"></div>

          <div class="relative flex overflow-x-hidden group">
            <div class="animate-marquee whitespace-nowrap flex items-center gap-12 group-hover:[animation-play-state:paused] pr-12">
              <div v-for="(item, index) in tickerItems" :key="'t1-' + index" class="inline-flex items-center gap-3">
                <div class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary-container opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-tertiary-container"></span>
                </div>
                <div class="font-mono flex items-center gap-4">
                  <span class="text-on-surface font-bold tracking-widest text-xs">{{ item.chain }}</span>
                  <span class="text-on-surface-variant text-xs hidden sm:inline-block">BLK: {{ item.block }}</span>
                  <span class="text-on-surface-variant text-xs hidden md:inline-block">{{ item.hash }}...</span>
                  <span class="text-primary/70 text-xs font-semibold">[{{ item.feature }}]</span>
                </div>
              </div>
            </div>
            <div class="animate-marquee whitespace-nowrap flex items-center gap-12 group-hover:[animation-play-state:paused] pr-12 absolute top-0" aria-hidden="true">
              <div v-for="(item, index) in tickerItems" :key="'t2-' + index" class="inline-flex items-center gap-3">
                <div class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary-container opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-tertiary-container"></span>
                </div>
                <div class="font-mono flex items-center gap-4">
                  <span class="text-on-surface font-bold tracking-widest text-xs">{{ item.chain }}</span>
                  <span class="text-on-surface-variant text-xs hidden sm:inline-block">BLK: {{ item.block }}</span>
                  <span class="text-on-surface-variant text-xs hidden md:inline-block">{{ item.hash }}...</span>
                  <span class="text-primary/70 text-xs font-semibold">[{{ item.feature }}]</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 3: Features Bento — Dark Section              -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section id="features" class="py-24 md:py-32 bg-void relative overflow-hidden" data-section="features">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--color-ledger-border)_1px,transparent_1px),linear-gradient(to_bottom,var(--color-ledger-border)_1px,transparent_1px)] bg-[size:100px_100px] [mask-image:radial-gradient(ellipse_80%_80%_at_50%_0%,#000_10%,transparent_100%)] pointer-events-none opacity-40"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
          <div class="reveal mb-16 md:mb-20">
            <div class="inline-flex items-center gap-2 mb-4">
              <span class="w-1.5 h-1.5 bg-pulse animate-pulse"></span>
              <span class="font-mono text-xs text-pulse uppercase tracking-widest">System_Architecture</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-black uppercase text-text-primary tracking-tighter font-headline">Built Different. Documented Better.</h2>
            <div class="h-1 w-32 bg-pulse mt-8 shadow-[0_0_15px] shadow-pulse"></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Invoice Engine (col-span-7) -->
            <div class="reveal md:col-span-7 group relative bg-ledger border border-ledger-border p-8 md:p-10 transition-all duration-300 hover:border-pulse/60 hover:-translate-y-1 hover:shadow-2xl hover:shadow-pulse/10 rounded-2xl">
              <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-pulse to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-2xl"></div>
              <div class="flex justify-between items-start mb-8">
                <span class="material-symbols-outlined text-4xl text-pulse p-3 bg-pulse/10 rounded-xl">account_balance_wallet</span>
                <div class="font-mono text-[10px] text-text-muted group-hover:text-pulse transition-colors text-right">
                  ID: 0x01<br><span class="opacity-0 group-hover:opacity-100 transition-opacity">{{ bentoHash1 }}</span>
                </div>
              </div>
              <h3 class="text-2xl md:text-3xl font-black uppercase tracking-wider mb-4 text-text-primary group-hover:text-pulse transition-colors cursor-default font-headline" @mouseenter="decodeText($event, 'Invoice Engine (Zero-Custody)')">Invoice Engine (Zero-Custody)</h3>
              <p class="text-text-muted text-sm leading-relaxed font-mono max-w-md">Issue compliant invoices that settle directly to your vault. We never touch your private keys or hold user funds.</p>
              <div class="mt-8 grid grid-cols-3 gap-4 border-t border-ledger-border pt-6 group-hover:border-pulse/30 transition-colors">
                <div>
                  <p class="text-xl font-black text-pulse">0%</p>
                  <p class="text-[10px] uppercase font-bold tracking-tighter text-text-muted">Custody Risk</p>
                </div>
                <div>
                  <p class="text-xl font-black text-pulse">Instant</p>
                  <p class="text-[10px] uppercase font-bold tracking-tighter text-text-muted">Settlement</p>
                </div>
                <div>
                  <p class="text-xl font-black text-pulse">Sovereign</p>
                  <p class="text-[10px] uppercase font-bold tracking-tighter text-text-muted">Key Mgmt</p>
                </div>
              </div>
            </div>

            <!-- Multi-Chain (col-span-5) -->
            <div class="reveal md:col-span-5 cta-gradient text-white p-8 md:p-10 rounded-2xl flex flex-col justify-between min-h-[300px]">
              <span class="material-symbols-outlined text-4xl text-white/80 bg-white/10 p-3 rounded-xl w-fit">hub</span>
              <div class="space-y-4 mt-auto">
                <h3 class="text-2xl font-bold tracking-tight font-headline">12+ Chain Support</h3>
                <p class="text-white/80 text-sm leading-relaxed">Real-time verification across Ethereum, Solana, Polygon, Arbitrum, and more. Cross-chain reconciliation made simple.</p>
              </div>
              <div class="flex flex-wrap gap-2 mt-6">
                <span class="px-2 py-1 bg-white/20 rounded text-[10px] font-bold">MAINNET</span>
                <span class="px-2 py-1 bg-white/20 rounded text-[10px] font-bold">L2 READY</span>
                <span class="px-2 py-1 bg-white/20 rounded text-[10px] font-bold">TESTNET</span>
              </div>
            </div>

            <!-- DAT Reports (col-span-5) -->
            <div class="reveal md:col-span-5 group relative bg-ledger-light border border-ledger-border p-8 md:p-10 transition-all duration-300 hover:border-pulse/60 hover:-translate-y-1 hover:shadow-2xl hover:shadow-pulse/10 rounded-2xl">
              <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-pulse to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-2xl"></div>
              <div class="flex justify-between items-start mb-6">
                <span class="material-symbols-outlined text-4xl text-node">assessment</span>
                <div class="font-mono text-[10px] text-text-muted group-hover:text-pulse transition-colors text-right">
                  ID: 0x02<br><span class="opacity-0 group-hover:opacity-100 transition-opacity">{{ bentoHash2 }}</span>
                </div>
              </div>
              <h3 class="text-2xl font-black uppercase tracking-wider mb-4 text-text-primary group-hover:text-pulse transition-colors cursor-default font-headline" @mouseenter="decodeText($event, 'DAT Reports')">DAT Reports</h3>
              <p class="text-text-muted text-sm leading-relaxed font-mono">Generate audit-ready Digital Asset Transaction reports with a single click. Compliant with emerging global standards.</p>
              <div class="mt-6 font-mono text-xs text-text-muted border-t border-ledger-border pt-4 group-hover:border-pulse/30 transition-colors">
                > ASSETS_TRACKED: <span class="text-text-primary group-hover:text-pulse transition-colors">5,000+</span>
              </div>
            </div>

            <!-- No VASP (col-span-7) -->
            <div class="reveal md:col-span-7 group relative bg-void border border-ledger-border p-8 md:p-10 transition-all duration-300 hover:border-pulse/60 rounded-2xl flex flex-col md:flex-row items-start md:items-center gap-8">
              <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-pulse to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-2xl"></div>
              <div class="space-y-4 flex-1">
                <div class="flex items-center gap-2 text-node">
                  <span class="material-symbols-outlined">shield_lock</span>
                  <span class="text-xs font-bold uppercase tracking-widest font-mono">Security First</span>
                </div>
                <h3 class="text-2xl font-black uppercase tracking-wider text-text-primary group-hover:text-pulse transition-colors cursor-default font-headline" @mouseenter="decodeText($event, 'No VASP Required')">No VASP Required</h3>
                <p class="text-text-muted text-sm leading-relaxed font-mono">Direct peer-to-peer commercial invoicing without registering as a Virtual Asset Service Provider.</p>
              </div>
              <div class="flex-shrink-0 bg-ledger p-6 rounded-xl border border-ledger-border">
                <ul class="space-y-3">
                  <li class="flex items-center gap-2 text-xs font-medium text-text-muted font-mono">
                    <span class="material-symbols-outlined text-sm text-node">check_circle</span>Immutable Audit Logs
                  </li>
                  <li class="flex items-center gap-2 text-xs font-medium text-text-muted font-mono">
                    <span class="material-symbols-outlined text-sm text-node">check_circle</span>AML/KYC Integration
                  </li>
                  <li class="flex items-center gap-2 text-xs font-medium text-text-muted font-mono">
                    <span class="material-symbols-outlined text-sm text-node">check_circle</span>SOC2 Type II (Pending)
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 4: How It Works — 3-Step Flow                 -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section id="how-it-works" class="py-24 md:py-32 bg-surface relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
          <div class="reveal text-center mb-16 md:mb-20">
            <h2 class="text-4xl md:text-5xl font-black text-on-surface tracking-tighter font-headline">Three Steps. Zero Custody.</h2>
            <p class="text-on-surface-variant mt-4 max-w-xl mx-auto">From transaction to compliant documentation in seconds, not days.</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-4 relative">
            <!-- Connecting lines (desktop only) -->
            <div class="hidden md:block absolute top-1/2 left-[20%] right-[20%] -translate-y-1/2 z-0">
              <div class="border-t-2 border-dashed border-outline-variant/30 relative">
                <div class="absolute w-2 h-2 bg-primary-container rounded-full -top-1 animate-travel-dot"></div>
              </div>
            </div>

            <!-- Step 1 -->
            <div class="reveal relative z-10 text-center group">
              <div class="relative inline-block mb-6">
                <span class="absolute -top-8 -left-4 text-8xl font-black text-primary/5 font-headline select-none">1</span>
                <div class="relative w-16 h-16 rounded-2xl bg-primary/5 border border-primary/10 flex items-center justify-center mx-auto group-hover:bg-primary/10 group-hover:border-primary/20 transition-all duration-300">
                  <span class="material-symbols-outlined text-3xl text-primary">receipt_long</span>
                </div>
              </div>
              <h3 class="text-xl font-bold text-on-surface mb-3 font-headline">Create Invoice</h3>
              <p class="text-on-surface-variant text-sm leading-relaxed max-w-xs mx-auto">One API call generates a compliant invoice linked to any on-chain transaction.</p>
            </div>

            <!-- Step 2 -->
            <div class="reveal delay-200 relative z-10 text-center group">
              <div class="relative inline-block mb-6">
                <span class="absolute -top-8 -left-4 text-8xl font-black text-primary/5 font-headline select-none">2</span>
                <div class="relative w-16 h-16 rounded-2xl bg-primary/5 border border-primary/10 flex items-center justify-center mx-auto group-hover:bg-primary/10 group-hover:border-primary/20 transition-all duration-300">
                  <span class="material-symbols-outlined text-3xl text-primary">sensors</span>
                </div>
              </div>
              <h3 class="text-xl font-bold text-on-surface mb-3 font-headline">Auto-Detect Payment</h3>
              <p class="text-on-surface-variant text-sm leading-relaxed max-w-xs mx-auto">Real-time monitoring across 12+ chains detects settlement and verifies block confirmations.</p>
            </div>

            <!-- Step 3 -->
            <div class="reveal delay-400 relative z-10 text-center group">
              <div class="relative inline-block mb-6">
                <span class="absolute -top-8 -left-4 text-8xl font-black text-primary/5 font-headline select-none">3</span>
                <div class="relative w-16 h-16 rounded-2xl bg-primary/5 border border-primary/10 flex items-center justify-center mx-auto group-hover:bg-primary/10 group-hover:border-primary/20 transition-all duration-300">
                  <span class="material-symbols-outlined text-3xl text-primary">verified</span>
                </div>
              </div>
              <h3 class="text-xl font-bold text-on-surface mb-3 font-headline">Generate Compliance Docs</h3>
              <p class="text-on-surface-variant text-sm leading-relaxed max-w-xs mx-auto">DAT classification, tax reports, and audit-ready PDF — all generated automatically.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 5: Terminal Demo — Split Panel                -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section class="py-24 md:py-32 bg-surface-container-low relative overflow-hidden">
        <div class="absolute inset-0 noise-overlay opacity-[0.015] pointer-events-none mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,var(--color-outline-variant)_1px,transparent_1px),linear-gradient(to_bottom,var(--color-outline-variant)_1px,transparent_1px)] bg-[size:6rem_6rem] opacity-[0.04] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
          <div class="reveal text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/5 border border-primary/15 rounded-full mb-6">
              <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
              <span class="text-primary font-mono text-[10px] font-bold tracking-widest uppercase">Live Developer Console</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-on-surface tracking-tighter font-headline">Watch It Happen. Live.</h2>
            <p class="text-on-surface-variant mt-4 text-lg max-w-2xl mx-auto leading-relaxed">One API call generates a compliant invoice, triggers on-chain verification, and produces a tax-ready document — all in under 45ms.</p>
          </div>

          <div class="reveal grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch relative">
            <!-- Connecting pulse (desktop) -->
            <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 w-12 h-12 items-center justify-center">
              <div class="w-10 h-10 rounded-full bg-surface-container-lowest border-2 border-primary/30 flex items-center justify-center shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-primary text-lg">sync_alt</span>
              </div>
            </div>

            <!-- Terminal Side -->
            <div class="bg-on-surface rounded-2xl shadow-2xl overflow-hidden flex flex-col min-h-[480px] relative group">
              <div class="absolute inset-0 rounded-2xl border border-primary/0 group-hover:border-primary/20 transition-all pointer-events-none"></div>
              <div class="bg-on-surface/80 px-4 py-3 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 rounded-full bg-white/20"></div>
                  <div class="w-3 h-3 rounded-full bg-white/20"></div>
                  <div class="w-3 h-3 rounded-full bg-white/20"></div>
                </div>
                <span class="font-mono text-[10px] text-white/40 tracking-widest uppercase">root@noryxon-node:~</span>
              </div>
              <div class="font-mono text-sm text-white/90 flex-1 overflow-x-auto p-6 relative">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(245,158,11,0.02)_1px,transparent_1px)] bg-[size:100%_4px] pointer-events-none"></div>
                <div class="relative z-10">
                  <p class="mb-4 text-white/40"># CREATE_INVOICE_FOR_TRANSACTION</p>
                  <p><span class="text-primary-container font-bold">curl</span> -X POST https://api.noryxon.com/v1/invoices \</p>
                  <p class="ml-4">-H <span class="text-primary/80">"Authorization: Bearer sk_live_7x9a..."</span> \</p>
                  <p class="ml-4">-H <span class="text-primary/80">"Content-Type: application/json"</span> \</p>
                  <p class="ml-4">-d '{</p>
                  <p class="ml-8 text-white/80">"amount": "{{ termAmount }}",</p>
                  <p class="ml-8 text-white/80">"currency": "{{ termCurrency }}",</p>
                  <p class="ml-8 text-white/80">"chain": "{{ termChain }}",</p>
                  <p class="ml-8 text-white/80">"payer": "client@example.com"</p>
                  <p class="ml-4">}'</p>

                  <div class="mt-8 border-t border-dashed border-white/10 pt-4">
                    <div v-if="!termIsSimulating && !termIsComplete" class="text-primary animate-pulse">
                      <span class="mr-2">_</span>READY_TO_GENERATE_INVOICE
                    </div>
                    <div v-if="termIsSimulating" class="text-primary-container">
                      <p v-for="(log, idx) in termSimLogs" :key="idx" class="mb-1 opacity-80">> {{ log }}</p>
                      <p class="animate-pulse">> _</p>
                    </div>
                    <div v-if="termIsComplete" class="text-primary text-xs leading-relaxed">
                      > HTTP/1.1 200 OK<br>
                      > <span class="text-white">{</span><br>
                      > &nbsp;&nbsp;"id": "{{ termSessionId }}",<br>
                      > &nbsp;&nbsp;"amount": "{{ termAmount }} {{ termCurrency }}",<br>
                      > &nbsp;&nbsp;"status": "invoice_generated",<br>
                      > &nbsp;&nbsp;"tax_report": "dat_compliant"<br>
                      > <span class="text-white">}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Checkout Preview Side -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-xl flex flex-col justify-center items-center relative min-h-[480px] overflow-hidden group">
              <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="w-full h-1 bg-primary/30 shadow-[0_0_15px] shadow-primary/20 -translate-y-2 group-hover:animate-scan"></div>
              </div>

              <div class="absolute top-4 right-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full" :class="termIsComplete ? 'bg-primary shadow-[0_0_8px] shadow-primary' : 'bg-tertiary-container shadow-[0_0_8px] shadow-tertiary-container animate-pulse'"></span>
                <span class="font-mono text-[10px] tracking-widest text-on-surface-variant">SESSION_ACTIVE</span>
              </div>

              <div class="text-center mt-8 z-10 w-full px-8">
                <h3 class="text-2xl font-black uppercase tracking-widest mb-1 text-on-surface font-headline">
                  {{ termIsComplete ? 'Invoice Generated' : 'Awaiting Transaction' }}
                </h3>
                <p class="font-mono text-on-surface-variant mb-8 text-xs border-b border-outline-variant/20 pb-4 w-full">
                  TRANSFER_EXPECTED: {{ termAmount }} {{ termCurrency }} ({{ termChain.toUpperCase() }})
                </p>

                <div class="relative w-48 h-48 mx-auto mb-8 bg-surface border border-outline-variant/20 flex items-center justify-center p-2 rounded-xl">
                  <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-on-surface/40 rounded-tl-lg"></div>
                  <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-on-surface/40 rounded-tr-lg"></div>
                  <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-on-surface/40 rounded-bl-lg"></div>
                  <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-on-surface/40 rounded-br-lg"></div>

                  <div class="w-full h-full flex flex-col items-center justify-center transition-all" :class="{'opacity-10 blur-sm': termIsComplete}">
                    <div class="grid grid-cols-6 gap-1 w-28 h-28">
                      <div v-for="i in 36" :key="'qr-'+i" class="rounded-sm" :class="qrPattern[i-1] ? 'bg-on-surface' : 'bg-outline-variant/20'"></div>
                    </div>
                  </div>

                  <div v-if="termIsComplete" class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center animate-pop-in shadow-[0_0_30px] shadow-primary/40">
                      <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                  </div>
                </div>

                <div class="font-mono bg-surface-container-low border border-outline-variant/20 px-4 py-3 w-full max-w-sm mx-auto truncate text-on-surface/80 text-xs tracking-wider rounded-lg relative overflow-hidden group/addr">
                  <div class="absolute inset-0 bg-primary/5 translate-y-full group-hover/addr:translate-y-0 transition-transform"></div>
                  <span class="relative z-10">{{ termIsComplete ? 'INVOICE_DOCUMENTED' : termAddress }}</span>
                </div>

                <div class="mt-8 mx-auto font-mono text-[10px] uppercase font-bold px-6 py-3 tracking-widest text-center border rounded-lg"
                  :class="termIsComplete ? 'border-tertiary text-tertiary shadow-[0_0_10px] shadow-tertiary/20' : 'border-primary/40 text-primary/70'">
                  {{ termIsComplete ? '[ INVOICE_COMPLETE ]' : (termIsSimulating ? '[ VERIFYING_TRANSACTION ]' : '[ REALTIME_MONITORING_ACTIVE ]') }}
                </div>
              </div>
            </div>
          </div>

          <!-- SDK Language Pills -->
          <div class="reveal flex flex-wrap justify-center gap-3 mt-12">
            <div v-for="sdk in ['TypeScript', 'Python', 'Go', 'PHP', 'Rust', 'Ruby']" :key="sdk" class="flex items-center gap-2 px-4 py-2 bg-surface-container-lowest border border-outline-variant/10 rounded-full text-xs font-semibold text-on-surface-variant hover:border-primary/30 hover:text-primary transition-colors cursor-default">
              <span class="w-2 h-2 rounded-full bg-primary/40"></span>
              {{ sdk }}
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 6: Compliance Spotlight                       -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section class="py-24 md:py-32 bg-void relative overflow-hidden" data-section="compliance">
        <!-- Background layers -->
        <div class="absolute inset-0 noise-overlay opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:5rem_5rem] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-8">
          <!-- Section Header -->
          <div class="reveal text-center mb-20">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-pulse/10 border border-pulse/20 rounded-full mb-6">
              <span class="material-symbols-outlined text-pulse text-sm" style="font-variation-settings: 'FILL' 1;">shield</span>
              <span class="text-pulse font-mono text-[10px] font-bold tracking-widest uppercase">Enterprise-Grade Compliance</span>
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-text-primary tracking-tighter font-headline leading-[0.95]">Regulatory Resilience<br/>by Design</h2>
            <p class="text-text-muted mt-6 text-lg max-w-2xl mx-auto leading-relaxed">Don't let changing regulations slow your growth. Noryxon provides the cryptographic breadcrumbs required by modern tax authorities — without compromising the decentralized ethos.</p>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
            <!-- Audit Log Card — wide -->
            <div class="reveal lg:col-span-7">
              <div class="bg-ledger p-8 rounded-2xl border border-ledger-border shadow-2xl h-full">
                <div class="flex items-center justify-between mb-6">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                    <div class="w-3 h-3 rounded-full bg-pulse/80"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                  </div>
                  <div class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 bg-pulse rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-mono text-text-muted tracking-widest uppercase">audit_log_v2.json — LIVE</span>
                  </div>
                </div>
                <div class="space-y-3 font-mono text-xs text-text-muted overflow-hidden min-h-[160px]">
                  <p v-for="(log, idx) in displayedComplianceLogs" :key="'cl-'+idx" class="transition-opacity duration-500" :class="log.color === 'text-tertiary' ? 'text-green-400' : log.color === 'text-primary' ? 'text-pulse' : 'text-text-muted/80'">
                    {{ log.text }}
                  </p>
                  <p class="animate-blink text-text-muted/40">_</p>
                </div>
                <!-- Log footer -->
                <div class="mt-6 pt-4 border-t border-ledger-border flex items-center justify-between">
                  <span class="font-mono text-[10px] text-text-muted/60">ENTRIES_COMMITTED: {{ complianceVisible ? formatCompact(auditLogs) : '0' }}</span>
                  <span class="font-mono text-[10px] text-green-400/80">STATUS: IMMUTABLE</span>
                </div>
              </div>
            </div>

            <!-- Stats Column -->
            <div class="reveal lg:col-span-5 grid grid-cols-2 gap-4">
              <div class="bg-ledger border border-ledger-border rounded-2xl p-6 flex flex-col justify-between group hover:border-pulse/30 transition-colors">
                <span class="material-symbols-outlined text-pulse text-2xl mb-4">speed</span>
                <div>
                  <p class="font-black text-pulse text-3xl mb-1 font-headline tracking-tighter">{{ complianceVisible ? uptimeSLA.toFixed(1) + '%' : '0%' }}</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-text-muted">Uptime SLA</p>
                </div>
              </div>
              <div class="bg-ledger border border-ledger-border rounded-2xl p-6 flex flex-col justify-between group hover:border-pulse/30 transition-colors">
                <span class="material-symbols-outlined text-pulse text-2xl mb-4">receipt_long</span>
                <div>
                  <p class="font-black text-text-primary text-3xl mb-1 font-headline tracking-tighter">{{ complianceVisible ? formatCompact(auditLogs) : '0' }}</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-text-muted">Audit Entries</p>
                </div>
              </div>
              <div class="bg-ledger border border-ledger-border rounded-2xl p-6 flex flex-col justify-between group hover:border-pulse/30 transition-colors">
                <span class="material-symbols-outlined text-green-400 text-2xl mb-4">description</span>
                <div>
                  <p class="font-black text-green-400 text-3xl mb-1 font-headline tracking-tighter">{{ complianceVisible ? formatCompact(datReports) : '0' }}</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-text-muted">DAT Reports</p>
                </div>
              </div>
              <div class="bg-ledger border border-ledger-border rounded-2xl p-6 flex flex-col justify-between group hover:border-pulse/30 transition-colors">
                <span class="material-symbols-outlined text-text-primary text-2xl mb-4">bolt</span>
                <div>
                  <p class="font-black text-text-primary text-3xl mb-1 font-headline tracking-tighter">&lt; 45ms</p>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-text-muted">Avg Latency</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Compliance trust badges -->
          <div class="reveal grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-3 bg-ledger border border-ledger-border rounded-xl px-5 py-4 hover:border-pulse/20 transition-colors">
              <span class="material-symbols-outlined text-pulse" style="font-variation-settings: 'FILL' 1;">verified_user</span>
              <div>
                <p class="text-xs font-bold text-text-primary">SOC 2 Type II</p>
                <p class="text-[10px] text-text-muted">Audit Compliant</p>
              </div>
            </div>
            <div class="flex items-center gap-3 bg-ledger border border-ledger-border rounded-xl px-5 py-4 hover:border-pulse/20 transition-colors">
              <span class="material-symbols-outlined text-pulse" style="font-variation-settings: 'FILL' 1;">lock</span>
              <div>
                <p class="text-xs font-bold text-text-primary">AES-256</p>
                <p class="text-[10px] text-text-muted">End-to-End Encryption</p>
              </div>
            </div>
            <div class="flex items-center gap-3 bg-ledger border border-ledger-border rounded-xl px-5 py-4 hover:border-pulse/20 transition-colors">
              <span class="material-symbols-outlined text-pulse" style="font-variation-settings: 'FILL' 1;">gavel</span>
              <div>
                <p class="text-xs font-bold text-text-primary">GDPR Ready</p>
                <p class="text-[10px] text-text-muted">Data Protection</p>
              </div>
            </div>
            <div class="flex items-center gap-3 bg-ledger border border-ledger-border rounded-xl px-5 py-4 hover:border-pulse/20 transition-colors">
              <span class="material-symbols-outlined text-pulse" style="font-variation-settings: 'FILL' 1;">policy</span>
              <div>
                <p class="text-xs font-bold text-text-primary">No Custody</p>
                <p class="text-[10px] text-text-muted">Zero Fund Exposure</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 7: Pricing                                    -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section id="pricing" class="py-24 md:py-32 bg-surface relative overflow-hidden">
        <div class="absolute w-[600px] h-[600px] -top-60 -right-60 bg-primary-container/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute w-[400px] h-[400px] -bottom-40 -left-40 bg-tertiary-container/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-6 md:px-8 relative z-10">
          <div class="text-center mb-20">
            <div class="reveal inline-flex items-center gap-2 px-4 py-1.5 bg-primary/5 border border-primary/15 rounded-full mb-6">
              <span class="material-symbols-outlined text-primary text-sm">payments</span>
              <span class="text-primary font-mono text-[10px] font-bold tracking-widest uppercase">Transparent Pricing</span>
            </div>
            <h2 class="reveal text-4xl md:text-6xl font-black text-on-surface tracking-tighter font-headline leading-[0.95]">Built to Scale<br/>With You</h2>
            <p class="reveal text-on-surface-variant mt-6 text-lg max-w-xl mx-auto leading-relaxed">Start free. Pay only when you grow. No hidden fees, no lock-in, no surprises.</p>
          </div>

          <div class="grid md:grid-cols-3 gap-6 items-stretch">
            <!-- Starter -->
            <div class="reveal relative p-8 rounded-2xl bg-surface-container-lowest border border-outline-variant/15 shadow-sm flex flex-col hover:-translate-y-2 hover:shadow-xl hover:border-primary/20 transition-all duration-300">
              <div class="mb-6">
                <div class="w-10 h-10 rounded-xl bg-surface-container-low flex items-center justify-center mb-4">
                  <span class="material-symbols-outlined text-primary">code</span>
                </div>
                <h3 class="text-lg font-bold font-headline">Starter</h3>
                <p class="text-on-surface-variant text-sm mt-1">For indie developers and early projects.</p>
              </div>
              <div class="mb-6">
                <span class="text-5xl font-black tracking-tighter font-headline">Free</span>
                <span class="text-on-surface-variant text-sm ml-1">forever</span>
              </div>
              <div class="text-xs text-on-surface-variant mb-6 pb-6 border-b border-outline-variant/10">Up to $10,000/month in documented volume</div>
              <ul class="space-y-3 mb-8 flex-grow">
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-primary text-lg">check_circle</span>12+ Blockchain Networks</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-primary text-lg">check_circle</span>Basic DAT Reporting</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-primary text-lg">check_circle</span>REST API + Webhooks</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-primary text-lg">check_circle</span>Community Support</li>
                <li class="flex items-center gap-2.5 text-sm text-on-surface-variant/50"><span class="material-symbols-outlined text-on-surface-variant/30 text-lg">remove</span>White-label</li>
                <li class="flex items-center gap-2.5 text-sm text-on-surface-variant/50"><span class="material-symbols-outlined text-on-surface-variant/30 text-lg">remove</span>Dedicated Support</li>
              </ul>
              <Link href="/register" class="w-full py-3.5 rounded-xl font-bold border-2 border-primary text-primary hover:bg-primary hover:text-white transition-all text-center block text-sm">Start Building</Link>
            </div>

            <!-- Pro — Highlighted -->
            <div class="reveal relative p-8 rounded-2xl bg-surface-container-lowest border-2 border-primary shadow-xl shadow-primary/10 flex flex-col hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 md:-mt-4 md:mb-[-16px]">
              <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                <span class="bg-primary text-white text-[10px] font-black px-4 py-1 rounded-full uppercase tracking-wider shadow-lg shadow-primary/30">Most Popular</span>
              </div>
              <div class="mb-6 mt-2">
                <div class="w-10 h-10 rounded-xl cta-gradient flex items-center justify-center mb-4">
                  <span class="material-symbols-outlined text-white">rocket_launch</span>
                </div>
                <h3 class="text-lg font-bold font-headline">Pro</h3>
                <p class="text-on-surface-variant text-sm mt-1">For growing businesses and serious builders.</p>
              </div>
              <div class="mb-6">
                <span class="text-5xl font-black tracking-tighter font-headline">0.5%</span>
                <span class="text-on-surface-variant text-sm ml-1">per transaction</span>
              </div>
              <div class="text-xs text-on-surface-variant mb-6 pb-6 border-b border-outline-variant/10">Unlimited documented volume</div>
              <ul class="space-y-3 mb-8 flex-grow">
                <li class="flex items-center gap-2.5 text-sm font-medium"><span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>Everything in Starter</li>
                <li class="flex items-center gap-2.5 text-sm font-medium"><span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>Advanced DAT + Tax Reports</li>
                <li class="flex items-center gap-2.5 text-sm font-medium"><span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>Multi-Wallet Support</li>
                <li class="flex items-center gap-2.5 text-sm font-medium"><span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>Priority Email Support</li>
                <li class="flex items-center gap-2.5 text-sm font-medium"><span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>Team Collaboration (5 seats)</li>
                <li class="flex items-center gap-2.5 text-sm font-medium"><span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>Custom Webhook Filters</li>
              </ul>
              <Link href="/register" class="w-full py-3.5 rounded-xl font-bold cta-gradient text-white shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all text-center block text-sm">Get Started</Link>
            </div>

            <!-- Enterprise -->
            <div class="reveal relative p-8 rounded-2xl bg-on-surface text-surface shadow-2xl flex flex-col overflow-hidden hover:-translate-y-2 hover:shadow-[0_25px_60px_-15px] hover:shadow-primary/20 transition-all duration-300">
              <div class="absolute top-0 right-0 p-4">
                <span class="bg-pulse text-void text-[10px] font-black px-3 py-1 rounded-full uppercase animate-pulse-slow">Enterprise</span>
              </div>
              <div class="mb-6">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                  <span class="material-symbols-outlined text-pulse">domain</span>
                </div>
                <h3 class="text-lg font-bold font-headline">Node Operator</h3>
                <p class="text-surface/50 text-sm mt-1">For institutional volume and white-label needs.</p>
              </div>
              <div class="mb-6">
                <span class="text-5xl font-black tracking-tighter font-headline">Custom</span>
                <span class="text-surface/50 text-sm ml-1">quoted</span>
              </div>
              <div class="text-xs text-surface/40 mb-6 pb-6 border-b border-white/10">Volume-based enterprise pricing</div>
              <ul class="space-y-3 mb-8 flex-grow">
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-tertiary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>Everything in Pro</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-tertiary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>Private Instance Deployment</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-tertiary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>Dedicated Compliance Officer</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-tertiary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>White-Label Dashboard</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-tertiary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>24/7 Priority Support + SLA</li>
                <li class="flex items-center gap-2.5 text-sm"><span class="material-symbols-outlined text-tertiary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>Custom Integrations</li>
              </ul>
              <button class="w-full py-3.5 rounded-xl font-bold bg-white/10 text-white border border-white/20 hover:bg-white/20 transition-all text-center text-sm">Contact Sales</button>
            </div>
          </div>

          <!-- Pricing footer -->
          <div class="reveal mt-16 text-center">
            <p class="text-on-surface-variant text-sm">All plans include <span class="font-semibold text-on-surface">zero custody risk</span>, <span class="font-semibold text-on-surface">instant settlement tracking</span>, and <span class="font-semibold text-on-surface">sovereign key management</span>.</p>
            <div class="flex flex-wrap justify-center gap-6 mt-6">
              <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                No credit card required
              </div>
              <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                Cancel anytime
              </div>
              <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                SOC 2 compliant
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 7.5: Trusted By / Social Proof                -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section class="py-16 bg-surface-container-low border-y border-outline-variant/10">
        <div class="max-w-6xl mx-auto px-6 md:px-8">
          <p class="reveal text-center text-xs font-bold text-on-surface-variant/60 uppercase tracking-[0.2em] mb-10">Trusted by teams building the future of finance</p>
          <div class="reveal flex flex-wrap justify-center items-center gap-x-16 gap-y-6 opacity-40">
            <span class="text-2xl font-black tracking-tighter text-on-surface font-headline">Phantom</span>
            <span class="text-2xl font-black tracking-tighter text-on-surface font-headline">Alchemy</span>
            <span class="text-2xl font-black tracking-tighter text-on-surface font-headline">Chainlink</span>
            <span class="text-2xl font-black tracking-tighter text-on-surface font-headline">Moralis</span>
            <span class="text-2xl font-black tracking-tighter text-on-surface font-headline">QuickNode</span>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════ -->
      <!-- SECTION 8: CTA — Vault-Style                          -->
      <!-- ═══════════════════════════════════════════════════════ -->
      <section class="py-20 px-6 md:px-8">
        <div class="reveal max-w-7xl mx-auto rounded-3xl cta-gradient p-12 md:p-20 text-center relative overflow-hidden group">
          <!-- Background layers -->
          <div class="absolute inset-0 opacity-10 pointer-events-none" style="background: radial-gradient(ellipse at top left, rgba(255,255,255,0.3) 0%, transparent 60%), radial-gradient(ellipse at bottom right, rgba(0,0,0,0.2) 0%, transparent 60%);"></div>
          <div class="absolute inset-0 noise-overlay opacity-[0.04] pointer-events-none mix-blend-overlay"></div>
          <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:3rem_3rem] pointer-events-none"></div>

          <!-- Vault framing corners -->
          <div class="absolute top-4 left-4 w-8 h-8 border-t-2 border-l-2 border-white/30 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
          <div class="absolute top-4 right-4 w-8 h-8 border-t-2 border-r-2 border-white/30 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
          <div class="absolute bottom-4 left-4 w-8 h-8 border-b-2 border-l-2 border-white/30 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
          <div class="absolute bottom-4 right-4 w-8 h-8 border-b-2 border-r-2 border-white/30 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>

          <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 border border-white/20 rounded-full mb-8 backdrop-blur-sm">
              <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
              <span class="text-white/80 font-mono text-[10px] font-bold tracking-widest uppercase">Deploy in Under 5 Minutes</span>
            </div>
            <h2 class="text-4xl sm:text-5xl md:text-7xl font-black text-white tracking-tighter mb-6 leading-[0.95] font-headline">
              Ready to bridge the gap<br/>between crypto and compliance?
            </h2>
            <p class="text-white/60 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">Join thousands of developers and businesses using Noryxon to generate compliant invoices, track on-chain payments, and automate tax documentation.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
              <Link href="/register" class="bg-white text-primary px-10 py-5 rounded-xl font-black text-lg shadow-xl hover:scale-105 transition-transform duration-300 inline-block">Start For Free</Link>
              <Link href="/docs" class="bg-white/10 text-white border border-white/30 backdrop-blur-sm px-10 py-5 rounded-xl font-black text-lg hover:bg-white/20 transition-colors inline-block">Read the Docs</Link>
            </div>
            <p class="mt-10 font-mono text-[10px] text-white/30 tracking-widest uppercase">No custody of funds &middot; Non-custodial documentation engine &middot; SOC 2 compliant</p>
          </div>
        </div>
      </section>
    </main>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- SECTION 9: Footer                                      -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <footer class="bg-on-surface relative overflow-hidden">
      <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:5rem_5rem] pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
        <!-- Main Footer Grid -->
        <div class="py-16 grid grid-cols-2 md:grid-cols-5 gap-10">
          <!-- Brand Column -->
          <div class="col-span-2">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-8 h-8 cta-gradient rounded-lg flex items-center justify-center text-white font-bold text-sm">N</div>
              <span class="text-surface/90 text-lg font-extrabold tracking-tighter font-headline">Noryxon</span>
            </div>
            <p class="text-surface/40 text-sm leading-relaxed max-w-xs mb-6">The non-custodial digital asset invoicing and compliance engine trusted by developers worldwide.</p>
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
              <span class="text-green-400 text-xs font-bold uppercase tracking-wider">All Systems Operational</span>
            </div>
          </div>

          <!-- Product -->
          <div>
            <h4 class="text-surface/60 text-[10px] font-bold uppercase tracking-[0.15em] mb-5">Product</h4>
            <ul class="space-y-3">
              <li><a href="#features" class="text-surface/40 hover:text-pulse text-sm transition-colors">Features</a></li>
              <li><Link href="/pricing" class="text-surface/40 hover:text-pulse text-sm transition-colors">Pricing</Link></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">Changelog</a></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">Integrations</a></li>
            </ul>
          </div>

          <!-- Developers -->
          <div>
            <h4 class="text-surface/60 text-[10px] font-bold uppercase tracking-[0.15em] mb-5">Developers</h4>
            <ul class="space-y-3">
              <li><Link href="/docs" class="text-surface/40 hover:text-pulse text-sm transition-colors">Documentation</Link></li>
              <li><Link href="/developer" class="text-surface/40 hover:text-pulse text-sm transition-colors">API Reference</Link></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">SDKs</a></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">Status</a></li>
            </ul>
          </div>

          <!-- Company -->
          <div>
            <h4 class="text-surface/60 text-[10px] font-bold uppercase tracking-[0.15em] mb-5">Company</h4>
            <ul class="space-y-3">
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">About</a></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">Blog</a></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">Careers</a></li>
              <li><a href="#" class="text-surface/40 hover:text-pulse text-sm transition-colors">Contact</a></li>
            </ul>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="py-6 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
          <span class="text-surface/30 text-xs">&copy; {{ new Date().getFullYear() }} Noryxon Inc. All rights reserved.</span>
          <div class="flex items-center gap-6">
            <a href="#" class="text-surface/30 hover:text-surface/60 text-xs transition-colors">Privacy Policy</a>
            <a href="#" class="text-surface/30 hover:text-surface/60 text-xs transition-colors">Terms of Service</a>
            <a href="#" class="text-surface/30 hover:text-surface/60 text-xs transition-colors">Cookie Policy</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';

// ─── NAV STATE ───
const navScrolled = ref(false);

// ─── HERO: Invoice Simulation (ported from HeroStation) ───
const phase = ref(0);
const currentInvoiceId = ref('NX-XXXXXXXX');
const heroAmount = ref('0.00');
const heroToken = ref('USDC');
const heroChain = ref('Ethereum');
const displayHash = ref('0x0000...0000');
const heroLatency = ref('0');
const heroBlockConfirms = ref(0);
const heroGasPrice = ref(0);
const visibleLogs = ref([]);
const progressPercent = computed(() => [0, 25, 50, 75, 100][phase.value] || 0);

// Log slot helpers — all 6 slots always rendered, styled by phase
const logState = (idx) => {
  if (idx < phase.value) return 'text-on-surface-variant'; // completed
  if (idx === phase.value && phase.value > 0) return 'text-on-surface font-semibold'; // active
  return 'text-on-surface-variant/20'; // future / idle
};
const logIcon = (idx) => {
  if (idx < phase.value) return '>'; // completed
  if (idx === phase.value && phase.value > 0) return '~'; // active (with blinking cursor)
  return ''; // future
};

const orbitChains = ref([
  { chain: 'ETH', posX: -5, posY: 20, active: false },
  { chain: 'BTC', posX: 98, posY: 15, active: false },
  { chain: 'SOL', posX: -5, posY: 70, active: false },
  { chain: 'ARB', posX: 98, posY: 60, active: false },
  { chain: 'POL', posX: 40, posY: -8, active: false },
  { chain: 'OPT', posX: 60, posY: 108, active: false },
]);

const particles = [
  { label: 'ETH', x: 8, y: 20, color: 'text-on-surface-variant/20', duration: 6, delay: 0 },
  { label: 'BTC', x: 88, y: 15, color: 'text-primary/15', duration: 7, delay: 1 },
  { label: 'SOL', x: 15, y: 70, color: 'text-on-surface-variant/15', duration: 5, delay: 2 },
  { label: 'USDC', x: 82, y: 65, color: 'text-on-surface-variant/15', duration: 8, delay: 0.5 },
  { label: 'DAI', x: 5, y: 45, color: 'text-primary/10', duration: 6, delay: 3 },
  { label: 'ARB', x: 92, y: 40, color: 'text-on-surface-variant/10', duration: 7, delay: 1.5 },
  { label: 'TRX', x: 25, y: 85, color: 'text-on-surface-variant/10', duration: 5.5, delay: 2.5 },
  { label: 'POL', x: 75, y: 80, color: 'text-primary/10', duration: 6.5, delay: 0.8 },
];

// ─── HERO: Animated Counters ───
const invoicesGenerated = ref(0);
const chainsMonitored = ref(0);
const avgLatency = ref(0);

// ─── CHAIN TICKER ───
const tickerItems = ref([
  { chain: 'BTC', status: 'operational', block: '840,122', hash: '', feature: 'TX VERIFIED' },
  { chain: 'ETH', status: 'operational', block: '19,432,001', hash: '', feature: 'INVOICE READY' },
  { chain: 'SOL', status: 'operational', block: '254,110,892', hash: '', feature: 'DOCUMENTED' },
  { chain: 'ARB', status: 'operational', block: '188,432,100', hash: '', feature: 'TAX REPORTED' },
  { chain: 'TRX', status: 'operational', block: '59,882,109', hash: '', feature: 'TRACKED' },
  { chain: 'POL', status: 'operational', block: '54,345,992', hash: '', feature: 'NO CUSTODY' },
  { chain: 'OPT', status: 'operational', block: '117,832,111', hash: '', feature: 'SDK READY' },
  { chain: 'BASE', status: 'operational', block: '12,445,001', hash: '', feature: 'BRIDGED' },
]);

// ─── FEATURES BENTO: Hash generation ───
const bentoHash1 = ref('');
const bentoHash2 = ref('');

// ─── TERMINAL DEMO (ported from TerminalDemo) ───
const termIsSimulating = ref(false);
const termIsComplete = ref(false);
const termSimLogs = ref([]);
const termAmount = ref('1250.00');
const termCurrency = ref('USDC');
const termChain = ref('ethereum');
const termSessionId = ref('inv_982x7a');
const termAddress = ref('0x7A250d5630B4cF539739dF2C5dAcb4c659F2488D');

// QR pattern (static randomized)
const qrPattern = Array.from({ length: 36 }, () => Math.random() > 0.4);

// ─── COMPLIANCE: Scroll-triggered counters ───
const complianceVisible = ref(false);
const uptimeSLA = ref(0);
const auditLogs = ref(0);
const datReports = ref(0);

const complianceLogSets = [
  [
    { text: '{"status": "verified", "block": 18459203}', color: 'text-tertiary' },
    { text: '{"event": "INVOICE_CREATED", "id": "NX-8821"}', color: '' },
    { text: '{"hash": "0x4f2e...9a21", "compliance": "DAT_PASSED"}', color: '' },
    { text: '{"action": "SETTLEMENT_DETECTED", "timestamp": 1704067200}', color: 'text-primary' },
  ],
  [
    { text: '{"event": "TX_DETECTED", "chain": "solana"}', color: 'text-tertiary' },
    { text: '{"invoice": "NX-9102", "amount": "5000 USDC"}', color: '' },
    { text: '{"verification": "BLOCK_CONFIRMED", "count": 32}', color: '' },
    { text: '{"tax_class": "DAT", "report_generated": true}', color: 'text-primary' },
  ],
  [
    { text: '{"chain": "arbitrum", "block": 188432100}', color: 'text-tertiary' },
    { text: '{"reconciliation": "CROSS_CHAIN_MATCH"}', color: '' },
    { text: '{"invoice": "NX-9205", "status": "documented"}', color: '' },
    { text: '{"audit_log": "IMMUTABLE_COMMITTED"}', color: 'text-primary' },
  ],
];
const complianceLogIndex = ref(0);
const displayedComplianceLogs = computed(() => complianceLogSets[complianceLogIndex.value % complianceLogSets.length]);

// ─── TRACKING FOR CLEANUP ───
let intervals = [];
let timeouts = [];
let observer = null;
let scrambleInterval = null;
let termSimInterval = null;
let termResetTimeout = null;
let termMainLoop = null;

// ─── UTILITY FUNCTIONS ───
const hexChars = 'ABCDEFabcdef0123456789';
const tokens = ['USDC', 'USDT', 'ETH', 'BTC', 'DAI', 'SOL'];
const chains = ['Ethereum', 'Solana', 'Polygon', 'Arbitrum', 'Optimism', 'Bitcoin'];
const protocols = ['ethereum', 'solana', 'polygon', 'arbitrum', 'optimism', 'bitcoin'];

const generateHash = () => '0x' + Array.from({ length: 8 }, () => hexChars[Math.floor(Math.random() * hexChars.length)]).join('') + '...' + Array.from({ length: 4 }, () => hexChars[Math.floor(Math.random() * hexChars.length)]).join('');
const generateShortHash = () => Array.from({ length: 8 }, () => Math.floor(Math.random() * 16).toString(16)).join('');
const generateInvoiceId = () => 'NX-' + Math.random().toString(36).substring(2, 10).toUpperCase();
const generateMockAddress = () => '0x' + Array.from({ length: 40 }, () => hexChars[Math.floor(Math.random() * hexChars.length)]).join('');
const generateMockId = () => 'inv_' + Math.random().toString(36).substring(2, 8);
const formatNumber = (n) => n.toLocaleString();
const formatCompact = (n) => {
  if (n >= 1000000) return (n / 1000000).toFixed(1) + 'M+';
  if (n >= 1000) return (n / 1000).toFixed(0) + 'K+';
  return n.toString();
};

// Glitch text decode (from ProtocolGrid)
const glitchChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
const decodeText = (event, originalText) => {
  let iteration = 0;
  const el = event.target;
  clearInterval(el.dataset.interval);
  el.dataset.interval = setInterval(() => {
    el.innerText = originalText.split('').map((letter, index) => {
      if (index < iteration) return originalText[index];
      return glitchChars[Math.floor(Math.random() * glitchChars.length)];
    }).join('');
    if (iteration >= originalText.length) clearInterval(el.dataset.interval);
    iteration += 0.5;
  }, 30);
};

// Counter animation
const animateCounter = (target, endValue, duration = 2000, isFloat = false) => {
  const start = performance.now();
  const step = (now) => {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3);
    target.value = isFloat ? parseFloat((endValue * eased).toFixed(1)) : Math.floor(endValue * eased);
    if (progress < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
};

// ─── HERO INVOICE SIMULATION (from HeroStation) ───
const heroLogs = [
  'Detecting on-chain transaction...',
  'Verifying block confirmations...',
  'Cross-referencing wallet addresses...',
  'Generating compliant invoice document...',
  'Computing Digital Asset Tax classification...',
  'Attaching tax report to invoice...',
];

const clearHeroTimeouts = () => {
  timeouts.forEach(t => clearTimeout(t));
  timeouts = [];
};

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

const runHeroSimulation = () => {
  clearHeroTimeouts();
  phase.value = 0;
  visibleLogs.value = [];

  const chainIdx = Math.floor(Math.random() * chains.length);
  heroToken.value = tokens[Math.floor(Math.random() * tokens.length)];
  heroChain.value = chains[chainIdx];
  heroAmount.value = (Math.random() * 9500 + 100).toFixed(2);
  currentInvoiceId.value = generateInvoiceId();
  displayHash.value = '0x0000...0000';
  heroLatency.value = (Math.random() * 30 + 35).toFixed(0);
  heroBlockConfirms.value = 0;
  heroGasPrice.value = Math.floor(Math.random() * 40 + 12);

  orbitChains.value.forEach((o, i) => o.active = i === chainIdx % orbitChains.value.length);

  timeouts.push(setTimeout(() => {
    phase.value = 1;
    heroBlockConfirms.value = 1;
  }, 600));

  timeouts.push(setTimeout(() => {
    scrambleHash();
    heroBlockConfirms.value = 4;
  }, 1200));

  timeouts.push(setTimeout(() => {
    phase.value = 2;
    heroBlockConfirms.value = 12;
  }, 2200));

  timeouts.push(setTimeout(() => {
    phase.value = 3;
    heroBlockConfirms.value = 24;
  }, 3500));

  timeouts.push(setTimeout(() => {
    phase.value = 4;
    heroBlockConfirms.value = 32;
    orbitChains.value.forEach(o => o.active = false);
  }, 5000));

  timeouts.push(setTimeout(runHeroSimulation, 9000));
};

// ─── TERMINAL DEMO SIMULATION (from TerminalDemo) ───
const runTerminalSim = () => {
  if (termIsSimulating.value) return;

  termChain.value = protocols[Math.floor(Math.random() * protocols.length)];
  termCurrency.value = tokens[Math.floor(Math.random() * tokens.length)];
  termAmount.value = (Math.random() * 8500 + 50).toFixed(2);
  termSessionId.value = generateMockId();
  termAddress.value = generateMockAddress();

  termIsSimulating.value = true;
  termIsComplete.value = false;
  termSimLogs.value = [];

  const logs = [
    'Connecting to Noryxon Invoice Engine...',
    'Verifying on-chain transaction...',
    `POST /v1/invoices (Payload: 112 bytes)`,
    `DETECTED: ${termAmount.value} ${termCurrency.value} on ${termChain.value}...`,
    'Generating compliant invoice with payer details...',
    'Computing Digital Asset Tax documentation...',
    '200 OK - Invoice generated. Tax report ready.',
  ];

  clearInterval(termSimInterval);
  clearTimeout(termResetTimeout);

  let i = 0;
  termSimInterval = setInterval(() => {
    if (i < logs.length) {
      termSimLogs.value.push(logs[i]);
      i++;
    } else {
      clearInterval(termSimInterval);
      termResetTimeout = setTimeout(() => {
        termIsSimulating.value = false;
        termIsComplete.value = true;
      }, 400);
    }
  }, 400);
};

const loopTerminalSim = () => {
  runTerminalSim();
  setTimeout(() => {
    termIsComplete.value = false;
    termSimLogs.value = [];
    termMainLoop = setTimeout(loopTerminalSim, Math.random() * 5000 + 2000);
  }, 5500);
};

// ─── LIFECYCLE ───
const handleScroll = () => {
  navScrolled.value = window.scrollY > 80;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true });

  // Initialize ticker hashes
  tickerItems.value = tickerItems.value.map(item => ({ ...item, hash: generateShortHash() }));
  bentoHash1.value = generateShortHash();
  bentoHash2.value = generateShortHash();

  // Hero counters (delayed)
  setTimeout(() => {
    animateCounter(invoicesGenerated, 847293, 2500);
    animateCounter(chainsMonitored, 12, 1500);
    animateCounter(avgLatency, 45, 1800);
  }, 600);

  // Hero simulation
  timeouts.push(setTimeout(runHeroSimulation, 800));

  // Ticker refresh
  intervals.push(setInterval(() => {
    tickerItems.value = tickerItems.value.map(item => ({
      ...item,
      hash: generateShortHash(),
      block: Math.random() > 0.8 ? (parseInt(item.block.replace(/,/g, '')) + Math.floor(Math.random() * 100)).toLocaleString() : item.block,
    }));
  }, 3000));

  // Bento hash refresh
  intervals.push(setInterval(() => {
    bentoHash1.value = generateShortHash();
    bentoHash2.value = generateShortHash();
  }, 2000));

  // Terminal demo
  termMainLoop = setTimeout(loopTerminalSim, 1500);

  // Compliance log cycling
  intervals.push(setInterval(() => {
    complianceLogIndex.value++;
  }, 4000));

  // IntersectionObserver for scroll reveals
  nextTick(() => {
    observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          const section = entry.target.dataset.section;
          if (section === 'compliance' && !complianceVisible.value) {
            complianceVisible.value = true;
            animateCounter(uptimeSLA, 99.9, 2000, true);
            animateCounter(auditLogs, 2400000, 2500);
            animateCounter(datReports, 184000, 2200);
          }
        }
      });
    }, { threshold: 0.15 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  });
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  intervals.forEach(i => clearInterval(i));
  clearHeroTimeouts();
  clearInterval(scrambleInterval);
  clearInterval(termSimInterval);
  clearTimeout(termResetTimeout);
  clearTimeout(termMainLoop);
  if (observer) observer.disconnect();
});
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }

/* Gradient mesh drift */
@keyframes drift-1 {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(80px, -40px) scale(1.1); }
  66% { transform: translate(-40px, 60px) scale(0.95); }
}
@keyframes drift-2 {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(-60px, 30px) scale(1.05); }
  66% { transform: translate(50px, -50px) scale(0.9); }
}
@keyframes drift-3 {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(40px, -30px); }
}
.animate-drift-1 { animation: drift-1 20s ease-in-out infinite; }
.animate-drift-2 { animation: drift-2 25s ease-in-out infinite; }
.animate-drift-3 { animation: drift-3 18s ease-in-out infinite; }

/* Marquee */
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-100%); }
}
.animate-marquee { animation: marquee 40s linear infinite; }

/* Float particles */
@keyframes float-particle {
  0%, 100% { opacity: 0; transform: translateY(0px); }
  20% { opacity: 0.5; }
  50% { transform: translateY(-20px); opacity: 0.3; }
  80% { opacity: 0.5; }
}

/* Scan line */
@keyframes scan {
  0% { top: -5%; opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { top: 105%; opacity: 0; }
}
.animate-scan { animation: scan 3s ease-in-out infinite; }

/* Fade up */
@keyframes fade-up {
  from { opacity: 0; transform: translateY(24px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-up { animation: fade-up 0.6s ease-out both; }

/* Slow pulse for glows */
@keyframes pulse-slow {
  0%, 100% { opacity: 0.4; }
  50% { opacity: 0.7; }
}
.animate-pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }

/* Pop in */
@keyframes pop-in {
  0% { transform: scale(0); opacity: 0; }
  80% { transform: scale(1.1); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}
.animate-pop-in { animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

/* Fade in */
@keyframes fade-in {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.5s ease-out; }

/* Shimmer for CTA button */
@keyframes shimmer {
  0% { transform: translateX(-200%) skewX(-12deg); }
  100% { transform: translateX(200%) skewX(-12deg); }
}
.animate-shimmer { animation: shimmer 1.5s ease-in-out; }

/* Travel dot */
@keyframes travel-dot {
  0% { left: 0%; opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { left: 100%; opacity: 0; }
}
.animate-travel-dot { animation: travel-dot 3s ease-in-out infinite; }

/* Blink cursor */
@keyframes blink-cursor {
  0%, 50% { opacity: 1; }
  51%, 100% { opacity: 0; }
}
.animate-blink { animation: blink-cursor 1s step-end infinite; }

/* Scroll reveal */
.reveal {
  opacity: 0;
  transform: translateY(24px);
  transition: opacity 0.7s ease-out, transform 0.7s ease-out;
}
.reveal.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Stagger delays */
.delay-100 { animation-delay: 100ms; transition-delay: 100ms; }
.delay-200 { animation-delay: 200ms; transition-delay: 200ms; }
.delay-300 { animation-delay: 300ms; transition-delay: 300ms; }
.delay-400 { animation-delay: 400ms; transition-delay: 400ms; }

/* Duration helper */
.duration-800 { transition-duration: 800ms; }
</style>
