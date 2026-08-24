<script setup>
import { computed, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

import {
    ArrowLeftOutlined,
    EyeOutlined,
    ShoppingOutlined,
    ClockCircleOutlined,
    SearchOutlined,
    InboxOutlined,
    ReloadOutlined,
} from "@ant-design/icons-vue";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

const props = defineProps({
    orders: {
        type: [Array, Object],
        default: () => [],
    },

    filters: {
        type: Object,
        default: () => ({
            search: "",
        }),
    },

    title: {
        type: String,
        default: "Processing Orders",
    },
});

const search = ref(props.filters?.search || "");

/*
|--------------------------------------------------------------------------
| NORMALIZE PAGINATED DATA
|--------------------------------------------------------------------------
*/

const orderList = computed(() => {
    if (Array.isArray(props.orders)) {
        return props.orders;
    }

    if (
        props.orders &&
        Array.isArray(props.orders.data)
    ) {
        return props.orders.data;
    }

    return [];
});

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const filteredOrders = computed(() => {
    const keyword = search.value.trim().toLowerCase();

    if (!keyword) {
        return orderList.value;
    }

    return orderList.value.filter((order) => {
        return (
            String(order?.order_number || "")
                .toLowerCase()
                .includes(keyword) ||

            String(order?.customer_email || "")
                .toLowerCase()
                .includes(keyword)
        );
    });
});

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const paginationLinks = computed(() => {
    if (
        props.orders &&
        !Array.isArray(props.orders) &&
        Array.isArray(props.orders.links)
    ) {
        return props.orders.links;
    }

    return [];
});

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    return new Intl.NumberFormat("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(amount || 0));
};

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

const statusLabel = (status) => {
    const labels = {
        pending: "Pending",
        processing: "Processing",
        completed: "Completed",
        cancelled: "Cancelled",
    };

    return labels[status] || status || "Unknown";
};

/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const statusClass = (status) => {
    const classes = {
        pending: "status-pending",
        processing: "status-processing",
        completed: "status-completed",
        cancelled: "status-cancelled",
    };

    return classes[status] || "status-default";
};

/*
|--------------------------------------------------------------------------
| ORDER ITEM COUNT
|--------------------------------------------------------------------------
*/

const itemCount = (order) => {
    if (!Array.isArray(order?.items)) {
        return 0;
    }

    return order.items.reduce(
        (total, item) =>
            total + Number(item?.quantity || 0),
        0
    );
};
</script>

<template>
    <Head title="My Processing Orders" />

    <UserFashionLayout>
        <div class="processing-orders-page">

            <!-- ========================================================= -->
            <!-- HEADER -->
            <!-- ========================================================= -->

            <section class="page-header">
                <div class="header-inner">

                    <div class="header-left">

                        <Link
                            :href="route('client.orders.index')"
                            class="back-button"
                        >
                            <ArrowLeftOutlined />
                            <span>My Orders</span>
                        </Link>

                        <div class="title-area">

                            <div class="title-icon">
                                <ClockCircleOutlined />
                            </div>

                            <div>
                                <h1>
                                    My Processing Orders
                                </h1>

                                <p>
                                    Orders currently being prepared
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="order-count">
                        <span class="count-number">
                            {{ filteredOrders.length }}
                        </span>

                        <span class="count-label">
                            Processing
                        </span>
                    </div>

                </div>
            </section>


            <!-- ========================================================= -->
            <!-- CONTENT -->
            <!-- ========================================================= -->

            <main class="orders-container">

                <!-- SEARCH -->
                <div class="toolbar">

                    <div class="search-wrapper">

                        <SearchOutlined class="search-icon" />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search order number or email..."
                            class="search-input"
                        />

                        <button
                            v-if="search"
                            type="button"
                            class="clear-search"
                            @click="search = ''"
                        >
                            ×
                        </button>

                    </div>

                    <Link
                        :href="route('client.orders.processing')"
                        class="refresh-button"
                    >
                        <ReloadOutlined />
                        <span>Refresh</span>
                    </Link>

                </div>


                <!-- ===================================================== -->
                <!-- EMPTY STATE -->
                <!-- ===================================================== -->

                <div
                    v-if="filteredOrders.length === 0"
                    class="empty-state"
                >

                    <div class="empty-icon">
                        <InboxOutlined />
                    </div>

                    <h2>
                        No Processing Orders
                    </h2>

                    <p v-if="search">
                        No processing orders match your search.
                    </p>

                    <p v-else>
                        You currently don't have any orders being processed.
                    </p>

                    <button
                        v-if="search"
                        type="button"
                        class="clear-filter-button"
                        @click="search = ''"
                    >
                        Clear Search
                    </button>

                    <Link
                        v-else
                        :href="route('client.orders.index')"
                        class="view-orders-button"
                    >
                        <ShoppingOutlined />
                        View My Orders
                    </Link>

                </div>


                <!-- ===================================================== -->
                <!-- ORDER LIST -->
                <!-- ===================================================== -->

                <div
                    v-else
                    class="orders-list"
                >

                    <article
                        v-for="order in filteredOrders"
                        :key="order?.id || order?.order_number"
                        class="order-card"
                    >

                        <!-- TOP -->
                        <div class="order-top">

                            <div class="order-reference">

                                <div class="order-icon">
                                    <ShoppingOutlined />
                                </div>

                                <div>

                                    <span class="reference-label">
                                        Order
                                    </span>

                                    <h2>
                                        {{ order?.order_number || "N/A" }}
                                    </h2>

                                </div>

                            </div>

                            <span
                                class="status-badge"
                                :class="statusClass(order?.status)"
                            >
                                <span class="status-dot"></span>

                                {{ statusLabel(order?.status) }}
                            </span>

                        </div>


                        <!-- DETAILS -->
                        <div class="order-details">

                            <div class="detail-item">

                                <span class="detail-label">
                                    Date
                                </span>

                                <span class="detail-value">
                                    {{ order?.created_at || "—" }}
                                </span>

                            </div>


                            <div class="detail-item">

                                <span class="detail-label">
                                    Items
                                </span>

                                <span class="detail-value">
                                    {{ itemCount(order) }}
                                    {{ itemCount(order) === 1 ? "item" : "items" }}
                                </span>

                            </div>


                            <div class="detail-item">

                                <span class="detail-label">
                                    Total
                                </span>

                                <span class="detail-value total-value">
                                    ZMW
                                    {{ formatMoney(order?.total_amount) }}
                                </span>

                            </div>

                        </div>


                        <!-- PROGRESS -->
                        <div class="processing-progress">

                            <div class="progress-header">

                                <span>
                                    Order Progress
                                </span>

                                <span>
                                    Processing
                                </span>

                            </div>

                            <div class="progress-track">

                                <div
                                    class="progress-fill"
                                    style="width: 66%"
                                ></div>

                            </div>

                            <div class="progress-steps">

                                <span class="completed-step">
                                    <span class="step-circle">✓</span>
                                    Placed
                                </span>

                                <span class="active-step">
                                    <span class="step-circle">2</span>
                                    Processing
                                </span>

                                <span class="pending-step">
                                    <span class="step-circle">3</span>
                                    Completed
                                </span>

                            </div>

                        </div>


                        <!-- FOOTER -->
                        <div class="order-footer">

                            <div class="email-info">
                                <span>
                                    {{ order?.customer_email || "—" }}
                                </span>
                            </div>

                            <Link
                                v-if="order?.id"
                                :href="
                                    route(
                                        'client.orders.show',
                                        order.id
                                    )
                                "
                                class="view-order-button"
                            >
                                <EyeOutlined />
                                <span>View Order</span>
                            </Link>

                        </div>

                    </article>

                </div>


                <!-- ===================================================== -->
                <!-- PAGINATION -->
                <!-- ===================================================== -->

                <div
                    v-if="paginationLinks.length > 3"
                    class="pagination"
                >

                    <template
                        v-for="(link, index) in paginationLinks"
                        :key="index"
                    >

                        <span
                            v-if="!link.url"
                            class="pagination-link disabled"
                            v-html="link.label"
                        ></span>

                        <Link
                            v-else
                            :href="link.url"
                            class="pagination-link"
                            :class="{
                                active: link.active,
                            }"
                            v-html="link.label"
                        />

                    </template>

                </div>

            </main>

        </div>
    </UserFashionLayout>
