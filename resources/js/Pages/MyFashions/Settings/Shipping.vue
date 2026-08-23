<!-- resources\js\Pages\MyFashions\Settings\Shipping.vue -->
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
 <Head title="Shipping Settings" />

     <MyFashionLayout>
 <div class="p-6">

        <h1 class="text-2xl font-bold">
            Shipping Settings
        </h1>

        <p class="mt-2 text-gray-500">
            Configure shipping options.
        </p>

    </div>

     </MyFashionLayout>
   
</template>