<template>
  <section class="py-32 relative bg-void border-t border-ledger-border flex justify-center items-center px-6 overflow-hidden">
    <!-- Massive metallic vault styling using dark radial gradients -->
    <div class="absolute inset-0 max-w-5xl mx-auto rounded-full bg-pulse/5 blur-[120px] pointer-events-none"></div>
    
    <!-- Matrix rain subtle overlay -->
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.85%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>

    <div class="relative z-10 w-full max-w-4xl bg-ledger border border-pulse/30 p-12 text-center shadow-2xl group">
      <!-- Institutional framing corners -->
      <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-pulse opacity-50 group-hover:opacity-100 transition-opacity"></div>
      <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-pulse opacity-50 group-hover:opacity-100 transition-opacity"></div>
      <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-pulse opacity-50 group-hover:opacity-100 transition-opacity"></div>
      <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-pulse opacity-50 group-hover:opacity-100 transition-opacity"></div>
      
      <div class="inline-flex items-center gap-2 mb-8 border border-pulse/20 px-4 py-1 bg-pulse/5">
        <svg class="w-4 h-4 text-pulse animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <span class="font-mono text-xs text-pulse tracking-widest">READY FOR PRODUCTION</span>
      </div>

      <h2 class="text-4xl md:text-6xl font-black uppercase text-text-primary tracking-tighter mb-4 relative drop-shadow-xl">
        Start Accepting Crypto.
      </h2>
      
      <p class="text-sm md:text-base text-text-muted font-mono mb-12 max-w-2xl mx-auto border-b border-ledger-border pb-8">
        Stop paying high payment gateway fees and giving up custody of your revenue. Join thousands of merchants routing payments directly to their cold storage.
      </p>
      
      <form action="/dashboard" method="GET" class="flex flex-col sm:flex-row max-w-2xl mx-auto border border-ledger-border focus-within:border-pulse transition-all shadow-xl focus-within:shadow-[0_0_30px] focus-within:shadow-pulse/10 relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <span class="text-pulse font-mono">></span>
        </div>
        <input 
          type="email" 
          name="email"
          placeholder="developer@startup.com_" 
          class="flex-1 bg-void text-pulse px-10 py-5 font-mono text-sm md:text-base placeholder-text-muted/30 focus:outline-none focus:ring-0 appearance-none selection:bg-pulse selection:text-void"
          required
        />
        <button 
          type="submit" 
          class="group/btn relative bg-pulse text-void px-12 py-5 font-bold tracking-widest uppercase hover:text-text-primary transition-colors font-mono overflow-hidden"
        >
          <div class="absolute inset-0 bg-void -translate-x-[101%] group-hover/btn:translate-x-0 transition-transform duration-300 ease-out border-l border-pulse"></div>
          <span class="relative z-10">[ DEPLOY ]</span>
        </button>
      </form>

      <div class="mt-8 font-mono text-[10px] text-text-muted/50 tracking-widest">
        WARNING: ON-CHAIN TRANSACTIONS ARE IMMUTABLE.
      </div>
    </div>
  </section>
</template>

<style>
/* Remove default autofill background in webkit */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-box-shadow: 0 0 0 30px var(--theme-void) inset !important;
    -webkit-text-fill-color: var(--theme-pulse) !important;
}
</style>
