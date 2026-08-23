<!-- resources/js/Pages/MyFashions/Orders/Rejected.vue -->

<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'

import { Head, Link, router } from '@inertiajs/vue3'

import { computed } from 'vue'

import Swal from 'sweetalert2'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    orders: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            meta: {},
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            payment_method: '',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
*/

const orderList = computed(() => {

    return props.orders?.data || []

})


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = computed(() => {

    return props.filters?.search || ''

})


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {

    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleDateString(
        'en-GB',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    )

}


/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {

    return Number(amount || 0).toFixed(2)

}


/*
|--------------------------------------------------------------------------
| CUSTOMER NAME
|--------------------------------------------------------------------------
*/

const customerName = (order) => {

    const firstName =
        order.shipping_first_name ||
        ''

    const lastName =
        order.shipping_last_name ||
        ''

    return `${firstName} ${lastName}`.trim() || '-'

}


/*
|--------------------------------------------------------------------------
| PAYMENT METHOD
|--------------------------------------------------------------------------
*/

const paymentMethodLabel = (method) => {

    if (method === 'cash_on_delivery') {
        return 'Cash on Delivery'
    }

    if (method === 'mobile_money') {
        return 'Mobile Money'
    }

    if (method === 'card') {
        return 'Visa / Card'
    }

    return method || '-'

}


/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {

    if (!image) {
        return '/assets/placeholder.jpg'
    }

    if (
        image.startsWith('http://') ||
        image.startsWith('https://') ||
        image.startsWith('/')
    ) {
        return image
    }

    return `/storage/${image}`

}


/*
|--------------------------------------------------------------------------
| VIEW ORDER
|--------------------------------------------------------------------------
*/

const viewOrder = (order) => {

    router.visit(
        `/fashion/orders/${order.id}`
    )

}


/*
|--------------------------------------------------------------------------
| RESTORE ORDER
|--------------------------------------------------------------------------
|
| Optional action if you want to move a rejected order back to pending.
|
*/

