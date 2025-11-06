import { defineStore } from 'pinia'
import {DifficultyLevel} from "../enums/difficultyLevel.js";
import {PlayerMark} from "../enums/playerMark.js";
import {SoundFxType} from "../enums/soundFxType.js";
import {nextTick} from "vue";
import {useAudioStore} from "./audioStore.js";
import {API} from "../utilities/api.js";
import {useErrorStore} from "./errorStore.js";

const audio = useAudioStore();
const error = useErrorStore()
export const useGameStore = defineStore('game', {
    state: () => {
        const size = 3;

        return {
            size: size,
            cells: Array(size * size).fill(null),
            turn: Math.random() < 0.5 ? PlayerMark.X : PlayerMark.O,
            winner: null,
            winningLine: null,
            playerX: null,
            playerO: null,
            score: {
                X: 0,
                O: 0
            },
            vsComputer: false,
            isMultiPlayer: true,
            isOnline: false,
            isHosting: false,
            roomCode: null,
            difficulty: DifficultyLevel.MEDIUM,
        }
    },

    actions: {
        resetGame() {
            this.size = size
            this.cells = Array(size * size).fill(null)
            this.turn = Math.random() < 0.5 ? PlayerMark.X : PlayerMark.O
            this.winner = null
            this.winningLine = null
            this.playerX = null
            this.playerO = null
            this.score = {
                X: 0,
                O: 0
            }
            this.vsComputer = false
            this.isMultiPlayer = true
            this.isOnline = false
            this.isHosting = false
            this.roomCode = null
            this.roomCodeId = null
        },
        aiDelay() {
            switch (this.difficulty) {
                case DifficultyLevel.MEDIUM:
                    return 500
                case DifficultyLevel.HARD:
                    return 250
                default:
                    return 800
            }
        },

        // Simple Moves
        _aiMove() {
            if (this.canSkipAiMove()) {
                return;
            }

            setTimeout(() => {
                const available = this.cells
                    .map((v, i) => (v ? null : i))
                    .filter(i => i !== null);
                let moveIndex;
                if (Math.random() < (1 - this.mistakeChance())) {
                    moveIndex = this.findWinningMove(PlayerMark.O)
                        || this.findBlockingMove(PlayerMark.X)
                        || this.randomPick(available);
                } else {
                    console.log('random')
                    moveIndex = this.randomPick(available);
                }

                if (moveIndex !== undefined) {
                    this.play(moveIndex);
                }
            }, this.aiDelay());
        },

        canSkipAiMove() {
            return !this.vsComputer || this.winner || this.turn !== PlayerMark.O
        },

        aiMove() {
            if (this.canSkipAiMove()) {
                return;
            }

            setTimeout(() => {
                const available = this.cells
                    .map((v, i) => (v ? null : i))
                    .filter(i => i !== null);

                let moveIndex;

                if (Math.random() < this.mistakeChance()) {
                    // console.log('random move')
                    moveIndex = this.randomPick(available);
                } else {
                    moveIndex =
                        this.findWinningMove(PlayerMark.O) ||
                        this.findBlockingMove(PlayerMark.X) ||
                        this.takeCenter() ||
                        this.takeCorner(available) ||
                        this.randomPick(available);
                }

                if (moveIndex !== undefined) {
                    this.play(moveIndex);
                }
            }, this.aiDelay());
        },

        takeCenter() {
            const center = Math.floor(this.size * this.size / 2);
            return this.cells[center] === null ? center : null;
        },

        takeCorner(available) {
            const size = this.size;
            const corners = [
                0,
                size - 1,
                size * (size - 1),
                size * size - 1
            ];
            const valid = corners.filter(i => available.includes(i));

            return this.randomPick(valid);
        },

        async play(cellIndex) {
            if (this.cells[cellIndex]) {
                audio.playSound(SoundFxType.WRONG);
                return;
            }
            if (this.winner) return;

            this.cells[cellIndex] = this.turn;
            audio.playSound(SoundFxType.MOVE);

            await nextTick();
            this.check();

            if (! this.winner) {
                this.turn = this.turn === PlayerMark.X
                    ? PlayerMark.O
                    : PlayerMark.X;
                this.aiMove();
            }
        },

        check() {
            const v = this.cells;
            const total = this.size * this.size;
            //const s = this.size;

            // normalize length if somehow mismatched
            if (v.length !== total) {
                this.cells = Array.from({ length: total }, (_, i) => v[i] ?? null);
            }

            // dynamically compute lines fresh
            const winLines = this.generateLines()

            // check for win
            for (const line of winLines) {
                const first = v[line[0]];
                if (first && line.every(idx => v[idx] === first)) {
                    this.winner = first;
                    this.winningLine = line;
                    this.score[first]++;
                    this.save();
                    return;
                }
            }

            // check draw only after confirming no winner
            const filledCount = v.filter(Boolean).length;
            if (filledCount === total) {
                this.winner = PlayerMark.DRAW;
                this.save();
            }
        },

        mistakeChance() {
            switch (this.difficulty) {
                case DifficultyLevel.EASY:
                    return 0.7
                case DifficultyLevel.MEDIUM:
                    return 0.3
                case DifficultyLevel.HARD:
                    return 0.1
                default:
                    return 0.0
            }
        },

        randomPick(arr) {
            return arr[Math.floor(Math.random() * arr.length)];
        },

        randomPlayerMark() {
            return Math.random() < 0.5 ? PlayerMark.X : PlayerMark.O
        },

        findWinningMove(mark) {
            for (const line of this.generateLines()) {
                const marks = line.map(i => this.cells[i]);
                if (marks.filter(Boolean).length === this.size - 1 && marks.includes(null)) {
                    if (marks.filter(v => v === mark).length === this.size - 1) {
                        return line[marks.indexOf(null)];
                    }
                }
            }
            return null;
        },

        findBlockingMove(enemyMark) {
            // return this.findWinningMove(enemyMark);

            // Check for the opponent’s threats
            for (const line of this.generateLines()) {
                const marks = line.map(i => this.cells[i]);
                const enemyCount = marks.filter(v => v === enemyMark).length;
                const emptyCount = marks.filter(v => !v).length;

                // If the opponent is about to win, block that line
                if (enemyCount === this.size - 1 && emptyCount === 1) {
                    return line[marks.indexOf(null)];
                }
            }
            return null;
        },

        generateLines() {
            const s = this.size;
            const lines = [];
            for (let r = 0; r < s; r++) lines.push(Array.from({ length: s }, (_, i) => r * s + i));
            for (let c = 0; c < s; c++) lines.push(Array.from({ length: s }, (_, i) => i * s + c));
            lines.push(Array.from({ length: s }, (_, i) => i * (s + 1)));
            lines.push(Array.from({ length: s }, (_, i) => (i + 1) * (s - 1)));
            return lines;
        },

        // Weighted heuristic
        __aiMove() {
            if (!this.vsComputer || this.winner || this.turn !== PlayerMark.O) return;

            setTimeout(() => {
                const available = this.cells
                    .map((v, i) => (v ? null : i))
                    .filter(i => i !== null);

                const moveScores = available.map(i => {
                    let score = 0;

                    // Simulate this move
                    this.cells[i] = PlayerMark.O;
                    const wouldWin = this.checkHypotheticalWin(PlayerMark.O);
                    this.cells[i] = null;

                    // Scoring rules
                    if (wouldWin) score += 100; // Win now
                    else if (this.wouldBlock(i, PlayerMark.X)) score += 80; // Block player
                    else if (i === Math.floor(this.size * this.size / 2)) score += 50; // Center
                    else if (this.isCorner(i)) score += 30; // Corner
                    else score += 10; // Random preference

                    // Add small random factor to make it less robotic
                    score += Math.random() * 5;

                    return { i, score };
                });

                // Pick the move with the highest score
                moveScores.sort((a, b) => b.score - a.score);
                const best = moveScores[0];

                if (best) this.play(best.i);
            }, this.aiDelay());
        },

        checkHypotheticalWin(mark) {
            for (const line of this.generateLines()) {
                const marks = line.map(i => this.cells[i]);
                if (marks.filter(v => v === mark).length === this.size - 1 &&
                    marks.includes(null)) {
                    return true;
                }
            }
            return false;
        },

        wouldBlock(index, enemyMark) {
            this.cells[index] = enemyMark;
            const blocked = this.checkHypotheticalWin(enemyMark);
            this.cells[index] = null;
            return blocked;
        },

        isCorner(i) {
            const size = this.size;
            const corners = [
                0,
                size - 1,
                size * (size - 1),
                size * size - 1
            ];
            return corners.includes(i);
        },

        async save() {
            try {
                const gameData = {
                    player_x_name: this.playerX,
                    player_o_name: this.playerO,
                    first_player: this.turn,
                    winner: this.winner,
                    board_size: this.size,
                    vs_computer: this.vsComputer,
                    room_code_id: this.roomCode,
                    moves: this.cells.map((mark, idx) => ({
                        mark,
                        row: Math.floor(idx / this.size),
                        col: idx % this.size,
                        is_computer: (this.vsComputer && mark === PlayerMark.O),
                    })).filter(m => m.mark)
                }

                await API.game.store(gameData);
            } catch (e) {
                console.error('Failed to save game', e)
            }
        },

        async createRoom() {
            try {
                const {data} = await API.roomCode.create()

                if (data) {
                    console.log(data)
                    this.roomCode = data.code
                    this.roomCodeId = data.room_code_id
                    return data
                }
            } catch (e) {
                console.error('Failed to create room:', e)
                error.show(e, 'Failed to create room')
            }
        },

        async joinRoom(roomCode) {
            try {
                if (! roomCode) {
                    throw new Error('You must have a room code.')
                }
                else if (! this.isOnline) {
                    throw new Error('You can only join rooms for online games.')
                }
                else if ( ! this.playerO) {
                    throw new Error('You must have a player name.')
                }

                const {data} = await API.game.join(roomCode, this.playerO)

                if (data.error) {
                    throw new Error(data.error)
                } else if (data) {
                    console.log('room-joined', data)
                    this.roomCode = data.code
                    return data
                }
            } catch (e) {
                this.roomCode = null
                // error.show(e.message, 'Failed to join room')
                console.error('Failed to join room:', e.message)
                return {
                    error: true,
                    message: e.message,
                    title: 'Failed to join room',
                }
            }
            return null
        },
    },
});
