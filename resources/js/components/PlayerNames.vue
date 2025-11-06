<template>
    <form class="px-6 pb-10 pt-0 space-y-3 text-center h-screen flex flex-col items-center justify-center" @submit.prevent="submit">
        <div class="relative grid gap-2 mb-12 w-48 sm:w-60 mx-auto"
             :style="{ gridTemplateColumns: `repeat(${game.size}, minmax(0, 1fr))` }">
            <div class="bg-purple-500/50 rounded-lg aspect-square animate-pulse"
                 :style="{
                    animationDelay: `${Math.random() * 300}ms`,
                    animationDuration: `${Math.random() * 100 + 1000}ms`
                 }"
                 v-for="arrVal in Array(game.size * game.size).fill(null)">
                <div class="h-full flex w-full items-center justify-center text-4xl font-light opacity-50">
<!--                    {{Math.random() > 0.5 ? 'X' : 'O'}}-->
                </div>
            </div>
        </div>
        <h2 class="text-4xl font-bold mb-4">Player Names</h2>
        <label v-if="! game.isOnline || game.isHosting" class="relative block w-full" :class="{'invalid': isValid.x}">
            <input v-model="game.playerX"
                   class="form-input w-full"
                   placeholder="Player 1 Name (X's)"
                   @blur="isValid.x = ! game.playerX"/>
            <span class="absolute w-20 left-2 top-2 text-sm p-2 rounded-lg bg-purple-200 font-bold text-purple-400">
                Player X
            </span>
        </label>
        <label v-if="! game.isOnline || (game.isOnline && ! game.isHosting)" class="relative block w-full" :class="{'invalid': isValid.o}">
            <input v-model="game.playerO"
                   class="form-input"
                   placeholder="Player 2 Name (O's)"
                   @blur="isValid.o = ! game.playerO"/>
            <span class="absolute w-20 left-2 top-2 text-sm p-2 rounded-lg bg-purple-200 font-bold text-purple-400">
                Player O
            </span>
        </label>

        <div v-if="(game.isHosting && game.isOnline) || ! game.isOnline" class="self-start flex w-72">
            <label class="relative block w-full">
                <input v-model="game.size" type="tel" class="form-input rounded-r-none pr-3 text-center select-none" readonly/>
                <span class="absolute w-20 left-2 top-2 text-sm p-2 rounded-lg bg-purple-200 font-bold text-purple-400">
                Grid Size
            </span>
            </label>
            <SoundFxEvent file="click">
                <button type="button" class="btn-secondary btn-small flex-none w-14 p-2 rounded-none border-x-0 active:scale-100" @click="game.size = game.size > 3 ? game.size - 1 : game.size">
                    &minus;
                </button>
            </SoundFxEvent>
            <SoundFxEvent file="click">
                <button type="button" class="btn-secondary btn-small flex-none w-14 p-2 rounded-l-none rounded-r-xl active:scale-100" @click="game.size < 10 ? game.size++ : game.size">
                    &plus;
                </button>
            </SoundFxEvent>
        </div>

        <label v-if="game.isOnline && ! game.isHosting" class="relative block w-full">
            <input v-model="roomCode"
                   class="form-input w-full"
                   @input="() => roomCode = roomCode.toUpperCase()"
                   placeholder="Join Online Game"/>
            <span class="absolute w-20 left-2 top-2 text-sm p-2 rounded-lg bg-purple-200 font-bold text-purple-400">
                Room #
            </span>
        </label>

        <SoundFxEvent file="switch" v-if="! game.isOnline">
            <Checkbox v-model="game.vsComputer"
                      class="w-full"
                      label="Play against Buddy-Bot"
                      @change="game.playerO = game.vsComputer ? 'Buddy-Bot' : ''"/>
        </SoundFxEvent>

        <DifficultyLevel v-if="game.vsComputer" />

        <SoundFxEvent file="switch"  v-if="! game.vsComputer">
            <Checkbox v-model="game.isOnline"
                      class="w-full"
                      label="Play Online"/>
        </SoundFxEvent>

        <SoundFxEvent file="switch" v-if="game.isOnline && ! game.vsComputer">
            <Checkbox v-model="game.isHosting"
                      class="w-full"
                      label="Host Game"/>
        </SoundFxEvent>

        <div class="flex flex-col gap-6 w-full sm:w-1/2 my-10">

            <SoundFxEvent file="click" v-if="! game.isHosting && game.isOnline">
                <button type="button" class="btn-primary w-2/3 mx-auto btn-glow" @click="joinRoom(roomCode)" adisabled="! roomCode || roomCode.length < 4">
                    Join Game
                </button>
            </SoundFxEvent>

            <SoundFxEvent file="click" v-else-if="! game.isHosting">
                <button type="submit" class="btn-primary w-2/3 mx-auto btn-glow" :disabled="! game.playerX">
                    Start Game
                </button>
            </SoundFxEvent>

            <SoundFxEvent file="click" v-else>
                <button type="button" class="btn-primary w-2/3 mx-auto btn-glow" :disabled="! game.playerX" @click="createRoom">
                    Create Game
                </button>
            </SoundFxEvent>

            <Modal :visible="!!game.roomCode" @close="game.roomCode = null">
                <template #title>
                    <div class="text-xl font-bold mb-3 text-cyan-300 line-clamp-1">
                        Game Room Code
                    </div>
                </template>
                <template #message>
                    <p class="text-4xl font-mono tracking-widest">
                        {{ game.roomCode }}
                    </p>
                    <p class="mt-3 mb-6 italic text-gray-400">
                        Waiting for player to join...
                    </p>
                    <p class="mt-3 mb-8 font-bold text-green-500">
                        Player NAME has joined the game!
                    </p>
                </template>
                <template #actions>
                    <SoundFxEvent file="click">
                        <button type="submit" class="btn-secondary btn-small w-2/3 mx-auto my-4">
                            Let's Go!
                        </button>
                    </SoundFxEvent>
                </template>
            </Modal>

            <Modal :visible="!!error.message" @close="error.close" :title="error.title ?? 'Error'" :message="error.message" />

            <SoundFxEvent file="click">
                <RouterLink class="btn-secondary btn-small w-1/3 mx-auto" to="/">
                    Back
                </RouterLink>
            </SoundFxEvent>
        </div>
    </form>
