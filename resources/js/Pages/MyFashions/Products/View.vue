<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({

    product: {
        type: Object,
        required: true,
    },

});

const formatMoney = (amount) => {

    return new Intl.NumberFormat('en-ZM', {

        style: 'currency',

        currency: 'ZMW',

        minimumFractionDigits: 2,

    }).format(Number(amount || 0));

};

const imageUrl = (image) => {

    if (!image) {
        return null;
    }

    return `/storage/${image}`;

};

</script>


<template>

    <MyFashionLayout>

        <div class="product-view-page">


            <!-- HEADER -->

            <div class="page-header">

                <div>

                    <h1>
                        {{ product.name }}
                    </h1>

                    <p>
                        View product information and inventory details.
                    </p>

                </div>


                <div class="header-actions">

                    <Link
                        href="/fashion/products"
                        class="back-button"
                    >
                        Back to Products
                    </Link>


                    <Link
                        :href="`/fashion/products/${product.id}/edit`"
                        class="edit-button"
                    >
                        Edit Product
                    </Link>

                </div>

            </div>


            <!-- PRODUCT -->

            <div class="product-layout">


                <!-- IMAGE -->

                <div class="content-card image-card">

                    <div
                        v-if="imageUrl(product.image)"
                        class="product-image-wrapper"
                    >

                        <img
                            :src="imageUrl(product.image)"
                            :alt="product.name"
                            class="product-image"
                        >

                    </div>


                    <div
                        v-else
                        class="no-image"
                    >

                        No Product Image

                    </div>

                </div>



                <!-- DETAILS -->

                <div class="content-card details-card">

                    <div class="details-header">

                        <div>

                            <h2>
                                {{ product.name }}
                            </h2>

                            <p>
                                SKU: {{ product.sku }}
                            </p>

                        </div>


                        <span
                            class="status-badge"
                            :class="{
                                active: product.is_active,
                                inactive: !product.is_active
                            }"
                        >

                            {{ product.is_active ? 'Active' : 'Inactive' }}

                        </span>

                    </div>



                    <!-- CATEGORY -->

                    <div class="detail-row">

                        <span class="detail-label">
                            Category
                        </span>

                        <strong>

                            {{ product.category?.name ?? 'Uncategorized' }}

                        </strong>

                    </div>



                    <!-- PRICE -->

                    <div class="detail-row">

                        <span class="detail-label">
                            Price
                        </span>

                        <strong>
                            {{ formatMoney(product.price) }}
                        </strong>

                    </div>



                    <!-- SALE PRICE -->

                    <div
                        v-if="product.sale_price"
                        class="detail-row"
                    >

                        <span class="detail-label">
                            Sale Price
                        </span>

                        <strong>
                            {{ formatMoney(product.sale_price) }}
                        </strong>

                    </div>



                    <!-- COST -->

                    <div class="detail-row">

                        <span class="detail-label">
                            Cost Price
                        </span>

                        <strong>
                            {{ formatMoney(product.cost_price) }}
                        </strong>

                    </div>



                    <!-- STOCK -->

                    <div class="detail-row">

                        <span class="detail-label">
                            Stock Quantity
                        </span>

                        <strong>
                            {{ product.stock_quantity }}
                        </strong>

                    </div>



                    <!-- LOW STOCK -->

                    <div class="detail-row">

                        <span class="detail-label">
                            Low Stock Threshold
                        </span>

                        <strong>
                            {{ product.low_stock_threshold }}
                        </strong>

                    </div>



                    <!-- FEATURED -->

                    <div class="detail-row">

                        <span class="detail-label">
                            Featured
                        </span>

                        <span
                            class="featured-badge"
                            :class="{
                                yes: product.is_featured,
                                no: !product.is_featured
                            }"
                        >

                            {{ product.is_featured ? 'Yes' : 'No' }}

                        </span>

                    </div>



                    <!-- DESCRIPTION -->

                    <div class="description-section">

                        <h3>
                            Description
                        </h3>

                        <p>
                            {{ product.description || 'No description provided.' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.product-view-page {
    padding: 10px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 20px;
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

.header-actions {
    display: flex;
    gap: 10px;
}

.back-button,
.edit-button {
    display: inline-flex;
    align-items: center;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
}

.back-button {
    border: 1px solid #ddd;
    color: #333;
    background: white;
}

.edit-button {
    background: #111827;
    color: white;
}

.product-layout {
    display: grid;
    grid-template-columns: minmax(300px, 420px) 1fr;
    gap: 25px;
}

.content-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.image-card {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image-wrapper {
    width: 100%;
}

.product-image {
    width: 100%;
    max-height: 500px;
    object-fit: contain;
    border-radius: 10px;
}

.no-image {
    color: #999;
    text-align: center;
}

.details-card {
    padding: 30px;
}

.details-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 25px;
}

.details-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.details-header p {
    margin-top: 5px;
    color: #777;
}

.status-badge,
.featured-badge {
    display: inline-flex;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active,
.featured-badge.yes {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive,
.featured-badge.no {
    background: #fee2e2;
    color: #991b1b;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.detail-label {
    color: #777;
}

.description-section {
    margin-top: 25px;
}

.description-section h3 {
    margin-bottom: 10px;
    font-size: 18px;
}

.description-section p {
    line-height: 1.7;
    color: #555;
}

@media (max-width: 900px) {

    .product-layout {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

}

</style>