const restoreOrder = (order) => {

    Swal.fire({

        title: 'Restore Order?',

        text:
            'This will move the rejected order back to Pending.',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText:
            'Yes, Restore',

        cancelButtonText:
            'Keep Rejected',

        confirmButtonColor:
            '#075c59',

        cancelButtonColor:
            '#6b7280',

    }).then((result) => {

        if (!result.isConfirmed) {
            return
        }

        router.patch(

            `/fashion/orders/${order.id}/status`,

            {
                status: 'pending',
            },

            {

                preserveScroll: true,

                onSuccess: () => {

                    Swal.fire({

                        icon: 'success',

                        title: 'Order Restored',

                        text:
                            'The order has been moved back to Pending.',

                        timer: 1800,

                        showConfirmButton: false,

                    })

                },

                onError: () => {

                    Swal.fire({

                        icon: 'error',

                        title: 'Update Failed',

                        text:
                            'Unable to restore this order.',

                    })

                },

            }

        )

    })

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const goToPage = (url) => {

    if (!url) {
        return
    }

    router.visit(url, {
        preserveScroll: true,
        preserveState: true,
    })

}

</script>


<template>

    <Head title="Rejected Orders" />


    <MyFashionLayout>

        <div class="orders-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Rejected Orders
                    </h1>

                    <p>
                        Orders that have been rejected or declined.
                    </p>

                </div>


                <div class="header-count">

                    <span>
                        {{ orders.total || orderList.length }}
                    </span>

                    Rejected

                </div>

            </div>



            <!-- =====================================================
                 FILTER BAR
            ====================================================== -->

            <div class="filter-card">

                <div class="filter-info">

                    <div class="filter-icon">
                        ✕
                    </div>

                    <div>

                        <strong>
                            Rejected Orders
                        </strong>

                        <span>
                            Review orders that were rejected.
                        </span>

                    </div>

                </div>


                <div class="filter-actions">

                    <Link
                        href="/fashion/orders"
                        class="all-orders-btn"
                    >
                        View All Orders
                    </Link>

                </div>

            </div>



            <!-- =====================================================
                 EMPTY STATE
            ====================================================== -->

            <div
                v-if="!orderList.length"
                class="empty-card"
            >

                <div class="empty-icon">
                    ✕
                </div>

                <h2>
                    No Rejected Orders
                </h2>

                <p>
                    There are currently no rejected orders.
                </p>

                <Link
                    href="/fashion/orders"
                    class="empty-btn"
                >
                    View All Orders
                </Link>

            </div>



            <!-- =====================================================
                 ORDERS TABLE
            ====================================================== -->

            <div
                v-else
                class="orders-card"
            >

                <div class="card-header">

                    <div>

                        <h2>
                            Rejected Orders
                        </h2>

                        <p>
                            Orders that require review or follow-up.
                        </p>

                    </div>

                    <span class="order-count">

                        {{ orderList.length }}

                        {{
                            orderList.length === 1
                                ? 'Order'
                                : 'Orders'
                        }}

                    </span>

                </div>



                <div class="table-wrapper">

                    <table class="orders-table">

                        <thead>

                            <tr>

                                <th>
                                    Order
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="order in orderList"
                                :key="order.id"
                            >


                                <!-- ORDER -->

                                <td>

                                    <div class="order-info">

                                        <strong>
                                            {{ order.order_number }}
                                        </strong>

                                        <small>
                                            {{ order.items?.length || 0 }}

                                            {{
                                                order.items?.length === 1
                                                    ? 'item'
                                                    : 'items'
                                            }}
                                        </small>

                                    </div>

                                </td>



                                <!-- CUSTOMER -->

                                <td>

                                    <div class="customer-info">

                                        <strong>
                                            {{ customerName(order) }}
                                        </strong>

                                        <small>
                                            {{ order.customer_email || '-' }}
                                        </small>

                                    </div>

                                </td>



                                <!-- DATE -->

                                <td>

                                    <span class="date">
                                        {{ formatDate(order.created_at) }}
                                    </span>

                                </td>



                                <!-- PAYMENT -->

                                <td>

                                    <span class="payment-method">

                                        {{
                                            paymentMethodLabel(
                                                order.payment_method
                                            )
                                        }}

                                    </span>

                                </td>



                                <!-- TOTAL -->

                                <td>

                                    <strong class="order-total">

                                        ZMW
                                        {{ formatMoney(order.total_amount) }}

                                    </strong>

                                </td>



                                <!-- STATUS -->

                                <td>

                                    <span class="status-badge">

                                        <span class="status-dot">
                                        </span>

                                        Rejected

                                    </span>

                                </td>



                                <!-- ACTIONS -->

                                <td>

                                    <div class="actions">

                                        <button
                                            type="button"
                                            class="view-btn"
                                            @click="viewOrder(order)"
                                        >
                                            View
                                        </button>


                                        <button
                                            type="button"
                                            class="restore-btn"
                                            @click="restoreOrder(order)"
                                        >
                                            Restore
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>



                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div
                    v-if="orders.links?.length > 3"
                    class="pagination"
                >

                    <button
                        v-for="link in orders.links"
                        :key="link.label"
                        type="button"
                        class="pagination-btn"
                        :class="{
                            active: link.active,
                            disabled: !link.url
                        }"
                        :disabled="!link.url"
                        @click="goToPage(link.url)"
                        v-html="link.label"
                    />

                </div>

            </div>


        </div>

    </MyFashionLayout>

</template>


<style scoped>

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.orders-page {

    width: 100%;

    padding: 10px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 22px;

}

.page-header h1 {

    margin: 0;

    color: #111827;

    font-size: 27px;

    font-weight: 700;

}

.page-header p {

    margin: 6px 0 0;

    color: #6b7280;

    font-size: 13px;

}


.header-count {

    display: flex;

    align-items: center;

    gap: 7px;

    padding: 8px 12px;

    border-radius: 20px;

    background: #fceeee;

    color: #b33a3a;

    font-size: 10px;

    font-weight: 700;

}

.header-count span {

    font-size: 14px;

}


/*
|--------------------------------------------------------------------------
| FILTER CARD
|--------------------------------------------------------------------------
*/

.filter-card {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 15px 18px;

    margin-bottom: 18px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 10px;

}


.filter-info {

    display: flex;

    align-items: center;

    gap: 11px;

}


.filter-icon {

    width: 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #fef2f2;

    color: #b33a3a;

    font-weight: 700;

}


.filter-info strong {

    display: block;

    color: #111827;

    font-size: 12px;

}


.filter-info span {

    display: block;

    margin-top: 3px;

    color: #9ca3af;

    font-size: 10px;

}


.all-orders-btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 8px 13px;

    border-radius: 7px;

    background: #111827;

    color: white;

    font-size: 10px;

    font-weight: 600;

    text-decoration: none;

}


