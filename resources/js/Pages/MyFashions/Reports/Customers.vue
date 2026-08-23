<!-- resources\js\Pages\MyFashions\Reports\Customers.vue -->
<!-- resources/js/Pages/MyFashions/Reports/Customers.vue -->

<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js'

import { Line, Bar } from 'vue-chartjs'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend
)

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    reports: {
        type: Object,
        default: () => ({
            summary: {},
            customerGrowth: [],
            topCustomers: [],
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            range: 'month',
            start_date: '',
            end_date: '',
        }),
    },

})

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

const selectedRange = ref(
    props.filters?.range || 'month'
)

const startDate = ref(
    props.filters?.start_date || ''
)

const endDate = ref(
    props.filters?.end_date || ''
)

const applyFilter = () => {

    router.get(
        '/fashion/reports/customers',
        {
            range: selectedRange.value,
            start_date: startDate.value,
            end_date: endDate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

const summary = computed(() => {

    return {

        totalCustomers:
            props.reports?.summary?.totalCustomers || 0,

        newCustomers:
            props.reports?.summary?.newCustomers || 0,

        returningCustomers:
            props.reports?.summary?.returningCustomers || 0,

        totalOrders:
            props.reports?.summary?.totalOrders || 0,

        totalRevenue:
            props.reports?.summary?.totalRevenue || 0,

        averageCustomerValue:
            props.reports?.summary?.averageCustomerValue || 0,

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

/*
|--------------------------------------------------------------------------
| NUMBER
|--------------------------------------------------------------------------
*/

const formatNumber = (number) => {

    return Number(number || 0).toLocaleString()

}

/*
|--------------------------------------------------------------------------
| CUSTOMER GROWTH
|--------------------------------------------------------------------------
*/

const growthData = computed(() => {

    const data =
        props.reports?.customerGrowth || []

    return {

        labels: data.map(item => item.label),

        datasets: [
            {
                label: 'New Customers',

                data: data.map(
                    item => item.customers
                ),

                tension: 0.4,

                fill: false,

                borderWidth: 2,

                pointRadius: 4,
            },
        ],

    }

})

/*
|--------------------------------------------------------------------------
| TOP CUSTOMERS CHART
|--------------------------------------------------------------------------
*/

const topCustomersData = computed(() => {

    const customers =
        props.reports?.topCustomers || []

    return {

        labels: customers.map(
            customer =>
                customer.name ||
                customer.email ||
                'Customer'
        ),

        datasets: [
            {
                label: 'Revenue',

                data: customers.map(
                    customer =>
                        Number(customer.revenue || 0)
                ),

                borderRadius: 6,

            },
        ],

    }

})

/*
|--------------------------------------------------------------------------
| CHART OPTIONS
|--------------------------------------------------------------------------
*/

const growthOptions = {

    responsive: true,

    maintainAspectRatio: false,

    plugins: {

        legend: {
            display: false,
        },

    },

    scales: {

        y: {

            beginAtZero: true,

            ticks: {
                precision: 0,
            },

        },

    },

}

const customerBarOptions = {

    responsive: true,

    maintainAspectRatio: false,

    indexAxis: 'y',

    plugins: {

        legend: {
            display: false,
        },

        tooltip: {

            callbacks: {

                label: (context) => {

                    return ` ZMW ${formatMoney(
                        context.raw
                    )}`

                },

            },

        },

    },

    scales: {

        x: {

            beginAtZero: true,

        },

    },

}

</script>


<template>

    <Head title="Customer Reports" />

    <MyFashionLayout>

        <div class="reports-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Customer Reports
                    </h1>

                    <p>
                        Analyze customer growth,
                        spending and purchasing behavior.
                    </p>

                </div>

            </div>


            <!-- =====================================================
                 FILTERS
            ====================================================== -->

            <div class="filter-card">

                <div class="filter-group">

                    <label>
                        Date Range
                    </label>

                    <select
                        v-model="selectedRange"
                        @change="applyFilter"
                    >

                        <option value="today">
                            Today
                        </option>

                        <option value="week">
                            This Week
                        </option>

                        <option value="month">
                            This Month
                        </option>

                        <option value="year">
                            This Year
                        </option>

                        <option value="custom">
                            Custom
                        </option>

                    </select>

                </div>


                <template
                    v-if="selectedRange === 'custom'"
                >

                    <div class="filter-group">

                        <label>
                            Start Date
                        </label>

                        <input
                            type="date"
                            v-model="startDate"
                        />

                    </div>


                    <div class="filter-group">

                        <label>
                            End Date
                        </label>

                        <input
                            type="date"
                            v-model="endDate"
                        />

                    </div>


                    <button
                        class="apply-button"
                        @click="applyFilter"
                    >
                        Apply Filter
                    </button>

                </template>

            </div>


            <!-- =====================================================
                 SUMMARY CARDS
            ====================================================== -->

            <div class="summary-grid">

                <div class="summary-card">

                    <div class="summary-icon">
                        👥
                    </div>

                    <div>

                        <span>
                            Total Customers
                        </span>

                        <strong>
                            {{ formatNumber(
                                summary.totalCustomers
                            ) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        🆕
                    </div>

                    <div>

                        <span>
                            New Customers
                        </span>

                        <strong>
                            {{ formatNumber(
                                summary.newCustomers
                            ) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        🔄
                    </div>

                    <div>

                        <span>
                            Returning Customers
                        </span>

                        <strong>
                            {{ formatNumber(
                                summary.returningCustomers
                            ) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        🛍️
                    </div>

                    <div>

                        <span>
                            Customer Orders
                        </span>

                        <strong>
                            {{ formatNumber(
                                summary.totalOrders
                            ) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        💰
                    </div>

                    <div>

                        <span>
                            Customer Revenue
                        </span>

                        <strong>
                            ZMW
                            {{ formatMoney(
                                summary.totalRevenue
                            ) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        📊
                    </div>

                    <div>

                        <span>
                            Average Customer Value
                        </span>

                        <strong>
                            ZMW
                            {{ formatMoney(
                                summary.averageCustomerValue
                            ) }}
                        </strong>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 CHARTS
            ====================================================== -->

            <div class="charts-grid">


                <!-- CUSTOMER GROWTH -->

                <div class="chart-card">

                    <div class="chart-header">

                        <div>

                            <h2>
                                Customer Growth
                            </h2>

                            <p>
                                New customers over time.
                            </p>

                        </div>

                    </div>


                    <div class="chart-container">

                        <Line
                            :data="growthData"
                            :options="growthOptions"
                        />

                    </div>

                </div>


                <!-- TOP CUSTOMERS -->

                <div class="chart-card">

                    <div class="chart-header">

                        <div>

                            <h2>
                                Top Customers
                            </h2>

                            <p>
                                Customers generating the
                                most revenue.
                            </p>

                        </div>

                    </div>


                    <div class="chart-container">

                        <Bar
                            :data="topCustomersData"
                            :options="customerBarOptions"
                        />

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 TOP CUSTOMERS TABLE
            ====================================================== -->

            <div class="table-card">

                <div class="table-header">

                    <div>

                        <h2>
                            Top Customers
                        </h2>

                        <p>
                            Customers ranked by total
                            spending.
                        </p>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Orders
                                </th>

                                <th>
                                    Revenue
                                </th>

                                <th>
                                    Average Order
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="(
                                    customer,
                                    index
                                ) in props.reports?.topCustomers || []"
                                :key="customer.id || index"
                            >

                                <td>

                                    <span
                                        class="rank"
                                    >
                                        {{ index + 1 }}
                                    </span>

                                </td>


                                <td>

                                    <div
                                        class="customer-cell"
                                    >

                                        <div
                                            class="avatar"
                                        >

                                            {{
                                                (
                                                    customer.name ||
                                                    'C'
                                                )
                                                .charAt(0)
                                                .toUpperCase()
                                            }}

                                        </div>

                                        <strong>
                                            {{
                                                customer.name ||
                                                'Guest Customer'
                                            }}
                                        </strong>

                                    </div>

                                </td>


                                <td>

                                    {{
                                        customer.email || '-'
                                    }}

                                </td>


                                <td>

                                    {{
                                        formatNumber(
                                            customer.orders
                                        )
                                    }}

                                </td>


                                <td>

                                    <strong>

                                        ZMW
                                        {{
                                            formatMoney(
                                                customer.revenue
                                            )
                                        }}

                                    </strong>

                                </td>


                                <td>

                                    ZMW
                                    {{
                                        formatMoney(
                                            customer.average_order
                                        )
                                    }}

                                </td>

                            </tr>


                            <tr
                                v-if="
                                    !props.reports?.topCustomers?.length
                                "
                            >

                                <td
                                    colspan="6"
                                    class="empty-row"
                                >

                                    No customer data
                                    available.

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

/* =========================================================
   PAGE
========================================================= */

.reports-page {

    padding: 10px;

    width: 100%;

}


/* =========================================================
   HEADER
========================================================= */

.page-header {

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


/* =========================================================
   FILTER
========================================================= */

.filter-card {

    display: flex;

    align-items: flex-end;

    gap: 14px;

    flex-wrap: wrap;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 11px;

    padding: 15px;

    margin-bottom: 18px;

}

.filter-group {

    display: flex;

    flex-direction: column;

    gap: 5px;

}

.filter-group label {

    color: #6b7280;

    font-size: 10px;

    font-weight: 600;

}

.filter-group select,
.filter-group input {

    height: 36px;

    min-width: 150px;

    padding: 0 10px;

    border: 1px solid #e5e7eb;

    border-radius: 6px;

    background: white;

    color: #374151;

    font-size: 11px;

    outline: none;

}

.apply-button {

    height: 36px;

    padding: 0 18px;

    border: none;

    border-radius: 6px;

    background: #075c59;

    color: white;

    font-size: 11px;

    font-weight: 600;

    cursor: pointer;

}


/* =========================================================
   SUMMARY
========================================================= */

.summary-grid {

    display: grid;

    grid-template-columns:
        repeat(6, minmax(0, 1fr));

    gap: 12px;

    margin-bottom: 18px;

}

.summary-card {

    display: flex;

    align-items: center;

    gap: 11px;

    min-height: 85px;

    padding: 15px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 10px;

}

.summary-icon {

    width: 38px;

    height: 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #f3f7f6;

    font-size: 17px;

}

.summary-card span {

    display: block;

    margin-bottom: 4px;

    color: #9ca3af;

    font-size: 9px;

}

.summary-card strong {

    display: block;

    color: #111827;

    font-size: 14px;

}


/* =========================================================
   CHARTS
========================================================= */

.charts-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;

    margin-bottom: 18px;

}

.chart-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 11px;

    overflow: hidden;

}

.chart-header {

    padding: 17px;

    border-bottom: 1px solid #edf0f2;

}

.chart-header h2 {

    margin: 0;

    color: #111827;

    font-size: 14px;

    font-weight: 700;

}

.chart-header p {

    margin: 4px 0 0;

    color: #9ca3af;

    font-size: 10px;

}

.chart-container {

    height: 300px;

    padding: 15px;

}


/* =========================================================
   TABLE
========================================================= */

.table-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 11px;

    overflow: hidden;

}

.table-header {

    padding: 17px;

    border-bottom: 1px solid #edf0f2;

}

.table-header h2 {

    margin: 0;

    color: #111827;

    font-size: 14px;

}

.table-header p {

    margin: 4px 0 0;

    color: #9ca3af;

    font-size: 10px;

}

.table-wrapper {

    overflow-x: auto;

}

table {

    width: 100%;

    min-width: 800px;

    border-collapse: collapse;

}

th {

    padding: 12px 15px;

    background: #f9fafb;

    color: #6b7280;

    font-size: 9px;

    font-weight: 700;

    text-align: left;

    white-space: nowrap;

}

td {

    padding: 13px 15px;

    border-top: 1px solid #f0f1f2;

    color: #4b5563;

    font-size: 10px;

}

.rank {

    width: 25px;

    height: 25px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f3f4f6;

    color: #374151;

    font-size: 10px;

    font-weight: 700;

}

.customer-cell {

    display: flex;

    align-items: center;

    gap: 9px;

}

.avatar {

    width: 30px;

    height: 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #075c59;

    color: white;

    font-size: 10px;

    font-weight: 700;

}

.customer-cell strong {

    color: #111827;

    font-size: 11px;

}

.empty-row {

    padding: 35px !important;

    text-align: center;

    color: #9ca3af;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .summary-grid {

        grid-template-columns:
            repeat(3, 1fr);

    }

}

@media (max-width: 900px) {

    .charts-grid {

        grid-template-columns: 1fr;

    }

}

@media (max-width: 650px) {

    .summary-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }

    .filter-card {

        align-items: stretch;

        flex-direction: column;

    }

    .filter-group select,
    .filter-group input {

        width: 100%;

    }

    .apply-button {

        width: 100%;

    }

}

@media (max-width: 450px) {

    .summary-grid {

        grid-template-columns: 1fr;

    }

}

</style>