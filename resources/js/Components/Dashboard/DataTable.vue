<template>
  <div class="border border-outline-variant/15 bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm">
    <!-- Table Header Bar -->
    <div v-if="title" class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/15">
      <div class="text-sm font-bold text-on-surface flex items-center gap-2 font-headline">
        <span class="w-1.5 h-1.5 bg-primary-container rounded-full"></span>
        {{ title }}
      </div>
      <slot name="actions" />
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-outline-variant/15 bg-surface-container-low/50">
            <th
              v-for="col in columns"
              :key="col.key"
              class="text-left text-[11px] font-bold text-on-surface-variant/70 px-6 py-3.5 whitespace-nowrap uppercase tracking-wider"
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
            class="border-b border-outline-variant/10 hover:bg-surface-container-low/40 transition-colors group/row"
          >
            <td
              v-for="col in columns"
              :key="col.key"
              class="px-6 py-4 text-sm whitespace-nowrap text-on-surface"
              :class="col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : ''"
            >
              <slot :name="'cell-' + col.key" :row="row" :value="row[col.key]">
                <span class="text-on-surface">{{ row[col.key] }}</span>
              </slot>
            </td>
          </tr>
          <tr v-if="!displayRows.length">
            <td :colspan="columns.length" class="px-6 py-12 text-center text-sm text-on-surface-variant">
              No data available
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="rows.length > perPage" class="flex items-center justify-between px-6 py-4 border-t border-outline-variant/15">
      <div class="text-xs text-on-surface-variant">
        Showing {{ (currentPage - 1) * perPage + 1 }}-{{ Math.min(currentPage * perPage, rows.length) }} of {{ rows.length }}
      </div>
      <div class="flex items-center gap-1.5">
        <button
          @click="currentPage = Math.max(1, currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-3 py-1.5 rounded-lg border border-outline-variant/20 text-xs font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
        >
          Previous
        </button>
        <button
          v-for="p in totalPages"
          :key="p"
          @click="currentPage = p"
          class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors"
          :class="p === currentPage
            ? 'cta-gradient text-white shadow-sm shadow-primary/20'
            : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'"
        >
          {{ p }}
        </button>
        <button
          @click="currentPage = Math.min(totalPages, currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-3 py-1.5 rounded-lg border border-outline-variant/20 text-xs font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
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

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
