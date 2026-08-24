<!-- resources\js\Pages\MyFashions\Clients\Orders\Index.vue -->
<!-- <script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    ShoppingOutlined,
    EyeOutlined,
    ArrowRightOutlined,
    ClockCircleOutlined,
    CheckCircleOutlined,
    CloseCircleOutlined,
    SyncOutlined,
    InboxOutlined,
} from "@ant-design/icons-vue";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },

    stats: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            processing: 0,
            completed: 0,
            cancelled: 0,
        }),
    },
});

const formatCurrency = (amount) => {
    return Number(amount || 0).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatDate = (date) => {
    if (!date) {
        return "-";
    }

    return new Date(date).toLocaleDateString("en-ZM", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
};

const getStatusLabel = (status) => {
    if (!status) {
        return "Pending";
    }

    return status.charAt(0).toUpperCase() + status.slice(1);
};

const getStatusClass = (status) => {
    switch (String(status || "").toLowerCase()) {
        case "pending":
            return "status-pending";

        case "processing":
            return "status-processing";

        case "completed":
        case "delivered":
            return "status-completed";

        case "cancelled":
        case "canceled":
            return "status-cancelled";

        default:
            return "status-default";
    }
};

const getStatusIcon = (status) => {
    switch (String(status || "").toLowerCase()) {
        case "pending":
            return ClockCircleOutlined;

        case "processing":
            return SyncOutlined;

        case "completed":
        case "delivered":
            return CheckCircleOutlined;

        case "cancelled":
        case "canceled":
            return CloseCircleOutlined;

        default:
            return ClockCircleOutlined;
    }
};

const orderCount = computed(() => {
    return props.orders.length;
});
</script> -->

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

import {
    ShoppingOutlined,
    EyeOutlined,
    ArrowRightOutlined,
    ClockCircleOutlined,
    CheckCircleOutlined,
    CloseCircleOutlined,
    SyncOutlined,
    InboxOutlined,
} from "@ant-design/icons-vue";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    orders: {
        type: [Array, Object],
        default: () => [],
    },

    stats: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            processing: 0,
            completed: 0,
            cancelled: 0,
        }),
    },

    cart: {
        type: Object,
        default: () => ({
            item_count: 0,
            total: 0,
        }),
    },
});



const orderList = computed(() => {
    if (Array.isArray(props.orders)) {
        return props.orders;
    }

    if (props.orders?.data && Array.isArray(props.orders.data)) {
        return props.orders.data;
    }

    return [];
});


/*
|--------------------------------------------------------------------------
| CLEAN ORDERS
|--------------------------------------------------------------------------
|
| Remove null/undefined records before rendering.
|
*/

const validOrders = computed(() => {
    return orderList.value.filter((order) => {
        return order !== null && order !== undefined;
    });
});


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

const cartCount = computed(() => {
    return Number(props.cart?.item_count || 0);
});

const cartTotal = computed(() => {
    return Number(props.cart?.total || 0);
});


/*
|--------------------------------------------------------------------------
| ORDER COUNT
|--------------------------------------------------------------------------
*/

const orderCount = computed(() => {
    return validOrders.value.length;
});


/*
|--------------------------------------------------------------------------
| CURRENCY
|--------------------------------------------------------------------------
*/

