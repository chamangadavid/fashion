<!-- resources\js\Pages\MyFashions\Products\Index.vue -->
<script setup>
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import {
    Link,
    Head,
    router,
} from '@inertiajs/vue3';

import {
    ref,
    computed,
    watch,
} from 'vue';

import {
    SearchOutlined,
    PlusOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    StarFilled,
    ReloadOutlined,
    FilterOutlined,
    ShoppingOutlined,
    ExclamationCircleOutlined,
} from '@ant-design/icons-vue';

import { Modal, message } from 'ant-design-vue';


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    products: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0,
            links: [],
        }),
    },

    categories: {
        type: Array,
        default: () => [],
    },

});


/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

const search = ref('');

const selectedCategory = ref('');

const selectedStatus = ref('');


/*
|--------------------------------------------------------------------------
| SEARCH / FILTER
|--------------------------------------------------------------------------
*/

const applyFilters = () => {

    router.get(
        '/fashion/products',
        {
            search: search.value || undefined,

            category:
                selectedCategory.value || undefined,

            status:
                selectedStatus.value || undefined,
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
| WATCH SEARCH
|--------------------------------------------------------------------------
*/

let searchTimeout = null;

watch(search, () => {

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {

        applyFilters();

    }, 500);

});


/*
|--------------------------------------------------------------------------
| RESET FILTERS
|--------------------------------------------------------------------------
*/

const resetFilters = () => {

    search.value = '';

    selectedCategory.value = '';

    selectedStatus.value = '';

    router.get(
        '/fashion/products',
        {},
        {
            preserveState: true,

            preserveScroll: true,
        }
    );

};


/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE
|--------------------------------------------------------------------------
*/

const productImage = (product) => {

    if (!product?.image) {

        return null;

    }

    if (
        product.image.startsWith('http://') ||
        product.image.startsWith('https://')
    ) {

        return product.image;

    }

    return `/storage/${product.image}`;

};


/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {

    return new Intl.NumberFormat('en-ZM', {

        style: 'currency',

        currency: 'ZMW',

        minimumFractionDigits: 2,

    }).format(Number(amount || 0));

};


/*
|--------------------------------------------------------------------------
| PRODUCT STATUS
|--------------------------------------------------------------------------
*/

const statusLabel = (product) => {

    return product?.is_active
        ? 'Active'
        : 'Inactive';

};


const statusClass = (product) => {

    return product?.is_active
        ? 'status-active'
        : 'status-inactive';

};


/*
|--------------------------------------------------------------------------
| STOCK STATUS
|--------------------------------------------------------------------------
*/

const stockClass = (product) => {

    const quantity =
        Number(product?.stock_quantity || 0);

    const threshold =
        Number(product?.low_stock_threshold || 0);

    if (quantity <= 0) {

        return 'stock-out';

    }

    if (quantity <= threshold) {

        return 'stock-low';

    }

    return 'stock-good';

};


const stockLabel = (product) => {

    const quantity =
        Number(product?.stock_quantity || 0);

    const threshold =
        Number(product?.low_stock_threshold || 0);

    if (quantity <= 0) {

        return 'Out of stock';

    }

    if (quantity <= threshold) {

        return 'Low stock';

    }

    return `${quantity} in stock`;

};


/*
|--------------------------------------------------------------------------
| DELETE PRODUCT
|--------------------------------------------------------------------------
*/

const deleteProduct = (product) => {

    Modal.confirm({

        title: 'Delete Product',

        content:
            `Are you sure you want to delete "${product.name}"?`,

        okText: 'Delete',

        cancelText: 'Cancel',

        okType: 'danger',

        centered: true,

        onOk() {

            router.delete(
                `/fashion/products/${product.id}`,
                {

                    preserveScroll: true,

                    onSuccess: () => {

                        message.success(
                            'Product deleted successfully.'
                        );

                    },

                    onError: () => {

                        message.error(
                            'Unable to delete product.'
                        );

                    },

                }
            );

        },

    });

};


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const goToPage = (url) => {

    if (!url) {

        return;

    }

    router.get(
        url,
        {},
        {

            preserveState: true,

            preserveScroll: true,

        }
    );

};


/*
|--------------------------------------------------------------------------
| PRODUCT COUNT
|--------------------------------------------------------------------------
*/

const productCount = computed(() => {

    return props.products?.total ?? 0;

});


</script>


<template>

    <Head title="Products" />

    <MyFashionLayout>


        <!-- =========================================================
             PRODUCTS PAGE
        ========================================================== -->

        <div class="products-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <div class="title-row">

                        <div class="title-icon">

                            <ShoppingOutlined />

                        </div>

                        <div>

                            <h1>
                                All Products
                            </h1>

                            <p>
                                Manage all products in your fashion store.
                            </p>

                        </div>

                    </div>

                </div>


                <Link
                    href="/fashion/products/create"
                    class="btn-primary"
                >

                    <PlusOutlined />

                    <span>
                        Add Product
                    </span>

                </Link>

            </div>



            <!-- =====================================================
                 SUMMARY
            ====================================================== -->

            <div class="summary-card">

                <div class="summary-left">

                    <div class="summary-icon">

                        <ShoppingOutlined />

                    </div>

                    <div>

                        <span>
                            Total Products
                        </span>

                        <strong>
                            {{ productCount }}
                        </strong>

                    </div>

                </div>


                <div class="summary-right">

                    <span>
                        Showing
                    </span>

                    <strong>
                        {{ props.products?.from || 0 }}
                    </strong>

                    <span>
                        -
                    </span>

                    <strong>
                        {{ props.products?.to || 0 }}
                    </strong>

                </div>

            </div>



            <!-- =====================================================
                 FILTER CARD
            ====================================================== -->

            <div class="filter-card">


                <!-- SEARCH -->

                <div class="search-box">

                    <SearchOutlined />

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by product name or SKU..."
                    />

                </div>


                <!-- CATEGORY -->

                <div class="filter-control">

                    <FilterOutlined />

                    <select
                        v-model="selectedCategory"
                        @change="applyFilters"
                    >

                        <option value="">
                            All Categories
                        </option>

                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >

                            {{ category.name }}

                        </option>

                    </select>

                </div>


                <!-- STATUS -->

                <div class="filter-control">

                    <select
                        v-model="selectedStatus"
                        @change="applyFilters"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                    </select>

                </div>


                <!-- RESET -->

                <button
                    type="button"
                    class="reset-button"
                    @click="resetFilters"
                >

                    <ReloadOutlined />

                    Reset

                </button>

            </div>



            <!-- =====================================================
                 PRODUCTS TABLE
            ====================================================== -->

            <div class="content-card">


                <!-- TABLE -->

                <div
                    v-if="products.data?.length"
                    class="table-wrapper"
                >

                    <table class="products-table">

                        <thead>

                            <tr>

                                <th>
                                    Product
                                </th>

                                <th>
                                    SKU
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Stock
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Featured
                                </th>

                                <th class="actions-column">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="product in products.data"
                                :key="product.id"
                            >


                                <!-- PRODUCT -->

                                <td>

                                    <div class="product-cell">

                                        <div class="product-image">

                                            <img
                                                v-if="productImage(product)"
                                                :src="productImage(product)"
                                                :alt="product.name"
                                            />

                                            <ShoppingOutlined
                                                v-else
                                            />

                                        </div>


                                        <div class="product-details">

                                            <strong>
                                                {{ product.name }}
                                            </strong>

                                            <small>
                                                ID #{{ product.id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- SKU -->

                                <td>

                                    <span class="sku">
                                        {{ product.sku }}
                                    </span>

                                </td>


                                <!-- CATEGORY -->

                                <td>

                                    <span class="category-name">

                                        {{
                                            product.category?.name
                                            || 'Uncategorized'
                                        }}

                                    </span>

                                </td>


                                <!-- PRICE -->

                                <td>

                                    <div class="price-cell">

                                        <strong>
                                            {{ formatMoney(product.price) }}
                                        </strong>

                                        <small
                                            v-if="product.sale_price"
                                        >

                                            Sale:
                                            {{
                                                formatMoney(
                                                    product.sale_price
                                                )
                                            }}

                                        </small>

                                    </div>

                                </td>


                                <!-- STOCK -->

                                <td>

                                    <div class="stock-cell">

                                        <span
                                            class="stock-badge"
                                            :class="stockClass(product)"
                                        >

                                            <span class="stock-dot"></span>

                                            {{ stockLabel(product) }}

                                        </span>

                                        <small>
                                            {{ product.stock_quantity }}
                                            units
                                        </small>

                                    </div>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="status-badge"
                                        :class="statusClass(product)"
                                    >

                                        {{ statusLabel(product) }}

                                    </span>

                                </td>


                                <!-- FEATURED -->

                                <td>

                                    <span
                                        v-if="product.is_featured"
                                        class="featured-badge"
                                    >

                                        <StarFilled />

                                        Featured

                                    </span>

                                    <span
                                        v-else
                                        class="not-featured"
                                    >
                                        —
                                    </span>

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="action-buttons">


                                        <!-- VIEW -->

                                        <Link
                                            :href="`/fashion/products/${product.id}`"
                                            class="action-button view"
                                            title="View Product"
                                        >

                                            <EyeOutlined />

                                        </Link>


                                        <!-- EDIT -->

                                        <Link
                                            :href="`/fashion/products/${product.id}/edit`"
                                            class="action-button edit"
                                            title="Edit Product"
                                        >

                                            <EditOutlined />

                                        </Link>


                                        <!-- DELETE -->

                                        <button
                                            type="button"
                                            class="action-button delete"
                                            title="Delete Product"
                                            @click="deleteProduct(product)"
                                        >

                                            <DeleteOutlined />

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>



                <!-- =================================================
                     EMPTY STATE
                ================================================== -->

                <div
                    v-else
                    class="empty-state"
                >

                    <div class="empty-icon">

                        <ShoppingOutlined />

                    </div>

                    <h2>
                        No Products Found
                    </h2>

                    <p v-if="search || selectedCategory || selectedStatus">

                        No products match your current filters.

                    </p>

                    <p v-else>

                        You haven't added any products yet.

                    </p>


                    <div class="empty-actions">

                        <button
                            v-if="search || selectedCategory || selectedStatus"
                            type="button"
                            class="btn-secondary"
                            @click="resetFilters"
                        >

                            <ReloadOutlined />

                            Clear Filters

                        </button>


                        <Link
                            href="/fashion/products/create"
                            class="btn-primary"
                        >

                            <PlusOutlined />

                            Add Product

                        </Link>

                    </div>

                </div>



                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div
                    v-if="
                        products.data?.length &&
                        products.last_page > 1
                    "
                    class="pagination"
                >

                    <div class="pagination-info">

                        Showing

                        <strong>
                            {{ products.from }}
                        </strong>

                        to

                        <strong>
                            {{ products.to }}
                        </strong>

                        of

                        <strong>
                            {{ products.total }}
                        </strong>

                        products

                    </div>


                    <div class="pagination-links">

                        <button
                            v-for="link in products.links"
                            :key="link.label"
                            type="button"
                            class="pagination-button"
                            :class="{
                                active: link.active,
                                disabled: !link.url
                            }"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                        >

                            <span
                                v-html="link.label"
                            ></span>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.products-page {
    padding: 10px;
    max-width: 1600px;
    margin: 0 auto;
}


/* =========================================================
   HEADER
========================================================= */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
}

