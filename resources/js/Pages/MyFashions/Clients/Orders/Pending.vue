<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    ArrowLeftOutlined,
    EyeOutlined,
    ShoppingOutlined,
    ClockCircleOutlined,
    RightOutlined,
    InboxOutlined,
} from "@ant-design/icons-vue";

const props = defineProps({
    orders: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
            links: [],
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: "",
        }),
    },
});

/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
| Laravel paginate() returns:
|
| {
|   data: [],
|   current_page: 1,
|   last_page: 1,
|   total: 0,
|   ...
| }
|
*/

const orderList = computed(() => {
    return Array.isArray(props.orders?.data)
        ? props.orders.data
        : [];
});

/*
|--------------------------------------------------------------------------
| TOTAL PENDING ORDERS
|--------------------------------------------------------------------------
*/

const totalOrders = computed(() => {
    return Number(props.orders?.total || 0);
});

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    const value = Number(amount || 0);

    return new Intl.NumberFormat("en-ZM", {
        style: "currency",
        currency: "ZMW",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
};

/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

const statusLabel = (status) => {
    const labels = {
        pending: "Pending",
        processing: "Processing",
        completed: "Completed",
        cancelled: "Cancelled",
    };

    return labels[status] || status;
};

/*
|--------------------------------------------------------------------------
| ITEM COUNT
|--------------------------------------------------------------------------
*/

const itemCount = (order) => {
    if (!Array.isArray(order?.items)) {
        return 0;
    }

    return order.items.reduce(
        (total, item) => total + Number(item.quantity || 0),
        0
    );
};

/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {
    if (!date) {
        return "—";
    }

    try {
        return new Intl.DateTimeFormat("en-ZM", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        }).format(new Date(date));
    } catch {
        return date;
    }
};

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const paginationLinks = computed(() => {
    return Array.isArray(props.orders?.links)
        ? props.orders.links
        : [];
});
</script>