</template>
<script setup>
import {onMounted, ref} from 'vue'
import SoundFxEvent from "./SoundFxEvent.vue";
import {useAudioStore} from "../stores/audioStore.js";
import Checkbox from "./Checkbox.vue";
import DifficultyLevel from "./DifficultyLevel.vue";
import {useGameStore} from "../stores/gamePlayStore.js";
import {API} from "../utilities/api.js";
import Modal from "./Modal.vue";
import {useErrorStore} from "../stores/errorStore.js";

const audio = useAudioStore();
const game = useGameStore()
const error = useErrorStore()
const isValid = ref({})
const roomCode = ref(game.roomCode)

const emit = defineEmits(['submit', 'created', 'create-error', 'joined', 'join-error'])

function submit() {
    // isValid.value = {
    //     x: !game.playerX,
    //     o: !game.playerO,
    // }
    // if (!game.playerX || !game.playerO) {
    //     audio.playSound('wrong')
    //     setTimeout(() => isValid.value = {},5000)
    //     return
    // }
    emit('submit', {
        x: game.playerX,
        o: game.playerO,
        gridSize: game.size,
        vsComputerMode: game.vsComputer,
    })
}

const joinRoom = async (code) => {
    const data = await game.joinRoom(code)
    if (data.error){
        console.log(data)
        error.show(data.message, data.title)
        emit('join-error', data, code)
    }
    emit('joined', data)
}

const createRoom = async () => {
    let roomCodeId = null;
    try {
        const {data} = await API.roomCode.create()

        if (data) {
            console.log(data)
            game.roomCode = data.code
            roomCodeId = data.room_code_id
            emit('created', data)
        }
    } catch (e) {
        console.error('Failed to create room:', e)
    }
    try {
        const {data} = await API.game.store({
            player_x_name: game.playerX,
            player_o_name: null,
            grid_size: game.size,
            vs_computer: false,
            is_online: true,
            room_code_id: roomCodeId,
            first_player: game.randomPlayerMark(),
        })

        if (data) {
            console.log(data)
        }
    } catch (e) {
        console.error('Failed to create room:', e)
    }
    emit('submit', {
        x: game.playerX,
        o: null,
        gridSize: game.size,
        vsComputerMode: false,
        roomCode: roomCodeId,
    })
}

onMounted(async () => {
    try {
        const {data} = await axios.get('/api/games/last-human-game');

        if (data) {
            game.playerX = data.player_x_name;
            game.playerO = data.player_o_name;
            game.size = data.board_size;
            game.vsComputer = false;
        }
    } catch (e) {
        error.show(e, 'Failed to load last human game')
        console.error('Failed to load last human game:', e)
    }
})
</script>
<style scoped>
@reference "tailwindcss";

.form-input {
    @apply pl-24;
}

.invalid input {
    @apply ring-red-700 ring-4 bg-red-100 text-red-950 placeholder:text-red-400
}
</style>
