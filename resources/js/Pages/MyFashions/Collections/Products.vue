<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import {
    computed,
    ref,
} from 'vue';

import {
    Head,
    Link,
    router,
} from '@inertiajs/vue3';


// =====================================================
// PROPS
// =====================================================

const props = defineProps({

    collection: {
        type: Object,
        required: true,
    },

    products: {
        type: Array,
        default: () => [],
    },

    assignedProductIds: {
        type: Array,
        default: () => [],
    },

    categories: {
        type: Array,
        default: () => [],
    },

});


// =====================================================
// STATE
// =====================================================

const search = ref('');

const selectedCategory = ref('');

const selectedProducts = ref(
    [...props.assignedProductIds]
);

const saving = ref(false);


// =====================================================
// FILTER PRODUCTS
// =====================================================

const filteredProducts = computed(() => {

    let results = props.products;


    // SEARCH
    if (search.value.trim()) {

        const keyword =
            search.value
                .toLowerCase()
                .trim();

        results = results.filter(product => {

            return (

                product.name
                    ?.toLowerCase()
                    .includes(keyword)

                ||

                product.sku
                    ?.toLowerCase()
                    .includes(keyword)

            );

        });

    }


    // CATEGORY
    if (selectedCategory.value) {

        results = results.filter(product => {

            return String(
                product.product_category_id
            ) === String(
                selectedCategory.value
            );

        });

    }


    return results;

});


// =====================================================
// SELECTED COUNT
// =====================================================

const selectedCount = computed(() => {

    return selectedProducts.value.length;

});


// =====================================================
// VISIBLE SELECTED COUNT
// =====================================================

const visibleSelectedCount = computed(() => {

    return filteredProducts.value.filter(
        product =>
            selectedProducts.value.includes(product.id)
    ).length;

});


// =====================================================
// ALL VISIBLE SELECTED
// =====================================================

const allVisibleSelected = computed(() => {

    if (!filteredProducts.value.length) {
        return false;
    }

    return filteredProducts.value.every(
        product =>
            selectedProducts.value.includes(product.id)
    );

});


// =====================================================
// TOGGLE PRODUCT
// =====================================================

const toggleProduct = (productId) => {

    const index =
        selectedProducts.value.indexOf(productId);


    if (index === -1) {

        selectedProducts.value.push(productId);

    } else {

        selectedProducts.value.splice(
            index,
            1
        );

    }

};


// =====================================================
// TOGGLE ALL VISIBLE
// =====================================================

const toggleAllVisible = () => {

    if (allVisibleSelected.value) {

        selectedProducts.value =
            selectedProducts.value.filter(
                id =>
                    !filteredProducts.value.some(
                        product =>
                            product.id === id
                    )
            );

    } else {

        const ids =
            filteredProducts.value.map(
                product => product.id
            );

        selectedProducts.value = [
            ...new Set([
                ...selectedProducts.value,
                ...ids,
            ]),
        ];

    }

};


// =====================================================
// REMOVE SELECTED
// =====================================================

const removeProduct = (productId) => {

    selectedProducts.value =
        selectedProducts.value.filter(
            id => id !== productId
        );

};


// =====================================================
// SAVE
// =====================================================

const saveProducts = () => {

    saving.value = true;


    router.post(
        `/fashion/collections/${props.collection.id}/products`,
        {
            product_ids:
                selectedProducts.value,
        },
        {
            preserveScroll: true,

            onFinish: () => {

                saving.value = false;

            },
        }
    );

};


// =====================================================
// IMAGE URL
// =====================================================

const imageUrl = (image) => {

    if (!image) {
        return '/images/product-placeholder.jpg';
    }

    if (
        image.startsWith('http://') ||
        image.startsWith('https://')
    ) {

        return image;

    }

    return `/storage/${image}`;

};

</script>


