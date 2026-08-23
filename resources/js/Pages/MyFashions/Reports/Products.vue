<!-- resources\js\Pages\MyFashions\Reports\Products.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, onMounted, onBeforeUnmount, ref, watch } from 'vue'

import {
    Chart,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    DoughnutController,
    ArcElement,
    Tooltip,
    Legend,
} from 'chart.js'

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    DoughnutController,
    ArcElement,
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
            products: [],
            summary: {},
        }),
    },

})

/*
|--------------------------------------------------------------------------
| CHART REFERENCES
|--------------------------------------------------------------------------
*/

const salesChart = ref(null)
const revenueChart = ref(null)
const categoryChart = ref(null)

let salesChartInstance = null
let revenueChartInstance = null
let categoryChartInstance = null

/*
|--------------------------------------------------------------------------
| DATE FILTER
|--------------------------------------------------------------------------
*/

const dateRange = ref('month')

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
| PRODUCTS
|--------------------------------------------------------------------------
*/

const products = computed(() => {

    return props.reports?.products || []

})

/*
|--------------------------------------------------------------------------
| NUMBER FORMAT
|--------------------------------------------------------------------------
*/

const formatNumber = (value) => {

    return Number(value || 0).toLocaleString('en-US')

}

/*
|--------------------------------------------------------------------------
| MONEY FORMAT
|--------------------------------------------------------------------------
*/

const formatMoney = (value) => {

    return Number(value || 0).toLocaleString('en-ZM', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })

}

/*
|--------------------------------------------------------------------------
| PRODUCT DATA
|--------------------------------------------------------------------------
*/

const productNames = computed(() => {

    return products.value.map(product =>
        product.product_name ||
        product.name ||
        'Unknown Product'
    )

})

const productQuantities = computed(() => {

    return products.value.map(product =>
        Number(
            product.quantity_sold ??
            product.total_quantity ??
            product.units_sold ??
            0
        )
    )

})

const productRevenue = computed(() => {

    return products.value.map(product =>
        Number(
            product.revenue ??
            product.total_revenue ??
            product.sales ??
            0
        )
    )

})

/*
|--------------------------------------------------------------------------
| CREATE SALES CHART
|--------------------------------------------------------------------------
*/

const createSalesChart = () => {

    if (!salesChart.value) {
        return
    }

    if (salesChartInstance) {
        salesChartInstance.destroy()
    }

    salesChartInstance = new Chart(
        salesChart.value,
        {
            type: 'bar',

            data: {
                labels: productNames.value,

                datasets: [
                    {
                        label: 'Units Sold',

                        data: productQuantities.value,

                        borderWidth: 0,

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

                            label: (context) => {

                                return ` ${formatNumber(context.raw)} units`

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false,
                        },

                        ticks: {
                            font: {
                                size: 10,
                            },

                            maxRotation: 45,

                            minRotation: 0,
                        }

                    },

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0,

                            font: {
                                size: 10,
                            }

                        }

                    }

                }

            }

        }
    )

}

/*
|--------------------------------------------------------------------------
| CREATE REVENUE CHART
|--------------------------------------------------------------------------
*/

const createRevenueChart = () => {

    if (!revenueChart.value) {
        return
    }

    if (revenueChartInstance) {
        revenueChartInstance.destroy()
    }

    revenueChartInstance = new Chart(
        revenueChart.value,
        {

            type: 'bar',

            data: {

                labels: productNames.value,

                datasets: [
                    {
                        label: 'Revenue',

                        data: productRevenue.value,

                        borderWidth: 0,

                        borderRadius: 6,
                    }
                ]

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

                            label: (context) => {

                                return ` ZMW ${formatMoney(context.raw)}`

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false,
                        },

                        ticks: {

                            font: {
                                size: 10,
                            },

                            maxRotation: 45,

                            minRotation: 0,

                        }

                    },

                    y: {

                        beginAtZero: true,

                        ticks: {

                            font: {
                                size: 10,
                            },

                            callback: (value) => {

                                return `ZMW ${formatMoney(value)}`

                            }

                        }

                    }

                }

            }

        }
    )

}

/*
|--------------------------------------------------------------------------
| CREATE CATEGORY CHART
|--------------------------------------------------------------------------
*/

const createCategoryChart = () => {

    if (!categoryChart.value) {
        return
    }

    if (categoryChartInstance) {
        categoryChartInstance.destroy()
    }

    const categories = props.reports?.categories || []

    const labels = categories.map(
        category =>
            category.category_name ||
            category.name ||
            'Unknown'
    )

    const values = categories.map(
        category =>
            Number(
                category.revenue ??
                category.total_revenue ??
                category.sales ??
                0
            )
    )

    categoryChartInstance = new Chart(
        categoryChart.value,
        {

            type: 'doughnut',

            data: {

                labels,

                datasets: [
                    {
                        data: values,

                        borderWidth: 2,
                    }
                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '65%',

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            boxWidth: 10,

                            padding: 15,

                            font: {
                                size: 10,
                            }

                        }

                    },

                    tooltip: {

                        callbacks: {

                            label: (context) => {

                                return ` ${context.label}: ZMW ${formatMoney(context.raw)}`

                            }

                        }

                    }

                }

            }

        }
    )

}

