<script setup>
import { computed, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    CheckCircleOutlined,
    ShoppingOutlined,
    EyeOutlined,
    SearchOutlined,
    ReloadOutlined,
    CalendarOutlined,
    ArrowRightOutlined,
    CheckOutlined,
    CloseCircleOutlined,
    FileDoneOutlined,
} from "@ant-design/icons-vue";

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
});

const search = ref(props.filters?.search || "");
const loading = ref(false);

/*
|--------------------------------------------------------------------------
| NORMALIZE ORDERS
|--------------------------------------------------------------------------
*/

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
| PAGINATION
|--------------------------------------------------------------------------
*/

const pagination = computed(() => {
    if (!props.orders || Array.isArray(props.orders)) {
        return null;
    }

    return props.orders;
});

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const performSearch = () => {
    loading.value = true;

    router.get(
        route("client.orders.completed"),
        {
            search: search.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onFinish: () => {
                loading.value = false;
            },
        }
    );
};

/*
|--------------------------------------------------------------------------
| RESET SEARCH
|--------------------------------------------------------------------------
*/

const resetSearch = () => {
    search.value = "";

    performSearch();
};

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const goToPage = (url) => {
    if (!url || loading.value) {
        return;
    }

    loading.value = true;

    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
            },
        }
    );
};

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    const value = Number(amount || 0);

    return new Intl.NumberFormat("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
};

/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {
    if (!date) {
        return "—";
    }

    const parsed = new Date(date);

    if (Number.isNaN(parsed.getTime())) {
        return date;
    }

    return parsed.toLocaleDateString("en-ZM", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
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
        (total, item) => total + Number(item?.quantity || 0),
        0
    );
};

/*
|--------------------------------------------------------------------------
| VIEW ORDER
|--------------------------------------------------------------------------
*/

const viewOrder = (order) => {
    if (!order?.id) {
        return;
    }

    router.get(route("client.orders.show", order.id));
};
</script>

