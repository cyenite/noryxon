import { ref, computed } from 'vue';

const chars = 'abcdef0123456789';
const generateHash = (len = 64) => '0x' + Array.from({ length: len }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
const generateShortHash = () => generateHash(16) + '...';

const chains = [
  { id: 'btc', name: 'Bitcoin', symbol: 'BTC', color: '#F7931A', icon: '₿' },
  { id: 'eth', name: 'Ethereum', symbol: 'ETH', color: '#627EEA', icon: 'Ξ' },
  { id: 'sol', name: 'Solana', symbol: 'SOL', color: '#9945FF', icon: '◎' },
  { id: 'matic', name: 'Polygon', symbol: 'MATIC', color: '#8247E5', icon: '⬡' },
  { id: 'avax', name: 'Avalanche', symbol: 'AVAX', color: '#E84142', icon: '▲' },
  { id: 'bnb', name: 'BNB Chain', symbol: 'BNB', color: '#F3BA2F', icon: '◆' },
  { id: 'trx', name: 'Tron', symbol: 'TRX', color: '#FF0013', icon: '◈' },
  { id: 'arb', name: 'Arbitrum', symbol: 'ARB', color: '#28A0F0', icon: '⟐' },
  { id: 'op', name: 'Optimism', symbol: 'OP', color: '#FF0420', icon: '⊕' },
  { id: 'base', name: 'Base', symbol: 'BASE', color: '#0052FF', icon: '◉' },
  { id: 'near', name: 'Near', symbol: 'NEAR', color: '#00C08B', icon: '◐' },
  { id: 'xlm', name: 'Stellar', symbol: 'XLM', color: '#08B5E5', icon: '✦' },
];

const tokens = ['USDC', 'USDT', 'DAI', 'BTC', 'ETH', 'SOL', 'AVAX', 'MATIC', 'WBTC', 'WETH'];

// ─── Wallet Provider Configurations ───

const walletProviders = {
  exchanges: [
    { id: 'binance', name: 'Binance', icon: '◆', requiresPassphrase: false, color: '#F3BA2F' },
    { id: 'coinbase', name: 'Coinbase', icon: '◉', requiresPassphrase: true, color: '#0052FF' },
    { id: 'kraken', name: 'Kraken', icon: '◈', requiresPassphrase: false, color: '#5741D9' },
    { id: 'kucoin', name: 'KuCoin', icon: '⬡', requiresPassphrase: true, color: '#24AE8F' },
    { id: 'bybit', name: 'Bybit', icon: '▲', requiresPassphrase: false, color: '#F7A600' },
    { id: 'okx', name: 'OKX', icon: '⊕', requiresPassphrase: true, color: '#000000' },
  ],
  software: [
    { id: 'metamask', name: 'MetaMask', icon: '🦊', chains: ['eth', 'bnb', 'matic', 'arb', 'op', 'base', 'avax'], color: '#E2761B' },
    { id: 'trustwallet', name: 'Trust Wallet', icon: '🛡️', chains: ['eth', 'bnb', 'sol', 'trx', 'matic', 'btc'], color: '#3375BB' },
    { id: 'phantom', name: 'Phantom', icon: '👻', chains: ['sol', 'eth', 'matic'], color: '#AB9FF2' },
    { id: 'rabby', name: 'Rabby', icon: '🐰', chains: ['eth', 'bnb', 'matic', 'arb', 'op', 'base', 'avax'], color: '#8697FF' },
    { id: 'rainbow', name: 'Rainbow', icon: '🌈', chains: ['eth', 'arb', 'op', 'base', 'matic'], color: '#001E59' },
    { id: 'generic', name: 'Other Wallet', icon: '💼', chains: null, color: '#9CA3AF' },
  ],
};

const getProviderConfig = (type, providerId) => {
  const list = type === 'exchange' ? walletProviders.exchanges : walletProviders.software;
  return list?.find(p => p.id === providerId) || { id: providerId, name: providerId, icon: '?', color: '#9CA3AF' };
};

const getWalletTypeLabel = (type) => {
  return { exchange: 'Exchange', software: 'Software Wallet', xpub: 'XPub Key', manual: 'Manual Address' }[type] || type;
};

const getWalletTypeColor = (type) => {
  return { exchange: 'text-primary', software: 'text-blue-500', xpub: 'text-purple-500', manual: 'text-on-surface-variant' }[type] || 'text-on-surface-variant';
};

const getWalletTypeBg = (type) => {
  return { exchange: 'bg-primary/10', software: 'bg-blue-500/10', xpub: 'bg-purple-500/10', manual: 'bg-on-surface-variant/10' }[type] || 'bg-on-surface-variant/10';
};


const formatDate = (iso) => {
  const d = new Date(iso);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
};

// Generates mock daily volume data for chart display
const generateDailyVolume = (days = 30) => {
  const data = [];
  const now = new Date();
  let baseVolume = 20000 + Math.random() * 30000;
  for (let i = days - 1; i >= 0; i--) {
    const date = new Date(now);
    date.setDate(date.getDate() - i);
    baseVolume = Math.max(5000, baseVolume + (Math.random() - 0.45) * 8000);
    data.push({
      date: date.toISOString().split('T')[0],
      volume: Math.round(baseVolume),
      transactions: Math.floor(20 + Math.random() * 180),
    });
  }
  return data;
};

// Generates a breakdown of volume by chain for analytics
const generateChainBreakdown = () => {
  const chainData = [
    { id: 'eth', name: 'Ethereum', symbol: 'ETH', color: '#627EEA', volume: 285000 + Math.random() * 50000 },
    { id: 'btc', name: 'Bitcoin', symbol: 'BTC', color: '#F7931A', volume: 210000 + Math.random() * 40000 },
    { id: 'sol', name: 'Solana', symbol: 'SOL', color: '#9945FF', volume: 95000 + Math.random() * 20000 },
    { id: 'bnb', name: 'BNB Chain', symbol: 'BNB', color: '#F3BA2F', volume: 72000 + Math.random() * 15000 },
    { id: 'arb', name: 'Arbitrum', symbol: 'ARB', color: '#28A0F0', volume: 55000 + Math.random() * 10000 },
    { id: 'matic', name: 'Polygon', symbol: 'MATIC', color: '#8247E5', volume: 38000 + Math.random() * 8000 },
  ];
  const total = chainData.reduce((s, c) => s + c.volume, 0);
  return chainData
    .map(c => ({ ...c, volume: Math.round(c.volume), percentage: Math.round((c.volume / total) * 100) }))
    .sort((a, b) => b.volume - a.volume);
};

// Generates a breakdown of volume by asset for analytics
const generateAssetBreakdown = () => {
  const assets = [
    { symbol: 'USDC', volume: 320000 + Math.random() * 60000, color: '#2775CA' },
    { symbol: 'USDT', volume: 198000 + Math.random() * 40000, color: '#26A17B' },
    { symbol: 'ETH', volume: 145000 + Math.random() * 30000, color: '#627EEA' },
    { symbol: 'BTC', volume: 112000 + Math.random() * 20000, color: '#F7931A' },
    { symbol: 'SOL', volume: 67000 + Math.random() * 15000, color: '#9945FF' },
    { symbol: 'Other', volume: 45000 + Math.random() * 10000, color: '#848E9C' },
  ];
  const total = assets.reduce((s, a) => s + a.volume, 0);
  return assets.map(a => ({
    ...a,
    volume: Math.round(a.volume),
    percentage: Math.round((a.volume / total) * 100),
  }));
};

export function useDashboard() {
  // ===== Utilities =====
  const getChainConfig = (chainId) => {
    return chains.find(c => c.id === chainId) || { id: 'unknown', name: 'Unknown', symbol: '???', color: '#9CA3AF', icon: '?' };
  };

  // ===== Testnet Mode =====
  const isTestnet = ref(false);

  const toggleTestnet = () => {
    isTestnet.value = !isTestnet.value;
  };

  return {
    // Testnet
    isTestnet,
    toggleTestnet,
    // Utilities
    chains,
    tokens,
    formatDate,
    formatCurrency,
    generateHash,
    generateShortHash,
    getChainConfig,
    // Wallet providers
    walletProviders,
    getProviderConfig,
    getWalletTypeLabel,
    getWalletTypeColor,
    getWalletTypeBg,
    // Chart helpers
    generateDailyVolume,
    generateChainBreakdown,
    generateAssetBreakdown,
  };
}
