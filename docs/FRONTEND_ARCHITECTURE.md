# Noryxon Frontend Architecture

The Noryxon client-side architecture is divided into two primary interfaces: the Merchant Dashboard (`app.noryxon.com`) and the Developer Portal (`dev.noryxon.com`). Both are designed to provide a premium, real-time, "dark-mode native" experience suited for crypto purists.

## Tech Stack Overview

- **Backend Framework:** Laravel. Provides robust routing, API architecture, and a secure foundation without blocking the UI thread with heavy SSR.
- **Frontend Framework:** Vue.js (v3). Selected for lightning-fast client-side reactivity and smooth Single Page Application (SPA) functionality. Integrated seamlessly with Laravel via Inertia.js.
- **Styling:** Tailwind CSS with a highly customized, edge-driven design system (deep blacks, neon accents, crisp typography).
- **State Management:** Pinia for global state (user sessions, active filters), Vue Query for server state caching and real-time invalidation.
- **Web3 Integrations:** Wagmi (via Vue-Wagmi) and Viem for WalletConnect integration, signing messages, and local XPUB validation.

---

## 1. Merchant Dashboard (`app.noryxon.com`)

The control center for merchants to manage their incoming non-custodial crypto flows.

### Core Modules

#### A. XPUB Vault (The Core)
- **Function:** Where merchants link their business to the blockchain.
- **UI:** A secure, locked-down interface to add extended public keys. Supports direct integration with Ledger/Trezor hardware via WebUSB.
- **Validation:** Frontend performs local checksum validation of the XPUB before ever transmitting it to the backend to prevent typos and lost funds.

#### B. Live Monitor (WebSocket Feed)
- **Function:** A real-time terminal-like view of incoming payments globally.
- **UI:** Connects via wss:// to the Noryxon backend. Automatically flashes and updates progress bars as block confirmations roll in. Gives merchants the dopamine hit of watching crypto stream in.

#### C. Analytics Engine
- **Function:** Read-only aggregation of on-chain revenue.
- **UI:** Built using specialized charting libraries (e.g., Recharts) to display:
  - Total Volume across 12 chains.
  - Most popular payment currencies (USDC vs USDT vs Native ETH).
  - Average confirmation latency per chain.

#### D. Invoicing & Payment Links
- **Function:** Point-of-Sale UI generation.
- **UI:** Merchants can generate static "Pay Now" links or dynamic QR codes. Includes a WYSIWYG editor to customize the checkout page appearance (logo, brand colors) without writing code.

---

## 2. Developer Portal (`dev.noryxon.com`)

Engineered for the builders integrating Noryxon into custom shops, games, or high-throughput platforms.

### Core Modules

#### A. API Key Management
- Generates revocable `sk_live_...` and `sk_test_...` keys.
- UI elements to whitelist specific IP addresses for added security.

#### B. API Playground & Swagger UI
- Interactive, embedded OpenAPI spec. Developers can test `POST /payments` directly from the browser using their test keys.

#### C. Webhook Tester
- A dedicated view that shows a historic log of all webhook deliveries.
- Allows developers to manually click "Replay" to resend a webhook payload to their server if it went down during development.

#### D. Sandbox Environment
- Visual toggle (a prominent top-bar switch) that flips the entire Dashboard into "Testnet Mode."
- All XPUBs entered in this mode must be testnet equivalents (e.g., tpub/upub), and no real funds are ever displayed or monitored.

---

## 3. The Hosted Checkout Page

When a merchant redirects a customer to a Noryxon-hosted checkout link, the frontend must be flawless.

- **Speed:** Highly optimized Blade templates injected with minimal, lightweight Vue components. Sub-200ms load times globally.
- **UX:** 
  - Displays a clean QR code.
  - "Click to Copy" elements for the raw address and exact amount.
  - WalletConnect v2 integration for mobile "one-tap" wallet opening.
  - A countdown timer (typically 15-30 minutes depending on the chain) to lock in exchange rates.
- **Real-Time Swap:** The checkout page listens via WebSocket to the Noryxon backend. The moment the transaction hits the mempool, the UI shifts from "Awaiting Payment" to "Processing (0/3 Confirmations)", and finally to "Success - Redirecting."

## 4. Authentication & Security

- **Login:** Supports traditional Email + Magic Link alongside "Sign-in with Ethereum" (SIWE), leveraging a merchant's WalletConnect session as their primary identifier.
- **Role-Based Access Control (RBAC):** UI dynamically hides features (like managing XPUBs or viewing API secrets) based on the user's permission level (Admin vs Analyst vs Support).
