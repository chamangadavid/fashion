<!-- resources\js\Pages\MyFashions\Reports\Revenue.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref, onMounted, onBeforeUnmount, watch, } from 'vue'

import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    BarController,
    BarElement,
    DoughnutController,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js'

import Swal from 'sweetalert2'

/*
|--------------------------------------------------------------------------
| CHART JS
|--------------------------------------------------------------------------
*/

Chart.register(
    LineController,
    LineElement,
    PointElement,
    BarController,
    BarElement,
    DoughnutController,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler
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
            revenueByDate: [],
            revenueByPaymentMethod: [],
            revenueByPaymentStatus: [],
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            period: 'month',
            start_date: '',
            end_date: '',
        }),
    },

})

watch(
    () => props.reports,
    () => {
        setTimeout(() => {
            renderCharts()
        }, 50)
    },
    {
        deep: true,
    }
)

/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

const selectedPeriod = ref(
    props.filters?.period || 'month'
)

const startDate = ref(
    props.filters?.start_date || ''
)

const endDate = ref(
    props.filters?.end_date || ''
)

/*
|--------------------------------------------------------------------------
| CHART REFERENCES
|--------------------------------------------------------------------------
*/

const revenueChartCanvas = ref(null)

const paymentMethodChartCanvas = ref(null)

const paymentStatusChartCanvas = ref(null)

let revenueChart = null

let paymentMethodChart = null

let paymentStatusChart = null

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

const summary = computed(() => {

    return props.reports?.summary || {}

})

/*
|--------------------------------------------------------------------------
| MONEY VALUES
|--------------------------------------------------------------------------
*/

const grossRevenue = computed(() => {

    return Number(
        summary.value.gross_revenue ||
        summary.value.total_revenue ||
        summary.value.total_sales ||
        0
    )

})


const paidRevenue = computed(() => {

    return Number(
        summary.value.paid_revenue ||
        0
    )

})


const pendingRevenue = computed(() => {

    return Number(
        summary.value.pending_revenue ||
        0
    )

})


const failedRevenue = computed(() => {

    return Number(
        summary.value.failed_revenue ||
        0
    )

})


const refundedRevenue = computed(() => {

    return Number(
        summary.value.refunded_revenue ||
        0
    )

})


const cancelledRevenue = computed(() => {

    return Number(
        summary.value.cancelled_revenue ||
        0
    )

})


const totalOrders = computed(() => {

    return Number(
        summary.value.total_orders ||
        0
    )

})


const averageRevenue = computed(() => {

    if (!totalOrders.value) {
        return 0
    }

    return grossRevenue.value / totalOrders.value

})


/*
|--------------------------------------------------------------------------
| REVENUE BY DATE
|--------------------------------------------------------------------------
*/

const revenueByDate = computed(() => {

    return props.reports?.revenueByDate || []

})


/*
|--------------------------------------------------------------------------
| PAYMENT METHOD
|--------------------------------------------------------------------------
*/

const revenueByPaymentMethod = computed(() => {

    return props.reports?.revenueByPaymentMethod || []

})


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

