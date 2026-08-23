<!-- resources\js\Pages\MyFashions\Reports\Revenue.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    reports: {
        type: Array,
        default: () => [],
    },
});

</script>

<template>
 <Head title="Revenue Reports" />
     <MyFashionLayout>
  <div class="p-6">

        <h1 class="text-2xl font-bold">
            Revenue Reports
        </h1>

        <p class="mt-2 text-gray-500">
            Revenue analytics and reports.
        </p>

    </div>
     </MyFashionLayout>
  

</template>