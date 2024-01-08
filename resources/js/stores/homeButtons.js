import { defineStore } from 'pinia'

export const useHomeButtons = defineStore('homeButtons', {
    state: () => ({
        activeButton: 'Заказчикам',
        routes: {
            'Заказчикам': '/',
            'Владельцу канала': '/owners',
        }
    }),
    actions: {
        setActiveButton(buttonName){
            this.activeButton = buttonName
        },
        setActiveButtonByUrl(url) { // Add this
            for (const buttonName in this.routes) {
                if (this.routes[buttonName] === url) {
                    this.activeButton = buttonName
                    break;
                }
            }
        }
    }
})
