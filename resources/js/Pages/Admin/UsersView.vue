<script setup>
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import TableUsers from "@/Components/Admin/TableUsers.vue";


</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <CardBox class="mb-6" has-table>
                <TableUsers/>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>

<style scoped lang="scss">

</style>
