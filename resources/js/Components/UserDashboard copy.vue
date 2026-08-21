<!-- Resources/js/Pages/UserDashboard.vue -->

<script setup>

import { Head, Link } from '@inertiajs/vue3';

import { computed } from 'vue';

import {
    ShoppingCartOutlined,
    ShoppingOutlined,
    CreditCardOutlined,
    FileTextOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    GiftOutlined,
    ArrowRightOutlined,
    EyeOutlined,
    DeleteOutlined,
    PlusOutlined,
    MinusOutlined,
    UserOutlined,
    HeartOutlined,
    WalletOutlined,
    EnvironmentOutlined,
    RightOutlined,
    ExclamationCircleOutlined,
} from '@ant-design/icons-vue';


const props = defineProps({

    auth: Object,

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


const currency = (amount) => {

    return new Intl.NumberFormat('en-ZM', {

        style: 'currency',

        currency: 'ZMW',

        minimumFractionDigits: 2,

    }).format(Number(amount || 0));

};


const cartItemCount = computed(() => {

    if (props.cart?.item_count !== undefined) {

        return props.cart.item_count;

    }

    return props.cart?.items?.reduce(

        (total, item) => total + Number(item.quantity || 0),

        0

    );

});


const cartIsEmpty = computed(() => {

    return !props.cart?.items?.length;

});


const orderStatusClass = (status) => {

    const classes = {

        pending: 'status-pending',

        processing: 'status-processing',

        shipped: 'status-shipped',

        delivered: 'status-delivered',

        completed: 'status-completed',

        cancelled: 'status-cancelled',

    };

    return classes[String(status || '').toLowerCase()] || 'status-pending';

};


const orderStatusIcon = (status) => {

    const value = String(status || '').toLowerCase();

    if (value === 'delivered' || value === 'completed') {

        return CheckCircleOutlined;

    }

    if (value === 'shipped') {

        return ShoppingOutlined;

    }

    return ClockCircleOutlined;

};

</script>


<template>

    <Head title="My Dashboard" />

    <div class="dashboard-page">

        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="dashboard-header">

            <div>

                <p class="welcome-label">
                    Welcome back
                </p>

                <h2 class="dashboard-title">
                    {{ auth?.user?.name || 'My Dashboard' }}
                </h2>

                <p class="dashboard-subtitle">
                    Manage your orders, cart and payments from one place.
                </p>

            </div>

        </div>


        <!-- =====================================================
             SUMMARY CARDS
        ====================================================== -->

        <div class="stats-grid">

            <!-- Cart -->

            <div class="stat-card cart-stat">

                <div class="stat-icon">
                    <ShoppingCartOutlined />
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        My Cart
                    </span>

                    <strong class="stat-value">
                        {{ cartItemCount }}
                    </strong>

                    <span class="stat-description">
                        Items waiting for checkout
                    </span>

                </div>

                <Link
                    href="/cart"
                    class="stat-arrow"
                >
                    <ArrowRightOutlined />
                </Link>

            </div>


            <!-- Orders -->

            <div class="stat-card order-stat">

                <div class="stat-icon">
                    <ShoppingOutlined />
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Total Orders
                    </span>

                    <strong class="stat-value">
                        {{ stats.total_orders || 0 }}
                    </strong>

                    <span class="stat-description">
                        Orders placed
                    </span>

                </div>

                <Link
                    href="/orders"
                    class="stat-arrow"
                >
                    <ArrowRightOutlined />
                </Link>

            </div>


            <!-- Pending -->

            <div class="stat-card pending-stat">

                <div class="stat-icon">
                    <ClockCircleOutlined />
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Pending Orders
                    </span>

                    <strong class="stat-value">
                        {{ stats.pending_orders || 0 }}
                    </strong>

                    <span class="stat-description">
                        Currently processing
                    </span>

                </div>

                <Link
                    href="/orders"
                    class="stat-arrow"
                >
                    <ArrowRightOutlined />
                </Link>

            </div>


            <!-- Spent -->

            <div class="stat-card payment-stat">

                <div class="stat-icon">
                    <WalletOutlined />
                </div>

                <div class="stat-content">

                    <span class="stat-label">
                        Total Spent
                    </span>

                    <strong class="stat-value">
                        {{ currency(stats.total_spent) }}
                    </strong>

                    <span class="stat-description">
                        Across all orders
                    </span>

                </div>

                <Link
                    href="/payments"
                    class="stat-arrow"
                >
                    <ArrowRightOutlined />
                </Link>

            </div>

        </div>


        <!-- =====================================================
             MAIN CONTENT
        ====================================================== -->

        <div class="main-grid">

            <!-- =================================================
                 CART
            ================================================== -->

            <div class="dashboard-card cart-card">

                <div class="card-header">

                    <div class="header-title">

                        <div class="header-icon">
                            <ShoppingCartOutlined />
                        </div>

                        <div>

                            <h3>
                                Your Shopping Cart
                            </h3>

                            <p>
                                {{ cartItemCount }}
                                {{ cartItemCount === 1 ? 'item' : 'items' }}
                                in your cart
                            </p>

                        </div>

                    </div>

                    <Link
                        href="/cart"
                        class="view-link"
                    >
                        View Cart
                        <ArrowRightOutlined />
                    </Link>

                </div>


                <!-- Empty Cart -->

                <div
                    v-if="cartIsEmpty"
                    class="empty-cart"
                >

                    <div class="empty-cart-icon">
                        <ShoppingCartOutlined />
                    </div>

                    <h4>
                        Your cart is empty
                    </h4>

                    <p>
                        You haven't added any products to your cart yet.
                    </p>

                    <Link
                        href="/products"
                        class="shop-button"
                    >
                        <ShoppingOutlined />
                        Start Shopping
                    </Link>

                </div>


                <!-- Cart Items -->

                <div
                    v-else
                    class="cart-content"
                >

                    <div class="cart-items">

                        <div
                            v-for="item in cart.items"
                            :key="item.id"
                            class="cart-item"
                        >

                            <!-- Product Image -->

                            <div class="product-image-wrapper">

                                <img
                                    v-if="item.image"
                                    :src="item.image"
                                    :alt="item.name"
                                    class="product-image"
                                />

                                <div
                                    v-else
                                    class="product-placeholder"
                                >
                                    <ShoppingOutlined />
                                </div>

                            </div>


                            <!-- Product Information -->

                            <div class="product-details">

                                <h4>
                                    {{ item.name }}
                                </h4>

                                <p
                                    v-if="item.variant"
                                    class="product-variant"
                                >
                                    {{ item.variant }}
                                </p>

                                <span class="product-price">
                                    {{ currency(item.price) }}
                                </span>

                            </div>


                            <!-- Quantity -->

                            <div class="quantity-control">

                                <button
                                    type="button"
                                    class="quantity-button"
                                >
                                    <MinusOutlined />
                                </button>

                                <span>
                                    {{ item.quantity }}
                                </span>

                                <button
                                    type="button"
                                    class="quantity-button"
                                >
                                    <PlusOutlined />
                                </button>

                            </div>


                            <!-- Total -->

                            <div class="item-total">
                                {{ currency(item.price * item.quantity) }}
                            </div>

                        </div>

                    </div>


                    <!-- Cart Summary -->

                    <div class="cart-summary">

                        <div class="summary-row">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                {{ currency(cart.subtotal) }}
                            </strong>

                        </div>

                        <div class="summary-row">

                            <span>
                                Shipping
                            </span>

                            <strong>
                                {{
                                    cart.shipping
                                        ? currency(cart.shipping)
                                        : 'Free'
                                }}
                            </strong>

                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row total-row">

                            <span>
                                Total
                            </span>

                            <strong>
                                {{ currency(cart.total) }}
                            </strong>

                        </div>

                        <Link
                            href="/checkout"
                            class="checkout-button"
                        >
                            <CreditCardOutlined />
                            Proceed to Checkout
                            <ArrowRightOutlined />
                        </Link>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 ORDER TRACKING
            ================================================== -->

            <div class="dashboard-card orders-card">

                <div class="card-header">

                    <div class="header-title">

                        <div class="header-icon purple-icon">
                            <ShoppingOutlined />
                        </div>

                        <div>

                            <h3>
                                Recent Orders
                            </h3>

                            <p>
                                Track your latest purchases
                            </p>

                        </div>

                    </div>

                    <Link
                        href="/orders"
                        class="view-link"
                    >
                        View All
                        <ArrowRightOutlined />
                    </Link>

                </div>


                <div
                    v-if="!orders.length"
                    class="empty-orders"
                >

                    <FileTextOutlined />

                    <p>
                        You haven't placed any orders yet.
                    </p>

                    <Link
                        href="/products"
                        class="text-button"
                    >
                        Browse Products
                    </Link>

                </div>


                <div
                    v-else
                    class="orders-list"
                >

                    <div
                        v-for="order in orders.slice(0, 5)"
                        :key="order.id"
                        class="order-item"
                    >

                        <div class="order-icon">

                            <component
                                :is="orderStatusIcon(order.status)"
                            />

                        </div>

                        <div class="order-information">

                            <div class="order-top">

                                <strong>
                                    #{{ order.order_number || order.id }}
                                </strong>

                                <span
                                    class="order-status"
                                    :class="orderStatusClass(order.status)"
                                >
                                    {{ order.status }}
                                </span>

                            </div>

                            <p>
                                {{ order.items_count || 0 }}
                                items

                                <span>•</span>

                                {{ currency(order.total) }}
                            </p>

                            <small>
                                {{ order.created_at }}
                            </small>

                        </div>

                        <Link
                            :href="`/orders/${order.id}`"
                            class="order-view"
                        >
                            <EyeOutlined />
                        </Link>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             BOTTOM SECTION
        ====================================================== -->

        <div class="bottom-grid">

            <!-- Payment History -->

            <div class="dashboard-card">

                <div class="card-header">

                    <div class="header-title">

                        <div class="header-icon green-icon">
                            <CreditCardOutlined />
                        </div>

                        <div>

                            <h3>
                                Payment History
                            </h3>

                            <p>
                                Your recent transactions
                            </p>

                        </div>

                    </div>

                    <Link
                        href="/payments"
                        class="view-link"
                    >
                        View All
                        <ArrowRightOutlined />
                    </Link>

                </div>


                <div
                    v-if="!payments.length"
                    class="empty-payment"
                >

                    <CreditCardOutlined />

                    <p>
                        No payment history available.
                    </p>

                </div>


                <div
                    v-else
                    class="payments-list"
                >

                    <div
                        v-for="payment in payments.slice(0, 5)"
                        :key="payment.id"
                        class="payment-item"
                    >

                        <div class="payment-icon">
                            <CreditCardOutlined />
                        </div>

                        <div class="payment-details">

                            <strong>
                                {{
                                    payment.reference ||
                                    `Payment #${payment.id}`
                                }}
                            </strong>

                            <span>
                                {{ payment.payment_method || 'Payment' }}
                            </span>

                        </div>

                        <div class="payment-amount">

                            <strong>
                                {{ currency(payment.amount) }}
                            </strong>

                            <span
                                :class="{
                                    'payment-success':
                                        payment.status === 'paid' ||
                                        payment.status === 'completed',

                                    'payment-pending':
                                        payment.status === 'pending',
                                }"
                            >
                                {{ payment.status || 'Completed' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Quick Actions -->

            <div class="dashboard-card quick-actions-card">

                <div class="card-header">

                    <div class="header-title">

                        <div class="header-icon orange-icon">
                            <GiftOutlined />
                        </div>

                        <div>

                            <h3>
                                Quick Actions
                            </h3>

                            <p>
                                Manage your account
                            </p>

                        </div>

                    </div>

                </div>


                <div class="quick-actions">

                    <Link
                        href="/products"
                        class="quick-action"
                    >

                        <div class="quick-action-icon">
                            <ShoppingOutlined />
                        </div>

                        <div>

                            <strong>
                                Continue Shopping
                            </strong>

                            <span>
                                Browse our products
                            </span>

                        </div>

                        <RightOutlined />

                    </Link>


                    <Link
                        href="/orders"
                        class="quick-action"
                    >

                        <div class="quick-action-icon">
                            <FileTextOutlined />
                        </div>

                        <div>

                            <strong>
                                My Orders
                            </strong>

                            <span>
                                View and track orders
                            </span>

                        </div>

                        <RightOutlined />

                    </Link>


                    <Link
                        href="/profile"
                        class="quick-action"
                    >

                        <div class="quick-action-icon">
                            <UserOutlined />
                        </div>

                        <div>

                            <strong>
                                Account Settings
                            </strong>

                            <span>
                                Update your profile
                            </span>

                        </div>

                        <RightOutlined />

                    </Link>


                    <Link
                        href="/wishlist"
                        class="quick-action"
                    >

                        <div class="quick-action-icon">
                            <HeartOutlined />
                        </div>

                        <div>

                            <strong>
                                My Wishlist
                            </strong>

                            <span>
                                Saved products
                            </span>

                        </div>

                        <RightOutlined />

                    </Link>

                </div>

            </div>

        </div>


        <!-- =====================================================
             CHECKOUT BANNER
        ====================================================== -->

        <div
            v-if="!cartIsEmpty"
            class="checkout-banner"
        >

            <div class="checkout-banner-icon">
                <ShoppingCartOutlined />
            </div>

            <div class="checkout-banner-content">

                <h3>
                    Ready to complete your order?
                </h3>

                <p>
                    You have {{ cartItemCount }}
                    {{ cartItemCount === 1 ? 'item' : 'items' }}
                    waiting in your cart.
                </p>

            </div>

            <div class="checkout-banner-total">

                <span>
                    Cart Total
                </span>

                <strong>
                    {{ currency(cart.total) }}
                </strong>

            </div>

            <Link
                href="/checkout"
                class="banner-checkout-button"
            >
                Checkout Now
                <ArrowRightOutlined />
            </Link>

        </div>

    </div>

</template>


<style scoped>

/* =========================================================
   PAGE
========================================================= */

.dashboard-page {
    min-height: calc(100vh - 80px);
    padding: 28px 20px 50px;

    background:
        linear-gradient(
            135deg,
            #f8fafc 0%,
            #f0fdfa 50%,
            #f8fafc 100%
        );
}


.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}


.welcome-label {
    margin: 0 0 3px;

    color: #14b8a6;

    font-size: 13px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .08em;
}


.dashboard-title {
    margin: 0;

    font-size: 25px;
    font-weight: 800;

    color: #111827;
}


.dashboard-subtitle {
    margin: 5px 0 0;

    color: #6b7280;

    font-size: 14px;
}


.profile-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 10px 16px;

    border-radius: 10px;

    background: white;

    border: 1px solid #e5e7eb;

    color: #374151;

    font-size: 14px;
    font-weight: 600;

    text-decoration: none;

    transition: all .25s ease;
}


.profile-button:hover {
    border-color: #14b8a6;
    color: #0f766e;

    transform: translateY(-1px);
}


/* =========================================================
   STATS
========================================================= */

.stats-grid {
    max-width: 1400px;

    margin: 0 auto 24px;

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 18px;
}


.stat-card {
    position: relative;

    overflow: hidden;

    display: flex;
    align-items: center;

    gap: 15px;

    padding: 20px;

    background: rgba(255,255,255,.95);

    border: 1px solid #e5e7eb;

    border-radius: 16px;

    box-shadow:
        0 4px 18px rgba(15, 23, 42, .05);

    transition: all .3s ease;
}


.stat-card:hover {
    transform: translateY(-3px);

    box-shadow:
        0 10px 28px rgba(15, 23, 42, .09);
}


.stat-icon {
    width: 48px;
    height: 48px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background: #ecfdf5;

    color: #0f766e;

    font-size: 22px;
}


.order-stat .stat-icon {
    background: #eff6ff;
    color: #2563eb;
}


.pending-stat .stat-icon {
    background: #fffbeb;
    color: #d97706;
}


.payment-stat .stat-icon {
    background: #f5f3ff;
    color: #7c3aed;
}


.stat-content {
    display: flex;
    flex-direction: column;

    min-width: 0;
}


.stat-label {
    color: #6b7280;

    font-size: 12px;
    font-weight: 600;
}


.stat-value {
    margin-top: 2px;

    color: #111827;

    font-size: 22px;
    font-weight: 800;
}


.stat-description {
    color: #9ca3af;

    font-size: 11px;
}


.stat-arrow {
    position: absolute;

    right: 15px;
    top: 15px;

    color: #9ca3af;

    transition: all .2s ease;
}


.stat-card:hover .stat-arrow {
    color: #14b8a6;

    transform: translateX(3px);
}


/* =========================================================
   MAIN GRID
========================================================= */

.main-grid {
    max-width: 1400px;

    margin: 0 auto;

    display: grid;

    grid-template-columns:
        minmax(0, 1.55fr)
        minmax(350px, .8fr);

    gap: 20px;
}


.bottom-grid {
    max-width: 1400px;

    margin: 20px auto 0;

    display: grid;

    grid-template-columns:
        1.3fr
        1fr;

    gap: 20px;
}


/* =========================================================
   CARDS
========================================================= */

.dashboard-card {
    overflow: hidden;

    background: rgba(255,255,255,.96);

    border: 1px solid #e5e7eb;

    border-radius: 17px;

    box-shadow:
        0 5px 22px rgba(15, 23, 42, .05);
}


.card-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 20px 22px;

    border-bottom: 1px solid #f1f5f9;
}


