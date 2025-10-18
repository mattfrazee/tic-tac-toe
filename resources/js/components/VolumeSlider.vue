<template>
    <div class="relative w-full h-3 rounded-full cursor-pointer bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 shadow-lg"
        @mousedown="startDrag"
        @touchstart="startDrag"
        ref="slider">
        <div class="absolute top-1/2 -translate-1/2 bg-white rounded-full shadow-lg border-2 border-pink-300 transition-transform duration-150 ease-out w-6 h-6"
            :style="{
            left: `${volume * 100}%`,
            transform: dragging ? 'translate(0%, 0%) scale(1.2)' : 'translate(0%, 0%) scale(1)'
        }"></div>
    </div>
</template>

<script setup>
import {ref, watch} from 'vue'

const props = defineProps({
    initialVolume: {
        type: Number,
        default: 0.5
    },
})
const emit = defineEmits(['volume-changed', 'start-dragging', 'stop-dragging'])

const volume = ref(props.initialVolume)
const dragging = ref(false)
const slider = ref(null)

const updateVolume = (e) => {
    const rect = slider.value.getBoundingClientRect()
    const clientX = e.touches ? e.touches[0].clientX : e.clientX
    const percent = Math.min(Math.max(0, (clientX - rect.left) / rect.width), 1)
    volume.value = percent
    emit('volume-changed', volume.value)
}

const startDrag = (e) => {
    emit('start-dragging')
    dragging.value = true
    updateVolume(e)
    window.addEventListener('mousemove', updateVolume)
    window.addEventListener('mouseup', stopDrag)
    window.addEventListener('touchmove', updateVolume)
    window.addEventListener('touchend', stopDrag)
}

const stopDrag = () => {
    emit('stop-dragging')
    dragging.value = false
    window.removeEventListener('mousemove', updateVolume)
    window.removeEventListener('mouseup', stopDrag)
    window.removeEventListener('touchmove', updateVolume)
    window.removeEventListener('touchend', stopDrag)
}

watch(() => props.initialVolume, (newValue, oldValue) =>{
    // console.log(newValue, oldValue)
    volume.value = newValue;
});
</script>

<style scoped>
div[ref="slider"] {
    transition: box-shadow 0.2s ease;
}
div[ref="slider"]:hover {
    box-shadow: 0 0 15px rgba(255, 110, 196, 0.5), 0 0 25px rgba(120, 115, 245, 0.5);
}
</style>