const revenueByPaymentStatus = computed(() => {

    return props.reports?.revenueByPaymentStatus || []

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
| FORMAT NUMBER
|--------------------------------------------------------------------------
*/

const formatNumber = (number) => {

    return Number(number || 0).toLocaleString()

}


/*
|--------------------------------------------------------------------------
| PAYMENT METHOD LABEL
|--------------------------------------------------------------------------
*/

const paymentMethodLabel = (method) => {

    const labels = {

        cash_on_delivery: 'Cash on Delivery',

        mobile_money: 'Mobile Money',

        card: 'Visa / Card',

        cash: 'Cash',

    }

    return labels[method] || method || '-'

}


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS LABEL
|--------------------------------------------------------------------------
*/

const paymentStatusLabel = (status) => {

    const labels = {

        pending: 'Pending',

        paid: 'Paid',

        failed: 'Failed',

        cancelled: 'Cancelled',

        refunded: 'Refunded',

    }

    return labels[status] || status || '-'

}


/*
|--------------------------------------------------------------------------
| APPLY FILTER
|--------------------------------------------------------------------------
*/

const applyFilter = () => {

    router.get(
        '/fashion/reports/revenue',
        {
            period: selectedPeriod.value,

            start_date: startDate.value,

            end_date: endDate.value,
        },
        {
            preserveState: true,

            preserveScroll: true,

            replace: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| CHANGE PERIOD
|--------------------------------------------------------------------------
*/

const changePeriod = (period) => {

    selectedPeriod.value = period

    if (period !== 'custom') {

        startDate.value = ''

        endDate.value = ''

        applyFilter()

    }

}


/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

const refreshReport = () => {

    router.reload({

        only: ['reports'],

        preserveScroll: true,

        preserveState: true,

    })

}


/*
|--------------------------------------------------------------------------
| EXPORT
|--------------------------------------------------------------------------
*/

const exportReport = () => {

    Swal.fire({

        title: 'Export Revenue Report',

        text: 'Choose the export format.',

        icon: 'question',

        showCancelButton: true,

        showDenyButton: true,

        confirmButtonText: 'Excel',

        denyButtonText: 'PDF',

        cancelButtonText: 'Cancel',

        confirmButtonColor: '#075c59',

        denyButtonColor: '#374151',

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href =
                `/fashion/reports/revenue/export/excel?period=${selectedPeriod.value}&start_date=${startDate.value}&end_date=${endDate.value}`

        }

        if (result.isDenied) {

            window.location.href =
                `/fashion/reports/revenue/export/pdf?period=${selectedPeriod.value}&start_date=${startDate.value}&end_date=${endDate.value}`

        }

    })

}


/*
|--------------------------------------------------------------------------
| REVENUE CHART
|--------------------------------------------------------------------------
*/

const createRevenueChart = () => {

    if (!revenueChartCanvas.value) {
        return
    }

    if (revenueChart) {
        revenueChart.destroy()
    }

    const labels = revenueByDate.value.map(
        item => item.date || item.label
    )

    const revenue = revenueByDate.value.map(
        item => Number(
            item.revenue ||
            item.total_revenue ||
            item.sales ||
            item.total ||
            0
        )
    )

    revenueChart = new Chart(
        revenueChartCanvas.value,
        {

            type: 'line',

            data: {

                labels,

                datasets: [

                    {

                        label: 'Revenue',

                        data: revenue,

                        borderWidth: 2,

                        tension: 0.35,

                        fill: true,

                        pointRadius: 3,

                        pointHoverRadius: 6,

                    },

                ],

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {

                    intersect: false,

                    mode: 'index',

                },

                plugins: {

                    legend: {

                        display: false,

                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` ZMW ${formatMoney(context.raw)}`

                            },

                        },

                    },

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function (value) {

                                return `ZMW ${formatMoney(value)}`

                            },

                        },

                    },

                    x: {

                        grid: {

                            display: false,

                        },

                    },

                },

            },

        }
    )

}


/*
|--------------------------------------------------------------------------
| PAYMENT METHOD CHART
|--------------------------------------------------------------------------
*/

const createPaymentMethodChart = () => {

    if (!paymentMethodChartCanvas.value) {
        return
    }

    if (paymentMethodChart) {
        paymentMethodChart.destroy()
    }

    const labels =
        revenueByPaymentMethod.value.map(
            item =>
                paymentMethodLabel(
                    item.payment_method ||
                    item.method
                )
        )

    const values =
        revenueByPaymentMethod.value.map(
            item =>
                Number(
                    item.revenue ||
                    item.total ||
                    item.amount ||
                    0
                )
        )

    paymentMethodChart = new Chart(
        paymentMethodChartCanvas.value,
        {

            type: 'bar',

            data: {

                labels,

                datasets: [

                    {

                        label: 'Revenue',

                        data: values,

                        borderWidth: 1,

                        borderRadius: 6,

                    },

                ],

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false,

                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` ZMW ${formatMoney(context.raw)}`

                            },

                        },

                    },

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function (value) {

                                return `ZMW ${formatMoney(value)}`

                            },

                        },

                    },

                    x: {

                        grid: {

                            display: false,

                        },

                    },

                },

            },

        }
    )

}


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS CHART
|--------------------------------------------------------------------------
*/

const createPaymentStatusChart = () => {

    if (!paymentStatusChartCanvas.value) {
        return
    }

    if (paymentStatusChart) {
        paymentStatusChart.destroy()
    }

    const labels =
        revenueByPaymentStatus.value.map(
            item =>
                paymentStatusLabel(
                    item.payment_status ||
                    item.status
                )
        )

    const values =
        revenueByPaymentStatus.value.map(
            item =>
                Number(
                    item.revenue ||
                    item.total ||
                    item.amount ||
                    0
                )
        )

    paymentStatusChart = new Chart(
        paymentStatusChartCanvas.value,
        {

            type: 'doughnut',

            data: {

                labels,

                datasets: [

                    {

                        data: values,

                        borderWidth: 2,

                    },

                ],

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '65%',

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            padding: 15,

                            boxWidth: 12,

                            font: {

                                size: 10,

                            },

                        },

                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` ZMW ${formatMoney(context.raw)}`

                            },

                        },

                    },

                },

            },

        }
    )

}