.header-title {
    display: flex;

    align-items: center;

    gap: 12px;
}


.header-icon {
    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #ecfdf5;

    color: #0f766e;

    font-size: 19px;
}


.purple-icon {
    background: #f5f3ff;
    color: #7c3aed;
}


.green-icon {
    background: #ecfdf5;
    color: #059669;
}


.orange-icon {
    background: #fff7ed;
    color: #ea580c;
}


.card-header h3 {
    margin: 0;

    color: #111827;

    font-size: 16px;
    font-weight: 750;
}


.card-header p {
    margin: 3px 0 0;

    color: #9ca3af;

    font-size: 12px;
}


.view-link {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    color: #0f766e;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;

    white-space: nowrap;
}


.view-link:hover {
    color: #14b8a6;
}


/* =========================================================
   CART
========================================================= */

.cart-content {
    padding: 0;
}


.cart-items {
    padding: 4px 22px 0;
}


.cart-item {
    display: grid;

    grid-template-columns:
        68px
        minmax(0, 1fr)
        auto
        auto;

    align-items: center;

    gap: 15px;

    padding: 17px 0;

    border-bottom: 1px solid #f1f5f9;
}


.product-image-wrapper {
    width: 68px;
    height: 68px;

    overflow: hidden;

    border-radius: 11px;

    background: #f8fafc;
}


