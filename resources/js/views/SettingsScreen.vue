<template>
    <div class="h-full p-6 space-y-4">
        <h2 class="text-2xl font-bold text-center">Settings</h2>
        <div class="space-y-4">
            <!--            <div class="cursor-pointer flex items-center justify-center px-6 py-3 rounded-xl bg-gray-800 hover:bg-gray-700 transition-all shadow-lg"-->
            <!--                 @click="toggleSoundFx">-->
            <!--                <span v-if="settings.playSoundFx" class="flex items-center gap-2 text-green-400">🔊 Sound FX On</span>-->
            <!--                <span v-else class="flex items-center gap-2 text-red-400">🔇 Sound FX Off</span>-->
            <!--            </div>-->

            <!--            <div class="cursor-pointer flex items-center justify-center px-6 py-3 rounded-xl bg-gray-800 hover:bg-gray-700 transition-all shadow-lg"-->
            <!--                 @click="toggleMusic">-->
            <!--                <span v-if="settings.playMusic" class="flex items-center gap-2 text-green-400">🔊 Background Music On</span>-->
            <!--                <span v-else class="flex items-center gap-2 text-red-400">🔇 Background Music Off</span>-->
            <!--            </div>-->

            <div class="text-xl font-bold">Sound</div>

            <Checkbox v-model="settings.playSoundFx" label="Play Sound FX"></Checkbox>

            <Checkbox v-model="settings.playMusic" label="Play Music"></Checkbox>

            <Dropdown v-model="audio.currentBackgroundMusic"
                      :options="audio.backgroundMusicFiles"
                      @change="changeMusic"></Dropdown>

            <div v-if="! canVibrate">
                <div class="text-xl font-bold mb-2 mt-10">
                    Accessibility
                </div>
                <Checkbox v-model="settings.vibration" label="Use Haptics/Vibration"></Checkbox>
            </div>

            <div class="text-xl font-bold mt-10">Data</div>

            <div>
                <button :disabled="isClearing"
                        class="btn-primary block w-full text-lg"
                        type="button"
                        @click="showConfirm = true">
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
                <button class="btn-primary block w-full text-lg" type="button">
                    Reset Settings
                </button>
            </div>
        </div>
        <div class="flex flex-col gap-3 w-full max-w-sm pt-12">
            <RouterLink class="btn" to="/" @click="audio.playSound('click')">
                Back
            </RouterLink>
        </div>
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
const {canVibrate} = useVibration();
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
