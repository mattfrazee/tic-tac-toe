<template>
    <div class="relative w-full mx-auto">
        <!-- Button -->
        <button
            class="w-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-center
             text-white font-extrabold py-3 px-4 rounded-full shadow-xl flex items-center justify-between
             active:scale-95 transition-all duration-200 border-2 border-pink-300 hover:shadow-pink-400/50 text-lg"
            @click="toggleDropdown">
            <span class="line-clamp-1">
                <span class="font-normal" v-if="props.modelValue">Track:</span> {{ label }}
            </span>
            <svg xmlns="http://www.w3.org/2000/svg"
                :class="{'rotate-180': open}"
                class="h-5 w-5 transition-transform duration-300 fill-none stroke-current"
                viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7" class="stroke-2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Dropdown -->
        <transition name="fade">
            <ul v-if="open"
                class="absolute left-0 right-0 mt-2 bg-[rgba(40,0,60,0.95)] rounded-2xl shadow-2xl overflow-hidden z-50 border-2 border-pink-400/40 backdrop-blur-md
                       ring-1 ring-pink-400/30">
                <li v-for="(path, name) in options"
                    :key="name"
                    class="py-4 px-6 text-center cursor-pointer font-bold text-pink-200 text-lg
                        hover:bg-[rgba(180,40,255,0.12)] hover:text-pink-400
                        hover:shadow-[0_0_12px_2px_rgba(236,72,255,0.35)]
                        active:scale-97
                        transition-all duration-200
                        border-b border-purple-700/20 last:border-b-0
                        outline-none focus-visible:ring-2 focus-visible:ring-pink-400/70
                        drop-shadow-[0_2px_12px_rgba(236,72,255,0.10)]"
                    style="box-shadow: 0 0 0 2px rgba(180,40,255,0.07) inset, 0 0 10px 2px rgba(236,72,255,0.12);"
                    @click="selectTrack(name)">
                    🎵 {{ name }}
                </li>
            </ul>
        </transition>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue'

const props = defineProps({
    options: Object,
    modelValue: String
})
const emit = defineEmits(['update:modelValue', 'change'])

const open = ref(false)

const toggleDropdown = () => {
    open.value = !open.value
}

const selectTrack = (name) => {
    emit('update:modelValue', name)
    emit('change', name)
    open.value = false
}

const label = computed(() => (props.modelValue || 'Select Track'))
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
