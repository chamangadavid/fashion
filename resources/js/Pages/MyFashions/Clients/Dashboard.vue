<!-- resources\js\Pages\MyFashions\Clients\Dashboard.vue -->
<!-- resources/js/Pages/MyFashions/Clients/Dashboard.vue -->

<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    ShoppingOutlined,
    ShoppingCartOutlined,
    ClockCircleOutlined,
    CheckCircleOutlined,
    ArrowRightOutlined,
    WalletOutlined,
    AppstoreOutlined,
    GiftOutlined,
    RightOutlined,
    CalendarOutlined,
    InboxOutlined,
} from "@ant-design/icons-vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({
            user: null,
        }),
    },

    cart: {
        type: Object,
        default: () => ({
            items: [],
            item_count: 0,
            subtotal: 0,
            shipping: 0,
            total: 0,
        }),
    },

    orders: {
        type: Array,
        default: () => [],
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
});

/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

const user = computed(() => props.auth?.user ?? {});

const userName = computed(() => {
    return user.value?.name || "Customer";
});

/*
|--------------------------------------------------------------------------
| GREETING
|--------------------------------------------------------------------------
*/

const greeting = computed(() => {
    const hour = new Date().getHours();

    if (hour < 12) {
        return "Good morning";
    }

    if (hour < 18) {
        return "Good afternoon";
    }

    return "Good evening";
});

/*
|--------------------------------------------------------------------------
| CURRENCY
|--------------------------------------------------------------------------
*/

