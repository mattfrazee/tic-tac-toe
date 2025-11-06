<template>
    <div class="flex justify-center items-center gap-1 w-full">
        <div class="flex bg-zinc-800 rounded-full overflow-hidden shadow-lg border border-pink-500/20 w-full">
            <template v-for="key in Object.keys(DifficultyLevel)">
                <SoundFxEvent file="switch">
                    <button type="button"
                            class="btn-difficulty-level"
                            :class="{'active': settings.difficulty === DifficultyLevel[key]}"
                            :title="DifficultyLevel[key]"
                            @click="changeDifficulty(DifficultyLevel[key])">
                        {{ DifficultyLevel[key] }}
                    </button>
                </SoundFxEvent>
            </template>
        </div>
    </div>
</template>

<script setup>
import SoundFxEvent from "./SoundFxEvent.vue";
import {DifficultyLevel} from "../enums/difficultyLevel.js";
import {useSettingsStore} from "../stores/settingsStore.js";

const settings = useSettingsStore();

const changeDifficulty = (level) => {
    settings.difficulty = level
    emit('selectDifficulty', level)
}

const emit = defineEmits(['selectDifficulty'])
</script>

<style scoped>
@reference "tailwindcss";

.btn-difficulty-level {
    @apply flex items-center justify-center py-2 flex-grow font-bold;
    @apply text-cyan-300 hover:text-pink-400 border-r border-zinc-700 hover:bg-zinc-700 last:border-r-0;
}

.btn-difficulty-level.active {
    @apply bg-pink-500 text-white;
}
</style>
