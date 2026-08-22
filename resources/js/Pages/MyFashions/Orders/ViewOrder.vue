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

    order: {
        type: Object,
        default: () => ({}),
    },

})

/*
|--------------------------------------------------------------------------
| ORDER ITEMS
|--------------------------------------------------------------------------
*/

const orderItems = computed(() => {

    return props.order?.items || []

})

/*
|--------------------------------------------------------------------------
| PAYMENT METHOD
|--------------------------------------------------------------------------
*/

const paymentMethodLabel = computed(() => {

    const method = props.order?.payment_method

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


const updateStatus = (status) => {

    let title = 'Update Order Status?'
    let text = `Change order status to ${status}?`
    let icon = 'question'

    if (status === 'cancelled') {

        title = 'Cancel Order?'

        text =
            'This will cancel the order and restore the purchased products back into stock.'

        icon = 'warning'
    }

    Swal.fire({

        title,

        text,

        icon,

        showCancelButton: true,

        confirmButtonText:
            status === 'cancelled'
                ? 'Yes, Cancel Order'
                : 'Yes, Update',

        cancelButtonText: 'Keep Order',

        confirmButtonColor:
            status === 'cancelled'
                ? '#dc2626'
                : '#111827',

        cancelButtonColor: '#6b7280',

    }).then((result) => {

        if (!result.isConfirmed) {
            return
        }

        router.patch(
            `/fashion/orders/${props.order.id}/status`,
            {
                status: status
            },
            {
                preserveScroll: true,

                onSuccess: () => {

                    Swal.fire({

                        icon: 'success',

                        title:
                            status === 'cancelled'
                                ? 'Order Cancelled'
                                : 'Status Updated',

                        text:
                            status === 'cancelled'
                                ? 'Order cancelled and stock restored.'
                                : 'Order status updated successfully.',

                        timer: 1800,

                        showConfirmButton: false,

                    })
                },

                onError: () => {

                    Swal.fire({

                        icon: 'error',

                        title: 'Update Failed',

                        text: 'Unable to update the order status.',

                    })
                }
            }
        )
    })
}

</script>


<template>

    <Head
        :title="`Order ${order.order_number || ''}`"
    />


    <MyFashionLayout>

        <div class="order-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <div class="back-link-wrapper">

                        <Link
                            href="/fashion/orders"
                            class="back-link"
                        >
                            ← Back to Orders
                        </Link>

                    </div>

                    <h1>
                        Order {{ order.order_number }}
                    </h1>

                    <p>
                        View and manage this customer order.
                    </p>

                </div>


                <div class="header-status">

                    <span
                        class="status-badge"
                        :class="`status-${order.status}`"
                    >
                        {{ order.status }}
                    </span>

                </div>

            </div>


            <!-- =====================================================
                 ORDER SUMMARY
            ====================================================== -->

            <div class="summary-row">

                <div class="summary-card">

                    <span class="summary-label">
                        Order Number
                    </span>

                    <strong>
                        {{ order.order_number }}
                    </strong>

                </div>


                <div class="summary-card">

                    <span class="summary-label">
                        Order Date
                    </span>

                    <strong>
                        {{ formatDate(order.created_at) }}
                    </strong>

                </div>


                <div class="summary-card">

                    <span class="summary-label">
                        Payment
                    </span>

                    <strong>
                        {{ paymentMethodLabel }}
                    </strong>

                </div>


                <div class="summary-card">

                    <span class="summary-label">
                        Total
                    </span>

                    <strong>
                        ZMW {{ formatMoney(order.total_amount) }}
                    </strong>

                </div>

            </div>


            <!-- =====================================================
                 MAIN GRID
            ====================================================== -->

            <div class="order-grid">


                <!-- =================================================
                     LEFT
                ================================================== -->

                <div class="main-column">

   <!-- =========================================================
                        ORDER STATUS MANAGEMENT
                    ========================================================= -->

                    <div class="status-management-card">

                        <div class="status-management-header">

                            <div>

                                <h3>
                                    Order Status
                                </h3>

                                <p>
                                    Manage the progress of this customer order.
                                </p>

                            </div>

                            <span
                                class="current-status"
                                :class="`status-${order.status}`"
                            >
                                {{ order.status }}
                            </span>

                        </div>


                        <div class="status-flow">

                            <!-- PENDING -->

                            <button
                                type="button"
                                class="status-option"
                                :class="{
                                    active: order.status === 'pending'
                                }"
                                :disabled="
                                    order.status === 'pending' ||
                                    order.status === 'cancelled'
                                "
                                @click="updateStatus('pending')"
                            >

                                <span class="status-icon">
                                    ⏳
                                </span>

                                <span>
                                    Pending
                                </span>

                            </button>


                            <span class="status-arrow">
                                →
                            </span>


                            <!-- PROCESSING -->

                            <button
                                type="button"
                                class="status-option"
                                :class="{
                                    active: order.status === 'processing'
                                }"
                                :disabled="
                                    order.status === 'processing' ||
                                    order.status === 'cancelled'
                                "
                                @click="updateStatus('processing')"
                            >

                                <span class="status-icon">
                                    ⚙️
                                </span>

                                <span>
                                    Processing
                                </span>

                            </button>


                            <span class="status-arrow">
                                →
                            </span>


                            <!-- COMPLETED -->

                            <button
                                type="button"
                                class="status-option"
                                :class="{
                                    active: order.status === 'completed'
                                }"
                                :disabled="
                                    order.status === 'completed' ||
                                    order.status === 'cancelled'
                                "
                                @click="updateStatus('completed')"
                            >

                                <span class="status-icon">
                                    ✓
                                </span>

                                <span>
                                    Completed
                                </span>

                            </button>


                            <span class="status-arrow">
                                →
                            </span>


                            <!-- CANCELLED -->

                            <button
                                type="button"
                                class="status-option cancelled-option"
                                :class="{
                                    active: order.status === 'cancelled'
                                }"
                                :disabled="
                                    order.status === 'cancelled'
                                "
                                @click="updateStatus('cancelled')"
                            >

                                <span class="status-icon">
                                    ✕
                                </span>

                                <span>
                                    Cancelled
                                </span>

                            </button>

                        </div>


                        <!-- CANCELLATION WARNING -->

                        <div
                            v-if="order.status !== 'cancelled'"
                            class="stock-warning"
                        >

                            <span class="warning-icon">
                                ⚠️
                            </span>

                            <div>

                                <strong>
                                    Cancellation & Stock
                                </strong>

                                <p>
                                    Cancelling this order will restore all ordered
                                    products back into inventory.
                                </p>

                            </div>

                        </div>


                        <!-- CANCELLED MESSAGE -->

                        <div
                            v-else
                            class="cancelled-message"
                        >

                            <span>
                                ✓
                            </span>

                            <div>

                                <strong>
                                    Order Cancelled
                                </strong>

                                <p>
                                    The stock for this order has already been restored.
                                </p>

                            </div>

                        </div>

                    </div>
                    
                    <!-- =============================================
                         ORDER ITEMS
                    ============================================== -->

                    <div class="content-card">

                        <div class="card-header">

                            <div>

                                <h2>
                                    Order Items
                                </h2>

                                <p>
                                    Products included in this order.
                                </p>

                            </div>

                            <span class="item-count">
                                {{ orderItems.length }}
                                {{ orderItems.length === 1 ? 'Item' : 'Items' }}
                            </span>

                        </div>


                        <div class="table-wrapper">

                            <table class="order-items-table">

                                <thead>

                                    <tr>

                                        <th>
                                            Product
                                        </th>

                                        <th>
                                            SKU
                                        </th>

                                        <th>
                                            Price
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                        <th>
                                            Total
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <tr
                                        v-for="item in orderItems"
                                        :key="item.id"
                                    >

                                        <!-- PRODUCT -->

                                        <td>

                                            <div class="product-cell">

                                                <div class="product-image">

                                                    <img
                                                        :src="
                                                            imageUrl(
                                                                item.product?.image
                                                                || item.product?.featured_image
                                                            )
                                                        "
                                                        :alt="item.product_name"
                                                        @error="$event.target.src = '/assets/placeholder.jpg'"
                                                    />

                                                </div>


                                                <div class="product-info">

                                                    <strong>
                                                        {{ item.product_name }}
                                                    </strong>

                                                    <small>
                                                        Product ID:
                                                        {{ item.product_id }}
                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- SKU -->

                                        <td>

                                            <span class="sku">
                                                {{ item.sku || '-' }}
                                            </span>

                                        </td>


                                        <!-- PRICE -->

                                        <td>

                                            ZMW
                                            {{ formatMoney(item.unit_price) }}

                                        </td>


                                        <!-- QUANTITY -->

                                        <td>

                                            <span class="quantity">
                                                {{ item.quantity }}
                                            </span>

                                        </td>


                                        <!-- TOTAL -->

                                        <td>

                                            <strong>
                                                ZMW
                                                {{ formatMoney(item.total_price) }}
                                            </strong>

                                        </td>

                                    </tr>


                                    <tr v-if="!orderItems.length">

                                        <td
                                            colspan="5"
                                            class="empty-row"
                                        >
                                            No items found for this order.
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    <!-- =============================================
                         ORDER TOTALS
                    ============================================== -->

                    <div class="content-card">

                        <div class="card-header">

                            <div>

                                <h2>
                                    Order Summary
                                </h2>

                            </div>

                        </div>


                        <div class="totals">

                            <div class="total-row">

                                <span>
                                    Subtotal
                                </span>

                                <strong>
                                    ZMW {{ formatMoney(order.subtotal) }}
                                </strong>

                            </div>


                            <div class="total-row">

                                <span>
                                    Shipping
                                </span>

                                <strong>
                                    ZMW {{ formatMoney(order.shipping_amount) }}
                                </strong>

                            </div>


                            <div class="total-row grand-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    ZMW {{ formatMoney(order.total_amount) }}
                                </strong>

                            </div>

                        </div>

                    </div>

                  

                </div>


                <!-- =================================================
                     RIGHT SIDEBAR
                ================================================== -->

                <div class="sidebar">


                    <!-- =============================================
                         CUSTOMER
                    ============================================== -->

                    <div class="content-card">

                        <div class="card-header">

                            <h2>
                                Customer
                            </h2>

                        </div>


                        <div class="info-list">

                            <div class="info-item">

                                <span>
                                    Name
                                </span>

                                <strong>
                                    {{ order.shipping_first_name }}
                                    {{ order.shipping_last_name }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Email
                                </span>

                                <strong>
                                    {{ order.customer_email }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Phone
                                </span>

                                <strong>
                                    {{ order.customer_phone }}
                                </strong>

                            </div>

                        </div>

                    </div>

  


                    <!-- =============================================
                         SHIPPING
                    ============================================== -->

                    <div class="content-card">

                        <div class="card-header">

                            <h2>
                                Shipping Address
                            </h2>

                        </div>


                        <div class="address">

                            <strong>

                                {{ order.shipping_first_name }}
                                {{ order.shipping_last_name }}

                            </strong>


                            <p>
                                {{ order.shipping_address }}
                            </p>


                            <p>
                                {{ order.shipping_city }}
                            </p>


                            <p>
                                {{ order.shipping_country }}
                            </p>


                            <p>
                                {{ order.customer_phone }}
                            </p>

                        </div>

                    </div>


                    <!-- =============================================
                         PAYMENT
                    ============================================== -->

                    <div class="content-card">

                        <div class="card-header">

                            <h2>
                                Payment
                            </h2>

                        </div>


                        <div class="info-list">

                            <div class="info-item">

                                <span>
                                    Method
                                </span>

                                <strong>
                                    {{ paymentMethodLabel }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Payment Status
                                </span>

                                <span
                                    class="payment-status"
                                    :class="`payment-${order.payment_status}`"
                                >
                                    {{ order.payment_status }}
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- =============================================
                         ORDER INFORMATION
                    ============================================== -->

                    <div class="content-card">

                        <div class="card-header">

                            <h2>
                                Order Information
                            </h2>

                        </div>


                        <div class="info-list">

                            <div class="info-item">

                                <span>
                                    Created
                                </span>

                                <strong>
                                    {{ formatDateTime(order.created_at) }}
                                </strong>

                            </div>


                            <div class="info-item">

                                <span>
                                    Updated
                                </span>

                                <strong>
                                    {{ formatDateTime(order.updated_at) }}
                                </strong>

                            </div>

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

.order-page {

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
| STATUS
|--------------------------------------------------------------------------
*/

.status-badge {

    display: inline-flex;

    padding: 7px 12px;

    border-radius: 20px;

    font-size: 10px;

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

    font-size: 14px;

}


/*
|--------------------------------------------------------------------------
| MAIN GRID
|--------------------------------------------------------------------------
*/

.order-grid {

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

.order-items-table {

    width: 100%;

    min-width: 700px;

    border-collapse: collapse;

}

.order-items-table th {

    padding: 13px 16px;

    background: #f9fafb;

    border-bottom: 1px solid #e5e7eb;

    color: #6b7280;

    font-size: 10px;

    font-weight: 700;

    text-align: left;

    white-space: nowrap;

}

.order-items-table td {

    padding: 14px 16px;

    border-bottom: 1px solid #f0f1f2;

    color: #374151;

    font-size: 11px;

    vertical-align: middle;

}


/*
|--------------------------------------------------------------------------
| PRODUCT
|--------------------------------------------------------------------------
*/

.product-cell {

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 220px;

}

.product-image {

    width: 52px;

    height: 52px;

    flex: 0 0 52px;

    overflow: hidden;

    border-radius: 7px;

    background: #f3f4f6;

}

.product-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

}

.product-info strong {

    display: block;

    color: #111827;

    font-size: 12px;

}

.product-info small {

    display: block;

    margin-top: 4px;

    color: #9ca3af;

    font-size: 9px;

}

.sku {

    color: #777;

    font-size: 10px;

}

.quantity {

    display: inline-flex;

    min-width: 30px;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 5px;

    background: #f3f4f6;

    font-weight: 600;

}

.empty-row {

    padding: 40px !important;

    text-align: center;

    color: #999;

}


/* =========================================================
   ORDER STATUS MANAGEMENT
========================================================= */

.status-management-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    padding: 20px;

    margin-bottom: 20px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

}


.status-management-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 20px;

}


.status-management-header h3 {

    margin: 0;

    color: #111827;

    font-size: 16px;

    font-weight: 700;

}


.status-management-header p {

    margin: 5px 0 0;

    color: #777;

    font-size: 11px;

}


/* =========================================================
   CURRENT STATUS
========================================================= */

.current-status {

    display: inline-flex;

    align-items: center;

    padding: 7px 12px;

    border-radius: 20px;

    font-size: 10px;

    font-weight: 700;

    text-transform: uppercase;

}


.current-status.status-pending {

    background: #fff7e6;

    color: #a0833d;

}


.current-status.status-processing {

    background: #eef7f5;

    color: #075c59;

}


.current-status.status-completed {

    background: #edf7ed;

    color: #287a2d;

}


.current-status.status-cancelled {

    background: #fceeee;

    color: #b33a3a;

}


/* =========================================================
   STATUS FLOW
========================================================= */

.status-flow {

    display: flex;

    align-items: center;

    gap: 10px;

}


.status-option {

    flex: 1;

    min-height: 70px;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    gap: 6px;

    border: 1px solid #e5e7eb;

    border-radius: 9px;

    background: white;

    color: #555;

    cursor: pointer;

    font-size: 11px;

    font-weight: 600;

    transition: .2s ease;

}


.status-option:hover:not(:disabled) {

    border-color: #075c59;

    color: #075c59;

    background: #f7fbfa;

}


.status-option.active {

    border-color: #111827;

    background: #111827;

    color: white;

}


.status-option:disabled {

    cursor: not-allowed;

    opacity: .55;

}


.status-icon {

    font-size: 18px;

}


.status-arrow {

    color: #bbb;

    font-size: 18px;

}


/* =========================================================
   CANCELLED
========================================================= */

.cancelled-option {

    color: #b33a3a;

}


.cancelled-option:hover:not(:disabled) {

    border-color: #dc2626;

    background: #fef2f2;

    color: #dc2626;

}


.cancelled-option.active {

    background: #b33a3a;

    border-color: #b33a3a;

    color: white;

}


/* =========================================================
   STOCK WARNING
========================================================= */

.stock-warning {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-top: 18px;

    padding: 12px 14px;

    border-radius: 8px;

    background: #fffaf0;

    border: 1px solid #f5dfad;

}


.warning-icon {

    font-size: 15px;

}


.stock-warning strong {

    display: block;

    color: #7a5a18;

    font-size: 11px;

}


.stock-warning p {

    margin: 3px 0 0;

    color: #8a6d2f;

    font-size: 10px;

}


/* =========================================================
   CANCELLED MESSAGE
========================================================= */

.cancelled-message {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-top: 18px;

    padding: 12px 14px;

    border-radius: 8px;

    background: #fef2f2;

    border: 1px solid #fecaca;

}


.cancelled-message > span {

    width: 22px;

    height: 22px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #b33a3a;

    color: white;

    font-size: 11px;

}


.cancelled-message strong {

    display: block;

    color: #991b1b;

    font-size: 11px;

}


.cancelled-message p {

    margin: 3px 0 0;

    color: #b33a3a;

    font-size: 10px;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

    .status-management-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .status-flow {

        flex-direction: column;

        align-items: stretch;

    }


    .status-option {

        width: 100%;

        flex-direction: row;

        justify-content: flex-start;

        padding: 12px;

    }


    .status-arrow {

        display: none;

    }

}

/*
|--------------------------------------------------------------------------
| TOTALS
|--------------------------------------------------------------------------
*/

.totals {

    padding: 18px;

}

.total-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 9px 0;

    color: #6b7280;

    font-size: 12px;

}

.total-row strong {

    color: #374151;

}

.grand-total {

    margin-top: 8px;

    padding-top: 15px;

    border-top: 1px solid #e5e7eb;

    color: #111827;

    font-size: 14px;

}

.grand-total strong {

    color: #111827;

    font-size: 17px;

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

.info-item span:first-child {

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
| ADDRESS
|--------------------------------------------------------------------------
*/

.address {

    padding: 5px 18px 18px;

    color: #555;

    font-size: 11px;

    line-height: 1.7;

}

.address strong {

    display: block;

    color: #111827;

    margin-bottom: 6px;

}

.address p {

    margin: 0;

}


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

.payment-status {

    display: inline-flex;

    width: fit-content;

    padding: 4px 8px;

    border-radius: 5px;

    font-size: 9px;

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


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1000px) {

    .summary-row {

        grid-template-columns: repeat(2, 1fr);

    }

    .order-grid {

        grid-template-columns: 1fr;

    }

    .sidebar {

        display: grid;

        grid-template-columns: repeat(2, 1fr);

    }

}


@media (max-width: 650px) {

    .order-page {

        padding: 5px;

    }

    .page-header {

        align-items: flex-start;

        flex-direction: column;

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