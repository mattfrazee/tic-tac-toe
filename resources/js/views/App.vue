<template>
    <router-view/>
</template>

<script setup>
import {onMounted, onUnmounted} from 'vue'
import {usePreventSwipeBack} from "../composables/usePreventSwipeBack.js";

const {enableSwipeBack, disableSwipeBack} = usePreventSwipeBack()

onMounted(() => enableSwipeBack())
onUnmounted(() => disableSwipeBack())
</script>
