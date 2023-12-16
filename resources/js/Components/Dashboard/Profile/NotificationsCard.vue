<script setup>
const props = defineProps({
    notification: Object
})
</script>

<template>
    <div class="notification__card-wrapper">
        <div class="notification__card">
            <p>{{notification.message}}</p>
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
        font-family: Poppins;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%; /* 18.2px */
    }
}
</style>
