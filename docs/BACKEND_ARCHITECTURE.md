# Noryxon Backend Architecture

Noryxon operates as a **stateless invoicing and documentation layer**. Its primary mandate is to observe blockchains, verify transactions, generate compliant invoices, and produce tax-ready documentation. **It never custodies funds, private keys, or seed phrases.** It never handles off-ramping — that's delegated to licensed partner services.

## System Overview

The backend is built on **Laravel 12** with the following core responsibilities:

1. **Invoice Engine (Core API):** Handles invoice creation, transaction verification, tax report generation, and API key authentication.
2. **Chain Indexers (The "Eyes"):** Blockchain monitoring layer that detects and verifies on-chain transactions for invoice generation.
3. **Webhook Dispatcher (The "Mouth"):** Delivers invoice notifications and tax report events to integrators with cryptographic signatures.

---

## 1. Invoice Generation Flow

When a user or developer calls `POST /v1/invoices`, Noryxon:

1. **Validates the request:** Checks API key, payer details, amount, currency, and chain.
2. **Resolves receiving address:** Based on the wallet type — derives from XPUB, reads a stored address, or fetches a deposit address from an exchange API — then monitors for matching on-chain transactions.
3. **Verifies the transaction:** Confirms the TX hash, amount, and block depth meet the configured thresholds.
4. **Generates the invoice:** Creates a tamper-proof invoice document linking payer details, purpose, on-chain TX, and timestamp.
5. **Produces tax documentation:** Computes Digital Asset Tax (DAT) classification and generates an exportable tax report.
6. **Fires webhooks:** Notifies the integrator via signed webhooks (`invoice.generated`, `invoice.verified`, `tax_report.ready`).

### Universal Wallet System (Stateless Address Resolution)
Noryxon supports four wallet connection types, all zero-custody:

- **Exchange** (API key): Read-only API connections to Binance, Coinbase, Kraken, KuCoin, Bybit, OKX. API credentials are encrypted at rest. Used to fetch deposit addresses and monitor balances.
- **Software Wallet** (address-based): Direct address tracking for MetaMask, Trust Wallet, Phantom, Rabby, Rainbow. User provides their public address.
- **XPub/Extended Key**: Hardware wallets and watch-only monitoring via xpub/ypub/zpub. Adheres to BIP-44 (Legacy), BIP-49 (Nested SegWit), and BIP-84 (Native SegWit) standards.
- **Manual Address**: Paste any blockchain address for monitoring.

Private keys never exist on Noryxon servers. Exchange API keys are stored with read-only permissions only. Even if fully compromised, an attacker cannot move funds.

**Wallet status lifecycle:** `connected` → `syncing` → `watching` / `paused` / `error`

---

## 2. Real-Time Blockchain Monitoring

- **Infrastructure:** Fleet of redundant full nodes for supported chains, supplemented by premium 3rd-party RPC providers.
- **Supported Chains:** Bitcoin, Ethereum, Solana, Polygon, Avalanche, BNB Chain, Tron, Arbitrum, Optimism, Base, and more (12+ networks).
- **Ingestion Pipeline:**
  - Indexers subscribe to pending mempool transactions and new blocks via WebSocket.
  - Filter for transaction outputs matching active monitored addresses.
- **Validation Logic:**
  - Amount match (handling exchange rate dust variations).
  - Token validation for ERC-20/TRC-20/SPL tokens.
  - Configurable confirmation thresholds.

---

## 3. The Webhook Dispatcher

When the Indexer confirms a valid transaction meeting the configured threshold:

- **Payload Construction:** JSON payload with `invoice_id`, `tx_hash`, `amount`, `chain`, `tax_classification`, and `report_url`.
- **Events:** `transaction.detected`, `transaction.confirmed`, `invoice.generated`, `invoice.verified`, `tax_report.ready`.
- **Cryptographic Signature:** HMAC-SHA256 of the raw payload body using the integrator's Webhook Secret, injected in `X-Noryxon-Signature` header.
- **Delivery & Retries:** Exponential backoff: +1m, +5m, +30m, +1hr, up to 10 attempts over 3 days.

---

## 4. Database & Storage Architecture

- **SQLite (Local Dev) / PostgreSQL (Production):** Primary source of truth for:
  - `wallets` — Universal wallet tracking entries (exchange, software, xpub, manual)
  - `invoices` — Generated invoice records
  - `payments` — Transaction monitoring data
  - `api_keys` — Developer API key management
  - `webhook_endpoints` — Registered webhook URLs
  - `webhook_deliveries` — Delivery log with replay capability
  - `team_members` — Multi-user access
  - `audit_logs` — Immutable activity log
- **Redis:** Rate limiting (Token Bucket), active address monitoring cache, wallet sync state, HD wallet index increments.
- **Event Bus:** Queue-based event processing for indexer → webhook pipeline.

---

## 5. API Endpoints

### Invoice API (`/v1/invoices`)
- `POST /v1/invoices` — Create a new invoice for a transaction
- `GET /v1/invoices/:id` — Retrieve invoice details
- `GET /v1/invoices/:id/tax-report` — Download the DAT tax report

### Wallet API (`/v1/wallets`)
- `GET /v1/wallets` — List tracked wallets (all types)
- `POST /v1/wallets` — Add a wallet for monitoring (exchange, software, xpub, or manual — type-conditional validation)
- `PATCH /v1/wallets/:id` — Update wallet label/status
- `DELETE /v1/wallets/:id` — Remove a wallet

### Webhook Management
- `POST /v1/webhooks` — Register a webhook endpoint
- `GET /v1/webhooks/deliveries` — View delivery history

---

## 6. Security & Compliance

- **Zero Custody:** No private keys, no fund handling, no fiat rails.
- **No VASP Required:** Noryxon generates documentation only and redirects to licensed off-ramp partners for withdrawal processing.
- **Key Isolation:** API Keys and Webhook Secrets are hashed and encrypted at rest.
- **Rate Limiting:** IP and API Key based, default 100 req/min for free tier.
- **Audit Logging:** Every API request, webhook delivery, and dashboard action is immutably logged.

---

## 7. Off-Ramp Partner Integration

Noryxon does **not** handle off-ramping directly. Instead:

1. Generates compliant invoice + tax documentation for the transaction.
2. Provides an `offramp_redirect` URL in the API response.
3. The user/developer redirects to a licensed off-ramp partner with the documentation attached.
4. The partner processes the withdrawal with proper DAT classification, avoiding the 30% "unexplained income" tax.

This architecture intentionally avoids VASP licensing requirements by never handling, routing, or custodying user funds.
