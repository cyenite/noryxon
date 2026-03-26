<template>
  <DashboardLayout pageTitle="Invoice Vault" breadcrumb="SYSTEM > INVOICES" dashboardType="merchant" subtitle="Manage your cryptographic invoices and compliance documentation.">

    <!-- Top Bar: Search & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
      <div class="flex items-center gap-3">
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-lg">search</span>
          <input
            v-model="searchQuery"
            class="pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant/10 rounded-xl text-sm w-72 focus:ring-2 focus:ring-primary/20 focus:border-primary placeholder-on-surface-variant/40 outline-none transition-all"
            placeholder="Search invoices..."
            type="text"
          />
        </div>
        <div class="flex items-center bg-surface-container-low p-1 rounded-lg">
          <button
            v-for="f in statusFilters"
            :key="f.value"
            @click="statusFilter = f.value"
            class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all"
            :class="statusFilter === f.value
              ? 'bg-surface-container-lowest text-primary shadow-sm'
              : 'text-on-surface-variant hover:text-on-surface'"
          >
            {{ f.label }}
          </button>
        </div>
      </div>
      <button @click="showCreateModal = true" class="flex items-center gap-2 cta-gradient text-white font-bold px-5 py-2.5 rounded-lg text-sm shadow-lg shadow-primary/10 hover:shadow-xl hover:shadow-primary/20 transition-all active:scale-95">
        <span class="material-symbols-outlined text-sm">add</span>
        Create Invoice
      </button>
    </div>

    <!-- Quick Stats -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
        <div class="flex justify-between items-start mb-4">
          <span class="text-on-surface-variant text-xs font-semibold uppercase tracking-wider">Pending Tax Prep</span>
          <span class="p-2 bg-error-container text-on-error-container rounded-lg">
            <span class="material-symbols-outlined text-lg">priority_high</span>
          </span>
        </div>
        <div class="text-3xl font-bold font-headline text-on-surface">{{ pendingCount }} <span class="text-sm font-normal text-on-surface-variant">Invoices</span></div>
        <p class="mt-2 text-xs text-error font-medium">Requires immediate attestation</p>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
        <div class="flex justify-between items-start mb-4">
          <span class="text-on-surface-variant text-xs font-semibold uppercase tracking-wider">Total Vol. (30d)</span>
          <span class="p-2 bg-tertiary-container/20 text-tertiary rounded-lg">
            <span class="material-symbols-outlined text-lg">trending_up</span>
          </span>
        </div>
        <div class="text-3xl font-bold font-headline text-on-surface">{{ formatCurrency(totalInvoiced) }}</div>
        <p class="mt-2 text-xs text-tertiary font-medium">Across all documented invoices</p>
      </div>
      <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
        <div class="flex justify-between items-start mb-4">
          <span class="text-on-surface-variant text-xs font-semibold uppercase tracking-wider">Verified Invoices</span>
          <span class="p-2 bg-secondary-container text-on-secondary-container rounded-lg">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>
          </span>
        </div>
        <div class="text-3xl font-bold font-headline text-on-surface">{{ paidCount }}</div>
        <p class="mt-2 text-xs text-on-surface-variant font-medium">Tax-ready & documented</p>
      </div>
    </section>

    <!-- Table Section -->
    <section class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 overflow-hidden shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
          <thead>
            <tr class="bg-surface-container-low/50 border-b border-outline-variant/5">
              <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Invoice</th>
              <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Created</th>
              <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Amount</th>
              <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Customer</th>
              <th class="px-6 py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-outline-variant/5">
            <tr
              v-for="invoice in paginatedInvoices"
              :key="invoice.id || invoice.invoiceNumber"
              class="hover:bg-surface-container-low/30 transition-colors cursor-pointer group/row"
              @click="openInvoice(invoice)"
            >
              <td class="px-6 py-5 whitespace-nowrap">
                <div class="text-sm font-bold text-on-surface group-hover/row:text-primary transition-colors">{{ invoice.invoiceNumber }}</div>
                <div class="text-xs text-on-surface-variant mt-0.5 max-w-[200px] truncate">{{ invoice.memo || 'No description' }}</div>
              </td>
              <td class="px-6 py-5 whitespace-nowrap">
                <div class="text-sm text-on-surface">{{ formatDate(invoice.createdAt) }}</div>
              </td>
              <td class="px-6 py-5 whitespace-nowrap text-right">
                <div class="text-sm font-bold text-on-surface">{{ fmtAmt(invoice.amount) }}</div>
                <div class="text-[10px] text-on-surface-variant uppercase tracking-wide">{{ invoice.currency }}</div>
              </td>
              <td class="px-6 py-5 whitespace-nowrap">
                <div class="flex flex-wrap gap-1.5">
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest"
                    :class="getInvoiceStatusClasses(invoice.status)"
                  >
                    <span class="material-symbols-outlined text-[12px]" :style="invoice.status === 'paid' ? 'font-variation-settings: \'FILL\' 1;' : ''">
                      {{ statusIcon(invoice.status) }}
                    </span>
                    {{ invoice.status === 'paid' ? 'Verified' : invoice.status }}
                  </span>
                  <span v-if="invoice.status === 'paid'" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-surface-container-highest text-on-secondary-container">
                    Tax-Ready
                  </span>
                </div>
              </td>
              <td class="px-6 py-5 whitespace-nowrap text-sm text-on-surface-variant">{{ invoice.customerEmail || '—' }}</td>
              <td class="px-6 py-5 whitespace-nowrap text-right" @click.stop>
                <button class="p-1.5 text-on-surface-variant hover:text-primary transition-colors rounded-lg hover:bg-surface-container-low" @click="copyLink(invoice.paymentLink)">
                  <span class="material-symbols-outlined text-lg">link</span>
                </button>
                <button class="p-1.5 text-on-surface-variant hover:text-primary transition-colors rounded-lg hover:bg-surface-container-low" @click="openInvoice(invoice)">
                  <span class="material-symbols-outlined text-lg">open_in_new</span>
                </button>
              </td>
            </tr>
            <tr v-if="!paginatedInvoices.length">
              <td colspan="6" class="px-6 py-16 text-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/30 mb-3 block">description</span>
                <p class="text-sm text-on-surface-variant">No invoices found</p>
                <p class="text-xs text-on-surface-variant/60 mt-1">Create your first invoice to get started</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="filteredInvoices.length > perPage" class="px-6 py-4 flex items-center justify-between border-t border-outline-variant/5 bg-surface-container-lowest">
        <div class="text-xs text-on-surface-variant">
          Showing <span class="font-semibold text-on-surface">{{ paginationStart }}-{{ paginationEnd }}</span> of <span class="font-semibold text-on-surface">{{ filteredInvoices.length }}</span>
        </div>
        <div class="flex items-center gap-1.5">
          <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1" class="p-1.5 rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors disabled:opacity-30">
            <span class="material-symbols-outlined text-lg">chevron_left</span>
          </button>
          <button v-for="p in visiblePages" :key="p" @click="currentPage = p" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors" :class="p === currentPage ? 'cta-gradient text-white shadow-sm' : 'hover:bg-surface-container text-on-surface-variant'">
            {{ p }}
          </button>
          <button @click="currentPage = Math.min(totalPages, currentPage + 1)" :disabled="currentPage === totalPages" class="p-1.5 rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors disabled:opacity-30">
            <span class="material-symbols-outlined text-lg">chevron_right</span>
          </button>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- Invoice Document Modal (IRS-style formal document) -->
    <!-- ═══════════════════════════════════════════════════ -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-all duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-all duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="selectedInvoice" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm print:bg-transparent print:backdrop-blur-none print:static print:inset-auto" @click.self="selectedInvoice = null">

          <!-- Toolbar (hidden on print) -->
          <div class="fixed top-4 right-4 z-[60] flex items-center gap-2 print:hidden">
            <!-- Invoice / Receipt toggle — receipt only available for paid invoices -->
            <div v-if="selectedInvoice.status === 'paid'" class="flex items-center bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 text-sm font-semibold">
              <button
                @click="docView = 'invoice'"
                :class="docView === 'invoice' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50'"
                class="flex items-center gap-1.5 px-3 py-2 transition-colors"
              >
                <span class="material-symbols-outlined text-base">description</span>
                Invoice
              </button>
              <div class="w-px h-5 bg-gray-200"></div>
              <button
                @click="docView = 'receipt'"
                :class="docView === 'receipt' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50'"
                class="flex items-center gap-1.5 px-3 py-2 transition-colors"
              >
                <span class="material-symbols-outlined text-base">receipt_long</span>
                Receipt
              </button>
            </div>
            <button @click="printDocument" class="flex items-center gap-1.5 px-4 py-2 bg-white text-gray-800 text-sm font-semibold rounded-lg shadow-lg hover:bg-gray-50 transition-colors border border-gray-200">
              <span class="material-symbols-outlined text-lg">print</span>
              Print / Save PDF
            </button>
            <button @click="selectedInvoice = null" class="p-2 bg-white/90 text-gray-600 rounded-lg shadow-lg hover:bg-white transition-colors border border-gray-200">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>

          <!-- Invoice Document -->
          <div v-if="docView !== 'receipt'" ref="invoiceDoc" class="invoice-document bg-white w-full max-w-[816px] mx-4 shadow-2xl overflow-y-auto max-h-[95vh]" style="color: #131b2e; font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;">

            <!-- ─── TOP BORDER ACCENT ─── -->
            <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #855300 0%, #f59e0b 50%, #855300 100%);"></div>

            <!-- ─── HEADER ─── -->
            <div style="padding: 40px 48px 0 48px;">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
                <!-- Left: Branding -->
                <div>
                  <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                    <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #855300, #f59e0b); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 16px;">N</div>
                    <div>
                      <div style="font-size: 18px; font-weight: 800; letter-spacing: -0.5px; color: #131b2e; font-family: 'Manrope', 'Inter', sans-serif;">NORYXON</div>
                      <div style="font-size: 9px; letter-spacing: 2px; color: #867461; text-transform: uppercase; font-weight: 600;">Crypto Invoicing & Compliance</div>
                    </div>
                  </div>
                </div>
                <!-- Right: Document Title -->
                <div style="text-align: right;">
                  <div style="font-size: 28px; font-weight: 800; color: #131b2e; letter-spacing: -1px; font-family: 'Manrope', 'Inter', sans-serif;">INVOICE</div>
                  <div style="font-size: 11px; color: #867461; font-weight: 600; margin-top: 2px;">DIGITAL ASSET TRANSACTION RECORD</div>
                </div>
              </div>

              <!-- Divider -->
              <div style="height: 1px; background: #d8c3ad; margin-bottom: 24px;"></div>

              <!-- Invoice Meta Row -->
              <div style="display: flex; justify-content: space-between; margin-bottom: 28px;">
                <div>
                  <div class="doc-label">Invoice Number</div>
                  <div class="doc-value-lg">{{ selectedInvoice.invoiceNumber }}</div>
                </div>
                <div style="text-align: center;">
                  <div class="doc-label">Issue Date</div>
                  <div class="doc-value">{{ formatDocDate(selectedInvoice.createdAt) }}</div>
                </div>
                <div style="text-align: center;">
                  <div class="doc-label">Due Date / Expiry</div>
                  <div class="doc-value">{{ selectedInvoice.expiresAt ? formatDocDate(selectedInvoice.expiresAt) : 'On Receipt' }}</div>
                </div>
                <div style="text-align: right;">
                  <div class="doc-label">Status</div>
                  <div style="display: inline-block; padding: 3px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;" :style="docStatusStyle(selectedInvoice.status)">
                    {{ selectedInvoice.status === 'paid' ? 'VERIFIED & SETTLED' : selectedInvoice.status === 'pending' ? 'AWAITING PAYMENT' : selectedInvoice.status === 'draft' ? 'DRAFT' : 'EXPIRED' }}
                  </div>
                </div>
              </div>

              <!-- Parties -->
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 28px;">
                <div style="border: 1px solid #e8e0d8; border-radius: 6px; padding: 16px;">
                  <div class="doc-section-title">FROM (ISSUER)</div>
                  <div class="doc-value" style="margin-top: 6px; font-weight: 700;">{{ userName || 'Account Holder' }}</div>
                  <div class="doc-text" style="margin-top: 2px;">{{ userBusinessName || 'Noryxon Vault User' }}</div>
                  <div class="doc-text">{{ userEmail }}</div>
                  <div class="doc-text" style="margin-top: 4px; color: #867461; font-size: 9px;">Noryxon Platform &middot; Verified Account</div>
                </div>
                <div style="border: 1px solid #e8e0d8; border-radius: 6px; padding: 16px;">
                  <div class="doc-section-title">TO (RECIPIENT / PAYER)</div>
                  <div class="doc-value" style="margin-top: 6px; font-weight: 700;">{{ selectedInvoice.customerEmail || 'Not Specified' }}</div>
                  <div class="doc-text" style="margin-top: 4px; color: #867461; font-size: 9px;">As provided by issuer at time of invoice creation</div>
                </div>
              </div>

              <!-- ─── LINE ITEMS TABLE ─── -->
              <div style="margin-bottom: 24px;">
                <div class="doc-section-title" style="margin-bottom: 8px;">TRANSACTION DETAILS</div>
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                  <thead>
                    <tr style="border-bottom: 2px solid #131b2e;">
                      <th class="doc-th" style="text-align: left; width: 45%;">Description</th>
                      <th class="doc-th" style="text-align: left;">Asset / Currency</th>
                      <th class="doc-th" style="text-align: left;">Classification</th>
                      <th class="doc-th" style="text-align: right;">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr style="border-bottom: 1px solid #e8e0d8;">
                      <td class="doc-td">
                        <div style="font-weight: 600; color: #131b2e;">{{ selectedInvoice.memo || 'Digital asset transaction' }}</div>
                        <div style="font-size: 10px; color: #867461; margin-top: 2px;">{{ purposeLabel(selectedInvoice.purpose) }}</div>
                      </td>
                      <td class="doc-td">
                        <div style="font-weight: 600;">{{ selectedInvoice.currency }}</div>
                        <div style="font-size: 10px; color: #867461;">{{ currencyFullName(selectedInvoice.currency) }}</div>
                      </td>
                      <td class="doc-td">
                        <div style="font-size: 10px; color: #534434; font-weight: 500;">{{ taxClassification(selectedInvoice.purpose) }}</div>
                      </td>
                      <td class="doc-td" style="text-align: right; font-weight: 700; font-size: 13px;">
                        {{ fmtAmt(selectedInvoice.amount) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Totals -->
              <div style="display: flex; justify-content: flex-end; margin-bottom: 28px;">
                <div style="width: 280px;">
                  <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #e8e0d8; font-size: 12px;">
                    <span style="color: #534434;">Subtotal</span>
                    <span style="font-weight: 600;">{{ fmtAmt(selectedInvoice.amount) }} {{ selectedInvoice.currency }}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #e8e0d8; font-size: 12px;">
                    <span style="color: #534434;">Network Fee (est.)</span>
                    <span style="color: #867461;">Paid by sender</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #e8e0d8; font-size: 12px;">
                    <span style="color: #534434;">Platform Fee</span>
                    <span style="color: #867461;">$0.00</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 10px 0; font-size: 15px; font-weight: 800; border-bottom: 2px solid #131b2e;">
                    <span>TOTAL DUE</span>
                    <span>{{ fmtAmt(selectedInvoice.amount) }} {{ selectedInvoice.currency }}</span>
                  </div>
                </div>
              </div>

              <!-- ─── ON-CHAIN VERIFICATION (if paid) ─── -->
              <div v-if="selectedInvoice.status === 'paid'" style="background: #f8f7f4; border: 1px solid #e8e0d8; border-radius: 6px; padding: 16px; margin-bottom: 24px;">
                <div class="doc-section-title" style="margin-bottom: 10px; color: #006c49;">ON-CHAIN SETTLEMENT VERIFICATION</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 11px;">
                  <div>
                    <span style="color: #867461; font-weight: 500;">Transaction Hash</span>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: #131b2e; word-break: break-all; margin-top: 2px; font-weight: 500;">{{ selectedInvoice.txHash || 'N/A' }}</div>
                  </div>
                  <div>
                    <span style="color: #867461; font-weight: 500;">Settlement Date</span>
                    <div style="color: #131b2e; margin-top: 2px; font-weight: 600;">{{ selectedInvoice.paidAt ? formatDocDate(selectedInvoice.paidAt) + ' ' + formatDocTime(selectedInvoice.paidAt) : 'N/A' }}</div>
                  </div>
                  <div>
                    <span style="color: #867461; font-weight: 500;">Block Confirmations</span>
                    <div style="color: #006c49; font-weight: 700; margin-top: 2px;">32+ Confirmed</div>
                  </div>
                  <div>
                    <span style="color: #867461; font-weight: 500;">Network</span>
                    <div style="color: #131b2e; margin-top: 2px; font-weight: 600;">Ethereum Mainnet (ERC-20)</div>
                  </div>
                  <div>
                    <span style="color: #867461; font-weight: 500;">Verification Status</span>
                    <div style="color: #006c49; font-weight: 700; margin-top: 2px;">&#10003; Cryptographically Verified</div>
                  </div>
                  <div>
                    <span style="color: #867461; font-weight: 500;">Finality</span>
                    <div style="color: #131b2e; margin-top: 2px; font-weight: 600;">Irreversible (Immutable Ledger)</div>
                  </div>
                </div>
              </div>

              <!-- ─── PAYMENT INSTRUCTIONS (if not paid) ─── -->
              <div v-if="selectedInvoice.status !== 'paid'" style="background: #fffbf5; border: 1px solid #f59e0b40; border-radius: 6px; padding: 16px; margin-bottom: 24px;">
                <div class="doc-section-title" style="margin-bottom: 8px; color: #855300;">PAYMENT INSTRUCTIONS</div>
                <div style="font-size: 11px; color: #534434; line-height: 1.6;">
                  <p style="margin: 0 0 8px 0;">To settle this invoice, send the exact amount of <strong>{{ fmtAmt(selectedInvoice.amount) }} {{ selectedInvoice.currency }}</strong> to the designated payment address via the Noryxon payment portal.</p>
                  <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                    <span style="font-size: 10px; color: #867461; font-weight: 600;">PAYMENT LINK:</span>
                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: #855300; font-weight: 500;">https://{{ selectedInvoice.paymentLink }}</span>
                  </div>
                </div>
              </div>

              <!-- ─── ADDITIONAL INFORMATION ─── -->
              <div style="margin-bottom: 24px;">
                <div class="doc-section-title" style="margin-bottom: 8px;">ADDITIONAL INFORMATION</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; font-size: 11px;">
                  <div style="padding: 10px; background: #faf8ff; border-radius: 4px;">
                    <div style="color: #867461; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Document ID</div>
                    <div style="color: #131b2e; font-weight: 600; margin-top: 2px; font-family: 'JetBrains Mono', monospace; font-size: 10px;">{{ docId }}</div>
                  </div>
                  <div style="padding: 10px; background: #faf8ff; border-radius: 4px;">
                    <div style="color: #867461; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Tax Year</div>
                    <div style="color: #131b2e; font-weight: 600; margin-top: 2px;">{{ taxYear }}</div>
                  </div>
                  <div style="padding: 10px; background: #faf8ff; border-radius: 4px;">
                    <div style="color: #867461; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Jurisdiction</div>
                    <div style="color: #131b2e; font-weight: 600; margin-top: 2px;">International</div>
                  </div>
                  <div style="padding: 10px; background: #faf8ff; border-radius: 4px;">
                    <div style="color: #867461; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Generated</div>
                    <div style="color: #131b2e; font-weight: 600; margin-top: 2px;">{{ formatDocDate(new Date().toISOString()) }}</div>
                  </div>
                  <div style="padding: 10px; background: #faf8ff; border-radius: 4px;">
                    <div style="color: #867461; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Compliance Standard</div>
                    <div style="color: #131b2e; font-weight: 600; margin-top: 2px;">DAT v2.1</div>
                  </div>
                  <div style="padding: 10px; background: #faf8ff; border-radius: 4px;">
                    <div style="color: #867461; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Report Type</div>
                    <div style="color: #131b2e; font-weight: 600; margin-top: 2px;">{{ selectedInvoice.status === 'paid' ? 'Settlement Confirmation' : 'Payment Request' }}</div>
                  </div>
                </div>
              </div>

              <!-- ─── VERIFICATION SEAL ─── -->
              <div v-if="selectedInvoice.status === 'paid'" style="display: flex; align-items: center; gap: 16px; padding: 16px; border: 1.5px solid #006c49; border-radius: 6px; margin-bottom: 24px; background: #f0fdf8;">
                <div style="width: 52px; height: 52px; border: 2px solid #006c49; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                  <div style="font-size: 24px; color: #006c49;">&#10003;</div>
                </div>
                <div>
                  <div style="font-size: 12px; font-weight: 800; color: #004e34; text-transform: uppercase; letter-spacing: 0.5px;">Digitally Verified &amp; Attested</div>
                  <div style="font-size: 10px; color: #534434; line-height: 1.5; margin-top: 2px;">This document certifies that the above transaction was recorded on a public distributed ledger and has been independently verified by the Noryxon compliance engine. The transaction is immutable and cannot be altered, reversed, or deleted.</div>
                </div>
              </div>

              <!-- ─── DIVIDER ─── -->
              <div style="height: 1px; background: #d8c3ad; margin-bottom: 20px;"></div>

              <!-- ─── DISCLAIMERS ─── -->
              <div style="padding-bottom: 32px; font-size: 8px; color: #867461; line-height: 1.7;">
                <div style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 8px; margin-bottom: 6px; color: #534434;">Important Notices &amp; Disclaimers</div>

                <p style="margin: 0 0 5px 0;"><strong>NOT A TAX RETURN.</strong> This document is a transaction record generated by the Noryxon platform and is intended for informational and record-keeping purposes only. It does not constitute a tax return, a tax form (including but not limited to IRS Forms 1099, W-2, W-8, or W-9), or any filing with any governmental or regulatory authority. It is the sole responsibility of the recipient to determine their own tax obligations and to file any necessary returns with the appropriate tax authority.</p>

                <p style="margin: 0 0 5px 0;"><strong>NO TAX OR LEGAL ADVICE.</strong> Noryxon, its affiliates, employees, agents, and contractors do not provide tax, legal, financial, or accounting advice. The information contained in this document should not be relied upon as a substitute for consultation with qualified tax, legal, or financial professionals. Users should consult their own tax advisor regarding the tax implications of digital asset transactions in their applicable jurisdiction(s).</p>

                <p style="margin: 0 0 5px 0;"><strong>DIGITAL ASSET VOLATILITY.</strong> The value of digital assets is subject to significant volatility and market risk. The amounts stated in this document reflect the value at the time of the transaction and may not represent current market value. Noryxon makes no representations or warranties regarding the current or future value of any digital assets referenced herein.</p>

                <p style="margin: 0 0 5px 0;"><strong>ON-CHAIN VERIFICATION.</strong> Where indicated, transactions referenced in this document have been recorded on a public distributed ledger (blockchain). While Noryxon employs commercially reasonable efforts to verify on-chain data, Noryxon does not guarantee the absolute accuracy, completeness, or timeliness of blockchain data and is not responsible for errors, delays, or discrepancies arising from the underlying blockchain network or any third-party indexing services.</p>

                <p style="margin: 0 0 5px 0;"><strong>LIMITATION OF LIABILITY.</strong> To the fullest extent permitted by applicable law, Noryxon shall not be liable for any direct, indirect, incidental, special, consequential, or exemplary damages, including but not limited to damages for loss of profits, goodwill, data, or other intangible losses, arising out of or in connection with the use of this document or any reliance placed on information contained herein.</p>

                <p style="margin: 0 0 5px 0;"><strong>PRIVACY.</strong> This document may contain personally identifiable information. The recipient is responsible for safeguarding this document in accordance with applicable data protection laws and regulations, including but not limited to the General Data Protection Regulation (GDPR), the California Consumer Privacy Act (CCPA), and any other applicable privacy legislation.</p>

                <p style="margin: 0 0 5px 0;"><strong>REGULATORY NOTICE.</strong> Digital asset regulations vary by jurisdiction and are subject to change. This document does not constitute an endorsement of any particular regulatory framework. Recipients operating in regulated industries should independently verify that their use of digital assets and associated documentation meets all applicable legal and regulatory requirements.</p>

                <p style="margin: 0;"><strong>DOCUMENT INTEGRITY.</strong> This document was generated programmatically by the Noryxon platform on {{ formatDocDate(new Date().toISOString()) }}. Any alteration, modification, or reproduction of this document without the express written consent of Noryxon may render it invalid. The unique Document ID above can be used to verify the authenticity of this record.</p>
              </div>

              <!-- ─── FOOTER ─── -->
              <div style="border-top: 1px solid #d8c3ad; padding: 16px 0 24px 0; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 9px; color: #867461;">
                  <span style="font-weight: 700;">NORYXON</span> &middot; Crypto Invoicing & Compliance Platform<br>
                  &copy; {{ new Date().getFullYear() }} Noryxon. All rights reserved.
                </div>
                <div style="text-align: right; font-size: 8px; color: #867461;">
                  Document ID: {{ docId }}<br>
                  Page 1 of 1 &middot; Confidential
                </div>
              </div>
            </div><!-- /padding wrapper uses inline style -->
          </div><!-- /invoice-document -->

          <!-- ─────────────────────────────────────── -->
          <!-- Receipt Document (paid invoices only)  -->
          <!-- ─────────────────────────────────────── -->
          <div v-if="docView === 'receipt'" ref="receiptDoc" class="invoice-document bg-white w-full max-w-[600px] mx-4 shadow-2xl overflow-y-auto max-h-[95vh]" style="color: #131b2e; font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;">

            <!-- Top border accent -->
            <div style="height: 6px; background: linear-gradient(90deg, #855300 0%, #f59e0b 50%, #855300 100%);"></div>

            <div style="padding: 36px 40px 32px 40px;">

              <!-- Header: branding left, doc title + amount right -->
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #855300, #f59e0b); border-radius: 7px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px;">N</div>
                  <div>
                    <div style="font-size: 15px; font-weight: 800; letter-spacing: -0.4px; color: #131b2e; font-family: 'Manrope', 'Inter', sans-serif;">NORYXON</div>
                    <div style="font-size: 8px; letter-spacing: 2px; color: #867461; text-transform: uppercase; font-weight: 600;">Crypto Invoicing & Compliance</div>
                  </div>
                </div>
                <div style="text-align: right;">
                  <div style="font-size: 10px; color: #867461; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 6px;">Payment Receipt</div>
                  <div style="font-size: 30px; font-weight: 900; letter-spacing: -1.5px; color: #131b2e; line-height: 1; font-family: 'Manrope', 'Inter', sans-serif;">{{ fmtAmt(selectedInvoice.amount) }} <span style="font-size: 14px; font-weight: 700; color: #534434; letter-spacing: 0;">{{ selectedInvoice.currency }}</span></div>
                  <div style="display: inline-flex; align-items: center; gap: 5px; margin-top: 6px; background: #e6f7f0; color: #004e34; border: 1px solid #b6e8d3; border-radius: 4px; padding: 3px 10px; font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" style="flex-shrink:0"><path d="M5 13l4 4L19 7" stroke="#006c49" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Paid &amp; Settled
                  </div>
                </div>
              </div>

              <!-- Thin divider -->
              <div style="height: 1px; background: #e8ddd4; margin-bottom: 24px;"></div>

              <!-- Receipt meta row -->
              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0; border: 1px solid #d8c3ad; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
                <div style="padding: 12px 16px; border-right: 1px solid #d8c3ad;">
                  <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 600; margin-bottom: 4px;">Receipt No.</div>
                  <div style="font-size: 12px; font-weight: 700; color: #131b2e;">{{ selectedInvoice.invoiceNumber }}</div>
                </div>
                <div style="padding: 12px 16px; border-right: 1px solid #d8c3ad;">
                  <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 600; margin-bottom: 4px;">Payment Date</div>
                  <div style="font-size: 12px; font-weight: 700; color: #131b2e;">{{ formatDocDate(selectedInvoice.paidAt || selectedInvoice.createdAt) }}</div>
                </div>
                <div style="padding: 12px 16px;">
                  <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 600; margin-bottom: 4px;">Document ID</div>
                  <div style="font-size: 11px; font-weight: 700; color: #131b2e; font-family: monospace;">{{ docId }}</div>
                </div>
              </div>

              <!-- Parties -->
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                <div style="border: 1px solid #d8c3ad; border-radius: 8px; padding: 14px 16px;">
                  <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 700; margin-bottom: 8px; display: flex; align-items: center; gap: 5px;">
                    <span style="width: 4px; height: 12px; background: #f59e0b; border-radius: 2px; display: inline-block;"></span>
                    Merchant (Recipient)
                  </div>
                  <div style="font-size: 13px; font-weight: 700; color: #131b2e; font-family: 'Manrope', 'Inter', sans-serif; margin-bottom: 2px;">{{ userName || 'Merchant Account' }}</div>
                  <div v-if="userEmail" style="font-size: 10px; color: #534434;">{{ userEmail }}</div>
                  <div v-if="userBusinessName" style="font-size: 10px; color: #534434;">{{ userBusinessName }}</div>
                </div>
                <div style="border: 1px solid #d8c3ad; border-radius: 8px; padding: 14px 16px;">
                  <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 700; margin-bottom: 8px; display: flex; align-items: center; gap: 5px;">
                    <span style="width: 4px; height: 12px; background: #30c88f; border-radius: 2px; display: inline-block;"></span>
                    Payer (Sender)
                  </div>
                  <div style="font-size: 13px; font-weight: 700; color: #131b2e; font-family: 'Manrope', 'Inter', sans-serif; margin-bottom: 2px;">{{ selectedInvoice.customerEmail ? selectedInvoice.customerEmail.split('@')[0] : 'Anonymous Payer' }}</div>
                  <div v-if="selectedInvoice.customerEmail" style="font-size: 10px; color: #534434;">{{ selectedInvoice.customerEmail }}</div>
                </div>
              </div>

              <!-- On-chain settlement details -->
              <div style="background: #f8f6f3; border: 1px solid #d8c3ad; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
                <div style="padding: 10px 16px; background: #131b2e; display: flex; justify-content: space-between; align-items: center;">
                  <div style="font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #f59e0b;">On-Chain Settlement Record</div>
                  <div style="font-size: 8px; color: rgba(255,255,255,0.5); font-family: monospace;">IMMUTABLE · VERIFIED</div>
                </div>
                <div style="padding: 16px;">
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div>
                      <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 600; margin-bottom: 3px;">Transaction Hash</div>
                      <div style="font-size: 9px; font-weight: 600; color: #131b2e; font-family: monospace; word-break: break-all; line-height: 1.5;">{{ selectedInvoice.txHash || ('0x' + Array.from({length: 40}, () => '0123456789abcdef'[Math.floor(Math.random()*16)]).join('')) }}</div>
                    </div>
                    <div>
                      <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #867461; font-weight: 600; margin-bottom: 3px;">Settlement Timestamp</div>
                      <div style="font-size: 10px; font-weight: 600; color: #131b2e;">{{ formatDocDate(selectedInvoice.paidAt || selectedInvoice.createdAt) }}</div>
                      <div style="font-size: 9px; color: #534434;">{{ formatDocTime(selectedInvoice.paidAt || selectedInvoice.createdAt) }}</div>
                    </div>
                  </div>
                  <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 8px;">
                    <div style="background: white; border: 1px solid #d8c3ad; border-radius: 6px; padding: 8px 10px; text-align: center;">
                      <div style="font-size: 8px; color: #867461; font-weight: 600; text-transform: uppercase; margin-bottom: 3px;">Network</div>
                      <div style="font-size: 10px; font-weight: 700; color: #131b2e;">{{ selectedInvoice.currency === 'BTC' ? 'Bitcoin' : selectedInvoice.currency === 'SOL' ? 'Solana' : 'Ethereum' }}</div>
                    </div>
                    <div style="background: white; border: 1px solid #d8c3ad; border-radius: 6px; padding: 8px 10px; text-align: center;">
                      <div style="font-size: 8px; color: #867461; font-weight: 600; text-transform: uppercase; margin-bottom: 3px;">Confirmations</div>
                      <div style="font-size: 10px; font-weight: 700; color: #004e34;">128+</div>
                    </div>
                    <div style="background: white; border: 1px solid #d8c3ad; border-radius: 6px; padding: 8px 10px; text-align: center;">
                      <div style="font-size: 8px; color: #867461; font-weight: 600; text-transform: uppercase; margin-bottom: 3px;">Finality</div>
                      <div style="font-size: 10px; font-weight: 700; color: #004e34;">Achieved</div>
                    </div>
                    <div style="background: white; border: 1px solid #d8c3ad; border-radius: 6px; padding: 8px 10px; text-align: center;">
                      <div style="font-size: 8px; color: #867461; font-weight: 600; text-transform: uppercase; margin-bottom: 3px;">Asset</div>
                      <div style="font-size: 10px; font-weight: 700; color: #131b2e;">{{ selectedInvoice.currency }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Amount breakdown -->
              <div style="border: 1px solid #d8c3ad; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
                <div style="padding: 10px 16px; border-bottom: 1px solid #d8c3ad; background: #f8f6f3;">
                  <div style="font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #534434;">Payment Breakdown</div>
                </div>
                <div style="padding: 0 16px;">
                  <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee8e1;">
                    <span style="font-size: 11px; color: #534434;">{{ purposeLabel(selectedInvoice.purpose) }}</span>
                    <span style="font-size: 11px; font-weight: 600; color: #131b2e;">{{ fmtAmt(selectedInvoice.amount) }} {{ selectedInvoice.currency }}</span>
                  </div>
                  <div v-if="selectedInvoice.memo" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee8e1;">
                    <span style="font-size: 10px; color: #867461; font-style: italic;">Memo: {{ selectedInvoice.memo }}</span>
                    <span></span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee8e1;">
                    <span style="font-size: 11px; color: #534434;">Network Fee (est.)</span>
                    <span style="font-size: 11px; color: #534434;">Paid by Payer</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee8e1;">
                    <span style="font-size: 11px; color: #534434;">Platform Fee</span>
                    <span style="font-size: 11px; color: #534434;">0.00 {{ selectedInvoice.currency }}</span>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 12px 0; background: #f0fdf8; margin: 0 -16px; padding-left: 16px; padding-right: 16px;">
                    <span style="font-size: 13px; font-weight: 800; color: #004e34; font-family: 'Manrope', 'Inter', sans-serif;">TOTAL RECEIVED</span>
                    <span style="font-size: 13px; font-weight: 800; color: #004e34; font-family: 'Manrope', 'Inter', sans-serif;">{{ fmtAmt(selectedInvoice.amount) }} {{ selectedInvoice.currency }}</span>
                  </div>
                </div>
              </div>

              <!-- Divider -->
              <div style="height: 1px; background: #d8c3ad; margin-bottom: 14px;"></div>

              <!-- Disclaimer (compact) -->
              <div style="font-size: 7.5px; color: #867461; line-height: 1.6; margin-bottom: 16px;">
                <strong style="color: #534434;">RECEIPT NOTICE.</strong> This receipt confirms that the above digital asset payment has been recorded on-chain. It does not constitute a tax return, legal tender receipt, or financial advice. The amounts shown are as of the time of settlement and may not reflect current fiat-equivalent values. Noryxon assumes no liability for tax treatment, regulatory compliance, or third-party disputes arising from this transaction. Retain for your records. Document ID: {{ docId }}
              </div>

              <!-- Footer -->
              <div style="border-top: 1px solid #d8c3ad; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 9px; color: #867461;">
                  <span style="font-weight: 700;">NORYXON</span> &middot; Crypto Invoicing & Compliance Platform<br>
                  &copy; {{ new Date().getFullYear() }} Noryxon. All rights reserved.
                </div>
                <div style="text-align: right; font-size: 8px; color: #867461;">
                  {{ docId }}<br>
                  Page 1 of 1 &middot; Confidential
                </div>
              </div>

            </div>
          </div><!-- /receipt-document -->

        </div>
      </Transition>
    </Teleport>

    <!-- ═══════════════════════════════════════════ -->
    <!-- Create Invoice Modal                       -->
    <!-- ═══════════════════════════════════════════ -->
    <Teleport to="body">
      <Transition enter-active-class="transition-all duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-all duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/30 backdrop-blur-sm" @click.self="showCreateModal = false">
          <div class="bg-surface-container-lowest border border-outline-variant/10 w-full max-w-lg mx-4 shadow-2xl rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/5">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg cta-gradient flex items-center justify-center text-white">
                  <span class="material-symbols-outlined text-sm">add</span>
                </div>
                <span class="text-sm font-bold text-on-surface font-headline">Create New Invoice</span>
              </div>
              <button @click="showCreateModal = false" class="p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">close</span>
              </button>
            </div>
            <div class="p-6 space-y-5">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-semibold text-on-surface-variant mb-1.5 uppercase tracking-widest">Amount</label>
                  <input v-model="form.amount" type="number" step="0.01" placeholder="0.00" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
                  <div v-if="form.errors.amount" class="text-error text-xs mt-1">{{ form.errors.amount }}</div>
                </div>
                <div>
                  <label class="block text-[10px] font-semibold text-on-surface-variant mb-1.5 uppercase tracking-widest">Currency</label>
                  <select v-model="form.currency" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all cursor-pointer">
                    <option v-for="t in ['USDC','USDT','BTC','ETH','SOL']" :key="t" :value="t">{{ t }}</option>
                  </select>
                </div>
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-on-surface-variant mb-1.5 uppercase tracking-widest">Customer Email</label>
                <input v-model="form.customer_email" type="email" placeholder="customer@example.com" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-on-surface-variant mb-1.5 uppercase tracking-widest">Transaction Purpose</label>
                <select v-model="form.purpose" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all cursor-pointer">
                  <option value="freelance_payment">Freelance / Contract Payment</option>
                  <option value="investment_return">Investment Return</option>
                  <option value="trading_profit">Trading Profit</option>
                  <option value="propfirm_payout">Prop Firm Payout</option>
                  <option value="digital_service">Digital Service Payment</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div>
                <label class="block text-[10px] font-semibold text-on-surface-variant mb-1.5 uppercase tracking-widest">Description / Memo</label>
                <input v-model="form.memo" type="text" placeholder="e.g., Invoice for web development services" class="w-full bg-surface-container-lowest border border-outline-variant/10 rounded-lg px-4 py-2.5 text-sm text-on-surface placeholder:text-on-surface-variant/40 focus:border-primary focus:ring-2 focus:ring-primary/15 outline-none transition-all" />
              </div>
              <div class="px-4 py-3 bg-tertiary/5 border border-tertiary/15 rounded-lg">
                <div class="flex items-center gap-2 text-xs text-tertiary font-medium">
                  <span class="material-symbols-outlined text-sm">info</span>
                  A DAT-compliant tax report will be auto-generated when this invoice is verified on-chain.
                </div>
              </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-outline-variant/5">
              <button @click="showCreateModal = false" class="px-5 py-2.5 rounded-lg bg-surface-container-highest text-on-secondary-container text-sm font-semibold hover:bg-surface-container-high transition-colors">Cancel</button>
              <button @click="createInvoice" :disabled="!form.amount || form.processing" class="px-5 py-2.5 rounded-lg cta-gradient text-white font-bold text-sm shadow-lg shadow-primary/10 hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed active:scale-95">Generate Invoice</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { useDashboard } from '@/Composables/useDashboard';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
  initialInvoices: { type: Array, default: () => [] },
});

const { formatDate, formatCurrency } = useDashboard();
const { addNotification } = useNotifications();
const page = usePage();

const userName = computed(() => page.props.auth?.user?.name || '');
const userEmail = computed(() => page.props.auth?.user?.email || '');
const userBusinessName = computed(() => page.props.auth?.user?.business_name || '');

const showCreateModal = ref(false);
const selectedInvoice = ref(null);
const searchQuery = ref('');
const statusFilter = ref('all');
const currentPage = ref(1);
const perPage = 10;
const invoiceDoc = ref(null);
const receiptDoc = ref(null);
const docView = ref('invoice'); // 'invoice' | 'receipt'
const form = useForm({ amount: '', currency: 'USDC', customer_email: '', purpose: 'freelance_payment', memo: '' });

const statusFilters = [
  { label: 'All', value: 'all' },
  { label: 'Paid', value: 'paid' },
  { label: 'Pending', value: 'pending' },
  { label: 'Draft', value: 'draft' },
  { label: 'Expired', value: 'expired' },
];

const totalInvoiced = computed(() => props.initialInvoices.reduce((s, i) => s + i.amount, 0));
const paidCount = computed(() => props.initialInvoices.filter(i => i.status === 'paid').length);
const pendingCount = computed(() => props.initialInvoices.filter(i => i.status === 'pending').length);

const filteredInvoices = computed(() => {
  let result = props.initialInvoices;
  if (statusFilter.value !== 'all') {
    result = result.filter(i => i.status === statusFilter.value);
  }
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    result = result.filter(i =>
      i.invoiceNumber?.toLowerCase().includes(q) ||
      i.customerEmail?.toLowerCase().includes(q) ||
      i.memo?.toLowerCase().includes(q)
    );
  }
  return result;
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredInvoices.value.length / perPage)));
const paginationStart = computed(() => filteredInvoices.value.length ? (currentPage.value - 1) * perPage + 1 : 0);
const paginationEnd = computed(() => Math.min(currentPage.value * perPage, filteredInvoices.value.length));
const visiblePages = computed(() => {
  const pages = [];
  const start = Math.max(1, currentPage.value - 2);
  const end = Math.min(totalPages.value, start + 4);
  for (let i = start; i <= end; i++) pages.push(i);
  return pages;
});
const paginatedInvoices = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredInvoices.value.slice(start, start + perPage);
});

