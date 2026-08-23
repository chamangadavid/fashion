<!-- resources\js\Pages\MyFashions\Customers\Groups.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    customers: {
        type: Array,
        default: () => [],
    },
})

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = ref('')

/*
|--------------------------------------------------------------------------
| GROUP FILTER
|--------------------------------------------------------------------------
*/

const selectedGroup = ref('all')

/*
|--------------------------------------------------------------------------
| CUSTOMER GROUP
|--------------------------------------------------------------------------
*/

const getGroup = (customer) => {

    const orders = Number(customer.orders_count || 0)

    if (orders >= 5) {
        return 'loyal'
    }

    if (orders >= 2) {
        return 'regular'
    }

    return 'new'
}

/*
|--------------------------------------------------------------------------
| GROUP LABEL
|--------------------------------------------------------------------------
*/

const groupLabel = (customer) => {

    const group = getGroup(customer)

    if (group === 'loyal') {
        return 'Loyal Customer'
    }

    if (group === 'regular') {
        return 'Regular Customer'
    }

    return 'New Customer'
}

/*
|--------------------------------------------------------------------------
| FILTERED CUSTOMERS
|--------------------------------------------------------------------------
*/

const filteredCustomers = computed(() => {

    let results = [...props.customers]

    /*
    |----------------------------------------------------------------------
    | Search
    |----------------------------------------------------------------------
    */

    if (search.value.trim()) {

        const term = search.value
            .toLowerCase()
            .trim()

        results = results.filter(customer => {

            return (
                String(customer.name || '')
                    .toLowerCase()
                    .includes(term)
                ||
                String(customer.email || '')
                    .toLowerCase()
                    .includes(term)
            )
        })
    }

    /*
    |----------------------------------------------------------------------
    | Group
    |----------------------------------------------------------------------
    */

    if (selectedGroup.value !== 'all') {

        results = results.filter(customer => {

            return getGroup(customer) === selectedGroup.value

        })
    }

    return results
})

/*
|--------------------------------------------------------------------------
| GROUP COUNTS
|--------------------------------------------------------------------------
*/

const groupCounts = computed(() => {

    const customers = props.customers

    return {
        all: customers.length,

        new: customers.filter(
            customer => getGroup(customer) === 'new'
        ).length,

        regular: customers.filter(
            customer => getGroup(customer) === 'regular'
        ).length,

        loyal: customers.filter(
            customer => getGroup(customer) === 'loyal'
        ).length,
    }
})

/*
|--------------------------------------------------------------------------
| MONEY
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

</script>

<template>

    <Head title="Customer Groups" />

    <MyFashionLayout>

        <div class="groups-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Customer Groups
                    </h1>

                    <p>
                        Organise customers based on their purchasing activity.
                    </p>

                </div>

                <Link href="/fashion/customers" class="back-button">
                    ← All Customers
                </Link>

            </div>


            <!-- =====================================================
                 GROUP CARDS
            ====================================================== -->

            <div class="group-cards">

                <!-- ALL -->

                <button type="button" class="group-card" :class="{
                    active: selectedGroup === 'all'
                }" @click="selectedGroup = 'all'">

                    <span class="group-icon">
                        👥
                    </span>

                    <span class="group-content">

                        <strong>
                            All Customers
                        </strong>

                        <small>
                            {{ groupCounts.all }} customers
                        </small>

                    </span>

                </button>


                <!-- NEW -->

                <button type="button" class="group-card" :class="{
                    active: selectedGroup === 'new'
                }" @click="selectedGroup = 'new'">

                    <span class="group-icon">
                        🆕
                    </span>

                    <span class="group-content">

                        <strong>
                            New Customers
                        </strong>

                        <small>
                            {{ groupCounts.new }} customers
                        </small>

                    </span>

                </button>


                <!-- REGULAR -->

                <button type="button" class="group-card" :class="{
                    active: selectedGroup === 'regular'
                }" @click="selectedGroup = 'regular'">

                    <span class="group-icon">
                        ⭐
                    </span>

                    <span class="group-content">

                        <strong>
                            Regular Customers
                        </strong>

                        <small>
                            {{ groupCounts.regular }} customers
                        </small>

                    </span>

                </button>


                <!-- LOYAL -->

                <button type="button" class="group-card" :class="{
                    active: selectedGroup === 'loyal'
                }" @click="selectedGroup = 'loyal'">

                    <span class="group-icon">
                        👑
                    </span>

                    <span class="group-content">

                        <strong>
                            Loyal Customers
                        </strong>

                        <small>
                            {{ groupCounts.loyal }} customers
                        </small>

                    </span>

                </button>

            </div>


            <!-- =====================================================
                 CUSTOMER LIST
            ====================================================== -->

            <div class="content-card">

                <!-- HEADER -->

                <div class="card-header">

                    <div>

                        <h2>
                            {{ selectedGroup === 'all'
                                ? 'All Customers'
                                : selectedGroup === 'new'
                                    ? 'New Customers'
                                    : selectedGroup === 'regular'
                                        ? 'Regular Customers'
                                        : 'Loyal Customers'
                            }}
                        </h2>

                        <p>
                            Customers grouped according to order activity.
                        </p>

                    </div>

                    <span class="customer-count">

                        {{ filteredCustomers.length }}

                        {{ filteredCustomers.length === 1
                            ? 'Customer'
                            : 'Customers'
                        }}

                    </span>

                </div>


                <!-- SEARCH -->

                <div class="filters">

                    <input v-model="search" type="text" placeholder="Search customer name or email..."
                        class="search-input" />

                </div>


                <!-- TABLE -->

                <div class="table-wrapper">

                    <table class="customers-table">

                        <thead>

                            <tr>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Group
                                </th>

                                <th>
                                    Orders
                                </th>

                                <th>
                                    Total Spent
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr v-for="customer in filteredCustomers" :key="customer.customer_email">

                                <!-- CUSTOMER -->

                                <td>

                                    <div class="customer-cell">

                                        <div class="avatar">

                                            {{
                                                (customer.name || 'C')
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}

                                        </div>

                                        <div>

                                            <strong>
                                                {{ customer.name || 'Unknown Customer' }}
                                            </strong>

                                            <small>
                                                Customer #{{ customer.id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- EMAIL -->

                                <td>

                                    <span class="email">
                                        {{ customer.email || '-' }}
                                    </span>

                                </td>


                                <!-- GROUP -->

                                <td>

                                    <span class="group-badge" :class="`group-${getGroup(customer)}`">
                                        {{ groupLabel(customer) }}
                                    </span>

                                </td>


                                <!-- ORDERS -->

                                <td>

                                    <strong>
                                        {{ customer.orders_count || 0 }}
                                    </strong>

                                </td>


                                <!-- TOTAL -->

                                <td>

                                    <strong class="amount">
                                        ZMW
                                        {{
                                            formatMoney(
                                                customer.orders_sum_total_amount
                                            )
                                        }}
                                    </strong>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <Link :href="`/fashion/customers/${encodeURIComponent(customer.customer_email)}`"
                                        class="view-button">
                                        View
                                    </Link>

                                </td>

                            </tr>


                            <!-- EMPTY -->

                            <tr v-if="!filteredCustomers.length">

                                <td colspan="6" class="empty-row">

                                    No customers found.

                                </td>

                            </tr>

                        </tbody>

                    </table>

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

.groups-page {
    padding: 10px;
    width: 100%;
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
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0;
    color: #111827;
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.back-button {
    padding: 9px 13px;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    background: white;
    color: #075c59;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
}


/*
|--------------------------------------------------------------------------
| GROUP CARDS
|--------------------------------------------------------------------------
*/

