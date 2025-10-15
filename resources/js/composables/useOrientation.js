import {onMounted, onUnmounted, ref} from 'vue';

export function useOrientation() {
    const isLandscape = ref(false);
    const useScreen = 'orientation' in screen;
    const updateOrientation = () => {
        if (useScreen) {
            isLandscape.value = screen.orientation.type.startsWith('landscape');
        } else {
            isLandscape.value = Math.abs(window.orientation) === 90;
        }
    };

    onMounted(() => {
        updateOrientation();
        if (useScreen) {
            screen.orientation.addEventListener('change', updateOrientation);
        } else {
            window.addEventListener('orientationchange', updateOrientation);
        }
    });

    onUnmounted(() => {
        if (useScreen) {
            screen.orientation.removeEventListener('change', updateOrientation);
        } else {
            window.removeEventListener('orientationchange', updateOrientation);
        }
    });

    return {isLandscape};
}