/*
|--------------------------------------------------------------------------
| RENDER CHARTS
|--------------------------------------------------------------------------
*/

const renderCharts = () => {

    createRevenueChart()

    createPaymentMethodChart()

    createPaymentStatusChart()

}


/*
|--------------------------------------------------------------------------
| LIFECYCLE
|--------------------------------------------------------------------------
*/

onMounted(() => {

    renderCharts()

})


onBeforeUnmount(() => {

    if (revenueChart) {
        revenueChart.destroy()
    }

    if (paymentMethodChart) {
        paymentMethodChart.destroy()
    }

    if (paymentStatusChart) {
        paymentStatusChart.destroy()
    }

})

</script>


<template>

    <Head title="Revenue Reports" />

    <MyFashionLayout>

        <div class="report-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Revenue Reports
                    </h1>

                    <p>
                        Analyse revenue, payments and financial performance.
                    </p>

                </div>


                <div class="header-actions">

                    <button type="button" class="refresh-btn" @click="refreshReport">
                        ↻ Refresh
                    </button>


                    <button type="button" class="export-btn" @click="exportReport">
                        ↓ Export Report
                    </button>

                </div>

            </div>


            <!-- =====================================================
                 FILTERS
            ====================================================== -->

            <div class="filter-card">

                <div class="filter-title">

                    <strong>
                        Revenue Period
                    </strong>

                    <span>
                        Select the period you want to analyse.
                    </span>

                </div>


                <div class="period-buttons">

                    <button type="button" :class="{
                        active: selectedPeriod === 'today'
                    }" @click="changePeriod('today')">
                        Today
                    </button>


                    <button type="button" :class="{
                        active: selectedPeriod === 'week'
                    }" @click="changePeriod('week')">
                        This Week
                    </button>


                    <button type="button" :class="{
                        active: selectedPeriod === 'month'
                    }" @click="changePeriod('month')">
                        This Month
                    </button>


                    <button type="button" :class="{
                        active: selectedPeriod === 'year'
                    }" @click="changePeriod('year')">
                        This Year
                    </button>


                    <button type="button" :class="{
                        active: selectedPeriod === 'custom'
                    }" @click="selectedPeriod = 'custom'">
                        Custom
                    </button>

                </div>


                <!-- CUSTOM DATES -->

                <div v-if="selectedPeriod === 'custom'" class="custom-date-row">

                    <div class="date-field">

                        <label>
                            Start Date
                        </label>

                        <input v-model="startDate" type="date" />

                    </div>


                    <div class="date-field">

                        <label>
                            End Date
                        </label>

                        <input v-model="endDate" type="date" />

                    </div>


                    <button type="button" class="apply-btn" @click="applyFilter">
                        Apply Filter
                    </button>

                </div>

            </div>


            <!-- =====================================================
                 FINANCIAL SUMMARY
            ====================================================== -->

            <div class="summary-grid">


                <!-- GROSS -->

                <div class="summary-card">

                    <div class="summary-icon">
                        💰
                    </div>

                    <div>

                        <span>
                            Gross Revenue
                        </span>

                        <strong>
                            ZMW {{ formatMoney(grossRevenue) }}
                        </strong>

                    </div>

                </div>


                <!-- PAID -->

                <div class="summary-card">

                    <div class="summary-icon">
                        ✓
                    </div>

                    <div>

                        <span>
                            Paid Revenue
                        </span>

                        <strong>
                            ZMW {{ formatMoney(paidRevenue) }}
                        </strong>

                    </div>

                </div>


                <!-- PENDING -->

                <div class="summary-card">

                    <div class="summary-icon">
                        ⏳
                    </div>

                    <div>

                        <span>
                            Pending Revenue
                        </span>

                        <strong>
                            ZMW {{ formatMoney(pendingRevenue) }}
                        </strong>

                    </div>

                </div>


                <!-- REFUNDED -->

                <div class="summary-card">

                    <div class="summary-icon">
                        ↩
                    </div>

                    <div>

                        <span>
                            Refunded Revenue
                        </span>

                        <strong>
                            ZMW {{ formatMoney(refundedRevenue) }}
                        </strong>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 SECONDARY SUMMARY
            ====================================================== -->

            <div class="secondary-summary">

                <div class="mini-card">

                    <span>
                        Failed Payments
                    </span>

                    <strong>
                        ZMW {{ formatMoney(failedRevenue) }}
                    </strong>

                </div>


                <div class="mini-card">

                    <span>
                        Cancelled Orders
                    </span>

                    <strong>
                        ZMW {{ formatMoney(cancelledRevenue) }}
                    </strong>

                </div>


                <div class="mini-card">

                    <span>
                        Total Orders
                    </span>

                    <strong>
                        {{ formatNumber(totalOrders) }}
                    </strong>

                </div>


                <div class="mini-card">

                    <span>
                        Average Order Value
                    </span>

                    <strong>
                        ZMW {{ formatMoney(averageRevenue) }}
                    </strong>

                </div>

            </div>


            <!-- =====================================================
                 REVENUE TREND
            ====================================================== -->

            <div class="chart-card revenue-chart-card">

                <div class="chart-header">

                    <div>

                        <h2>
                            Revenue Trend
                        </h2>

                        <p>
                            Revenue generated during the selected period.
                        </p>

                    </div>


                    <span class="chart-label">
                        Revenue
                    </span>

                </div>


                <div class="revenue-chart">

                    <canvas ref="revenueChartCanvas"></canvas>

                </div>

            </div>


            <!-- =====================================================
                 PAYMENT CHARTS
            ====================================================== -->

            <div class="chart-grid">


                <!-- PAYMENT METHOD -->

                <div class="chart-card">

                    <div class="chart-header">

                        <div>

                            <h2>
                                Revenue by Payment Method
                            </h2>

                            <p>
                                Compare revenue across payment methods.
                            </p>

                        </div>

                    </div>


                    <div class="small-chart">

                        <canvas ref="paymentMethodChartCanvas"></canvas>

                    </div>

                </div>


                <!-- PAYMENT STATUS -->

                <div class="chart-card">

                    <div class="chart-header">

                        <div>

                            <h2>
                                Revenue by Payment Status
                            </h2>

                            <p>
                                Financial status of orders.
                            </p>

                        </div>

                    </div>


                    <div class="small-chart">

                        <canvas ref="paymentStatusChartCanvas"></canvas>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 PAYMENT DETAILS TABLE
            ====================================================== -->

            <div class="content-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Payment Revenue Breakdown
                        </h2>

                        <p>
                            Revenue grouped by payment status.
                        </p>

                    </div>


                    <span class="item-count">

                        {{ revenueByPaymentStatus.length }}

                        {{
                            revenueByPaymentStatus.length === 1
                                ? 'Status'
                                : 'Statuses'
                        }}

                    </span>

                </div>


                <div class="table-wrapper">

                    <table class="report-table">

                        <thead>

                            <tr>

                                <th>
                                    Payment Status
                                </th>

                                <th>
                                    Orders
                                </th>

                                <th>
                                    Revenue
                                </th>

                                <th>
                                    Percentage
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr v-for="(item, index) in revenueByPaymentStatus"
                                :key="item.payment_status || item.status || index">

                                <td>

                                    <span class="payment-status"
                                        :class="`payment-${item.payment_status || item.status}`">

                                        {{
                                            paymentStatusLabel(
                                                item.payment_status ||
                                                item.status
                                            )
                                        }}

                                    </span>

                                </td>


                                <td>

                                    {{
                                        formatNumber(
                                            item.orders ||
                                            item.order_count ||
                                            item.count ||
                                            0
                                        )
                                    }}

                                </td>


                                <td>

                                    <strong>

                                        ZMW
                                        {{
                                            formatMoney(
                                                item.revenue ||
                                                item.total ||
                                                item.amount ||
                                                0
                                            )
                                        }}

                                    </strong>

                                </td>


                                <td>

                                    {{
                                        grossRevenue > 0
                                            ? (
                                                (
                                                    Number(
                                                        item.revenue ||
                                                        item.total ||
                                                        item.amount ||
                                                        0
                                                    ) /
                                                    grossRevenue
                                                ) * 100
                                            ).toFixed(1)
                                            : '0.0'
                                    }}%

                                </td>

                            </tr>


                            <tr v-if="!revenueByPaymentStatus.length">

                                <td colspan="4" class="empty-row">
                                    No payment revenue data available.
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =====================================================
                 PAYMENT METHODS TABLE
            ====================================================== -->

            <div class="content-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Payment Method Performance
                        </h2>

                        <p>
                            Revenue generated from each payment method.
                        </p>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="report-table">

                        <thead>

                            <tr>

                                <th>
                                    Payment Method
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

                            <tr v-for="(item, index) in revenueByPaymentMethod"
                                :key="item.payment_method || item.method || index">

                                <td>

                                    <strong>

                                        {{
                                            paymentMethodLabel(
                                                item.payment_method ||
                                                item.method
                                            )
                                        }}

                                    </strong>

                                </td>


                                <td>

                                    {{
                                        formatNumber(
                                            item.orders ||
                                            item.order_count ||
                                            item.count ||
                                            0
                                        )
                                    }}

                                </td>


                                <td>

                                    <strong class="revenue">

                                        ZMW
                                        {{
                                            formatMoney(
                                                item.revenue ||
                                                item.total ||
                                                item.amount ||
                                                0
                                            )
                                        }}

                                    </strong>

                                </td>


                                <td>

                                    ZMW
                                    {{
                                        formatMoney(
                                            Number(
                                                item.orders ||
                                                item.order_count ||
                                                item.count ||
                                                0
                                            ) > 0
                                                ? Number(
                                                    item.revenue ||
                                                    item.total ||
                                                    item.amount ||
                                                    0
                                                ) /
                                                Number(
                                                    item.orders ||
                                                    item.order_count ||
                                                    item.count ||
                                                    1
                                                )
                                                : 0
                                        )
                                    }}

                                </td>

                            </tr>


                            <tr v-if="!revenueByPaymentMethod.length">

                                <td colspan="4" class="empty-row">
                                    No payment method data available.
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =====================================================
                 FOOTER
            ====================================================== -->

            <div class="report-footer">

                <span>
                    Revenue report generated from your order and payment records.
                </span>

                <span>

                    Period:

                    <strong>
                        {{ selectedPeriod }}
                    </strong>

                </span>

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

