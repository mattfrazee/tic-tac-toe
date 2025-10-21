<template>
    <div>
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 scale-95 translate-y-10"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-10">
            <div v-if="visible"
                 class="fixed inset-0 flex items-center justify-center" :style="{ zIndex: zIndex }">
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

                    <div class="mt-8 space-y-4 mb-4">
                        <SoundFxEvent file="click">
                            <button class="btn-primary w-full mx-auto"
                                    @click="$emit('confirm')">
                                {{ confirmButtonText }}
                            </button>
                        </SoundFxEvent>
                        <SoundFxEvent file="click">
                            <button class="btn-secondary btn-small mx-auto"
                                    @click="$emit('cancel')">
                                {{ cancelButtonText }}
                            </button>
                        </SoundFxEvent>
                    </div>
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
            <div v-if="visible" class="fixed w-screen h-screen left-0 top-0 bg-black/70" :style="{zIndex: zIndex - 1}"></div>
        </Transition>
    </div>
</template>

<script setup>
import SoundFxEvent from "./SoundFxEvent.vue";

defineProps({
    visible: Boolean,
    zIndex: {
        type: Number,
        default: 50,
    },
    title: {
        type: String,
        default: 'Are you sure?',
    },
    confirmButtonText: {
        type: String,
        default: "Yes, I’m sure",
    },
    cancelButtonText: {
        type: String,
        default: 'Cancel',
    },
    message: {
        type: String,
        default: 'This action cannot be undone.',
    },
})

defineEmits(['confirm', 'cancel'])
</script>