<template>
    <UserFashionLayout>
        <Head title="My Pending Orders" />

        <div class="pending-orders-page">
            <!-- =========================================================
                 PAGE HEADER
            ========================================================== -->

            <section class="page-header">
                <div class="header-inner">

                    <div class="header-left">
                        <Link
                            href="/client/orders"
                            class="back-button"
                            aria-label="Back to My Orders"
                        >
                            <ArrowLeftOutlined />
                        </Link>

                        <div class="header-icon">
                            <ClockCircleOutlined />
                        </div>

                        <div>
                            <span class="eyebrow">
                                ORDER MANAGEMENT
                            </span>

                            <h1>
                                My Pending Orders
                            </h1>

                            <p>
                                Orders that are waiting to be processed.
                            </p>
                        </div>
                    </div>

                    <div class="order-count">
                        <span class="count-number">
                            {{ totalOrders }}
                        </span>

                        <span class="count-label">
                            Pending
                        </span>
                    </div>

                </div>
            </section>

            <!-- =========================================================
                 CONTENT
            ========================================================== -->

            <main class="orders-container">

                <!-- =====================================================
                     EMPTY STATE
                ====================================================== -->

                <div
                    v-if="orderList.length === 0"
                    class="empty-state"
                >
                    <div class="empty-icon">
                        <InboxOutlined />
                    </div>

                    <h2>
                        No Pending Orders
                    </h2>

                    <p>
                        You currently don't have any orders waiting
                        to be processed.
                    </p>

                    <Link
                        href="/products"
                        class="shop-button"
                    >
                        <ShoppingOutlined />

                        Continue Shopping
                    </Link>
                </div>

                <!-- =====================================================
                     ORDERS
                ====================================================== -->

                <template v-else>

                    <div class="orders-topbar">

                        <div>
                            <h2>
                                Pending Orders
                            </h2>

                            <p>
                                Showing your orders that are awaiting processing.
                            </p>
                        </div>

                        <Link
                            href="/client/orders"
                            class="all-orders-link"
                        >
                            View All Orders
                            <RightOutlined />
                        </Link>

                    </div>

                    <!-- =================================================
                         ORDER CARDS
                    ================================================== -->

                    <div class="orders-list">

                        <article
                            v-for="order in orderList"
                            :key="order?.id || order?.order_number"
                            class="order-card"
                        >

                            <!-- =========================================
                                 CARD HEADER
                            ========================================== -->

                            <div class="order-card-header">

                                <div class="order-info">

                                    <div class="order-number">
                                        {{ order.order_number }}
                                    </div>

                                    <div class="order-date">
                                        <ClockCircleOutlined />

                                        {{ formatDate(order.created_at) }}
                                    </div>

                                </div>

                                <span class="status-badge pending">
                                    <span class="status-dot"></span>

                                    {{ statusLabel(order.status) }}
                                </span>

                            </div>

                            <!-- =========================================
                                 CARD BODY
                            ========================================== -->

                            <div class="order-card-body">

                                <div class="order-summary">

                                    <div class="summary-item">

                                        <span class="summary-label">
                                            Items
                                        </span>

                                        <span class="summary-value">
                                            {{ itemCount(order) }}
                                            {{ itemCount(order) === 1 ? "item" : "items" }}
                                        </span>

                                    </div>

                                    <div class="summary-divider"></div>

                                    <div class="summary-item">

                                        <span class="summary-label">
                                            Total
                                        </span>

                                        <span class="summary-value total">
                                            {{ formatMoney(order.total_amount) }}
                                        </span>

                                    </div>

                                </div>

                                <!-- =====================================
                                     ITEMS PREVIEW
                                ====================================== -->

                                <div
                                    v-if="Array.isArray(order.items) && order.items.length"
                                    class="items-preview"
                                >

                                    <div
                                        v-for="item in order.items.slice(0, 3)"
                                        :key="item?.id || `${order.id}-${item.quantity}`"
                                        class="item-row"
                                    >

                                        <div class="item-icon">
                                            <ShoppingOutlined />
                                        </div>

                                        <div class="item-details">

                                            <span class="item-name">
                                                {{ item.product_name || "Product" }}
                                            </span>

                                            <span class="item-meta">
                                                Qty: {{ item.quantity }}
                                            </span>

                                        </div>

                                        <span class="item-price">
                                            {{ formatMoney(item.total_price ?? item.price) }}
                                        </span>

                                    </div>

                                    <div
                                        v-if="order.items.length > 3"
                                        class="more-items"
                                    >
                                        + {{ order.items.length - 3 }} more
                                        {{ order.items.length - 3 === 1 ? "item" : "items" }}
                                    </div>

                                </div>

                            </div>

                            <!-- =========================================
                                 CARD FOOTER
                            ========================================== -->

                            <div class="order-card-footer">

                                <div class="pending-message">

                                    <ClockCircleOutlined />

                                    <span>
                                        Your order is awaiting processing.
                                    </span>

                                </div>

                                <Link
                                    :href="`/client/orders/${order.id}`"
                                    class="view-order-button"
                                >
                                    <EyeOutlined />

                                    View Order
                                </Link>

                            </div>

                        </article>

                    </div>

                    <!-- =================================================
                         PAGINATION
                    ================================================== -->

                    <div
                        v-if="paginationLinks.length > 3"
                        class="pagination"
                    >

                        <template
                            v-for="(link, index) in paginationLinks"
                            :key="index"
                        >

                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="pagination-link"
                                :class="{ active: link.active }"
                                preserve-scroll
                            >
                                <span v-html="link.label"></span>
                            </Link>

                            <span
                                v-else
                                class="pagination-link disabled"
                            >
                                <span v-html="link.label"></span>
                            </span>

                        </template>

                    </div>

                </template>

            </main>
        </div>
    </UserFashionLayout>
</template>

<style scoped>
/* ================================================================
   PAGE
================================================================ */

.pending-orders-page {
    min-height: 100vh;
    background: #f8fafc;
    color: #172033;
}

/* ================================================================
   HEADER
================================================================ */

.page-header {
    background: linear-gradient(
        135deg,
        #fff8eb 0%,
        #fff3d8 50%,
        #fce9b2 100%
    );

    border-bottom: 1px solid #eadfca;
}

.header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 42px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 18px;
}

.back-button {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.75);
    border: 1px solid rgba(0, 0, 0, 0.07);
    color: #4b5563;

    display: flex;
    align-items: center;
    justify-content: center;

    text-decoration: none;

    transition:
        transform 0.2s ease,
        background 0.2s ease,
        color 0.2s ease;
}

.back-button:hover {
    transform: translateX(-3px);
    background: #fff;
    color: #111827;
}

