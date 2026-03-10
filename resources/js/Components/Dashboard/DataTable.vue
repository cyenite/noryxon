<template>
  <div class="border border-ledger-border bg-void rounded-xl overflow-hidden shadow-sm">
    <!-- Table Header Bar -->
    <div v-if="title" class="flex items-center justify-between px-6 py-4 border-b border-ledger-border bg-void">
      <div class="text-sm font-semibold text-text-primary flex items-center gap-2">
        <span class="w-1.5 h-1.5 bg-pulse rounded-full"></span>
        {{ title }}
      </div>
      <slot name="actions" />
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-ledger-border bg-ledger/30">
            <th 
              v-for="col in columns" 
              :key="col.key"
              class="text-left text-xs font-medium text-text-muted px-6 py-3.5 whitespace-nowrap"
              :class="col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : ''"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="(row, idx) in displayRows" 
            :key="row.id || idx"
            class="border-b border-ledger-border/50 hover:bg-ledger/50 transition-colors group/row"
          >
            <td 
              v-for="col in columns" 
              :key="col.key"
              class="px-6 py-4 text-sm whitespace-nowrap text-text-primary"
              :class="col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : ''"
            >
              <slot :name="'cell-' + col.key" :row="row" :value="row[col.key]">
                <span class="text-text-primary">{{ row[col.key] }}</span>
              </slot>
            </td>
          </tr>
          <tr v-if="!displayRows.length">
            <td :colspan="columns.length" class="px-6 py-12 text-center text-sm text-text-muted">
              No data available
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="rows.length > perPage" class="flex items-center justify-between px-6 py-4 border-t border-ledger-border bg-void">
      <div class="text-xs text-text-muted">
        Showing {{ (currentPage - 1) * perPage + 1 }}-{{ Math.min(currentPage * perPage, rows.length) }} of {{ rows.length }}
      </div>
      <div class="flex items-center gap-1.5">
        <button 
          @click="currentPage = Math.max(1, currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-3 py-1.5 rounded-md border border-ledger-border text-xs font-medium text-text-muted hover:text-text-primary hover:bg-ledger transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
        >
          Previous
        </button>
        <button 
          v-for="p in totalPages" 
          :key="p"
          @click="currentPage = p"
          class="w-8 h-8 flex items-center justify-center rounded-md text-xs font-medium transition-colors"
          :class="p === currentPage 
            ? 'bg-pulse text-void' 
            : 'text-text-muted hover:bg-ledger hover:text-text-primary'"
        >
          {{ p }}
        </button>
        <button 
          @click="currentPage = Math.min(totalPages, currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-3 py-1.5 rounded-md border border-ledger-border text-xs font-medium text-text-muted hover:text-text-primary hover:bg-ledger transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  title: { type: String, default: '' },
  columns: { type: Array, required: true },
  rows: { type: Array, required: true },
  perPage: { type: Number, default: 10 },
});

const currentPage = ref(1);
const totalPages = computed(() => Math.ceil(props.rows.length / props.perPage));
const displayRows = computed(() => {
  const start = (currentPage.value - 1) * props.perPage;
  return props.rows.slice(start, start + props.perPage);
});
</script>
