import { defineStore } from "pinia";

export const useCartStore = defineStore("cart", {

    state: () => ({
        cartUpdate: 0,
    }),
})
