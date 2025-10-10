<template>
    <div class="h-full p-6 space-y-4">
        <h2 class="text-2xl font-bold">Settings</h2>
        <div class="space-y-2">
            <label class="flex items-center gap-3">
                <input v-model="sfx" class="accent-fuchsia-500" type="checkbox">
                <span>Sound effects</span>
            </label>
            <label class="flex items-center gap-3">
                <input v-model="music" class="accent-fuchsia-500" type="checkbox">
                <span>Background music</span>
            </label>
        </div>
    </div>
</template>
<script setup>
import {ref} from 'vue'

const sfx = ref(true)
const music = ref(false)
</script>
