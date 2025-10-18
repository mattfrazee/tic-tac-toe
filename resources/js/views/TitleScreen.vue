<template>
    <div class="h-full min-h-screen flex flex-col items-center justify-center gap-6 p-6 bg-[#ffe3c3]">
        <div v-if="! settings.startedApp"
             class="h-screen absolute inset-0 z-50 flex flex-col items-center justify-center bg-gradient-to-br from-purple-600 via-pink-500 to-pink-400 text-white"
             @click="startGame">
            <span class="block text-6xl font-extrabold animate-pulse drop-shadow-[0_0_10px_rgba(255,192,203,0.8)] text-center leading-tight">
                ✨<br/>
                Tap to Start
                <br/>✨
            </span>

            <div class="absolute origin-center inset-0 overflow-hidden pointer-events-none">
                <span v-for="n in 50" :key="n"
                    class="xo animate-xo-fall -top-[100px] font-extrabold"
                    :style="{
                      left: `${Math.random() * 300}px`,
                      animationDelay: `${Math.random() * 5}s`,
                      fontSize: `${Math.random() * 4 + 1}rem`,
                      opacity: Math.random() * 0.5 + 0.3,
                      transform: `rotate(${Math.random() * 360}deg)`
                    }">
                    {{ Math.random() > 0.5 ? 'X' : 'O' }}
                </span>
            </div>
        </div>

        <img alt="Tic Tac Toe" class="size-96 object-contain mb-4" src="/images/logo.png"/>

        <div class="flex flex-col gap-4 w-full max-w-sm">
            <RouterLink class="btn-primary btn-glow w-full" to="/game"
                        @click="audio.playSound('playerSelect')">
                Play
            </RouterLink>
            <RouterLink class="btn-primary w-full" to="/scores"
                        @click="audio.playSound('click')">
                Scores
            </RouterLink>
            <RouterLink class="btn-primary w-full" to="/settings"
                        @click="audio.playSound('click')">
                Settings
            </RouterLink>
        </div>

        <orientation-warning />
    </div>
</template>
<script setup>
import {useSettingsStore} from "../stores/settingsStore.js";
import {useAudioStore} from '../stores/audioStore'
import OrientationWarning from "../components/OrientationWarning.vue";
import {onMounted, onUnmounted} from "vue";

const settings = useSettingsStore();
const audio = useAudioStore();

const startGame = () => {
    settings.startedApp = true;
    audio.playSound('notification')
    audio.playMusic();
}

onMounted(() => document.querySelector('html').classList.add('non-scrollable'))
onUnmounted(() => document.querySelector('html').classList.remove('non-scrollable'))
</script>
<style scoped>
.xo {
    position: absolute;
    color: rgba(255, 255, 255, 0.6);
    text-shadow: 0 0 6px rgba(255, 255, 255, 0.5);
    user-select: none;
    animation: xo-fall 8s ease-in-out infinite;
}

@keyframes xo-fall {
    0% {
        transform: translate(0, 0) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translate(100vw, 100vh) rotate(360deg);
        opacity: 0;
    }
}
</style>
