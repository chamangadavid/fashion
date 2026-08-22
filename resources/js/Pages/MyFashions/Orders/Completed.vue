<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({

    orders: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: 'completed',
            payment_method: '',
        }),
    },

})

const search = ref(props.filters?.search || '')

const paymentMethod = ref(
    props.filters?.payment_method || ''
)


const applyFilters = () => {

    router.get(
        '/fashion/orders/completed',
        {
            search: search.value || undefined,
            payment_method: paymentMethod.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )

}


const clearFilters = () => {

    search.value = ''
    paymentMethod.value = ''

    router.get(
        '/fashion/orders/completed',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )

}


const goToPage = (url) => {

    if (!url) {
        return
    }

    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}


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


const paymentLabel = (method) => {

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

}

</script>


<template>

    <Head title="Completed Orders" />

    <MyFashionLayout>

        <div class="orders-page">

            <!-- HEADER -->

            <div class="page-header">

                <div>

                    <h1>
                        Completed Orders
                    </h1>

                    <p>
                        View successfully completed customer orders.
                    </p>

                </div>

            </div>


            <!-- FILTERS -->

            <div class="filter-card">

                <div class="filter-grid">

                    <div class="filter-group search-group">

                        <label>
                            Search
                        </label>

                        <div class="search-wrapper">

                            <span class="search-icon">
                                🔍
                            </span>

                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search order or customer..."
                                @keyup.enter="applyFilters"
                            />

                        </div>

                    </div>


                    <div class="filter-group">

                        <label>
                            Payment Method
                        </label>

                        <select v-model="paymentMethod">

                            <option value="">
                                All Payment Methods
                            </option>

                            <option value="cash_on_delivery">
                                Cash on Delivery
                            </option>

                            <option value="mobile_money">
                                Mobile Money
                            </option>

                            <option value="card">
                                Visa / Card
                            </option>

                        </select>

                    </div>


                    <div class="filter-actions">

                        <button
                            type="button"
                            class="filter-button"
                            @click="applyFilters"
                        >
                            Search
                        </button>

                        <button
                            type="button"
                            class="clear-button"
                            @click="clearFilters"
                        >
                            Clear
                        </button>

                    </div>

                </div>

            </div>


            <!-- SUMMARY -->

            <div class="summary-row">

                <div>

                    <strong>
                        {{ orders.total || 0 }}
                    </strong>

                    <span>
                        Completed Orders
                    </span>

                </div>

                <div>

                    <strong>
                        {{ orders.data?.length || 0 }}
                    </strong>

                    <span>
                        Current Page
                    </span>

                </div>

                <div>

                    <strong>
                        Completed
                    </strong>

                    <span>
                        Order Status
                    </span>

                </div>

            </div>


            <!-- TABLE -->

            <div class="content-card">

                <div
                    v-if="orders.data?.length"
                    class="table-wrapper"
                >

                    <table class="orders-table">

                        <thead>

                            <tr>

                                <th>
                                    Order
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Items
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="actions-column">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="order in orders.data"
                                :key="order.id"
                            >

                                <td>

                                    <strong class="order-number">
                                        {{ order.order_number }}
                                    </strong>

                                    <span class="date">
                                        {{ formatDate(order.created_at) }}
                                    </span>

                                </td>


                                <td>

                                    <strong class="customer-name">

                                        {{ order.shipping_first_name }}
                                        {{ order.shipping_last_name }}

                                    </strong>

                                    <span class="customer-email">
                                        {{ order.customer_email }}
                                    </span>

                                </td>


                                <td>

                                    <span class="item-count">

                                        {{ order.items?.length || 0 }}

                                        {{
                                            order.items?.length === 1
                                                ? 'Item'
                                                : 'Items'
                                        }}

                                    </span>

                                </td>


                                <td>

                                    <strong class="amount">

                                        ZMW
                                        {{ Number(order.total_amount || 0).toFixed(2) }}

                                    </strong>

                                </td>


                                <td>

                                    <span class="payment-badge">

                                        {{ paymentLabel(order.payment_method) }}

                                    </span>

                                </td>


                                <td>

                                    <span class="status-badge status-completed">

                                        <span class="status-dot"></span>

                                        Completed

                                    </span>

                                </td>


                                <td>

                                    <div class="actions">

                                        <Link
                                            :href="`/fashion/orders/${order.id}`"
                                            class="action-button view"
                                            title="View Order"
                                        >
                                            👁
                                        </Link>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- EMPTY -->

                <div
                    v-else
                    class="empty-state"
                >

                    <div class="empty-icon">
                        ✅
                    </div>

                    <h2>
                        No Completed Orders
                    </h2>

                    <p>
                        There are currently no completed orders.
                    </p>

                    <button
                        v-if="search || paymentMethod"
                        type="button"
                        class="clear-empty-button"
                        @click="clearFilters"
                    >
                        Clear Filters
                    </button>

                </div>


                <!-- PAGINATION -->

                <div
                    v-if="
                        orders.data?.length &&
                        orders.last_page > 1
                    "
                    class="pagination-wrapper"
                >

                    <div class="pagination-info">

                        Showing

                        <strong>
                            {{ orders.from }}
                        </strong>

                        to

                        <strong>
                            {{ orders.to }}
                        </strong>

                        of

                        <strong>
                            {{ orders.total }}
                        </strong>

                        orders

                    </div>


                    <div class="pagination">

                        <button
                            v-for="(link, index) in orders.links"
                            :key="index"
                            type="button"
                            :disabled="!link.url"
                            :class="[
                                'page-button',
                                {
                                    active: link.active,
                                }
                            ]"
                            @click="goToPage(link.url)"
                        >

                            <span v-html="link.label"></span>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.orders-page {
    padding: 10px;
    width: 100%;
}


