import { Howl } from 'howler'

export function useSfx() {
    const move = new Howl({ src: ['/sfx/move.mp3'], volume: 0.4 })
    const win = new Howl({ src: ['/sfx/win.mp3'], volume: 0.5 })
    const click = new Howl({ src: ['/sfx/click.mp3'], volume: 0.2 })
    return { move, win, click }
}