<template>

    <Head
        :title="`Manage Products - ${collection.name}`"
    />


    <MyFashionLayout>

        <div class="products-page">


            <!-- =================================================
                 HEADER
            ================================================== -->

            <div class="page-header">

                <div>

                    <div class="breadcrumb">

                        <Link
                            href="/fashion/collections"
                        >
                            Collections
                        </Link>

                        <span>/</span>

                        <Link
                            :href="`/fashion/collections/${collection.id}`"
                        >
                            {{ collection.name }}
                        </Link>

                        <span>/</span>

                        <span>
                            Products
                        </span>

                    </div>


                    <h1>
                        Manage Products
                    </h1>

                    <p>
                        Select the products that belong
                        to this collection.
                    </p>

                </div>


                <div class="header-actions">

                    <Link
                        :href="`/fashion/collections/${collection.id}`"
                        class="back-button"
                    >
                        Back to Collection
                    </Link>

                    <button
                        type="button"
                        class="save-button"
                        :disabled="saving"
                        @click="saveProducts"
                    >

                        <span v-if="saving">
                            Saving...
                        </span>

                        <span v-else>
                            Save Products
                        </span>

                    </button>

                </div>

            </div>


            <!-- =================================================
                 COLLECTION SUMMARY
            ================================================== -->

            <div class="collection-summary">

                <div class="summary-image">

                    <img
                        :src="imageUrl(collection.image)"
                        :alt="collection.name"
                    />

                </div>


                <div class="summary-content">

                    <span>
                        COLLECTION
                    </span>

                    <h2>
                        {{ collection.name }}
                    </h2>

                    <p v-if="collection.description">
                        {{ collection.description }}
                    </p>

                </div>


                <div class="summary-count">

                    <strong>
                        {{ selectedCount }}
                    </strong>

                    <span>
                        Products Assigned
                    </span>

                </div>

            </div>


            <!-- =================================================
                 TOOLBAR
            ================================================== -->

            <div class="toolbar">

                <div class="search-wrapper">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search products by name or SKU..."
                    />

                </div>


                <select
                    v-model="selectedCategory"
                    class="category-select"
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


                <button
                    type="button"
                    class="select-all-button"
                    @click="toggleAllVisible"
                >

                    {{
                        allVisibleSelected
                            ? 'Unselect Visible'
                            : 'Select Visible'
                    }}

                </button>

            </div>


            <!-- =================================================
                 RESULT INFO
            ================================================== -->

            <div class="result-bar">

                <span>

                    Showing
                    <strong>
                        {{ filteredProducts.length }}
                    </strong>
                    products

                </span>


                <span>

                    <strong>
                        {{ visibleSelectedCount }}
                    </strong>

                    visible selected

                </span>

            </div>


            <!-- =================================================
                 PRODUCTS
            ================================================== -->

            <div
                v-if="filteredProducts.length"
                class="products-grid"
            >

                <div
                    v-for="product in filteredProducts"
                    :key="product.id"
                    class="product-card"
                    :class="{
                        selected:
                            selectedProducts.includes(
                                product.id
                            )
                    }"
                    @click="toggleProduct(product.id)"
                >


                    <!-- CHECKBOX -->

                    <div class="checkbox-wrapper">

                        <input
                            type="checkbox"
                            :checked="
                                selectedProducts.includes(
                                    product.id
                                )
                            "
                            @click.stop
                            @change="
                                toggleProduct(product.id)
                            "
                        />

                    </div>


                    <!-- IMAGE -->

                    <div class="product-image">

                        <img
                            :src="imageUrl(product.image)"
                            :alt="product.name"
                        />


                        <div
                            v-if="
                                selectedProducts.includes(
                                    product.id
                                )
                            "
                            class="selected-badge"
                        >
                            ✓ Selected
                        </div>

                    </div>


                    <!-- INFORMATION -->

                    <div class="product-info">

                        <div class="product-category">

                            {{
                                product.category?.name
                                || 'Uncategorized'
                            }}

                        </div>

                        <h3>
                            {{ product.name }}
                        </h3>

                        <p class="sku">
                            SKU: {{ product.sku }}
                        </p>


                        <div class="product-bottom">

                            <strong>
                                {{ product.price }}
                            </strong>

                            <span
                                :class="{
                                    'stock-good':
                                        product.stock_quantity > 0,

                                    'stock-empty':
                                        product.stock_quantity <= 0
                                }"
                            >

                                {{
                                    product.stock_quantity
                                }}
                                in stock

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 EMPTY
            ================================================== -->

            <div
                v-else
                class="empty-state"
            >

                <div class="empty-icon">
                    📦
                </div>

                <h2>
                    No Products Found
                </h2>

                <p>
                    Try changing your search or
                    category filter.
                </p>

            </div>


            <!-- =================================================
                 BOTTOM SAVE BAR
            ================================================== -->

            <div
                v-if="selectedCount"
                class="bottom-bar"
            >

                <div>

                    <strong>
                        {{ selectedCount }}
                    </strong>

                    products selected

                </div>


                <button
                    type="button"
                    class="save-button"
                    :disabled="saving"
                    @click="saveProducts"
                >

                    {{
                        saving
                            ? 'Saving...'
                            : 'Save Collection'
                    }}

                </button>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