const formatCurrency = (amount) => {
    return Number(amount || 0).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {
    if (!date) {
        return "-";
    }

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) {
        return "-";
    }

    return parsedDate.toLocaleDateString("en-ZM", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
};


/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

const getStatusLabel = (status) => {
    if (!status) {
        return "Pending";
    }

    return String(status).charAt(0).toUpperCase()
        + String(status).slice(1);
};


/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const getStatusClass = (status) => {
    switch (String(status || "").toLowerCase()) {

        case "pending":
            return "status-pending";

        case "processing":
            return "status-processing";

        case "completed":
        case "delivered":
            return "status-completed";

        case "cancelled":
        case "canceled":
            return "status-cancelled";

        default:
            return "status-default";
    }
};


/*
|--------------------------------------------------------------------------
| STATUS ICON
|--------------------------------------------------------------------------
*/

const getStatusIcon = (status) => {
    switch (String(status || "").toLowerCase()) {

        case "pending":
            return ClockCircleOutlined;

        case "processing":
            return SyncOutlined;

        case "completed":
        case "delivered":
            return CheckCircleOutlined;

        case "cancelled":
        case "canceled":
            return CloseCircleOutlined;

        default:
            return ClockCircleOutlined;
    }
};


/*
|--------------------------------------------------------------------------
| ORDER ID
|--------------------------------------------------------------------------
|
| Handles different possible order structures.
|
*/

const getOrderId = (order) => {
    if (!order) {
        return null;
    }

    return (
        order.id ??
        order.order_id ??
        null
    );
};


/*
|--------------------------------------------------------------------------
| ORDER NUMBER
|--------------------------------------------------------------------------
*/

const getOrderNumber = (order) => {
    if (!order) {
        return "-";
    }

    return (
        order.order_number ??
        order.number ??
        order.id ??
        "-"
    );
};


/*
|--------------------------------------------------------------------------
| ORDER ITEMS COUNT
|--------------------------------------------------------------------------
*/

const getItemsCount = (order) => {
    if (!order) {
        return 0;
    }

    return Number(
        order.items_count ??
        order.quantity ??
        0
    );
};


/*
|--------------------------------------------------------------------------
| ORDER TOTAL
|--------------------------------------------------------------------------
*/

const getOrderTotal = (order) => {
    if (!order) {
        return 0;
    }

    return Number(
        order.total ??
        order.total_amount ??
        0
    );
};
</script>
<template>
    <UserFashionLayout
        :total-orders="stats.total"
        :pending-orders="stats.pending"
        :cart-count="0"
        :cart-total="0"
    >
        <template #title>
            My Orders
        </template>

        <template #subtitle>
            View and manage all your fashion orders.
        </template>

        <div class="orders-page">

            <!-- =====================================================
                 PAGE HEADER
            ====================================================== -->

            <section class="page-header">
                <div>
                    <div class="breadcrumb">
                        <Link href="/client/dashboard">
                            Dashboard
                        </Link>

                        <ArrowRightOutlined />

                        <span>My Orders</span>
                    </div>

                    <div class="page-title-row">
                        <div class="page-title-icon">
                            <ShoppingOutlined />
                        </div>

                        <div>
                            <h2>My Orders</h2>

                            <p>
                                Keep track of all your purchases and order status.
                            </p>
                        </div>
                    </div>
                </div>

                <Link
                    href="/my-shop"
                    class="continue-shopping"
                >
                    Continue Shopping
                    <ArrowRightOutlined />
                </Link>
            </section>


            <!-- =====================================================
                 ORDER STATISTICS
            ====================================================== -->

            <section class="order-stats">

                <!-- TOTAL -->

                <Link
                    href="/client/orders"
                    class="stat-card"
                >
                    <div class="stat-icon total-icon">
                        <ShoppingOutlined />
                    </div>

                    <div class="stat-content">
                        <span>Total Orders</span>

                        <strong>
                            {{ stats.total }}
                        </strong>
                    </div>
                </Link>


                <!-- PENDING -->

                <Link
                    href="/client/orders/pending"
                    class="stat-card"
                >
                    <div class="stat-icon pending-icon">
                        <ClockCircleOutlined />
                    </div>

                    <div class="stat-content">
                        <span>Pending</span>

                        <strong>
                            {{ stats.pending }}
                        </strong>
                    </div>
                </Link>


                <!-- PROCESSING -->

                <Link
                    href="/client/orders/processing"
                    class="stat-card"
                >
                    <div class="stat-icon processing-icon">
                        <SyncOutlined />
                    </div>

                    <div class="stat-content">
                        <span>Processing</span>

                        <strong>
                            {{ stats.processing }}
                        </strong>
                    </div>
                </Link>


                <!-- COMPLETED -->

                <Link
                    href="/client/orders/completed"
                    class="stat-card"
                >
                    <div class="stat-icon completed-icon">
                        <CheckCircleOutlined />
                    </div>

                    <div class="stat-content">
                        <span>Completed</span>

                        <strong>
                            {{ stats.completed }}
                        </strong>
                    </div>
                </Link>


                <!-- CANCELLED -->

                <Link
                    href="/client/orders/cancelled"
                    class="stat-card"
                >
                    <div class="stat-icon cancelled-icon">
                        <CloseCircleOutlined />
                    </div>

                    <div class="stat-content">
                        <span>Cancelled</span>

                        <strong>
                            {{ stats.cancelled }}
                        </strong>
                    </div>
                </Link>

            </section>


            <!-- =====================================================
                 ORDERS CARD
            ====================================================== -->

            <section class="orders-card">

                <!-- CARD HEADER -->

                <div class="orders-card-header">

                    <div>
                        <h3>
                            All Orders
                        </h3>

                        <p>
                            {{ orderCount }}
                            order{{ orderCount === 1 ? "" : "s" }}
                            found in your account.
                        </p>
                    </div>

                    <div class="order-filter-label">
                        <span class="filter-dot"></span>
                        All Orders
                    </div>

                </div>


                <!-- =================================================
                     EMPTY STATE
                ================================================== -->

                <div
                    v-if="orders.length === 0"
                    class="empty-orders"
                >
                    <div class="empty-icon">
                        <InboxOutlined />
                    </div>

                    <h3>
                        No Orders Yet
                    </h3>

                    <p>
                        You haven't placed any orders yet.
                        Start shopping and your orders will appear here.
                    </p>

                    <Link
                        href="/my-shop"
                        class="shop-button"
                    >
                        Start Shopping
                        <ArrowRightOutlined />
                    </Link>
                </div>


                <!-- =================================================
                     ORDERS LIST
                ================================================== -->

                <div
                    v-else
                    class="orders-list"
                >

                    <div
    v-for="order in validOrders"
    :key="getOrderId(order)"
    class="order-row"
>

                        <!-- ORDER ICON -->

                        <div class="order-icon">
                            <ShoppingOutlined />
                        </div>


                        <!-- ORDER INFORMATION -->

                        <div class="order-main">

                            <div class="order-number">
                                <strong>
                                    #{{ getOrderNumber(order) }}
                                </strong>

                                <span
                                    class="order-status"
                                    :class="getStatusClass(order.status)"
                                >
                                    <component
                                        :is="getStatusIcon(order.status)"
                                    />

                                    {{ getStatusLabel(order.status) }}
                                </span>
                            </div>

                            <div class="order-meta">

                                <span>
                                    Ordered
                                    {{ formatDate(order.created_at) }}
                                </span>

                                <span class="separator">
                                    •
                                </span>

                                <span>
                                    {{ getItemsCount(order) }}
                                    item{{ getItemsCount(order) === 1 ? "" : "s" }}
                                </span>

                            </div>

                        </div>


                        <!-- TOTAL -->

                        <div class="order-total">

                            <span>
                                Total
                            </span>

                            <strong>
                                K{{ formatCurrency(getOrderTotal(order)) }}
                            </strong>

                        </div>


                        <!-- <Link
    :href="route('client.orders.show', order.id)"
>
    View Order
</Link> -->

                        <!-- VIEW BUTTON -->

                        <!-- <Link
    :href="route('client.orders.show', order.id)"
    class="view-order-butto"
>
    <EyeOutlined />

    <span>
        View Order
    </span>
</Link> -->

                         <Link
    v-if="getOrderId(order)"
    :href="`/client/orders/${getOrderId(order)}`"
    class="view-order-button"
>
    <EyeOutlined />

    <span>
        View Order
    </span>
</Link>

<span
    v-else
    class="view-order-disabled"
>
    Order unavailable
</span>

<!-- 
                        <div class="order-action">

                            <Link
                                :href="`/client/orders/${order.id}`"
                                class="view-order-button"
                            >
                                <EyeOutlined />

                                <span>
                                    View Order
                                </span>
                            </Link>

                        </div> -->



                    </div>

                </div>

            </section>

        </div>
    </UserFashionLayout>
</template>


<style scoped>
/* ================================================================
   PAGE
================================================================ */

.orders-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
}


