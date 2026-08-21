<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    settings: {
        type: Array,
        default: () => [],
    },
});

</script>


<template>
<Head title="Payment Settings" />
 <MyFashionLayout>
 <div class="p-6">

        <h1 class="text-2xl font-bold">
            Payment Settings
        </h1>

        <p class="mt-2 text-gray-500">
            Configure payment methods.
        </p>

    </div>
 </MyFashionLayout>

   

</template>