import {useAudioStore} from "../stores/audioStore.js";

export function useSfx() {
    const audio = useAudioStore();

    return {
        // expose store state directly
        musicIsPlaying: audio.musicIsPlaying,
        backgroundMusic: audio.backgroundMusic,

        // simple proxies to the store
        play: audio.playSfx,
        playBgMusic: audio.playBgMusic,
        stopBgMusic: audio.stopBgMusic,
        isBackgroundMusicPlaying: audio.isBackgroundMusicPlaying,
    };
}
