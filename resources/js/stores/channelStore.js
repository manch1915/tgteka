import { defineStore } from 'pinia'
import axios from 'axios';

export const useChannelStore = defineStore('channel',{
    state: () => ({
        channels: {
            data: []
        },
        searchData: '',
        sort: '',
        loading: false,
        activeButton: null
    }),

    actions: {
        async fetchChannels(page = 1) {
            this.loading = true
            // Get URL with all necessary parameters
            let url = route('catalog.channels.list') + `?page=${page}`;
            if (this.searchData.length > 0) {
                url += `&search=${this.searchData}`;
            }
            if (this.sort.length > 0) {
                url += `&order=${this.sort}`;
            }

            // Fetch data from the API
            const response = await axios.get(url);

            // Update state with the fetched channels
            this.channels = response.data;
            this.loading = false
        },

        convertSortTitleToFieldName(title) {
            switch (title) {
                case 'Рейтинг':
                    return 'rating';
                case 'ER':
                    return 'er';
                case 'Просмотры':
                    return 'views';
                case 'Подписчики':
                    return 'subscribers';
                case 'Цена':
                    return 'price';
                case 'CPM':
                    return 'cpm';
                default:
                    return 'rating';
            }
        },

        async sortChannels(title) {
            this.sort = this.convertSortTitleToFieldName(title)
            await this.fetchChannels()
        }
    },
});
