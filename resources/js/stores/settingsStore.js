import { defineStore } from 'pinia'

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        startedApp: false,
        playSoundFx: true,
        playMusic: true,
        vibration: true,
    }),

    actions: {
        toggleSoundFx() {
            this.playSoundFx = ! this.playSoundFx;
        },

        toggleMusic() {
            this.playMusic = ! this.playMusic;
        },

        toggleVibration() {
            this.vibration = ! this.vibration;
        },
    },
})
