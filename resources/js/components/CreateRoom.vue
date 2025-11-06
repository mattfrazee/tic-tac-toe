<script setup>
import {ref} from "vue";
import {useErrorStore} from "../stores/errorStore.js";
import {useRoomCodeStore} from "../stores/roomCodeStore.js";
import Modal from "./Modal.vue";

const emit = defineEmits(['created', 'error'])
const error = useErrorStore()
const roomCodes = useRoomCodeStore()
const roomCode = ref({});
const showConfirmModal = ref(false)
const createRoom = async () => {
    try {
        const {data} = await roomCodes.create()
        roomCode.value = data
        emit('created', roomCode.value)
        showConfirmModal.value = true
    } catch (e) {
        emit('error', e)
        error.show(e, 'Failed to create room')
    }
}

const shareRoom = async () => {
    const message = `Join my Tic Tac Toe room! Code: ${roomCode.value.code}`
    const shareData = {
        title: 'Tic Tac Toe Challenge',
        text: message,
        url: `${window.location.origin}?room=${roomCode.value.code}`,
    }

    if (navigator.share) {
        try {
            await navigator.share(shareData)
            console.log('Room shared successfully!')
            return
        } catch (err) {
            console.warn('Share cancelled or failed', err)
        }
    }

    try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(message)
        } else {
            // fallback using execCommand for older browsers / iOS PWAs
            const textarea = document.createElement('textarea')
            textarea.value = message
            textarea.style.position = 'fixed'
            textarea.style.opacity = '0'
            document.body.appendChild(textarea)
            textarea.select()
            document.execCommand('copy')
            document.body.removeChild(textarea)
        }
        error.show('Room info copied to clipboard!')
    } catch (err) {
        console.error('Clipboard copy failed:', err)
        error.show('Unable to copy room code. Please copy it manually.')
    }
}

const close = () => showConfirmModal.value = false
</script>

<template>
    <div>
        <span @click="createRoom">
            <slot>
                <button class="btn-small btn-secondary" type="button">
                    Create Room
                </button>
            </slot>
        </span>

        <Modal :message="error.message"
               :title="error.title ?? 'Error'"
               :visible="!!error.message"
               @close="error.close()"/>

        <Modal title="Room Code Created!"
               :visible="showConfirmModal"
               @close="close">
            <template #message>
                <p class="text-base text-gray-400">Your room code is:</p>
                <p class="text-4xl mb-4 text-white font-bold">{{ roomCode.code }}</p>
            </template>
            <template #actions>
                <div class="flex flex-col gap-6 py-6 items-center">
                    <button class="btn-primary w-3/4" type="button">
                        Start Game
                    </button>
                    <button class="btn-small btn-secondary" type="button" @click="shareRoom">
                        Share Code with Friends
                    </button>
                    <button class="btn-small btn-secondary" type="button" @click="close">
                        Cancel
                    </button>
                </div>
            </template>
        </Modal>
    </div>
</template>
