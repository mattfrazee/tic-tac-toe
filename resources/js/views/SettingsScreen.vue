<template>
    <div class="h-full p-6 space-y-4">
        <h2 class="text-2xl font-bold text-center">Settings</h2>
        <div class="space-y-2">
            <div class="cursor-pointer mb-8 flex items-center justify-center px-6 py-3 rounded-xl bg-gray-800 hover:bg-gray-700 transition-all shadow-lg"
                 @click="toggleSoundFx">
                <span v-if="!muted" class="flex items-center gap-2 text-green-400">🔊 Sound FX On</span>
                <span v-else class="flex items-center gap-2 text-red-400">🔇 Sound FX Off</span>
            </div>
<!--            <label class="flex items-center gap-3">-->
<!--                <input v-model="muted" class="accent-fuchsia-500" type="checkbox">-->
<!--                <span>Sound effects</span>-->
<!--            </label>-->
            <label class="flex items-center gap-3">
                <input v-model="music" class="accent-fuchsia-500" type="checkbox">
                <span>Background music</span>
            </label>
        </div>
        <button class="mt-6 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl shadow-lg transition-all"
                @click="router.push('/')">
            Back
        </button>
    </div>
</template>
<script setup>
import {useRouter} from 'vue-router'
import {ref} from 'vue'
import {useSfx} from "../composables/useSfx.js";

const {toggleMute, muted, click, play} = useSfx();
const router = useRouter();
const music = ref(false);

const toggleSoundFx = () => {
    toggleMute();
    if (muted) {
        play(click);
    }
}
</script>