.title-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.title-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #111827;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.page-header p {
    margin: 5px 0 0;
    color: #6b7280;
}


/* =========================================================
   BUTTONS
========================================================= */

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 18px;
    background: #111827;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: .2s;
}

.btn-primary:hover {
    background: #1f2937;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}


/* =========================================================
   SUMMARY
========================================================= */

.summary-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 18px;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .03);
}

.summary-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.summary-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #111827;
}

.summary-left span {
    display: block;
    color: #6b7280;
    font-size: 13px;
}

.summary-left strong {
    display: block;
    margin-top: 2px;
    font-size: 22px;
}

.summary-right {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #6b7280;
    font-size: 13px;
}

.summary-right strong {
    color: #111827;
}


/* =========================================================
   FILTERS
========================================================= */

.filter-card {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    background: white;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 18px;
    border: 1px solid #eef0f3;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 9px;
    flex: 1;
    min-width: 260px;
    height: 42px;
    border: 1px solid #dfe3e8;
    border-radius: 8px;
    padding: 0 12px;
    color: #9ca3af;
}

.search-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 14px;
    background: transparent;
}

.filter-control {
    display: flex;
    align-items: center;
    gap: 7px;
    height: 42px;
    border: 1px solid #dfe3e8;
    border-radius: 8px;
    padding: 0 10px;
    color: #6b7280;
}