.product-image {
    width: 100%;
    height: 100%;

    object-fit: cover;
}


.product-placeholder {
    width: 100%;
    height: 100%;

    display: flex;

    align-items: center;
    justify-content: center;

    color: #94a3b8;

    font-size: 22px;
}


.product-details {
    min-width: 0;
}


.product-details h4 {
    margin: 0;

    color: #1f2937;

    font-size: 14px;
    font-weight: 700;
}


.product-variant {
    margin: 3px 0;

    color: #9ca3af;

    font-size: 11px;
}


.product-price {
    color: #0f766e;

    font-size: 12px;
    font-weight: 700;
}


.quantity-control {
    display: flex;

    align-items: center;

    gap: 9px;

    padding: 5px;

    border: 1px solid #e5e7eb;

    border-radius: 8px;
}


.quantity-control > span {
    min-width: 20px;

    text-align: center;

    font-size: 12px;
    font-weight: 700;
}


.quantity-button {
    width: 24px;
    height: 24px;

    border: 0;

    border-radius: 5px;

    background: #f8fafc;

    color: #64748b;

    cursor: pointer;
}


.quantity-button:hover {
    background: #ccfbf1;
    color: #0f766e;
}


.item-total {
    min-width: 80px;

    text-align: right;

    color: #111827;

    font-size: 13px;
    font-weight: 750;
}


