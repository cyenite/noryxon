# Noryxon Product Features

Noryxon is a **digital asset invoicing and documentation platform**. It generates compliant invoices and tax reports for on-chain transactions, enabling users to off-ramp through licensed partners with proper Digital Asset Tax (DAT) classification — avoiding the 30% "unexplained income" tax applied to undocumented crypto withdrawals.

**We never custody funds. We never handle off-ramping. We generate documentation.**

---

## 1. Invoice Engine (Zero-Custody Documentation)
- **Transaction-Linked Invoices:** Every invoice is cryptographically linked to an on-chain transaction hash. Tamper-proof and verifiable.
- **Payer Details & Purpose:** Invoices include payer information, transaction purpose, amount, chain, and KES equivalent for local tax compliance.
- **Universal Wallet System:** Connect any wallet type — exchange accounts (Binance, Coinbase, Kraken, KuCoin, Bybit, OKX via read-only API keys), software wallets (MetaMask, Trust Wallet, Phantom, Rabby, Rainbow via address), hardware wallets (Ledger, Trezor, ColdCard via xpub/ypub/zpub), or any manual blockchain address.
- **XPub Derivation:** For hardware wallets, Noryxon derives unique receiving addresses using BIP-44/49/84 standards — never stores private keys.
- **Exchange API Monitoring:** Read-only API connections to major exchanges. API credentials encrypted at rest. Noryxon never has withdrawal permissions.
- **Zero Custody:** Funds flow directly between parties. Noryxon only observes and documents.

## 2. Multi-Chain Verification
- **12+ Native Blockchains:** Bitcoin, Ethereum, Solana, Polygon, Avalanche, BNB Chain, Tron, Near, Ton, Stellar, Ripple (XRP), and more.
- **Layer 2 Support:** Arbitrum, Optimism, and Base for low-fee stablecoin transactions.
- **5,000+ Tokens:** All major stablecoins (USDC, USDT, DAI) plus any liquid ERC-20, SPL, or TRC-20 token.
- **Real-Time Monitoring:** Transactions detected and verified in ~45ms average latency via redundant full nodes and enterprise indexers.
- **Configurable Confirmation Thresholds:** Users dictate risk tolerance per transaction.

## 3. Tax-Ready Documentation
- **Digital Asset Tax (DAT) Reports:** Auto-generated tax reports compliant with Kenya Revenue Authority (KRA) requirements.
- **Export Formats:** PDF and CSV reports ready for tax authority filing.
- **Multi-Jurisdiction Support:** Enterprise tier includes multi-jurisdiction tax report formats for international operations.
- **Transaction Classification:** Proper DAT classification prevents crypto from being taxed as "unexplained income" at 30%.

## 4. Off-Ramp Partner Redirects
- **No VASP Required:** Noryxon generates documentation only — it never processes withdrawals or handles fiat conversion.
- **Licensed Partner Network:** Invoice + tax report are attached to the off-ramp redirect, enabling compliant withdrawal through licensed partners.
- **API-Driven Redirects:** `offramp_redirect` URL included in invoice API responses for seamless integration.

## 5. Invoice Dashboard (`app.noryxon.com`)
- **Invoice Vault:** Central repository of all generated invoices and documentation.
- **Wallets:** Manage connected wallets across multiple blockchains — exchange accounts, software wallets, hardware wallets (XPUB), and manual addresses. Card grid UI with multi-step connect modal.
- **Documented Volume Analytics:** View total documented volume, pending invoices, and generation history.
- **Recent Documented Transactions:** Real-time feed of invoice activity.
- **Quick Actions:** Generate invoice, manage wallets, access developer portal, view tax reports.
- **Role-Based Access Control (RBAC):** Invite team members with appropriate permission levels.

## 6. Developer Experience (`dev.noryxon.com`)
- **RESTful Invoice API:** Clean endpoints — `POST /v1/invoices`, `GET /v1/invoices/:id`, `GET /v1/invoices/:id/tax-report`, `GET /v1/wallets`.
- **Instant Signed Webhooks:** Events include `invoice.generated`, `invoice.verified`, `transaction.detected`, `transaction.confirmed`, `tax_report.ready`. HMAC-SHA256 signed with exponential backoff retries.
- **Official SDKs:** TypeScript/Node.js (`@noryxon/sdk`), Python (`noryxon`), Go (`noryxon.com/sdk`), PHP (`noryxon/sdk`).
- **Testnet Sandbox:** Full invoice simulation environment — generate test invoices, trigger mock webhooks, test SDK integrations without touching mainnet.
- **API Playground:** Interactive endpoint testing with mock responses.

## 7. SDK Integration
- **npm:** `npm install @noryxon/sdk`
- **pip:** `pip install noryxon`
- **go:** `go get noryxon.com/sdk`
- **composer:** `composer require noryxon/sdk`
- SDKs wrap the REST API for invoice creation, retrieval, tax report generation, and webhook verification.

## 8. Compliance & Security
- **Zero Custody:** Noryxon never touches, holds, or routes user funds. Documentation only.
- **No VASP Licensing Required:** By design, the platform generates documentation and redirects to licensed off-ramp partners — avoiding VASP regulatory requirements.
- **Audit Logs:** Every API request, webhook delivery, invoice generation, and dashboard action is immutably logged.
- **Key Isolation:** API Keys and Webhook Secrets encrypted at rest.
- **Rate Limiting:** Token Bucket algorithm, 100 req/min default for free tier.

## 9. Pricing
- **Developer Tier:** Free up to $10,000/mo documented volume, then 1% per transaction. Includes all core features, SDKs, sandbox, and off-ramp redirects.
- **Node Operator Tier:** Custom volume-based pricing for high-throughput integrators. Includes white-label invoice templates, multi-jurisdiction tax formats, priority support, and custom SDK builds.
- **No setup fees. No monthly subscriptions.**
