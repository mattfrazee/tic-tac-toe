import {useSettingsStore} from "../stores/settingsStore.js";

/**
 * Haptic feedback using the Web Vibration API.
 * Works on most Android browsers. Silently fails elsewhere.
 */
export function useVibration() {
    const settings = useSettingsStore();
    const canVibrate = 'vibrate' in navigator;

    const vibrate = (pattern = 50) => {
        if (canVibrate && settings.vibration) {
            navigator.vibrate(pattern)
        }
    }

    return {
        canVibrate,
        vibrate,
    }
}