/* ================================================================
   PAGE HEADER
================================================================ */

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 13px;
    color: #9ca3af;
    font-size: 11px;
}

.breadcrumb a {
    color: #0f766e;
    text-decoration: none;
    font-weight: 600;
}

.breadcrumb a:hover {
    color: #115e59;
}

.breadcrumb :deep(svg) {
    font-size: 9px;
    color: #d1d5db;
}

.page-title-row {
    display: flex;
    align-items: center;
    gap: 13px;
}

.page-title-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #111827;
    color: #ffffff;
    font-size: 19px;
}

.page-title-row h2 {
    margin: 0;
    color: #111827;
    font-size: 22px;
    font-weight: 750;
}

.page-title-row p {
    margin: 4px 0 0;
    color: #9ca3af;
    font-size: 12px;
}


/* ================================================================
   CONTINUE SHOPPING
================================================================ */

.continue-shopping {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 40px;
    padding: 0 15px;
    border-radius: 9px;
    background: #111827;
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.continue-shopping:hover {
    background: #374151;
    color: #ffffff;
    transform: translateY(-1px);
}


.view-order-disabled {
    min-height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 11px;
    border-radius: 8px;
    background: #f3f4f6;
    color: #9ca3af;
    font-size: 10px;
    font-weight: 600;
}


/* ================================================================
   STATISTICS
================================================================ */

.order-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.stat-card {
    min-height: 82px;
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    text-decoration: none;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    border-color: #d1d5db;
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.05);
}

