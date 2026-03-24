<template>
  <DashboardLayout pageTitle="Settings" breadcrumb="SYSTEM > CONFIG > SETTINGS" dashboardType="merchant">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Settings -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Business Profile -->
        <div class="border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            Business Profile
          </div>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Business Name</label>
              <input v-model="form.business_name" type="text" placeholder="Your Business Name" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
              <div v-if="form.errors.business_name" class="text-red-500 text-xs mt-1">{{ form.errors.business_name }}</div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Admin Email</label>
              <input v-model="form.email" type="email" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all" disabled />
            </div>
            <div>
              <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Webhook URL</label>
              <input v-model="form.webhook_url" type="url" placeholder="https://api.yourdomain.com/webhooks" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-lg px-4 py-2 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-pulse focus:outline-none transition-all" />
              <div v-if="form.errors.webhook_url" class="text-red-500 text-xs mt-1">{{ form.errors.webhook_url }}</div>
            </div>
            <div class="pt-2">
              <button @click="saveProfile" :disabled="form.processing" class="px-5 py-2 mt-2 bg-primary text-void font-bold rounded-lg text-sm hover:shadow-[0_0_15px] hover:shadow-pulse/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                Save Changes
              </button>
            </div>
          </div>
        </div>

        <!-- Security -->
        <div class="border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-on-surface mb-6 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-node"></span>
            Security Protocol
          </div>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-4 border border-outline-variant/15 rounded-xl hover:border-primary/30 transition-colors">
              <div>
                <div class="text-sm text-on-surface font-bold">Two-Factor Authentication</div>
                <div class="text-xs font-medium text-on-surface-variant mt-1">Protect your account with TOTP-based 2FA</div>
              </div>
              <button 
                @click="twoFaEnabled = !twoFaEnabled"
                class="w-12 h-6 rounded-full relative transition-colors cursor-pointer"
                :class="twoFaEnabled ? 'bg-primary' : 'bg-surface-container-lowest-border'"
              >
                <div class="absolute top-0.5 w-5 h-5 bg-surface rounded-full transition-transform shadow" :class="twoFaEnabled ? 'translate-x-6' : 'translate-x-0.5'"></div>
              </button>
            </div>
            <div class="flex items-center justify-between p-4 border border-outline-variant/15 rounded-xl hover:border-primary/30 transition-colors">
              <div>
                <div class="text-sm text-on-surface font-bold">IP Whitelist</div>
                <div class="text-xs font-medium text-on-surface-variant mt-1">Restrict dashboard access to specific IPs</div>
              </div>
              <button class="px-4 py-2 rounded-lg border border-outline-variant/15 text-xs font-semibold text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container-low transition-colors">
                Configure
              </button>
            </div>
            <div class="flex items-center justify-between p-4 border border-outline-variant/15 rounded-xl hover:border-primary/30 transition-colors">
              <div>
                <div class="text-sm text-on-surface font-bold">Active Sessions</div>
                <div class="text-xs font-medium text-on-surface-variant mt-1">2 devices currently logged in</div>
              </div>
              <button class="px-4 py-2 rounded-lg border border-outline-variant/15 text-xs font-semibold text-on-surface-variant hover:border-red-500 hover:text-red-500 hover:bg-surface-container-low transition-colors">
                Revoke All
              </button>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="border border-red-500/30 bg-surface p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-red-500 mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-500"></span>
            Danger Zone
          </div>
          <div class="flex items-center justify-between">
            <div>
              <div class="text-sm text-on-surface font-bold">Delete Account</div>
              <div class="text-xs font-medium text-on-surface-variant mt-1">Permanently remove all data. This action cannot be undone.</div>
            </div>
            <button class="px-4 py-2 rounded-lg border border-red-500/50 text-red-500 text-xs font-semibold hover:bg-red-500 hover:text-void transition-all">
              Delete Account
            </button>
          </div>
        </div>
      </div>

      <!-- Team Members Sidebar -->
      <div class="space-y-6">
        <div class="border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <div class="text-sm font-semibold text-on-surface flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-primary"></span>
              Team Members
            </div>
            <button class="px-3 py-1.5 rounded-lg border border-outline-variant/15 text-xs font-semibold text-on-surface-variant hover:border-primary hover:text-primary hover:bg-surface-container-low transition-colors">
              + Invite
            </button>
          </div>
          <div class="space-y-3">
            <div 
              v-for="member in teamMembers" 
              :key="member.id"
              class="flex items-center gap-3 p-3 border border-outline-variant/15 rounded-xl hover:border-primary/30 transition-colors"
            >
              <div class="w-9 h-9 border border-outline-variant/15 rounded-full flex items-center justify-center font-bold text-xs text-on-surface-variant bg-surface-container-lowest shrink-0">
                {{ getInitials(member.name) }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm text-on-surface font-bold truncate">{{ member.name }}</div>
                <div class="text-xs font-medium text-on-surface-variant truncate">{{ member.email }}</div>
              </div>
              <span 
                class="px-2 py-0.5 rounded-md text-[10px] font-semibold tracking-wide shrink-0"
                :class="{
                  'text-primary bg-primary/10': member.role === 'Admin',
                  'text-primary-container bg-node/10': member.role === 'Developer',
                  'text-amber-500 bg-amber-500/10': member.role === 'Analyst',
                  'text-on-surface-variant bg-text-muted/10': member.role === 'Support',
                }"
              >
                {{ member.role }}
              </span>
            </div>
          </div>
        </div>

        <!-- Audit Log Preview -->
        <div class="border border-outline-variant/15 bg-surface p-6 rounded-xl shadow-sm">
          <div class="text-sm font-semibold text-on-surface mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-node"></span>
            Recent Audit Log
          </div>
          <div class="space-y-3 text-xs font-medium text-on-surface-variant">
            <div v-for="log in auditLogs" :key="log.id" class="flex items-start gap-2">
              <span class="text-primary shrink-0 font-bold">•</span>
              <span>{{ log.action }} — <span class="text-on-surface">{{ formatDateLabel(log.created_at) }}</span></span>
            </div>
            <div v-if="auditLogs.length === 0" class="text-on-surface-variant/50 text-center py-4">No recent activity.</div>
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