.cart-summary {
    padding: 20px 22px;

    background: #f8fafc;
}


.summary-row {
    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 10px;

    color: #64748b;

    font-size: 13px;
}


.summary-row strong {
    color: #334155;
}


.summary-divider {
    height: 1px;

    margin: 13px 0;

    background: #e2e8f0;
}


.total-row {
    margin-bottom: 17px;

    color: #111827;

    font-size: 15px;
}


.total-row strong {
    color: #0f766e;

    font-size: 20px;
}


.checkout-button {
    width: 100%;

    min-height: 46px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 9px;

    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #0f766e,
            #14b8a6
        );

    color: white;

    font-size: 13px;
    font-weight: 750;

    text-decoration: none;

    box-shadow:
        0 6px 16px rgba(20,184,166,.2);

    transition: all .25s ease;
}


.checkout-button:hover {
    color: white;

    transform: translateY(-2px);

    box-shadow:
        0 10px 22px rgba(20,184,166,.28);
}


/* =========================================================
   EMPTY STATES
========================================================= */

.empty-cart,
.empty-orders,
.empty-payment {
    padding: 45px 20px;

    text-align: center;

    color: #94a3b8;
}


.empty-cart-icon {
    width: 64px;
    height: 64px;

    margin: 0 auto 14px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #ecfdf5;

    color: #14b8a6;

    font-size: 28px;
}


