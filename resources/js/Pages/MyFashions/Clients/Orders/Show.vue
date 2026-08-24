<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";

import {
    ArrowLeftOutlined,
    ShoppingOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    SyncOutlined,
    CloseCircleOutlined,
    EnvironmentOutlined,
    PhoneOutlined,
    MailOutlined,
    CreditCardOutlined,
    FileTextOutlined,
    InboxOutlined,
    SafetyCertificateOutlined,
} from "@ant-design/icons-vue";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/

const order = computed(() => props.order || {});

const items = computed(() => order.value.items || []);

const currency = "K";

/*
|--------------------------------------------------------------------------
| FORMATTING
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    const value = Number(amount || 0);

    return `${currency}${value.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`;
};

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

const statusLabel = computed(() => {
    const status = String(order.value.status || "").toLowerCase();

    const labels = {
        pending: "Pending",
        processing: "Processing",
        completed: "Completed",
        cancelled: "Cancelled",
    };

    return labels[status] || "Unknown";
});

const statusClass = computed(() => {
    const status = String(order.value.status || "").toLowerCase();

    return {
        pending: "status-pending",
        processing: "status-processing",
        completed: "status-completed",
        cancelled: "status-cancelled",
    }[status] || "status-default";
});

const statusIcon = computed(() => {
    const status = String(order.value.status || "").toLowerCase();

    if (status === "completed") {
        return CheckCircleOutlined;
    }

    if (status === "processing") {
        return SyncOutlined;
    }

    if (status === "cancelled") {
        return CloseCircleOutlined;
    }

    return ClockCircleOutlined;
});

/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

const paymentStatusLabel = computed(() => {
    const status = String(order.value.payment_status || "").toLowerCase();

    const labels = {
        pending: "Payment Pending",
        paid: "Paid",
        completed: "Paid",
        failed: "Payment Failed",
        refunded: "Refunded",
        cancelled: "Payment Cancelled",
    };

    return labels[status] || order.value.payment_status || "Pending";
});

const paymentStatusClass = computed(() => {
    const status = String(order.value.payment_status || "").toLowerCase();

    if (
        status === "paid" ||
        status === "completed"
    ) {
        return "payment-paid";
    }

    if (
        status === "failed" ||
        status === "cancelled"
    ) {
        return "payment-failed";
    }

    if (status === "refunded") {
        return "payment-refunded";
    }

    return "payment-pending";
});

/*
|--------------------------------------------------------------------------
| PAYMENT METHOD
|--------------------------------------------------------------------------
*/

const paymentMethodLabel = computed(() => {
    const method = String(
        order.value.payment_method || ""
    ).toLowerCase();

    const methods = {
        cash_on_delivery: "Cash on Delivery",
        mobile_money: "Mobile Money",
        card: "Card Payment",
        cash: "Cash",
        bank_transfer: "Bank Transfer",
        cheque: "Cheque",
    };

    return methods[method] ||
        order.value.payment_method ||
        "Not specified";
});

/*
|--------------------------------------------------------------------------
| ORDER TOTALS
|--------------------------------------------------------------------------
*/

const subtotal = computed(() => {
    return Number(order.value.subtotal || 0);
});

const shippingAmount = computed(() => {
    return Number(order.value.shipping_amount || 0);
});

const totalAmount = computed(() => {
    return Number(order.value.total_amount || 0);
});

/*
|--------------------------------------------------------------------------
| TOTAL ITEM QUANTITY
|--------------------------------------------------------------------------
*/

const totalQuantity = computed(() => {
    return items.value.reduce(
        (total, item) =>
            total + Number(item.quantity || 0),
        0
    );
});

/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE
|--------------------------------------------------------------------------
*/

