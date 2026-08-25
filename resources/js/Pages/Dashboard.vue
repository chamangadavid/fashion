<!-- resources/js/Pages/Dashboard.vue -->

<script setup>
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminBoard from '@/Components/AdminBoard.vue'

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

    orders: {
        type: Array,
        default: () => [],
    },

    products: {
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
| ROLES
|--------------------------------------------------------------------------
|
| Laravel sends roles through:
|
| auth.roles = ["Users"]
|
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
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

const isSuperAdmin = computed(() => {
    return roles.value.includes('Super Admin')
})

/*
|--------------------------------------------------------------------------
| PERMISSION CHECK
|--------------------------------------------------------------------------
*/

const can = (permission) => {
    if (isSuperAdmin.value) {
        return true
    }

    return permissions.value.includes(permission)
}

/*
|--------------------------------------------------------------------------
| ADMIN / STAFF ACCESS
|--------------------------------------------------------------------------
*/
const isAdminOrStaff = computed(() => {
    // Users role must never access the admin dashboard
    if (roles.value.includes('Users')) {
        return false
    }

    return (
        isSuperAdmin.value ||
        can('manage access control') ||
        can('manage staff access control')
    )
})



/*
|--------------------------------------------------------------------------
| PAGE TITLE
|--------------------------------------------------------------------------
*/

const dashboardTitle = computed(() => {
    return isAdminOrStaff.value
        ? 'Admin Dashboard'
        : 'Dashboard'
})
</script>

<template>
    <Head :title="dashboardTitle" />

    <!-- =========================================================
         ADMIN / STAFF DASHBOARD
    ========================================================== -->

    <AdminBoard
        v-if="isAdminOrStaff"
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
                    You do not currently have access to this dashboard.
                </p>

                <div class="mt-6 text-sm text-gray-400">
                    Roles:
                    {{ roles.join(', ') || 'None' }}
                </div>
            </div>
        </div>
    </MyFashionLayout>
</template>