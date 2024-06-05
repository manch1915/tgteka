<script setup>
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import UsersTableWithSearch from '@/Components/Admin/UsersTableWithSearch.vue'


</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <CardBox class="mb-6" has-table>
                <UsersTableWithSearch />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>

<style scoped lang="scss">

</style>
