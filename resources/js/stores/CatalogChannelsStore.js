import { defineStore } from "pinia";

export const useCatalogChannelsStore = defineStore("catalogChannels", {
    state: () => ({
        loading: false,
    }),
});