const currency = (value) => {
    return Number(value || 0).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

/*
|--------------------------------------------------------------------------
| CART COUNT
|--------------------------------------------------------------------------
*/

const cartCount = computed(() => {
    return Number(props.cart?.item_count || 0);
});

/*
|--------------------------------------------------------------------------
| CART TOTAL
|--------------------------------------------------------------------------
*/

const cartTotal = computed(() => {
    return currency(props.cart?.total || 0);
});

/*
|--------------------------------------------------------------------------
| TOTAL SPENT
|--------------------------------------------------------------------------
*/

const totalSpent = computed(() => {
    return currency(props.stats?.total_spent || 0);
});

/*
|--------------------------------------------------------------------------
| RECENT ORDERS
|--------------------------------------------------------------------------
*/

const recentOrders = computed(() => {
    return props.orders || [];
});

/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const statusClass = (status) => {
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
| STATUS LABEL
|--------------------------------------------------------------------------
*/

const statusLabel = (status) => {
    if (!status) {
        return "Unknown";
    }

    return String(status)
        .replace(/_/g, " ")
        .replace(/\b\w/g, (letter) => letter.toUpperCase());
};

/*
|--------------------------------------------------------------------------
| STATUS ICON
|--------------------------------------------------------------------------
*/

const statusIcon = (status) => {
    switch (String(status || "").toLowerCase()) {
        case "pending":
            return ClockCircleOutlined;

        case "processing":
            return InboxOutlined;

        case "completed":
        case "delivered":
            return CheckCircleOutlined;

        default:
            return ClockCircleOutlined;
    }
};
</script>

<template>
    <Head title="My Dashboard" />

    <UserFashionLayout
        :cart-count="cartCount"
        :total-orders="stats.total_orders"
        :pending-orders="stats.pending_orders"
        :cart-total="cart.total"
    >
        <!-- =========================================================
             TITLE
        ========================================================== -->

        <template #title>
            My Dashboard
        </template>

        <template #subtitle>
            {{ greeting }}, {{ userName }}. Here's what's happening
            with your account.
        </template>

        <!-- =========================================================
             WELCOME BANNER
        ========================================================== -->

        <section class="welcome-banner">
            <div class="welcome-content">
                <div class="welcome-label">
                    <span class="welcome-dot"></span>
                    CUSTOMER ACCOUNT
                </div>

                <h2>
                    Welcome back,
                    <strong>{{ userName }}</strong>
                </h2>

                <p>
                    Manage your orders, explore our latest fashion,
                    and continue shopping from your account.
                </p>

                <div class="welcome-actions">
                    <Link
                        href="/my-shop"
                        class="primary-button"
                    >
                        <AppstoreOutlined />
                        Continue Shopping
                        <ArrowRightOutlined />
                    </Link>

                    <Link
                        href="/my-orders"
                        class="secondary-button"
                    >
                        View My Orders
                    </Link>
                </div>
            </div>

            <div class="welcome-decoration">
                <div class="decoration-circle circle-one"></div>
                <div class="decoration-circle circle-two"></div>

                <div class="fashion-icon">
                    <GiftOutlined />
                </div>
            </div>
        </section>

        <!-- =========================================================
             STATISTICS
        ========================================================== -->

        <section class="stats-grid">

            <!-- TOTAL ORDERS -->

            <Link
                href="/my-orders"
                class="stat-card"
            >
                <div class="stat-icon stat-orders">
                    <ShoppingOutlined />
                </div>

                <div class="stat-content">
                    <span>Total Orders</span>

                    <strong>
                        {{ stats.total_orders }}
                    </strong>

                    <small>
                        All your orders
                    </small>
                </div>

                <RightOutlined class="stat-arrow" />
            </Link>

            <!-- PENDING -->

            <Link
                href="/my-orders/pending"
                class="stat-card"
            >
                <div class="stat-icon stat-pending">
                    <ClockCircleOutlined />
                </div>

                <div class="stat-content">
                    <span>Pending Orders</span>

                    <strong>
                        {{ stats.pending_orders }}
                    </strong>

                    <small>
                        Awaiting processing
                    </small>
                </div>

                <RightOutlined class="stat-arrow" />
            </Link>

            <!-- COMPLETED -->

            <Link
                href="/my-orders/completed"
                class="stat-card"
            >
                <div class="stat-icon stat-completed">
                    <CheckCircleOutlined />
                </div>

                <div class="stat-content">
                    <span>Completed Orders</span>

                    <strong>
                        {{ stats.completed_orders }}
                    </strong>

                    <small>
                        Successfully delivered
                    </small>
                </div>

                <RightOutlined class="stat-arrow" />
            </Link>

            <!-- TOTAL SPENT -->

            <div class="stat-card">
                <div class="stat-icon stat-spent">
                    <WalletOutlined />
                </div>

                <div class="stat-content">
                    <span>Total Spent</span>

                    <strong>
                        K{{ totalSpent }}
                    </strong>

                    <small>
                        Across your orders
                    </small>
                </div>
            </div>
        </section>

        <!-- =========================================================
             MAIN GRID
        ========================================================== -->

        <section class="dashboard-grid">

            <!-- =====================================================
                 RECENT ORDERS
            ====================================================== -->

            <div class="dashboard-card orders-card">

                <div class="card-header">
                    <div>
                        <span class="card-eyebrow">
                            ORDER ACTIVITY
                        </span>

                        <h3>
                            Recent Orders
                        </h3>
                    </div>

                    <Link
                        href="/my-orders"
                        class="view-all-link"
                    >
                        View All
                        <ArrowRightOutlined />
                    </Link>
                </div>

                <!-- ORDERS -->

                <div
                    v-if="recentOrders.length"
                    class="orders-list"
                >
                    <Link
                        v-for="order in recentOrders"
                        :key="order.id"
                        :href="`/my-orders/${order.id}`"
                        class="order-row"
                    >
                        <div class="order-main">

                            <div class="order-icon">
                                <ShoppingOutlined />
                            </div>

                            <div class="order-information">
                                <strong>
                                    {{
                                        order.order_number ||
                                        `Order #${order.id}`
                                    }}
                                </strong>

                                <span>
                                    <CalendarOutlined />

                                    {{ order.created_at }}
                                </span>
                            </div>
                        </div>

                        <div class="order-middle">
                            <span class="items-count">
                                {{ order.items_count || 0 }}
                                item{{
                                    Number(order.items_count) === 1
                                        ? ""
                                        : "s"
                                }}
                            </span>
                        </div>

                        <div class="order-right">

                            <strong class="order-total">
                                K{{ currency(order.total) }}
                            </strong>

                            <span
                                class="order-status"
                                :class="statusClass(order.status)"
                            >
                                <component
                                    :is="statusIcon(order.status)"
                                />

                                {{ statusLabel(order.status) }}
                            </span>
                        </div>

                        <RightOutlined class="order-arrow" />
                    </Link>
                </div>

                <!-- EMPTY ORDERS -->

                <div
                    v-else
                    class="empty-state"
                >
                    <div class="empty-icon">
                        <ShoppingOutlined />
                    </div>

                    <h4>
                        No orders yet
                    </h4>

                    <p>
                        You haven't placed an order yet.
                        Start exploring our collection.
                    </p>

                    <Link
                        href="/my-shop"
                        class="empty-button"
                    >
                        Start Shopping
                        <ArrowRightOutlined />
                    </Link>
                </div>
            </div>

            <!-- =====================================================
                 CART
            ====================================================== -->

            <div class="dashboard-card cart-dashboard-card">

                <div class="card-header">
                    <div>
                        <span class="card-eyebrow">
                            SHOPPING
                        </span>

                        <h3>
                            My Cart
                        </h3>
                    </div>

                    <div class="cart-header-icon">
                        <ShoppingCartOutlined />
                    </div>
                </div>

                <div class="cart-summary">

                    <div class="cart-large-icon">
                        <ShoppingCartOutlined />
                    </div>

                    <div class="cart-summary-info">
                        <span>
                            Items in your cart
                        </span>

                        <strong>
                            {{ cartCount }}
                        </strong>
                    </div>
                </div>

                <div class="cart-total-box">
                    <span>
                        Cart Total
                    </span>

                    <strong>
                        K{{ cartTotal }}
                    </strong>
                </div>

                <Link
                    href="/my-cart"
                    class="cart-button"
                >
                    View Shopping Cart
                    <ArrowRightOutlined />
                </Link>

                <Link
                    href="/my-shop"
                    class="continue-shopping"
                >
                    Continue Shopping
                    <ArrowRightOutlined />
                </Link>
            </div>
        </section>

        <!-- =========================================================
             QUICK ACTIONS
        ========================================================== -->

        <section class="quick-section">

            <div class="section-heading">
                <div>
                    <span>
                        QUICK ACCESS
                    </span>

                    <h3>
                        What would you like to do?
                    </h3>
                </div>
            </div>

            <div class="quick-grid">

                <Link
                    href="/my-shop"
                    class="quick-card"
                >
                    <div class="quick-icon">
                        <AppstoreOutlined />
                    </div>

                    <div>
                        <strong>
                            Browse Shop
                        </strong>

                        <span>
                            Explore our latest fashion
                        </span>
                    </div>

                    <ArrowRightOutlined />
                </Link>

                <Link
                    href="/my-orders"
                    class="quick-card"
                >
                    <div class="quick-icon">
                        <ShoppingOutlined />
                    </div>

                    <div>
                        <strong>
                            Track Orders
                        </strong>

                        <span>
                            Check your order status
                        </span>
                    </div>

                    <ArrowRightOutlined />
                </Link>

                <Link
                    href="/payment-history"
                    class="quick-card"
                >
                    <div class="quick-icon">
                        <WalletOutlined />
                    </div>

                    <div>
                        <strong>
                            Payments
                        </strong>

                        <span>
                            View your payment history
                        </span>
                    </div>

                    <ArrowRightOutlined />
                </Link>

                <Link
                    href="/profile"
                    class="quick-card"
                >
                    <div class="quick-icon">
                        <InboxOutlined />
                    </div>

                    <div>
                        <strong>
                            My Account
                        </strong>

                        <span>
                            Manage your profile
                        </span>
                    </div>

                    <ArrowRightOutlined />
                </Link>
            </div>
        </section>
    </UserFashionLayout>
</template>

<style scoped>

/* ================================================================
   DASHBOARD
================================================================ */

.dashboard-content {
    max-width: 1500px;
    margin: 0 auto;
}

/* ================================================================
   WELCOME BANNER
================================================================ */

.welcome-banner {
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    justify-content: space-between;

    min-height: 245px;

    padding: 34px 40px;

    border-radius: 18px;

    background:
        linear-gradient(
            135deg,
            #111827 0%,
            #1f2937 55%,
            #374151 100%
        );

    color: #ffffff;

    box-shadow:
        0 15px 40px rgba(17, 24, 39, 0.12);
}

.welcome-content {
    position: relative;
    z-index: 2;

    max-width: 720px;
}

.welcome-label {
    display: flex;
    align-items: center;
    gap: 8px;

    margin-bottom: 12px;

    color: #a7f3d0;

    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.8px;
}

.welcome-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: #34d399;

    box-shadow:
        0 0 0 5px rgba(52, 211, 153, 0.12);
}

.welcome-banner h2 {
    margin: 0;

    font-size: 31px;
    line-height: 1.2;
    font-weight: 400;
}

.welcome-banner h2 strong {
    font-weight: 800;
}

.welcome-banner p {
    max-width: 600px;

    margin: 12px 0 22px;

    color: #d1d5db;

    font-size: 13px;
    line-height: 1.7;
}

.welcome-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.primary-button,
.secondary-button {
    min-height: 40px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 0 16px;

    border-radius: 9px;

    font-size: 11px;
    font-weight: 700;

    text-decoration: none;

    transition: all 0.2s ease;
}

.primary-button {
    background: #ffffff;
    color: #111827;
}

.primary-button:hover {
    transform: translateY(-2px);

    background: #f0fdfa;

    color: #0f766e;

    box-shadow:
        0 8px 20px rgba(0, 0, 0, 0.15);
}

.secondary-button {
    border: 1px solid rgba(255, 255, 255, 0.18);

    background: rgba(255, 255, 255, 0.08);

    color: #ffffff;
}

.secondary-button:hover {
    background: rgba(255, 255, 255, 0.14);
    color: #ffffff;
}

/* ================================================================
   DECORATION
================================================================ */

.welcome-decoration {
    position: absolute;

    right: 0;
    top: 0;

    width: 400px;
    height: 100%;
}

.decoration-circle {
    position: absolute;

    border-radius: 50%;

    border: 1px solid rgba(255, 255, 255, 0.08);
}

.circle-one {
    width: 330px;
    height: 330px;

    right: -100px;
    top: -50px;
}

.circle-two {
    width: 210px;
    height: 210px;

    right: 30px;
    top: 18px;
}

.fashion-icon {
    position: absolute;

    right: 100px;
    top: 50%;

    width: 75px;
    height: 75px;

    display: flex;
    align-items: center;
    justify-content: center;

    transform: translateY(-50%);

    border: 1px solid rgba(255, 255, 255, 0.15);

    border-radius: 20px;

    background: rgba(255, 255, 255, 0.08);

    color: #ffffff;

    font-size: 30px;

    backdrop-filter: blur(10px);
}

/* ================================================================
   STATISTICS
================================================================ */

.stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 15px;

    margin-top: 18px;
}

