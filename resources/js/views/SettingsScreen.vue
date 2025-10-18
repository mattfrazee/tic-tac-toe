<template>
    <div class="h-full safe-area-container overflow-auto">
        <div class="h-full p-6">
            <h2 class="text-4xl font-bold mb-4 text-center">Settings</h2>
            <div class="space-y-4">

                <div class="settings-title">Sound Fx</div>

                <Checkbox v-model="settings.playSoundFx"
                          label="Enable Sound Fx"
                          @click.prevent="toggleSoundFx"></Checkbox>

                <div :class="{'opacity-40 pointer-events-none': ! settings.playSoundFx}">
                    <div class="text-pink-200 font-semibold text-lg tracking-wide mb-4 flex gap-4 items-baseline">
                       <div>Volume</div>
                        <EqualizerBars class="" :height="`${audio.soundFxVolume * 20}px`" :bars="5" :playing="audio.soundFxIsPlaying" />
                    </div>
                    <VolumeSlider ref="soundVolumeSlider"
                                  :initial-volume="audio.soundFxVolume"
                                  @start-dragging="audio.playSound('retroRun', {loop: true})"
                                  @stop-dragging="audio.stopSoundFx"
                                  @volume-changed="(v) => audio.updateSoundFxVolume(v)" />
                </div>

                <div class="settings-title">
                    Music
                </div>

                <Checkbox v-model="settings.playMusic"
                          label="Enable Music"
                          @click.prevent="toggleMusic"></Checkbox>

                <div class="flex gap-4 items-center" :class="{'opacity-40 pointer-events-none': ! settings.playMusic}">
                    <div class="text-pink-200 font-semibold text-lg tracking-wide">
                        Track:
                    </div>
                    <MusicDropdown v-model="audio.currentMusic"
                                   :music-volume="audio.musicVolume"
                                   :music-is-playing="audio.musicIsPlaying"
                                   :options="audio.musicFiles"
                                   @change="changeMusic" />
                </div>

                <div :class="{'opacity-40 pointer-events-none': ! settings.playMusic}">
                    <AudioControls :is-looping="settings.loopMusic"
                                   :is-playing="audio.musicIsPlaying"
                                   :is-paused="audio.musicIsPaused"
                                   @loop="settings.toggleLoopMusic"
                                   @next="audio.nextTrack"
                                   @prev="audio.previousTrack"
                                   @pause="audio.pauseMusic"
                                   @play="audio.playMusic"
                                   @stop="audio.stopMusic" />
                </div>

                <div :class="{'opacity-40 pointer-events-none': ! settings.playMusic}">
                    <div class="text-pink-200 font-semibold text-lg tracking-wide mb-4">
                        Volume
                    </div>
                    <VolumeSlider class="mb-6"
                                  ref="musicVolumeSlider"
                                  :initialVolume="audio.musicVolume"
                                  @start-dragging="audio.playMusic"
                                  @volume-changed="(v) => audio.updateMusicVolume(v)" />
                </div>

                <div v-if="canVibrate">
                    <div class="settings-title mb-4">
                        Accessibility
                    </div>
                    <Checkbox v-model="settings.vibration"
                              label="Use Haptics/Vibration"
                              @click.prevent="toggleVibration" />
                </div>

                <div class="settings-title">
                    Data
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <button :disabled="isClearing"
                                class="btn-primary w-full btn-small"
                                type="button"
                                @click="openClearScoresModal">
                            {{ isClearing ? 'Clearing...' : 'Clear Scoreboard' }}
                        </button>

                        <Transition
                            enter-active-class="transition ease-out duration-300"
                            enter-from-class="opacity-0 scale-95 -translate-y-5"
                            enter-to-class="opacity-100 scale-100 translate-y-0"
                            leave-active-class="transition ease-in duration-200"
                            leave-from-class="opacity-100 scale-100 translate-y-0"
                            leave-to-class="opacity-0 scale-95 -translate-y-5"
                        >
                            <p v-if="message" class="text-pink-300 text-center mt-2">
                                {{ message }}
                            </p>
                        </Transition>
                        <ConfirmModal
                            :visible="showClearScoresConfirm"
                            message="This will permanently delete all saved games and moves."
                            title="Clear all scores?"
                            @cancel="showClearScoresConfirm = false"
                            @confirm="clearScores"
                        />
                    </div>

                    <div>
                        <button :disabled="isClearing" class="btn-primary w-full btn-small" type="button" @click="openResetSettingsModal">
                            Reset Settings
                        </button>
                        <ConfirmModal
                            :visible="showResetSettingsConfirm"
                            message="This will reset all game settings. This does not clear scores."
                            title="Reset Settings?"
                            @cancel="showResetSettingsConfirm = false"
                            @confirm="resetSettings"
                        />
                    </div>
                </div>

            </div>

            <div class="pb-8">
                <RouterLink class="btn-primary btn-glow w-full mt-12" to="/" @click="audio.playSound('click')">
                    Back
                </RouterLink>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {useVibration} from "../composables/useVibration.js";
import {useSettingsStore} from "../stores/settingsStore.js";
import {useAudioStore} from "../stores/audioStore.js";
import Checkbox from "../components/Checkbox.vue";
import ConfirmModal from "../components/ConfirmModal.vue";
import VolumeSlider from "../components/VolumeSlider.vue";
import AudioControls from "../components/AudioControls.vue";
import MusicDropdown from "../components/MusicDropdown.vue";
import EqualizerBars from "../components/EqualizerBars.vue";

const settings = useSettingsStore();
const audio = useAudioStore();
const {canVibrate, vibrate} = useVibration();
const isClearing = ref(false);
const showClearScoresConfirm = ref(false);
const showResetSettingsConfirm = ref(false);
const message = ref(null);
const soundVolumeSlider = ref(null);
const musicVolumeSlider = ref(null);

const toggleMusic = () => {
    audio.playSound('switch');
    settings.toggleMusic();
    audio.stopMusic();
    if (settings.playMusic) {
        audio.playMusic();
    }
}
const toggleSoundFx = () => {
    audio.playSound('switch');
    settings.toggleSoundFx();
}
const toggleVibration = () => {
    audio.playSound('switch');
    vibrate([200, 50]);
    settings.toggleVibration();
}

const changeMusic = () => {
    audio.stopMusic();
    audio.playMusic();
}

const openClearScoresModal = () => {
    audio.playSound('click');
    showClearScoresConfirm.value = true
}
const openResetSettingsModal = () => {
    audio.playSound('click');
    showResetSettingsConfirm.value = true;
}

const resetSettings = () => {
    settings.resetState();
    audio.resetState();
    showResetSettingsConfirm.value = false;
}
const clearScores = async () => {
    isClearing.value = true;
    try {
        const {data} = await axios.delete('/api/games/clear');
        message.value = data.message;
    } catch (error) {
        message.value = error.response?.data?.message || 'Something went wrong.';
    } finally {
        isClearing.value = false;
        showClearScoresConfirm.value = false;
        setTimeout(() => message.value = null, 8000);
    }
}

onMounted(() => document.querySelector('html').classList.add('safe-area', 'non-scrollable'))
onUnmounted(() => document.querySelector('html').classList.remove('safe-area', 'non-scrollable'))
</script>
