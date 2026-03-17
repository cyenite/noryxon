# Noryxon — Full Backend Implementation Guide

This document is the complete engineering specification for implementing the Noryxon digital asset invoicing platform. It covers every API endpoint, database schema, service class, queue job, and integration required to make the application fully functional.

---

## Table of Contents

1. [Architecture Overview](#1-architecture-overview)
2. [Database Schema](#2-database-schema)
3. [Authentication & Authorization](#3-authentication--authorization)
4. [Core Services](#4-core-services)
5. [API Endpoints](#5-api-endpoints)
6. [Invoice Generation Flow](#6-invoice-generation-flow)
7. [Blockchain Monitoring](#7-blockchain-monitoring)
8. [Tax Report Generation](#8-tax-report-generation)
9. [Webhook System](#9-webhook-system)
10. [Off-Ramp Partner Integration](#10-off-ramp-partner-integration)
11. [SDK & Developer Portal](#11-sdk--developer-portal)
12. [Queue Jobs & Background Processing](#12-queue-jobs--background-processing)
13. [Rate Limiting & Security](#13-rate-limiting--security)
14. [Testing Strategy](#14-testing-strategy)
15. [Deployment & Infrastructure](#15-deployment--infrastructure)

---

## 1. Architecture Overview

### Tech Stack
- **Backend:** Laravel 12 (PHP 8.3+)
- **Frontend:** Vue 3 + Inertia.js (SPA via server-side routing)
- **Database:** SQLite (dev) / PostgreSQL (production)
- **Cache:** Redis (rate limiting, address monitoring, session)
- **Queue:** Redis + Laravel Horizon (job processing)
- **Build:** Vite 7

### Design Principles
- **Zero Custody:** Noryxon never stores private keys or handles funds. Wallet addresses are monitored only — via XPUB derivation, direct address tracking, or exchange API read-only keys.
- **Documentation Only:** The platform generates invoices and tax reports — it does not process payments, convert currencies, or facilitate withdrawals.
- **Off-Ramp Delegation:** Users are redirected to licensed off-ramp partners with attached documentation. This avoids VASP licensing.
- **API-First:** Every dashboard feature is backed by a REST API endpoint accessible via API keys.

### Request Flow
```
Client (Vue/Inertia) → Laravel Router → Controller → Service → Model/DB
                                                   → Queue Job (async)
                                                   → External APIs (blockchain RPCs)
```

---

## 2. Database Schema

### 2.1 Users Table (existing)
```sql
CREATE TABLE users (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    name            VARCHAR(255) NOT NULL,
    email           VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password        VARCHAR(255) NOT NULL,
    business_name   VARCHAR(255) NULL,
    webhook_url     VARCHAR(500) NULL,
    timezone        VARCHAR(50) DEFAULT 'Africa/Nairobi',
    default_currency VARCHAR(10) DEFAULT 'KES',
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret  VARCHAR(255) NULL,
    remember_token  VARCHAR(100) NULL,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP
);
```

### 2.2 Wallets Table
```sql
CREATE TABLE wallets (
    id                      BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id                 BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type                    ENUM('exchange','software','xpub','manual') NOT NULL,
    provider                VARCHAR(50) NULL,        -- 'binance', 'metamask', 'ledger', etc.
    label                   VARCHAR(255) NOT NULL,
    chain_id                VARCHAR(50) NOT NULL,    -- 'bitcoin', 'ethereum', 'solana', etc.
    status                  ENUM('connected','syncing','watching','paused','error') DEFAULT 'watching',
    address                 VARCHAR(255) NULL,       -- For software/manual wallet types
    xpub_key                TEXT NULL,               -- For xpub wallet type (extended public key)
    derivation_path         VARCHAR(100) DEFAULT "m/84'/0'/0'/0/0",
    address_index           INT DEFAULT 0,           -- Current derivation index (xpub only)
    api_key_encrypted       TEXT NULL,               -- For exchange wallet type
    api_secret_encrypted    TEXT NULL,               -- For exchange wallet type
    api_passphrase_encrypted TEXT NULL,              -- For exchange wallet type (e.g. Coinbase)
    total_received          DECIMAL(20,8) DEFAULT 0,
    addresses_generated     INT DEFAULT 0,
    last_synced_at          TIMESTAMP NULL,
    created_at              TIMESTAMP,
    updated_at              TIMESTAMP,
    INDEX idx_wallets_user (user_id),
    INDEX idx_wallets_chain (chain_id),
    INDEX idx_wallets_type (type),
    INDEX idx_wallets_status (status)
);
```

**Wallet Types:**
- **Exchange** (`type: 'exchange'`): Connected via read-only API keys. Providers: Binance, Coinbase, Kraken, KuCoin, Bybit, OKX.
- **Software Wallet** (`type: 'software'`): Address-based tracking. Providers: MetaMask, Trust Wallet, Phantom, Rabby, Rainbow.
- **XPub/Extended Key** (`type: 'xpub'`): Hardware wallets and watch-only via xpub/ypub/zpub. BIP-44/49/84 derivation.
- **Manual Address** (`type: 'manual'`): Paste any blockchain address for monitoring.

### 2.3 Invoices Table
```sql
CREATE TABLE invoices (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    invoice_number  VARCHAR(50) UNIQUE NOT NULL,  -- INV-XXXXXXXX
    amount          DECIMAL(20,8) NOT NULL,
    currency        VARCHAR(10) NOT NULL,          -- USDC, ETH, BTC, etc.
    fiat_equivalent DECIMAL(20,2) NULL,            -- KES equivalent at time of creation
    chain_id        VARCHAR(50) NULL,              -- Which blockchain
    status          ENUM('draft','pending','processing','paid','expired','cancelled') DEFAULT 'draft',
    purpose         VARCHAR(100) NULL,             -- freelance_payment, investment_return, etc.
    memo            TEXT NULL,
    customer_email  VARCHAR(255) NULL,
    payer_name      VARCHAR(255) NULL,
    payer_wallet    VARCHAR(255) NULL,              -- Payer's wallet address
    receiving_address VARCHAR(255) NULL,            -- Derived address for this invoice
    tx_hash         VARCHAR(255) NULL,              -- On-chain transaction hash
    block_number    BIGINT NULL,
    confirmations   INT DEFAULT 0,
    required_confirmations INT DEFAULT 3,
    payment_link    VARCHAR(255) NULL,              -- pay.noryxon.com/XXXX
    tax_report_url  VARCHAR(500) NULL,              -- URL to generated PDF
    tax_classification VARCHAR(50) DEFAULT 'DAT',  -- Digital Asset Tax
    offramp_redirect_url VARCHAR(500) NULL,         -- Off-ramp partner redirect
    paid_at         TIMESTAMP NULL,
    expires_at      TIMESTAMP NULL,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    INDEX idx_invoices_user (user_id),
    INDEX idx_invoices_status (status),
    INDEX idx_invoices_tx (tx_hash),
    INDEX idx_invoices_number (invoice_number)
);
```

### 2.4 Payments Table (Transaction Monitoring Log)
```sql
CREATE TABLE payments (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    invoice_id      BIGINT NULL REFERENCES invoices(id) ON DELETE SET NULL,
    wallet_id       BIGINT NULL REFERENCES wallets(id) ON DELETE SET NULL,
    tx_hash         VARCHAR(255) NOT NULL,
    amount          DECIMAL(20,8) NOT NULL,
    token           VARCHAR(20) NOT NULL,
    chain_id        VARCHAR(50) NOT NULL,
    status          ENUM('detected','processing','confirmed','failed','expired') DEFAULT 'detected',
    confirmations   INT DEFAULT 0,
    required_confirmations INT DEFAULT 3,
    from_address    VARCHAR(255) NULL,
    to_address      VARCHAR(255) NULL,
    block_number    BIGINT NULL,
    block_hash      VARCHAR(255) NULL,
    gas_used        DECIMAL(20,8) NULL,
    detected_at     TIMESTAMP NULL,
    confirmed_at    TIMESTAMP NULL,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    INDEX idx_payments_user (user_id),
    INDEX idx_payments_invoice (invoice_id),
    INDEX idx_payments_tx (tx_hash),
    INDEX idx_payments_status (status)
);
```

### 2.5 API Keys Table
```sql
CREATE TABLE api_keys (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name            VARCHAR(255) NOT NULL,
    key_prefix      VARCHAR(20) NOT NULL,     -- sk_live_ or sk_test_
    key_hash        VARCHAR(255) NOT NULL,     -- SHA-256 hash of the full key
    environment     ENUM('live','test') DEFAULT 'test',
    last_used_at    TIMESTAMP NULL,
    request_count   INT DEFAULT 0,
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    INDEX idx_api_keys_user (user_id),
    INDEX idx_api_keys_prefix (key_prefix)
);
```

### 2.6 Webhook Endpoints Table
```sql
CREATE TABLE webhook_endpoints (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    url             VARCHAR(500) NOT NULL,
    signing_secret  VARCHAR(255) NOT NULL,
    subscribed_events JSON NOT NULL,           -- ["invoice.generated","tax_report.ready"]
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    INDEX idx_webhook_endpoints_user (user_id)
);
```

### 2.7 Webhook Deliveries Table
```sql
CREATE TABLE webhook_deliveries (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    webhook_endpoint_id BIGINT NOT NULL REFERENCES webhook_endpoints(id) ON DELETE CASCADE,
    event_type      VARCHAR(100) NOT NULL,
    payload         JSON NOT NULL,
    status_code     INT NULL,
    response_body   TEXT NULL,
    response_time_ms INT NULL,
    attempt         INT DEFAULT 1,
    max_attempts    INT DEFAULT 10,
    next_retry_at   TIMESTAMP NULL,
    delivered_at    TIMESTAMP NULL,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    INDEX idx_deliveries_endpoint (webhook_endpoint_id),
    INDEX idx_deliveries_event (event_type)
);
```

### 2.8 Team Members Table
```sql
CREATE TABLE team_members (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    invited_by      BIGINT NOT NULL REFERENCES users(id),
    name            VARCHAR(255) NOT NULL,
    email           VARCHAR(255) NOT NULL,
    role            ENUM('Admin','Developer','Analyst','Support') DEFAULT 'Analyst',
    status          ENUM('pending','active','suspended') DEFAULT 'pending',
    invite_token    VARCHAR(255) NULL,
    accepted_at     TIMESTAMP NULL,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    INDEX idx_team_user (user_id)
);
```

### 2.9 Audit Logs Table
```sql
CREATE TABLE audit_logs (
    id              BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    action          VARCHAR(255) NOT NULL,     -- 'invoice.created', 'wallet.added', 'api_key.revoked'
    resource_type   VARCHAR(100) NULL,         -- 'Invoice', 'Wallet', 'ApiKey'
    resource_id     BIGINT NULL,
    ip_address      VARCHAR(45) NULL,
    user_agent      TEXT NULL,
    metadata        JSON NULL,
    created_at      TIMESTAMP,
    INDEX idx_audit_user (user_id),
    INDEX idx_audit_action (action)
);
```

---

## 3. Authentication & Authorization

### 3.1 Web Authentication (Inertia/Session)
- **Framework:** Laravel Sanctum (session-based for web)
- **Routes:** Standard Breeze auth scaffolding (login, register, forgot password, reset, verify email, confirm password)
- **Session:** Cookie-based, stored in Redis for production
- **2FA:** TOTP-based (Google Authenticator) using `pragmarx/google2fa-laravel`

### 3.2 API Authentication (Token-based)
- **Mechanism:** API key in `Authorization: Bearer sk_live_...` header
- **Middleware:** Custom `auth.api_key` middleware that:
  1. Extracts the bearer token
  2. Hashes it with SHA-256
  3. Looks up the hash in `api_keys` table
  4. Rejects if key is revoked or inactive
  5. Sets `request()->apiKey` and `request()->user()` for downstream use
  6. Increments `request_count` and updates `last_used_at`

```php
// app/Http/Middleware/AuthenticateApiKey.php
class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Missing API key'], 401);
        }

        $hash = hash('sha256', $token);
        $apiKey = ApiKey::where('key_hash', $hash)
            ->where('is_active', true)
            ->first();

        if (!$apiKey) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $apiKey->increment('request_count');
        $apiKey->update(['last_used_at' => now()]);

        $request->merge(['apiKey' => $apiKey]);
        $request->setUserResolver(fn() => $apiKey->user);

        return $next($request);
    }
}
```

### 3.3 Role-Based Access Control
- **Roles:** Admin, Developer, Analyst, Support
- **Implementation:** Check `team_members.role` in middleware/policy
- **Permissions:**
  - Admin: Full access (wallet management, API keys, settings, invoices, team)
  - Developer: API keys, webhooks, sandbox, invoices (read)
  - Analyst: Analytics, invoices (read), reports
  - Support: View-only dashboard, audit logs

---

## 4. Core Services

### 4.1 InvoiceService
```
app/Services/InvoiceService.php
```
**Responsibilities:**
- Create invoices with unique invoice numbers (INV-XXXXXXXX)
- Resolve receiving address from user's wallet (XPUB derivation, direct address, or exchange API)
- Calculate fiat equivalent using exchange rate service
- Set expiration (configurable, default 30 minutes)
- Generate payment link
- Trigger `invoice.generated` webhook

**Key Methods:**
```php
class InvoiceService
{
    public function create(User $user, array $data): Invoice;
    public function markAsPaid(Invoice $invoice, Payment $payment): Invoice;
    public function generateTaxReport(Invoice $invoice): string; // Returns PDF URL
    public function getOfframpRedirect(Invoice $invoice): string; // Returns partner URL
    public function expire(Invoice $invoice): void;
}
```

### 4.2 BlockchainMonitorService
```
app/Services/BlockchainMonitorService.php
```
**Responsibilities:**
- Poll blockchain RPCs for transactions to monitored addresses
- Detect incoming transactions matching invoice parameters
- Track confirmation depth
- Trigger status transitions (detected → processing → confirmed)

**Key Methods:**
```php
class BlockchainMonitorService
{
    public function checkAddress(string $address, string $chainId): ?array;
    public function getConfirmations(string $txHash, string $chainId): int;
    public function getTransaction(string $txHash, string $chainId): ?array;
    public function monitorInvoice(Invoice $invoice): void;
}
```

### 4.3 WalletAddressService
```
app/Services/WalletAddressService.php
```
**Responsibilities:**
- Resolve a receiving address from any wallet type
- For `xpub` type: derive child addresses using BIP-44/49/84 standards, increment address index atomically
- For `software` / `manual` type: return the stored address directly
- For `exchange` type: fetch deposit address via exchange API
- Validate addresses and XPUB keys by format and checksum

**Dependencies:** `bitwasp/bitcoin` PHP library for BIP derivation; exchange SDK clients for API-based wallets

```php
class WalletAddressService
{
    public function resolveAddress(Wallet $wallet): string;
    public function deriveXpubAddress(Wallet $wallet): string;
    public function validateXpub(string $key): bool;
    public function validateAddress(string $address, string $chainId): bool;
    public function getAddressAtIndex(Wallet $wallet, int $index): string;
    public function fetchExchangeDepositAddress(Wallet $wallet): string;
}
```

### 4.4 TaxReportService
```
app/Services/TaxReportService.php
```
**Responsibilities:**
- Generate DAT-compliant PDF tax reports
- Calculate Digital Asset Tax (1.5% of transfer value in Kenya)
- Include: invoice number, payer details, TX hash, amount, KES equivalent, chain, timestamp, tax classification
- Store generated PDF and return URL

**Dependencies:** `barryvdh/laravel-dompdf` for PDF generation

```php
class TaxReportService
{
    public function generate(Invoice $invoice): string; // Returns file path
    public function calculateDAT(float $amount, string $currency): array;
    public function exportCSV(User $user, Carbon $from, Carbon $to): string;
}
```

### 4.5 WebhookDispatchService
```
app/Services/WebhookDispatchService.php
```
**Responsibilities:**
- Dispatch webhook events to registered endpoints
- Compute HMAC-SHA256 signature
- Handle retries with exponential backoff
- Log delivery attempts

```php
class WebhookDispatchService
{
    public function dispatch(User $user, string $event, array $payload): void;
    public function retry(WebhookDelivery $delivery): void;
    public function computeSignature(string $payload, string $secret): string;
}
```

### 4.6 ExchangeRateService
```
app/Services/ExchangeRateService.php
```
**Responsibilities:**
- Fetch real-time crypto-to-KES exchange rates
- Cache rates in Redis (TTL: 60 seconds)
- Support: BTC, ETH, SOL, USDC, USDT, DAI, and all major tokens
- Fallback to multiple providers (CoinGecko, Binance API)

```php
class ExchangeRateService
{
    public function getRate(string $token, string $fiat = 'KES'): float;
    public function convert(float $amount, string $from, string $to): float;
}
```

### 4.7 OfframpPartnerService
```
app/Services/OfframpPartnerService.php
```
**Responsibilities:**
- Generate off-ramp redirect URLs with invoice documentation attached
- Support multiple off-ramp partners (configurable)
- Pass invoice PDF URL, tax report, and transaction details as query params

```php
class OfframpPartnerService
{
    public function getRedirectUrl(Invoice $invoice): string;
    public function getAvailablePartners(string $country = 'KE'): array;
}
```

---

## 5. API Endpoints

### 5.1 Public API (Bearer Token Auth)

#### Invoices
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/invoices` | Create a new invoice |
| GET | `/api/v1/invoices` | List user's invoices (paginated) |
| GET | `/api/v1/invoices/{id}` | Get invoice details |
| GET | `/api/v1/invoices/{id}/tax-report` | Download tax report PDF |
| POST | `/api/v1/invoices/{id}/cancel` | Cancel a pending invoice |

**POST /api/v1/invoices Request:**
```json
{
    "amount": "1250.00",
    "currency": "USDC",
    "chain": "ethereum",
    "payer": "client@example.com",
    "payer_name": "John Doe",
    "purpose": "freelance_payment",
    "memo": "Web development services - March 2026",
    "required_confirmations": 3,
    "expires_in": 1800
}
```

**POST /api/v1/invoices Response:**
```json
{
    "id": "inv_a8x7b2",
    "invoice_number": "INV-K4X9M2PL",
    "amount": "1250.00",
    "currency": "USDC",
    "chain": "ethereum",
    "fiat_equivalent": "161250.00",
    "fiat_currency": "KES",
    "status": "pending",
    "purpose": "freelance_payment",
    "receiving_address": "0x7A250d5630B4cF539739dF2C5dAcb4c659F2488D",
    "payment_link": "https://pay.noryxon.com/K4X9M2PL",
    "tax_classification": "DAT",
    "offramp_redirect": "https://offramp.partner.com/process?invoice=INV-K4X9M2PL",
    "expires_at": "2026-03-16T15:30:00Z",
    "created_at": "2026-03-16T15:00:00Z"
}
```

#### Wallets
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/wallets` | List tracked wallets |
| POST | `/api/v1/wallets` | Add a wallet (exchange, software, xpub, or manual) |
| PATCH | `/api/v1/wallets/{id}` | Update wallet label/status |
| DELETE | `/api/v1/wallets/{id}` | Remove a wallet |

#### Webhooks
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/webhooks` | List webhook endpoints |
| POST | `/api/v1/webhooks` | Register a webhook endpoint |
| PUT | `/api/v1/webhooks/{id}` | Update webhook endpoint |
| DELETE | `/api/v1/webhooks/{id}` | Remove webhook endpoint |

### 5.2 Dashboard Routes (Session Auth via Inertia)

#### Merchant Dashboard
| Method | Route | Controller | Page |
|--------|-------|------------|------|
| GET | `/dashboard` | DashboardController@index | Dashboard/Overview |
| GET | `/dashboard/wallets` | WalletController@index | Dashboard/Wallets |
| POST | `/dashboard/wallets` | WalletController@store | — |
| PATCH | `/dashboard/wallets/{id}` | WalletController@update | — |
| DELETE | `/dashboard/wallets/{id}` | WalletController@destroy | — |
| GET | `/dashboard/live-monitor` | PaymentController@index | Dashboard/LiveMonitor |
| GET | `/dashboard/analytics` | AnalyticsController@index | Dashboard/Analytics |
| GET | `/dashboard/invoices` | InvoiceController@index | Dashboard/Invoices |
| POST | `/dashboard/invoices` | InvoiceController@store | — |
| GET | `/dashboard/settings` | SettingsController@index | Dashboard/Settings |
| PATCH | `/dashboard/settings` | SettingsController@update | — |

#### Developer Portal
| Method | Route | Controller | Page |
|--------|-------|------------|------|
| GET | `/developer` | DeveloperOverviewController@index | Developer/Overview |
| GET | `/developer/api-keys` | ApiKeyController@index | Developer/ApiKeys |
| POST | `/developer/api-keys` | ApiKeyController@store | — |
| DELETE | `/developer/api-keys/{id}` | ApiKeyController@destroy | — |
| GET | `/developer/webhooks` | WebhookController@index | Developer/Webhooks |
| POST | `/developer/webhooks` | WebhookController@store | — |
| GET | `/developer/sandbox` | DeveloperToolsController@sandbox | Developer/Sandbox |
| GET | `/developer/playground` | DeveloperToolsController@playground | Developer/Playground |

---

## 6. Invoice Generation Flow

### Step-by-step:

```
1. User calls POST /api/v1/invoices (or creates via dashboard)
    ↓
2. InvoiceService.create()
    a. Generate unique invoice_number (INV-XXXXXXXX)
    b. Resolve receiving address from user's wallet for target chain:
       - WalletAddressService.resolveAddress(wallet) → receiving_address
       - For xpub type: derive via BIP path and increment wallet.address_index
       - For software/manual type: use stored address directly
       - For exchange type: fetch deposit address via exchange API
    c. ExchangeRateService.getRate() → fiat_equivalent (KES)
    d. Generate payment_link slug
    e. Save Invoice record (status: 'pending')
    f. Set expires_at (default: now + 30 minutes)
    ↓
3. Dispatch MonitorInvoiceJob (queued)
    ↓
4. MonitorInvoiceJob polls blockchain RPC every 10 seconds:
    a. BlockchainMonitorService.checkAddress(receiving_address, chain_id)
    b. If transaction detected:
       - Create Payment record (status: 'detected')
       - Update Invoice status → 'processing'
       - Dispatch WebhookEvent('transaction.detected', {...})
    c. Track confirmations:
       - BlockchainMonitorService.getConfirmations(tx_hash, chain_id)
       - Update Payment.confirmations
       - When confirmations >= required_confirmations:
         * Update Payment status → 'confirmed'
         * InvoiceService.markAsPaid(invoice, payment)
         * Dispatch WebhookEvent('transaction.confirmed', {...})
    ↓
5. InvoiceService.markAsPaid()
    a. Update Invoice status → 'paid', set tx_hash, paid_at
    b. TaxReportService.generate(invoice) → tax_report_url
    c. OfframpPartnerService.getRedirectUrl(invoice) → offramp_redirect_url
    d. Dispatch WebhookEvent('invoice.verified', {...})
    e. Dispatch WebhookEvent('tax_report.ready', {...})
    ↓
6. Invoice complete. User can:
    - Download tax report PDF
    - Click off-ramp redirect to withdraw through licensed partner
```

### Invoice Status State Machine:
```
draft → pending → processing → paid
                ↘ expired
                ↘ cancelled
```

---

## 7. Blockchain Monitoring

### 7.1 Supported Chains
| Chain | RPC Provider | Confirmation Threshold |
|-------|-------------|----------------------|
| Bitcoin | Bitcoin Core / BlockCypher | 3 (configurable) |
| Ethereum | Alchemy / Infura / Geth | 12 |
| Solana | Helius / QuickNode | 1 (finalized) |
| Polygon | Alchemy / QuickNode | 30 |
| Arbitrum | Alchemy | 1 |
| Optimism | Alchemy | 1 |
| Base | Alchemy | 1 |
| BNB Chain | QuickNode | 15 |
| Tron | TronGrid | 20 |
| Avalanche | QuickNode | 1 |
| Near | RPC | 1 |
| Stellar | Horizon API | 1 |

### 7.2 Monitoring Implementation

**Job-based polling (Phase 1):**
```php
// app/Jobs/MonitorInvoiceJob.php
class MonitorInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 180;     // 30 minutes at 10s intervals
    public int $timeout = 30;

    public function __construct(public Invoice $invoice) {}

    public function handle(BlockchainMonitorService $monitor): void
    {
        if ($this->invoice->status === 'paid' || $this->invoice->status === 'expired') {
            return;
        }

        if ($this->invoice->expires_at && now()->isAfter($this->invoice->expires_at)) {
            $this->invoice->update(['status' => 'expired']);
            return;
        }

        $result = $monitor->checkAddress(
            $this->invoice->receiving_address,
            $this->invoice->chain_id
        );

        if ($result) {
            // Transaction detected — handle confirmation tracking
            app(InvoiceService::class)->processDetectedTransaction($this->invoice, $result);
        } else {
            // Re-queue with 10s delay
            self::dispatch($this->invoice)->delay(now()->addSeconds(10));
        }
    }
}
```

**WebSocket-based (Phase 2 — future):**
- Subscribe to mempool + new blocks via WebSocket
- Filter for monitored addresses in real-time
- Eliminates polling overhead

### 7.3 Chain-Specific Adapters
```
app/Services/Chains/
├── ChainAdapter.php          (Interface)
├── BitcoinAdapter.php
├── EthereumAdapter.php
├── SolanaAdapter.php
├── PolygonAdapter.php
├── ArbitrumAdapter.php
├── TronAdapter.php
└── ...
```

Each adapter implements:
```php
interface ChainAdapter
{
    public function getBalance(string $address): float;
    public function getTransactions(string $address, int $limit = 10): array;
    public function getTransaction(string $txHash): ?array;
    public function getConfirmations(string $txHash): int;
    public function getCurrentBlock(): int;
    public function validateAddress(string $address): bool;
}
```

---

## 8. Tax Report Generation

### 8.1 Report Content
Each tax report PDF includes:
- **Header:** Noryxon logo, report ID, generation date
- **Invoice Details:** Invoice number, amount, currency, chain, purpose
- **Transaction Proof:** TX hash (linked to block explorer), block number, confirmations, timestamp
- **Payer Information:** Name, email, wallet address
- **Tax Classification:** "Digital Asset Tax (DAT)" — NOT "unexplained income"
- **Fiat Equivalent:** KES amount at time of transaction (with exchange rate source)
- **Tax Calculation:** 1.5% DAT on the transaction value
- **Footer:** "This document was generated by Noryxon Invoice Protocol. Noryxon does not custody funds."

### 8.2 Implementation
```php
// app/Services/TaxReportService.php
class TaxReportService
{
    public function generate(Invoice $invoice): string
    {
        $data = [
            'invoice' => $invoice,
            'payment' => $invoice->payments()->where('status', 'confirmed')->first(),
            'exchange_rate' => app(ExchangeRateService::class)->getRate($invoice->currency, 'KES'),
            'dat_amount' => $invoice->fiat_equivalent * 0.015,
            'generated_at' => now(),
        ];

        $pdf = PDF::loadView('reports.tax-report', $data);
        $path = "tax-reports/{$invoice->invoice_number}.pdf";

        Storage::disk('s3')->put($path, $pdf->output());

        $invoice->update(['tax_report_url' => Storage::disk('s3')->url($path)]);

        return $path;
    }
}
```

### 8.3 Batch Export
Users can export all invoices for a date range as a consolidated CSV:
```
Invoice Number, Date, Amount, Currency, KES Equivalent, Chain, TX Hash, Payer, Purpose, DAT Amount, Status
INV-K4X9M2PL, 2026-03-16, 1250.00, USDC, 161250.00, ethereum, 0xabc..., client@..., freelance_payment, 2418.75, paid
```

---

## 9. Webhook System

### 9.1 Events
| Event | Trigger | Payload Includes |
|-------|---------|-----------------|
| `transaction.detected` | TX seen in mempool | invoice_id, tx_hash, amount, chain |
| `transaction.confirmed` | TX reaches required confirmations | invoice_id, tx_hash, confirmations, block_number |
| `invoice.generated` | Invoice created | invoice_id, invoice_number, amount, currency, payment_link |
| `invoice.verified` | Invoice marked as paid with all docs | invoice_id, tx_hash, tax_report_url, offramp_redirect |
| `tax_report.ready` | PDF tax report generated | invoice_id, report_url, dat_amount |

### 9.2 Delivery
```php
// app/Jobs/DispatchWebhookJob.php
class DispatchWebhookJob implements ShouldQueue
{
    public int $tries = 10;
    public array $backoff = [60, 300, 1800, 3600, 7200, 14400, 28800, 43200, 86400, 172800];

    public function handle(): void
    {
        $payloadJson = json_encode($this->payload);
        $signature = hash_hmac('sha256', $payloadJson, $this->endpoint->signing_secret);

        $response = Http::timeout(10)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Noryxon-Signature' => $signature,
                'X-Noryxon-Event' => $this->event,
                'X-Noryxon-Delivery' => $this->delivery->id,
            ])
            ->post($this->endpoint->url, $this->payload);

        $this->delivery->update([
            'status_code' => $response->status(),
            'response_body' => Str::limit($response->body(), 1000),
            'response_time_ms' => /* calculated */,
            'delivered_at' => $response->successful() ? now() : null,
        ]);

        if (!$response->successful()) {
            throw new WebhookDeliveryFailedException();
        }
    }
}
```

### 9.3 Signature Verification (SDK-side)
```javascript
// SDK: Verify webhook signature
const crypto = require('crypto');
function verifyWebhook(payload, signature, secret) {
    const expected = crypto.createHmac('sha256', secret).update(payload).digest('hex');
    return crypto.timingSafeEqual(Buffer.from(signature), Buffer.from(expected));
}
```

---

## 10. Off-Ramp Partner Integration

### 10.1 Flow
```
1. Invoice paid + tax report generated
2. OfframpPartnerService.getRedirectUrl(invoice)
3. URL format: https://partner.com/process?
     invoice_id=INV-K4X9M2PL
     &amount=1250.00
     &currency=USDC
     &chain=ethereum
     &tx_hash=0xabc...
     &tax_report=https://noryxon.com/reports/INV-K4X9M2PL.pdf
     &tax_class=DAT
     &fiat_equiv=161250
     &fiat_currency=KES
     &callback=https://api.noryxon.com/v1/offramp/callback
4. User completes withdrawal on partner's platform
5. Partner calls callback URL to confirm completion (optional)
```

### 10.2 Configuration
```php
// config/offramp.php
return [
    'default_partner' => env('OFFRAMP_DEFAULT_PARTNER', 'partner_a'),
    'partners' => [
        'partner_a' => [
            'name' => 'Partner A',
            'base_url' => env('OFFRAMP_PARTNER_A_URL'),
            'api_key' => env('OFFRAMP_PARTNER_A_KEY'),
            'supported_countries' => ['KE', 'NG', 'GH'],
            'supported_currencies' => ['KES', 'NGN', 'GHS'],
        ],
    ],
];
```

### 10.3 Key Principle
Noryxon NEVER handles the actual withdrawal/conversion. The off-ramp partner receives:
- Proof of transaction (TX hash, block explorer link)
- Tax documentation (DAT report PDF)
- Invoice details (payer, purpose, amount)

The partner then processes the fiat conversion and withdrawal under their own VASP license.

---

## 11. SDK & Developer Portal

### 11.1 SDK Structure
```
@noryxon/sdk (TypeScript/Node.js)
├── src/
│   ├── client.ts        — HTTP client with auth
│   ├── invoices.ts      — Invoice CRUD
│   ├── wallets.ts       — Wallet management
│   ├── webhooks.ts      — Webhook management + verification
│   └── types.ts         — TypeScript interfaces
```

### 11.2 SDK Usage Example
```typescript
import { Noryxon } from '@noryxon/sdk';

const noryxon = new Noryxon('sk_live_7x9a...');

// Create invoice
const invoice = await noryxon.invoices.create({
    amount: '1250.00',
    currency: 'USDC',
    chain: 'ethereum',
    payer: 'client@example.com',
    purpose: 'freelance_payment',
    memo: 'Web development - March 2026',
});

console.log(invoice.payment_link);     // https://pay.noryxon.com/K4X9M2PL
console.log(invoice.offramp_redirect); // https://partner.com/process?...

// Get tax report
const report = await noryxon.invoices.getTaxReport(invoice.id);
console.log(report.pdf_url);
```

### 11.3 Sandbox Environment
- All `sk_test_` keys route to testnet RPCs
- Invoice amounts are simulated
- Webhooks fire with test payloads
- No real blockchain transactions monitored

---

## 12. Queue Jobs & Background Processing

### 12.1 Job Registry
| Job | Queue | Description |
|-----|-------|-------------|
| `MonitorInvoiceJob` | `monitoring` | Polls blockchain for invoice payment |
| `DispatchWebhookJob` | `webhooks` | Delivers webhook to endpoint |
| `GenerateTaxReportJob` | `reports` | Generates PDF tax report |
| `ExpireInvoiceJob` | `default` | Expires invoices past deadline |
| `SyncWalletBalanceJob` | `monitoring` | Updates wallet total_received |
| `RefreshExchangeRatesJob` | `default` | Caches latest exchange rates |
| `PruneAuditLogsJob` | `default` | Cleans old audit log entries |

### 12.2 Scheduler
```php
// app/Console/Kernel.php
$schedule->job(new RefreshExchangeRatesJob)->everyMinute();
$schedule->job(new PruneAuditLogsJob)->daily();
$schedule->command('invoices:expire-pending')->everyFiveMinutes();
$schedule->command('wallets:sync-balances')->everyTenMinutes();
```

### 12.3 Laravel Horizon Config
```php
'environments' => [
    'production' => [
        'monitoring' => ['maxProcesses' => 10, 'balanceMaxShift' => 1, 'balanceCooldown' => 3],
        'webhooks'   => ['maxProcesses' => 5],
        'reports'    => ['maxProcesses' => 3],
        'default'    => ['maxProcesses' => 3],
    ],
],
```

---

## 13. Rate Limiting & Security

### 13.1 API Rate Limits
| Tier | Limit | Window |
|------|-------|--------|
| Free (Developer) | 100 req/min | Per API key |
| Enterprise (Node Operator) | 1000 req/min | Per API key |
| Auth endpoints | 5 req/min | Per IP |

### 13.2 Implementation
```php
// app/Providers/RouteServiceProvider.php
RateLimiter::for('api', function (Request $request) {
    $key = $request->apiKey;
    $limit = $key?->user?->plan === 'enterprise' ? 1000 : 100;
    return Limit::perMinute($limit)->by($key?->id ?: $request->ip());
});
```

### 13.3 Security Checklist
- [ ] All API keys hashed with SHA-256 before storage
- [ ] Webhook secrets generated with `Str::random(64)`
- [ ] HMAC-SHA256 signature on all webhook deliveries
- [ ] CORS restricted to approved origins
- [ ] CSRF protection on all Inertia routes
- [ ] Input validation on all controller methods
- [ ] SQL injection prevention via Eloquent ORM
- [ ] XSS prevention via Vue.js template escaping
- [ ] Rate limiting on all public endpoints
- [ ] Audit logging on all sensitive operations
- [ ] IP whitelisting option for API keys
- [ ] 2FA support for dashboard access
- [ ] Encrypted secrets at rest (APP_KEY)

---

## 14. Testing Strategy

### 14.1 Test Structure
```
tests/
├── Unit/
│   ├── Services/
│   │   ├── InvoiceServiceTest.php
│   │   ├── WalletAddressServiceTest.php
│   │   ├── TaxReportServiceTest.php
│   │   ├── WebhookDispatchServiceTest.php
│   │   └── ExchangeRateServiceTest.php
│   └── Models/
│       ├── InvoiceTest.php
│       ├── PaymentTest.php
│       └── WalletTest.php
├── Feature/
│   ├── Api/
│   │   ├── InvoiceApiTest.php
│   │   ├── WalletApiTest.php
│   │   └── WebhookApiTest.php
│   ├── Dashboard/
│   │   ├── DashboardOverviewTest.php
│   │   ├── InvoiceManagementTest.php
│   │   └── WalletsTest.php
│   └── Auth/
│       ├── LoginTest.php
│       ├── RegisterTest.php
│       └── ApiKeyAuthTest.php
└── Integration/
    ├── BlockchainMonitorTest.php
    └── WebhookDeliveryTest.php
```

### 14.2 Key Test Scenarios
1. **Invoice lifecycle:** Create → detect TX → confirm → generate report → expire timeout
2. **Wallet address resolution:** Correct address resolution for all wallet types (XPUB derivation, direct address, exchange API)
3. **API auth:** Valid key succeeds, revoked key fails, missing key returns 401
4. **Webhook delivery:** Successful delivery, retry on failure, signature verification
5. **Tax report:** Correct DAT calculation, PDF generation, KES conversion
6. **Rate limiting:** Exceeding limit returns 429

---

## 15. Deployment & Infrastructure

### 15.1 Environment Variables
```env
# App
APP_NAME=Noryxon
APP_ENV=production
APP_KEY=base64:...
APP_URL=https://app.noryxon.com

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_DATABASE=noryxon
DB_USERNAME=noryxon
DB_PASSWORD=...

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=...

# Queue
QUEUE_CONNECTION=redis

# Blockchain RPCs
ETHEREUM_RPC_URL=https://eth-mainnet.g.alchemy.com/v2/...
BITCOIN_RPC_URL=...
SOLANA_RPC_URL=https://mainnet.helius-rpc.com/?api-key=...
POLYGON_RPC_URL=...
ARBITRUM_RPC_URL=...

# Exchange Rate
COINGECKO_API_KEY=...

# Off-Ramp
OFFRAMP_DEFAULT_PARTNER=partner_a
OFFRAMP_PARTNER_A_URL=https://...
OFFRAMP_PARTNER_A_KEY=...

# Storage (for tax reports)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=af-south-1
AWS_BUCKET=noryxon-reports

# Mail
MAIL_MAILER=ses
```

### 15.2 Production Stack
```
Load Balancer (Nginx/Cloudflare)
    ↓
App Server(s) (Laravel + PHP-FPM)
    ↓
PostgreSQL (Primary + Read Replica)
Redis (Cache + Queue + Sessions)
S3 (Tax report PDFs)
Laravel Horizon (Queue Workers)
```

### 15.3 Deployment Checklist
- [ ] `php artisan migrate --force`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `npm run build`
- [ ] `php artisan horizon:terminate` (graceful restart)
- [ ] Health check: `/api/health`
- [ ] Monitor Horizon dashboard for failed jobs
