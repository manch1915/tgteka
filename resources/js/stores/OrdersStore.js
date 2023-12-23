import { defineStore } from "pinia";
import axios from "axios";

export const useOrdersStore = defineStore("orders", {

    state: () => ({
        orders: {}
    }),
    actions: {
        async getOrders(page = 1) {
            let url = route('order.get') + `?page=${page}`;
            await axios.get(url)
                .then(response => {
                    this.orders = response.data
                })
        }
    }
})
