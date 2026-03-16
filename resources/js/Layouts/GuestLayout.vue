<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen bg-void font-sans antialiased">

        <!-- Left branding panel (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden bg-ledger flex-col justify-between p-12 xl:p-16">
            <!-- Grid background -->
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 40px 40px;">
            </div>

            <!-- Glow orb top right -->
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-10"
                style="background: radial-gradient(circle, #00FFA3 0%, transparent 70%);">
            </div>
            <!-- Glow orb bottom left -->
            <div class="absolute -bottom-40 -left-20 w-80 h-80 rounded-full opacity-8"
                style="background: radial-gradient(circle, #00D1FF 0%, transparent 70%);">
            </div>

            <!-- Logo top -->
            <div class="relative z-10">
                <Link href="/" class="inline-flex items-center gap-3 group">
                    <div class="w-8 h-8 border-2 border-pulse flex items-center justify-center group-hover:bg-pulse/10 transition-colors">
                        <div class="w-2 h-2 bg-pulse"></div>
                    </div>
                    <span class="font-black tracking-tighter text-xl uppercase text-text-primary">NORYXON</span>
                </Link>
            </div>

            <!-- Center content -->
            <div class="relative z-10 space-y-8">
                <div class="space-y-4">
                    <div class="font-mono text-xs text-pulse tracking-[0.3em] uppercase">BRIDGE PROTOCOL v2</div>
                    <h1 class="text-4xl xl:text-5xl font-black tracking-tight text-text-primary leading-tight">
                        Enterprise Crypto<br>
                        <span class="text-pulse">Payments</span> Infrastructure
                    </h1>
                    <p class="text-text-muted text-base max-w-md leading-relaxed">
                        Institutional-grade multi-chain settlement layer designed for scale. Zero-trust, always verified.
                    </p>
                </div>

                <!-- Feature list -->
                <div class="space-y-3">
                    <div v-for="item in features" :key="item.label" class="flex items-center gap-3">
                        <div class="w-1 h-1 bg-pulse rounded-full"></div>
                        <span class="text-sm text-text-muted font-mono">{{ item.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Bottom stats bar -->
            <div class="relative z-10 border-t border-ledger-border pt-8 grid grid-cols-3 gap-6">
                <div v-for="stat in stats" :key="stat.label">
                    <div class="text-2xl font-black text-text-primary">{{ stat.value }}</div>
                    <div class="text-xs text-text-muted font-mono mt-1 tracking-wider">{{ stat.label }}</div>
                </div>
            </div>
        </div>

        <!-- Right auth panel -->
        <div class="flex flex-1 flex-col justify-center items-center px-6 py-12 lg:px-12 xl:px-16">
            <!-- Mobile logo -->
            <div class="lg:hidden mb-10">
                <Link href="/" class="inline-flex items-center gap-3">
                    <div class="w-8 h-8 border-2 border-pulse flex items-center justify-center">
                        <div class="w-2 h-2 bg-pulse"></div>
                    </div>
                    <span class="font-black tracking-tighter text-xl uppercase text-text-primary">NORYXON</span>
                </Link>
            </div>

            <div class="w-full max-w-sm">
                <slot />
            </div>

            <!-- Bottom nav links -->
            <div class="mt-12 flex items-center gap-6 text-xs text-text-muted font-mono">
                <Link href="/" class="hover:text-pulse transition-colors">HOME</Link>
                <Link href="/pricing" class="hover:text-pulse transition-colors">PRICING</Link>
                <a href="https://docs.noryxon.com" target="_blank" class="hover:text-pulse transition-colors">DOCS</a>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            features: [
                { label: 'Multi-chain settlement (BTC, ETH, SOL +20)' },
                { label: 'Sub-second confirmation webhooks' },
                { label: 'ISO 27001 compliant key management' },
                { label: 'API-first with SDKs in 8 languages' },
            ],
            stats: [
                { value: '$4.2B+', label: 'SETTLED' },
                { value: '99.99%', label: 'UPTIME' },
                { value: '180+', label: 'COUNTRIES' },
            ]
        };
    }
};
</script>