const productImage = (item) => {
    const product = item?.product;

    if (!product) {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | POSSIBLE IMAGE FIELDS
    |--------------------------------------------------------------------------
    */

    const image =
        product.image ||
        product.image_url ||
        product.thumbnail ||
        product.featured_image;

    if (!image) {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | ALREADY FULL URL
    |--------------------------------------------------------------------------
    */

    if (
        image.startsWith("http://") ||
        image.startsWith("https://") ||
        image.startsWith("/")
    ) {
        return image;
    }

    return `/storage/${image}`;
};

/*
|--------------------------------------------------------------------------
| SHIPPING ADDRESS
|--------------------------------------------------------------------------
*/

const fullName = computed(() => {
    return [
        order.value.shipping_first_name,
        order.value.shipping_last_name,
    ]
        .filter(Boolean)
        .join(" ");
});

/*
|--------------------------------------------------------------------------
| STATUS TIMELINE
|--------------------------------------------------------------------------
*/

const statusSteps = [
    {
        key: "pending",
        label: "Order Placed",
        description: "Your order has been received.",
    },
    {
        key: "processing",
        label: "Processing",
        description: "Your order is being prepared.",
    },
    {
        key: "completed",
        label: "Completed",
        description: "Your order has been completed.",
    },
];

const statusIndex = computed(() => {
    const current = String(
        order.value.status || ""
    ).toLowerCase();

    return statusSteps.findIndex(
        (step) => step.key === current
    );
});

const isStepCompleted = (index) => {
    if (order.value.status === "cancelled") {
        return false;
    }

    return index <= statusIndex.value;
};

const isCurrentStep = (index) => {
    if (order.value.status === "cancelled") {
        return false;
    }

    return index === statusIndex.value;
};

/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

const orderDate = computed(() => {
    return order.value.created_at || "—";
});
</script>

<template>
    <UserFashionLayout>

        <Head
            :title="`Order ${order.order_number || ''}`"
        />

        <div class="order-page">

            <!-- ========================================================= -->
            <!-- PAGE HEADER -->
            <!-- ========================================================= -->

            <section class="page-header">

                <div class="header-container">

                    <Link
                        :href="route('client.orders.index')"
                        class="back-link"
                    >
                        <ArrowLeftOutlined />

                        <span>
                            Back to My Orders
                        </span>
                    </Link>

                    <div class="header-content">

                        <div>
                            <div class="eyebrow">
                                <ShoppingOutlined />

                                Order Details
                            </div>

                            <h1>
                                Order
                                <span>
                                    #{{ order.order_number }}
                                </span>
                            </h1>

                            <p>
                                Placed on {{ orderDate }}
                            </p>
                        </div>

                        <div
                            class="order-status"
                            :class="statusClass"
                        >
                            <component
                                :is="statusIcon"
                            />

                            <span>
                                {{ statusLabel }}
                            </span>
                        </div>

                    </div>

                </div>

            </section>


            <!-- ========================================================= -->
            <!-- MAIN CONTENT -->
            <!-- ========================================================= -->

            <main class="content-container">

                <!-- ===================================================== -->
                <!-- CANCELLED NOTICE -->
                <!-- ===================================================== -->

                <div
                    v-if="order.status === 'cancelled'"
                    class="cancelled-notice"
                >
                    <div class="notice-icon">
                        <CloseCircleOutlined />
                    </div>

                    <div>
                        <h3>
                            This order has been cancelled
                        </h3>

                        <p>
                            This order is no longer being processed.
                        </p>
                    </div>
                </div>


                <!-- ===================================================== -->
                <!-- ORDER STATUS TIMELINE -->
                <!-- ===================================================== -->

                <section
                    v-if="order.status !== 'cancelled'"
                    class="card status-card"
                >

                    <div class="card-header">

                        <div class="card-title">

                            <div class="title-icon">
                                <FileTextOutlined />
                            </div>

                            <div>
                                <h2>
                                    Order Status
                                </h2>

                                <p>
                                    Track the progress of your order
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="timeline">

                        <div
                            v-for="(
                                step,
                                index
                            ) in statusSteps"
                            :key="step.key"
                            class="timeline-item"
                            :class="{
                                completed:
                                    isStepCompleted(index),

                                current:
                                    isCurrentStep(index),
                            }"
                        >

                            <div class="timeline-marker">

                                <CheckCircleOutlined
                                    v-if="
                                        isStepCompleted(index)
                                    "
                                />

                                <ClockCircleOutlined
                                    v-else
                                />

                            </div>

                            <div class="timeline-content">

                                <h3>
                                    {{ step.label }}
                                </h3>

                                <p>
                                    {{ step.description }}
                                </p>

                            </div>

                            <div
                                v-if="
                                    index <
                                    statusSteps.length - 1
                                "
                                class="timeline-line"
                            ></div>

                        </div>

                    </div>

                </section>


                <!-- ===================================================== -->
                <!-- TWO COLUMN AREA -->
                <!-- ===================================================== -->

                <div class="main-grid">

                    <!-- ================================================= -->
                    <!-- ORDER ITEMS -->
                    <!-- ================================================= -->

                    <section class="card products-card">

                        <div class="card-header">

                            <div class="card-title">

                                <div class="title-icon">
                                    <ShoppingOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Order Items
                                    </h2>

                                    <p>
                                        {{ items.length }}
                                        {{ items.length === 1
                                            ? "product"
                                            : "products"
                                        }}
                                        ·
                                        {{ totalQuantity }}
                                        {{
                                            totalQuantity === 1
                                                ? "item"
                                                : "items"
                                        }}
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div
                            v-if="items.length"
                            class="products-list"
                        >

                            <div
                                v-for="item in items"
                                :key="item.id"
                                class="product-item"
                            >

                                <!-- IMAGE -->

                                <div class="product-image">

                                    <img
                                        v-if="
                                            productImage(item)
                                        "
                                        :src="
                                            productImage(item)
                                        "
                                        :alt="
                                            item.product_name
                                        "
                                    />

                                    <div
                                        v-else
                                        class="image-placeholder"
                                    >
                                        <ShoppingOutlined />
                                    </div>

                                </div>


                                <!-- DETAILS -->

                                <div class="product-details">

                                    <h3>
                                        {{
                                            item.product_name ||
                                            item.product?.name ||
                                            "Product"
                                        }}
                                    </h3>

                                    <p
                                        v-if="item.sku"
                                        class="sku"
                                    >
                                        SKU:
                                        {{ item.sku }}
                                    </p>

                                    <div class="product-meta">

                                        <span>
                                            Qty:
                                            {{ item.quantity }}
                                        </span>

                                        <span>
                                            {{
                                                formatMoney(
                                                    item.unit_price
                                                )
                                            }}
                                            each
                                        </span>

                                    </div>

                                </div>


                                <!-- PRICE -->

                                <div class="product-total">

                                    {{
                                        formatMoney(
                                            item.total_price ??
                                            (
                                                Number(
                                                    item.unit_price || 0
                                                ) *
                                                Number(
                                                    item.quantity || 0
                                                )
                                            )
                                        )
                                    }}

                                </div>

                            </div>

                        </div>


                        <!-- EMPTY -->

                        <div
                            v-else
                            class="empty-items"
                        >
                            <InboxOutlined />

                            <h3>
                                No order items found
                            </h3>

                            <p>
                                The products for this order
                                are currently unavailable.
                            </p>
                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- ORDER SUMMARY -->
                    <!-- ================================================= -->

                    <section class="card summary-card">

                        <div class="card-header">

                            <div class="card-title">

                                <div class="title-icon">
                                    <FileTextOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Order Summary
                                    </h2>

                                    <p>
                                        Payment breakdown
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="summary-body">

                            <div class="summary-row">
                                <span>
                                    Subtotal
                                </span>

                                <strong>
                                    {{
                                        formatMoney(
                                            subtotal
                                        )
                                    }}
                                </strong>
                            </div>


                            <div class="summary-row">

                                <span>
                                    Shipping
                                </span>

                                <strong
                                    :class="{
                                        free:
                                            shippingAmount === 0,
                                    }"
                                >
                                    {{
                                        shippingAmount === 0
                                            ? "FREE"
                                            : formatMoney(
                                                shippingAmount
                                            )
                                    }}
                                </strong>

                            </div>


                            <div class="summary-divider"></div>


                            <div class="summary-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    {{
                                        formatMoney(
                                            totalAmount
                                        )
                                    }}
                                </strong>

                            </div>

                        </div>

                    </section>

                </div>


                <!-- ===================================================== -->
                <!-- INFORMATION GRID -->
                <!-- ===================================================== -->

                <div class="info-grid">


                    <!-- ================================================= -->
                    <!-- SHIPPING INFORMATION -->
                    <!-- ================================================= -->

                    <section class="card info-card">

                        <div class="card-header">

                            <div class="card-title">

                                <div class="title-icon">
                                    <EnvironmentOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Delivery Information
                                    </h2>

                                    <p>
                                        Where your order will be delivered
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="info-body">

                            <div class="customer-name">
                                {{
                                    fullName || "Customer"
                                }}
                            </div>

                            <div class="info-line">

                                <EnvironmentOutlined />

                                <span>
                                    {{
                                        order.shipping_address ||
                                        "Address not provided"
                                    }}
                                </span>

                            </div>

                            <div
                                v-if="
                                    order.shipping_city ||
                                    order.shipping_country
                                "
                                class="info-line"
                            >

                                <EnvironmentOutlined />

                                <span>

                                    {{
                                        [
                                            order.shipping_city,
                                            order.shipping_country,
                                        ]
                                            .filter(Boolean)
                                            .join(", ")
                                    }}

                                </span>

                            </div>

                            <div
                                v-if="order.customer_phone"
                                class="info-line"
                            >

                                <PhoneOutlined />

                                <span>
                                    {{ order.customer_phone }}
                                </span>

                            </div>

                            <div
                                v-if="order.customer_email"
                                class="info-line"
                            >

                                <MailOutlined />

                                <span>
                                    {{ order.customer_email }}
                                </span>

                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- PAYMENT INFORMATION -->
                    <!-- ================================================= -->

                    <section class="card info-card">

                        <div class="card-header">

                            <div class="card-title">

                                <div class="title-icon">
                                    <CreditCardOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Payment Information
                                    </h2>

                                    <p>
                                        Payment details for this order
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="info-body">

                            <div class="payment-method">

                                <div class="payment-method-icon">
                                    <CreditCardOutlined />
                                </div>

                                <div>

                                    <span>
                                        Payment Method
                                    </span>

                                    <strong>
                                        {{
                                            paymentMethodLabel
                                        }}
                                    </strong>

                                </div>

                            </div>


                            <div class="payment-status-row">

                                <span>
                                    Payment Status
                                </span>

                                <span
                                    class="payment-badge"
                                    :class="
                                        paymentStatusClass
                                    "
                                >
                                    {{
                                        paymentStatusLabel
                                    }}
                                </span>

                            </div>

                            <div
                                v-if="
                                    order.payment_reference
                                "
                                class="payment-reference"
                            >

                                <span>
                                    Reference
                                </span>

                                <strong>
                                    {{
                                        order.payment_reference
                                    }}
                                </strong>

                            </div>

                        </div>

                    </section>

                </div>


                <!-- ===================================================== -->
                <!-- NOTES -->
                <!-- ===================================================== -->

                <section
                    v-if="order.notes"
                    class="card notes-card"
                >

                    <div class="card-header">

                        <div class="card-title">

                            <div class="title-icon">
                                <FileTextOutlined />
                            </div>

                            <div>
                                <h2>
                                    Order Notes
                                </h2>

                                <p>
                                    Additional information
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="notes-body">
                        {{ order.notes }}
                    </div>

                </section>


                <!-- ===================================================== -->
                <!-- SECURITY / TRUST -->
                <!-- ===================================================== -->

                <div class="secure-notice">

                    <SafetyCertificateOutlined />

                    <div>
                        <strong>
                            Your order is secure
                        </strong>

                        <p>
                            Your order information is safely
                            stored and can only be accessed
                            through your account.
                        </p>
                    </div>

                </div>


                <!-- ===================================================== -->
                <!-- BOTTOM ACTIONS -->
                <!-- ===================================================== -->

                <div class="bottom-actions">

                    <Link
                        :href="
                            route('client.orders.index')
                        "
                        class="secondary-button"
                    >
                        <ArrowLeftOutlined />

                        Back to My Orders
                    </Link>

                </div>

            </main>

        </div>

    </UserFashionLayout>
