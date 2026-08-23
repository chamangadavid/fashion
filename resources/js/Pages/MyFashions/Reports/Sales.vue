<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'

import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js'

import Swal from 'sweetalert2'

Chart.register(
    LineController,
    LineElement,
    PointElement,
    BarController,
    BarElement,
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
            salesByDate: [],
            ordersByStatus: [],
            topProducts: [],
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

const salesChartCanvas = ref(null)
const statusChartCanvas = ref(null)

let salesChart = null
let statusChart = null

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

const summary = computed(() => {

    return props.reports?.summary || {}

})

const totalSales = computed(() => {

    return Number(
        summary.value.total_sales || 0
    )

})

const totalOrders = computed(() => {

    return Number(
        summary.value.total_orders || 0
    )

})

const itemsSold = computed(() => {

    return Number(
        summary.value.items_sold || 0
    )

})

const averageOrderValue = computed(() => {

    if (!totalOrders.value) {
        return 0
    }

    return totalSales.value / totalOrders.value

})

/*
|--------------------------------------------------------------------------
| SALES DATA
|--------------------------------------------------------------------------
*/

const salesByDate = computed(() => {
    return props.reports?.salesByMonth || []
})
/*
|--------------------------------------------------------------------------
| ORDER STATUS
|--------------------------------------------------------------------------
*/

const ordersByStatus = computed(() => {

    return props.reports?.ordersByStatus || []

})

/*
|--------------------------------------------------------------------------
| TOP PRODUCTS
|--------------------------------------------------------------------------
*/

const topProducts = computed(() => {

    return props.reports?.topProducts || []

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
| APPLY FILTER
|--------------------------------------------------------------------------
*/

const applyFilter = () => {

    router.get(
        '/fashion/reports/sales',
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
| QUICK PERIOD
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
        title: 'Export Sales Report',
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
                `/fashion/reports/sales/export/excel?period=${selectedPeriod.value}&start_date=${startDate.value}&end_date=${endDate.value}`

        }

        if (result.isDenied) {

            window.location.href =
                `/fashion/reports/sales/export/pdf?period=${selectedPeriod.value}&start_date=${startDate.value}&end_date=${endDate.value}`

        }

    })

}

/*
|--------------------------------------------------------------------------
| SALES CHART
|--------------------------------------------------------------------------
*/

const createSalesChart = () => {

    if (!salesChartCanvas.value) {
        return
    }

    if (salesChart) {
        salesChart.destroy()
    }

    // const labels = salesByDate.value.map(
    //     item => item.date || item.label
    // )

    const labels = salesByDate.value.map(
        item => item.date || item.label || item.month
    )

    const sales = salesByDate.value.map(
        item => Number(
            item.sales ||
            item.total_sales ||
            item.revenue ||
            item.total ||
            0
        )
    )

    // const sales = salesByDate.value.map(
    //     item => Number(
    //         item.sales ||
    //         item.total_sales ||
    //         item.revenue ||
    //         0
    //     )
    // )

    salesChart = new Chart(
        salesChartCanvas.value,
        {
            type: 'line',

            data: {
                labels,

                datasets: [
                    {
                        label: 'Sales',

                        data: sales,

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
| STATUS CHART
|--------------------------------------------------------------------------
*/

const createStatusChart = () => {

    if (!statusChartCanvas.value) {
        return
    }

    if (statusChart) {
        statusChart.destroy()
    }

    const labels = ordersByStatus.value.map(
        item => item.status
    )

    const values = ordersByStatus.value.map(
        item => Number(
            item.total ||
            item.count ||
            0
        )
    )

    statusChart = new Chart(
        statusChartCanvas.value,
        {
            type: 'bar',

            data: {

                labels,

                datasets: [
                    {
                        label: 'Orders',

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

                },

                scales: {

                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0,
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
| CREATE CHARTS
|--------------------------------------------------------------------------
*/

const renderCharts = () => {

    createSalesChart()

    createStatusChart()

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

    if (salesChart) {
        salesChart.destroy()
    }

    if (statusChart) {
        statusChart.destroy()
    }

})

</script>


<template>

    <Head title="Sales Reports" />

    <MyFashionLayout>

        <div class="report-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Sales Reports
                    </h1>

                    <p>
                        Monitor sales performance, orders and revenue.
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
                        Report Period
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
                 SUMMARY CARDS
            ====================================================== -->

            <div class="summary-grid">

                <div class="summary-card">

                    <div class="summary-icon">
                        💰
                    </div>

                    <div>

                        <span>
                            Total Sales
                        </span>

                        <strong>
                            ZMW {{ formatMoney(totalSales) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        🧾
                    </div>

                    <div>

                        <span>
                            Total Orders
                        </span>

                        <strong>
                            {{ formatNumber(totalOrders) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        📦
                    </div>

                    <div>

                        <span>
                            Items Sold
                        </span>

                        <strong>
                            {{ formatNumber(itemsSold) }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        📈
                    </div>

                    <div>

                        <span>
                            Average Order
                        </span>

                        <strong>
                            ZMW {{ formatMoney(averageOrderValue) }}
                        </strong>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 CHART GRID
            ====================================================== -->

            <div class="chart-grid">


                <!-- SALES CHART -->

                <div class="chart-card large-chart">

                    <div class="chart-header">

                        <div>

                            <h2>
                                Sales Performance
                            </h2>

                            <p>
                                Sales revenue over the selected period.
                            </p>

                        </div>

                        <span class="chart-label">
                            Revenue
                        </span>

                    </div>


                    <div class="chart-container">

                        <canvas ref="salesChartCanvas"></canvas>

                    </div>

                </div>


                <!-- STATUS CHART -->

                <div class="chart-card">

                    <div class="chart-header">

                        <div>

                            <h2>
                                Orders by Status
                            </h2>

                            <p>
                                Order distribution.
                            </p>

                        </div>

                    </div>


                    <div class="chart-container status-chart">

                        <canvas ref="statusChartCanvas"></canvas>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 TOP PRODUCTS
            ====================================================== -->

            <div class="content-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Top Selling Products
                        </h2>

                        <p>
                            Best performing products during this period.
                        </p>

                    </div>

                    <span class="item-count">

                        {{ topProducts.length }}

                        {{
                            topProducts.length === 1
                                ? 'Product'
                                : 'Products'
                        }}

                    </span>

                </div>


                <div class="table-wrapper">

                    <table class="report-table">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    SKU
                                </th>

                                <th>
                                    Units Sold
                                </th>

                                <th>
                                    Orders
                                </th>

                                <th>
                                    Revenue
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr v-for="(product, index) in topProducts" :key="product.id || index">

                                <td>

                                    <span class="rank">
                                        {{ index + 1 }}
                                    </span>

                                </td>

                                <td>

                                    <div class="product-name">

                                        <strong>
                                            {{
                                                product.product_name ||
                                                product.name ||
                                                '-'
                                            }}
                                        </strong>

                                    </div>

                                </td>

                                <td>

                                    <span class="sku">
                                        {{
                                            product.sku ||
                                            product.product_sku ||
                                            '-'
                                        }}
                                    </span>

                                </td>

                                <td>

                                    <strong>
                                        {{
                                            formatNumber(
                                                product.units_sold ||
                                                product.quantity_sold ||
                                                product.quantity ||
                                                0
                                            )
                                        }}
                                    </strong>

                                </td>

                                <td>

                                    {{
                                        formatNumber(
                                            product.orders ||
                                            product.order_count ||
                                            0
                                        )
                                    }}

                                </td>

                                <td>

                                    <strong class="revenue">

                                        ZMW
                                        {{
                                            formatMoney(
                                                product.revenue ||
                                                product.total_sales ||
                                                product.total ||
                                                0
                                            )
                                        }}

                                    </strong>

                                </td>

                            </tr>


                            <tr v-if="!topProducts.length">

                                <td colspan="6" class="empty-row">
                                    No sales data available for this period.
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- =====================================================
                 REPORT INFORMATION
            ====================================================== -->

            <div class="report-footer">

                <span>
                    Sales report generated from your order records.
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

    gap: 8px;

    flex-wrap: wrap;

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

    margin-bottom: 18px;

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

    font-size: 18px;

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
| CHARTS
|--------------------------------------------------------------------------
*/

.chart-grid {

    display: grid;

    grid-template-columns: minmax(0, 1.7fr) minmax(300px, 1fr);

    gap: 18px;

    margin-bottom: 18px;

}

.chart-card {

    min-width: 0;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    padding: 18px;

    box-shadow: 0 2px 10px rgba(0, 0, 0, .03);

}

.chart-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

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

.chart-container {

    height: 300px;

    position: relative;

}

.status-chart {

    height: 300px;

}


/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

.content-card {

    overflow: hidden;

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

    border-collapse: collapse;

    min-width: 700px;

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

.rank {

    display: inline-flex;

    width: 24px;

    height: 24px;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f3f4f6;

    color: #374151;

    font-size: 9px;

    font-weight: 700;

}

.product-name strong {

    color: #111827;

    font-size: 11px;

}

.sku {

    color: #9ca3af;

    font-size: 9px;

}

.revenue {

    color: #075c59;

}

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

    justify-content: space-between;

    gap: 15px;

    margin-top: 14px;

    padding: 12px 3px;

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

    .chart-grid {

        grid-template-columns: 1fr;

    }

}


@media (max-width: 700px) {

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

    .custom-date-row {

        align-items: stretch;

        flex-direction: column;

    }

    .apply-btn {

        width: 100%;

    }

    .report-footer {

        flex-direction: column;

    }

}
</style>