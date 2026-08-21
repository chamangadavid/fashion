<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

</script>

<template>
  <Head title="Products" />
 <MyFashionLayout>
  <div class="p-6">

        <h1 class="text-2xl font-bold">
            All Products
        </h1>

        <p class="mt-2 text-gray-500">
            Manage all products in your fashion store.
        </p>

    </div>
 </MyFashionLayout>
  

</template>