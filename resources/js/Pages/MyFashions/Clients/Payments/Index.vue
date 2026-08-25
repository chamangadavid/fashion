<script setup>
import { computed, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    ArrowLeftOutlined,
    CreditCardOutlined,
    EyeOutlined,
    SearchOutlined,
    ShoppingOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    CloseCircleOutlined,
    ReloadOutlined,
    WalletOutlined,
} from "@ant-design/icons-vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    payments: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            current_page: 1,
            last_page: 1,
            total: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: "",
            payment_status: "",
        }),
    },
});

/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

const search = ref(props.filters?.search || "");
const paymentStatus = ref(
    props.filters?.payment_status || ""
);

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const applyFilters = () => {
    router.get(
        route("client.my-payments"),
        {
            search: search.value || undefined,
            payment_status:
                paymentStatus.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

/*
|--------------------------------------------------------------------------
| CLEAR FILTERS
|--------------------------------------------------------------------------
*/

const clearFilters = () => {
    search.value = "";
    paymentStatus.value = "";

    router.get(
        route("client.my-payments"),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    return Number(amount || 0).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {
    if (!date) {
        return "-";
    }

    return new Date(date).toLocaleDateString("en-ZM", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

/*
|--------------------------------------------------------------------------
| PAYMENT METHOD
|--------------------------------------------------------------------------
*/

const paymentMethodLabel = (method) => {
    const methods = {
        cash_on_delivery: "Cash on Delivery",
        mobile_money: "Mobile Money",
        card: "Card Payment",
    };

    return methods[method] || method || "Not specified";
};

/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

const paymentStatusClass = (status) => {
    switch (status) {
        case "paid":
            return "status-paid";

        case "pending":
            return "status-pending";

        case "failed":
            return "status-failed";

        case "refunded":
            return "status-refunded";

        default:
            return "status-default";
    }
};

const paymentStatusLabel = (status) => {
    if (!status) {
        return "Pending";
    }

    return status.charAt(0).toUpperCase() + status.slice(1);
};

const paymentStatusIcon = (status) => {
    switch (status) {
        case "paid":
            return CheckCircleOutlined;

        case "failed":
            return CloseCircleOutlined;

        case "refunded":
            return ReloadOutlined;

        default:
            return ClockCircleOutlined;
    }
};

/*
|--------------------------------------------------------------------------
| ORDER STATUS
|--------------------------------------------------------------------------
*/

const orderStatusClass = (status) => {
    switch (status) {
        case "completed":
            return "order-completed";

        case "processing":
            return "order-processing";

        case "cancelled":
            return "order-cancelled";

        default:
            return "order-pending";
    }
};

/*
|--------------------------------------------------------------------------
| STATISTICS
|--------------------------------------------------------------------------
*/

const totalPayments = computed(() => {
    return props.payments?.total || 0;
});

const totalAmount = computed(() => {
    return (props.payments?.data || []).reduce(
        (total, payment) => {
            return total + Number(payment.total_amount || 0);
        },
        0
    );
});

const paidAmount = computed(() => {
    return (props.payments?.data || [])
        .filter((payment) => payment.payment_status === "paid")
        .reduce(
            (total, payment) => {
                return total + Number(payment.total_amount || 0);
            },
            0
        );
});
</script>

<template>
    <UserFashionLayout>
        <Head title="Payment History" />

        <div class="payments-page">

            <!-- =========================================================
                 HEADER
            ========================================================== -->

            <div class="page-header">

                <div class="header-left">

                    <Link
                        :href="route('client.dashboard')"
                        class="back-button"
                    >
                        <ArrowLeftOutlined />

                        <span>Back to Dashboard</span>
                    </Link>

                    <div class="title-section">

                        <div class="title-icon">
                            <WalletOutlined />
                        </div>

                        <div>
                            <h1>Payment History</h1>

                            <p>
                                View and track all your order payments.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 SUMMARY CARDS
            ========================================================== -->

            <div class="summary-grid">

                <!-- TOTAL PAYMENTS -->

                <div class="summary-card">

                    <div class="summary-icon">
                        <CreditCardOutlined />
                    </div>

                    <div class="summary-content">

                        <span class="summary-label">
                            Total Orders
                        </span>

                        <strong>
                            {{ totalPayments }}
                        </strong>

                    </div>

                </div>


                <!-- TOTAL AMOUNT -->

                <div class="summary-card">

                    <div class="summary-icon">
                        <ShoppingOutlined />
                    </div>

                    <div class="summary-content">

                        <span class="summary-label">
                            Order Value
                        </span>

                        <strong>
                            K{{ formatMoney(totalAmount) }}
                        </strong>

                    </div>

                </div>


                <!-- PAID -->

                <div class="summary-card">

                    <div class="summary-icon">
                        <CheckCircleOutlined />
                    </div>

                    <div class="summary-content">

                        <span class="summary-label">
                            Paid
                        </span>

                        <strong>
                            K{{ formatMoney(paidAmount) }}
                        </strong>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 FILTERS
            ========================================================== -->

            <div class="filter-card">

                <div class="search-box">

                    <SearchOutlined />

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search order number..."
                        @keyup.enter="applyFilters"
                    />

                </div>


                <select
                    v-model="paymentStatus"
                    class="status-select"
                    @change="applyFilters"
                >
                    <option value="">
                        All Payment Statuses
                    </option>

                    <option value="pending">
                        Pending
                    </option>

                    <option value="paid">
                        Paid
                    </option>

                    <option value="failed">
                        Failed
                    </option>

                    <option value="refunded">
                        Refunded
                    </option>
                </select>


                <button
                    v-if="search || paymentStatus"
                    type="button"
                    class="clear-button"
                    @click="clearFilters"
                >
                    Clear
                </button>

                <button
                    type="button"
                    class="search-button"
                    @click="applyFilters"
                >
                    <SearchOutlined />
                    Search
                </button>

            </div>


            <!-- =========================================================
                 PAYMENT TABLE
            ========================================================== -->

            <div class="payment-card">

                <div class="card-header">

                    <div>
                        <h2>Payment Transactions</h2>

                        <p>
                            Your recent orders and payment status.
                        </p>
                    </div>

                    <span class="transaction-count">
                        {{ payments.total || 0 }}
                        transactions
                    </span>

                </div>


                <!-- DESKTOP TABLE -->

                <div
                    v-if="payments.data?.length"
                    class="table-wrapper"
                >

                    <table class="payments-table">

                        <thead>

                            <tr>
                                <th>Order</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr
                                v-for="payment in payments.data"
                                :key="payment.id"
                            >

                                <!-- ORDER -->

                                <td>

                                    <div class="order-number">

                                        <ShoppingOutlined />

                                        <div>
                                            <strong>
                                                {{
                                                    payment.order_number
                                                }}
                                            </strong>

                                            <small>
                                                Order #{{ payment.id }}
                                            </small>
                                        </div>

                                    </div>

                                </td>


                                <!-- DATE -->

                                <td>
                                    <span class="date">
                                        {{
                                            formatDate(
                                                payment.created_at
                                            )
                                        }}
                                    </span>
                                </td>


                                <!-- PAYMENT METHOD -->

                                <td>

                                    <div class="payment-method">

                                        <CreditCardOutlined />

                                        <span>
                                            {{
                                                paymentMethodLabel(
                                                    payment.payment_method
                                                )
                                            }}
                                        </span>

                                    </div>

                                </td>


                                <!-- AMOUNT -->

                                <td>

                                    <strong class="amount">
                                        K{{
                                            formatMoney(
                                                payment.total_amount
                                            )
                                        }}
                                    </strong>

                                </td>


                                <!-- PAYMENT STATUS -->

                                <td>

                                    <span
                                        class="status-badge"
                                        :class="
                                            paymentStatusClass(
                                                payment.payment_status
                                            )
                                        "
                                    >

                                        <component
                                            :is="
                                                paymentStatusIcon(
                                                    payment.payment_status
                                                )
                                            "
                                        />

                                        {{
                                            paymentStatusLabel(
                                                payment.payment_status
                                            )
                                        }}

                                    </span>

                                </td>


                                <!-- ORDER STATUS -->

                                <td>

                                    <span
                                        class="order-status"
                                        :class="
                                            orderStatusClass(
                                                payment.status
                                            )
                                        "
                                    >
                                        {{
                                            payment.status
                                                ? payment.status
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                  payment.status.slice(1)
                                                : "Pending"
                                        }}
                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <Link
                                        :href="
                                            route(
                                                'client.orders.show',
                                                payment.id
                                            )
                                        "
                                        class="view-button"
                                    >

                                        <EyeOutlined />

                                        View

                                    </Link>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- =====================================================
                     EMPTY STATE
                ====================================================== -->

                <div
                    v-else
                    class="empty-state"
                >

                    <div class="empty-icon">
                        <WalletOutlined />
                    </div>

                    <h3>
                        No Payment History
                    </h3>

                    <p>
                        You haven't placed any orders yet.
                        Your payment history will appear here
                        after you place your first order.
                    </p>

                    <Link
                        :href="route('client.products')"
                        class="shop-button"
                    >
                        <ShoppingOutlined />
                        Start Shopping
                    </Link>

                </div>


                <!-- =====================================================
                     PAGINATION
                ====================================================== -->

                <div
                    v-if="
                        payments.links &&
                        payments.links.length > 3
                    "
                    class="pagination"
                >

                    <Link
                        v-for="link in payments.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        class="pagination-link"
                        :class="{
                            active: link.active,
                            disabled: !link.url,
                        }"
                        v-html="link.label"
                    />

                </div>

            </div>

        </div>
    </UserFashionLayout>
</template>

<style scoped>
/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.payments-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 35px 25px 60px;
    color: #1f2937;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {
    margin-bottom: 30px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 20px;
    transition: 0.2s ease;
}

.back-button:hover {
    color: #0f766e;
}

.title-section {
    display: flex;
    align-items: center;
    gap: 16px;
}

.title-icon {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ecfdf5;
    color: #0f766e;
    font-size: 25px;
}

.title-section h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.title-section p {
    margin: 5px 0 0;
    color: #6b7280;
}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}

.summary-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #f0fdfa;
    color: #0f766e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.summary-label {
    font-size: 13px;
    color: #6b7280;
}

.summary-content strong {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
}


/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

.filter-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 25px;
    display: flex;
    gap: 12px;
    align-items: center;
}

.search-box {
    flex: 1;
    max-width: 500px;
    height: 44px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    display: flex;
    align-items: center;
    padding: 0 13px;
    gap: 10px;
    color: #9ca3af;
}

.search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 14px;
    background: transparent;
}

.status-select {
    height: 44px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 0 14px;
    background: white;
    outline: none;
    cursor: pointer;
}

.search-button,
.clear-button {
    height: 44px;
    padding: 0 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.search-button {
    background: #0f766e;
    color: white;
}

.search-button:hover {
    background: #115e59;
}

.clear-button {
    background: #f3f4f6;
    color: #374151;
}


/*
|--------------------------------------------------------------------------
| PAYMENT CARD
|--------------------------------------------------------------------------
*/

.payment-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
}

.card-header p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.transaction-count {
    font-size: 13px;
    color: #6b7280;
}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-wrapper {
    overflow-x: auto;
}

.payments-table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table th {
    padding: 15px 20px;
    text-align: left;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #6b7280;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.payments-table td {
    padding: 18px 20px;
    border-bottom: 1px solid #f1f5f9;
    white-space: nowrap;
}

.payments-table tbody tr:hover {
    background: #f9fafb;
}

.order-number {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #0f766e;
}

.order-number div {
    display: flex;
    flex-direction: column;
}

.order-number strong {
    color: #111827;
    font-size: 14px;
}

.order-number small {
    color: #9ca3af;
    margin-top: 3px;
}

.date {
    color: #6b7280;
    font-size: 14px;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #4b5563;
    font-size: 14px;
}

.amount {
    color: #111827;
    font-size: 15px;
}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status-badge,
.order-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status-paid {
    background: #dcfce7;
    color: #166534;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-failed {
    background: #fee2e2;
    color: #991b1b;
}

.status-refunded {
    background: #ede9fe;
    color: #6d28d9;
}

.status-default {
    background: #f3f4f6;
    color: #4b5563;
}

.order-completed {
    background: #dcfce7;
    color: #166534;
}

.order-processing {
    background: #dbeafe;
    color: #1d4ed8;
}

.order-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.order-pending {
    background: #fef3c7;
    color: #92400e;
}


/*
|--------------------------------------------------------------------------
| VIEW BUTTON
|--------------------------------------------------------------------------
*/

.view-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 13px;
    border-radius: 8px;
    background: #f0fdfa;
    color: #0f766e;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s ease;
}

