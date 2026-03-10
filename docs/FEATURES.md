# Noryxon Product Features

Noryxon provides a comprehensive suite of features designed to make accepting crypto natively as frictionless as possible.

## 1. Zero-Custody Settlement Engine
- **XPUB Integration:** Merchants provide Master Public Keys. Noryxon derives unique receiving addresses for every transaction.
- **Hardware Wallet Compatibility:** Works seamlessly with Ledger, Trezor, ColdCard, and any BIP-44/49/84 compliant wallet.
- **Direct On-Chain Deposits:** Funds go directly from the customer's wallet to the merchant's wallet. Noryxon never touches the money.
- **No Withdrawal Fees:** Because Noryxon never holds the funds, merchants never pay withdrawal or pooling fees. Gas is paid by the customer.

## 2. Multi-Chain, Omni-Asset Support
- **12+ Native Blockchains:** Bitcoin (including Lightning Network), Ethereum, Solana, Polygon, Avalanche, BNB Chain, Tron, Near, Ton, Stellar, Ripple (XRP), and more.
- **Layer 2 Scalability:** Support for Arbitrum, Optimism, and Base for low-fee ETH and stablecoin settlements.
- **5,000+ Tokens:** From blue-chips (USDC, USDT, DAI) to any liquid liquid ERC-20, SPL, or TRC-20 token.
- **Lightning Network:** Instant, zero-fee Bitcoin payments via integrated LND nodes.

## 3. Real-Time transaction Monitoring
- **Global Node Redundancy:** Noryxon runs its own full nodes backed by enterprise indexers to guarantee 99.99% uptime.
- **Customizable Confirmation Thresholds:** Merchants dictate their risk tolerance. Accept 0-conf for digital goods, or wait for 6 confirmations on large physical orders.
- **Underpayment/Overpayment Handling:** The system automatically flags partial payments and overpayments, exposing them in the API and Dashboard for merchant resolution.

## 4. Merchant Dashboard (`app.noryxon.com`)
- **XPUB Vault:** Securely manage public keys across endless blockchains.
- **Live Terminal:** Watch payments arrive in real-time as blocks confirm.
- **Payment Links & Invoices:** Generate static Payment Links (e.g., `pay.noryxon.com/merchant_name`) or dynamic, itemized crypto invoices with custom branding.
- **Analytics & Export:** View revenue by asset, conversion rates, and export tax-ready CSV/PDF reports of all on-chain activity.
- **Role-Based Access Control (RBAC):** Invite team members (e.g., Accountants with view-only, Developers with API key access).

## 5. Developer Experience (`dev.noryxon.com`)
- **RESTful API:** Clean, intuitive endpoints (`/payments/create`, `/subscriptions`).
- **Instant Webhooks:** Highly reliable `POST` callbacks with cryptographically signed payloads (HMAC-SHA256) and exponential backoff retries.
- **Testnet Sandbox:** A fully segmented environment. Generate testnet addresses and mock webhooks without touching mainnet funds.
- **WebSocket Feed:** Subscribe to live payment streams via WSS for instant UI updates.
- **Official SDKs:** TypeScript, Python, Node.js, Go, PHP.

## 6. Plug-and-Play Integrations
- **E-commerce Plugins:** One-click installations for WooCommerce, Shopify, Magento 2, BigCommerce, PrestaShop, and WHMCS.
- **No-Code Workflows:** Zapier and Make.com integrations to trigger downstream actions (e.g., "When payment confirmed, send Discord message and unlock digital download").

## 7. Subscription & Recurring Billing (Crypto-Native)
- **Time-Based Address Generation:** `POST /subscriptions` creates a recurring schedule. Noryxon automatically issues the customer a new payment address when their cycle renews.
- **Dunning Management:** Automated email reminders sent to customers when their crypto subscription is due for renewal.

## 8. Compliance & Security (The Cypherpunk Way)
- **Zero Fiat, Zero KYC (For standard use):** Because Noryxon does not touch the money or interact with fiat rails, it sits outside the VASP regulatory framework in most jurisdictions.
- **No Off-Ramps:** Pure crypto-to-crypto bridging.
- **Audit Logs:** Every API request, webhook delivery, and dashboard login is immutably logged for the merchant's security review.