/* HEADER */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.page-header p {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 14px;
}


/* FILTER */

.filter-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 18px;
    border: 1px solid #edf0f2;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
}

.filter-grid {
    display: grid;
    grid-template-columns: minmax(250px, 1fr) 220px auto;
    align-items: end;
    gap: 15px;
}

.filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 12px;
    font-weight: 600;
}

.filter-group input,
.filter-group select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #dfe3e8;
    border-radius: 7px;
    background: white;
    color: #374151;
    font-size: 13px;
    outline: none;
}

.filter-group input:focus,
.filter-group select:focus {
    border-color: #075c59;
    box-shadow: 0 0 0 3px rgba(7,92,89,.08);
}

.search-wrapper {
    position: relative;
}

.search-wrapper input {
    padding-left: 36px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    opacity: .6;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.filter-button,
.clear-button {
    height: 40px;
    padding: 0 17px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.filter-button {
    border: none;
    background: #111827;
    color: white;
}

.filter-button:hover {
    background: #075c59;
}

.clear-button {
    border: 1px solid #dfe3e8;
    background: white;
    color: #555;
}


/* SUMMARY */

.summary-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 18px;
}

.summary-row > div {
    background: white;
    border: 1px solid #edf0f2;
    border-radius: 10px;
    padding: 15px 18px;
}

.summary-row strong {
    display: block;
    color: #111827;
    font-size: 22px;
    font-weight: 700;
}

.summary-row span {
    display: block;
    margin-top: 3px;
    color: #777;
    font-size: 11px;
}


/* CARD */

.content-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #edf0f2;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    overflow: hidden;
}


/* TABLE */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    min-width: 1050px;
    border-collapse: collapse;
}

.orders-table th {
    padding: 14px 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    color: #6b7280;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .4px;
    text-align: left;
    white-space: nowrap;
}

.orders-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f1f2;
    color: #374151;
    font-size: 12px;
    vertical-align: middle;
}

.orders-table tbody tr:hover {
    background: #fcfdfd;
}


/* ORDER */

.order-number {
    display: block;
    color: #111827;
    font-size: 12px;
}

.date {
    display: block;
    margin-top: 4px;
    color: #9ca3af;
    font-size: 10px;
}


/* CUSTOMER */

.customer-name {
    display: block;
    color: #111827;
    font-size: 12px;
}

.customer-email {
    display: block;
    margin-top: 4px;
    color: #9ca3af;
    font-size: 10px;
}


/* ITEMS */

.item-count {
    display: inline-flex;
    padding: 5px 9px;
    background: #f3f4f6;
    border-radius: 6px;
    color: #374151;
    font-size: 10px;
    font-weight: 600;
}


/* AMOUNT */

.amount {
    color: #111827;
    white-space: nowrap;
}


/* PAYMENT */

.payment-badge {
    display: inline-flex;
    padding: 5px 9px;
    background: #f3f4f6;
    border-radius: 6px;
    color: #555;
    font-size: 10px;
    font-weight: 600;
}


/* STATUS */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
}

.status-completed {
    background: #ecfdf3;
    color: #15803d;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}


/* ACTIONS */

.actions-column {
    text-align: center !important;
}

.actions {
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-button {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    background: white;
    text-decoration: none;
    cursor: pointer;
    font-size: 12px;
}

.action-button.view:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
}


/* EMPTY */

.empty-state {
    padding: 80px 25px;
    text-align: center;
}

.empty-icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #f3f4f6;
    font-size: 28px;
}

.empty-state h2 {
    margin: 0 0 8px;
    color: #111827;
    font-size: 19px;
}

.empty-state p {
    max-width: 450px;
    margin: 0 auto 20px;
    color: #777;
    font-size: 12px;
}

.clear-empty-button {
    padding: 9px 16px;
    border: 1px solid #ddd;
    border-radius: 7px;
    background: white;
    color: #555;
    cursor: pointer;
}


/* PAGINATION */

.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 16px 18px;
    border-top: 1px solid #edf0f2;
}

.pagination-info {
    color: #777;
    font-size: 11px;
}

.pagination-info strong {
    color: #374151;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 4px;
}

.page-button {
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
    color: #555;
    font-size: 11px;
    cursor: pointer;
}

.page-button:hover:not(:disabled) {
    background: #f9fafb;
}

.page-button.active {
    border-color: #111827;
    background: #111827;
    color: white;
}

.page-button:disabled {
    cursor: not-allowed;
    opacity: .45;
}


/* RESPONSIVE */

@media (max-width: 1000px) {

    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

}

@media (max-width: 650px) {

    .orders-page {
        padding: 5px;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        width: 100%;
    }

    .filter-button,
    .clear-button {
        flex: 1;
    }

    .summary-row {
        grid-template-columns: 1fr;
    }

    .pagination-wrapper {
        align-items: flex-start;
        flex-direction: column;
    }

    .pagination {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 3px;
    }

}

</style>