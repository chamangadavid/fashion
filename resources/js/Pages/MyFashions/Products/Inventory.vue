<script setup>

import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import {
    SearchOutlined,
    PlusOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    MoreOutlined,
    InboxOutlined,
    ExclamationCircleOutlined,
    CheckCircleOutlined,
    CloseOutlined,
    PlusCircleOutlined,
    MinusCircleOutlined,
    ReloadOutlined,
    HistoryOutlined,
} from '@ant-design/icons-vue';


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
        }),
    },

    categories: {
        type: Array,
        default: () => [],
    },

});


/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

const page = usePage();


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = ref('');


/*
|--------------------------------------------------------------------------
| CATEGORY FILTER
|--------------------------------------------------------------------------
*/

const categoryFilter = ref('');


/*
|--------------------------------------------------------------------------
| STATUS FILTER
|--------------------------------------------------------------------------
*/

const statusFilter = ref('');


/*
|--------------------------------------------------------------------------
| FEATURED FILTER
|--------------------------------------------------------------------------
*/

const featuredFilter = ref('');


/*
|--------------------------------------------------------------------------
| STOCK FILTER
|--------------------------------------------------------------------------
*/

const stockFilter = ref('');


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

const deletingProduct = ref(null);


/*
|--------------------------------------------------------------------------
| STOCK ADJUSTMENT MODAL
|--------------------------------------------------------------------------
*/

const showStockModal = ref(false);

const selectedProduct = ref(null);

const auditDetailsModal = ref(false);

const selectedAudit = ref(null);

const auditLoading = ref(false);

/*
|--------------------------------------------------------------------------
| STOCK FORM
|--------------------------------------------------------------------------
*/

const stockForm = ref({

    type: 'add',

    quantity: '',

    reason: 'New stock received',

    notes: '',

});


/*
|--------------------------------------------------------------------------
| STOCK REASONS
|--------------------------------------------------------------------------
*/

const stockReasons = [

    'New stock received',

    'Stock sold',

    'Damaged item',

    'Returned item',

    'Stock correction',

    'Other',

];


// const viewAuditDetails = async (audit) => {

//     auditLoading.value = true;

//     selectedAudit.value = null;

//     auditDetailsModal.value = true;

//     try {

//         const response = await axios.get(
//             `/fashion/products/inventory/audit/${audit.id}`
//         );

//         selectedAudit.value = response.data.audit;

//     } catch (error) {

//         console.error(
//             'Failed to load audit details:',
//             error
//         );

//         alert(
//             'Unable to load audit details.'
//         );

//         auditDetailsModal.value = false;

//     } finally {

//         auditLoading.value = false;

//     }
// };

const viewAuditDetails = async (product) => {
    if (!product?.id) {
        console.error('No product selected for audit details.');
        return;
    }

    auditLoading.value = true;
    selectedAudit.value = null;
    auditDetailsModal.value = true;

    try {
        const response = await axios.get(
            `/fashion/products/inventory/audit/${product.id}`
        );

        selectedAudit.value = response.data.audit;

    } catch (error) {
        console.error(
            'Failed to load audit details:',
            error
        );

        alert('Unable to load audit details.');

        auditDetailsModal.value = false;
    } finally {
        auditLoading.value = false;
    }
};

const closeAuditDetails = () => {

    auditDetailsModal.value = false;

    selectedAudit.value = null;

};

/*
|--------------------------------------------------------------------------
| OPEN STOCK MODAL
|--------------------------------------------------------------------------
*/

const openStockModal = (product) => {

    selectedProduct.value = product;

    stockForm.value = {

        type: 'add',

        quantity: '',

        reason: 'New stock received',

        notes: '',

    };

    showStockModal.value = true;

};


/*
|--------------------------------------------------------------------------
| CLOSE STOCK MODAL
|--------------------------------------------------------------------------
*/

const closeStockModal = () => {

    showStockModal.value = false;

    selectedProduct.value = null;

};


/*
|--------------------------------------------------------------------------
| CURRENT STOCK
|--------------------------------------------------------------------------
*/

const currentStock = computed(() => {

    return Number(
        selectedProduct.value?.stock_quantity ?? 0
    );

});


/*
|--------------------------------------------------------------------------
| NEW STOCK
|--------------------------------------------------------------------------
*/

const newStock = computed(() => {

    const quantity = Number(
        stockForm.value.quantity || 0
    );

    const current = currentStock.value;


    if (stockForm.value.type === 'add') {

        return current + quantity;

    }


    if (stockForm.value.type === 'remove') {

        return current - quantity;

    }


    if (stockForm.value.type === 'set') {

        return quantity;

    }


    return current;

});


