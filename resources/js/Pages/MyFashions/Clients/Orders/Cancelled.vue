<script setup>
import { computed, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    CloseCircleOutlined,
    ShoppingOutlined,
    EyeOutlined,
    SearchOutlined,
    ReloadOutlined,
    CalendarOutlined,
    ArrowRightOutlined,
    CloseOutlined,
    FileDoneOutlined,
    ExclamationCircleOutlined,
} from "@ant-design/icons-vue";

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

    filters: {
        type: Object,
        default: () => ({
            search: "",
        }),
    },
});

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const search = ref(props.filters?.search || "");
const loading = ref(false);

/*
|--------------------------------------------------------------------------
| NORMALIZE ORDERS
|--------------------------------------------------------------------------
|
| Laravel paginate() returns an object:
|
| {
|     data: [],
|     current_page: 1,
|     total: 10,
|     ...
| }
|
| This prevents:
|
| props.orders.filter is not a function
|
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
        route("client.orders.cancelled"),
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
| REFRESH
|--------------------------------------------------------------------------
*/

const refreshOrders = () => {
    loading.value = true;

    router.reload({
        only: ["orders", "filters"],

        onFinish: () => {
            loading.value = false;
        },
    });
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
        (total, item) => {
            return total + Number(item?.quantity || 0);
        },
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

    router.get(
        route("client.orders.show", order.id)
    );
};
</script>

