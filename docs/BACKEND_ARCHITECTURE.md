# Noryxon Backend Architecture

Noryxon operates as a stateless monitoring layer. Its primary mandate is to observe blockchains, validate payments securely, and notify merchants via low-latency webhooks. **It never custodians funds, private keys, or seed phrases.**

## System Overview
The backend is split into three core microservices:
1. **API Gateway & Core API:** Handles payment creation requests, merchant configuration, and API Key authentication.
2. **Chain specific Indexers (The "Eyes"):** A cluster of highly-redundant indexers connected to RPC nodes that listen for incoming transactions.
3. **Webhook Dispatcher (The "Mouth"):** Responsible for delivering payment confirmations to merchants, complete with cryptographic signatures and exponential backoffs.

---

## 1. XPUB Derivation Engine (Stateless Address Generation)

When a merchant calls `POST /payments`, Noryxon must generate a fresh receiving address.

- **Process:** The system retrieves the merchant's stored XPUB (Extended Public Key) for the target blockchain.
- **Derivation Paths:** Noryxon adheres strictly to BIP-44 (Legacy), BIP-49 (Nested SegWit), and BIP-84 (Native SegWit) standards.
  ```
  m / purpose' / coin_type' / merchant_account' / change / address_index
  ```
- **Incrementing Index:** Every time a new payment is requested, Noryxon increments the `address_index` for that XPUB in the Redis database, derives the new public key off-chain, and caches the expected parameters (amount, currency, expiration).
- **Security:** Private keys never exist on the Noryxon servers. Even if Noryxon is entirely compromised, an attacker can only view the merchant's balance and address history—they cannot move funds.

## 2. Real-Time Blockchain Monitoring

Once a payment address is generated, the Indexer cluster begins monitoring it.

- **Infrastructure:** Noryxon runs a fleet of redundant full nodes for supported chains (Bitcoin core, Geth, Erigon, Solana validators), supplemented by premium 3rd-party RPC providers to ensure no single point of failure.
- **Ingestion Pipeline:**
  - Indexers subscribe to pending mempool transactions and new blocks via WebSocket.
  - They filter for transaction outputs matching the active `address_index`.
- **Validation Logic:**
  - **Amount Match:** Verifies the `msg.value` (ETH) or output value matches or exceeds the requested amount (handling potential exchange rate dust variations).
  - **Token Validation:** For ERC-20 / TRC-20 payments, standardizes decimals and ensures the exact contract address was interacted with.
  - **Confirmation Thresholds:** Tracks block depth. Merchants can configure thresholds (e.g., 1 confirmation for low-value items, 6 confirmations for high-value).

## 3. The Webhook Dispatcher

When the Indexer confirms a valid payment meeting the merchant's configured confirmation threshold, it queues a message for the Webhook Dispatcher.

- **Payload Construction:** Creates a JSON payload detailing the `payment_id`, `tx_hash`, `amount_received`, and `chain`.
- **Cryptographic Signature:** Computes an HMAC-SHA256 signature of the raw payload body using the merchant's secret Webhook Key. This is injected into the `X-Noryxon-Signature` HTTP header.
- **Delivery & Retries:** 
  - Attempts a `POST` request to the merchant's configured `webhook_url`.
  - If the merchant's server returns anything but HTTP `200` or `201`, the payload enters a Dead Letter Queue (DLQ).
  - Exponential backoff kicks in: Retries occur at +1m, +5m, +30m, +1hr, up to 10 attempts over 3 days.

## 4. Database & Storage Architecture

- **PostgreSQL:** Primary source of truth for Merchant Profiles, XPUB entries, Payment Metadata (UUIDs, statuses, requested amounts), and API Keys.
- **Redis:** Used for rate limiting (Token Bucket algorithm), active address monitoring cache, and fast index increments for HD Wallets.
- **Kafka / RabbitMQ:** Event bus connecting the Core API, Indexers, and Webhook Dispatcher.

## 5. Security & Compliance Enforcement

- **No Fiat Rails:** The backend explicitly has no bank integrations, no stripe connections, and no VASP logic.
- **Key Isolation:** API Keys and Webhook Secrets are hashed and encrypted at rest using KMS (Key Management Service).
- **Rate Limiting:** IP and API Key based rate limits default to 100 req/min for free tiers.