/*
|--------------------------------------------------------------------------
| STOCK VALIDATION
|--------------------------------------------------------------------------
*/

const stockError = computed(() => {

    const quantity = Number(
        stockForm.value.quantity || 0
    );


    if (
        stockForm.value.quantity !== '' &&
        quantity < 0
    ) {

        return 'Quantity cannot be negative.';

    }


    if (
        stockForm.value.type === 'remove' &&
        quantity > currentStock.value
    ) {

        return `You cannot remove more than the current stock of ${currentStock.value}.`;

    }


    return '';

});


/*
|--------------------------------------------------------------------------
| SUBMIT STOCK ADJUSTMENT
|--------------------------------------------------------------------------
*/

const submitStockAdjustment = () => {

    if (!selectedProduct.value) {
        return;
    }


    const quantity = Number(
        stockForm.value.quantity
    );


    if (
        stockForm.value.quantity === '' ||
        !Number.isInteger(quantity) ||
        quantity < 0
    ) {

        alert('Please enter a valid quantity.');

        return;

    }


    if (stockError.value) {

        alert(stockError.value);

        return;

    }


    router.post(

        `/fashion/products/${selectedProduct.value.id}/stock-adjustment`,

        {

            type: stockForm.value.type,

            quantity: quantity,

            reason: stockForm.value.reason,

            notes: stockForm.value.notes,

        },

        {

            preserveScroll: true,

            onSuccess: () => {

                closeStockModal();

            },

            onError: (errors) => {

                console.error(
                    'Stock adjustment error:',
                    errors
                );

            },

        }

    );

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

    }).format(amount ?? 0);

};


/*
|--------------------------------------------------------------------------
| STOCK STATUS
|--------------------------------------------------------------------------
*/

const stockStatus = (product) => {

    const stock = Number(
        product.stock_quantity ?? 0
    );

    const threshold = Number(
        product.low_stock_threshold ?? 5
    );


    if (stock <= 0) {

        return {
            label: 'Out of Stock',
            class: 'out-of-stock',
        };

    }


    if (stock <= threshold) {

        return {
            label: 'Low Stock',
            class: 'low-stock',
        };

    }


    return {
        label: 'In Stock',
        class: 'in-stock',
    };

};


/*
|--------------------------------------------------------------------------
| FILTERED PRODUCTS
|--------------------------------------------------------------------------
*/