.header-icon {
    width: 58px;
    height: 58px;
    border-radius: 16px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #fff;
    color: #b7791f;

    font-size: 25px;

    box-shadow: 0 8px 25px rgba(120, 90, 30, 0.1);
}

.eyebrow {
    display: block;
    margin-bottom: 5px;

    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.16em;
    color: #9a6b20;
}

.header-left h1 {
    margin: 0;

    font-size: clamp(26px, 4vw, 36px);
    font-weight: 800;
    letter-spacing: -0.03em;
    color: #172033;
}

.header-left p {
    margin: 7px 0 0;
    color: #6b7280;
    font-size: 14px;
}

/* ================================================================
   ORDER COUNT
================================================================ */

.order-count {
    min-width: 105px;
    padding: 15px 20px;

    border-radius: 16px;

    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(183, 121, 31, 0.12);

    text-align: center;
}

.count-number {
    display: block;

    font-size: 25px;
    line-height: 1;
    font-weight: 800;

    color: #a16207;
}

.count-label {
    display: block;
    margin-top: 6px;

    font-size: 12px;
    font-weight: 600;

    color: #78716c;
}

/* ================================================================
   CONTAINER
================================================================ */

.orders-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 38px 24px 70px;
}

/* ================================================================
   TOPBAR
================================================================ */

.orders-topbar {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;

    margin-bottom: 22px;
}

.orders-topbar h2 {
    margin: 0;

    font-size: 21px;
    font-weight: 750;
    color: #172033;
}

.orders-topbar p {
    margin: 6px 0 0;

    font-size: 13px;
    color: #737b89;
}

.all-orders-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    color: #9a6b20;

    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    transition: gap 0.2s ease;
}

.all-orders-link:hover {
    gap: 12px;
}

/* ================================================================
   ORDER LIST
================================================================ */

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* ================================================================
   ORDER CARD
================================================================ */

.order-card {
    overflow: hidden;

    background: #fff;

    border: 1px solid #e8ebf0;
    border-radius: 18px;

    box-shadow:
        0 5px 20px rgba(15, 23, 42, 0.035);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        border-color 0.25s ease;
}

.order-card:hover {
    transform: translateY(-2px);

    border-color: #e0d4bc;

    box-shadow:
        0 14px 35px rgba(15, 23, 42, 0.08);
}

/* ================================================================
   CARD HEADER
================================================================ */

.order-card-header {
    padding: 20px 22px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    border-bottom: 1px solid #eef0f3;
}

.order-number {
    font-size: 15px;
    font-weight: 750;
    color: #1f2937;
}

.order-date {
    margin-top: 7px;

    display: flex;
    align-items: center;
    gap: 6px;

    font-size: 12px;
    color: #8a919d;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 7px 11px;

    border-radius: 999px;

    font-size: 11px;
    font-weight: 750;

    white-space: nowrap;
}

.status-badge.pending {
    color: #a16207;
    background: #fef3c7;
}

.status-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;
    background: currentColor;
}

/* ================================================================
   CARD BODY
================================================================ */

.order-card-body {
    padding: 20px 22px;
}

