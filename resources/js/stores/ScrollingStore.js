import { defineStore } from 'pinia';

export const useScrollingStore = defineStore("scrolling",{

    state: () => ({
        missionBlockOffset: 0,
    }),
});