</template>


<style scoped>
/* =========================================================
   PAGE
========================================================= */

.processing-orders-page {
    min-height: 100vh;
    background:
        linear-gradient(
            135deg,
            #fffdf8 0%,
            #fff9ed 45%,
            #fff4d6 100%
        );
    color: #1f2937;
}


/* =========================================================
   HEADER
========================================================= */

.page-header {
    background: rgba(255, 255, 255, 0.92);
    border-bottom: 1px solid #eee5d4;
    backdrop-filter: blur(14px);
}

.header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.back-button {
    width: fit-content;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s ease;
}

.back-button:hover {
    color: #111827;
    transform: translateX(-2px);
}

.title-area {
    display: flex;
    align-items: center;
    gap: 15px;
}

.title-icon {
    width: 52px;
    height: 52px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff1d1;
    color: #b7791f;
    font-size: 23px;
}

.title-area h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    color: #111827;
    letter-spacing: -0.5px;
}

.title-area p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.order-count {
    min-width: 110px;
    padding: 15px 20px;
    border-radius: 16px;
    background: #fff8e8;
    border: 1px solid #f4dfad;
    text-align: center;
}

.count-number {
    display: block;
    color: #9a6700;
    font-size: 24px;
    line-height: 1;
    font-weight: 800;
}

