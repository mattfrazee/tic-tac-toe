export const API = {
    game: {
        store(gameData) {
            return axios.post('/api/games', gameData)
        },
        clearAllScores() {
            return axios.delete('/api/games/clear');
        },
        join(roomCode, playerO) {
            return axios.post(`/api/games/join`, {
                playerO: playerO,
                roomCode: roomCode,
            });
        },
    },
    player: {
        index() {},
        show() {},
        store() {},
        update() {},
        destroy() {},
    },
    playerStat: {
        index() {},
        show() {},
        store() {},
        update() {},
        destroy() {},
    },
    move: {},
    roomCode: {
        store() {},
        update() {},
        index() {
            return axios.get('/api/rooms');
        },
        create() {
            return axios.post('/api/rooms');
        },
        show(roomCodeId) {
            return axios.get(`/api/rooms/${roomCodeId}`);
        },
        destroy(roomCodeId) {
            return axios.delete(`/api/rooms/${roomCodeId}`);
        },
        destroyExpiredRoomCodes() {
            return axios.delete(`/api/rooms/expired`);
        },
    },
}
