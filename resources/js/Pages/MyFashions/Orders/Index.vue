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

    <Head title="All Orders" />

    <MyFashionLayout>

        <template #title>
            All Orders
        </template>

        <template #subtitle>
            Manage all customer orders.
        </template>


        <div class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h3>
                        All Orders
                    </h3>

                    <p>
                        View and manage customer orders.
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
                                Product
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
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
                                {{ order.product }}
                            </td>

                            <td>
                                {{ order.amount }}
                            </td>

                            <td>
                                {{ order.status }}
                            </td>

                            <td>

                                <button>
                                    View
                                </button>

                            </td>

                        </tr>


                        <tr v-if="!orders.length">

                            <td
                                colspan="6"
                                class="text-center"
                            >
                                No orders found.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </MyFashionLayout>

</template>