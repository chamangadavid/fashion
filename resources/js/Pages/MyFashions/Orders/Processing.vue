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

    <Head title="Processing Orders" />

    <MyFashionLayout>

        <template #title>
            Processing Orders
        </template>

        <template #subtitle>
            Orders currently being processed.
        </template>


        <div class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h3>
                        Processing Orders
                    </h3>

                    <p>
                        Manage orders currently being prepared.
                    </p>

                </div>

            </div>


            <div class="orders-table">

                <!-- table will go here -->

            </div>

        </div>

    </MyFashionLayout>

</template>