.group-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.group-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 1px solid #edf0f2;
    border-radius: 10px;
    background: white;
    text-align: left;
    cursor: pointer;
    transition: .2s ease;
}

.group-card:hover {
    border-color: #075c59;
}

.group-card.active {
    border-color: #075c59;
    box-shadow: 0 0 0 1px #075c59;
}

.group-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #f3f4f6;
    font-size: 20px;
}

.group-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.group-content strong {
    color: #111827;
    font-size: 12px;
}

.group-content small {
    color: #9ca3af;
    font-size: 10px;
}


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.content-card {
    overflow: hidden;
    border: 1px solid #edf0f2;
    border-radius: 12px;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
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
}

.card-header p {
    margin: 5px 0 0;
    color: #777;
    font-size: 11px;
}

.customer-count {
    padding: 5px 9px;
    border-radius: 6px;
    background: #f3f4f6;
    color: #555;
    font-size: 10px;
    font-weight: 600;
}


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filters {
    padding: 15px 18px;
    border-bottom: 1px solid #edf0f2;
}

.search-input {
    width: 100%;
    max-width: 400px;
    padding: 10px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    outline: none;
    font-size: 11px;
}

.search-input:focus {
    border-color: #075c59;
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
    min-width: 850px;
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
}

.customers-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f1f2;
    color: #374151;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

.customer-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #eef7f5;
    color: #075c59;
    font-size: 13px;
    font-weight: 700;
}

.customer-cell strong {
    display: block;
    color: #111827;
    font-size: 11px;
}

.customer-cell small {
    display: block;
    margin-top: 3px;
    color: #9ca3af;
    font-size: 9px;
}

.email {
    color: #6b7280;
}


/*
|--------------------------------------------------------------------------
| GROUP BADGES
|--------------------------------------------------------------------------
*/

.group-badge {
    display: inline-flex;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 9px;
    font-weight: 700;
}

.group-new {
    background: #eef7f5;
    color: #075c59;
}

.group-regular {
    background: #fff7e6;
    color: #a0833d;
}

.group-loyal {
    background: #f3e8ff;
    color: #7e22ce;
}


/*
|--------------------------------------------------------------------------
| AMOUNT
|--------------------------------------------------------------------------
*/

.amount {
    color: #111827;
}


/*
|--------------------------------------------------------------------------
| VIEW BUTTON
|--------------------------------------------------------------------------
*/

.view-button {
    display: inline-flex;
    padding: 6px 10px;
    border-radius: 6px;
    background: #075c59;
    color: white;
    font-size: 9px;
    font-weight: 600;
    text-decoration: none;
}

.view-button:hover {
    background: #064e4b;
}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-row {
    padding: 50px !important;
    text-align: center;
    color: #9ca3af;
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1000px) {

    .group-cards {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 650px) {

    .groups-page {
        padding: 5px;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .group-cards {
        grid-template-columns: 1fr;
    }

    .card-header {
        align-items: flex-start;
        flex-direction: column;
    }

}
</style>