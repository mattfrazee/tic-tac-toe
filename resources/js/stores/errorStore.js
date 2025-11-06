import { defineStore } from 'pinia'

export const useErrorStore = defineStore('error', {
    state: () => ({
        title: null,
        message: null,
    }),

    actions: {
        close() {
            this.title = null;
            this.message = null;
        },

        show(errorMessage, errorTitle) {
            this.title = errorTitle ?? null;
            this.message = errorMessage;
        },
    },
});