.empty-cart h4 {
    margin: 0 0 5px;

    color: #334155;

    font-size: 15px;
}


.empty-cart p {
    margin: 0 0 18px;

    font-size: 12px;
}


.shop-button {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 10px 17px;

    border-radius: 9px;

    background: #0f766e;

    color: white;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;
}


.shop-button:hover {
    background: #115e59;

    color: white;
}


.empty-orders svg,
.empty-payment svg {
    margin-bottom: 8px;

    font-size: 30px;

    color: #cbd5e1;
}


.empty-orders p,
.empty-payment p {
    margin: 0 0 10px;

    font-size: 12px;
}


.text-button {
    color: #0f766e;

    font-size: 12px;
    font-weight: 700;
}


/* =========================================================
   ORDERS
========================================================= */

.orders-list {
    padding: 5px 20px;
}


.order-item {
    display: flex;

    align-items: center;

    gap: 12px;

    padding: 15px 2px;

    border-bottom: 1px solid #f1f5f9;
}


.order-item:last-child {
    border-bottom: 0;
}


.order-icon {
    width: 38px;
    height: 38px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #ecfdf5;

    color: #0f766e;
}


.order-information {
    flex: 1;

    min-width: 0;
}


.order-top {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 8px;
}


.order-top strong {
    color: #334155;

    font-size: 12px;
}


