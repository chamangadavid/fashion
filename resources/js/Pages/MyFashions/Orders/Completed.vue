<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

</script>


<template>

    <Head title="Completed Orders" />

    <MyFashionLayout>

        <template #title>
            Completed Orders
        </template>

        <template #subtitle>
            Successfully completed customer orders.
        </template>


        <div class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h3>
                        Completed Orders
                    </h3>

                    <p>
                        View your completed orders.
                    </p>

                </div>

            </div>


            <div class="orders-table">

                <!-- completed orders table -->

            </div>

        </div>

    </MyFashionLayout>

</template>