.filter-control select {
    border: none;
    outline: none;
    background: white;
    min-width: 150px;
    color: #374151;
    cursor: pointer;
}

.reset-button {
    height: 42px;
    padding: 0 14px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    color: #374151;
    cursor: pointer;
}

.reset-button:hover {
    background: #f9fafb;
}


/* =========================================================
   CONTENT
========================================================= */

.content-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #eef0f3;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .03);
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}


/* =========================================================
   TABLE
========================================================= */

.products-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1050px;
}

.products-table th {
    background: #f9fafb;
    color: #6b7280;
    font-size: 12px;
    font-weight: 700;
    text-align: left;
    padding: 14px 16px;
    border-bottom: 1px solid #eef0f3;
    white-space: nowrap;
}

.products-table td {
    padding: 15px 16px;
    border-bottom: 1px solid #f0f2f4;
    vertical-align: middle;
}

.products-table tbody tr:hover {
    background: #fafafa;
}


/* =========================================================
   PRODUCT
========================================================= */

.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 220px;
}

.product-image {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    color: #9ca3af;
    font-size: 18px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details strong {
    display: block;
    color: #111827;
    font-size: 14px;
}

.product-details small {
    display: block;
    margin-top: 3px;
    color: #9ca3af;
    font-size: 11px;
}


/* =========================================================
   SKU
========================================================= */

.sku {
    padding: 5px 8px;
    background: #f3f4f6;
    border-radius: 6px;
    font-size: 12px;
    font-family: monospace;
    color: #374151;
}


/* =========================================================
   CATEGORY
========================================================= */

.category-name {
    color: #4b5563;
    font-size: 13px;
}


/* =========================================================
   PRICE
========================================================= */

.price-cell strong {
    display: block;
    color: #111827;
}

.price-cell small {
    display: block;
    color: #16a34a;
    margin-top: 3px;
    font-size: 11px;
}


/* =========================================================
   STOCK
========================================================= */

.stock-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
}

