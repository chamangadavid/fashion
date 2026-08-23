<!-- resources\js\Pages\MyFashions\Customers\Vip.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

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
| FILTERED CUSTOMERS
|--------------------------------------------------------------------------
*/

const filteredCustomers = computed(() => {

    const value = search.value
        .trim()
        .toLowerCase()

    if (!value) {
        return props.customers
    }

    return props.customers.filter(customer => {

        const name = `${customer.first_name || ''} ${customer.last_name || ''}`

        return (
            name.toLowerCase().includes(value) ||
            String(customer.email || '')
                .toLowerCase()
                .includes(value) ||
            String(customer.phone || '')
                .toLowerCase()
                .includes(value)
        )

    })

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
| CUSTOMER NAME
|--------------------------------------------------------------------------
*/

const customerName = (customer) => {

    const name = [
        customer.first_name,
        customer.last_name,
    ]
        .filter(Boolean)
        .join(' ')

    return name || customer.name || 'Customer'

}

/*
|--------------------------------------------------------------------------
| CUSTOMER INITIALS
|--------------------------------------------------------------------------
*/

const customerInitials = (customer) => {

    const name = customerName(customer)

    const parts = name
        .trim()
        .split(/\s+/)

    if (parts.length === 1) {
        return parts[0].substring(0, 2).toUpperCase()
    }

    return (
        parts[0].charAt(0) +
        parts[parts.length - 1].charAt(0)
    ).toUpperCase()

}

/*
|--------------------------------------------------------------------------
| VIP LEVEL
|--------------------------------------------------------------------------
*/

const vipLevel = (customer) => {

    const spent = Number(customer.total_spent || 0)

    if (spent >= 100000) {
        return 'Platinum'
    }

    if (spent >= 50000) {
        return 'Gold'
    }

    if (spent >= 20000) {
        return 'Silver'
    }

    return 'VIP'

}

/*
|--------------------------------------------------------------------------
| VIP CLASS
|--------------------------------------------------------------------------
*/

const vipClass = (customer) => {

    const level = vipLevel(customer)

    return `vip-${level.toLowerCase()}`

}

</script>


<template>

    <Head title="VIP Customers" />

    <MyFashionLayout>

        <div class="vip-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <div class="breadcrumb">

                        <Link href="/fashion/customers" class="back-link">
                            ← Customers
                        </Link>

                    </div>

                    <h1>
                        VIP Customers
                    </h1>

                    <p>
                        Manage and monitor your most valuable customers.
                    </p>

                </div>


                <div class="vip-summary">

                    <span class="summary-number">
                        {{ customers.length }}
                    </span>

                    <span class="summary-label">
                        VIP Customers
                    </span>

                </div>

            </div>


            <!-- =====================================================
                 VIP INFORMATION
            ====================================================== -->

            <div class="vip-info">

                <div class="vip-info-icon">
                    ★
                </div>

                <div>

                    <strong>
                        VIP Customer Program
                    </strong>

                    <p>
                        VIP customers are ranked according to their
                        total completed order value.
                    </p>

                </div>

            </div>


            <!-- =====================================================
                 TOOLBAR
            ====================================================== -->

            <div class="toolbar">

                <div class="search-box">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input v-model="search" type="text" placeholder="Search VIP customers..." />

                </div>

                <div class="customer-count">

                    {{ filteredCustomers.length }}

                    {{
                        filteredCustomers.length === 1
                            ? 'customer'
                            : 'customers'
                    }}

                </div>

            </div>


            <!-- =====================================================
                 CUSTOMER TABLE
            ====================================================== -->

            <div class="content-card">

                <div class="table-wrapper">

                    <table class="customers-table">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

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
                                    VIP Level
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr v-for="(customer, index) in filteredCustomers" :key="customer.customer_email">

                                <!-- NUMBER -->

                                <td>

                                    <span class="row-number">
                                        {{ index + 1 }}
                                    </span>

                                </td>


                                <!-- CUSTOMER -->

                                <td>

                                    <div class="customer-cell">

                                        <div class="avatar">
                                            {{ customerInitials(customer) }}
                                        </div>

                                        <div class="customer-info">

                                            <strong>
                                                {{ customerName(customer) }}
                                            </strong>

                                            <small>
                                                Customer #{{ customer.id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- CONTACT -->

                                <td>

                                    <div class="contact-info">

                                        <span>
                                            {{ customer.email || '-' }}
                                        </span>

                                        <small>
                                            {{ customer.phone || 'No phone number' }}
                                        </small>

                                    </div>

                                </td>


                                <!-- ORDERS -->

                                <td>

                                    <span class="orders-badge">

                                        {{ customer.orders_count || 0 }}

                                    </span>

                                </td>


                                <!-- TOTAL SPENT -->

                                <td>

                                    <strong class="spent">

                                        ZMW
                                        {{ formatMoney(customer.total_spent) }}

                                    </strong>

                                </td>


                                <!-- VIP LEVEL -->

                                <td>

                                    <span class="vip-badge" :class="vipClass(customer)">

                                        <span class="vip-star">
                                            ★
                                        </span>

                                        {{ vipLevel(customer) }}

                                    </span>

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

                            <tr v-if="filteredCustomers.length === 0">

                                <td colspan="7" class="empty-row">

                                    <div class="empty-state">

                                        <div class="empty-icon">
                                            ★
                                        </div>

                                        <strong>
                                            No VIP customers found
                                        </strong>

                                        <p>
                                            Customers matching your search
                                            will appear here.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =====================================================
                 VIP LEVEL GUIDE
            ====================================================== -->

            <div class="level-card">

                <div class="level-header">

                    <div>

                        <h2>
                            VIP Levels
                        </h2>

                        <p>
                            Customer classification based on total spending.
                        </p>

                    </div>

                </div>


                <div class="levels">

                    <div class="level platinum">

                        <span class="level-icon">
                            ★
                        </span>

                        <div>

                            <strong>
                                Platinum
                            </strong>

                            <small>
                                ZMW 100,000+
                            </small>

                        </div>

                    </div>


                    <div class="level gold">

                        <span class="level-icon">
                            ★
                        </span>

                        <div>

                            <strong>
                                Gold
                            </strong>

                            <small>
                                ZMW 50,000+
                            </small>

                        </div>

                    </div>


                    <div class="level silver">

                        <span class="level-icon">
                            ★
                        </span>

                        <div>

                            <strong>
                                Silver
                            </strong>

                            <small>
                                ZMW 20,000+
                            </small>

                        </div>

                    </div>


                    <div class="level standard">

                        <span class="level-icon">
                            ★
                        </span>

                        <div>

                            <strong>
                                VIP
                            </strong>

                            <small>
                                Below ZMW 20,000
                            </small>

                        </div>

                    </div>

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

.vip-page {

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

    margin-bottom: 20px;

}

.breadcrumb {

    margin-bottom: 7px;

}

.back-link {

    color: #075c59;

    font-size: 11px;

    font-weight: 600;

    text-decoration: none;

}

.back-link:hover {

    text-decoration: underline;

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

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.vip-summary {

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    min-width: 120px;

    padding: 14px 20px;

    border-radius: 10px;

    background: #111827;

    color: white;

}

.summary-number {

    font-size: 22px;

    font-weight: 700;

}

.summary-label {

    margin-top: 2px;

    color: #d1d5db;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: .5px;

}


/*
|--------------------------------------------------------------------------
| VIP INFORMATION
|--------------------------------------------------------------------------
*/

.vip-info {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    margin-bottom: 18px;

    padding: 14px 16px;

    border: 1px solid #eadca9;

    border-radius: 10px;

    background: #fffaf0;

}

.vip-info-icon {

    width: 30px;

    height: 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #111827;

    color: #f4d35e;

    font-size: 14px;

}

.vip-info strong {

    display: block;

    color: #7a5a18;

    font-size: 12px;

}

.vip-info p {

    margin: 3px 0 0;

    color: #8a6d2f;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| TOOLBAR
|--------------------------------------------------------------------------
*/

.toolbar {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-bottom: 12px;

}

.search-box {

    position: relative;

    width: 320px;

}

.search-icon {

    position: absolute;

    top: 50%;

    left: 12px;

    transform: translateY(-50%);

    font-size: 12px;

}

.search-box input {

    width: 100%;

    height: 38px;

    padding: 0 12px 0 35px;

    border: 1px solid #e5e7eb;

    border-radius: 8px;

    outline: none;

    background: white;

    color: #111827;

    font-size: 11px;

}

.search-box input:focus {

    border-color: #075c59;

    box-shadow: 0 0 0 3px rgba(7, 92, 89, .08);

}

.customer-count {

    color: #6b7280;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

.content-card {

    overflow: hidden;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    background: white;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .04);

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

    padding: 13px 15px;

    border-bottom: 1px solid #e5e7eb;

    background: #f9fafb;

    color: #6b7280;

    font-size: 9px;

    font-weight: 700;

    text-align: left;

    text-transform: uppercase;

    white-space: nowrap;

}

.customers-table td {

    padding: 13px 15px;

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
| ROW NUMBER
|--------------------------------------------------------------------------
*/

.row-number {

    color: #9ca3af;

    font-size: 10px;

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

    flex: 0 0 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #111827;

    color: white;

    font-size: 10px;

    font-weight: 700;

}

.customer-info strong {

    display: block;

    color: #111827;

    font-size: 11px;

}

.customer-info small {

    display: block;

    margin-top: 3px;

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

    max-width: 200px;

    overflow: hidden;

    color: #374151;

    text-overflow: ellipsis;

    white-space: nowrap;

}

.contact-info small {

    display: block;

    margin-top: 3px;

    color: #9ca3af;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
*/

.orders-badge {

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
| SPENT
|--------------------------------------------------------------------------
*/

.spent {

    color: #075c59;

    font-size: 11px;

}


/*
|--------------------------------------------------------------------------
| VIP BADGE
|--------------------------------------------------------------------------
*/

.vip-badge {

    display: inline-flex;

    align-items: center;

    gap: 4px;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 9px;

    font-weight: 700;

}

.vip-star {

    font-size: 10px;

}


/* Platinum */

.vip-platinum {

    background: #e5e7eb;

    color: #374151;

}


/* Gold */

.vip-gold {

    background: #fff4cc;

    color: #876b00;

}


/* Silver */

.vip-silver {

    background: #f1f5f9;

    color: #64748b;

}


/* VIP */

.vip-vip {

    background: #eef7f5;

    color: #075c59;

}


/*
|--------------------------------------------------------------------------
| VIEW BUTTON
|--------------------------------------------------------------------------
*/

.view-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 6px 11px;

    border-radius: 6px;

    background: #111827;

    color: white;

    font-size: 9px;

    font-weight: 600;

    text-decoration: none;

    transition: .2s ease;

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

    padding: 50px 20px !important;

}

.empty-state {

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

}

.empty-icon {

    width: 50px;

    height: 50px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-bottom: 10px;

    border-radius: 50%;

    background: #f3f4f6;

    color: #9ca3af;

    font-size: 20px;

}

.empty-state strong {

    color: #374151;

    font-size: 12px;

}

.empty-state p {

    margin: 5px 0 0;

    color: #9ca3af;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| VIP LEVEL CARD
|--------------------------------------------------------------------------
*/

.level-card {

    margin-top: 18px;

    padding: 18px;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    background: white;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .04);

}

.level-header h2 {

    margin: 0;

    color: #111827;

    font-size: 14px;

}

.level-header p {

    margin: 4px 0 0;

    color: #9ca3af;

    font-size: 10px;

}

.levels {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 12px;

    margin-top: 15px;

}

.level {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 12px;

    border-radius: 8px;

    background: #f9fafb;

}

.level-icon {

    font-size: 17px;

}

.level strong {

    display: block;

    font-size: 10px;

}

.level small {

    display: block;

    margin-top: 3px;

    color: #9ca3af;

    font-size: 9px;

}

.level.platinum .level-icon {

    color: #6b7280;

}

.level.gold .level-icon {

    color: #d4a72c;

}

.level.silver .level-icon {

    color: #94a3b8;

}

.level.standard .level-icon {

    color: #075c59;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .levels {

        grid-template-columns: repeat(2, 1fr);

    }

}

@media (max-width: 650px) {

    .page-header {

        align-items: flex-start;

        flex-direction: column;

    }

    .toolbar {

        align-items: stretch;

        flex-direction: column;

    }

    .search-box {

        width: 100%;

    }

    .levels {

        grid-template-columns: 1fr;

    }

}
</style>