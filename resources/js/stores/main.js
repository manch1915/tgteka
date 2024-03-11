import { defineStore } from "pinia";
import axios from "axios";

export const useMainStore = defineStore("main", {
    state: () => ({
        /* User */
        userName: null,
        userEmail: null,
        userAvatar: null,
        userBalance: 0,
        /* Field focus with ctrl+k (to register only once) */
        isFieldFocusRegistered: false,

        /* Sample data (commonly used) */
        channels: [],
        chats: [],
        supportChats: [],
        history: [],
        topics: [],
        format: [
            {
                label: '1/24',
                value: 1
            },
            {
                label: '2/48',
                value: 2,
            },
            {
                label: '3/72',
                value: 3,
            },
            {
                label: '3/без удаления',
                value: 4,
            }
        ],
        count: [
            {
                label: '1',
                value: 1,
            },
            {
                label: '2',
                value: 2,
            },
            {
                label: '3',
                value: 3,
            },
            {
                label: '4',
                value: 4,
            }
        ]
    }),

    actions: {
        setUser(payload) {
            if (payload.name) {
                this.userName = payload.name;
            }
            if (payload.email) {
                this.userEmail = payload.email;
            }
            if (payload.avatar) {
                this.userAvatar = payload.avatar;
            }
        },
        setUserBalance(balance) {
            this.userBalance = balance;
        },
        fetchChannels() {
            axios.get(route('admin.api.channels.index'))
                .then(r => {
                    this.channels = r.data.channels
                    this.chats = r.data.chats
                })
        },
        subtractFromUserBalance(amount) {
            this.userBalance -= amount;
        },
        fetchSupportChats() {
            axios.get(route('admin.api.support.index'))
                .then(r => this.supportChats = r.data.data)
        },
        fetchTopics() {
            axios.get(route('admin.api.topics.index'))
                .then(r => this.topics = r.data)
        }
    },
});