const filteredProducts = computed(() => {

    let products = props.products?.data ?? [];


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if (search.value.trim()) {

        const term = search.value
            .toLowerCase()
            .trim();


        products = products.filter(product => {

            return (

                String(product.name ?? '')
                    .toLowerCase()
                    .includes(term)

                ||

                String(product.sku ?? '')
                    .toLowerCase()
                    .includes(term)

                ||

                String(product.category?.name ?? '')
                    .toLowerCase()
                    .includes(term)

            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    if (categoryFilter.value) {

        products = products.filter(product => {

            return String(
                product.product_category_id
            ) === String(
                categoryFilter.value
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE
    |--------------------------------------------------------------------------
    */

    if (statusFilter.value === 'active') {

        products = products.filter(
            product => product.is_active
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INACTIVE
    |--------------------------------------------------------------------------
    */

    if (statusFilter.value === 'inactive') {

        products = products.filter(
            product => !product.is_active
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FEATURED
    |--------------------------------------------------------------------------
    */

    if (featuredFilter.value === 'featured') {

        products = products.filter(
            product => product.is_featured
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NOT FEATURED
    |--------------------------------------------------------------------------
    */

    if (featuredFilter.value === 'not_featured') {

        products = products.filter(
            product => !product.is_featured
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STOCK
    |--------------------------------------------------------------------------
    */

    if (stockFilter.value === 'in_stock') {

        products = products.filter(
            product =>
                Number(product.stock_quantity) > 0
        );

    }


    if (stockFilter.value === 'low_stock') {

        products = products.filter(product => {

            const stock =
                Number(product.stock_quantity ?? 0);

            const threshold =
                Number(product.low_stock_threshold ?? 5);

            return stock > 0 && stock <= threshold;

        });

    }


    if (stockFilter.value === 'out_of_stock') {

        products = products.filter(product => {

            return Number(
                product.stock_quantity ?? 0
            ) <= 0;

        });

    }


    return products;

});


/*
|--------------------------------------------------------------------------
| CLEAR FILTERS
|--------------------------------------------------------------------------
*/

const clearFilters = () => {

    search.value = '';

    categoryFilter.value = '';

    statusFilter.value = '';

    featuredFilter.value = '';

    stockFilter.value = '';

};


/*
|--------------------------------------------------------------------------
| DELETE PRODUCT
|--------------------------------------------------------------------------
*/

const deleteProduct = (product) => {

    if (
        !confirm(
            `Are you sure you want to delete "${product.name}"?`
        )
    ) {

        return;

    }


    deletingProduct.value = product.id;


    router.delete(

        `/fashion/products/${product.id}`,

        {

            preserveScroll: true,

            onFinish: () => {

                deletingProduct.value = null;

            },

        }

    );

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
            preserveScroll: true,
            preserveState: true,
        }

    );

};

</script>


<template>

    <MyFashionLayout>

        <div class="inventory-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Inventory
                    </h1>

                    <p>
                        Manage product stock and inventory levels.
                    </p>

                </div>


                <Link
                    href="/fashion/products/create"
                    class="primary-button"
                >

                    <PlusOutlined />

                    Add Product

                </Link>

            </div>


            <!-- =====================================================
                 FILTERS
            ====================================================== -->

            <div class="filter-card">


                <!-- SEARCH -->

                <div class="search-box">

                    <SearchOutlined />

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search product or SKU..."
                    />

                </div>


                <!-- CATEGORY -->

                <select
                    v-model="categoryFilter"
                    class="filter-select"
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


                <!-- STATUS -->

                <select
                    v-model="statusFilter"
                    class="filter-select"
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


                <!-- FEATURED -->

                <select
                    v-model="featuredFilter"
                    class="filter-select"
                >

                    <option value="">
                        All Products
                    </option>

                    <option value="featured">
                        Featured
                    </option>

                    <option value="not_featured">
                        Not Featured
                    </option>

                </select>


                <!-- STOCK -->

                <select
                    v-model="stockFilter"
                    class="filter-select"
                >

                    <option value="">
                        All Stock
                    </option>

                    <option value="in_stock">
                        In Stock
                    </option>

                    <option value="low_stock">
                        Low Stock
                    </option>

                    <option value="out_of_stock">
                        Out of Stock
                    </option>

                </select>


                <button
                    type="button"
                    class="clear-button"
                    @click="clearFilters"
                >

                    <ReloadOutlined />

                    Clear

                </button>

            </div>


            <!-- =====================================================
                 INVENTORY TABLE
            ====================================================== -->

            <div class="content-card">

                <div class="table-header">

                    <div>

                        <h2>
                            Product Inventory
                        </h2>

                        <p>
                            {{ filteredProducts.length }}
                            products shown
                        </p>

                    </div>

                    <div class="total-products">

                        Total:
                        <strong>
                            {{ products.total ?? 0 }}
                        </strong>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table>

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

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="product in filteredProducts"
                                :key="product.id"
                            >


                                <!-- PRODUCT -->

                                <td>

                                    <div class="product-cell">


                                        <div class="product-image">

                                            <img
                                                v-if="product.image"
                                                :src="`/storage/${product.image}`"
                                                :alt="product.name"
                                            />

                                            <InboxOutlined
                                                v-else
                                            />

                                        </div>


                                        <div>

                                            <strong>
                                                {{ product.name }}
                                            </strong>

                                            <small>
                                                {{ product.sku }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- SKU -->

                                <td>
                                    {{ product.sku }}
                                </td>


                                <!-- CATEGORY -->

                                <td>

                                    {{
                                        product.category?.name
                                        ?? 'Uncategorized'
                                    }}

                                </td>


                                <!-- PRICE -->

                                <td>

                                    {{ formatMoney(product.price) }}

                                </td>


                                <!-- STOCK -->

                                <td>

                                    <div class="stock-cell">

                                        <strong>
                                            {{ product.stock_quantity }}
                                        </strong>

                                        <span
                                            class="stock-badge"
                                            :class="
                                                stockStatus(product).class
                                            "
                                        >

                                            <CheckCircleOutlined
                                                v-if="
                                                    stockStatus(product).class ===
                                                    'in-stock'
                                                "
                                            />

                                            <ExclamationCircleOutlined
                                                v-else
                                            />

                                            {{
                                                stockStatus(product).label
                                            }}

                                        </span>

                                    </div>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="status-badge"
                                        :class="
                                            product.is_active
                                                ? 'active'
                                                : 'inactive'
                                        "
                                    >

                                        {{
                                            product.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}

                                    </span>

                                </td>


                                <!-- FEATURED -->

                                <td>

                                    <span
                                        class="featured-badge"
                                        :class="{
                                            featured:
                                                product.is_featured
                                        }"
                                    >

                                        {{
                                            product.is_featured
                                                ? 'Featured'
                                                : 'No'
                                        }}

                                    </span>

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="actions">


                                        <!-- VIEW -->

                                        <Link
                                            :href="`/fashion/products/${product.id}`"
                                            class="action-button view"
                                            title="View"
                                        >

                                            <EyeOutlined />

                                        </Link>


                                        <!-- EDIT -->

                                        <Link
                                            :href="`/fashion/products/${product.id}/edit`"
                                            class="action-button edit"
                                            title="Edit"
                                        >

                                            <EditOutlined />

                                        </Link>


                                        <!-- ADJUST STOCK -->

                                        <button
                                            type="button"
                                            class="action-button stock"
                                            title="Adjust Stock"
                                            @click="
                                                openStockModal(product)
                                            "
                                        >

                                            <PlusCircleOutlined />

                                        </button>

                                        <!-- <button
    type="button"
    class="action-button view"

    @click="viewAuditDetails(product)"
>
    <HistoryOutlined />
</button> -->


                                        <!-- DELETE -->

                                        <button
                                            type="button"
                                            class="action-button delete"
                                            title="Delete"
                                            :disabled="
                                                deletingProduct ===
                                                product.id
                                            "
                                            @click="
                                                deleteProduct(product)
                                            "
                                        >

                                            <DeleteOutlined />

                                        </button>

                                    </div>

                                </td>

                            </tr>


                            <!-- EMPTY -->

                            <tr
                                v-if="
                                    filteredProducts.length === 0
                                "
                            >

                                <td
                                    colspan="8"
                                    class="empty-cell"
                                >

                                    <InboxOutlined />

                                    <h3>
                                        No products found
                                    </h3>

                                    <p>
                                        Try changing your filters or
                                        add a new product.
                                    </p>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div
                    v-if="
                        products.links &&
                        products.links.length > 3
                    "
                    class="pagination"
                >

                    <button
                        v-for="(link, index) in products.links"
                        :key="index"
                        :disabled="!link.url"
                        :class="{
                            active: link.active
                        }"
                        @click="goToPage(link.url)"
                    >

                        <span
                            v-html="link.label"
                        ></span>

                    </button>

                </div>

            </div>

        </div>


        <!-- =========================================================
             STOCK ADJUSTMENT MODAL
        ========================================================== -->

        <div
            v-if="showStockModal"
            class="modal-overlay"
            @click.self="closeStockModal"
        >

            <div class="stock-modal">


                <!-- MODAL HEADER -->

                <div class="modal-header">

                    <div>

                        <h2>
                            Adjust Stock
                        </h2>

                        <p
                            v-if="selectedProduct"
                        >

                            {{ selectedProduct.name }}

                        </p>

                    </div>


                    <button
                        type="button"
                        class="close-button"
                        @click="closeStockModal"
                    >

                        <CloseOutlined />

                    </button>

                </div>


                <!-- MODAL BODY -->

                <div class="modal-body">


                    <!-- PRODUCT INFO -->

                    <div class="product-summary">

                        <div class="modal-product-image">

                            <img
                                v-if="
                                    selectedProduct?.image
                                "
                                :src="
                                    `/storage/${selectedProduct.image}`
                                "
                                :alt="
                                    selectedProduct?.name
                                "
                            />

                            <InboxOutlined
                                v-else
                            />

                        </div>


                        <div>

                            <strong>
                                {{ selectedProduct?.name }}
                            </strong>

                            <span>
                                SKU:
                                {{ selectedProduct?.sku }}
                            </span>

                        </div>

                    </div>


                    <!-- CURRENT STOCK -->

                    <div class="current-stock-box">

                        <span>
                            Current Stock
                        </span>

                        <strong>
                            {{ currentStock }}
                        </strong>

                    </div>


                    <!-- ADJUSTMENT TYPE -->

                    <div class="form-group">

                        <label>
                            Adjustment Type
                        </label>

                        <select
                            v-model="stockForm.type"
                            class="form-control"
                        >

                            <option value="add">
                                Add Stock
                            </option>

                            <option value="remove">
                                Remove Stock
                            </option>

                            <option value="set">
                                Set Stock
                            </option>

                        </select>

                    </div>


                    <!-- QUANTITY -->

                    <div class="form-group">

                        <label>
                            Quantity
                        </label>

                        <input
                            v-model="stockForm.quantity"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control"
                            placeholder="Enter quantity"
                        />

                        <small
                            v-if="stockError"
                            class="form-error"
                        >

                            {{ stockError }}

                        </small>

                    </div>


                    <!-- REASON -->

                    <div class="form-group">

                        <label>
                            Reason
                        </label>

                        <select
                            v-model="stockForm.reason"
                            class="form-control"
                        >

                            <option
                                v-for="reason in stockReasons"
                                :key="reason"
                                :value="reason"
                            >

                                {{ reason }}

                            </option>

                        </select>

                    </div>


                    <!-- NOTES -->

                    <div class="form-group">

                        <label>
                            Notes
                        </label>

                        <textarea
                            v-model="stockForm.notes"
                            rows="3"
                            class="form-control"
                            placeholder="Optional notes..."
                        ></textarea>

                    </div>


                    <!-- NEW STOCK -->

                    <div
                        class="new-stock-box"
                        :class="{
                            danger: newStock < 0
                        }"
                    >

                        <div>

                            <span>
                                New Stock
                            </span>

                            <small>

                                {{
                                    stockForm.type === 'add'
                                        ? 'Current + Quantity'
                                        : stockForm.type === 'remove'
                                            ? 'Current - Quantity'
                                            : 'Set to Quantity'
                                }}

                            </small>

                        </div>


                        <strong>
                            {{ newStock }}
                        </strong>

                    </div>

                </div>

                <!-- Inventory Audit Details Modal -->
                 <!-- =========================================================
     AUDIT DETAILS MODAL
========================================================= -->

<div
    v-if="auditDetailsModal"
    class="modal-overlay"
    @click.self="closeAuditDetails"
>

    <div class="audit-details-modal">

        <!-- HEADER -->

        <div class="modal-header">

            <div>

                <h2>
                    Inventory Audit Details
                </h2>

                <p>
                    Complete history for this stock adjustment
                </p>

            </div>

            <button
                type="button"
                class="modal-close"
                @click="closeAuditDetails"
            >
                ×
            </button>

        </div>


        <!-- LOADING -->

        <div
            v-if="auditLoading"
            class="audit-loading"
        >

            <div class="loading-spinner"></div>

            <p>
                Loading audit details...
            </p>

        </div>


        <!-- CONTENT -->

        <div
            v-else-if="selectedAudit"
            class="audit-details-content"
        >

            <!-- PRODUCT -->

            <div class="audit-section">

                <h3>
                    Product
                </h3>

                <div class="product-details">

                    <div class="product-image">

                        <img
                            v-if="selectedAudit.product?.image"
                            :src="`/storage/${selectedAudit.product.image}`"
                            :alt="selectedAudit.product.name"
                        />

                        <div
                            v-else
                            class="no-image"
                        >
                            No Image
                        </div>

                    </div>

                    <div class="product-information">

                        <strong>
                            {{ selectedAudit.product?.name }}
                        </strong>

                        <span>
                            SKU:
                            {{ selectedAudit.product?.sku }}
                        </span>

                    </div>

                </div>

            </div>


            <!-- STOCK MOVEMENT -->

            <div class="audit-section">

                <h3>
                    Stock Movement
                </h3>

                <div class="stock-history">

                    <div class="stock-box">

                        <span>
                            Stock Before
                        </span>

                        <strong>
                            {{ selectedAudit.quantity_before }}
                        </strong>

                    </div>


                    <div class="movement-arrow">

                        <span
                            :class="{
                                increase:
                                    Number(selectedAudit.quantity_change) > 0,

                                decrease:
                                    Number(selectedAudit.quantity_change) < 0
                            }"
                        >

                            {{
                                Number(selectedAudit.quantity_change) > 0
                                    ? '+'
                                    : ''
                            }}

                            {{ selectedAudit.quantity_change }}

                        </span>

                    </div>


                    <div class="stock-box">

                        <span>
                            Stock After
                        </span>

                        <strong>
                            {{ selectedAudit.quantity_after }}
                        </strong>

                    </div>

                </div>

            </div>


            <!-- AUDIT INFORMATION -->

            <div class="audit-section">

                <h3>
                    Adjustment Information
                </h3>

                <div class="audit-information-grid">

                    <div>

                        <span>
                            Adjustment Type
                        </span>

                        <strong>
                            {{ selectedAudit.type }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Quantity Changed
                        </span>

                        <strong
                            :class="{
                                positive:
                                    Number(selectedAudit.quantity_change) > 0,

                                negative:
                                    Number(selectedAudit.quantity_change) < 0
                            }"
                        >

                            {{
                                Number(selectedAudit.quantity_change) > 0
                                    ? '+'
                                    : ''
                            }}

                            {{ selectedAudit.quantity_change }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Reason
                        </span>

                        <strong>
                            {{ selectedAudit.reason || 'N/A' }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Date
                        </span>

                        <strong>

                            {{
                                new Date(
                                    selectedAudit.created_at
                                ).toLocaleString()
                            }}

                        </strong>

                    </div>

                </div>

            </div>


            <!-- NOTES -->

            <div
                v-if="selectedAudit.notes"
                class="audit-section"
            >

                <h3>
                    Notes
                </h3>

                <div class="audit-notes">

                    {{ selectedAudit.notes }}

                </div>

            </div>


            <!-- USER -->

            <div class="audit-section">

                <h3>
                    Performed By
                </h3>

                <div class="performed-by">

                    <div class="user-avatar">

                        <UserOutlined />

                    </div>

                    <div>

                        <strong>
                            {{ selectedAudit.user?.name }}
                        </strong>

                        <span>
                            {{ selectedAudit.user?.email }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->

        <div class="modal-footer">

            <button
                type="button"
                class="close-button"
                @click="closeAuditDetails"
            >
                Close
            </button>

        </div>

    </div>

</div>


                <!-- MODAL FOOTER -->

                <div class="modal-footer">

                    <button
                        type="button"
                        class="cancel-button"
                        @click="closeStockModal"
                    >

                        Cancel

                    </button>


                    <button
                        type="button"
                        class="submit-button"
                        :disabled="
                            !stockForm.quantity ||
                            !!stockError
                        "
                        @click="submitStockAdjustment"
                    >

                        <PlusCircleOutlined />

                        Adjust Stock

                    </button>

                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.inventory-page {
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
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    margin-top: 5px;
    color: #777;
}

.primary-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #111827;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}


/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

.filter-card {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    background: white;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0 12px;
    height: 42px;
    min-width: 240px;
}

.search-box input {
    border: none;
    outline: none;
    width: 100%;
}

.filter-select {
    height: 42px;
    padding: 0 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
}

.clear-button {
    height: 42px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0 14px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
    cursor: pointer;
}


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    overflow: hidden;
}

.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px;
}

.table-header h2 {
    margin: 0;
    font-size: 18px;
}

.table-header p {
    margin: 4px 0 0;
    color: #777;
    font-size: 13px;
}

.total-products {
    color: #777;
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
    border-collapse: collapse;
}

th {
    text-align: left;
    background: #f9fafb;
    padding: 14px 18px;
    font-size: 12px;
    text-transform: uppercase;
    color: #6b7280;
}

td {
    padding: 16px 18px;
    border-top: 1px solid #f0f0f0;
    font-size: 14px;
}


/*
|--------------------------------------------------------------------------
| PRODUCT
|--------------------------------------------------------------------------
*/

.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 220px;
}

.product-cell strong {
    display: block;
}

.product-cell small {
    display: block;
    margin-top: 3px;
    color: #888;
}

.product-image {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


/*
|--------------------------------------------------------------------------
| STOCK
|--------------------------------------------------------------------------
*/

.stock-cell {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.stock-badge,
.status-badge,
.featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    width: fit-content;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.in-stock {
    background: #ecfdf5;
    color: #047857;
}

.low-stock {
    background: #fffbeb;
    color: #b45309;
}

.out-of-stock {
    background: #fef2f2;
    color: #b91c1c;
}

.status-badge.active {
    background: #ecfdf5;
    color: #047857;
}

.status-badge.inactive {
    background: #f3f4f6;
    color: #6b7280;
}

.featured-badge.featured {
    background: #fff7ed;
    color: #c2410c;
}


/* Audit  Details*/
/* =========================================================
   AUDIT DETAILS MODAL
========================================================= */

.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(0, 0, 0, 0.55);
}

.audit-details-modal {
    width: 100%;
    max-width: 750px;
    max-height: 90vh;

    overflow-y: auto;

    background: #ffffff;
    border-radius: 16px;

    box-shadow:
        0 20px 50px rgba(0, 0, 0, 0.20);
}

/* HEADER */

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    padding: 24px;

    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    margin: 0;

    font-size: 21px;
    font-weight: 700;

    color: #111827;
}

.modal-header p {
    margin: 5px 0 0;

    color: #6b7280;

    font-size: 14px;
}

.modal-close {
    width: 34px;
    height: 34px;

    border: none;
    border-radius: 8px;

    background: #f3f4f6;

    color: #374151;

    font-size: 24px;

    cursor: pointer;
}

.modal-close:hover {
    background: #e5e7eb;
}


/* CONTENT */

.audit-details-content {
    padding: 24px;
}

.audit-section {
    margin-bottom: 25px;
}

.audit-section:last-child {
    margin-bottom: 0;
}

.audit-section h3 {
    margin: 0 0 14px;

    font-size: 15px;
    font-weight: 700;

    color: #111827;
}


/* PRODUCT */

.product-details {
    display: flex;
    align-items: center;

    gap: 15px;

    padding: 15px;

    border: 1px solid #eee;
    border-radius: 12px;
}

.product-image {
    width: 70px;
    height: 70px;

    overflow: hidden;

    border-radius: 10px;

    background: #f3f4f6;
}

.product-image img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}

.no-image {
    display: flex;

    width: 100%;
    height: 100%;

    align-items: center;
    justify-content: center;

    color: #9ca3af;

    font-size: 11px;
}

.product-information {
    display: flex;
    flex-direction: column;

    gap: 5px;
}

.product-information strong {
    color: #111827;
}

.product-information span {
    color: #6b7280;

    font-size: 13px;
}


/* STOCK MOVEMENT */

.stock-history {
    display: flex;
    align-items: center;

    gap: 20px;

    padding: 20px;

    border-radius: 12px;

    background: #f9fafb;
}

.stock-box {
    flex: 1;

    display: flex;
    flex-direction: column;

    gap: 5px;

    text-align: center;
}

.stock-box span {
    color: #6b7280;

    font-size: 13px;
}

.stock-box strong {
    font-size: 28px;

    color: #111827;
}

.movement-arrow {
    display: flex;

    align-items: center;
    justify-content: center;

    min-width: 70px;
}

.movement-arrow span {
    font-size: 18px;
    font-weight: 700;
}

.movement-arrow .increase {
    color: #16a34a;
}

.movement-arrow .decrease {
    color: #dc2626;
}


/* INFORMATION GRID */

.audit-information-grid {
    display: grid;

    grid-template-columns: repeat(2, 1fr);

    gap: 15px;
}

.audit-information-grid > div {
    display: flex;
    flex-direction: column;

    gap: 5px;

    padding: 15px;

    border: 1px solid #eee;

    border-radius: 10px;
}

.audit-information-grid span {
    color: #6b7280;

    font-size: 12px;
}

.audit-information-grid strong {
    color: #111827;

    font-size: 14px;
}

.audit-information-grid strong.positive {
    color: #16a34a;
}

.audit-information-grid strong.negative {
    color: #dc2626;
}


/* NOTES */

.audit-notes {
    padding: 15px;

    border-radius: 10px;

    background: #f9fafb;

    color: #4b5563;

    line-height: 1.6;
}


/* USER */

.performed-by {
    display: flex;
    align-items: center;

    gap: 12px;

    padding: 15px;

    border: 1px solid #eee;

    border-radius: 10px;
}

.user-avatar {
    width: 42px;
    height: 42px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #111827;

    color: white;
}

.performed-by div:last-child {
    display: flex;
    flex-direction: column;

    gap: 3px;
}

.performed-by strong {
    color: #111827;
}

.performed-by span {
    color: #6b7280;

    font-size: 13px;
}


/* FOOTER */

.modal-footer {
    display: flex;
    justify-content: flex-end;

    padding: 18px 24px;

    border-top: 1px solid #eee;
}

.close-button {
    padding: 9px 18px;

    border: none;

    border-radius: 8px;

    background: #111827;

    color: white;

    cursor: pointer;
}

.close-button:hover {
    background: #1f2937;
}


/* LOADING */

.audit-loading {
    display: flex;

    flex-direction: column;

    align-items: center;
    justify-content: center;

    padding: 70px 20px;
}

.loading-spinner {
    width: 35px;
    height: 35px;

    border: 3px solid #e5e7eb;
    border-top-color: #111827;

    border-radius: 50%;

    animation: spin 0.8s linear infinite;
}

.audit-loading p {
    margin-top: 12px;

    color: #6b7280;
}

@keyframes spin {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}


/* MOBILE */

@media (max-width: 640px) {

    .audit-details-modal {
        max-height: 95vh;

        border-radius: 12px;
    }

    .stock-history {
        flex-direction: column;
    }

    .stock-box {
        width: 100%;
    }

    .movement-arrow {
        transform: rotate(90deg);
    }

    .audit-information-grid {
        grid-template-columns: 1fr;
    }

}


/*
|--------------------------------------------------------------------------
| ACTIONS
|--------------------------------------------------------------------------
*/

.actions {
    display: flex;
    gap: 6px;
}

.action-button {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    text-decoration: none;
}

.action-button.view {
    background: #eff6ff;
    color: #2563eb;
}

.action-button.edit {
    background: #f5f3ff;
    color: #7c3aed;
}

.action-button.stock {
    background: #ecfdf5;
    color: #059669;
}

.action-button.delete {
    background: #fef2f2;
    color: #dc2626;
}

.action-button:disabled {
    opacity: .5;
    cursor: not-allowed;
}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-cell {
    text-align: center;
    padding: 70px 20px !important;
    color: #888;
}

.empty-cell .anticon {
    font-size: 40px;
}

.empty-cell h3 {
    margin: 12px 0 5px;
    color: #333;
}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
    padding: 20px;
}

.pagination button {
    min-width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
}

.pagination button.active {
    background: #111827;
    color: white;
    border-color: #111827;
}

.pagination button:disabled {
    opacity: .4;
    cursor: not-allowed;
}


/*
|--------------------------------------------------------------------------
| MODAL
|--------------------------------------------------------------------------
*/

.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(0,0,0,.55);
}

.stock-modal {
    width: 100%;
    max-width: 560px;
    max-height: 90vh;
    overflow-y: auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,.25);
}


/*
|--------------------------------------------------------------------------
| MODAL HEADER
|--------------------------------------------------------------------------
*/

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 22px 24px;
    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    margin: 0;
    font-size: 21px;
}

.modal-header p {
    margin: 5px 0 0;
    color: #777;
}

.close-button {
    border: none;
    background: #f3f4f6;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    cursor: pointer;
}


/*
|--------------------------------------------------------------------------
| MODAL BODY
|--------------------------------------------------------------------------
*/

.modal-body {
    padding: 24px;
}

.product-summary {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.modal-product-image {
    width: 58px;
    height: 58px;
    border-radius: 9px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-summary strong {
    display: block;
}

.product-summary span {
    display: block;
    color: #888;
    font-size: 13px;
    margin-top: 4px;
}


/*
|--------------------------------------------------------------------------
| CURRENT STOCK
|--------------------------------------------------------------------------
*/

.current-stock-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    margin-bottom: 20px;
    background: #f9fafb;
    border-radius: 10px;
}

.current-stock-box span {
    color: #666;
}

.current-stock-box strong {
    font-size: 25px;
}


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.form-control {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 12px;
    outline: none;
    background: white;
}

.form-control:focus {
    border-color: #111827;
}

.form-error {
    display: block;
    margin-top: 6px;
    color: #dc2626;
    font-size: 12px;
}


/*
|--------------------------------------------------------------------------
| NEW STOCK
|--------------------------------------------------------------------------
*/

.new-stock-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    margin-top: 20px;
    background: #ecfdf5;
    color: #047857;
    border-radius: 10px;
}

.new-stock-box div {
    display: flex;
    flex-direction: column;
}

.new-stock-box small {
    margin-top: 3px;
    opacity: .75;
}

.new-stock-box strong {
    font-size: 26px;
}

.new-stock-box.danger {
    background: #fef2f2;
    color: #b91c1c;
}


/*
|--------------------------------------------------------------------------
| MODAL FOOTER
|--------------------------------------------------------------------------
*/

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 18px 24px;
    border-top: 1px solid #eee;
}

.cancel-button,
.submit-button {
    height: 42px;
    padding: 0 18px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.cancel-button {
    border: 1px solid #ddd;
    background: white;
}

.submit-button {
    border: none;
    background: #111827;
    color: white;
}

.submit-button:disabled {
    opacity: .45;
    cursor: not-allowed;
}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 768px) {

    .page-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .filter-card {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        min-width: auto;
    }

    .filter-select,
    .clear-button {
        width: 100%;
    }

    .stock-modal {
        max-height: 95vh;
    }

}

</style>