.order-information p {
    margin: 4px 0;

    color: #64748b;

    font-size: 11px;
}


.order-information p span {
    padding: 0 4px;

    color: #cbd5e1;
}


.order-information small {
    color: #94a3b8;

    font-size: 10px;
}


.order-status {
    padding: 4px 8px;

    border-radius: 20px;

    font-size: 9px;
    font-weight: 750;

    text-transform: capitalize;
}


.status-pending {
    background: #fffbeb;
    color: #b45309;
}


.status-processing {
    background: #eff6ff;
    color: #2563eb;
}


.status-shipped {
    background: #f5f3ff;
    color: #7c3aed;
}


.status-delivered,
.status-completed {
    background: #ecfdf5;
    color: #047857;
}


.status-cancelled {
    background: #fef2f2;
    color: #dc2626;
}


.order-view {
    color: #94a3b8;

    font-size: 15px;
}


.order-view:hover {
    color: #0f766e;
}


/* =========================================================
   PAYMENTS
========================================================= */

.payments-list {
    padding: 5px 20px;
}


.payment-item {
    display: flex;

    align-items: center;

    gap: 12px;

    padding: 14px 2px;

    border-bottom: 1px solid #f1f5f9;
}


.payment-item:last-child {
    border-bottom: 0;
}


.payment-icon {
    width: 38px;
    height: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #eff6ff;

    color: #2563eb;
}


.payment-details {
    flex: 1;

    display: flex;

    flex-direction: column;

    min-width: 0;
}


.payment-details strong {
    color: #334155;

    font-size: 12px;
}


.payment-details span {
    margin-top: 3px;

    color: #94a3b8;

    font-size: 10px;
}


