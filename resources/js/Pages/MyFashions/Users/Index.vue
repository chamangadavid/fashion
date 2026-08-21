<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    users: {
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
            All Users
        </h1>

        <p class="mt-2 text-gray-500">
            Manage fashion store users.
        </p>

    </div>
  </MyFashionLayout>
   

</template>