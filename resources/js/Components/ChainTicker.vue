<template>
  <div class="bg-[#020202] border-b border-ledger-border overflow-hidden py-2 relative">
    <!-- Overlay gradients for fade effect on edges -->
    <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-[#020202] to-transparent z-10 pointer-events-none"></div>
    <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-[#020202] to-transparent z-10 pointer-events-none"></div>

    <div class="relative flex overflow-x-hidden group">
      <div class="animate-marquee whitespace-nowrap flex items-center gap-12 group-hover:[animation-play-state:paused] pr-12">
        <div v-for="(item, index) in tickerItems" :key="'first-' + index" class="inline-flex items-center gap-3">
          <!-- Status Indicator -->
          <div class="relative flex h-2 w-2">
            <span v-if="item.status === 'operational'" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pulse opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2" :class="item.status === 'operational' ? 'bg-pulse' : 'bg-red-500'"></span>
          </div>
          
          <div class="font-mono flex items-center gap-4">
            <span class="text-text-primary font-bold tracking-widest">{{ item.chain }}</span>
            <span class="text-text-muted text-xs hidden sm:inline-block">BLK: {{ item.block }}</span>
            <span class="text-text-muted text-xs hidden md:inline-block">HASH: {{ item.hash }}...</span>
            <span class="text-pulse/70 text-xs">
              [{{ item.feature }}]
            </span>
          </div>
        </div>
      </div>

      <!-- Duplicate for seamless looping -->
      <div class="animate-marquee whitespace-nowrap flex items-center gap-12 group-hover:[animation-play-state:paused] pr-12 absolute top-0">
        <div v-for="(item, index) in tickerItems" :key="'second-' + index" class="inline-flex items-center gap-3">
          <div class="relative flex h-2 w-2">
            <span v-if="item.status === 'operational'" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pulse opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2" :class="item.status === 'operational' ? 'bg-pulse' : 'bg-red-500'"></span>
          </div>
          
          <div class="font-mono flex items-center gap-4">
            <span class="text-text-primary font-bold tracking-widest">{{ item.chain }}</span>
            <span class="text-text-muted text-xs hidden sm:inline-block">BLK: {{ item.block }}</span>
            <span class="text-text-muted text-xs hidden md:inline-block">HASH: {{ item.hash }}...</span>
            <span class="text-pulse/70 text-xs">
              [{{ item.feature }}]
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const generateHash = () => Array.from({length: 8}, () => Math.floor(Math.random() * 16).toString(16)).join('');
const generateBlock = (base) => base + Math.floor(Math.random() * 100);

const tickerItems = ref([
  { chain: 'BTC', status: 'operational', block: '840,122', hash: generateHash(), feature: 'NATIVE LIGHTNING' },
  { chain: 'ETH', status: 'operational', block: '19,432,001', hash: generateHash(), feature: 'ZERO GAS FEES' },
  { chain: 'SOL', status: 'operational', block: '254,110,892', hash: generateHash(), feature: 'INSTANT SETTLEMENT' },
  { chain: 'ARB', status: 'operational', block: '188,432,100', hash: generateHash(), feature: 'DIRECT ROUTING' },
  { chain: 'TRX', status: 'operational', block: '59,882,109', hash: generateHash(), feature: 'NO WRAPPED TOKENS' },
  { chain: 'POL', status: 'operational', block: '54,345,992', hash: generateHash(), feature: 'NON-CUSTODIAL' },
  { chain: 'OPT', status: 'operational', block: '117,832,111', hash: generateHash(), feature: 'NO SETUP FEES' },
]);

onMounted(() => {
    setInterval(() => {
        tickerItems.value = tickerItems.value.map(item => ({
            ...item,
            hash: generateHash(),
            // Only update block height occasionally to simulate real blocks
            block: Math.random() > 0.8 ? generateBlock(parseInt(item.block.replace(/,/g, ''))).toLocaleString() : item.block
        }));
    }, 3000);
});
</script>

<style>
.animate-marquee {
  animation: marquee 40s linear infinite;
}

@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-100%); }
}
</style>
