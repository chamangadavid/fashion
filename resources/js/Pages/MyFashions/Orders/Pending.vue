<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

</script>


<template>

    <Head title="Pending Orders" />

    <MyFashionLayout>

        <template #title>
            Pending Orders
        </template>

        <template #subtitle>
            Orders waiting to be processed.
        </template>


        <div class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h3>
                        Pending Orders
                    </h3>

                    <p>
                        Review orders that are waiting for processing.
                    </p>

                </div>

            </div>


            <div class="orders-table">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Order
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr
                            v-for="order in orders"
                            :key="order.id"
                        >

                            <td>
                                {{ order.id }}
                            </td>

                            <td>
                                {{ order.customer_name }}
                            </td>

                            <td>
                                {{ order.amount }}
                            </td>

                            <td>
                                {{ order.status }}
                            </td>

                        </tr>


                        <tr v-if="!orders.length">

                            <td
                                colspan="4"
                                class="text-center"
                            >
                                No pending orders.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </MyFashionLayout>

</template>