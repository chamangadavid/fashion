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

 <Head title="Completed Orders" />
 <MyFashionLayout>
<div class="p-6">

        <h1 class="text-2xl font-bold">
            Add Product
        </h1>

        <p class="mt-2 text-gray-500">
            Create a new fashion product.
        </p>

    </div>
 </MyFashionLayout>
    

</template>