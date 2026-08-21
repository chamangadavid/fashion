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

    <Head title="Customer Groups" />

     <MyFashionLayout>
 <div class="p-6">

        <h1 class="text-2xl font-bold">
            Customer Groups
        </h1>

        <p class="mt-2 text-gray-500">
            Manage customer groups.
        </p>

    </div>
     </MyFashionLayout>
   

</template>