/* =====================================================
   PAGE
===================================================== */

.products-page {

    padding: 10px;

    max-width: 1400px;

    margin: 0 auto;

}


/* =====================================================
   HEADER
===================================================== */

.page-header {

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    gap: 20px;

    margin-bottom: 25px;

}

.page-header h1 {

    margin: 8px 0 5px;

    font-size: 28px;

    font-weight: 700;

}

.page-header p {

    margin: 0;

    color: #777;

    font-size: 14px;

}


.breadcrumb {

    display: flex;

    gap: 8px;

    align-items: center;

    font-size: 12px;

    color: #888;

}

.breadcrumb a {

    color: #075c59;

    text-decoration: none;

}


/* =====================================================
   ACTIONS
===================================================== */

.header-actions {

    display: flex;

    gap: 10px;

}

.back-button,
.save-button {

    padding: 10px 17px;

    border-radius: 8px;

    font-size: 13px;

    cursor: pointer;

}

.back-button {

    border: 1px solid #ddd;

    color: #333;

    background: white;

    text-decoration: none;

}

.save-button {

    border: none;

    background: #075c59;

    color: white;

}

.save-button:hover {

    background: #064b48;

}

.save-button:disabled {

    opacity: .6;

    cursor: not-allowed;

}


/* =====================================================
   SUMMARY
===================================================== */

.collection-summary {

    display: flex;

    align-items: center;

    gap: 20px;

    padding: 18px;

    margin-bottom: 20px;

    background: white;

    border-radius: 12px;

    box-shadow:
        0 2px 10px rgba(0,0,0,.05);

}

.summary-image {

    width: 80px;

    height: 80px;

    flex-shrink: 0;

    overflow: hidden;

    border-radius: 10px;

    background: #f3f3f3;

}

.summary-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

}

.summary-content {

    flex: 1;

}

.summary-content > span {

    color: #a0833d;

    font-size: 8px;

    letter-spacing: 2px;

}

.summary-content h2 {

    margin: 5px 0;

    font-size: 20px;

}

.summary-content p {

    margin: 0;

    color: #777;

    font-size: 12px;

}

.summary-count {

    text-align: right;

}

.summary-count strong {

    display: block;

    color: #075c59;

    font-size: 28px;

}

.summary-count span {

    color: #777;

    font-size: 11px;

}


/* =====================================================
   TOOLBAR
===================================================== */

.toolbar {

    display: flex;

    gap: 12px;

    align-items: center;

    margin-bottom: 12px;

}

.search-wrapper {

    position: relative;

    flex: 1;

}

.search-wrapper input {

    width: 100%;

    box-sizing: border-box;

    padding: 11px 15px 11px 40px;

    border: 1px solid #ddd;

    border-radius: 8px;

    outline: none;

}

.search-wrapper input:focus {

    border-color: #075c59;

}