</template>

<style scoped>
/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.order-page {
    min-height: 100vh;
    background:
        linear-gradient(
            180deg,
            #fffaf2 0%,
            #ffffff 28%,
            #f8fafc 100%
        );

    color: #172033;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {
    background: #ffffff;
    border-bottom: 1px solid #e8ebf0;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 32px 34px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    color: #64748b;

    text-decoration: none;

    font-size: 14px;
    font-weight: 600;

    margin-bottom: 26px;

    transition:
        color 0.2s ease,
        transform 0.2s ease;
}

.back-link:hover {
    color: #b88932;
    transform: translateX(-3px);
}

.header-content {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 30px;
}

.eyebrow {
    display: flex;
    align-items: center;
    gap: 7px;

    color: #b88932;

    font-size: 12px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: 0.12em;

    margin-bottom: 8px;
}

.header-content h1 {
    margin: 0;

    font-size: clamp(28px, 4vw, 42px);
    line-height: 1.1;

    font-weight: 800;

    letter-spacing: -0.03em;

    color: #182230;
}

.header-content h1 span {
    color: #b88932;
}

.header-content p {
    margin: 10px 0 0;

    color: #718096;

    font-size: 14px;
}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 10px 17px;

    border-radius: 999px;

    font-size: 13px;
    font-weight: 800;

    white-space: nowrap;
}

