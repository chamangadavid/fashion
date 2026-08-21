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
 <Head title="Role and Permissions" />
 
 <MyFashionLayout>
 <div class="p-6">

        <h1 class="text-2xl font-bold">
            Roles & Permissions
        </h1>

        <p class="mt-2 text-gray-500">
            Manage user roles and permissions.
        </p>

    </div>
 </MyFashionLayout>
   

</template>