/*
|--------------------------------------------------------------------------
| CREATE ALL CHARTS
|--------------------------------------------------------------------------
*/

const createCharts = () => {

    setTimeout(() => {

        createSalesChart()

        createRevenueChart()

        createCategoryChart()

    }, 100)

}

/*
|--------------------------------------------------------------------------
| DATE FILTER
|--------------------------------------------------------------------------
*/

const changeDateRange = () => {

    /*
    |--------------------------------------------------------------------------
    | If your controller already supports a date filter,
    | this is where you can later use Inertia router.get().
    |--------------------------------------------------------------------------
    */

    createCharts()

}

/*
|--------------------------------------------------------------------------
| WATCH DATA
|--------------------------------------------------------------------------
*/

watch(
    () => props.reports,
    () => {

        createCharts()

    },
    {
        deep: true,
    }
)

/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/

onMounted(() => {

    createCharts()

})

/*
|--------------------------------------------------------------------------
| CLEANUP
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {

    if (salesChartInstance) {
        salesChartInstance.destroy()
    }

    if (revenueChartInstance) {
        revenueChartInstance.destroy()
    }

    if (categoryChartInstance) {
        categoryChartInstance.destroy()
    }

})

</script>


<template>

    <Head title="Product Reports" />

    <MyFashionLayout>

        <div class="reports-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Product Reports
                    </h1>

                    <p>
                        Analyse product performance, sales and revenue.
                    </p>

                </div>


                <div class="filter-box">

                    <label>
                        Period
                    </label>

                    <select v-model="dateRange" @change="changeDateRange">

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

                    </select>

                </div>

            </div>


            <!-- =====================================================
                 SUMMARY CARDS
            ====================================================== -->

            <div class="summary-grid">

                <div class="summary-card">

                    <div class="summary-icon">
                        📦
                    </div>

                    <div>

                        <span>
                            Total Products
                        </span>

                        <strong>
                            {{
                                formatNumber(
                                    summary.total_products ??
                                    products.length
                                )
                            }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        🛒
                    </div>

                    <div>

                        <span>
                            Units Sold
                        </span>

                        <strong>
                            {{
                                formatNumber(
                                    summary.units_sold ??
                                    summary.total_quantity ??
                                    productQuantities.reduce(
                                        (total, value) => total + value,
                                        0
                                    )
                                )
                            }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        💰
                    </div>

                    <div>

                        <span>
                            Product Revenue
                        </span>

                        <strong>
                            ZMW
                            {{
                                formatMoney(
                                    summary.revenue ??
                                    summary.total_revenue ??
                                    productRevenue.reduce(
                                        (total, value) => total + value,
                                        0
                                    )
                                )
                            }}
                        </strong>

                    </div>

                </div>


                <div class="summary-card">

                    <div class="summary-icon">
                        ⭐
                    </div>

                    <div>

                        <span>
                            Best Seller
                        </span>

                        <strong>

                            {{
                                summary.best_seller ||
                                products[0]?.product_name ||
                                products[0]?.name ||
                                '-'
                            }}

                        </strong>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 CHARTS
            ====================================================== -->

            <div class="charts-grid">


                <!-- =================================================
                     UNITS SOLD
                ================================================== -->

                <div class="report-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Units Sold by Product
                            </h2>

                            <p>
                                Products with the highest sales volume.
                            </p>

                        </div>

                    </div>

                    <div class="chart-container">

                        <canvas ref="salesChart"></canvas>

                    </div>

                </div>


                <!-- =================================================
                     REVENUE
                ================================================== -->

                <div class="report-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Revenue by Product
                            </h2>

                            <p>
                                Revenue generated by each product.
                            </p>

                        </div>

                    </div>

                    <div class="chart-container">

                        <canvas ref="revenueChart"></canvas>

                    </div>

                </div>


            </div>


            <!-- =====================================================
                 CATEGORY CHART
            ====================================================== -->

            <div v-if="props.reports?.categories?.length" class="report-card category-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Revenue by Category
                        </h2>

                        <p>
                            Product revenue distribution by category.
                        </p>

                    </div>

                </div>

                <div class="category-chart-container">

                    <canvas ref="categoryChart"></canvas>

                </div>

            </div>


            <!-- =====================================================
                 PRODUCT TABLE
            ====================================================== -->

            <div class="report-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Product Performance
                        </h2>

                        <p>
                            Detailed performance of your products.
                        </p>

                    </div>

                    <span class="record-count">

                        {{ products.length }}

                        {{
                            products.length === 1
                                ? 'Product'
                                : 'Products'
                        }}

                    </span>

                </div>


                <div class="table-wrapper">

                    <table>

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
                                    Revenue
                                </th>

                                <th>
                                    Orders
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr v-for="(product, index) in products" :key="product.id || index">

                                <td>
                                    {{ index + 1 }}
                                </td>

                                <td>

                                    <div class="product-name">

                                        {{
                                            product.product_name ||
                                            product.name ||
                                            'Unknown Product'
                                        }}

                                    </div>

                                </td>

                                <td>

                                    {{
                                        product.sku ||
                                        product.product_sku ||
                                        '-'
                                    }}

                                </td>

                                <td>

                                    <span class="quantity-badge">

                                        {{
                                            formatNumber(
                                                product.quantity_sold ??
                                                product.total_quantity ??
                                                product.units_sold ??
                                                0
                                            )
                                        }}

                                    </span>

                                </td>

                                <td>

                                    <strong>

                                        ZMW
                                        {{
                                            formatMoney(
                                                product.revenue ??
                                                product.total_revenue ??
                                                product.sales ??
                                                0
                                            )
                                        }}

                                    </strong>

                                </td>

                                <td>

                                    {{
                                        formatNumber(
                                            product.orders_count ??
                                            product.order_count ??
                                            product.orders ??
                                            0
                                        )
                                    }}

                                </td>

                            </tr>


                            <tr v-if="!products.length">

                                <td colspan="6" class="empty-row">

                                    No product report data available.

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

.reports-page {

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

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

.filter-box {

    display: flex;

    align-items: center;

    gap: 8px;

}

.filter-box label {

    color: #6b7280;

    font-size: 11px;

    font-weight: 600;

}

.filter-box select {

    min-width: 130px;

    padding: 9px 12px;

    border: 1px solid #e5e7eb;

    border-radius: 7px;

    background: white;

    color: #374151;

    font-size: 11px;

    outline: none;

}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.summary-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

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

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .03);

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

    margin-bottom: 4px;

    color: #9ca3af;

    font-size: 10px;

}

.summary-card strong {

    display: block;

    color: #111827;

    font-size: 15px;

    font-weight: 700;

}


/*
|--------------------------------------------------------------------------
| CHART GRID
|--------------------------------------------------------------------------
*/

