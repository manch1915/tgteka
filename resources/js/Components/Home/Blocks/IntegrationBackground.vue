<template>
  <div :class="classString">
    <img src="/images/integr-background.svg" alt="">
  </div>
</template>
<script setup>
const props = defineProps({
    classString: {
        type: String,
        default: ''
    }
})
</script>
<style>
.margin-top{
    @media screen and (max-width: 385px){
        margin-top: 200px;
    }
    @media screen and (max-width: 333px){
        margin-top: 300px;
    }
}
</style>
