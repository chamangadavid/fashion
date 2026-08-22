<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    collection: {
        type: Object,
        required: true
    }
})

const imageUrl = (image) => {

    if (!image) {
        return null
    }

    return `/storage/${image}`

}

</script>

<template>

    <Head :title="`View ${collection.name}`" />

    <MyFashionLayout>

        <div class="page">

            <!-- HEADER -->

            <div class="page-header">

                <div>

                    <h1>
                        {{ collection.name }}
                    </h1>

                    <p>
                        Collection details and information.
                    </p>

                </div>

                <div class="header-actions">

                    <Link
                        :href="`/fashion/collections/${collection.id}/edit`"
                        class="edit-button"
                    >
                        Edit Collection
                    </Link>

                    <Link :href="`/fashion/collections/${collection.id}/products`" 
                       class="manage-products-button">
                        Manage Products
                    </Link>

                    <Link
                        href="/fashion/collections"
                        class="back-button"
                    >
                        Back
                    </Link>

                </div>

            </div>


            <!-- COLLECTION CARD -->

            <div class="collection-card">

                <!-- IMAGE -->

                <div class="collection-image">

                    <img
                        v-if="imageUrl(collection.image)"
                        :src="imageUrl(collection.image)"
                        :alt="collection.name"
                    />

                    <div
                        v-else
                        class="no-image"
                    >
                        No Image
                    </div>

                </div>


                <!-- DETAILS -->

                <div class="collection-details">

                    <div class="title-row">

                        <div>

                            <span class="label">
                                COLLECTION
                            </span>

                            <h2>
                                {{ collection.name }}
                            </h2>

                        </div>

                        <span
                            class="status"
                            :class="collection.is_active
                                ? 'active'
                                : 'inactive'"
                        >
                            {{
                                collection.is_active
                                    ? 'Active'
                                    : 'Inactive'
                            }}
                        </span>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="detail-section">

                        <span class="label">
                            DESCRIPTION
                        </span>

                        <p v-if="collection.description">
                            {{ collection.description }}
                        </p>

                        <p
                            v-else
                            class="muted"
                        >
                            No description provided.
                        </p>

                    </div>


                    <!-- INFORMATION GRID -->

                    <div class="info-grid">

                        <div class="info-item">

                            <span class="label">
                                SLUG
                            </span>

                            <strong>
                                {{ collection.slug }}
                            </strong>

                        </div>


                        <div class="info-item">

                            <span class="label">
                                SORT ORDER
                            </span>

                            <strong>
                                {{ collection.sort_order ?? 0 }}
                            </strong>

                        </div>


                        <div class="info-item">

                            <span class="label">
                                FEATURED
                            </span>

                            <strong
                                :class="collection.is_featured
                                    ? 'yes'
                                    : 'no'"
                            >
                                {{
                                    collection.is_featured
                                        ? 'Yes'
                                        : 'No'
                                }}
                            </strong>

                        </div>


                        <div class="info-item">

                            <span class="label">
                                CREATED
                            </span>

                            <strong>
                                {{
                                    collection.created_at
                                        ? new Date(
                                            collection.created_at
                                        ).toLocaleDateString()
                                        : '-'
                                }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            <!-- PRODUCTS -->

            <div class="products-card">

                <div class="products-header">

                    <div>

                        <h2>
                            Products in Collection
                        </h2>

                        <p>
                            Products assigned to this collection.
                        </p>

                    </div>

                    <span class="product-count">

                        {{
                            collection.products
                                ? collection.products.length
                                : 0
                        }}

                        Products

                    </span>

                </div>


                <!-- PRODUCTS -->

                <div
                    v-if="
                        collection.products &&
                        collection.products.length
                    "
                    class="products-grid"
                >

                    <div
                        v-for="product in collection.products"
                        :key="product.id"
                        class="product-card"
                    >

                        <div class="product-image">

                            <img
                                v-if="product.image"
                                :src="`/storage/${product.image}`"
                                :alt="product.name"
                            />

                            <div
                                v-else
                                class="no-product-image"
                            >
                                No Image
                            </div>

                        </div>


                        <div class="product-info">

                            <h3>
                                {{ product.name }}
                            </h3>

                            <p>
                                SKU:
                                {{ product.sku }}
                            </p>

                            <strong>
                                {{
                                    Number(
                                        product.price
                                    ).toFixed(2)
                                }}
                            </strong>

                        </div>

                    </div>

                </div>


                <!-- EMPTY -->

                <div
                    v-else
                    class="empty-products"
                >

                    <h3>
                        No Products Assigned
                    </h3>

                    <p>
                        Products assigned to this collection
                        will appear here.
                    </p>

                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.page {
    padding: 10px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.header-actions {
    display: flex;
    gap: 10px;
}

.back-button,
.manage-products-button,
.edit-button {
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
}

.back-button {
    border: 1px solid #ddd;
    background: white;
    color: #333;
}

.edit-button {
    background: #111827;
    color: white;
}

.manage-products-button{
    background: #111827;
    color: white;
}

/* COLLECTION */

.collection-card {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 35px;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.collection-image {
    height: 350px;
    overflow: hidden;
    border-radius: 10px;
    background: #f5f5f5;
}

.collection-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image,
.no-product-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}


/* DETAILS */

.collection-details {
    padding: 10px;
}

.title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.label {
    display: block;
    color: #999;
    font-size: 10px;
    letter-spacing: 1.5px;
    margin-bottom: 7px;
}

.title-row h2 {
    margin: 0;
    font-size: 26px;
}

.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.status.active {
    background: #dcfce7;
    color: #166534;
}

.status.inactive {
    background: #fee2e2;
    color: #991b1b;
}


/* DESCRIPTION */

.detail-section {
    margin-top: 30px;
}

.detail-section p {
    color: #555;
    line-height: 1.7;
    font-size: 14px;
}

.muted {
    color: #999 !important;
}


/* INFO */

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid #eee;
}

.info-item strong {
    font-size: 14px;
}

.yes {
    color: #166534;
}

.no {
    color: #777;
}


/* PRODUCTS */

.products-card {
    margin-top: 25px;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.products-header h2 {
    margin: 0;
}

.products-header p {
    margin: 5px 0 0;
    color: #777;
}

.product-count {
    padding: 7px 12px;
    background: #f3f4f6;
    border-radius: 20px;
    font-size: 12px;
}


/* PRODUCT GRID */

.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.product-card {
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
    background: white;
}

.product-image {
    height: 220px;
    background: #f5f5f5;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 14px;
}

.product-info h3 {
    margin: 0 0 7px;
    font-size: 14px;
}

.product-info p {
    margin: 0 0 8px;
    color: #888;
    font-size: 11px;
}

.product-info strong {
    font-size: 14px;
}


/* EMPTY */

.empty-products {
    padding: 60px 20px;
    text-align: center;
    border: 1px dashed #ddd;
    border-radius: 10px;
}

.empty-products h3 {
    margin: 0 0 8px;
}

.empty-products p {
    color: #888;
}


/* RESPONSIVE */

@media (max-width: 900px) {

    .collection-card {
        grid-template-columns: 1fr;
    }

    .collection-image {
        height: 400px;
    }

    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .collection-image {
        height: 320px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .products-grid {
        grid-template-columns: 1fr;
    }

    .products-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 15px;
    }

}

</style>