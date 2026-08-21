<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

</script>

<template>

    <MyFashionLayout>

        <div class="page">

            <div class="page-header">

                <div>

                    <h1>
                        Product Categories
                    </h1>

                    <p>
                        Manage your fashion product categories.
                    </p>

                </div>

            </div>

            <div class="content-card">

                <h2>
                    Categories
                </h2>

                <p>
                    Product categories will be managed here.
                </p>

            </div>

        </div>

    </MyFashionLayout>

</template>

<style scoped>

.page {
    padding: 10px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    color: #777;
}

.content-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

</style>


<!-- <script setup>

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

    

</template> -->