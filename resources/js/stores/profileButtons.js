import { defineStore } from 'pinia'

export const useProfileButtons = defineStore('profileButtons', {
    state: () => ({
        activeButton: 'Личные данные',
    }),
    actions: {
        setActiveButton(buttonName){
            this.activeButton = buttonName
        }
    }
})
