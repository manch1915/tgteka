import { defineStore } from 'pinia'
import axios from 'axios';

export const useChannelStore = defineStore('channel',{
    state: () => ({
        channels: {
            data: []
        },
        searchData: '',
        sort: 'desc',
        order: '',
        loading: false,
        activeButton: null,
        sortDirection: 'desc',
        additionalFilter: {
            peerType: 'channel',
            participants_count: [],
            adv_post_reach_24h: [],
            err_percent: [],
            er_percent: [],
            daily_reach: [],
            ci_index: [],
            mentions_count: [],
            forwards_count: [],
            mentioning_channels_count: [],
            posts_count: []
        },
    }),

    actions: {
        async fetchChannels(page = 1, additionalFilter = null) {
            this.loading = true
            // Get URL with all necessary parameters
            let url = route('catalog.channels.list') + `?page=${page}&sort=${this.sort}`;
            if (this.searchData.length > 0) {
                url += `&search=${this.searchData}`;
            }
            if (additionalFilter && additionalFilter.peerType) {
                url += `&peerType=${additionalFilter.peerType}`;
            }
            if (this.order.length > 0) {
                url += `&order=${this.order}`;
            }
            if (additionalFilter) {
                Object.keys(additionalFilter).forEach((key) => {
                    // Exclude peerType from the URL
                    if (key === 'peerType') {
                        return;
                    }

                    const min = additionalFilter[key][0];
                    const max = additionalFilter[key][1];
                    if (min !== undefined && max !== undefined && min !== '' && max !== '') {
                        url += `&${key}_min=${min}&${key}_max=${max}`;
                    }
                });
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
                    return 'er_percent';
                case 'Просмотры':
                    return 'avg_post_reach';
                case 'Подписчики':
                    return 'participants_count';
                case 'Цена':
                    return 'price';
                case 'CPM':
                    return 'cpm';
                default:
                    return 'rating';
            }
        },

        async sortChannels(title) {
            const field = this.convertSortTitleToFieldName(title);
            if (this.order === field) {
                // if the currently sorted field is clicked again, toggle ascend/descend
                this.sort = this.sort === 'asc' ? 'desc' : 'asc';
            } else {
                // if another field is clicked to sort by, reset sort direction to descending
                this.sortDirection = 'desc';
                this.order = field;
            }
            await this.fetchChannels(1, this.additionalFilter);
        },
        resetFilters() {
            this.additionalFilter = {
                peerType: 'channel',
                participants_count: [],
                adv_post_reach_24h: [],
                err_percent: [],
                er_percent: [],
                daily_reach: [],
                ci_index: [],
                mentions_count: [],
                forwards_count: [],
                mentioning_channels_count: [],
                posts_count: [],
            };
        }
    },
});