.view-button:hover {
    background: #ccfbf1;
}


/*
|--------------------------------------------------------------------------
| EMPTY STATE
|--------------------------------------------------------------------------
*/

.empty-state {
    text-align: center;
    padding: 70px 25px;
}

.empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: #f0fdfa;
    color: #0f766e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.empty-state h3 {
    margin: 0 0 8px;
    font-size: 20px;
}

.empty-state p {
    max-width: 500px;
    margin: 0 auto 25px;
    color: #6b7280;
    line-height: 1.6;
}

.shop-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #0f766e;
    color: white;
    padding: 11px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
}

.shop-button:hover {
    background: #115e59;
}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.pagination {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 22px;
    border-top: 1px solid #e5e7eb;
}

.pagination-link {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #374151;
    text-decoration: none;
    border: 1px solid #e5e7eb;
    font-size: 13px;
}

.pagination-link:hover {
    border-color: #0f766e;
    color: #0f766e;
}

.pagination-link.active {
    background: #0f766e;
    border-color: #0f766e;
    color: white;
}

.pagination-link.disabled {
    opacity: 0.4;
    pointer-events: none;
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .filter-card {
        flex-wrap: wrap;
    }

    .search-box {
        max-width: none;
        width: 100%;
        flex-basis: 100%;
    }

}

@media (max-width: 600px) {

    .payments-page {
        padding: 25px 15px 45px;
    }

    .title-section h1 {
        font-size: 23px;
    }

    .title-section p {
        font-size: 13px;
    }

    .filter-card {
        flex-direction: column;
        align-items: stretch;
    }

    .status-select,
    .search-button,
    .clear-button {
        width: 100%;
    }

    .card-header {
        align-items: flex-start;
        gap: 10px;
        flex-direction: column;
    }

}
</style>