.stat-card {
    position: relative;

    min-height: 115px;

    display: flex;
    align-items: center;

    gap: 13px;

    padding: 18px;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

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

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.06);
}

.stat-icon {
    width: 45px;
    height: 45px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    font-size: 19px;
}

.stat-orders {
    background: #f3f4f6;
    color: #374151;
}

.stat-pending {
    background: #fff7ed;
    color: #ea580c;
}

.stat-completed {
    background: #f0fdf4;
    color: #16a34a;
}

.stat-spent {
    background: #f0fdfa;
    color: #0f766e;
}

.stat-content {
    min-width: 0;
    flex: 1;
}

.stat-content span {
    display: block;

    color: #9ca3af;

    font-size: 10px;
    font-weight: 600;
}

.stat-content strong {
    display: block;

    margin-top: 4px;

    color: #111827;

    font-size: 21px;
    font-weight: 800;
}

.stat-content small {
    display: block;

    margin-top: 3px;

    color: #9ca3af;

    font-size: 9px;
}

.stat-arrow {
    color: #d1d5db;
    font-size: 10px;
}

/* ================================================================
   MAIN GRID
================================================================ */

.dashboard-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1.65fr)
        minmax(300px, 0.8fr);

    gap: 18px;

    margin-top: 18px;
}

