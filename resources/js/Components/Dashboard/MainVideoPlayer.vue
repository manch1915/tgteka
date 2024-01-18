<script setup>
import {VideoPlayer} from "@videojs-player/vue";
import 'video.js/dist/video-js.css'

const props = defineProps({
    url: String,
    image: String
})
</script>

<template>
    <div class="h-1/2 w-1/2">
        <video-player
            :src="url"
            :poster="image"
            controls
            :loop="true"
            autoplay
            fill
        />
    </div>
</template>

<style scoped lang="scss">

</style>
