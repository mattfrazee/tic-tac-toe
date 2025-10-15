import {defineStore} from 'pinia'
import {Howl} from 'howler'
import {useSettingsStore} from "./settingsStore.js";

const settings = useSettingsStore();console.log('setting', localStorage.getItem('currentBackgroundMusic'))
export const useAudioStore = defineStore('audio', {
    state: () => ({
        backgroundMusic: null,
        musicIsPlaying: false,
        // ffmpeg -i public/music/XXX.mp3 -b:a 96k -ar 44100 -ac 2 public/music/XXX-small.mp3
        backgroundMusicFiles: {
            'Deep Urban': '/music/deep-urban-small.mp3',
            'Dirty Thinking': '/music/dirty-thinkin-small.mp3',
            'Dry Gin': '/music/dry-gin-small.mp3',
            'Jazz': '/music/jazz-small.mp3',
            'Sci-Fi Game': '/music/sci-fi-game-small.mp3',
            'Walking Dead': '/music/walking-dead-small.mp3',
            'Vampires in the City': '/music/vampires-in-the-city-small.mp3',
            'You Got Jazz': '/music/you-got-jazz-small.mp3',
            'Golden Gate Hip-Hop': '/music/golden-gate-hip-hop-small.mp3',
            'Games': '/music/games-music-small.mp3',
            'Fun Jazz': '/music/fun-jazz-small.mp3',
            'Cyberpunk City': '/music/cyberpunk-city-small.mp3',
            'Billy the Kid': '/music/billy-the-kid-small.mp3',
            'Almost Game Time': '/music/almost-game-time-small.mp3',
            'A Love Affair': '/music/a-love-affair-small.mp3',
            // other tracks here
        },
        currentBackgroundMusic: localStorage.getItem('currentBackgroundMusic') || 'Jazz',
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
            localStorage.setItem('currentBackgroundMusic', this.currentBackgroundMusic);
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