.status-pending {
    color: #9a6700;
    background: #fff7d6;
    border: 1px solid #f4df91;
}

.status-processing {
    color: #1665a7;
    background: #eaf5ff;
    border: 1px solid #c8e5ff;
}

.status-completed {
    color: #16734a;
    background: #e9f9f0;
    border: 1px solid #c9eedb;
}

.status-cancelled {
    color: #b42318;
    background: #fff0ef;
    border: 1px solid #ffd0cd;
}

.status-default {
    color: #475569;
    background: #f1f5f9;
}


/*
|--------------------------------------------------------------------------
| CONTENT
|--------------------------------------------------------------------------
*/

.content-container {
    max-width: 1400px;
    margin: 0 auto;

    padding: 32px;
}


/*
|--------------------------------------------------------------------------
| CARDS
|--------------------------------------------------------------------------
*/

.card {
    background: #ffffff;

    border: 1px solid #e7ebf0;

    border-radius: 18px;

    box-shadow:
        0 5px 18px rgba(15, 23, 42, 0.035);

    overflow: hidden;
}

.card-header {
    padding: 22px 24px;

    border-bottom: 1px solid #edf0f3;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 13px;
}

.title-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #fff7e6;
    color: #b88932;

    font-size: 18px;
}

.card-title h2 {
    margin: 0;

    font-size: 17px;
    font-weight: 800;

    color: #172033;
}