<template>
    <Head title="My Completed Orders" />

    <UserFashionLayout>
        <div class="completed-orders-page">

            <!-- ========================================================= -->
            <!-- HERO -->
            <!-- ========================================================= -->

            <section class="completed-hero">

                <div class="hero-content">

                    <div class="hero-icon">
                        <CheckCircleOutlined />
                    </div>

                    <div>
                        <span class="hero-eyebrow">
                            ORDER HISTORY
                        </span>

                        <h1>
                            My Completed Orders
                        </h1>

                        <p>
                            View the orders you have successfully received
                            and completed purchases from your account.
                        </p>
                    </div>

                </div>

                <div class="completion-badge">
                    <CheckOutlined />
                    Completed
                </div>

            </section>

            <!-- ========================================================= -->
            <!-- SUCCESS MESSAGE -->
            <!-- ========================================================= -->

            <section class="success-banner">

                <div class="success-icon">
                    <CheckCircleOutlined />
                </div>

                <div class="success-content">
                    <strong>
                        All done!
                    </strong>

                    <span>
                        These orders have been successfully completed.
                    </span>
                </div>

            </section>

            <!-- ========================================================= -->
            <!-- TOOLBAR -->
            <!-- ========================================================= -->

            <section class="orders-toolbar">

                <div class="toolbar-left">

                    <div class="section-title">

                        <div class="title-icon">
                            <FileDoneOutlined />
                        </div>

                        <div>
                            <h2>
                                Completed Orders
                            </h2>

                            <span>
                                {{ pagination?.total ?? orderList.length }}
                                completed
                                {{
                                    (pagination?.total ?? orderList.length) === 1
                                        ? "order"
                                        : "orders"
                                }}
                            </span>
                        </div>

                    </div>

                </div>

                <div class="toolbar-right">

                    <div class="search-box">

                        <SearchOutlined class="search-icon" />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search completed orders..."
                            @keyup.enter="performSearch"
                        />

                        <button
                            v-if="search"
                            type="button"
                            class="clear-search"
                            @click="resetSearch"
                        >
                            ×
                        </button>

                    </div>

                    <button
                        type="button"
                        class="refresh-button"
                        :disabled="loading"
                        @click="performSearch"
                    >
                        <ReloadOutlined :spin="loading" />
                    </button>

                </div>

            </section>

            <!-- ========================================================= -->
            <!-- EMPTY STATE -->
            <!-- ========================================================= -->

            <section
                v-if="orderList.length === 0"
                class="empty-state"
            >

                <div class="empty-icon">
                    <CheckCircleOutlined />
                </div>

                <h2>
                    No completed orders yet
                </h2>

                <p>
                    Once you successfully complete an order,
                    it will appear here.
                </p>

                <Link
                    :href="route('products.index')"
                    class="shop-button"
                >
                    <ShoppingOutlined />
                    Continue Shopping
                    <ArrowRightOutlined />
                </Link>

            </section>

            <!-- ========================================================= -->
            <!-- ORDERS -->
            <!-- ========================================================= -->

            <section
                v-else
                class="orders-list"
            >

                <article
                    v-for="order in orderList"
                    :key="order?.id || order?.order_number"
                    class="order-card completed-card"
                >

                    <!-- TOP -->
                    <div class="order-card-top">

                        <div class="order-reference">

                            <div class="order-check">
                                <CheckOutlined />
                            </div>

                            <div>

                                <span class="reference-label">
                                    ORDER NUMBER
                                </span>

                                <h3>
                                    {{ order?.order_number || "—" }}
                                </h3>

                            </div>

                        </div>

                        <div class="completed-status">

                            <CheckCircleOutlined />

                            <span>
                                Completed
                            </span>

                        </div>

                    </div>

                    <!-- DETAILS -->
                    <div class="order-details">

                        <div class="detail-item">

                            <CalendarOutlined />

                            <div>
                                <span>
                                    Completed
                                </span>

                                <strong>
                                    {{ formatDate(order?.created_at) }}
                                </strong>
                            </div>

                        </div>

                        <div class="detail-item">

                            <ShoppingOutlined />

                            <div>
                                <span>
                                    Items
                                </span>

                                <strong>
                                    {{ itemCount(order) }}
                                    {{
                                        itemCount(order) === 1
                                            ? "item"
                                            : "items"
                                    }}
                                </strong>
                            </div>

                        </div>

                        <div class="detail-item total-detail">

                            <div>
                                <span>
                                    Total
                                </span>

                                <strong>
                                    ZMW
                                    {{ formatMoney(order?.total_amount) }}
                                </strong>
                            </div>

                        </div>

                    </div>

                    <!-- COMPLETION STRIP -->
                    <div class="completion-strip">

                        <div class="completion-line">

                            <span class="completed-dot">
                                <CheckOutlined />
                            </span>

                            <div class="line"></div>

                            <span class="completed-dot">
                                <CheckOutlined />
                            </span>

                        </div>

                        <div class="completion-text">

                            <span>
                                Order placed
                            </span>

                            <strong>
                                Order completed successfully
                            </strong>

                        </div>

                    </div>

                    <!-- ACTIONS -->
                    <div class="order-card-footer">

                        <div class="completed-message">

                            <CheckCircleOutlined />

                            <span>
                                Thank you for shopping with us.
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

            </section>

            <!-- ========================================================= -->
            <!-- PAGINATION -->
            <!-- ========================================================= -->

            <div
                v-if="pagination && pagination.links?.length > 3"
                class="pagination"
            >

                <button
                    v-for="(link, index) in pagination.links"
                    :key="index"
                    type="button"
                    class="pagination-button"
                    :class="{
                        active: link.active,
                        disabled: !link.url,
                    }"
                    :disabled="!link.url || loading"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                />

            </div>

        </div>
    </UserFashionLayout>
</template>

<style scoped>
/* =========================================================
   PAGE
========================================================= */

.completed-orders-page {
    min-height: 100vh;
    background:
        linear-gradient(
            180deg,
            #f7faf8 0%,
            #ffffff 45%,
            #f8faf9 100%
        );
    padding: 32px;
    color: #17211b;
}

/* =========================================================
   HERO
========================================================= */

