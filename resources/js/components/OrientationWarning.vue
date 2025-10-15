<template>
    <transition name="fade">
        <div v-if="isLandscape" class="z-50 fixed top-2 left-1/2 -translate-x-1/2 bg-pink-500/80 text-white text-sm px-3 py-1 rounded-full shadow-md">
            Rotate to portrait for best view
        </div>
    </transition>
</template>

<script setup>
import {useOrientation} from "../composables/useOrientation.js";

const { isLandscape } = useOrientation();
</script>
