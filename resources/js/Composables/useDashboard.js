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


const formatDate = (iso) => {
  const d = new Date(iso);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
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
  };
}