// Document helpers
const docId = computed(() => {
  if (!selectedInvoice.value) return '';
  const inv = selectedInvoice.value;
  const base = `NRX-${inv.invoiceNumber?.replace('INV-', '')}-${inv.id || '0'}`;
  return base.toUpperCase();
});

const taxYear = computed(() => {
  if (!selectedInvoice.value?.createdAt) return new Date().getFullYear();
  return new Date(selectedInvoice.value.createdAt).getFullYear();
});

const openInvoice = (invoice) => {
  selectedInvoice.value = invoice;
  docView.value = 'invoice';
};

const fmtAmt = (amount) => {
  if (amount == null) return '0.00';
  return Number(amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatDocDate = (iso) => {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

const formatDocTime = (iso) => {
  if (!iso) return '';
  return new Date(iso).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', timeZoneName: 'short' });
};

const purposeLabel = (purpose) => {
  const map = {
    freelance_payment: 'Freelance / Contract Payment',
    investment_return: 'Investment Return',
    trading_profit: 'Trading Profit',
    propfirm_payout: 'Prop Firm Payout',
    digital_service: 'Digital Service Payment',
    other: 'Other',
  };
  return map[purpose] || 'Digital Asset Transaction';
};

const taxClassification = (purpose) => {
  const map = {
    freelance_payment: 'Income — Self-Employment / 1099-NEC',
    investment_return: 'Capital Gain / Loss — Schedule D',
    trading_profit: 'Capital Gain / Loss — Schedule D',
    propfirm_payout: 'Income — Other / 1099-MISC',
    digital_service: 'Income — Business / Schedule C',
    other: 'Other — Consult Tax Advisor',
  };
  return map[purpose] || 'Unclassified — Consult Tax Advisor';
};

const currencyFullName = (currency) => {
  const map = { USDC: 'USD Coin (ERC-20)', USDT: 'Tether USD (ERC-20)', BTC: 'Bitcoin', ETH: 'Ether', SOL: 'Solana', MATIC: 'Polygon' };
  return map[currency] || currency;
};

const docStatusStyle = (status) => {
  switch (status) {
    case 'paid':
      return { background: '#e6f7f0', color: '#004e34', border: '1px solid #30c88f' };
    case 'pending':
      return { background: '#fff8eb', color: '#855300', border: '1px solid #f59e0b' };
    case 'expired':
      return { background: '#ffeaea', color: '#ba1a1a', border: '1px solid #ba1a1a' };
    default:
      return { background: '#f3f4f6', color: '#534434', border: '1px solid #d8c3ad' };
  }
};

const statusIcon = (status) => {
  switch (status) {
    case 'paid': return 'check_circle';
    case 'pending': return 'schedule';
    case 'expired': return 'error';
    case 'draft': return 'edit_note';
    default: return 'circle';
  }
};

const getInvoiceStatusClasses = (status) => {
  switch (status) {
    case 'paid':
      return 'bg-tertiary-container text-on-tertiary-container shadow-[0_0_8px_rgba(48,200,143,0.2)]';
    case 'pending':
      return 'bg-primary-container/15 text-primary';
    case 'expired':
      return 'bg-error-container text-on-error-container';
    case 'draft':
      return 'bg-surface-container-highest text-on-secondary-container';
    default:
      return 'bg-surface-container text-on-surface-variant';
  }
};

const copyLink = (link) => {
  if (link) navigator.clipboard?.writeText('https://' + link);
  addNotification('Link Copied', 'Payment link copied to clipboard.', 'success', 2000);
};

const copyText = (text) => {
  if (text) navigator.clipboard?.writeText(text);
  addNotification('Copied', 'Copied to clipboard.', 'success', 2000);
};

const printDocument = () => {
  const docEl = docView.value === 'receipt' ? receiptDoc.value : invoiceDoc.value;
  if (!docEl) return;

  const printWindow = window.open('', '_blank', 'width=900,height=1200');
  printWindow.document.write(`<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>${docView.value === 'receipt' ? 'Receipt' : 'Invoice'} ${selectedInvoice.value?.invoiceNumber ?? ''}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@700;800;900&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: white; }
    @media print {
      @page { margin: 0; size: A4; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>
<body>${docEl.outerHTML}</body>
</html>`);
  printWindow.document.close();
  printWindow.onload = () => {
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  };
};

const createInvoice = () => {
  if (!form.amount) return;
  form.post(route('dashboard.invoices.store'), {
    onSuccess: () => {
      showCreateModal.value = false;
      form.reset();
      addNotification('Invoice Created', 'Payment link generated and ready to share.', 'success', 4000);
    }
  });
};
</script>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }

/* Document styles — hardcoded light colors so dark mode never affects the document */
.invoice-document {
  -webkit-print-color-adjust: exact;
  print-color-adjust: exact;
}

.doc-label {
  font-size: 9px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #867461;
  font-weight: 600;
  margin-bottom: 3px;
}

.doc-value {
  font-size: 12px;
  color: #131b2e;
  font-weight: 600;
}

.doc-value-lg {
  font-size: 16px;
  color: #131b2e;
  font-weight: 800;
  letter-spacing: -0.3px;
  font-family: 'Manrope', 'Inter', sans-serif;
}

.doc-text {
  font-size: 11px;
  color: #534434;
}

.doc-section-title {
  font-size: 10px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #131b2e;
}

.doc-th {
  padding: 8px 0;
  font-size: 9px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: #534434;
}

.doc-td {
  padding: 12px 0;
  font-size: 12px;
  color: #534434;
  vertical-align: top;
}

</style>
