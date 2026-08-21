

<script setup>

import AdminBoard from '@/Components/AdminBoard.vue';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({})
    },

    stats: {
        type: Object,
        default: () => ({})
    },

    charts: {
        type: Object,
        default: () => ({})
    },

    orders: {
        type: Array,
        default: () => []
    },

    reports: {
        type: Array,
        default: () => []
    }
});

</script>


<template>

    <AdminBoard
        :auth="props.auth"
        :stats="props.stats"
        :charts="props.charts"
        :orders="props.orders"
        :reports="props.reports"
    />

</template>

<!-- MyFashions/Dashboard.vue

<script setup>

import AdminBoard from '@/Components/AdminBoard.vue';

const props = defineProps({
    auth: Object,
    stats: Object,
    charts: Object,
    orders: Array,
    reports: Array,
});

</script>

<template>

    <MyFashionLayout
        :auth="auth"
    >

        <AdminBoard
            :auth="auth"
            :stats="stats"
            :charts="charts"
            :orders="orders"
            :reports="reports"
        />

    </MyFashionLayout>

</template> -->