import {defineStore} from 'pinia'
import {Howl} from 'howler'
import {useSettingsStore} from "./settingsStore.js";

const settings = useSettingsStore();
export const useAudioStore = defineStore('audio', {
    state: () => ({
        backgroundMusic: null,
        musicIsPlaying: false,
        backgroundMusicFiles: {
            'Jazz': '/music/jazz-small.mp3',
            'Dry Gin': '/music/dry-gin-small.mp3',
            'Dirty Thinking': '/music/dirty-thinkin-small.mp3',
            'Walking Dead': '/music/walking-dead-small.mp3',
            'Deep Urban': '/music/deep-urban-small.mp3',
            // other tracks here
        },
        currentBackgroundMusic: null,
        soundEffectFiles: {
            move: '/sfx/move.wav',
            win: '/sfx/win.wav',
            click: '/sfx/click-cool-interface.wav',
            gameOver: '/sfx/retro-game-over.wav',
            notification: '/sfx/retro-game-notification.wav',
            playerSelect: '/sfx/player-select.wav',
            lose: '/sfx/lose.wav',
        },
    }),

    actions: {
        isMusicPlaying() {
            return this.musicIsPlaying;
        },

        playSound(effect, options = {}) {
            const file = this.soundEffectFiles[effect];
            if (!file || !settings.playSoundFx) {
                return;
            }
            const sound = new Howl({src: [file], volume: 1, ...options});
            sound.play();
        },

        playMusic(trackKey, options = {}) {
            if (this.musicIsPlaying || !settings.playMusic) {
                return
            }
            if (this.backgroundMusicFiles[trackKey]) {
                this.currentBackgroundMusic = trackKey;
            }
            this.backgroundMusic = new Howl({
                src: [this.backgroundMusicFiles[this.currentBackgroundMusic]],
                loop: true,
                volume: 0.3, // softer background volume
                ...options
            });
            this.backgroundMusic.play();
            this.musicIsPlaying = true;
        },

        stopMusic() {
            if (this.backgroundMusic) {
                this.backgroundMusic.stop();
                this.musicIsPlaying = false;
            }
        },
    },
})