.charts-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;

    margin-bottom: 18px;

}


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.report-card {

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

    overflow: hidden;

    margin-bottom: 18px;

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

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| CHART
|--------------------------------------------------------------------------
*/

.chart-container {

    position: relative;

    height: 330px;

    padding: 18px;

}

.category-chart-container {

    position: relative;

    height: 350px;

    max-width: 600px;

    margin: auto;

    padding: 20px;

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

table {

    width: 100%;

    min-width: 750px;

    border-collapse: collapse;

}

thead th {

    padding: 13px 16px;

    background: #f9fafb;

    border-bottom: 1px solid #e5e7eb;

    color: #6b7280;

    font-size: 10px;

    font-weight: 700;

    text-align: left;

    white-space: nowrap;

}

tbody td {

    padding: 14px 16px;

    border-bottom: 1px solid #f0f1f2;

    color: #374151;

    font-size: 11px;

}

tbody tr:last-child td {

    border-bottom: none;

}

tbody tr:hover {

    background: #fafafa;

}


/*
|--------------------------------------------------------------------------
| PRODUCT
|--------------------------------------------------------------------------
*/

.product-name {

    color: #111827;

    font-weight: 600;

}

.quantity-badge {

    display: inline-flex;

    min-width: 35px;

    align-items: center;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 5px;

    background: #f3f4f6;

    color: #374151;

    font-weight: 600;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| RECORD COUNT
|--------------------------------------------------------------------------
*/

.record-count {

    padding: 5px 9px;

    border-radius: 6px;

    background: #f3f4f6;

    color: #555;

    font-size: 10px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-row {

    padding: 45px !important;

    text-align: center;

    color: #9ca3af;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1000px) {

    .summary-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

    .charts-grid {

        grid-template-columns: 1fr;

    }

}


@media (max-width: 650px) {

    .reports-page {

        padding: 5px;

    }

    .page-header {

        align-items: flex-start;

        flex-direction: column;

    }

    .page-header h1 {

        font-size: 24px;

    }

    .summary-grid {

        grid-template-columns: 1fr;

    }

    .filter-box {

        width: 100%;

    }

    .filter-box select {

        flex: 1;

    }

    .chart-container {

        height: 280px;

    }

}
</style>