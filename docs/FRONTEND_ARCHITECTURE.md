# Noryxon Frontend Architecture

The Noryxon client-side architecture is divided into two primary interfaces: the **Invoice Dashboard** (`app.noryxon.com`) and the **Developer Portal** (`dev.noryxon.com`). Both provide a premium, real-time, "dark-mode native" experience suited for crypto-native users and developers.

## Tech Stack Overview

- **Backend Framework:** Laravel 12. Provides robust routing, API architecture, and Sanctum-based authentication.
- **Frontend Framework:** Vue.js (v3). Integrated seamlessly with Laravel via **Inertia.js** — the bridge pattern where Laravel controllers return `Inertia::render()` with Vue component names and data props.
- **Build Tool:** Vite 7 for fast HMR and production builds.
- **Styling:** Tailwind CSS v4 with a custom design system using CSS custom properties (`--color-void`, `--color-pulse`, `--color-node`, `--color-ledger`, `--color-ledger-border`, `--color-text-primary`, `--color-text-muted`).
- **Theme System:** Multiple themes via `data-theme` attribute on the document, controlled by `useTheme()` composable. Themes: Default (gold/dark), Corporate (gold/light), Terminal (green/black), Neon (pink/purple).
- **Fonts:** Inter (headings/body), JetBrains Mono (code/data).

---

## 1. Invoice Dashboard (`app.noryxon.com`)

The control center for users to manage documented transactions, tracked wallets, and invoice generation.

### Core Modules

#### A. Wallets (`/dashboard/wallets` — `Wallets.vue`)
- **Function:** Where users connect and manage blockchain wallets for transaction monitoring.
- **UI:** Card grid displaying all connected wallets with status indicators (connected, syncing, watching, paused, error). Multi-step connect modal supporting four wallet types:
  1. **Exchange** (API key): Binance, Coinbase, Kraken, KuCoin, Bybit, OKX — enter read-only API credentials.
  2. **Software Wallet** (address-based): MetaMask, Trust Wallet, Phantom, Rabby, Rainbow — paste public address.
  3. **XPub/Extended Key**: Hardware wallets — enter xpub/ypub/zpub with BIP-44/49/84 derivation path.
  4. **Manual Address**: Paste any blockchain address.
- **Composable:** `useDashboard.js` exports `walletProviders` config with provider metadata (name, icon, supported chains, required fields).
- **Validation:** Type-conditional frontend validation — checksum for XPUB keys, address format for software/manual, API key format for exchanges.

#### B. Invoice Vault
- **Function:** Central repository of all generated invoices and tax documentation.
- **UI:** Dashboard overview showing Documented Volume, Connected Wallets, Pending Invoices, and Invoices Generated. Quick actions for generating new invoices, managing wallets, and accessing the developer portal.

#### C. Analytics & Reporting
- **Function:** Read-only aggregation of documented transaction volume.
- **UI:** Charts showing documented volume over time, most popular documented assets, and transaction frequency.

#### D. Tax Report Generation
- **Function:** Generate DAT-compliant tax reports for off-ramping.
- **UI:** Export-ready PDF/CSV reports with Digital Asset Tax calculations, payer details, and transaction references. Reports can be attached to off-ramp partner workflows.

---

## 2. Developer Portal (`dev.noryxon.com`)

Engineered for builders integrating Noryxon's invoicing engine into their platforms.

### Core Modules

#### A. API Key Management
- Generates revocable `sk_live_...` and `sk_test_...` keys.
- Stats showing API calls, sandbox invoices generated, and SDK downloads.

#### B. API Playground
- Interactive endpoint testing for `/v1/invoices`, `/v1/invoices/:id`, `/v1/invoices/:id/tax-report`, and `/v1/wallets`.
- Mock responses include `tax_report_url`, `pdf_url`, and `offramp_redirect`.

#### C. Webhook Management
- Events: `invoice.generated`, `invoice.verified`, `transaction.detected`, `transaction.confirmed`, `tax_report.ready`.
- Historic delivery log with replay capability.

#### D. Invoice Simulator (Sandbox)
- Visual sandbox for testing the full invoice generation flow.
- Simulates `POST /v1/invoices` → webhook `invoice.generated` → success notification.

#### E. SDK Quick Install
- Four SDK cards with one-line install commands:
  - `npm install @noryxon/sdk` (TypeScript/Node.js)
  - `pip install noryxon` (Python)
  - `go get noryxon.com/sdk` (Go)
  - `composer require noryxon/sdk` (PHP)

---

## 3. Landing Page Components

The public-facing landing page (`Welcome.vue`) assembles these Vue components:

1. **HeroStation.vue** — Cinematic hero with live invoice assembly animation
2. **ChainTicker.vue** — Infinite scrolling chain status marquee
3. **TerminalDemo.vue** — Split-panel API demo + invoice generation simulation
4. **ProtocolGrid.vue** — 3-column feature cards with decode-on-hover effects
5. **VaultCTA.vue** — Bottom CTA with email capture

### Theme Toggle
- `ThemeToggle.vue` component in navigation
- Default and Corporate themes available to all users
- Terminal and Neon themes unlockable via easter egg (5/10 logo clicks)

---

## 4. Authentication Pages

- **Layout:** `GuestLayout.vue` — split-screen with branding panel (left) and auth form (right).
- **Branding:** "INVOICE PROTOCOL v2" / "Digital Asset Invoicing Infrastructure"
- **Features displayed:** Multi-chain invoicing, tax-compliant documentation, off-ramp partner redirects, API-first with SDKs.
- **Stats:** $4.2B+ Documented, 99.99% Uptime, 180+ Countries.
- **Auth methods:** Email/password via Laravel Sanctum.

---

## 5. Pricing Page

- **Tiers:** Developer (Free up to $10K/mo, 1% per TX after) and Node Operator (Custom volume-based pricing).
- **Developer features:** Unlimited invoices, 12+ chains, SDKs, sandbox, off-ramp redirects.
- **Enterprise features:** White-label templates, multi-jurisdiction formats, custom SDK builds, priority support.
- **Easter egg:** Triple-clicking "PAY_AS_YOU_GO" unlocks $15K free tier.
- **Platform capabilities grid:** Invoice Engine, Multi-Chain Verification, SDK & Developer Tools, Tax & Compliance.

---

## 6. Dashboard Layout

- **Sidebar:** `DashboardSidebar.vue` — "Invoice Vault" navigation.
- **Wrapper:** `DashboardLayout.vue` — "Noryxon Invoice Systems" / "Invoice Vault v2.1.0".
- **Sidebar item:** "Wallets" (renamed from "XPUB Vault").
- **Overview:** Stats cards, volume chart, recent documented transactions, quick actions.
