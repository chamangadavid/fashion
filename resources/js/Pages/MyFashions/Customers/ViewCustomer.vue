<!-- resources\js\Pages\MyFashions\Customers\ViewCustomer.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    customer: {
        type: Object,
        default: () => ({}),
    },

    orders: {
        type: Array,
        default: () => [],
    },

})


/*
|--------------------------------------------------------------------------
| CUSTOMER ORDERS
|--------------------------------------------------------------------------
*/

const customerOrders = computed(() => {

    return props.orders || []

})


/*
|--------------------------------------------------------------------------
| TOTAL ORDERS
|--------------------------------------------------------------------------
*/

const totalOrders = computed(() => {

    return customerOrders.value.length

})


/*
|--------------------------------------------------------------------------
| TOTAL SPENT
|--------------------------------------------------------------------------
*/

const totalSpent = computed(() => {

    return customerOrders.value.reduce(
        (total, order) => {

            if (order.status === 'cancelled') {
                return total
            }

            if (order.status === 'refunded') {
                return total
            }

            return total + Number(order.total_amount || 0)

        },
        0
    )

})


/*
|--------------------------------------------------------------------------
| COMPLETED ORDERS
|--------------------------------------------------------------------------
*/

const completedOrders = computed(() => {

    return customerOrders.value.filter(
        order => order.status === 'completed'
    ).length

})


/*
|--------------------------------------------------------------------------
| PENDING ORDERS
|--------------------------------------------------------------------------
*/

const pendingOrders = computed(() => {

    return customerOrders.value.filter(
        order => order.status === 'pending'
    ).length

})


/*
|--------------------------------------------------------------------------
| CUSTOMER NAME
|--------------------------------------------------------------------------
*/

const customerName = computed(() => {

    const firstName =
        props.customer?.first_name ||
        props.customer?.shipping_first_name ||
        ''

    const lastName =
        props.customer?.last_name ||
        props.customer?.shipping_last_name ||
        ''

    const fullName = `${firstName} ${lastName}`.trim()

    return fullName || 'Guest Customer'

})


/*
|--------------------------------------------------------------------------
| CUSTOMER EMAIL
|--------------------------------------------------------------------------
*/

const customerEmail = computed(() => {

    return (
        props.customer?.email ||
        props.customer?.customer_email ||
        '-'
    )

})


/*
|--------------------------------------------------------------------------
| CUSTOMER PHONE
|--------------------------------------------------------------------------
*/

