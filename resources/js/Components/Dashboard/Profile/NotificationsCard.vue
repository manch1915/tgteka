<script setup>
import {Link} from "@inertiajs/vue3"
import { useMessage } from 'naive-ui'

const props = defineProps({
    notification: Object
})

const generateRoute = (routeName, params) => {
    // Use your routing library to generate the route with params
    return route(routeName, params );
};

const message = useMessage();

const postRoute = async (route) => {
    try {
        await axios.put(route)

        message.success('Успешно!')

        props.notification.actions = [];
    } catch (error) {
        message.error(error.response.data.error)
        props.notification.actions = [];
    }
}
</script>

<template>
    <div class="notification__card-wrapper">
        <div class="notification__card">
            <p>{{ notification.message }}</p>

            <template v-if="notification.action_url">
                <div class="pt-2">
                    <Link :href="notification.action_url">
                        <button class="transition px-5 py-1 hover:bg-violet-950 rounded-full border border-violet-700 justify-start items-start text-violet-100 text-sm font-bold font-['Open Sans']">
                            {{ notification.action_label }}
                        </button>
                    </Link>
                </div>
            </template>

            <div class="pt-2" v-for="(action, index) in notification.actions" :key="index">
                <button @click.prevent="postRoute(generateRoute(action.route_name, action.parameters))" class="transition px-5 py-1 hover:bg-violet-950 rounded-full border border-violet-700 justify-start items-start text-violet-100 text-sm font-bold font-['Open Sans']">
                        {{ action.label }}
                    </button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.notification__card-wrapper{
    padding: 2px;
    border-radius:0 20px 20px 20px;
    background: linear-gradient(to bottom, #6E649A, #0D143A);

    .notification__card{
        padding: 25px;
        border-radius:0 20px 20px 20px;
        background: linear-gradient(to bottom, #101531, #3C4057);
    }
    p{
        color: var(--White, #EAE0FF);
        font-family: Open Sans,serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%; /* 18.2px */
    }
}
</style>
