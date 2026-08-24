<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

import UserDashboard from '@/Components/UserDashboard.vue'
import AdminBoard from '@/Components/AdminBoard.vue'

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },

    dashboard_type: {
        type: String,
        default: null,
    },

    stats: {
        type: Object,
        default: () => ({
            total_orders: 0,
            pending_orders: 0,
            processing_orders: 0,
            completed_orders: 0,
            cancelled_orders: 0,
            total_spent: 0,
            total_revenue: 0,
            total_customers: 0,
            products: 0,
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

    products: {
        type: Array,
        default: () => [],
    },

    payments: {
        type: Array,
        default: () => [],
    },

    thread_feed: {
        type: Array,
        default: () => [],
    },

    reports: {
        type: Array,
        default: () => [],
    },
})

/*
|--------------------------------------------------------------------------
| CURRENT USER
|--------------------------------------------------------------------------
*/

const user = computed(() => {
    return props.auth?.user ?? {}
})

/*
|--------------------------------------------------------------------------
| ROLES
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Use auth.roles because Laravel is sending:
|
| auth.roles = ["Users"]
|
| while auth.user.roles contains role objects.
|--------------------------------------------------------------------------
*/

const roles = computed(() => {
    return props.auth?.roles ?? []
})

/*
|--------------------------------------------------------------------------
| PERMISSIONS
|--------------------------------------------------------------------------
*/

const permissions = computed(() => {
    return props.auth?.permissions ?? []
})

/*
|--------------------------------------------------------------------------
| ROLE CHECKS
|--------------------------------------------------------------------------
*/

const isUser = computed(() => {
    return roles.value.includes('Users')
})

const isSuperAdmin = computed(() => {
    return roles.value.includes('Super Admin')
})

/*
|--------------------------------------------------------------------------
| PERMISSION CHECK
|--------------------------------------------------------------------------
*/

const can = (permission) => {

    // Super Admin can access everything
    if (isSuperAdmin.value) {
        return true
    }

    return permissions.value.includes(permission)
}

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
    )
})

/*
|--------------------------------------------------------------------------
| WHICH DASHBOARD?
|--------------------------------------------------------------------------
|
| IMPORTANT:
| A user with the "Users" role MUST always receive UserDashboard.
|
| We check the Users role FIRST.
|--------------------------------------------------------------------------
*/

const showUserDashboard = computed(() => {

    return isUser.value
})

const showAdminDashboard = computed(() => {

    return (
        !isUser.value &&
        isAdminOrStaff.value
    )
})

/*
|--------------------------------------------------------------------------
| PAGE TITLE
|--------------------------------------------------------------------------
*/

const dashboardTitle = computed(() => {

    if (showUserDashboard.value) {
        return 'Dashboard'
    }

    if (showAdminDashboard.value) {
        return 'Admin Dashboard'
    }

    return 'Dashboard'
})

</script>


<template>

    <Head :title="dashboardTitle" />

    <!-- =========================================================
         USER DASHBOARD
    ========================================================== -->

        <UserDashboard

            v-if="showUserDashboard"

            :auth="auth"
            :stats="stats"
            :cart="cart"
            :orders="orders"
            :payments="payments"
            :charts="charts"

        />


    <!-- <MyFashionLayout v-if="showUserDashboard">

        <UserDashboard
            :auth="auth"
            :stats="stats"
            :cart="cart"
            :orders="orders"
            :payments="payments"
            :charts="charts"
        />

    </MyFashionLayout> -->


    <!-- =========================================================
         ADMIN / STAFF DASHBOARD
    ========================================================== -->

    <AdminBoard
        v-else-if="showAdminDashboard"
        :auth="auth"
        :stats="stats"
        :orders="orders"
        :products="products"
        :thread_feed="thread_feed"
        :reports="reports"
        :charts="charts"
    />


    <!-- =========================================================
         NO DASHBOARD ACCESS
    ========================================================== -->

    <MyFashionLayout v-else>

        <div class="flex min-h-[60vh] items-center justify-center">

            <div class="text-center">

                <h1 class="text-xl font-semibold text-gray-800">
                    Dashboard
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    You do not currently have access to a dashboard.
                </p>

                <!-- Debug information -->
                <div class="mt-6 text-sm text-gray-400">
                    Roles:
                    {{ roles.join(', ') || 'None' }}
                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>