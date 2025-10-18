import { defineStore } from 'pinia'

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        startedApp: false,
        playSoundFx: JSON.parse(localStorage.getItem('playSoundFx') ?? 'true'),
        playMusic: JSON.parse(localStorage.getItem('playMusic') ?? 'true'),
        loopMusic: JSON.parse(localStorage.getItem('loopMusic') ?? 'true'),
        vibration: JSON.parse(localStorage.getItem('vibration') ?? 'true'),
    }),

    actions: {
        resetState() {
            // this.startedApp = false;
            this.playSoundFx = true;
            this.playMusic = true;
            this.loopMusic = true;
            this.vibration = true;
        },

        toggleSoundFx() {
            this.playSoundFx = ! this.playSoundFx;
        },

        toggleMusic() {
            this.playMusic = ! this.playMusic;
        },

        toggleLoopMusic() {
            this.loopMusic = ! this.loopMusic;
        },

        toggleVibration() {
            this.vibration = ! this.vibration;
        },
    },
});

useSettingsStore().$subscribe((mutation, state) => {
    const keysToSave = ['playMusic', 'playSoundFx', 'loopMusic', 'vibration']
    keysToSave.forEach(key => {
        localStorage.setItem(key, JSON.stringify(state[key]))
    })
});
