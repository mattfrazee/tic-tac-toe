<template>
    <label class="inline-flex items-center space-x-4 cursor-pointer select-none">
        <input type="checkbox" :checked="modelValue" class="hidden" @change="toggle"/>
        <span :class="{ 'opacity-50 grayscale': !modelValue }"
            class="relative block w-16 h-9 rounded-full transition-all duration-300
              bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500
              shadow-[0_0_10px_rgba(255,0,255,0.5)]
              hover:shadow-[0_0_20px_rgba(255,0,255,0.7)]
              ring-1 ring-pink-300/30">
            <span :class="{ 'translate-x-7': modelValue }"
                class="absolute block top-1 left-1 w-7 h-7 bg-white rounded-full shadow-md transition-all duration-300 transform"></span>
        </span>
        <span class="text-pink-200 font-semibold text-lg tracking-wide">
            {{ label }}
        </span>
    </label>
</template>

<script setup>
const props = defineProps({
    modelValue: Boolean,
    label: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue', 'change'])

const toggle = (e) => {
    emit('update:modelValue', e.target.checked)
    emit('change', e.target.checked)
}
</script>