.completed-hero {
    max-width: 1400px;
    margin: 0 auto 24px;
    padding: 34px 38px;
    border-radius: 24px;
    background:
        linear-gradient(
            135deg,
            #123c2a 0%,
            #176b48 55%,
            #1d8a5c 100%
        );
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    box-shadow: 0 18px 45px rgba(18, 60, 42, 0.18);
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.hero-icon {
    width: 68px;
    height: 68px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.14);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    flex-shrink: 0;
}

.hero-eyebrow {
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.16em;
    opacity: 0.72;
    margin-bottom: 7px;
}

.completed-hero h1 {
    margin: 0;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.completed-hero p {
    margin: 9px 0 0;
    max-width: 650px;
    font-size: 14px;
    line-height: 1.6;
    opacity: 0.82;
}

.completion-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 17px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.13);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

/* =========================================================
   SUCCESS BANNER
========================================================= */

.success-banner {
    max-width: 1400px;
    margin: 0 auto 24px;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 17px 20px;
    border-radius: 16px;
    background: #ecfdf5;
    border: 1px solid #bbf7d0;
}

.success-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #d1fae5;
    color: #047857;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    flex-shrink: 0;
}

.success-content {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.success-content strong {
    color: #065f46;
    font-size: 14px;
}

.success-content span {
    color: #047857;
    font-size: 13px;
}

/* =========================================================
   TOOLBAR
========================================================= */

.orders-toolbar {
    max-width: 1400px;
    margin: 0 auto 20px;
    background: white;
    border: 1px solid #e7eee9;
    border-radius: 18px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 5px 20px rgba(23, 33, 27, 0.04);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.title-icon {
    width: 44px;
    height: 44px;
    border-radius: 13px;
    background: #ecfdf5;
    color: #047857;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.section-title h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
}

.section-title span {
    display: block;
    margin-top: 3px;
    color: #7b877f;
    font-size: 12px;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-box {
    width: 300px;
    height: 42px;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 12px;
    border: 1px solid #dce6df;
    border-radius: 11px;
    background: #fafcfb;
    transition: 0.2s ease;
}

.search-box:focus-within {
    border-color: #168653;
    box-shadow: 0 0 0 3px rgba(22, 134, 83, 0.09);
}

.search-icon {
    color: #87948c;
}

.search-box input {
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    color: #17211b;
}

.search-box input::placeholder {
    color: #9aa59e;
}

.clear-search {
    border: none;
    background: transparent;
    color: #8a958e;
    font-size: 18px;
    cursor: pointer;
}

.refresh-button {
    width: 42px;
    height: 42px;
    border: 1px solid #dce6df;
    border-radius: 11px;
    background: white;
    color: #166534;
    cursor: pointer;
    transition: 0.2s ease;
}

.refresh-button:hover {
    background: #ecfdf5;
    border-color: #a7e4c2;
}

.refresh-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* =========================================================
   ORDERS LIST
========================================================= */

.orders-list {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    gap: 17px;
}

.order-card {
    background: white;
    border: 1px solid #e5ebe7;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(23, 33, 27, 0.045);
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    border-color: #cde7d8;
    box-shadow: 0 12px 30px rgba(23, 33, 27, 0.08);
}

/* =========================================================
   ORDER TOP
========================================================= */

.order-card-top {
    padding: 20px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    border-bottom: 1px solid #edf1ee;
}

.order-reference {
    display: flex;
    align-items: center;
    gap: 13px;
}

.order-check {
    width: 45px;
    height: 45px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ecfdf5;
    color: #047857;
    font-size: 18px;
}

.reference-label {
    display: block;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.12em;
    color: #98a39c;
    margin-bottom: 4px;
}

.order-reference h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    color: #1b2820;
}

.completed-status {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 8px 13px;
    border-radius: 999px;
    background: #ecfdf5;
    color: #047857;
    font-size: 12px;
    font-weight: 800;
}

/* =========================================================
   DETAILS
========================================================= */

.order-details {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    padding: 19px 22px;
    gap: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 11px;
    color: #829087;
}

.detail-item > svg {
    color: #5e9a78;
    font-size: 17px;
}

.detail-item div {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.detail-item span {
    font-size: 11px;
    color: #929d96;
}

.detail-item strong {
    font-size: 13px;
    color: #29362f;
}

.total-detail {
    justify-content: flex-end;
}

.total-detail strong {
    font-size: 16px;
    color: #166534;
}

/* =========================================================
   COMPLETION STRIP
========================================================= */

.completion-strip {
    margin: 0 22px;
    padding: 17px 18px;
    border-radius: 14px;
    background: #f5faf7;
    border: 1px solid #e0eee5;
}

.completion-line {
    display: flex;
    align-items: center;
    width: 100%;
}

.completed-dot {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background: #168653;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    flex-shrink: 0;
}

.line {
    height: 2px;
    background: #54b57e;
    flex: 1;
}

.completion-text {
    margin-top: 9px;
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.completion-text span {
    color: #718077;
    font-size: 11px;
}

.completion-text strong {
    color: #166534;
    font-size: 11px;
}

/* =========================================================
   FOOTER
========================================================= */

.order-card-footer {
    padding: 17px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.completed-message {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #738078;
    font-size: 12px;
}

.completed-message svg {
    color: #168653;
}

.view-order-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    border-radius: 10px;
    padding: 10px 15px;
    background: #173c2a;
    color: white;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s ease;
}

.view-order-button:hover {
    background: #0d5a39;
    transform: translateX(2px);
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    max-width: 1400px;
    margin: 0 auto;
    background: white;
    border: 1px solid #e5ebe7;
    border-radius: 22px;
    padding: 75px 25px;
    text-align: center;
    box-shadow: 0 6px 24px rgba(23, 33, 27, 0.04);
}

.empty-icon {
    width: 76px;
    height: 76px;
    margin: 0 auto 18px;
    border-radius: 22px;
    background: #ecfdf5;
    color: #168653;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
}

.empty-state h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #26352d;
}

.empty-state p {
    max-width: 470px;
    margin: 9px auto 22px;
    color: #7e8a83;
    font-size: 13px;
    line-height: 1.6;
}

.shop-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 17px;
    border-radius: 10px;
    background: #173c2a;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: 0.2s ease;
}

.shop-button:hover {
    background: #0d5a39;
}

/* =========================================================
   PAGINATION
========================================================= */

.pagination {
    max-width: 1400px;
    margin: 25px auto 0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
}

.pagination-button {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border: 1px solid #dce6df;
    border-radius: 9px;
    background: white;
    color: #506057;
    cursor: pointer;
    font-size: 12px;
    transition: 0.2s ease;
}

.pagination-button:hover:not(:disabled) {
    background: #ecfdf5;
    border-color: #a7e4c2;
    color: #166534;
}

.pagination-button.active {
    background: #173c2a;
    border-color: #173c2a;
    color: white;
}

.pagination-button.disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {
    .completed-orders-page {
        padding: 22px 16px;
    }

    .completed-hero {
        padding: 26px;
        align-items: flex-start;
        flex-direction: column;
    }

    .orders-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .toolbar-right {
        width: 100%;
    }

    .search-box {
        flex: 1;
        width: auto;
    }

    .order-details {
        grid-template-columns: 1fr 1fr;
    }

    .total-detail {
        justify-content: flex-start;
    }
}

@media (max-width: 600px) {
    .completed-hero {
        border-radius: 18px;
        padding: 22px;
    }

    .hero-content {
        align-items: flex-start;
    }

    .hero-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        font-size: 24px;
    }

    .completed-hero h1 {
        font-size: 23px;
    }

    .completed-hero p {
        font-size: 12px;
    }

    .completion-badge {
        align-self: flex-start;
    }

    .order-card-top {
        align-items: flex-start;
        flex-direction: column;
    }

    .order-details {
        grid-template-columns: 1fr;
    }

    .order-card-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .view-order-button {
        justify-content: center;
    }

    .completion-text {
        flex-direction: column;
        gap: 4px;
    }

    .toolbar-right {
        flex-direction: column;
    }

    .search-box {
        width: 100%;
    }

    .refresh-button {
        width: 100%;
    }
}
</style>