.count-label {
    display: block;
    margin-top: 5px;
    color: #7c6a42;
    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   CONTENT
========================================================= */

.orders-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px 60px;
}


/* =========================================================
   TOOLBAR
========================================================= */

.toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
}

.search-wrapper {
    position: relative;
    flex: 1;
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.search-input {
    width: 100%;
    height: 48px;
    padding: 0 45px;
    border-radius: 13px;
    border: 1px solid #e5e7eb;
    background: #fff;
    outline: none;
    color: #111827;
    font-size: 14px;
    transition: 0.2s ease;
    box-sizing: border-box;
}

.search-input:focus {
    border-color: #d7a93d;
    box-shadow: 0 0 0 3px rgba(215, 169, 61, 0.12);
}

.clear-search {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: #f3f4f6;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    cursor: pointer;
    color: #6b7280;
}

.refresh-button {
    height: 48px;
    padding: 0 18px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 13px;
    background: #fff;
    border: 1px solid #e5e7eb;
    color: #374151;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s ease;
}

.refresh-button:hover {
    border-color: #d7a93d;
    color: #9a6700;
    background: #fffaf0;
}


/* =========================================================
   ORDER LIST
========================================================= */

.orders-list {
    display: grid;
    gap: 18px;
}

.order-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #ece6da;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(80, 60, 20, 0.05);
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        border-color 0.25s ease;
}

.order-card:hover {
    transform: translateY(-3px);
    border-color: #e5d19f;
    box-shadow: 0 14px 35px rgba(80, 60, 20, 0.09);
}


/* =========================================================
   ORDER TOP
========================================================= */

.order-top {
    padding: 20px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #f1ede5;
}

.order-reference {
    display: flex;
    align-items: center;
    gap: 13px;
}

.order-icon {
    width: 45px;
    height: 45px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff5df;
    color: #b7791f;
    font-size: 19px;
}

.reference-label {
    display: block;
    color: #9ca3af;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.order-reference h2 {
    margin: 3px 0 0;
    color: #111827;
    font-size: 16px;
    font-weight: 800;
}


