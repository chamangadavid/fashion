<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    collections: {
        type: Array,
        default: () => [],
    },
});

</script>

<template>
  <Head title="All Collections" />

      <MyFashionLayout>

          <div class="p-6">

        <h1 class="text-2xl font-bold">
            All Collections
        </h1>

        <p class="mt-2 text-gray-500">
            Manage fashion collections.
        </p>

    </div>
      </MyFashionLayout>

  

</template>