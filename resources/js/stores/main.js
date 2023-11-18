import { defineStore } from "pinia";
import axios from "axios";

export const useMainStore = defineStore("main", {
  state: () => ({
    /* User */
    userName: null,
    userEmail: null,
    userAvatar: null,

    /* Field focus with ctrl+k (to register only once) */
    isFieldFocusRegistered: false,

    /* Sample data (commonly used) */
    channels: [],
    supportChats: [],
    history: [],
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

    fetchChannels(){
        axios.get(route('admin.api.channels.index'))
            .then(r => this.channels = r.data.channels)
    },
    fetchSupportChats(){
        axios.get(route('admin.api.support.index'))
            .then(r => this.supportChats = r.data)
    }
  },
});