<template>

    <Head title="My Cancelled Orders" />

    <UserFashionLayout>

        <div class="cancelled-orders-page">

            <!-- ===================================================== -->
            <!-- HERO -->
            <!-- ===================================================== -->

            <section class="cancelled-hero">

                <div class="hero-content">

                    <div class="hero-icon">
                        <CloseCircleOutlined />
                    </div>

                    <div>

                        <span class="hero-eyebrow">
                            ORDER HISTORY
                        </span>

                        <h1>
                            My Cancelled Orders
                        </h1>

                        <p>
                            View orders that were cancelled and
                            are no longer being processed.
                        </p>

                    </div>

                </div>

                <div class="cancelled-badge">

                    <CloseOutlined />

                    Cancelled

                </div>

            </section>

            <!-- ===================================================== -->
            <!-- INFORMATION BANNER -->
            <!-- ===================================================== -->

            <section class="cancelled-banner">

                <div class="banner-icon">
                    <ExclamationCircleOutlined />
                </div>

                <div class="banner-content">

                    <strong>
                        Cancelled orders
                    </strong>

                    <span>
                        These orders will not be processed or delivered.
                    </span>

                </div>

            </section>

            <!-- ===================================================== -->
            <!-- TOOLBAR -->
            <!-- ===================================================== -->

            <section class="orders-toolbar">

                <div class="toolbar-left">

                    <div class="section-title">

                        <div class="title-icon">
                            <FileDoneOutlined />
                        </div>

                        <div>

                            <h2>
                                Cancelled Orders
                            </h2>

                            <span>
                                {{ pagination?.total ?? orderList.length }}

                                {{
                                    (pagination?.total ?? orderList.length) === 1
                                        ? "cancelled order"
                                        : "cancelled orders"
                                }}
                            </span>

                        </div>

                    </div>

                </div>

                <div class="toolbar-right">

                    <!-- SEARCH -->

                    <div class="search-box">

                        <SearchOutlined
                            class="search-icon"
                        />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search cancelled orders..."
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

                    <!-- REFRESH -->

                    <button
                        type="button"
                        class="refresh-button"
                        :disabled="loading"
                        @click="refreshOrders"
                    >

                        <ReloadOutlined
                            :spin="loading"
                        />

                    </button>

                </div>

            </section>

            <!-- ===================================================== -->
            <!-- EMPTY STATE -->
            <!-- ===================================================== -->

            <section
                v-if="orderList.length === 0"
                class="empty-state"
            >

                <div class="empty-icon">

                    <CloseCircleOutlined />

                </div>

                <h2>
                    No cancelled orders
                </h2>

                <p>
                    You don't have any cancelled orders.
                    Your cancelled orders will appear here if an order
                    is cancelled.
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

            <!-- ===================================================== -->
            <!-- ORDER LIST -->
            <!-- ===================================================== -->

            <section
                v-else
                class="orders-list"
            >

                <article
                    v-for="order in orderList"
                    :key="order?.id || order?.order_number"
                    class="order-card cancelled-card"
                >

                    <!-- ================================================= -->
                    <!-- ORDER HEADER -->
                    <!-- ================================================= -->

                    <div class="order-card-top">

                        <div class="order-reference">

                            <div class="order-cancel-icon">

                                <CloseOutlined />

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

                        <div class="cancelled-status">

                            <CloseCircleOutlined />

                            <span>
                                Cancelled
                            </span>

                        </div>

                    </div>

                    <!-- ================================================= -->
                    <!-- DETAILS -->
                    <!-- ================================================= -->

                    <div class="order-details">

                        <!-- DATE -->

                        <div class="detail-item">

                            <CalendarOutlined />

                            <div>

                                <span>
                                    Cancelled
                                </span>

                                <strong>
                                    {{ formatDate(order?.created_at) }}
                                </strong>

                            </div>

                        </div>

                        <!-- ITEMS -->

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

                        <!-- TOTAL -->

                        <div class="detail-item total-detail">

                            <div>

                                <span>
                                    Order Total
                                </span>

                                <strong>
                                    ZMW
                                    {{ formatMoney(order?.total_amount) }}
                                </strong>

                            </div>

                        </div>

                    </div>

                    <!-- ================================================= -->
                    <!-- CANCELLED STRIP -->
                    <!-- ================================================= -->

                    <div class="cancelled-strip">

                        <div class="cancelled-progress">

                            <span class="cancelled-dot">

                                <CloseOutlined />

                            </span>

                            <div class="cancelled-line"></div>

                            <span class="cancelled-dot faded">

                                <CloseOutlined />

                            </span>

                        </div>

                        <div class="cancelled-text">

                            <span>
                                Order placed
                            </span>

                            <strong>
                                Order cancelled
                            </strong>

                        </div>

                    </div>

                    <!-- ================================================= -->
                    <!-- FOOTER -->
                    <!-- ================================================= -->

                    <div class="order-card-footer">

                        <div class="cancelled-message">

                            <CloseCircleOutlined />

                            <span>
                                This order will not be processed.
                            </span>

                        </div>

                        <!-- <button
                            type="button"
                            class="view-order-button"
                            @click="viewOrder(order)"
                        >

                            <EyeOutlined />

                            View Order

                            <ArrowRightOutlined />

                        </button> -->

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

            <!-- ===================================================== -->
            <!-- PAGINATION -->
            <!-- ===================================================== -->

            <div
                v-if="
                    pagination &&
                    pagination.links &&
                    pagination.links.length > 3
                "
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

.cancelled-orders-page {
    min-height: 100vh;

    background:
        linear-gradient(
            180deg,
            #fffafa 0%,
            #ffffff 45%,
            #fafafa 100%
        );

    padding: 32px;

    color: #241919;
}


/* =========================================================
   HERO
========================================================= */

