<template>
  <DashboardLayout pageTitle="API Keys" breadcrumb="SYSTEM > DEV > AUTHENTICATION" dashboardType="developer">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div class="text-sm font-medium text-on-surface-variant">Manage your API authentication keys. Keep secrets secure.</div>
      <button 
        @click="showGenerateModal = true"
        class="flex items-center gap-2 bg-primary text-void font-bold px-4 py-2 rounded-lg text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Generate Key
      </button>
    </div>

    <!-- Live Keys Section -->
    <div class="mb-8">
      <div class="text-sm font-semibold text-primary mb-4 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
        Production Keys (sk_live_)
      </div>
      <div class="space-y-3">
        <div 
          v-for="key in liveKeys" 
          :key="key.id"
          class="border border-outline-variant/15 bg-surface rounded-xl p-5 flex items-center gap-4 hover:border-primary/30 hover:shadow-lg transition-all"
        >
          <div class="w-10 h-10 border border-primary/30 rounded-full bg-primary/5 flex items-center justify-center text-primary shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-sm text-on-surface font-bold">{{ key.name }}</div>
            <div class="font-mono text-xs text-primary-container mt-1 flex items-center gap-2 bg-surface-container-lowest w-fit px-2 py-0.5 rounded-md border border-outline-variant/15">
              <span>{{ showKey === key.id ? key.key : key.keyMasked }}</span>
              <button @click="showKey = showKey === key.id ? null : key.id" class="text-on-surface-variant hover:text-primary transition-colors" title="Toggle visibility">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
              <button @click="copyToClipboard(key.key)" class="text-on-surface-variant hover:text-primary transition-colors" title="Copy key">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              </button>
            </div>
          </div>
          <div class="text-right shrink-0">
            <div class="text-xs font-semibold text-on-surface-variant">{{ key.requestsToday.toLocaleString() }} req/today</div>
            <div class="text-[11px] font-medium text-on-surface-variant mt-1">Last: {{ key.lastUsed ? key.lastUsed : 'Never' }}</div>
          </div>
          <div class="flex items-center gap-2 shrink-0 ml-4">
            <button @click="revokeKey(key)" class="px-3 py-1.5 rounded-lg border border-red-500/30 text-red-500 text-xs font-semibold hover:bg-red-500 hover:text-void transition-all">
              Revoke
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Test Keys Section -->
    <div>
      <div class="text-sm font-semibold text-amber-500 mb-4 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
        Sandbox Keys (sk_test_)
      </div>
      <div class="space-y-3">
        <div 
          v-for="key in testKeys" 
          :key="key.id"
          class="border border-outline-variant/15 bg-surface rounded-xl p-5 flex items-center gap-4 hover:border-amber-400/30 hover:shadow-lg transition-all"
        >
          <div class="w-10 h-10 border border-amber-400/30 rounded-full bg-amber-400/5 flex items-center justify-center text-amber-400 shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-sm text-on-surface font-bold">{{ key.name }}</div>
            <div class="font-mono text-xs text-amber-400/80 mt-1 flex items-center gap-2 bg-surface-container-lowest w-fit px-2 py-0.5 rounded-md border border-outline-variant/15">
              <span>{{ showKey === key.id ? key.key : key.keyMasked }}</span>
              <button @click="showKey = showKey === key.id ? null : key.id" class="text-on-surface-variant hover:text-amber-400 transition-colors" title="Toggle visibility">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
              <button @click="copyToClipboard(key.key)" class="text-on-surface-variant hover:text-amber-400 transition-colors" title="Copy key">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              </button>
            </div>
          </div>
          <div class="text-right shrink-0">
            <div class="text-xs font-semibold text-on-surface-variant">{{ key.requestsToday.toLocaleString() }} req/today</div>
          </div>
          <div class="flex items-center gap-2 shrink-0 ml-4">
            <button @click="revokeKey(key)" class="px-3 py-1.5 rounded-lg border border-red-500/30 text-red-500 text-xs font-semibold hover:bg-red-500 hover:text-void transition-all">
              Revoke
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Generate Key Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-all duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showGenerateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-surface/80 backdrop-blur-sm" @click.self="showGenerateModal = false">
          <div class="bg-surface-container-lowest border border-outline-variant/15 w-full max-w-md mx-4 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/15 bg-surface/50">
              <div class="text-sm font-semibold text-on-surface flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                Generate API Key
              </div>
              <button @click="showGenerateModal = false" class="text-on-surface-variant hover:text-on-surface transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-5">
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Key Name</label>
                <input v-model="form.name" type="text" placeholder="e.g. Production API" class="w-full bg-surface border border-outline-variant/15 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Environment</label>
                <div class="flex gap-3">
                  <button @click="form.is_test = false" class="flex-1 py-2.5 rounded-lg border text-sm font-semibold transition-all" :class="!form.is_test ? 'border-primary text-primary bg-primary/10 shadow-sm' : 'border-outline-variant/15 text-on-surface-variant hover:border-primary/50'">Live</button>
                  <button @click="form.is_test = true" class="flex-1 py-2.5 rounded-lg border text-sm font-semibold transition-all" :class="form.is_test ? 'border-amber-400 text-amber-500 bg-amber-400/10 shadow-sm' : 'border-outline-variant/15 text-on-surface-variant hover:border-amber-400/50'">Test</button>
                </div>
                <div v-if="form.errors.is_test" class="text-red-500 text-xs mt-1">{{ form.errors.is_test }}</div>
              </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-outline-variant/15 bg-surface/50">
              <button @click="showGenerateModal = false" class="px-5 py-2.5 rounded-lg border border-outline-variant/15 text-on-surface-variant text-sm font-semibold hover:text-on-surface hover:bg-surface-container-lowest transition-colors">Cancel</button>
              <button @click="generateKey" :disabled="form.processing" class="px-5 py-2.5 rounded-lg bg-primary text-void font-bold text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Generate</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  initialApiKeys: Array,
});

