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
 <Head title="Create User" />
     <MyFashionLayout>
 <div class="p-6">

        <h1 class="text-2xl font-bold">
            Add User
        </h1>

        <p class="mt-2 text-gray-500">
            Create a new user.
        </p>

    </div>
     </MyFashionLayout>
   

</template>