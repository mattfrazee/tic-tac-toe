import {Howl} from 'howler'
import {ref} from "vue";

const musicIsPlaying = ref(false);
const bgMusic = ref(null);
const muted = ref(false);
export function useSfx() {
    // Sound Fx
    const move = new Howl({src: ['/sfx/move.wav'], volume: 1});
    const win = new Howl({src: ['/sfx/win.wav'], volume: 1});
    const click = new Howl({src: ['/sfx/click-cool-interface.wav'], volume: 1});
    const gameOver = new Howl({src: ['/sfx/retro-game-over.wav'], volume: 1});
    const notification = new Howl({src: ['/sfx/retro-game-notification.wav'], volume: 1});
    const playerSelect = new Howl({src: ['/sfx/player-select.wav'], volume: 1});
    const lose = new Howl({src: ['/sfx/lose.wav'], volume: 1});
    // Background Music
    const bgJazz = new Howl({src: ['/sfx/jazz-bg.mp3'], loop: true, volume: 1});
    return {
        move,
        win,
        click,
        gameOver,
        notification,
        playerSelect,
        lose,
        bgJazz,
        muted,
        musicIsPlaying,
        toggleMute() {
            muted.value = !muted.value;
            console.log('muted')
        },
        play(sfx) {
            if (sfx && ! muted.value) {
                sfx.play();
            }
        },
        playBgMusic(music) {
            if (music && ! muted.value) {
                // music.stop();
                // music.unload();
                // music.play();
                bgMusic.value = music;
                bgMusic.value.stop();
                bgMusic.value.unload();
                bgMusic.value.play();
                musicIsPlaying.value = true;
            }
        },
    };
}