.stock-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
}

.stock-good {
    color: #15803d;
}

.stock-good .stock-dot {
    background: #22c55e;
}

.stock-low {
    color: #ca8a04;
}

.stock-low .stock-dot {
    background: #eab308;
}

.stock-out {
    color: #dc2626;
}

.stock-out .stock-dot {
    background: #ef4444;
}

.stock-cell small {
    color: #9ca3af;
    font-size: 11px;
}


/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

.status-active {
    background: #dcfce7;
    color: #15803d;
}

.status-inactive {
    background: #f3f4f6;
    color: #6b7280;
}


/* =========================================================
   FEATURED
========================================================= */

.featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #ca8a04;
    font-size: 12px;
    font-weight: 600;
}

.not-featured {
    color: #d1d5db;
}


/* =========================================================
   ACTIONS
========================================================= */

.actions-column {
    text-align: right !important;
}

.action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 6px;
}

.action-button {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    border: 1px solid #e5e7eb;
    background: white;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
}

.action-button.view {
    color: #2563eb;
}

.action-button.edit {
    color: #7c3aed;
}

.action-button.delete {
    color: #dc2626;
}

.action-button:hover {
    background: #f9fafb;
    transform: translateY(-1px);
}


/* =========================================================
   EMPTY
========================================================= */

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.empty-state h2 {
    margin: 0;
    color: #111827;
    font-size: 20px;
}

.empty-state p {
    color: #6b7280;
    margin: 8px 0 20px;
}

.empty-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}


/* =========================================================
   PAGINATION
========================================================= */

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 16px 18px;
    border-top: 1px solid #eef0f3;
}

.pagination-info {
    color: #6b7280;
    font-size: 13px;
}

.pagination-info strong {
    color: #111827;
}

.pagination-links {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.pagination-button {
    min-width: 34px;
    height: 34px;
    padding: 0 9px;
    border: 1px solid #e5e7eb;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    color: #374151;
}

.pagination-button:hover:not(.disabled) {
    background: #f3f4f6;
}

.pagination-button.active {
    background: #111827;
    color: white;
    border-color: #111827;
}

.pagination-button.disabled {
    opacity: .45;
    cursor: not-allowed;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .summary-card {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .pagination {
        align-items: flex-start;
        flex-direction: column;
    }

    .pagination-links {
        justify-content: flex-start;
    }

}


@media (max-width: 600px) {

    .products-page {
        padding: 5px;
    }

    .page-header h1 {
        font-size: 23px;
    }

    .filter-card {
        align-items: stretch;
        flex-direction: column;
    }

    .search-box {
        min-width: 0;
    }

    .filter-control {
        width: 100%;
    }

    .filter-control select {
        width: 100%;
    }

    .reset-button {
        justify-content: center;
    }

    .empty-actions {
        align-items: stretch;
        flex-direction: column;
    }

}

</style>