.cancelled-hero {

    max-width: 1400px;

    margin: 0 auto 24px;

    padding: 34px 38px;

    border-radius: 24px;

    background:
        linear-gradient(
            135deg,
            #5c1717 0%,
            #8f2020 55%,
            #b83232 100%
        );

    color: white;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 30px;

    box-shadow:
        0 18px 45px rgba(92, 23, 23, 0.18);
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

    background:
        rgba(255, 255, 255, 0.14);

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


.cancelled-hero h1 {

    margin: 0;

    font-size: 30px;

    line-height: 1.15;

    font-weight: 800;

    letter-spacing: -0.02em;
}


.cancelled-hero p {

    margin: 9px 0 0;

    max-width: 650px;

    font-size: 14px;

    line-height: 1.6;

    opacity: 0.82;
}


.cancelled-badge {

    display: flex;

    align-items: center;

    gap: 8px;

    padding: 11px 17px;

    border-radius: 999px;

    background:
        rgba(255, 255, 255, 0.13);

    border:
        1px solid rgba(255, 255, 255, 0.2);

    font-size: 13px;

    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   INFORMATION BANNER
========================================================= */

.cancelled-banner {

    max-width: 1400px;

    margin: 0 auto 24px;

    display: flex;

    align-items: center;

    gap: 14px;

    padding: 17px 20px;

    border-radius: 16px;

    background: #fff1f2;

    border: 1px solid #fecdd3;
}


.banner-icon {

    width: 42px;

    height: 42px;

    border-radius: 12px;

    background: #ffe4e6;

    color: #be123c;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 21px;

    flex-shrink: 0;
}


.banner-content {

    display: flex;

    flex-direction: column;

    gap: 3px;
}


.banner-content strong {

    color: #9f1239;

    font-size: 14px;
}


.banner-content span {

    color: #be123c;

    font-size: 13px;
}


/* =========================================================
   TOOLBAR
========================================================= */

.orders-toolbar {

    max-width: 1400px;

    margin: 0 auto 20px;

    background: white;

    border: 1px solid #eee3e3;

    border-radius: 18px;

    padding: 18px 20px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    box-shadow:
        0 5px 20px rgba(40, 20, 20, 0.04);
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

    background: #fff1f2;

    color: #be123c;

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

    color: #958989;

    font-size: 12px;
}


.toolbar-right {

    display: flex;

    align-items: center;

    gap: 10px;
}


/* =========================================================
   SEARCH
========================================================= */

.search-box {

    width: 300px;

    height: 42px;

    display: flex;

    align-items: center;

    gap: 9px;

    padding: 0 12px;

    border: 1px solid #e4dcdc;

    border-radius: 11px;

    background: #fdfafa;

    transition: 0.2s ease;
}


.search-box:focus-within {

    border-color: #dc6b6b;

    box-shadow:
        0 0 0 3px rgba(220, 107, 107, 0.09);
}


.search-icon {

    color: #9a8989;
}


.search-box input {

    width: 100%;

    border: none;

    outline: none;

    background: transparent;

    font-size: 13px;

    color: #241919;
}


.search-box input::placeholder {

    color: #aa9c9c;
}


.clear-search {

    border: none;

    background: transparent;

    color: #9a8989;

    font-size: 18px;

    cursor: pointer;
}


/* =========================================================
   REFRESH
========================================================= */

.refresh-button {

    width: 42px;

    height: 42px;

    border: 1px solid #e4dcdc;

    border-radius: 11px;

    background: white;

    color: #a12b2b;

    cursor: pointer;

    transition: 0.2s ease;
}


.refresh-button:hover {

    background: #fff1f2;

    border-color: #f1b7bd;
}


.refresh-button:disabled {

    opacity: 0.5;

    cursor: not-allowed;
}


/* =========================================================
   ORDER LIST
========================================================= */

.orders-list {

    max-width: 1400px;

    margin: 0 auto;

    display: grid;

    gap: 17px;
}


.order-card {

    background: white;

    border: 1px solid #eee2e2;

    border-radius: 20px;

    overflow: hidden;

    box-shadow:
        0 6px 24px rgba(40, 20, 20, 0.045);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease;
}


.order-card:hover {

    transform: translateY(-2px);

    border-color: #f2caca;

    box-shadow:
        0 12px 30px rgba(40, 20, 20, 0.08);
}


/* =========================================================
   ORDER HEADER
========================================================= */

.order-card-top {

    padding: 20px 22px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    border-bottom: 1px solid #f1e9e9;
}


.order-reference {

    display: flex;

    align-items: center;

    gap: 13px;
}


.order-cancel-icon {

    width: 45px;

    height: 45px;

    border-radius: 13px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #fff1f2;

    color: #be123c;

    font-size: 18px;
}


.reference-label {

    display: block;

    font-size: 9px;

    font-weight: 800;

    letter-spacing: 0.12em;

    color: #a49a9a;

    margin-bottom: 4px;
}


.order-reference h3 {

    margin: 0;

    font-size: 15px;

    font-weight: 800;

    color: #342525;
}


.cancelled-status {

    display: flex;

    align-items: center;

    gap: 7px;

    padding: 8px 13px;

    border-radius: 999px;

    background: #fff1f2;

    color: #be123c;

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

    color: #8e8080;
}


.detail-item > svg {

    color: #c05b5b;

    font-size: 17px;
}


.detail-item div {

    display: flex;

    flex-direction: column;

    gap: 3px;
}


.detail-item span {

    font-size: 11px;

    color: #9d9292;
}


.detail-item strong {

    font-size: 13px;

    color: #3c3030;
}


.total-detail {

    justify-content: flex-end;
}


.total-detail strong {

    font-size: 16px;

    color: #9f1239;
}


/* =========================================================
   CANCELLED STRIP
========================================================= */

.cancelled-strip {

    margin: 0 22px;

    padding: 17px 18px;

    border-radius: 14px;

    background: #fff8f8;

    border: 1px solid #f4dfdf;
}


.cancelled-progress {

    display: flex;

    align-items: center;

    width: 100%;
}


.cancelled-dot {

    width: 25px;

    height: 25px;

    border-radius: 50%;

    background: #c53030;

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 10px;

    flex-shrink: 0;
}


.cancelled-dot.faded {

    background: #e8a6a6;

}


.cancelled-line {

    height: 2px;

    background:
        linear-gradient(
            90deg,
            #d66a6a,
            #efc1c1
        );

    flex: 1;
}


.cancelled-text {

    margin-top: 9px;

    display: flex;

    justify-content: space-between;

    gap: 20px;
}


.cancelled-text span {

    color: #847878;

    font-size: 11px;
}


.cancelled-text strong {

    color: #a12b2b;

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


.cancelled-message {

    display: flex;

    align-items: center;

    gap: 7px;

    color: #847878;

    font-size: 12px;
}


.cancelled-message svg {

    color: #be123c;
}


.view-order-button {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    border: none;

    border-radius: 10px;

    padding: 10px 15px;

    background: #401b1b;

    color: white;

    font-size: 12px;

    font-weight: 700;

    cursor: pointer;

    transition: 0.2s ease;
}


.view-order-button:hover {

    background: #711f1f;

    transform: translateX(2px);
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {

    max-width: 1400px;

    margin: 0 auto;

    background: white;

    border: 1px solid #eee3e3;

    border-radius: 22px;

    padding: 75px 25px;

    text-align: center;

    box-shadow:
        0 6px 24px rgba(40, 20, 20, 0.04);
}


.empty-icon {

    width: 76px;

    height: 76px;

    margin: 0 auto 18px;

    border-radius: 22px;

    background: #f6f6f6;

    color: #888;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 34px;
}


.empty-state h2 {

    margin: 0;

    font-size: 20px;

    font-weight: 800;

    color: #332828;
}


.empty-state p {

    max-width: 470px;

    margin: 9px auto 22px;

    color: #8b7f7f;

    font-size: 13px;

    line-height: 1.6;
}


.shop-button {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 11px 17px;

    border-radius: 10px;

    background: #401b1b;

    color: white;

    text-decoration: none;

    font-size: 13px;

    font-weight: 700;

    transition: 0.2s ease;
}


.shop-button:hover {

    background: #711f1f;
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

    border: 1px solid #e4dcdc;

    border-radius: 9px;

    background: white;

    color: #675858;

    cursor: pointer;

    font-size: 12px;

    transition: 0.2s ease;
}


.pagination-button:hover:not(:disabled) {

    background: #fff1f2;

    border-color: #f1b7bd;

    color: #9f1239;
}


.pagination-button.active {

    background: #401b1b;

    border-color: #401b1b;

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

    .cancelled-orders-page {

        padding: 22px 16px;
    }


    .cancelled-hero {

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

    .cancelled-hero {

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


    .cancelled-hero h1 {

        font-size: 23px;
    }


    .cancelled-hero p {

        font-size: 12px;
    }


    .cancelled-badge {

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


    .cancelled-text {

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