.card-title p {
    margin: 4px 0 0;

    color: #8792a2;

    font-size: 12px;
}


/*
|--------------------------------------------------------------------------
| CANCELLED NOTICE
|--------------------------------------------------------------------------
*/

.cancelled-notice {
    display: flex;
    align-items: center;
    gap: 15px;

    margin-bottom: 24px;

    padding: 18px 20px;

    border-radius: 16px;

    background: #fff4f3;
    border: 1px solid #ffd6d2;
}

.notice-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    color: #c62828;
    background: #ffe2df;

    font-size: 19px;
}

.cancelled-notice h3 {
    margin: 0;

    color: #a51d1d;

    font-size: 15px;
    font-weight: 800;
}

.cancelled-notice p {
    margin: 4px 0 0;

    color: #875858;

    font-size: 13px;
}


/*
|--------------------------------------------------------------------------
| STATUS CARD
|--------------------------------------------------------------------------
*/

.status-card {
    margin-bottom: 24px;
}

.timeline {
    display: flex;

    padding: 30px 32px 28px;
}

.timeline-item {
    position: relative;

    flex: 1;

    display: flex;
    flex-direction: column;
    align-items: flex-start;

    min-width: 0;
}

.timeline-marker {
    position: relative;
    z-index: 2;

    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    color: #94a3b8;

    background: #f1f5f9;

    border: 4px solid #ffffff;

    box-shadow:
        0 0 0 1px #e2e8f0;

    font-size: 17px;

    transition: all 0.25s ease;
}

.timeline-item.completed .timeline-marker {
    color: #ffffff;

    background: #b88932;

    box-shadow:
        0 0 0 1px #b88932;
}

.timeline-item.current .timeline-marker {
    color: #ffffff;

    background: #b88932;

    box-shadow:
        0 0 0 4px #fff4d9,
        0 0 0 5px #b88932;
}

.timeline-content {
    padding-top: 14px;
    padding-right: 20px;
}

.timeline-content h3 {
    margin: 0;

    color: #263244;

    font-size: 14px;
    font-weight: 800;
}

