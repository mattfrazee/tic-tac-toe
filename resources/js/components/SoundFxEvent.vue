<template>
    <slot />
</template>

<script setup>
import { onMounted, getCurrentInstance, onBeforeUnmount } from 'vue'
import { useAudioStore } from '../stores/audioStore.js'

const props = defineProps({
    file: {
        type: String,
        required: true,
    },
    events: {
        type: [String, Array],
        default: 'click', // single string or array of event names
    },
    autoplay: {
        type: Boolean,
        default: false,
    },
})

const audio = useAudioStore()
let el = null
let listeners = []

onMounted(() => {
    if (props.autoplay) {
        audio.playSound(props.file)
        return
    }

    const instance = getCurrentInstance()
    let elCandidate = instance?.subTree?.el
    while (elCandidate && elCandidate.nodeType !== Node.ELEMENT_NODE) {
        elCandidate = elCandidate.nextSibling
    }
    el = elCandidate

    if (!el) return

    const eventsArray = Array.isArray(props.events) ? props.events : [props.events]

    eventsArray.forEach(event => {
        const handler = () => audio.playSound(props.file)
        el.addEventListener(event, handler)
        listeners.push({ event, handler })
    })
})

onBeforeUnmount(() => {
    if (!el || !listeners.length) return

    listeners.forEach(({ event, handler }) => {
        el.removeEventListener(event, handler)
    })
    listeners = []
})
</script>
