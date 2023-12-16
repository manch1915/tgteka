import { defineStore } from "pinia";
import axios from "axios";

export const useNotificationStore = defineStore("notifications", {

    state: () => ({
        loading: false,
        notifications: {}
    }),
    actions: {
        async getNotifications(page = 1, order = 'all', sort = 'desc') {
            let url = route('notifications.get') + `?page=${page}&order=${order}&sort=${sort}`;
            this.loading = true
            await axios.get(url)
                .then(response => {
                    this.notifications = response.data
                    this.loading = false
                })
        }
    }
})