/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.status-processing {
    background: #fff4d6;
    color: #a16207;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.status-default {
    background: #f3f4f6;
    color: #4b5563;
}


/* =========================================================
   DETAILS
========================================================= */

.order-details {
    padding: 19px 22px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-label {
    color: #9ca3af;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #374151;
    font-size: 14px;
    font-weight: 600;
}

.total-value {
    color: #111827;
    font-size: 16px;
    font-weight: 800;
}


/* =========================================================
   PROCESSING PROGRESS
========================================================= */

.processing-progress {
    margin: 0 22px;
    padding: 18px;
    border-radius: 14px;
    background: #fffaf0;
    border: 1px solid #f3e5c3;
}

.progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #7c6a42;
    font-size: 12px;
    font-weight: 700;
}

.progress-track {
    height: 7px;
    border-radius: 999px;
    overflow: hidden;
    background: #eee7d8;
}

.progress-fill {
    height: 100%;
    border-radius: inherit;
    background: #d4a72c;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    margin-top: 12px;
    gap: 10px;
}

.progress-steps span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
}

.completed-step {
    color: #15803d;
}

.active-step {
    color: #a16207;
}

.pending-step {
    color: #9ca3af;
}

.step-circle {
    width: 19px;
    height: 19px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: 800;
    background: currentColor;
    color: white;
}


/* =========================================================
   FOOTER
========================================================= */

.order-footer {
    margin-top: 18px;
    padding: 16px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-top: 1px solid #f1ede5;
}

.email-info {
    min-width: 0;
    color: #9ca3af;
    font-size: 12px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.view-order-button {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 0 16px;
    border-radius: 11px;
    background: #111827;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: 0.2s ease;
}

.view-order-button:hover {
    background: #292f3a;
    transform: translateY(-1px);
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    padding: 70px 25px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #ece6da;
    border-radius: 20px;
    text-align: center;
}

.empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    background: #fff5df;
    color: #b7791f;
    font-size: 31px;
}

.empty-state h2 {
    margin: 0;
    color: #111827;
    font-size: 21px;
    font-weight: 800;
}

.empty-state p {
    max-width: 450px;
    margin: 8px auto 22px;
    color: #6b7280;
    font-size: 14px;
    line-height: 1.6;
}

.view-orders-button,
.clear-filter-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 18px;
    border-radius: 11px;
    background: #111827;
    color: white;
    border: none;
    text-decoration: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
}


/* =========================================================
   PAGINATION
========================================================= */

.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 28px;
}

.pagination-link {
    min-width: 38px;
    height: 38px;
    padding: 0 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #fff;
    border: 1px solid #e5e7eb;
    color: #4b5563;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

.pagination-link:hover {
    border-color: #d7a93d;
    color: #9a6700;
}

.pagination-link.active {
    background: #111827;
    border-color: #111827;
    color: #fff;
}

.pagination-link.disabled {
    opacity: 0.45;
    cursor: not-allowed;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .header-inner {
        padding: 22px 18px;
        align-items: flex-start;
    }

    .title-area h1 {
        font-size: 22px;
    }

    .order-count {
        display: none;
    }

    .orders-container {
        padding: 24px 16px 45px;
    }

    .toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .refresh-button {
        justify-content: center;
    }

    .order-details {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .order-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .view-order-button {
        width: 100%;
    }

    .progress-steps {
        flex-direction: column;
        gap: 8px;
    }

    .processing-progress {
        margin: 0 16px;
    }
}


@media (max-width: 480px) {

    .header-inner {
        padding: 18px 14px;
    }

    .orders-container {
        padding-left: 12px;
        padding-right: 12px;
    }

    .order-top {
        padding: 17px;
        align-items: flex-start;
        flex-direction: column;
    }

    .order-details {
        padding: 17px;
    }

    .order-footer {
        padding: 15px 17px;
    }

    .status-badge {
        align-self: flex-start;
    }

    .title-icon {
        width: 45px;
        height: 45px;
    }
}
</style>