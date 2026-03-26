<template>
  <div class="fixed bottom-6 right-6 z-[100] flex flex-col gap-4 max-w-sm pointer-events-none">
    <TransitionGroup 
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-y-8 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div 
        v-for="notification in notifications" 
        :key="notification.id"
        class="pointer-events-auto bg-surface border p-4 shadow-2xl flex items-start gap-4 relative overflow-hidden"
        :class="{
          'border-primary shadow-[0_0_15px_var(--theme-pulse)]': notification.type === 'success',
          'border-node shadow-[0_0_15px_var(--theme-node)]': notification.type === 'info',
          'border-red-500 shadow-[0_0_15px_rgba(239,68,68,0.5)]': notification.type === 'error'
        }"
      >
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r" 
          :class="{
            'from-pulse to-transparent': notification.type === 'success',
            'from-node to-transparent': notification.type === 'info',
            'from-red-500 to-transparent': notification.type === 'error'
          }"
        ></div>
        
        <div class="mt-1">
          <svg v-if="notification.type === 'success'" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          <svg v-if="notification.type === 'info'" class="w-5 h-5 text-primary-container" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          <svg v-if="notification.type === 'error'" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        
        <div class="flex-1">
          <h4 class="font-bold text-sm text-on-surface tracking-widest uppercase mb-1">{{ notification.title }}</h4>
          <p class="font-mono text-xs text-on-surface-variant leading-relaxed">{{ notification.message }}</p>
        </div>
        
        <button @click="removeNotification(notification.id)" class="text-on-surface-variant hover:text-on-surface transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { useNotifications } from '@/Composables/useNotifications';

const { notifications, removeNotification } = useNotifications();
</script>
