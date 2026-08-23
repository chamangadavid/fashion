<!-- resources\js\Pages\MyFashions\Settings\Store.vue -->
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
  <Head title="Store Settings" />
  
   <MyFashionLayout>
 <div class="p-6">

        <h1 class="text-2xl font-bold">
            Store Settings
        </h1>

        <p class="mt-2 text-gray-500">
            Configure your fashion store.
        </p>

    </div>
   </MyFashionLayout>
   

</template>