/*
|--------------------------------------------------------------------------
| ORDERS CARD
|--------------------------------------------------------------------------
*/

.orders-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    overflow: hidden;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

}


.card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 18px;

    border-bottom: 1px solid #edf0f2;

}


.card-header h2 {

    margin: 0;

    color: #111827;

    font-size: 15px;

    font-weight: 700;

}


.card-header p {

    margin: 5px 0 0;

    color: #777;

    font-size: 11px;

}


.order-count {

    padding: 5px 9px;

    border-radius: 6px;

    background: #fef2f2;

    color: #b33a3a;

    font-size: 10px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.orders-table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

}


.orders-table th {

    padding: 13px 16px;

    background: #f9fafb;

    border-bottom: 1px solid #e5e7eb;

    color: #6b7280;

    font-size: 10px;

    font-weight: 700;

    text-align: left;

    white-space: nowrap;

}


.orders-table td {

    padding: 14px 16px;

    border-bottom: 1px solid #f0f1f2;

    color: #374151;

    font-size: 11px;

    vertical-align: middle;

}


.orders-table tbody tr:hover {

    background: #fafafa;

}


/*
|--------------------------------------------------------------------------
| ORDER INFO
|--------------------------------------------------------------------------
*/

.order-info strong {

    display: block;

    color: #111827;

    font-size: 11px;

}


.order-info small {

    display: block;

    margin-top: 4px;

    color: #9ca3af;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

.customer-info strong {

    display: block;

    color: #374151;

    font-size: 11px;

}


.customer-info small {

    display: block;

    max-width: 180px;

    margin-top: 4px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    color: #9ca3af;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

.date {

    color: #6b7280;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

.payment-method {

    color: #555;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/

.order-total {

    color: #111827;

    font-size: 11px;

    white-space: nowrap;

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status-badge {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 5px 9px;

    border-radius: 20px;

    background: #fceeee;

    color: #b33a3a;

    font-size: 9px;

    font-weight: 700;

    text-transform: uppercase;

}


.status-dot {

    width: 6px;

    height: 6px;

    border-radius: 50%;

    background: #b33a3a;

}


/*
|--------------------------------------------------------------------------
| ACTIONS
|--------------------------------------------------------------------------
*/

.actions {

    display: flex;

    align-items: center;

    gap: 7px;

}


.view-btn,
.restore-btn {

    border: none;

    border-radius: 6px;

    padding: 7px 10px;

    font-size: 9px;

    font-weight: 600;

    cursor: pointer;

}


.view-btn {

    background: #f3f4f6;

    color: #374151;

}


.view-btn:hover {

    background: #e5e7eb;

}


.restore-btn {

    background: #eef7f5;

    color: #075c59;

}


.restore-btn:hover {

    background: #dff0ed;

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-card {

    padding: 70px 20px;

    text-align: center;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

}


.empty-icon {

    width: 52px;

    height: 52px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin: 0 auto 15px;

    border-radius: 50%;

    background: #fef2f2;

    color: #b33a3a;

    font-size: 20px;

    font-weight: 700;

}


.empty-card h2 {

    margin: 0;

    color: #111827;

    font-size: 17px;

}


.empty-card p {

    margin: 7px 0 18px;

    color: #9ca3af;

    font-size: 11px;

}


.empty-btn {

    display: inline-flex;

    padding: 9px 14px;

    border-radius: 7px;

    background: #111827;

    color: white;

    font-size: 10px;

    font-weight: 600;

    text-decoration: none;

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.pagination {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-wrap: wrap;

    gap: 5px;

    padding: 16px;

    border-top: 1px solid #edf0f2;

}


.pagination-btn {

    min-width: 30px;

    height: 30px;

    padding: 0 9px;

    border: 1px solid #e5e7eb;

    border-radius: 6px;

    background: white;

    color: #555;

    font-size: 9px;

    cursor: pointer;

}


.pagination-btn:hover:not(:disabled) {

    border-color: #075c59;

    color: #075c59;

}


.pagination-btn.active {

    border-color: #111827;

    background: #111827;

    color: white;

}


.pagination-btn.disabled {

    cursor: not-allowed;

    opacity: .45;

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 700px) {

    .orders-page {

        padding: 5px;

    }


    .page-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .filter-card {

        align-items: flex-start;

        flex-direction: column;

    }


    .filter-actions {

        width: 100%;

    }


    .all-orders-btn {

        width: 100%;

    }

}

</style>