import { ref, onMounted, computed } from 'vue';
import { useNotifications } from './useNotifications';

const ALL_THEMES = ['void', 'corporate', 'terminal', 'neon'];
const DEFAULT_THEMES = ['void', 'corporate'];
const currentTheme = ref('void');
const unlockedThemes = ref([]);

export function useTheme() {
    const { addNotification } = useNotifications();
    const applyTheme = (theme) => {
        if (typeof window !== 'undefined') {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('noryxon-theme', theme);
        }
    };

    const availableThemes = computed(() => {
        return [...new Set([...DEFAULT_THEMES, ...unlockedThemes.value])];
    });

    const setTheme = (theme) => {
        if (ALL_THEMES.includes(theme)) {
            currentTheme.value = theme;
            applyTheme(theme);
        }
    };

    const cycleTheme = () => {
        const themes = availableThemes.value;
        const index = themes.indexOf(currentTheme.value);
        const nextIndex = (index + 1) % themes.length;
        setTheme(themes[nextIndex]);
    };

    const unlockTheme = (themeName) => {
        if (ALL_THEMES.includes(themeName) && !unlockedThemes.value.includes(themeName)) {
            unlockedThemes.value.push(themeName);
            if (typeof window !== 'undefined') {
                localStorage.setItem('noryxon-unlocked-themes', JSON.stringify(unlockedThemes.value));
            }
            
            // Activate it immediately to show them what they found
            setTheme(themeName);
            
            const titles = {
                'terminal': 'Hacker Mode Activated',
                'neon': 'Synthwave Discovered'
            };
            
            addNotification(
                titles[themeName] || 'Theme Unlocked',
                `You found a secret. Keep being curious to unlock perks and discounts in the system.`,
                'info',
                6000
            );
        }
    };

    onMounted(() => {
        if (typeof window !== 'undefined') {
            const savedUnlocked = localStorage.getItem('noryxon-unlocked-themes');
            if (savedUnlocked) {
                try {
                    unlockedThemes.value = JSON.parse(savedUnlocked);
                } catch (e) {
                    unlockedThemes.value = [];
                }
            }

            const saved = localStorage.getItem('noryxon-theme');
            if (saved && ALL_THEMES.includes(saved)) {
                setTheme(saved);
            } else {
                setTheme('void');
            }
        }
    });

    return {
        currentTheme,
        availableThemes,
        setTheme,
        cycleTheme,
        unlockTheme,
        THEMES: ALL_THEMES
    };
}
