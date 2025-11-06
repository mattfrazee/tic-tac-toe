<template>
    <div class="p-2 w-full">
        <div class="relative mx-auto aspect-square w-full">
            <div :style="gridStyle" class="grid p-2">
                <button class="aspect-square rounded-2xl flex items-center justify-center "
                        :class="{
                            'active:scale-95': ! cells[i],
                            'bg-indigo-800 text-white': winningLine && winningLine.includes(i),
                            'bg-zinc-800': !winningLine || ! winningLine.includes(i),
                        }"
                        v-for="(c, i) in totalCells"
                        :key="i"
                        @click="$emit('play', i)">
                    <transition appear name="mark">
                        <span v-if="cells[i]"
                              :class="{
                                  'text-fuchsia-400 drop-shadow-fuchsia-400': cells[i] === 'X',
                                  'text-cyan-400 drop-shadow-cyan-400': cells[i] !== 'X'
                              }"
                              :style="{ fontSize: markSize }"
                              class="font-black drop-shadow-[0_0_20px] select-none leading-0">
                            {{ cells[i] }}
                        </span>
                    </transition>
                </button>
            </div>

            <!-- Animated winning line overlay -->
            <svg v-if="showWinningLine && winningLine && winningLine.length >= 2" class="absolute inset-0 w-full h-full pointer-events-none text-pink-400">
                <line class="stroke-[8] drop-shadow-[0_0_10px_#0ff]" pathLength="100" v-bind="lineCoords">
                    <animate attributeName="stroke-dashoffset" dur="0.4s" fill="freeze" from="100" to="0"/>
                </line>
            </svg>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue'

const props = defineProps({
    cells: {
        type: Array,
        required: true,
    },
    winningLine: {
        type: Array, // array of cell indexes that form a winning sequence
        default: () => [],
    },
    showWinningLine: Boolean,
    turn: String,
    size: {
        type: Number,
        default: 3,
        validator: (n) => Number.isInteger(n) && n >= 2 && n <= 10,
    },
})

const totalCells = computed(() => props.size * props.size)

// Dynamic grid columns and gap that scales with size
const gridStyle = computed(() => {
    const s = props.size
    const gap = s <= 3 ? '0.5rem' : s <= 5 ? '0.375rem' : '0.25rem'
    return {
        gridTemplateColumns: `repeat(${s}, minmax(0, 1fr))`,
        gap,
    }
})

// Scale mark size roughly with board size (keeps similar visual weight)
const markSize = computed(() => {
    const s = props.size
    return `calc(${60 / s}vmin)`
})

// Compute line coordinates (x1,y1,x2,y2) from the winning cell indexes
const lineCoords = computed(() => {
    if (!props.winningLine || props.winningLine.length < 2) return null
    const idxs = [...props.winningLine]
    const s = props.size

    const centerOf = (index) => {
        const row = Math.floor(index / s)
        const col = index % s
        const x = ((col + 0.5) / s) * 100
        const y = ((row + 0.5) / s) * 100
        return {x, y}
    }

    idxs.sort((a, b) => a - b)
    const start = centerOf(idxs[0])
    const end = centerOf(idxs[idxs.length - 1])

    return {
        x1: `${start.x}%`,
        y1: `${start.y}%`,
        x2: `${end.x}%`,
        y2: `${end.y}%`,
    }
})
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
    stroke: currentColor;
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
}
</style>
