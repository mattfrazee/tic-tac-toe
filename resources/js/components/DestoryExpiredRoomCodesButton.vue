<script setup>
import {useRoomCodeStore} from "../stores/roomCodeStore.js";
import Modal from "./Modal.vue";
import {ref} from "vue";
import SoundFxEvent from "./SoundFxEvent.vue";

const emit = defineEmits(['destroyed', 'error'])
const roomCodes = useRoomCodeStore()
const title = ref(null)
const msg = ref(null)
const destroyExpiredRoomCodes = async () => {
    try {
        const {data} = await roomCodes.destroyExpiredRoomCodes()
        title.value = data.roomCodesRemoved
            ? 'Cleanup Successful'
            : 'Attention'
        msg.value = data.roomCodesRemoved
            ? `Successfully removed ${data.roomCodesRemoved} expired room code${data.roomCodesRemoved === 1 ? '' : 's'}.`
            : `No expired room codes available.`

        emit('destroyed', data)
    } catch (e) {
        title.value = 'Failed to remove expired room codes.'
        msg.value = e

        emit('error', {
            title: title.value,
            message: msg.value
        })
    }
}
const close = () => {
    title.value = null
    msg.value = null
}
</script>

<template>
    <div>
        <span @click="destroyExpiredRoomCodes">
            <slot>
                <SoundFxEvent file="click">
                    <button class="btn-small btn-secondary" type="button">
                        Remove Expired Room Codes
                    </button>
                </SoundFxEvent>
            </slot>
        </span>
        <Modal :message="msg"
               :title="title"
               :visible="!!msg"
               @close="close"/>
    </div>
</template>
