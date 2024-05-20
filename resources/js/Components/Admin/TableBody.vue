<template>
    <n-card class="table-body-container" :content-style="{ padding: '0px' }">
        <slot name="header"></slot>
        <slot name="default"></slot>
        <slot name="footer"></slot>
    </n-card>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { NCard } from "naive-ui";
export default defineComponent({
    name: "TableBody",
    components: { NCard },
});
</script>
