<template>
    <div class="h-full p-6 space-y-4">
        <h2 class="text-2xl font-bold text-center">Settings</h2>
        <div class="space-y-4">

            <div class="text-xl font-bold">Sound</div>

            <Checkbox v-model="settings.playSoundFx"
                      label="Play Sound FX"
                      @click.prevent="toggleSoundFx"></Checkbox>

            <Checkbox v-model="settings.playMusic"
                      label="Play Music"
                      @click.prevent="toggleMusic"></Checkbox>

            <Dropdown v-model="audio.currentBackgroundMusic"
                      :options="audio.backgroundMusicFiles"
                      @change="changeMusic"></Dropdown>

            <div v-if="canVibrate">
                <div class="text-xl font-bold mb-2 mt-10">
                    Accessibility
                </div>
                <Checkbox v-model="settings.vibration"
                          label="Use Haptics/Vibration"
                          @change="vibrate([200, 50])"></Checkbox>
            </div>

            <div class="text-xl font-bold mt-10">Data</div>

            <div>
                <button :disabled="isClearing"
                        class="btn-primary w-full btn-small"
                        type="button"
                        @click="audio.playSound('click'); showConfirm = true">
                    {{ isClearing ? 'Clearing...' : 'Clear Scoreboard' }}
                </button>
                <p v-if="message" class="text-pink-300 mt-2">{{ message }}</p>
                <ConfirmModal
                    :visible="showConfirm"
                    message="This will permanently delete all saved games and moves."
                    title="Clear all scores?"
                    @cancel="showConfirm = false"
                    @confirm="clearScores"
                />
            </div>

            <div>
                <button class="btn-primary w-full btn-small" type="button" @click="audio.playSound('click')">
                    Reset Settings
                </button>
            </div>
        </div>
        <RouterLink class="btn-primary btn-glow w-full mt-12" to="/" @click="audio.playSound('click')">
            Back
        </RouterLink>
    </div>
</template>
<script setup>
import {useSettingsStore} from "../stores/settingsStore.js";
import {useAudioStore} from "../stores/audioStore.js";
import {useVibration} from "../composables/useVibration.js";
import Dropdown from "../components/Dropdown.vue";
import Checkbox from "../components/Checkbox.vue";
import {ref} from "vue";
import ConfirmModal from "../components/ConfirmModal.vue";

const settings = useSettingsStore();
const audio = useAudioStore();
const {canVibrate, vibrate} = useVibration();
const isClearing = ref(false)
const showConfirm = ref(false)
const message = ref(null)

const clearScores = async () => {
    isClearing.value = true
    try {
        const {data} = await axios.delete('/api/games/clear')
        message.value = data.message
    } catch (error) {
        message.value = error.response?.data?.message || 'Something went wrong.'
    } finally {
        isClearing.value = false
        showConfirm.value = false
    }
}
const toggleMusic = () => {
    audio.playSound('click');
    settings.toggleMusic();
    audio.stopMusic();
    if (settings.playMusic) {
        audio.playMusic();
    }
}

const toggleSoundFx = () => {
    audio.playSound('click');
    settings.toggleSoundFx();
}

const changeMusic = () => {
    audio.stopMusic();
    audio.playMusic();
}
</script>
