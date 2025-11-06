import { defineStore } from 'pinia'
import {API} from "../utilities/api.js";

export const useRoomCodeStore = defineStore('roomCodes', {
    state: () => ({
        isOnline: false,
        isHosting: false,
        roomCode: null,
    }),

    actions: {
        resetState() {
            this.isOnline = false
            this.isHosting = false
            this.roomCode = null
            this.playerX = null
            this.playerO = null
        },
        async create() {
            try {
                const data = await API.roomCode.create()
                this.roomCode = data.data?.code
                return data
            } catch (e) {
                console.error('Failed to create room code:', e)
                return e
            }
        },
        async joinRoom(roomCode) {},
        async destroyExpiredRoomCodes() {
            try {
                return await API.roomCode.destroyExpiredRoomCodes()
            } catch (e) {
                throw new Error(`Failed to remove expired room codes: ${e}`)
            }
        },
    },
});
