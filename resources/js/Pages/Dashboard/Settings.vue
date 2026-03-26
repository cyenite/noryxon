<template>
  <DashboardLayout pageTitle="Settings" breadcrumb="SYSTEM > CONFIG > SETTINGS" dashboardType="merchant">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Settings -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Business Profile -->
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
              <span class="material-symbols-outlined">business</span>
            </div>
            <div>
              <h3 class="text-sm font-bold text-on-surface">Business Profile</h3>
              <p class="text-[11px] text-on-surface-variant">Manage your organization details</p>
            </div>
          </div>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-label font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Business Name</label>
              <input v-model="form.business_name" type="text" placeholder="Your Business Name" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary/30 focus:outline-none transition-all" />
              <div v-if="form.errors.business_name" class="text-error text-xs mt-1">{{ form.errors.business_name }}</div>
            </div>
            <div>
              <label class="block text-xs font-label font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Admin Email</label>
              <input v-model="form.email" type="email" class="w-full bg-surface-container-low border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface-variant cursor-not-allowed" disabled />
            </div>
            <div>
              <label class="block text-xs font-label font-semibold text-on-surface-variant uppercase tracking-wider mb-1.5">Webhook URL</label>
              <input v-model="form.webhook_url" type="url" placeholder="https://api.yourdomain.com/webhooks" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary/30 focus:outline-none transition-all" />
              <div v-if="form.errors.webhook_url" class="text-error text-xs mt-1">{{ form.errors.webhook_url }}</div>
            </div>
            <div class="pt-2">
              <button @click="saveProfile" :disabled="form.processing" class="px-6 py-2.5 cta-gradient text-white font-bold rounded-lg text-sm shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.02] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                Save Changes
              </button>
            </div>
          </div>
        </div>

        <!-- Security -->
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-tertiary-container/10 flex items-center justify-center text-tertiary">
              <span class="material-symbols-outlined">shield</span>
            </div>
            <div>
              <h3 class="text-sm font-bold text-on-surface">Security Protocol</h3>
              <p class="text-[11px] text-on-surface-variant">Manage authentication and access</p>
            </div>
          </div>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-sm transition-all">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-on-surface-variant">passkey</span>
                <div>
                  <div class="text-sm text-on-surface font-bold">Two-Factor Authentication</div>
                  <div class="text-xs text-on-surface-variant mt-0.5">Protect your account with TOTP-based 2FA</div>
                </div>
              </div>
              <button
                @click="twoFaEnabled = !twoFaEnabled"
                class="w-12 h-6 rounded-full relative transition-colors cursor-pointer"
                :class="twoFaEnabled ? 'bg-primary' : 'bg-surface-container-highest'"
              >
                <div class="absolute top-0.5 w-5 h-5 bg-white rounded-full transition-transform shadow-sm" :class="twoFaEnabled ? 'translate-x-6' : 'translate-x-0.5'"></div>
              </button>
            </div>
            <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-sm transition-all">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-on-surface-variant">vpn_lock</span>
                <div>
                  <div class="text-sm text-on-surface font-bold">IP Whitelist</div>
                  <div class="text-xs text-on-surface-variant mt-0.5">Restrict dashboard access to specific IPs</div>
                </div>
              </div>
              <button class="px-4 py-2 rounded-lg border border-outline-variant/10 text-xs font-semibold text-on-surface-variant hover:border-primary hover:text-primary transition-colors">
                Configure
              </button>
            </div>
            <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-sm transition-all">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-on-surface-variant">devices</span>
                <div>
                  <div class="text-sm text-on-surface font-bold">Active Sessions</div>
                  <div class="text-xs text-on-surface-variant mt-0.5">2 devices currently logged in</div>
                </div>
              </div>
              <button class="px-4 py-2 rounded-lg border border-outline-variant/10 text-xs font-semibold text-on-surface-variant hover:border-error hover:text-error transition-colors">
                Revoke All
              </button>
            </div>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-error/20 shadow-sm">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-lg bg-error-container/10 flex items-center justify-center text-error">
              <span class="material-symbols-outlined">warning</span>
            </div>
            <div>
              <h3 class="text-sm font-bold text-error">Danger Zone</h3>
              <p class="text-[11px] text-on-surface-variant">Irreversible actions</p>
            </div>
          </div>
          <div class="flex items-center justify-between p-4 bg-error/5 rounded-xl">
            <div>
              <div class="text-sm text-on-surface font-bold">Delete Account</div>
              <div class="text-xs text-on-surface-variant mt-0.5">Permanently remove all data. This action cannot be undone.</div>
            </div>
            <button class="px-4 py-2 rounded-lg border border-error/50 text-error text-xs font-bold hover:bg-error hover:text-white transition-all">
              Delete Account
            </button>
          </div>
        </div>
      </div>

      <!-- Team Members Sidebar -->
      <div class="space-y-6">
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-lg">group</span>
              <span class="text-sm font-bold text-on-surface">Team Members</span>
            </div>
            <button class="px-3 py-1.5 rounded-lg border border-outline-variant/10 text-xs font-semibold text-on-surface-variant hover:border-primary hover:text-primary transition-colors flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">person_add</span>
              Invite
            </button>
          </div>
          <div class="space-y-3">
            <div
              v-for="member in teamMembers"
              :key="member.id"
              class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl hover:bg-surface-container-lowest hover:shadow-sm transition-all"
            >
              <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs text-white cta-gradient shrink-0">
                {{ getInitials(member.name) }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm text-on-surface font-bold truncate">{{ member.name }}</div>
                <div class="text-xs text-on-surface-variant truncate">{{ member.email }}</div>
              </div>
              <span
                class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide shrink-0"
                :class="{
                  'text-primary bg-primary-container/20': member.role === 'Admin',
                  'text-tertiary bg-tertiary-container/20': member.role === 'Developer',
                  'text-on-tertiary-container bg-tertiary-container': member.role === 'Analyst',
                  'text-on-surface-variant bg-surface-container-high': member.role === 'Support',
                }"
              >
                {{ member.role }}
              </span>
            </div>
          </div>
        </div>

        <!-- Audit Log Preview -->
        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
          <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-secondary text-lg">history</span>
            <span class="text-sm font-bold text-on-surface">Recent Audit Log</span>
          </div>
          <div class="space-y-3 text-xs text-on-surface-variant">
            <div v-for="log in auditLogs" :key="log.id" class="flex items-start gap-2">
              <span class="material-symbols-outlined text-primary text-sm shrink-0 mt-0.5">chevron_right</span>
              <span>{{ log.action }} — <span class="text-on-surface font-medium">{{ formatDateLabel(log.created_at) }}</span></span>
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
  teamMembers: { type: Array, default: () => [] },
  auditLogs: { type: Array, default: () => [] },
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

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
