<script setup>
const props = defineProps({
    text: String,
    userAvatar: String
})
</script>

<template>
    <div class="main">
        <div class="message">
            <div class="flex gap-x-3">
                <div class="avatar flex flex-col justify-end">
                    <img style="width: 34px; height: 34px; border-radius: 50%" :src="userAvatar" alt="ava">
                </div>
                <div class="message__box">
                    <div class="text text-slate-800 text-base font-normal font-['Open Sans'] leading-tight">
                        {{text}}
                    </div>
                    <div class="date float-right">
                        <div class="flex gap-x-0.5">
                            <p class="text-right text-slate-900 text-opacity-40 text-base font-normal font-['Open Sans'] leading-tight">12:30</p>
                            <img src="/images/vector.svg" alt="vector">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.message__box{
    background: #EAE0FF;
    border-radius: 10px 10px 10px 0;
    padding: 12px;
    width: 40%;
    position: relative;
    &:before {
        content: "";
        width: 0px;
        height: 0px;
        display: block;
        border-left: 5px solid transparent;
        border-right: 5px solid #EAE0FF;
        border-top: 5px solid transparent;
        border-bottom: 5px solid #EAE0FF;
        position: absolute;
        bottom: 0px; /* Changed from top to bottom */
        left: -10px;
    }
}
</style>