.dashboard-card {
    border: 1px solid #e5e7eb;

    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 2px 8px rgba(0, 0, 0, 0.02);
}

.orders-card {
    min-width: 0;
}

.card-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    padding: 21px 22px;

    border-bottom: 1px solid #f0f0f0;
}

.card-eyebrow {
    display: block;

    margin-bottom: 4px;

    color: #9ca3af;

    font-size: 8px;
    font-weight: 700;
    letter-spacing: 1.5px;
}

.card-header h3 {
    margin: 0;

    color: #111827;

    font-size: 16px;
    font-weight: 750;
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    color: #0f766e;

    font-size: 10px;
    font-weight: 700;

    text-decoration: none;
}

.view-all-link:hover {
    color: #115e59;
}

/* ================================================================
   ORDERS LIST
================================================================ */

.orders-list {
    padding: 4px 22px 10px;
}

.order-row {
    position: relative;

    display: grid;

    grid-template-columns: minmax(180px, 1.5fr) 100px minmax(150px, 0.9fr) 12px;

    align-items: center;

    gap: 15px;

    min-height: 77px;

    border-bottom: 1px solid #f3f4f6;

    text-decoration: none;

    transition: all 0.2s ease;
}

.order-row:last-child {
    border-bottom: none;
}

.order-row:hover {
    padding-left: 5px;
}

