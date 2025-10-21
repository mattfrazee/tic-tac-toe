import {defineStore} from 'pinia'
import {Howl} from 'howler'
import {useSettingsStore} from "./settingsStore.js";
import musicFiles from '../data/musicFiles.json'
import soundFxFiles from '../data/soundFxFiles.json'

const settings = useSettingsStore();
export const useAudioStore = defineStore('audio', {
    state: () => ({
        soundFx: null,
        soundFxVolume: JSON.parse(localStorage.getItem('soundFxVolume')) ?? 0.5,
        soundFxIsPlaying: false,

        music: null,
        musicVolume: JSON.parse(localStorage.getItem('musicVolume')) ?? 0.5,
        currentMusic: JSON.parse(localStorage.getItem('currentMusic')) || Object.keys(musicFiles)[0],
        musicIsPlaying: false,
        musicIsPaused: false,

        musicFiles: musicFiles,
        soundFxFiles: soundFxFiles,
    }),

    getters: {
        isSoundFxPlaying: (state) => {
            return state.soundFxIsPlaying
                && Howler._howls.filter(s => s._src.indexOf('/sfx/') === 0 && s.playing()).length > 0
        },
        isMusicPlaying: (state) => {
            return state.musicIsPlaying
                && Howler._howls.filter(s => s._src.indexOf('/music/') === 0 && s.playing()).length > 0
        },
        isAudioPlaying: (state) => {
            return this.isSoundFxPlaying(state) || this.isMusicPlaying(state)
        }
    },

    actions: {
        resetState() {
            this.stopSoundFx();
            this.soundFx = null;
            this.soundFxVolume = 0.5;
            this.soundFxIsPlaying = false;

            this.stopMusic();
            this.music = null;
            this.musicVolume = 0.5;
            this.musicIsPlaying = false;
            this.musicIsPaused = false;
            this.currentMusic = Object.keys(musicFiles)[0];
        },

        playSound(effect, options = {}) {
            const file = this.soundFxFiles[effect];
            if (!file || !settings.playSoundFx) {
            // if (!file || !settings.playSoundFx || this.soundFxIsPlaying) {
            //     console.log('dont playSound')
                return;
            }
            this.soundFx = new Howl({
                src: [file],
                volume: this.soundFxVolume,
                onplay: () => this.soundFxIsPlaying = true,
                onend: () => this.soundFxIsPlaying = false,
                onstop: () => this.soundFxIsPlaying = false,
                onpause: () => this.soundFxIsPlaying = false,
                ...options
            });
            this.soundFx.play();
            // console.log('playSound')
        },

        stopSoundFx() {
            this.soundFxIsPlaying = false;
            if (this.soundFx) {
                this.soundFx.stop();
            }
            // console.log('stopSoundFx')
        },

        updateSoundFxVolume(level) {
            this.soundFxVolume = level;
            if (this.soundFx) {
                this.soundFx.volume(level);
            }
            // console.log('updateSoundFxVolume')
        },

        stopAudio() {
            this.stopSoundFx()
            this.stopMusic()
        },

        playMusic(trackKey, options = {}) {
            if (this.music && ! this.music.playing() && this.musicIsPaused) {
                this.music.play();
                return;
            }
            if (this.musicIsPlaying || !settings.playMusic) {
                return;
            }
            if (this.musicFiles[trackKey]) {
                this.currentMusic = trackKey;
            }
            this.music = new Howl({
                src: [this.musicFiles[this.currentMusic]],
                volume: this.musicVolume,
                onpause: () => {
                    this.musicIsPlaying = false
                    this.musicIsPaused = true
                },
                onstop: () => {
                    this.musicIsPlaying = false
                    this.musicIsPaused = false
                },
                onplay: () => {
                    this.musicIsPaused = false
                    this.musicIsPlaying = true
                },
                onend: () => {
                    this.musicIsPlaying = false
                    if (! settings.loopMusic) {
                        this.nextTrack();
                    }
                },
                ...options
            });
            this.music.play();
        },

        pauseMusic() {
            if (this.music) {
                this.music.pause();
            }
        },

        stopMusic() {
            this.musicIsPlaying = false;
            if (this.music) {
                this.music.stop();
            }
        },

        nextTrack() {
            const keys = Object.keys(this.musicFiles);
            const currentIndex = keys.indexOf(this.currentMusic);
            const nextIndex = (currentIndex + 1) % keys.length;
            this.stopMusic();
            this.playMusic(keys[nextIndex]);
        },

        previousTrack() {
            const keys = Object.keys(this.musicFiles);
            const currentIndex = keys.indexOf(this.currentMusic);
            const prevIndex = (currentIndex - 1 + keys.length) % keys.length;
            this.stopMusic();
            this.playMusic(keys[prevIndex]);
        },

        updateMusicVolume(level = 0.5) {
            this.musicVolume = level;
            if (this.music) {
                this.music.volume(level);
            }
        },
    },
})

useAudioStore().$subscribe((mutation, state) => {
    const keysToSave = ['soundFxVolume', 'musicVolume', 'currentMusic']
    keysToSave.forEach(key => {
        localStorage.setItem(key, JSON.stringify(state[key]))
    })
});