const { addNotification } = useNotifications();
const page = usePage();

const showKey = ref(null);
const showGenerateModal = ref(false);

const form = useForm({
  name: '',
  is_test: true,
});

const liveKeys = computed(() => props.initialApiKeys.filter(k => !k.isTest));
const testKeys = computed(() => props.initialApiKeys.filter(k => k.isTest));

// Because the UI allows "showing" the key temporarily, if we just generated one, we'll
// inject it back into the local list since the server only sends hashes.
const flashNewKey = computed(() => page.props.flash?.flash_new_api_key);

const copyToClipboard = (text) => {
  // If text is undefined (which it is for existing keys since they are hashed),
  // we cannot copy it.
  if (!text) {
    addNotification('Error', 'Full API key is no longer available.', 'error', 3000);
    return;
  }
  navigator.clipboard?.writeText(text);
  addNotification('Copied', 'API key copied to clipboard.', 'success', 2000);
};

const generateKey = () => {
  // Set default name if empty
  if (!form.name.trim()) {
    form.name = form.is_test ? 'New Test Key' : 'New Live Key';
  }
  
  form.post(route('developer.api-keys.store'), {
    onSuccess: () => {
      showGenerateModal.value = false;
      const typeStr = form.is_test ? 'test' : 'live';
      
      // The newly generated plain text key is flashed to the session by the controller
      if (flashNewKey.value) {
        // Find the newly created key in the props list. It's the most recent one.
        const newKeyFromProps = props.initialApiKeys[0];
        if (newKeyFromProps) {
           newKeyFromProps.key = flashNewKey.value;
           showKey.value = newKeyFromProps.id;
        }
      }
      
      form.reset();
      addNotification('Key Generated', `New ${typeStr} API key created. Copy it now—it won't be shown again.`, 'success', 6000);
    }
  });
};

const revokeKey = (key) => {
  if (confirm(`Are you sure you want to revoke "${key.name}"? API requests using it will immediately fail.`)) {
    router.delete(route('developer.api-keys.destroy', key.id), {
      preserveScroll: true,
      onSuccess: () => {
        addNotification('Key Revoked', 'The API key has been permanently deactivated.', 'success', 3000);
      }
    });
  }
};
</script>