.stat-icon {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 16px;
}

.total-icon {
    background: #f3f4f6;
    color: #374151;
}

.pending-icon {
    background: #fff7ed;
    color: #ea580c;
}

.processing-icon {
    background: #eff6ff;
    color: #2563eb;
}

.completed-icon {
    background: #f0fdf4;
    color: #16a34a;
}

.cancelled-icon {
    background: #fef2f2;
    color: #dc2626;
}

.stat-content {
    min-width: 0;
}

.stat-content span {
    display: block;
    color: #9ca3af;
    font-size: 9px;
    font-weight: 600;
}

.stat-content strong {
    display: block;
    margin-top: 3px;
    color: #111827;
    font-size: 19px;
    font-weight: 750;
}


/* ================================================================
   ORDERS CARD
================================================================ */

.orders-card {
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: #ffffff;
}

.orders-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.orders-card-header h3 {
    margin: 0;
    color: #111827;
    font-size: 15px;
    font-weight: 700;
}

.orders-card-header p {
    margin: 4px 0 0;
    color: #9ca3af;
    font-size: 10px;
}

.order-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border-radius: 20px;
    background: #f8fafc;
    color: #6b7280;
    font-size: 10px;
    font-weight: 600;
}

.filter-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #0f766e;
}


/* ================================================================
   ORDER LIST
================================================================ */

.orders-list {
    width: 100%;
}

.order-row {
    display: grid;
    grid-template-columns: 44px minmax(180px, 1fr) auto auto;
    align-items: center;
    gap: 15px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
}

.order-row:last-child {
    border-bottom: none;
}

.order-row:hover {
    background: #fafafa;
}


/* ================================================================
   ORDER ICON
================================================================ */

