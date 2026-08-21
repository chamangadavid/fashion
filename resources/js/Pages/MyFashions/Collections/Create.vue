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
<Head title="Create" />
<MyFashionLayout>
    <div class="p-6">

        <h1 class="text-2xl font-bold">
            Create Collection
        </h1>

        <p class="mt-2 text-gray-500">
            Create a new fashion collection.
        </p>

    </div>
</MyFashionLayout>


</template>