.report-page {

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

    font-size: 28px;

    font-weight: 700;

}

.page-header p {

    margin: 6px 0 0;

    color: #6b7280;

    font-size: 13px;

}

.header-actions {

    display: flex;

    gap: 8px;

}

.refresh-btn,
.export-btn {

    border: none;

    border-radius: 7px;

    padding: 9px 13px;

    font-size: 11px;

    font-weight: 600;

    cursor: pointer;

}

.refresh-btn {

    background: #f3f4f6;

    color: #374151;

}

.export-btn {

    background: #075c59;

    color: white;

}


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filter-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    padding: 18px;

    margin-bottom: 18px;

}

.filter-title {

    display: flex;

    flex-direction: column;

    gap: 3px;

    margin-bottom: 14px;

}

.filter-title strong {

    color: #111827;

    font-size: 13px;

}

.filter-title span {

    color: #9ca3af;

    font-size: 10px;

}

.period-buttons {

    display: flex;

    flex-wrap: wrap;

    gap: 8px;

}

.period-buttons button {

    padding: 8px 14px;

    border: 1px solid #e5e7eb;

    border-radius: 7px;

    background: white;

    color: #555;

    font-size: 10px;

    font-weight: 600;

    cursor: pointer;

}

.period-buttons button:hover {

    border-color: #075c59;

    color: #075c59;

}

