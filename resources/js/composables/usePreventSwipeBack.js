export function usePreventSwipeBack() {
    let cleanupFn = null;

    function enableSwipeBack() {
        function onTouchStart(e) {
            if (e.touches.length === 1) {
                const touch = e.touches[0];
                const screenW = window.innerWidth;
                const edgeThreshold = Math.min(screenW * 0.08, 50); // 8% or 50px max
                // block if the gesture starts too close to screen edge
                if (touch.clientX < edgeThreshold || touch.clientX > screenW - edgeThreshold) {
                    e.preventDefault();
                }
            }
        }

        window.addEventListener('touchstart', onTouchStart, {passive: false});
        cleanupFn = () => window.removeEventListener('touchstart', onTouchStart);
    }

    function disableSwipeBack() {
        if (cleanupFn) {
            cleanupFn();
        }
        cleanupFn = null;
    }

    return {enableSwipeBack, disableSwipeBack};
}
