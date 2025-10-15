<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div v-if="visible"
            class="fixed inset-0 flex items-center justify-center bg-black/70 z-50">
            <div class="bg-gray-900 rounded-lg shadow-xl p-6 w-80 text-center text-white">
                <h2 class="text-xl font-semibold mb-3">
                    {{ title }}
                </h2>
                <p class="text-base text-gray-400">
                    {{ message }}
                </p>

                <div class="mt-8 space-y-4">
                    <button class="btn-primary"
                        @click="audio.playSound('click'); $emit('confirm')">
                        Yes, Clear
                    </button>

                    <button class="btn-secondary btn-small"
                        @click="audio.playSound('click'); $emit('cancel')">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import {useAudioStore} from "../stores/audioStore.js";

const audio = useAudioStore();

defineProps({
    visible: Boolean,
    title: {
        type: String,
        default: 'Are you sure?',
    },
    message: {
        type: String,
        default: 'This action cannot be undone.',
    },
})
defineEmits(['confirm', 'cancel'])
</script>