.order-main {
    display: flex;
    align-items: center;
    gap: 11px;

    min-width: 0;
}

.order-icon {
    width: 38px;
    height: 38px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #f8fafc;

    color: #64748b;

    font-size: 15px;
}

.order-information {
    min-width: 0;
}

.order-information strong {
    display: block;

    overflow: hidden;

    color: #111827;

    font-size: 11px;
    font-weight: 700;

    white-space: nowrap;
    text-overflow: ellipsis;
}

.order-information span {
    display: flex;
    align-items: center;
    gap: 5px;

    margin-top: 5px;

    color: #9ca3af;

    font-size: 9px;
}

.items-count {
    color: #9ca3af;

    font-size: 9px;
}

.order-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;

    gap: 6px;
}

.order-total {
    color: #111827;

    font-size: 11px;
    font-weight: 750;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;

    padding: 4px 7px;

    border-radius: 20px;

    font-size: 8px;
    font-weight: 700;
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

.order-arrow {
    color: #d1d5db;
    font-size: 9px;
}

/* ================================================================
   EMPTY STATE
================================================================ */

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    padding: 55px 25px;

    text-align: center;
}

.empty-icon {
    width: 55px;
    height: 55px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #f8fafc;

    color: #9ca3af;

    font-size: 21px;
}

.empty-state h4 {
    margin: 14px 0 5px;

    color: #111827;

    font-size: 14px;
}

.empty-state p {
    max-width: 330px;

    margin: 0;

    color: #9ca3af;

    font-size: 10px;
    line-height: 1.6;
}

.empty-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    margin-top: 15px;

    padding: 9px 13px;

    border-radius: 8px;

    background: #111827;

    color: #ffffff;

    font-size: 10px;
    font-weight: 700;

    text-decoration: none;
}

/* ================================================================
   CART CARD
================================================================ */

.cart-dashboard-card {
    padding-bottom: 20px;
}

.cart-header-icon {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #f0fdfa;

    color: #0f766e;

    font-size: 15px;
}

.cart-summary {
    display: flex;
    align-items: center;
    gap: 13px;

    padding: 24px 22px 18px;
}

.cart-large-icon {
    width: 52px;
    height: 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: #f0fdfa;

    color: #0f766e;

    font-size: 22px;
}

