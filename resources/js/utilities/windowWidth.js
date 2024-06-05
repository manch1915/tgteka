import { ref, onMounted, onUnmounted } from 'vue';

export function useWindowWidth() {
    const windowWidth = ref(0); // Initialize with 0 or any default value

    const updateWidth = () => {
        windowWidth.value = window.innerWidth;
    };

    // Check if window is defined (i.e., if it's running in the browser)
    if (typeof window !== 'undefined') {
        windowWidth.value = window.innerWidth; // Initialize width if available

        onMounted(() => {
            window.addEventListener('resize', updateWidth);
        });

        onUnmounted(() => {
            window.removeEventListener('resize', updateWidth);
        });
    }

    // Return the width value
    return { windowWidth };
}