.search-icon {

    position: absolute;

    left: 14px;

    top: 50%;

    transform: translateY(-50%);

    font-size: 13px;

}

.category-select {

    min-width: 180px;

    padding: 11px;

    border: 1px solid #ddd;

    border-radius: 8px;

    background: white;

}

.select-all-button {

    padding: 11px 16px;

    border: 1px solid #075c59;

    border-radius: 8px;

    background: white;

    color: #075c59;

    cursor: pointer;

}


/* =====================================================
   RESULT BAR
===================================================== */

.result-bar {

    display: flex;

    justify-content: space-between;

    margin-bottom: 12px;

    color: #777;

    font-size: 12px;

}


/* =====================================================
   PRODUCT GRID
===================================================== */

.products-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 18px;

}


.product-card {

    position: relative;

    overflow: hidden;

    background: white;

    border: 1px solid #eee;

    border-radius: 12px;

    cursor: pointer;

    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        transform .2s ease;

}

.product-card:hover {

    transform: translateY(-2px);

    box-shadow:
        0 5px 18px rgba(0,0,0,.08);

}

.product-card.selected {

    border: 2px solid #075c59;

}


/* =====================================================
   CHECKBOX
===================================================== */

.checkbox-wrapper {

    position: absolute;

    z-index: 3;

    top: 12px;

    left: 12px;

}

.checkbox-wrapper input {

    width: 19px;

    height: 19px;

    accent-color: #075c59;

    cursor: pointer;

}


/* =====================================================
   IMAGE
===================================================== */

.product-image {

    position: relative;

    height: 260px;

    background: #f4f4f4;

}

.product-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

}

.selected-badge {

    position: absolute;

    right: 10px;

    bottom: 10px;

    padding: 5px 9px;

    border-radius: 20px;

    background: #075c59;

    color: white;

    font-size: 10px;

}


/* =====================================================
   INFO
===================================================== */

.product-info {

    padding: 15px;

}

.product-category {

    margin-bottom: 5px;

    color: #a0833d;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: 1px;

}

.product-info h3 {

    margin: 0 0 5px;

    font-size: 15px;

    font-weight: 600;

}

.sku {

    margin: 0;

    color: #888;

    font-size: 10px;

}

.product-bottom {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-top: 12px;

}

.product-bottom strong {

    font-size: 14px;

}

.stock-good {

    color: #15803d;

    font-size: 10px;

}

.stock-empty {

    color: #dc2626;

    font-size: 10px;

}


/* =====================================================
   EMPTY
===================================================== */

.empty-state {

    padding: 80px 20px;

    text-align: center;

    background: white;

    border-radius: 12px;

}

.empty-icon {

    font-size: 40px;

}

.empty-state h2 {

    margin: 15px 0 5px;

}

.empty-state p {

    color: #777;

}


/* =====================================================
   BOTTOM BAR
===================================================== */

.bottom-bar {

    position: sticky;

    bottom: 15px;

    z-index: 10;

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 20px;

    padding: 14px 18px;

    background: white;

    border: 1px solid #ddd;

    border-radius: 10px;

    box-shadow:
        0 5px 25px rgba(0,0,0,.12);

    font-size: 13px;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 1100px) {

    .products-grid {

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 800px) {

    .page-header {

        align-items: flex-start;

        flex-direction: column;

    }

    .header-actions {

        width: 100%;

    }

    .header-actions > * {

        flex: 1;

        text-align: center;

    }

    .toolbar {

        flex-direction: column;

        align-items: stretch;

    }

    .category-select {

        width: 100%;

    }

    .products-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

    .collection-summary {

        align-items: flex-start;

    }

    .summary-count {

        margin-left: auto;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 500px) {

    .products-grid {

        grid-template-columns: 1fr;

    }

    .collection-summary {

        flex-wrap: wrap;

    }

    .summary-count {

        width: 100%;

        text-align: left;

    }

    .bottom-bar {

        flex-direction: column;

        gap: 10px;

        align-items: stretch;

    }

    .bottom-bar button {

        width: 100%;

    }

}

</style>