.payment-amount {
    display: flex;

    flex-direction: column;

    align-items: flex-end;
}


.payment-amount strong {
    color: #334155;

    font-size: 12px;
}


.payment-amount span {
    margin-top: 3px;

    font-size: 9px;

    font-weight: 700;

    text-transform: capitalize;
}


.payment-success {
    color: #059669;
}


.payment-pending {
    color: #d97706;
}


/* =========================================================
   QUICK ACTIONS
========================================================= */

.quick-actions {
    padding: 8px 20px 15px;
}


.quick-action {
    display: flex;

    align-items: center;

    gap: 12px;

    padding: 13px 4px;

    border-bottom: 1px solid #f1f5f9;

    color: #334155;

    text-decoration: none;

    transition: all .2s ease;
}


.quick-action:last-child {
    border-bottom: 0;
}


.quick-action:hover {
    color: #0f766e;

    transform: translateX(3px);
}


.quick-action-icon {
    width: 36px;
    height: 36px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #f8fafc;

    color: #0f766e;
}


.quick-action > div:nth-child(2) {
    flex: 1;

    display: flex;

    flex-direction: column;
}


.quick-action strong {
    font-size: 12px;
}


.quick-action span {
    margin-top: 2px;

    color: #94a3b8;

    font-size: 10px;
}


.quick-action > svg {
    color: #cbd5e1;

    font-size: 11px;
}


/* =========================================================
   CHECKOUT BANNER
========================================================= */

.checkout-banner {
    max-width: 1400px;

    margin: 20px auto 0;

    display: flex;

    align-items: center;

    gap: 17px;

    padding: 20px 24px;

    border-radius: 16px;

    background:
        linear-gradient(
            135deg,
            #064e3b,
            #0f766e
        );

    box-shadow:
        0 10px 30px rgba(15,118,110,.2);
}


.checkout-banner-icon {
    width: 48px;
    height: 48px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background: rgba(255,255,255,.12);

    color: white;

    font-size: 22px;
}


.checkout-banner-content {
    flex: 1;
}


.checkout-banner-content h3 {
    margin: 0;

    color: white;

    font-size: 15px;
    font-weight: 750;
}


.checkout-banner-content p {
    margin: 4px 0 0;

    color: rgba(255,255,255,.7);

    font-size: 11px;
}


.checkout-banner-total {
    display: flex;

    flex-direction: column;

    align-items: flex-end;

    margin-right: 10px;
}


.checkout-banner-total span {
    color: rgba(255,255,255,.65);

    font-size: 10px;
}


.checkout-banner-total strong {
    margin-top: 2px;

    color: white;

    font-size: 18px;
}


.banner-checkout-button {
    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 11px 18px;

    border-radius: 9px;

    background: white;

    color: #0f766e;

    font-size: 12px;
    font-weight: 750;

    text-decoration: none;

    transition: all .25s ease;
}


.banner-checkout-button:hover {
    color: #064e3b;

    transform: translateY(-2px);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .main-grid,
    .bottom-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 700px) {

    .dashboard-page {
        padding: 20px 12px 40px;
    }

    .dashboard-header {
        align-items: flex-start;
    }

    .dashboard-title {
        font-size: 21px;
    }

    .profile-button span {
        display: none;
    }

    .stats-grid {
        grid-template-columns: 1fr;

        gap: 12px;
    }

    .cart-item {
        grid-template-columns:
            55px
            minmax(0, 1fr)
            auto;
    }

    .product-image-wrapper {
        width: 55px;
        height: 55px;
    }

    .quantity-control {
        display: none;
    }

    .item-total {
        min-width: auto;
    }

    .card-header {
        padding: 16px;
    }

    .cart-items {
        padding: 4px 16px 0;
    }

    .cart-summary {
        padding: 18px 16px;
    }

    .checkout-banner {
        flex-wrap: wrap;

        padding: 18px;
    }

    .checkout-banner-content {
        min-width: calc(100% - 70px);
    }

    .checkout-banner-total {
        margin-left: 65px;

        margin-right: auto;

        align-items: flex-start;
    }

    .banner-checkout-button {
        margin-left: 65px;
    }

}

</style>