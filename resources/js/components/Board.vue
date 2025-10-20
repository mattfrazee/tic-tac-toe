<template>
    <div class="relative mx-auto aspect-square w-full max-w-xs">
        <div class="grid grid-cols-3 gap-2 p-2">
            <button v-for="(c,i) in cells" :key="i"
                    class="aspect-square rounded-2xl bg-zinc-800 flex items-center justify-center active:scale-95"
                    @click="$emit('play', i)">
                <transition appear name="mark">
                    <span v-if="c" class="text-5xl font-black drop-shadow-[0_0_20px]" :class="{
                        'text-fuchsia-400 drop-shadow-fuchsia-400': c === 'X',
                        'text-cyan-400 drop-shadow-cyan-400': c !== 'X'
                    }">{{ c }}</span>
                </transition>
            </button>
        </div>
<!--        <div v-if="winningLine && winningLine[0] === 0 && winningLine[1] === 4 && winningLine[2] === 8" class="absolute w-full h-2 bg-red-500 rotate-45 origin-top-left top-11 left-12 rounded-full"></div>-->
<!--        <div v-if="winningLine && winningLine[0] === 0 && winningLine[1] === 4 && winningLine[2] === 8" class="absolute w-full h-2 bg-red-500 -rotate-45 origin-top-right top-11 right-12 rounded-full"></div>-->
<!--        <div v-if="winningLine && winningLine[0] === 0 && winningLine[1] === 4 && winningLine[2] === 8" class="absolute w-10/12 h-2 bg-red-500 rotate-90 bottom-1/2 left-0 rounded-full"></div>-->
<!--        <svg v-if="winningLine" class="absolute inset-0 w-full h-full pointer-events-none">-->
<!--            <line-->
<!--                v-if="winningLine"-->
<!--                v-bind="getLineCoords(winningLine)"-->
<!--                class="stroke-[8] stroke-cyan-400 drop-shadow-[0_0_10px_#0ff]"-->
<!--            >-->
<!--                <animate attributeName="stroke-dashoffset" from="100" to="0" dur="0.4s" fill="freeze" />-->
<!--            </line>-->
<!--        </svg>-->
    </div>
</template>

<script setup>
defineProps({
    cells: Array,
    winningLine: Array,
    turn: String
});

function getLineCoords(type) {
    const lines = {
        row0: { x1: '5%', y1: '16.5%', x2: '95%', y2: '16.5%' },
        row1: { x1: '5%', y1: '50%',  x2: '95%', y2: '50%' },
        row2: { x1: '5%', y1: '83.5%', x2: '95%', y2: '83.5%' },
        col0: { x1: '16.5%', y1: '5%',  x2: '16.5%', y2: '95%' },
        col1: { x1: '50%', y1: '5%',   x2: '50%', y2: '95%' },
        col2: { x1: '83.5%', y1: '5%',  x2: '83.5%', y2: '95%' },
        diag1: { x1: '5%', y1: '5%',   x2: '95%', y2: '95%' },
        diag2: { x1: '95%', y1: '5%',  x2: '5%',  y2: '95%' },
    };
    return lines[type];
}
</script>

<style>
.mark-enter-from {
    transform: scale(0.6);
    opacity: 0;
}

.mark-enter-to {
    transform: scale(1);
    opacity: 1;
}

.mark-enter-active {
    transition: all .18s ease-out;
}

line {
    stroke-linecap: round;
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
    transition: stroke-dashoffset 0.4s ease-out;
}
</style>
