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
    
    <Head title="Product " />
     <MyFashionLayout>
<div class="p-6">

        <h1 class="text-2xl font-bold">
            Product Categories
        </h1>

        <p class="mt-2 text-gray-500">
            Manage your product categories.
        </p>

    </div>
     </MyFashionLayout>

    

</template>