const customerPhone = computed(() => {

    return (
        props.customer?.phone ||
        props.customer?.customer_phone ||
        '-'
    )

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
| FORMAT DATETIME
|--------------------------------------------------------------------------
*/

const formatDateTime = (date) => {

    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleString(
        'en-GB',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
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

</script>


<template>

    <Head
        :title="`Customer - ${customerName}`"
    />


    <MyFashionLayout>

        <div class="customer-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <div class="back-link-wrapper">

                        <Link
                            href="/fashion/customers"
                            class="back-link"
                        >
                            ← Back to Customers
                        </Link>

                    </div>


                    <h1>
                        {{ customerName }}
                    </h1>


                    <p>
                        View customer information and order history.
                    </p>

                </div>


                <div class="customer-avatar">

                    {{ customerName.charAt(0).toUpperCase() }}

                </div>

            </div>


            <!-- =====================================================
                 CUSTOMER SUMMARY
            ====================================================== -->

            <div class="summary-row">


                <!-- ORDERS -->

                <div class="summary-card">

                    <span class="summary-label">
                        Total Orders
                    </span>

                    <strong>
                        {{ totalOrders }}
                    </strong>

                </div>


                <!-- SPENT -->

                <div class="summary-card">

                    <span class="summary-label">
                        Total Spent
                    </span>

                    <strong>
                        ZMW {{ formatMoney(totalSpent) }}
                    </strong>

                </div>


                <!-- COMPLETED -->

                <div class="summary-card">

                    <span class="summary-label">
                        Completed Orders
                    </span>

                    <strong>
                        {{ completedOrders }}
                    </strong>

                </div>


                <!-- PENDING -->

                <div class="summary-card">

                    <span class="summary-label">
                        Pending Orders
                    </span>

                    <strong>
                        {{ pendingOrders }}
                    </strong>

                </div>

            </div>


            <!-- =====================================================
                 MAIN GRID
            ====================================================== -->

            <div class="customer-grid">


                <!-- =================================================
                     LEFT COLUMN
                ================================================== -->

                <div class="main-column">


                    <!-- =============================================
                         ORDER HISTORY
                    ============================================== -->

                    <div class="content-card">


                        <div class="card-header">

                            <div>

                                <h2>
                                    Order History
                                </h2>

                                <p>
                                    All orders placed by this customer.
                                </p>

                            </div>


                            <span class="item-count">

                                {{ totalOrders }}

                                {{
                                    totalOrders === 1
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
                                            Date
                                        </th>

                                        <th>
                                            Items
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
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>


                                    <tr
                                        v-for="order in customerOrders"
                                        :key="order.id"
                                    >


                                        <!-- ORDER -->

                                        <td>

                                            <Link
                                                :href="`/fashion/orders/${order.id}`"
                                                class="order-number"
                                            >

                                                {{
                                                    order.order_number
                                                }}

                                            </Link>

                                        </td>


                                        <!-- DATE -->

                                        <td>

                                            {{
                                                formatDate(
                                                    order.created_at
                                                )
                                            }}

                                        </td>


                                        <!-- ITEMS -->

                                        <td>

                                            <span class="items-count">

                                                {{
                                                    order.items_count ??
                                                    order.items?.length ??
                                                    0
                                                }}

                                            </span>

                                        </td>


                                        <!-- PAYMENT -->

                                        <td>

                                            <div class="payment-info">

                                                <strong>

                                                    {{
                                                        paymentMethodLabel(
                                                            order.payment_method
                                                        )
                                                    }}

                                                </strong>

                                                <span
                                                    class="payment-status"
                                                    :class="`payment-${order.payment_status}`"
                                                >

                                                    {{
                                                        order.payment_status ||
                                                        'pending'
                                                    }}

                                                </span>

                                            </div>

                                        </td>


                                        <!-- TOTAL -->

                                        <td>

                                            <strong>

                                                ZMW
                                                {{
                                                    formatMoney(
                                                        order.total_amount
                                                    )
                                                }}

                                            </strong>

                                        </td>


                                        <!-- STATUS -->

                                        <td>

                                            <span
                                                class="status-badge"
                                                :class="`status-${order.status}`"
                                            >

                                                {{
                                                    order.status
                                                }}

                                            </span>

                                        </td>


                                        <!-- ACTION -->

                                        <td>

                                            <Link
                                                :href="`/fashion/orders/${order.id}`"
                                                class="view-button"
                                            >
                                                View
                                            </Link>

                                        </td>


                                    </tr>


                                    <!-- EMPTY -->

                                    <tr
                                        v-if="!customerOrders.length"
                                    >

                                        <td
                                            colspan="7"
                                            class="empty-row"
                                        >

                                            No orders found for this customer.

                                        </td>

                                    </tr>


                                </tbody>

                            </table>

                        </div>

                    </div>


                    <!-- =============================================
                         CUSTOMER ACTIVITY
                    ============================================== -->

                    <div class="content-card">


                        <div class="card-header">

                            <div>

                                <h2>
                                    Customer Activity
                                </h2>

                                <p>
                                    Customer account information.
                                </p>

                            </div>

                        </div>


                        <div class="activity-list">


                            <div class="activity-item">

                                <div class="activity-icon">
                                    👤
                                </div>

                                <div>

                                    <strong>
                                        Customer Account
                                    </strong>

                                    <p>
                                        Customer profile information.
                                    </p>

                                </div>

                            </div>


                            <div class="activity-item">

                                <div class="activity-icon">
                                    🛍️
                                </div>

                                <div>

                                    <strong>
                                        {{ totalOrders }} Orders
                                    </strong>

                                    <p>
                                        Total orders placed by this customer.
                                    </p>

                                </div>

                            </div>


                            <div class="activity-item">

                                <div class="activity-icon">
                                    💰
                                </div>

                                <div>

                                    <strong>
                                        ZMW {{ formatMoney(totalSpent) }}
                                    </strong>

                                    <p>
                                        Total value of active orders.
                                    </p>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>


                <!-- =================================================
                     RIGHT SIDEBAR
                ================================================== -->

                <div class="sidebar">


                    <!-- =============================================
                         CUSTOMER INFORMATION
                    ============================================== -->

                    <div class="content-card">


                        <div class="card-header">

                            <h2>
                                Customer
                            </h2>

                        </div>


                        <div class="customer-profile">


                            <div class="profile-avatar">

                                {{ customerName.charAt(0).toUpperCase() }}

                            </div>


                            <h3>
                                {{ customerName }}
                            </h3>


                            <p>
                                {{ customerEmail }}
                            </p>

                        </div>


                        <div class="info-list">


                            <div class="info-item">

                                <span>
                                    Name
                                </span>

                                <strong>
                                    {{ customerName }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Email
                                </span>

                                <strong>
                                    {{ customerEmail }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Phone
                                </span>

                                <strong>
                                    {{ customerPhone }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Customer Since
                                </span>

                                <strong>
                                    {{
                                        formatDate(
                                            customer.created_at
                                        )
                                    }}
                                </strong>

                            </div>


                        </div>

                    </div>


                    <!-- =============================================
                         CUSTOMER STATS
                    ============================================== -->

                    <div class="content-card">


                        <div class="card-header">

                            <h2>
                                Customer Statistics
                            </h2>

                        </div>


                        <div class="stats-list">


                            <div class="stat-row">

                                <span>
                                    Total Orders
                                </span>

                                <strong>
                                    {{ totalOrders }}
                                </strong>

                            </div>


                            <div class="stat-row">

                                <span>
                                    Completed
                                </span>

                                <strong>
                                    {{ completedOrders }}
                                </strong>

                            </div>


                            <div class="stat-row">

                                <span>
                                    Pending
                                </span>

                                <strong>
                                    {{ pendingOrders }}
                                </strong>

                            </div>


                            <div class="stat-row">

                                <span>
                                    Total Spent
                                </span>

                                <strong>
                                    ZMW {{ formatMoney(totalSpent) }}
                                </strong>

                            </div>


                        </div>

                    </div>


                    <!-- =============================================
                         LAST ORDER
                    ============================================== -->

                    <div
                        v-if="customerOrders.length"
                        class="content-card"
                    >


                        <div class="card-header">

                            <h2>
                                Latest Order
                            </h2>

                        </div>


                        <div class="latest-order">


                            <strong class="latest-order-number">

                                {{
                                    customerOrders[0].order_number
                                }}

                            </strong>


                            <span class="latest-order-date">

                                {{
                                    formatDateTime(
                                        customerOrders[0].created_at
                                    )
                                }}

                            </span>


                            <div class="latest-order-total">

                                ZMW
                                {{
                                    formatMoney(
                                        customerOrders[0].total_amount
                                    )
                                }}

                            </div>


                            <span
                                class="status-badge"
                                :class="`status-${customerOrders[0].status}`"
                            >

                                {{
                                    customerOrders[0].status
                                }}

                            </span>


                            <Link
                                :href="`/fashion/orders/${customerOrders[0].id}`"
                                class="latest-order-button"
                            >

                                View Order

                            </Link>

                        </div>

                    </div>


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

.customer-page {

    padding: 10px;

    width: 100%;

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

    margin-bottom: 25px;

}

.page-header h1 {

    margin: 6px 0 0;

    font-size: 28px;

    font-weight: 700;

    color: #111827;

}

.page-header p {

    margin: 6px 0 0;

    color: #6b7280;

    font-size: 14px;

}

.back-link {

    color: #075c59;

    font-size: 12px;

    font-weight: 600;

    text-decoration: none;

}

.back-link:hover {

    text-decoration: underline;

}


/*
|--------------------------------------------------------------------------
| AVATAR
|--------------------------------------------------------------------------
*/

.customer-avatar {

    width: 55px;

    height: 55px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #075c59;

    color: white;

    font-size: 20px;

    font-weight: 700;

}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.summary-row {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 15px;

    margin-bottom: 18px;

}

.summary-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 10px;

    padding: 15px 18px;

}

.summary-label {

    display: block;

    color: #777;

    font-size: 10px;

    margin-bottom: 5px;

}

.summary-card strong {

    color: #111827;

    font-size: 17px;

}


/*
|--------------------------------------------------------------------------
| MAIN GRID
|--------------------------------------------------------------------------
*/

.customer-grid {

    display: grid;

    grid-template-columns: minmax(0, 1fr) 330px;

    gap: 18px;

}

.main-column,

.sidebar {

    display: flex;

    flex-direction: column;

    gap: 18px;

}


/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

.content-card {

    background: white;

    border-radius: 12px;

    border: 1px solid #edf0f2;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .04);

    overflow: hidden;

}


/*
|--------------------------------------------------------------------------
| CARD HEADER
|--------------------------------------------------------------------------
*/

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

.item-count {

    padding: 5px 9px;

    border-radius: 6px;

    background: #f3f4f6;

    color: #555;

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

    min-width: 850px;

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


/*
|--------------------------------------------------------------------------
| ORDER NUMBER
|--------------------------------------------------------------------------
*/

.order-number {

    color: #075c59;

    font-weight: 700;

    text-decoration: none;

}

.order-number:hover {

    text-decoration: underline;

}


/*
|--------------------------------------------------------------------------
| ITEMS
|--------------------------------------------------------------------------
*/

.items-count {

    display: inline-flex;

    min-width: 28px;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 5px;

    background: #f3f4f6;

    color: #374151;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

.payment-info {

    display: flex;

    flex-direction: column;

    gap: 4px;

}

.payment-info strong {

    font-size: 10px;

    color: #374151;

}

.payment-status {

    width: fit-content;

    padding: 3px 6px;

    border-radius: 4px;

    font-size: 8px;

    font-weight: 700;

    text-transform: uppercase;

}

.payment-pending {

    background: #fff7e6;

    color: #a0833d;

}

.payment-paid {

    background: #edf7ed;

    color: #287a2d;

}

.payment-failed {

    background: #fceeee;

    color: #b33a3a;

}

.payment-cancelled {

    background: #fceeee;

    color: #b33a3a;

}

.payment-refunded {

    background: #f3f4f6;

    color: #6b7280;

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status-badge {

    display: inline-flex;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 8px;

    font-weight: 700;

    text-transform: uppercase;

}

.status-pending {

    background: #fff7e6;

    color: #a0833d;

}

.status-processing {

    background: #eef7f5;

    color: #075c59;

}

.status-completed {

    background: #edf7ed;

    color: #287a2d;

}

.status-cancelled {

    background: #fceeee;

    color: #b33a3a;

}

.status-refunded {

    background: #f3f4f6;

    color: #6b7280;

}


/*
|--------------------------------------------------------------------------
| VIEW BUTTON
|--------------------------------------------------------------------------
*/

.view-button {

    display: inline-flex;

    padding: 6px 10px;

    border-radius: 6px;

    background: #075c59;

    color: white;

    font-size: 9px;

    font-weight: 600;

    text-decoration: none;

}

.view-button:hover {

    background: #064d4a;

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-row {

    padding: 45px !important;

    text-align: center;

    color: #999;

}


/*
|--------------------------------------------------------------------------
| CUSTOMER PROFILE
|--------------------------------------------------------------------------
*/

.customer-profile {

    padding: 22px 18px;

    text-align: center;

    border-bottom: 1px solid #edf0f2;

}

.profile-avatar {

    width: 65px;

    height: 65px;

    margin: 0 auto 10px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #075c59;

    color: white;

    font-size: 23px;

    font-weight: 700;

}

.customer-profile h3 {

    margin: 0;

    color: #111827;

    font-size: 15px;

}

.customer-profile p {

    margin: 5px 0 0;

    color: #777;

    font-size: 10px;

    word-break: break-word;

}


/*
|--------------------------------------------------------------------------
| INFO
|--------------------------------------------------------------------------
*/

.info-list {

    padding: 5px 18px 18px;

}

.info-item {

    display: flex;

    flex-direction: column;

    gap: 4px;

    padding: 12px 0;

    border-bottom: 1px solid #f0f1f2;

}

.info-item:last-child {

    border-bottom: none;

}

.info-item span {

    color: #9ca3af;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: .4px;

}

.info-item strong {

    color: #374151;

    font-size: 11px;

    word-break: break-word;

}


/*
|--------------------------------------------------------------------------
| STATS
|--------------------------------------------------------------------------
*/

.stats-list {

    padding: 8px 18px 15px;

}

.stat-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 11px 0;

    border-bottom: 1px solid #f0f1f2;

}

.stat-row:last-child {

    border-bottom: none;

}

.stat-row span {

    color: #777;

    font-size: 10px;

}

.stat-row strong {

    color: #111827;

    font-size: 12px;

}


/*
|--------------------------------------------------------------------------
| ACTIVITY
|--------------------------------------------------------------------------
*/

.activity-list {

    padding: 5px 18px 18px;

}

.activity-item {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 13px 0;

    border-bottom: 1px solid #f0f1f2;

}

.activity-item:last-child {

    border-bottom: none;

}

.activity-icon {

    width: 34px;

    height: 34px;

    flex: 0 0 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #eef7f5;

    font-size: 14px;

}

.activity-item strong {

    display: block;

    color: #374151;

    font-size: 11px;

}

.activity-item p {

    margin: 3px 0 0;

    color: #999;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| LATEST ORDER
|--------------------------------------------------------------------------
*/

.latest-order {

    padding: 18px;

}

.latest-order-number {

    display: block;

    color: #075c59;

    font-size: 13px;

}

.latest-order-date {

    display: block;

    margin-top: 4px;

    color: #999;

    font-size: 9px;

}

.latest-order-total {

    margin: 15px 0;

    color: #111827;

    font-size: 18px;

    font-weight: 700;

}

.latest-order-button {

    display: flex;

    justify-content: center;

    margin-top: 14px;

    padding: 9px 12px;

    border-radius: 7px;

    background: #111827;

    color: white;

    font-size: 10px;

    font-weight: 600;

    text-decoration: none;

}

.latest-order-button:hover {

    background: #075c59;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1000px) {

    .summary-row {

        grid-template-columns: repeat(2, 1fr);

    }

    .customer-grid {

        grid-template-columns: 1fr;

    }

    .sidebar {

        display: grid;

        grid-template-columns: repeat(2, 1fr);

    }

}


@media (max-width: 650px) {

    .customer-page {

        padding: 5px;

    }

    .page-header {

        align-items: flex-start;

    }

    .page-header h1 {

        font-size: 24px;

    }

    .summary-row {

        grid-template-columns: 1fr;

    }

    .sidebar {

        display: flex;

        flex-direction: column;

    }

}

</style>