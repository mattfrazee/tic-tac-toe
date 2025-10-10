<template>
    <div class="relative mx-auto aspect-square w-full max-w-xs">
        <div class="grid grid-cols-3 gap-2 p-2">
            <button v-for="(c,i) in cells" :key="i"
                    class="aspect-square rounded-2xl bg-zinc-800 flex items-center justify-center active:scale-95"
                    @click="$emit('play', i)">
                <transition appear name="mark">
                    <!--                  <span v-if="c" :class="c==='X'?'text-fuchsia-400':'text-cyan-400'" class="text-5xl font-black">-->
                    <!--                    {{ c }}-->
                    <!--                  </span>-->
                    <span v-if="c" :class="[
                            'text-5xl font-black drop-shadow-[0_0_8px_var(--color)]',
                            c==='X' ? 'text-fuchsia-400' : 'text-cyan-400'
                        ]"
                          :style="{ '--color': c==='X' ? '#f0f' : '#0ff' }">{{ c }}</span>
                </transition>
            </button>
        </div>
        <svg v-if="winningLine" class="absolute inset-0 w-full h-full pointer-events-none">
            <!-- draw line across winning path in CSS-space; TODO: map line to svg coords -->
        </svg>
    </div>
</template>

<script setup>
defineProps({
    cells: Array,
    winningLine: Array,
    turn: String
});
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
</style>
