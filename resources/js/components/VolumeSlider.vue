<template>
    <div ref="track"
        class="relative w-full h-3 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 rounded-full cursor-pointer select-none touch-none"
        @pointerdown="onPointerDown">
        <div :style="{
                left: `${volume * 100}%`,
                transform: `translate(-${volume * 100}%, 0%)`
            }"
            class="absolute -top-1/2 w-6 h-6 bg-white rounded-full border-2 border-pink-300 shadow-lg transition-transform duration-150 ease-out will-change-transform"></div>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";

const props = defineProps({
    modelValue: {type: Number, default: 0.5},
});

const emit = defineEmits(["update:modelValue", 'volume-changed', 'start-dragging', 'stop-dragging']);

const track = ref(null);
const dragging = ref(false);
const volume = ref(props.modelValue);

watch(
    () => props.modelValue,
    (newValue) => {
        if (!dragging.value) {
            volume.value = newValue;
        }
    }
);

function updatePosition(e) {
    const rect = track.value.getBoundingClientRect();
    const percent = Math.min(Math.max(0, (e.clientX - rect.left) / rect.width), 1);
    volume.value = percent;
    emit("volume-changed", percent);
    emit("update:modelValue", percent);
}

function onPointerDown(e) {
    emit('start-dragging')
    dragging.value = true;
    e.target.setPointerCapture(e.pointerId);
    updatePosition(e);

    const move = (ev) => updatePosition(ev);
    const up = (ev) => {
        emit('stop-dragging')
        dragging.value = false;
        e.target.releasePointerCapture(e.pointerId);
        window.removeEventListener("pointermove", move);
        window.removeEventListener("pointerup", up);
    };

    window.addEventListener("pointermove", move);
    window.addEventListener("pointerup", up);
}
</script>
