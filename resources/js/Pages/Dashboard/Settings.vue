<template>
  <DashboardLayout pageTitle="Settings" breadcrumb="SYSTEM > CONFIG > SETTINGS" dashboardType="merchant">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Settings -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Business Profile -->
        <div class="border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-text-primary mb-6 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-pulse"></span>
            Business Profile
          </div>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-text-muted mb-1.5">Business Name</label>
              <input v-model="form.business_name" type="text" placeholder="Your Business Name" class="w-full bg-ledger border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
              <div v-if="form.errors.business_name" class="text-red-500 text-xs mt-1">{{ form.errors.business_name }}</div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-text-muted mb-1.5">Admin Email</label>
              <input v-model="form.email" type="email" class="w-full bg-ledger border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" disabled />
            </div>
            <div>
              <label class="block text-xs font-semibold text-text-muted mb-1.5">Webhook URL</label>
              <input v-model="form.webhook_url" type="url" placeholder="https://api.yourdomain.com/webhooks" class="w-full bg-ledger border border-ledger-border rounded-lg px-4 py-2 text-sm text-text-primary focus:border-pulse focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
              <div v-if="form.errors.webhook_url" class="text-red-500 text-xs mt-1">{{ form.errors.webhook_url }}</div>
            </div>
            <div class="pt-2">
              <button @click="saveProfile" :disabled="form.processing" class="px-5 py-2 mt-2 bg-pulse text-void font-bold rounded-lg text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                Save Changes
              </button>
            </div>
          </div>
        </div>

        <!-- Security -->
        <div class="border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-text-primary mb-6 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-node"></span>
            Security Protocol
          </div>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-4 border border-ledger-border rounded-xl hover:border-pulse/30 transition-colors">
              <div>
                <div class="text-sm text-text-primary font-bold">Two-Factor Authentication</div>
                <div class="text-xs font-medium text-text-muted mt-1">Protect your account with TOTP-based 2FA</div>
              </div>
              <button 
                @click="twoFaEnabled = !twoFaEnabled"
                class="w-12 h-6 rounded-full relative transition-colors cursor-pointer"
                :class="twoFaEnabled ? 'bg-pulse' : 'bg-ledger-border'"
              >
                <div class="absolute top-0.5 w-5 h-5 bg-void rounded-full transition-transform shadow" :class="twoFaEnabled ? 'translate-x-6' : 'translate-x-0.5'"></div>
              </button>
            </div>
            <div class="flex items-center justify-between p-4 border border-ledger-border rounded-xl hover:border-pulse/30 transition-colors">
              <div>
                <div class="text-sm text-text-primary font-bold">IP Whitelist</div>
                <div class="text-xs font-medium text-text-muted mt-1">Restrict dashboard access to specific IPs</div>
              </div>
              <button class="px-4 py-2 rounded-lg border border-ledger-border text-xs font-semibold text-text-muted hover:border-pulse hover:text-pulse hover:bg-ledger transition-colors">
                Configure
              </button>
            </div>
            <div class="flex items-center justify-between p-4 border border-ledger-border rounded-xl hover:border-pulse/30 transition-colors">
              <div>
                <div class="text-sm text-text-primary font-bold">Active Sessions</div>
                <div class="text-xs font-medium text-text-muted mt-1">2 devices currently logged in</div>
              </div>
              <button class="px-4 py-2 rounded-lg border border-ledger-border text-xs font-semibold text-text-muted hover:border-red-500 hover:text-red-500 hover:bg-ledger transition-colors">
                Revoke All
              </button>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="border border-red-500/30 bg-void p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-red-500 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-500"></span>
            Danger Zone
          </div>
          <div class="flex items-center justify-between">
            <div>
              <div class="text-sm text-text-primary font-bold">Delete Account</div>
              <div class="text-xs font-medium text-text-muted mt-1">Permanently remove all data. This action cannot be undone.</div>
            </div>
            <button class="px-4 py-2 rounded-lg border border-red-500/50 text-red-500 text-xs font-semibold hover:bg-red-500 hover:text-void transition-all">
              Delete Account
            </button>
          </div>
        </div>
      </div>

      <!-- Team Members Sidebar -->
      <div class="space-y-6">
        <div class="border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-pulse"></span>
              Team Members
            </div>
            <button class="px-3 py-1.5 rounded-lg border border-ledger-border text-xs font-semibold text-text-muted hover:border-pulse hover:text-pulse hover:bg-ledger transition-colors">
              + Invite
            </button>
          </div>
          <div class="space-y-3">
            <div 
              v-for="member in teamMembers" 
              :key="member.id"
              class="flex items-center gap-3 p-3 border border-ledger-border rounded-xl hover:border-pulse/30 transition-colors"
            >
              <div class="w-9 h-9 border border-ledger-border rounded-full flex items-center justify-center font-bold text-xs text-text-muted bg-ledger shrink-0">
                {{ getInitials(member.name) }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm text-text-primary font-bold truncate">{{ member.name }}</div>
                <div class="text-xs font-medium text-text-muted truncate">{{ member.email }}</div>
              </div>
              <span 
                class="px-2 py-0.5 rounded-md text-[10px] font-semibold tracking-wide shrink-0"
                :class="{
                  'text-pulse bg-pulse/10': member.role === 'Admin',
                  'text-node bg-node/10': member.role === 'Developer',
                  'text-amber-500 bg-amber-500/10': member.role === 'Analyst',
                  'text-text-muted bg-text-muted/10': member.role === 'Support',
                }"
              >
                {{ member.role }}
              </span>
            </div>
          </div>
        </div>

        <!-- Audit Log Preview -->
        <div class="border border-ledger-border bg-void p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-text-primary mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-node"></span>
            Recent Audit Log
          </div>
          <div class="space-y-3 text-xs font-medium text-text-muted">
            <div v-for="log in auditLogs" :key="log.id" class="flex items-start gap-2">
              <span class="text-pulse shrink-0 font-bold">•</span>
              <span>{{ log.action }} — <span class="text-text-primary">{{ formatDateLabel(log.created_at) }}</span></span>
            </div>
            <div v-if="auditLogs.length === 0" class="text-text-muted/50 text-center py-4">No recent activity.</div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  teamMembers: Array,
  auditLogs: Array,
});

const page = usePage();
const user = page.props.auth.user;

const { addNotification } = useNotifications();

const form = useForm({
  business_name: user.business_name || '',
  email: user.email || '',
  webhook_url: user.webhook_url || '',
});

const twoFaEnabled = ref(true);

const saveProfile = () => {
  form.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      addNotification('Profile Updated', 'Your business profile has been updated.', 'success', 3000);
    }
  });
};

const getInitials = (name) => {
  if (!name) return 'U';
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
};

const formatDateLabel = (iso) => {
  const d = new Date(iso);
  const now = new Date();
  const diff = now - d;
  const mins = Math.floor(diff / 60000);
  const hours = Math.floor(mins / 60);
  const days = Math.floor(hours / 24);
  
  if (mins < 1) return 'just now';
  if (mins < 60) return `${mins}m ago`;
  if (hours < 24) return `${hours}h ago`;
  return `${days}d ago`;
};
</script>
