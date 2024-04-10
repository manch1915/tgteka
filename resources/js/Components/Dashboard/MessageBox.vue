<script setup>
import { computed } from "vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import { mdiCheckAll } from "@mdi/js";
import { NImage } from "naive-ui";

const props = defineProps({
    text: String,
    userAvatar: String,
    created_at: String,
    isImage: Boolean,
    isTimeString: {
        default: false,
        required: false,
    },
});

let createdAtLocalTimeString;

if (props.isTimeString) {
    createdAtLocalTimeString = props.created_at;
} else {
    let createdAtUTC = props.created_at;
    let createdAtDate = new Date(createdAtUTC);
    let options = { hour: "2-digit", minute: "2-digit", hour12: false };
    createdAtLocalTimeString = createdAtDate.toLocaleTimeString(
        "en-US",
        options
    );
}

const avatar = computed(
    () => `https://api.dicebear.com/7.x/initials/svg?seed=${props.userAvatar}`
);
</script>

<template>
    <div class="main px-4">
        <div class="message">
            <div class="flex gap-x-3">
                <div class="avatar flex flex-col justify-end flex-shrink-0">
                    <img
                        style="width: 34px; height: 34px; border-radius: 50%"
                        :src="avatar"
                        alt="ava"
                    />
                </div>
                <div class="message__box">
                    <!-- Render image if 'isImage' true -->
                    <n-image
                        v-if="isImage"
                        :src="text"
                        class="message-image"
                        alt=""
                    />

                    <!-- Render text if 'isImage' false -->
                    <p
                        v-else
                        class="text break-words text-slate-800 text-base font-normal font-['Open Sans'] leading-tight"
                    >
                        {{ text }}
                    </p>
                    <div class="date float-right">
                        <div class="flex gap-x-0.5">
                            <p
                                class="text-right text-slate-900 text-opacity-40 text-base font-normal font-['Open Sans'] leading-tight"
                            >
                                {{ createdAtLocalTimeString }}
                            </p>
                            <BaseIcon :path="mdiCheckAll" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.avatar {
    flex-shrink: 0;
}
.message__box {
    background: #eae0ff;
    border-radius: 10px 10px 10px 0;
    padding: 12px;
    width: 40%;
    position: relative;
    flex-grow: 1;
    min-width: 0;
    overflow-wrap: break-word;
    word-wrap: break-word;
    @media screen and (max-width: 640px) {
        width: 100%;
    }
    &:before {
        content: "";
        width: 0px;
        height: 0px;
        display: block;
        border-left: 5px solid transparent;
        border-right: 5px solid #eae0ff;
        border-top: 5px solid transparent;
        border-bottom: 5px solid #eae0ff;
        position: absolute;
        bottom: 0px; /* Changed from top to bottom */
        left: -10px;
    }
}
.message-image {
    width: 36rem;
}
</style>
