<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    customerOrders: {
        type: Array,
        default: () => [],
    },
});

</script>

<template>
 <Head title="VIP Customers" />

     <MyFashionLayout>
<div class="p-6">

        <h1 class="text-2xl font-bold">
            VIP Customers
        </h1>

        <p class="mt-2 text-gray-500">
            Manage VIP customers.
        </p>

    </div>
     </MyFashionLayout>
    

</template>