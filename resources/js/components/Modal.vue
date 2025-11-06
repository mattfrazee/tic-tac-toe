<template>
    <div>
        <Transition
            enter-active-class="transition-all ease-out duration-300"
            enter-from-class="mt-10 opacity-0 scale-95"
            enter-to-class="mt-0 opacity-100 scale-100"
            leave-active-class="transition-all ease-in duration-200"
            leave-from-class="mt-0 opacity-100 scale-100"
            leave-to-class="mt-10 opacity-0 scale-95">
            <div v-if="visible"
                 class="left-1/2 -translate-x-1/2 w-full top-1/2 -translate-y-1/2 fixed flex items-center justify-center" :style="{ zIndex: zIndex }">
                <div class="bg-gray-900 rounded-lg shadow-xl p-6 w-5/6 text-center text-white">
                    <slot name="title">
                        <h2 class="text-xl font-semibold mb-3">
                            {{ title }}
                        </h2>
                    </slot>

                    <slot name="message">
                        <p class="text-base text-gray-400">
                            {{ message }}
                        </p>
                    </slot>

                    <slot name="actions">
                        <div class="mt-8 space-y-4 mb-4">
                            <button type="button" class="btn-primary btn-small mx-auto" @click="close">
                                Close
                            </button>
                        </div>
                    </slot>
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="visible" class="fixed w-screen h-screen left-0 top-0 bg-black/70"
                 :style="{zIndex: zIndex - 1}"
                 :class="{'pointer-events-none': ! closableBackdrop}"
                 @click="close"></div>
        </Transition>
    </div>
</template>

<script setup>
import {SoundFxType} from "../enums/soundFxType.js";
import {useAudioStore} from "../stores/audioStore.js";

defineProps({
    visible: Boolean,
    closableBackdrop: {
        type: Boolean,
        default: true,
    },
    zIndex: {
        type: Number,
        default: 50,
    },
    closeText: String,
    title: String,
    message: String,
})

const sfx = useAudioStore()
const close = () => {
    sfx.playSound(SoundFxType.CLICK_ALT_1)
    emit('close')
}

const emit = defineEmits(['close'])
</script>
