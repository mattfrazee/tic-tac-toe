<template>
    <div
        v-if="visible"
        class="fixed inset-0 flex items-center justify-center bg-black/70 z-50"
    >
        <div class="bg-gray-900 text-white p-6 rounded-xl shadow-lg w-80 text-center">
            <h2 class="text-lg font-semibold mb-3">{{ title }}</h2>
            <p class="text-sm text-gray-400 mb-5">{{ message }}</p>

            <div class="flex justify-center gap-3">
                <button
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-lg"
                    @click="$emit('cancel')"
                >
                    Cancel
                </button>
                <button
                    class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg font-semibold"
                    @click="$emit('confirm')"
                >
                    Yes, Clear
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
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
