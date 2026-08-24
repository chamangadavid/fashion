<!-- resources\js\Pages\Dashboard.vue -->
<script setup>
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

import UserDashboard from '@/Components/UserDashboard.vue';
import AdminBoard from '@/Components/AdminBoard.vue';


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    auth: {
        type: Object,
        required: true,
    },

    stats: {
        type: Object,

        default: () => ({
            total_orders: 0,
            pending_orders: 0,
            completed_orders: 0,
            total_spent: 0,
        }),
    },

    charts: {
        type: Object,

        default: () => ({}),
    },

    cart: {
        type: Object,

        default: () => ({
            items: [],
            subtotal: 0,
            shipping: 0,
            total: 0,
            item_count: 0,
        }),
    },

    orders: {
        type: Array,

        default: () => [],
    },

    payments: {
        type: Array,

        default: () => [],
    },

});


/*
|--------------------------------------------------------------------------
| CURRENT USER
|--------------------------------------------------------------------------
*/

const user = computed(() => {

    return props.auth?.user ?? {};

});


/*
|--------------------------------------------------------------------------
| ROLES
|--------------------------------------------------------------------------
*/

const roles = computed(() => {

    return user.value?.roles ?? [];

});


/*
|--------------------------------------------------------------------------
| NORMAL CUSTOMER
|--------------------------------------------------------------------------
*/

const isUser = computed(() => {

    return roles.value.includes('Users');

});


/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

const isSuperAdmin = computed(() => {

    return roles.value.includes('Super Admin');

});


/*
|--------------------------------------------------------------------------
| PERMISSION CHECK
|--------------------------------------------------------------------------
*/

const can = (permission) => {

    /*
    | Super Admin has everything
    */

    if (isSuperAdmin.value) {

        return true;

    }


    return props.auth?.permissions?.includes(permission) ?? false;

};


/*
|--------------------------------------------------------------------------
| ADMIN / STAFF
|--------------------------------------------------------------------------
*/

const isAdminOrStaff = computed(() => {

    return (

        isSuperAdmin.value ||

        can('manage access control') ||

        can('manage staff access control')

    );

});


/*
|--------------------------------------------------------------------------
| WHICH DASHBOARD?
|--------------------------------------------------------------------------
*/

const showUserDashboard = computed(() => {

    return isUser.value;

});


const showAdminDashboard = computed(() => {

    return (

        !isUser.value &&

        isAdminOrStaff.value

    );

});


/*
|--------------------------------------------------------------------------
| PAGE TITLE
|--------------------------------------------------------------------------
*/

const dashboardTitle = computed(() => {

    if (showUserDashboard.value) {

        return 'Dashboard';

    }


    if (showAdminDashboard.value) {

        return 'Admin Dashboard';

    }


    return 'Dashboard';

});

</script>


<template>

    <Head :title="dashboardTitle" />


  


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <!-- <template #header>

            <div class="flex items-center justify-between">

                <div>

                    <h2
                        class="text-2xl font-bold text-gray-800 dark:text-white"
                    >
                        {{ dashboardTitle }}
                    </h2>


                    <p
                        v-if="user?.name"
                        class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                    >
                        Welcome back, {{ user.name }}
                    </p>

                </div>

            </div>

        </template> -->


        <!-- =====================================================
             CUSTOMER DASHBOARD
        ====================================================== -->

        <UserDashboard

            v-if="showUserDashboard"

            :auth="props.auth"

            :cart="props.cart"

            :orders="props.orders"

            :payments="props.payments"

            :stats="props.stats"

        />


        <!-- =====================================================
             ADMIN DASHBOARD
        ====================================================== -->

        <AdminBoard

            v-else-if="showAdminDashboard"

            :auth="props.auth"

            :stats="props.stats"

            :charts="props.charts"

        />


        <!-- =====================================================
             NO ACCESS
        ====================================================== -->

        <div
            v-else
            class="py-12"
        >

            <div
                class="mx-auto max-w-4xl px-6 lg:px-8"
            >

                <div
                    class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800"
                >

                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700"
                    >

                        <svg
                            class="h-8 w-8 text-gray-500 dark:text-gray-300"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"
                            />

                        </svg>

                    </div>


                    <h3
                        class="mt-5 text-xl font-semibold text-gray-800 dark:text-white"
                    >
                        No Dashboard Access
                    </h3>


                    <p
                        class="mx-auto mt-2 max-w-lg text-gray-500 dark:text-gray-400"
                    >
                        Your account does not currently have permission
                        to access a dashboard. Please contact an administrator.
                    </p>


                    <div
                        v-if="roles.length"
                        class="mt-6"
                    >

                        <p
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            Your roles:
                        </p>


                        <div
                            class="mt-3 flex flex-wrap justify-center gap-2"
                        >

                            <span
                                v-for="role in roles"
                                :key="role"
                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200"
                            >

                                {{ role }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>



</template>