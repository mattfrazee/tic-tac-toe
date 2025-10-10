import {Howl} from 'howler'

export function useSfx() {
    const move = new Howl({src: ['/sfx/move.wav'], volume: 1});
    const win = new Howl({src: ['/sfx/win.wav'], volume: 1});
    const click = new Howl({src: ['/sfx/click-cool-interface.wav'], volume: 1});
    const gameOver = new Howl({src: ['/sfx/retro-game-over.wav'], volume: 1});
    const notification = new Howl({src: ['/sfx/retro-game-notification.wav'], volume: 1});
    const playerSelect = new Howl({src: ['/sfx/player-select.wav'], volume: 1});
    const lose = new Howl({src: ['/sfx/lose.wav'], volume: 1});
    return {
        move,
        win,
        click,
        gameOver,
        notification,
        playerSelect,
        lose,
    };
}
