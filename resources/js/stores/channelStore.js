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
            peerType: '',
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
            dau: [],
            wau: [],
            mau: [],
            online_count_day_time: [],
            online_count_night_time: [],
            messages_count_yesterday: [],
            messages_count_last_week: [],
            messages_count_last_month: [],
            messages_count_total: [],
        },
        mainFilter: {
            channel_creation_date: 0,
            male_percentage: 0,
            female_percentage: 0,
        }
    }),

    actions: {
        createUrl(page, additionalFilter) {
            const params = new URLSearchParams({
                page,
                sort: this.sort,
            });

            if(this.order !== '') {
                params.append('order', this.order);
            }

            if(this.searchData !== '') {
                params.append('search', this.searchData);
            }

            if(this.mainFilter.channel_creation_date > 0) {
                params.append('channel_creation_date', this.mainFilter.channel_creation_date);
            }

            if(this.mainFilter.male_percentage > 0) {
                params.append('male_percentage', this.mainFilter.male_percentage);
            }

            if(this.mainFilter.female_percentage > 0) {
                params.append('female_percentage', this.mainFilter.female_percentage);
            }

            if (additionalFilter) {
                Object.entries(additionalFilter).forEach(([key, value]) => {
                    if (key !== 'peerType' && value[0] !== undefined && value[1] !== undefined && value[0] !== '' && value[1] !== '') {
                        params.append(`${key}_min`, value[0]);
                        params.append(`${key}_max`, value[1]);
                    } else if (key === 'peerType' && value !== '') {
                        params.append('peerType', value);
                    }
                });
            }

            return route('catalog.channels.list') + `?${params.toString()}`;
        },
        async fetchChannels(page = 1, additionalFilter = null) {
            this.loading = true

            const url = this.createUrl(page, additionalFilter)

            const response = await axios.get(url);

            this.channels = response.data;
            this.loading = false;
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
                    return 'format_one_price';
                case 'CPМ':
                    return 'cpm';
                default:
                    return '';
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
                peerType: '',
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
                dau: [],
                wau: [],
                mau: [],
                online_count_day_time: [],
                online_count_night_time: [],
                messages_count_yesterday: [],
                messages_count_last_week: [],
                messages_count_last_month: [],
                messages_count_total: [],
            };

            this.mainFilter = {
                channel_creation_date: 0,
                male_percentage: 0,
                female_percentage: 0,
            }
        }
    },
});
