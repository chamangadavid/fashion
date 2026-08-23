<!-- resources\js\Pages\MyFashions\Customers\Index.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'

import {
    Head,
    Link,
    router
} from '@inertiajs/vue3'

import {
    computed,
    ref
} from 'vue'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    customers: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            total: 0,
        }),
    },

    statistics: {
        type: Object,
        default: () => ({
            total_customers: 0,
            total_orders: 0,
            total_revenue: 0,
            average_order_value: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = ref(
    props.filters?.search || ''
)


/*
|--------------------------------------------------------------------------
| CUSTOMERS
|--------------------------------------------------------------------------
*/

const customerList = computed(() => {

    return props.customers?.data || []

})


/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {

    return Number(amount || 0).toLocaleString(
        'en-ZM',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }
    )

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| CUSTOMER NAME
|--------------------------------------------------------------------------
*/

const customerName = (customer) => {

    const first =
        customer.shipping_first_name || ''

    const last =
        customer.shipping_last_name || ''

    const name =
        `${first} ${last}`.trim()

    return name || 'Guest Customer'

}


/*
|--------------------------------------------------------------------------
| CUSTOMER INITIALS
|--------------------------------------------------------------------------
*/

const customerInitials = (customer) => {

    const first =
        customer.shipping_first_name?.charAt(0) || ''

    const last =
        customer.shipping_last_name?.charAt(0) || ''

    const initials =
        `${first}${last}`.toUpperCase()

    return initials || 'G'

}


/*
|--------------------------------------------------------------------------
| CUSTOMER TYPE
|--------------------------------------------------------------------------
*/

const customerType = (customer) => {

    /*
     * At this stage customers are coming from
     * the orders table.
     *
     * We will improve this when we connect
     * the User relationship.
     */

    return 'Customer'

}


/*
|--------------------------------------------------------------------------
| SEARCH CUSTOMERS
|--------------------------------------------------------------------------
*/

const searchCustomers = () => {

    router.get(
        '/fashion/customers',
        {
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| CLEAR SEARCH
|--------------------------------------------------------------------------
*/

const clearSearch = () => {

    search.value = ''

    searchCustomers()

}


/*
|--------------------------------------------------------------------------
| VIEW CUSTOMER
|--------------------------------------------------------------------------
*/

const viewCustomer = (customer) => {

    router.visit(
        `/fashion/customers/${encodeURIComponent(
            customer.customer_email
        )}`
    )

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const goToPage = (url) => {

    if (!url) {
        return
    }

    router.visit(
        url,
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}

</script>


<template>

    <Head title="Customers" />


    <MyFashionLayout>

        <div class="customers-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Customers
                    </h1>

                    <p>
                        Manage and monitor your fashion store customers.
                    </p>

                </div>


                <div class="header-total">

                    <span>
                        {{ statistics.total_customers }}
                    </span>

                    Customers

                </div>

            </div>



            <!-- =====================================================
                 STATISTICS
            ====================================================== -->

            <div class="statistics-grid">


                <!-- TOTAL CUSTOMERS -->

                <div class="stat-card">

                    <div class="stat-icon customer-icon">
                        👥
                    </div>

                    <div>

                        <span class="stat-label">
                            Total Customers
                        </span>

                        <strong>
                            {{ statistics.total_customers }}
                        </strong>

                    </div>

                </div>



                <!-- TOTAL ORDERS -->

                <div class="stat-card">

                    <div class="stat-icon order-icon">
                        🛍️
                    </div>

                    <div>

                        <span class="stat-label">
                            Total Orders
                        </span>

                        <strong>
                            {{ statistics.total_orders }}
                        </strong>

                    </div>

                </div>



                <!-- TOTAL REVENUE -->

                <div class="stat-card">

                    <div class="stat-icon revenue-icon">
                        💰
                    </div>

                    <div>

                        <span class="stat-label">
                            Customer Revenue
                        </span>

                        <strong>
                            ZMW
                            {{ formatMoney(
                                statistics.total_revenue
                            ) }}
                        </strong>

                    </div>

                </div>



                <!-- AVERAGE ORDER -->

                <div class="stat-card">

                    <div class="stat-icon average-icon">
                        📊
                    </div>

                    <div>

                        <span class="stat-label">
                            Average Order
                        </span>

                        <strong>
                            ZMW
                            {{ formatMoney(
                                statistics.average_order_value
                            ) }}
                        </strong>

                    </div>

                </div>

            </div>



            <!-- =====================================================
                 SEARCH
            ====================================================== -->

            <div class="filter-card">

                <div class="search-wrapper">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search customer name, email or phone..."
                        @keyup.enter="searchCustomers"
                    />

                </div>


                <button
                    type="button"
                    class="search-button"
                    @click="searchCustomers"
                >
                    Search
                </button>


                <button
                    v-if="search"
                    type="button"
                    class="clear-button"
                    @click="clearSearch"
                >
                    Clear
                </button>

            </div>



            <!-- =====================================================
                 CUSTOMERS CARD
            ====================================================== -->

            <div class="customers-card">


                <!-- CARD HEADER -->

                <div class="card-header">

                    <div>

                        <h2>
                            All Customers
                        </h2>

                        <p>
                            Customers who have placed orders in your store.
                        </p>

                    </div>


                    <span class="customer-count">

                        {{ customers.total || customerList.length }}

                        {{
                            (customers.total || customerList.length) === 1
                                ? 'Customer'
                                : 'Customers'
                        }}

                    </span>

                </div>



                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="table-wrapper">

                    <table class="customers-table">

                        <thead>

                            <tr>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th>
                                    Orders
                                </th>

                                <th>
                                    Total Spent
                                </th>

                                <th>
                                    Last Order
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <!-- =====================================
                                 CUSTOMER ROW
                            ====================================== -->

                            <tr
                                v-for="customer in customerList"
                                :key="customer.customer_email"
                            >


                                <!-- CUSTOMER -->

                                <td>

                                    <div class="customer-cell">

                                        <div class="avatar">

                                            {{
                                                customerInitials(
                                                    customer
                                                )
                                            }}

                                        </div>


                                        <div class="customer-info">

                                            <strong>
                                                {{
                                                    customerName(
                                                        customer
                                                    )
                                                }}
                                            </strong>

                                            <small>
                                                {{
                                                    customer.customer_email
                                                }}
                                            </small>

                                        </div>

                                    </div>

                                </td>



                                <!-- CONTACT -->

                                <td>

                                    <div class="contact-info">

                                        <span>
                                            {{
                                                customer.customer_phone
                                                || '-'
                                            }}
                                        </span>

                                        <small>
                                            {{
                                                customer.customer_email
                                                || '-'
                                            }}
                                        </small>

                                    </div>

                                </td>



                                <!-- ORDERS -->

                                <td>

                                    <span class="orders-count">

                                        {{ customer.orders_count }}

                                    </span>

                                </td>



                                <!-- TOTAL SPENT -->

                                <td>

                                    <strong class="total-spent">

                                        ZMW
                                        {{
                                            formatMoney(
                                                customer.total_spent
                                            )
                                        }}

                                    </strong>

                                </td>



                                <!-- LAST ORDER -->

                                <td>

                                    <span class="date">

                                        {{
                                            formatDate(
                                                customer.last_order_at
                                            )
                                        }}

                                    </span>

                                </td>



                                <!-- STATUS -->

                                <td>

                                    <span class="customer-status">

                                        <span class="status-dot">
                                        </span>

                                        Active

                                    </span>

                                </td>



                                <!-- ACTION -->

                                <td>

                                    <button
                                        type="button"
                                        class="view-button"
                                        @click="
                                            viewCustomer(customer)
                                        "
                                    >

                                        View

                                    </button>





                                </td>

                            </tr>



                            <!-- =====================================
                                 EMPTY
                            ====================================== -->

                            <tr
                                v-if="!customerList.length"
                            >

                                <td
                                    colspan="7"
                                    class="empty-row"
                                >

                                    <div class="empty-content">

                                        <div class="empty-icon">
                                            👥
                                        </div>

                                        <strong>
                                            No Customers Found
                                        </strong>

                                        <span>
                                            {{
                                                search
                                                    ? 'Try another search.'
                                                    : 'Customers will appear here when orders are placed.'
                                            }}
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>



                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div
                    v-if="customers.links?.length > 3"
                    class="pagination"
                >

                    <button
                        v-for="link in customers.links"
                        :key="link.label"
                        type="button"
                        class="pagination-button"
                        :class="{
                            active: link.active,
                            disabled: !link.url
                        }"
                        :disabled="!link.url"
                        @click="goToPage(link.url)"
                        v-html="link.label"
                    />

                </div>


            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.customers-page {

    width: 100%;

    padding: 10px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 22px;

}


.page-header h1 {

    margin: 0;

    color: #111827;

    font-size: 27px;

    font-weight: 700;

}


.page-header p {

    margin: 6px 0 0;

    color: #6b7280;

    font-size: 13px;

}


.header-total {

    display: flex;

    align-items: center;

    gap: 7px;

    padding: 8px 13px;

    border-radius: 20px;

    background: #eef7f5;

    color: #075c59;

    font-size: 10px;

    font-weight: 700;

}


.header-total span {

    font-size: 14px;

}


/*
|--------------------------------------------------------------------------
| STATISTICS
|--------------------------------------------------------------------------
*/

.statistics-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 15px;

    margin-bottom: 18px;

}


.stat-card {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 16px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 10px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .03);

}


.stat-icon {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex: 0 0 40px;

    border-radius: 9px;

    font-size: 17px;

}


.customer-icon {

    background: #eef7f5;

}


.order-icon {

    background: #f3f4f6;

}


.revenue-icon {

    background: #fff7e6;

}


.average-icon {

    background: #eef2ff;

}


.stat-label {

    display: block;

    margin-bottom: 4px;

    color: #9ca3af;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: .3px;

}


.stat-card strong {

    display: block;

    color: #111827;

    font-size: 15px;

}


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filter-card {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 18px;

    padding: 12px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 10px;

}


.search-wrapper {

    position: relative;

    flex: 1;

}


.search-wrapper input {

    width: 100%;

    height: 38px;

    box-sizing: border-box;

    padding:
        0 12px 0 35px;

    border: 1px solid #e5e7eb;

    border-radius: 7px;

    outline: none;

    color: #374151;

    font-size: 11px;

}


.search-wrapper input:focus {

    border-color: #075c59;

}


.search-icon {

    position: absolute;

    left: 12px;

    top: 50%;

    transform: translateY(-50%);

    color: #9ca3af;

    font-size: 12px;

}


.search-button,
.clear-button {

    height: 38px;

    padding: 0 14px;

    border: none;

    border-radius: 7px;

    font-size: 10px;

    font-weight: 600;

    cursor: pointer;

}


.search-button {

    background: #111827;

    color: white;

}


.clear-button {

    background: #f3f4f6;

    color: #555;

}


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.customers-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    overflow: hidden;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

}


.card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 18px;

    border-bottom: 1px solid #edf0f2;

}


.card-header h2 {

    margin: 0;

    color: #111827;

    font-size: 15px;

    font-weight: 700;

}


.card-header p {

    margin: 5px 0 0;

    color: #777;

    font-size: 11px;

}


.customer-count {

    padding: 5px 9px;

    border-radius: 6px;

    background: #eef7f5;

    color: #075c59;

    font-size: 10px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.customers-table {

    width: 100%;

    min-width: 950px;

    border-collapse: collapse;

}


.customers-table th {

    padding: 13px 16px;

    background: #f9fafb;

    border-bottom: 1px solid #e5e7eb;

    color: #6b7280;

    font-size: 10px;

    font-weight: 700;

    text-align: left;

    white-space: nowrap;

}


.customers-table td {

    padding: 14px 16px;

    border-bottom: 1px solid #f0f1f2;

    color: #374151;

    font-size: 11px;

    vertical-align: middle;

}


.customers-table tbody tr:hover {

    background: #fafafa;

}


/*
|--------------------------------------------------------------------------
| CUSTOMER CELL
|--------------------------------------------------------------------------
*/

.customer-cell {

    display: flex;

    align-items: center;

    gap: 10px;

    min-width: 190px;

}


.avatar {

    width: 38px;

    height: 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex: 0 0 38px;

    border-radius: 50%;

    background: #eef7f5;

    color: #075c59;

    font-size: 11px;

    font-weight: 700;

}


.customer-info strong {

    display: block;

    color: #111827;

    font-size: 11px;

}


.customer-info small {

    display: block;

    max-width: 180px;

    margin-top: 4px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    color: #9ca3af;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| CONTACT
|--------------------------------------------------------------------------
*/

.contact-info span {

    display: block;

    color: #374151;

    font-size: 10px;

}


.contact-info small {

    display: block;

    margin-top: 4px;

    color: #9ca3af;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
*/

.orders-count {

    display: inline-flex;

    min-width: 28px;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 5px;

    background: #f3f4f6;

    color: #374151;

    font-size: 10px;

    font-weight: 700;

}


/*
|--------------------------------------------------------------------------
| TOTAL SPENT
|--------------------------------------------------------------------------
*/

.total-spent {

    color: #111827;

    font-size: 10px;

    white-space: nowrap;

}


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

.date {

    color: #6b7280;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.customer-status {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 5px 9px;

    border-radius: 20px;

    background: #edf7ed;

    color: #287a2d;

    font-size: 9px;

    font-weight: 700;

}


.status-dot {

    width: 6px;

    height: 6px;

    border-radius: 50%;

    background: #287a2d;

}


/*
|--------------------------------------------------------------------------
| VIEW BUTTON
|--------------------------------------------------------------------------
*/

.view-button {

    padding: 7px 11px;

    border: none;

    border-radius: 6px;

    background: #111827;

    color: white;

    font-size: 9px;

    font-weight: 600;

    cursor: pointer;

}


.view-button:hover {

    background: #075c59;

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-row {

    padding: 60px 20px !important;

}


.empty-content {

    display: flex;

    align-items: center;

    flex-direction: column;

    justify-content: center;

    gap: 7px;

}


.empty-icon {

    width: 48px;

    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-bottom: 7px;

    border-radius: 50%;

    background: #eef7f5;

    font-size: 18px;

}


.empty-content strong {

    color: #374151;

    font-size: 13px;

}


.empty-content span {

    color: #9ca3af;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.pagination {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-wrap: wrap;

    gap: 5px;

    padding: 16px;

    border-top: 1px solid #edf0f2;

}


.pagination-button {

    min-width: 30px;

    height: 30px;

    padding: 0 9px;

    border: 1px solid #e5e7eb;

    border-radius: 6px;

    background: white;

    color: #555;

    font-size: 9px;

    cursor: pointer;

}


.pagination-button:hover:not(:disabled) {

    border-color: #075c59;

    color: #075c59;

}


.pagination-button.active {

    border-color: #111827;

    background: #111827;

    color: white;

}


.pagination-button.disabled {

    cursor: not-allowed;

    opacity: .45;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1100px) {

    .statistics-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

}


@media (max-width: 700px) {

    .customers-page {

        padding: 5px;

    }


    .page-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .statistics-grid {

        grid-template-columns: 1fr;

    }


    .filter-card {

        align-items: stretch;

        flex-direction: column;

    }


    .search-button,
    .clear-button {

        width: 100%;

    }

}

</style>