.period-buttons button.active {

    background: #075c59;

    border-color: #075c59;

    color: white;

}

.custom-date-row {

    display: flex;

    align-items: flex-end;

    gap: 12px;

    margin-top: 15px;

}

.date-field {

    display: flex;

    flex-direction: column;

    gap: 5px;

}

.date-field label {

    color: #6b7280;

    font-size: 9px;

    font-weight: 600;

}

.date-field input {

    height: 34px;

    padding: 0 9px;

    border: 1px solid #e5e7eb;

    border-radius: 6px;

    font-size: 10px;

}

.apply-btn {

    height: 34px;

    padding: 0 15px;

    border: none;

    border-radius: 6px;

    background: #111827;

    color: white;

    font-size: 10px;

    font-weight: 600;

    cursor: pointer;

}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.summary-grid {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 15px;

    margin-bottom: 15px;

}

.summary-card {

    display: flex;

    align-items: center;

    gap: 13px;

    padding: 17px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 11px;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .03);

}

.summary-icon {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: #f3f4f6;

    font-size: 17px;

}

.summary-card span {

    display: block;

    color: #9ca3af;

    font-size: 9px;

    margin-bottom: 4px;

}

.summary-card strong {

    display: block;

    color: #111827;

    font-size: 15px;

}


/*
|--------------------------------------------------------------------------
| SECONDARY SUMMARY
|--------------------------------------------------------------------------
*/

