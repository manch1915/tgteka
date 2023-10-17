import { defineStore } from 'pinia'

export const useModalStore = defineStore('modalStore', {
    state: () => ({
        modalShouldOpen: null
    }),
    actions: {
        setModalToOpen(modalName) {
            this.modalShouldOpen = modalName;
        },
        closeCurrentModal() {
            this.modalShouldOpen = null;
        }
    }
})