.cart-summary-info span {
    display: block;

    color: #9ca3af;

    font-size: 10px;
}

.cart-summary-info strong {
    display: block;

    margin-top: 4px;

    color: #111827;

    font-size: 25px;
    font-weight: 800;
}

.cart-total-box {
    display: flex;
    align-items: center;
    justify-content: space-between;

    margin: 0 22px;

    padding: 14px;

    border: 1px solid #f0f0f0;

    border-radius: 10px;

    background: #fafafa;
}

.cart-total-box span {
    color: #6b7280;

    font-size: 10px;
}

.cart-total-box strong {
    color: #111827;

    font-size: 14px;
}

.cart-button {
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    margin: 14px 22px 0;

    border-radius: 9px;

    background: #111827;

    color: #ffffff;

    font-size: 10px;
    font-weight: 700;

    text-decoration: none;

    transition: all 0.2s ease;
}

.cart-button:hover {
    background: #374151;
    color: #ffffff;
}

.continue-shopping {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    margin-top: 11px;

    color: #0f766e;

    font-size: 9px;
    font-weight: 700;

    text-decoration: none;
}

/* ================================================================
   QUICK ACCESS
================================================================ */

.quick-section {
    margin-top: 25px;
}

.section-heading span {
    color: #9ca3af;

    font-size: 8px;
    font-weight: 700;
    letter-spacing: 1.5px;
}

.section-heading h3 {
    margin: 4px 0 13px;

    color: #111827;

    font-size: 16px;
    font-weight: 750;
}

.quick-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 12px;
}

.quick-card {
    display: flex;
    align-items: center;

    gap: 10px;

    min-height: 70px;

    padding: 12px;

    border: 1px solid #e5e7eb;

    border-radius: 12px;

    background: #ffffff;

    color: #111827;

    text-decoration: none;

    transition: all 0.2s ease;
}

.quick-card:hover {
    transform: translateY(-2px);

    border-color: #d1d5db;

    box-shadow:
        0 8px 20px rgba(0, 0, 0, 0.05);
}

.quick-icon {
    width: 36px;
    height: 36px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #f8fafc;

    color: #0f766e;

    font-size: 15px;
}

.quick-card div:nth-child(2) {
    min-width: 0;
    flex: 1;
}

.quick-card strong {
    display: block;

    color: #111827;

    font-size: 10px;
}

.quick-card span {
    display: block;

    margin-top: 3px;

    overflow: hidden;

    color: #9ca3af;

    font-size: 8px;

    white-space: nowrap;
    text-overflow: ellipsis;
}

.quick-card > .anticon {
    color: #d1d5db;

    font-size: 9px;
}

/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .quick-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 1000px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .welcome-decoration {
        opacity: 0.45;
    }
}

@media (max-width: 700px) {
    .welcome-banner {
        min-height: auto;

        padding: 27px 22px;
    }

    .welcome-banner h2 {
        font-size: 24px;
    }

    .welcome-banner p {
        font-size: 11px;
    }

    .welcome-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .primary-button,
    .secondary-button {
        width: 100%;
    }

    .welcome-decoration {
        display: none;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .quick-grid {
        grid-template-columns: 1fr;
    }

    .order-row {
        grid-template-columns: 1fr auto;
        gap: 8px;

        padding: 12px 0;
    }

    .order-middle {
        display: none;
    }

    .order-right {
        grid-column: 2;
        grid-row: 1;

        align-items: flex-end;
    }

    .order-main {
        grid-column: 1;
        grid-row: 1;
    }

    .order-arrow {
        display: none;
    }
}

@media (max-width: 500px) {
    .card-header {
        padding: 17px;
    }

    .orders-list {
        padding-left: 17px;
        padding-right: 17px;
    }

    .cart-summary {
        padding-left: 17px;
        padding-right: 17px;
    }

    .cart-total-box {
        margin-left: 17px;
        margin-right: 17px;
    }

    .cart-button {
        margin-left: 17px;
        margin-right: 17px;
    }
}
</style>