.timeline-content p {
    margin: 5px 0 0;

    max-width: 180px;

    color: #8993a2;

    font-size: 12px;
    line-height: 1.5;
}

.timeline-line {
    position: absolute;

    left: 42px;
    right: 0;

    top: 20px;

    height: 2px;

    background: #e4e8ee;
}

.timeline-item.completed .timeline-line {
    background: #b88932;
}


/*
|--------------------------------------------------------------------------
| MAIN GRID
|--------------------------------------------------------------------------
*/

.main-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1.65fr)
        minmax(320px, 0.75fr);

    gap: 24px;

    align-items: start;

    margin-bottom: 24px;
}


/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/

.products-list {
    padding: 0 24px;
}

.product-item {
    display: grid;

    grid-template-columns: 78px minmax(0, 1fr) auto;

    align-items: center;

    gap: 17px;

    padding: 20px 0;

    border-bottom: 1px solid #edf0f3;
}

.product-item:last-child {
    border-bottom: none;
}

.product-image {
    width: 78px;
    height: 92px;

    overflow: hidden;

    border-radius: 12px;

    background: #f4f5f7;

    border: 1px solid #e8ebef;
}

.product-image img {
    width: 100%;
    height: 100%;

    display: block;

    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #a0a8b4;

    font-size: 24px;
}

.product-details {
    min-width: 0;
}

.product-details h3 {
    margin: 0;

    color: #1f2937;

    font-size: 15px;
    font-weight: 750;

    line-height: 1.4;
}

.sku {
    margin: 5px 0 0;

    color: #9aa3af;

    font-size: 11px;

    font-family:
        ui-monospace,
        SFMono-Regular,
        Menlo,
        Monaco,
        Consolas,
        monospace;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 13px;

    margin-top: 10px;

    color: #7b8796;

    font-size: 12px;
}

.product-total {
    color: #182230;

    font-size: 15px;
    font-weight: 800;

    white-space: nowrap;
}

.empty-items {
    padding: 50px 25px;

    text-align: center;

    color: #94a3b8;
}

.empty-items > :first-child {
    font-size: 34px;
}

.empty-items h3 {
    margin: 14px 0 5px;

    color: #475569;

    font-size: 15px;
}

.empty-items p {
    margin: 0;

    font-size: 13px;
}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.summary-card {
    position: sticky;
    top: 25px;
}

.summary-body {
    padding: 22px 24px;
}

.summary-row {
    display: flex;

    justify-content: space-between;
    align-items: center;

    gap: 20px;

    margin-bottom: 17px;

    color: #6b7686;

    font-size: 13px;
}

.summary-row strong {
    color: #263244;

    font-weight: 750;
}

.summary-row strong.free {
    color: #18834f;
}

.summary-divider {
    height: 1px;

    margin: 6px 0 18px;

    background: #e9edf1;
}

.summary-total {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.summary-total span {
    color: #293446;

    font-size: 15px;
    font-weight: 750;
}

.summary-total strong {
    color: #b88932;

    font-size: 23px;
    font-weight: 850;
}


/*
|--------------------------------------------------------------------------
| INFO GRID
|--------------------------------------------------------------------------
*/

.info-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 24px;

    margin-bottom: 24px;
}

.info-body {
    padding: 23px 24px;
}

.customer-name {
    margin-bottom: 18px;

    color: #1e293b;

    font-size: 15px;
    font-weight: 800;
}

.info-line {
    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-top: 12px;

    color: #657184;

    font-size: 13px;

    line-height: 1.5;
}

.info-line :first-child {
    flex-shrink: 0;

    margin-top: 2px;

    color: #b88932;
}


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

.payment-method {
    display: flex;

    align-items: center;

    gap: 13px;

    padding-bottom: 19px;

    margin-bottom: 18px;

    border-bottom: 1px solid #edf0f3;
}

.payment-method-icon {
    width: 43px;
    height: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #f2f6ff;
    color: #4267a8;

    font-size: 18px;
}

.payment-method span {
    display: block;

    color: #8993a2;

    font-size: 11px;

    margin-bottom: 4px;
}

.payment-method strong {
    display: block;

    color: #263244;

    font-size: 14px;
}

.payment-status-row,
.payment-reference {
    display: flex;

    justify-content: space-between;
    align-items: center;

    gap: 20px;

    font-size: 13px;
}