.order-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #f0fdfa;
    color: #0f766e;
    font-size: 17px;
}


/* ================================================================
   ORDER MAIN
================================================================ */

.order-main {
    min-width: 0;
}

.order-number {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
}

.order-number strong {
    color: #111827;
    font-size: 13px;
    font-weight: 700;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    min-height: 21px;
    padding: 0 7px;
    border-radius: 20px;
    font-size: 8px;
    font-weight: 700;
}

.order-status :deep(svg) {
    font-size: 9px;
}

.status-pending {
    background: #fff7ed;
    color: #c2410c;
}

.status-processing {
    background: #eff6ff;
    color: #2563eb;
}

.status-completed {
    background: #f0fdf4;
    color: #16a34a;
}

.status-cancelled {
    background: #fef2f2;
    color: #dc2626;
}

.status-default {
    background: #f3f4f6;
    color: #6b7280;
}


/* ================================================================
   ORDER META
================================================================ */

.order-meta {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 5px;
    color: #9ca3af;
    font-size: 10px;
}

.separator {
    color: #d1d5db;
}


/* ================================================================
   TOTAL
================================================================ */

.order-total {
    text-align: right;
    white-space: nowrap;
}

.order-total span {
    display: block;
    color: #9ca3af;
    font-size: 9px;
}

.order-total strong {
    display: block;
    margin-top: 3px;
    color: #111827;
    font-size: 13px;
    font-weight: 700;
}


/* ================================================================
   VIEW ORDER
================================================================ */

.order-action {
    display: flex;
    justify-content: flex-end;
}

.view-order-button {
    min-height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 11px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #ffffff;
    color: #374151;
    font-size: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.view-order-button:hover {
    border-color: #0f766e;
    background: #f0fdfa;
    color: #0f766e;
}


/* ================================================================
   EMPTY STATE
================================================================ */

.empty-orders {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 330px;
    padding: 40px 20px;
    text-align: center;
}

.empty-icon {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f3f4f6;
    color: #9ca3af;
    font-size: 27px;
}

.empty-orders h3 {
    margin: 15px 0 5px;
    color: #111827;
    font-size: 16px;
    font-weight: 700;
}

.empty-orders p {
    max-width: 400px;
    margin: 0;
    color: #9ca3af;
    font-size: 11px;
    line-height: 1.7;
}

.shop-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 17px;
    min-height: 38px;
    padding: 0 14px;
    border-radius: 8px;
    background: #111827;
    color: #ffffff;
    font-size: 10px;
    font-weight: 600;
    text-decoration: none;
}

.shop-button:hover {
    background: #374151;
    color: #ffffff;
}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 1150px) {
    .order-stats {
        grid-template-columns: repeat(3, 1fr);
    }

    .order-row {
        grid-template-columns: 44px minmax(160px, 1fr) auto auto;
    }
}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 800px) {
    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .continue-shopping {
        width: 100%;
        justify-content: center;
    }

    .order-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .order-row {
        grid-template-columns: 44px 1fr auto;
        gap: 11px;
    }

    .order-total {
        grid-column: 2;
        text-align: left;
    }

    .order-action {
        grid-column: 3;
        grid-row: 1 / span 2;
    }
}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 520px) {
    .order-stats {
        grid-template-columns: 1fr;
    }

    .page-title-row h2 {
        font-size: 19px;
    }

    .page-title-row p {
        font-size: 10px;
    }

    .orders-card-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .order-row {
        grid-template-columns: 40px 1fr;
        padding: 14px;
    }

    .order-icon {
        width: 40px;
        height: 40px;
    }

    .order-main {
        min-width: 0;
    }

    .order-total {
        grid-column: 2;
    }

    .order-action {
        grid-column: 1 / -1;
        grid-row: auto;
        justify-content: stretch;
    }

    .view-order-button {
        width: 100%;
    }
}
</style>

