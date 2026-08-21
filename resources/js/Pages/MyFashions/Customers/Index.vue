<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    customerOrders: {
        type: Array,
        default: () => [],
    },
});

</script>


<template>

  <Head title="Customers" />

     <MyFashionLayout>
    <div class="p-6">

        <h1 class="text-2xl font-bold">
            All Customers
        </h1>

        <p class="mt-2 text-gray-500">
            Manage your fashion store customers.
        </p>

    </div>
     </MyFashionLayout>


</template>