.order-summary {
    display: flex;
    align-items: center;
    gap: 28px;

    margin-bottom: 18px;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.summary-label {
    font-size: 11px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.summary-value {
    font-size: 14px;
    font-weight: 700;
    color: #374151;
}

.summary-value.total {
    color: #111827;
    font-size: 16px;
}

.summary-divider {
    width: 1px;
    height: 34px;
    background: #e5e7eb;
}

/* ================================================================
   ITEMS
================================================================ */

.items-preview {
    display: flex;
    flex-direction: column;

    border: 1px solid #eef0f3;
    border-radius: 12px;
    overflow: hidden;
}

.item-row {
    min-height: 58px;
    padding: 10px 13px;

    display: flex;
    align-items: center;
    gap: 12px;

    border-bottom: 1px solid #f0f1f3;
}

.item-row:last-child {
    border-bottom: none;
}

.item-icon {
    width: 35px;
    height: 35px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #faf7f0;
    color: #a16207;

    font-size: 15px;
}

.item-details {
    flex: 1;
    min-width: 0;

    display: flex;
    flex-direction: column;
    gap: 3px;
}

.item-name {
    overflow: hidden;

    color: #374151;

    font-size: 13px;
    font-weight: 650;

    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-meta {
    font-size: 11px;
    color: #9ca3af;
}

.item-price {
    font-size: 12px;
    font-weight: 700;
    color: #374151;
}

.more-items {
    padding: 9px 13px;

    background: #fafafa;

    color: #8b929e;

    font-size: 11px;
    font-weight: 600;
}

/* ================================================================
   FOOTER
================================================================ */

.order-card-footer {
    min-height: 64px;
    padding: 13px 22px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;

    background: #fafbfc;

    border-top: 1px solid #eef0f3;
}

.pending-message {
    display: flex;
    align-items: center;
    gap: 8px;

    color: #8a6a28;

    font-size: 12px;
    font-weight: 600;
}

.view-order-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 10px 16px;

    border-radius: 10px;

    background: #172033;
    color: #fff;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;

    transition:
        transform 0.2s ease,
        background 0.2s ease,
        box-shadow 0.2s ease;
}

.view-order-button:hover {
    transform: translateY(-1px);

    background: #0f172a;

    box-shadow:
        0 7px 18px rgba(15, 23, 42, 0.16);
}

/* ================================================================
   EMPTY STATE
================================================================ */

.empty-state {
    padding: 75px 25px;

    display: flex;
    flex-direction: column;
    align-items: center;

    text-align: center;

    background: #fff;

    border: 1px solid #e8ebf0;
    border-radius: 20px;

    box-shadow:
        0 5px 20px rgba(15, 23, 42, 0.035);
}

.empty-icon {
    width: 72px;
    height: 72px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 20px;

    background: #fff7e6;
    color: #b7791f;

    font-size: 30px;

    margin-bottom: 20px;
}

.empty-state h2 {
    margin: 0;

    font-size: 22px;
    font-weight: 750;
    color: #172033;
}

.empty-state p {
    max-width: 430px;

    margin: 9px 0 23px;

    font-size: 14px;
    line-height: 1.7;

    color: #737b89;
}

.shop-button {
    display: inline-flex;
    align-items: center;
    gap: 9px;

    padding: 12px 19px;

    border-radius: 11px;

    background: #172033;
    color: #fff;

    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    transition:
        transform 0.2s ease,
        background 0.2s ease;
}

.shop-button:hover {
    transform: translateY(-1px);
    background: #0f172a;
}

/* ================================================================
   PAGINATION
================================================================ */

.pagination {
    margin-top: 30px;

    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
}

.pagination-link {
    min-width: 36px;
    height: 36px;
    padding: 0 11px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #e1e5ea;
    border-radius: 9px;

    background: #fff;
    color: #596273;

    font-size: 12px;
    font-weight: 650;

    text-decoration: none;

    transition:
        background 0.2s ease,
        color 0.2s ease,
        border-color 0.2s ease;
}

.pagination-link:hover:not(.disabled) {
    border-color: #d6c39d;
    color: #9a6b20;
}

.pagination-link.active {
    border-color: #172033;
    background: #172033;
    color: #fff;
}

.pagination-link.disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 768px) {

    .header-inner {
        padding: 30px 18px;

        align-items: flex-start;
    }

    .header-left {
        align-items: flex-start;
    }

    .header-icon {
        width: 48px;
        height: 48px;

        flex-shrink: 0;

        font-size: 20px;
    }

    .back-button {
        display: none;
    }

    .header-left h1 {
        font-size: 25px;
    }

    .order-count {
        min-width: 80px;
        padding: 12px;
    }

    .orders-container {
        padding: 25px 16px 50px;
    }

    .orders-topbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .order-card-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
    }

    .order-card-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .view-order-button {
        width: 100%;
    }

    .pending-message {
        justify-content: center;
    }
}

@media (max-width: 480px) {

    .header-inner {
        padding: 25px 15px;
    }

    .header-left {
        gap: 11px;
    }

    .header-icon {
        display: none;
    }

    .eyebrow {
        font-size: 9px;
    }

    .header-left h1 {
        font-size: 22px;
    }

    .header-left p {
        font-size: 12px;
    }

    .order-count {
        min-width: 68px;
        padding: 10px;
    }

    .count-number {
        font-size: 20px;
    }

    .count-label {
        font-size: 10px;
    }

    .order-card-body {
        padding: 16px;
    }

    .order-card-header {
        padding: 16px;
    }

    .order-card-footer {
        padding: 13px 16px;
    }

    .order-summary {
        gap: 18px;
    }
}
</style>