.secondary-summary {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 12px;

    margin-bottom: 18px;

}

.mini-card {

    padding: 14px 16px;

    background: #f9fafb;

    border: 1px solid #edf0f2;

    border-radius: 9px;

}

.mini-card span {

    display: block;

    margin-bottom: 5px;

    color: #9ca3af;

    font-size: 9px;

}

.mini-card strong {

    color: #374151;

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| CHART CARD
|--------------------------------------------------------------------------
*/

.chart-card {

    min-width: 0;

    padding: 18px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .03);

}

.revenue-chart-card {

    margin-bottom: 18px;

}

.chart-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-bottom: 15px;

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

.chart-label {

    padding: 5px 8px;

    border-radius: 5px;

    background: #eef7f5;

    color: #075c59;

    font-size: 9px;

    font-weight: 600;

}

.revenue-chart {

    height: 320px;

    position: relative;

}


/*
|--------------------------------------------------------------------------
| CHART GRID
|--------------------------------------------------------------------------
*/

.chart-grid {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 18px;

    margin-bottom: 18px;

}

.small-chart {

    height: 280px;

    position: relative;

}


/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

.content-card {

    overflow: hidden;

    margin-bottom: 18px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .03);

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

    font-size: 14px;

    font-weight: 700;

}

.card-header p {

    margin: 5px 0 0;

    color: #9ca3af;

    font-size: 10px;

}

.item-count {

    padding: 5px 9px;

    border-radius: 6px;

    background: #f3f4f6;

    color: #555;

    font-size: 9px;

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

.report-table {

    width: 100%;

    min-width: 650px;

    border-collapse: collapse;

}

.report-table th {

    padding: 12px 16px;

    background: #f9fafb;

    border-bottom: 1px solid #e5e7eb;

    color: #6b7280;

    font-size: 9px;

    font-weight: 700;

    text-align: left;

    text-transform: uppercase;

}

.report-table td {

    padding: 13px 16px;

    border-bottom: 1px solid #f0f1f2;

    color: #374151;

    font-size: 10px;

}

.report-table tr:last-child td {

    border-bottom: none;

}

.revenue {

    color: #075c59;

}


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

.payment-status {

    display: inline-flex;

    padding: 5px 9px;

    border-radius: 6px;

    font-size: 9px;

    font-weight: 700;

    text-transform: uppercase;

}

.payment-pending {

    background: #fff7e6;

    color: #a0833d;

}

.payment-paid {

    background: #edf7ed;

    color: #287a2d;

}

.payment-failed {

    background: #fceeee;

    color: #b33a3a;

}

.payment-cancelled {

    background: #fceeee;

    color: #991b1b;

}

.payment-refunded {

    background: #f3e8ff;

    color: #7e22ce;

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-row {

    padding: 40px !important;

    text-align: center;

    color: #9ca3af;

}


/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.report-footer {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 10px 3px;

    color: #9ca3af;

    font-size: 9px;

}

.report-footer strong {

    color: #374151;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1100px) {

    .summary-grid {

        grid-template-columns: repeat(2, 1fr);

    }

    .secondary-summary {

        grid-template-columns: repeat(2, 1fr);

    }

}


@media (max-width: 800px) {

    .chart-grid {

        grid-template-columns: 1fr;

    }

}


@media (max-width: 650px) {

    .report-page {

        padding: 5px;

    }

    .page-header {

        align-items: flex-start;

        flex-direction: column;

    }

    .header-actions {

        width: 100%;

    }

    .refresh-btn,
    .export-btn {

        flex: 1;

    }

    .summary-grid {

        grid-template-columns: 1fr;

    }

    .secondary-summary {

        grid-template-columns: 1fr;

    }

    .custom-date-row {

        align-items: stretch;

        flex-direction: column;

    }

    .apply-btn {

        width: 100%;

    }

    .report-footer {

        align-items: flex-start;

        flex-direction: column;

    }

}
</style>