.payment-status-row {
    color: #707b8b;
}

.payment-badge {
    padding: 6px 10px;

    border-radius: 7px;

    font-size: 11px;
    font-weight: 800;
}

.payment-paid {
    color: #147447;
    background: #e9f9f0;
}

.payment-pending {
    color: #946800;
    background: #fff6d8;
}

.payment-failed {
    color: #b42318;
    background: #fff0ef;
}

.payment-refunded {
    color: #5b45a4;
    background: #f1edff;
}

.payment-reference {
    margin-top: 14px;

    color: #7c8797;
}

.payment-reference strong {
    color: #344054;

    font-size: 12px;
}


/*
|--------------------------------------------------------------------------
| NOTES
|--------------------------------------------------------------------------
*/

.notes-card {
    margin-bottom: 24px;
}

.notes-body {
    padding: 22px 24px;

    color: #596579;

    font-size: 14px;

    line-height: 1.7;

    white-space: pre-line;
}


/*
|--------------------------------------------------------------------------
| SECURE NOTICE
|--------------------------------------------------------------------------
*/

.secure-notice {
    display: flex;

    align-items: center;

    gap: 14px;

    padding: 17px 20px;

    margin-bottom: 24px;

    border-radius: 14px;

    background: #f5faf7;

    border: 1px solid #dcefe4;

    color: #26734c;
}

.secure-notice > :first-child {
    flex-shrink: 0;

    font-size: 21px;
}

.secure-notice strong {
    display: block;

    color: #236342;

    font-size: 13px;
}

.secure-notice p {
    margin: 3px 0 0;

    color: #688576;

    font-size: 12px;
}


/*
|--------------------------------------------------------------------------
| ACTIONS
|--------------------------------------------------------------------------
*/

.bottom-actions {
    display: flex;

    justify-content: flex-start;
}

.secondary-button {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 9px;

    min-height: 44px;

    padding: 0 19px;

    border-radius: 10px;

    background: #ffffff;

    border: 1px solid #dce1e7;

    color: #475569;

    font-size: 13px;
    font-weight: 750;

    text-decoration: none;

    transition:
        all 0.2s ease;
}

.secondary-button:hover {
    color: #a77928;

    border-color: #d5b267;

    background: #fffaf0;

    transform: translateY(-1px);
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1050px) {

    .main-grid {
        grid-template-columns: 1fr;
    }

    .summary-card {
        position: static;
    }

}

@media (max-width: 800px) {

    .header-container,
    .content-container {
        padding-left: 20px;
        padding-right: 20px;
    }

    .header-content {
        align-items: flex-start;

        flex-direction: column;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 650px) {

    .header-container {
        padding-top: 22px;
        padding-bottom: 25px;
    }

    .content-container {
        padding-top: 20px;
        padding-bottom: 30px;
    }

    .header-content h1 {
        font-size: 28px;
    }

    .timeline {
        flex-direction: column;

        gap: 0;

        padding: 25px 22px;
    }

    .timeline-item {
        flex-direction: row;

        align-items: flex-start;

        min-height: 78px;
    }

    .timeline-content {
        padding: 3px 0 0 15px;
    }

    .timeline-content p {
        max-width: none;
    }

    .timeline-line {
        top: 42px;
        bottom: 0;
        left: 20px;
        right: auto;

        width: 2px;
        height: auto;
    }

    .product-item {
        grid-template-columns: 64px minmax(0, 1fr);

        gap: 13px;
    }

    .product-image {
        width: 64px;
        height: 78px;
    }

    .product-total {
        grid-column: 2;

        margin-top: -5px;

        font-size: 14px;
    }

    .product-meta {
        flex-wrap: wrap;
    }

    .card-header {
        padding: 19px;
    }

    .products-list {
        padding: 0 19px;
    }

    .summary-body,
    .info-body,
    .notes-body {
        padding-left: 19px;
        padding-right: 19px;
    }

}

@media (max-width: 430px) {

    .header-container,
    .content-container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .order-status {
        padding: 8px 12px;

        font-size: 12px;
    }

    .product-details h3 {
        font-size: 13px;
    }

    .product-meta {
        font-size: 11px;
    }

    .summary-total strong {
        font-size: 20px;
    }

}
</style>