<!-- resources\js\Pages\Site\NewArrivals\Index.vue -->
<script setup>

import { computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";

import {
    ShoppingCartOutlined,
    EyeOutlined,
    ArrowRightOutlined,
    FireOutlined,
} from '@ant-design/icons-vue'

import { message } from 'ant-design-vue'

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
})

const products = computed(() => props.products?.data ?? [])

const formatPrice = (price) => {
    return `K${Number(price || 0).toFixed(2)}`
}

const getImageUrl = (image) => {

    if (!image) {
        return '/images/placeholder-product.jpg'
    }

    if (image.startsWith('http')) {
        return image
    }

    return `/storage/${image}`
}

const addToCart = (product) => {
    if (!product.stock_quantity || Number(product.stock_quantity) <= 0) {
        message.warning('This product is currently out of stock.')
        return
    }

    router.post(
        route('cart.add', product.id),
        {
            quantity: 1,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                message.success(
                    `${product.name} added to your shopping cart.`
                )
            },

            onError: (errors) => {
                console.error(
                    'Failed to add product to cart:',
                    errors
                )

                message.error(
                    'Unable to add this product to your cart.'
                )
            },
        }
    )
}

const stockLabel = (product) => {

    const stock = Number(product.stock_quantity || 0)

    if (stock <= 0) {
        return 'Sold Out'
    }

    if (stock <= Number(product.low_stock_threshold || 5)) {
        return `Only ${stock} left`
    }

    return `${stock} available`
}

const stockClass = (product) => {

    const stock = Number(product.stock_quantity || 0)

    if (stock <= 0) {
        return 'stock-sold-out'
    }

    if (stock <= Number(product.low_stock_threshold || 5)) {
        return 'stock-low'
    }

    return 'stock-available'
}

</script>

<template>

    <Head title="New Arrivals" />

    <div class="new-arrivals-page">

        <!-- HEADER -->

        <Header />

        <!-- ================================= -->
        <!-- HERO -->
        <!-- ================================= -->

        <section class="new-arrivals-hero">

            <div class="hero-content">

                <span class="hero-kicker">
                    <FireOutlined />
                    JUST IN
                </span>

                <h1>
                    New Arrivals
                </h1>

                <p>
                    Discover the latest pieces added to
                    our collection. Fresh styles, timeless
                    essentials and statement pieces.
                </p>

                <div class="hero-line"></div>

            </div>

        </section>


        <!-- ================================= -->
        <!-- CONTENT -->
        <!-- ================================= -->

        <main class="new-arrivals-container">

            <!-- HEADER -->

            <div class="collection-header">

                <div>

                    <span class="section-label">
                        THE LATEST EDIT
                    </span>

                    <h2>
                        Fresh From The Collection
                    </h2>

                </div>

                <span class="product-count">
                    {{ props.products?.total ?? products.length }}
                    {{ (props.products?.total ?? products.length) === 1
                        ? 'Product'
                        : 'Products'
                    }}
                </span>

            </div>


            <!-- ================================= -->
            <!-- PRODUCTS -->
            <!-- ================================= -->

            <div v-if="products.length" class="products-grid">

                <article v-for="product in products" :key="product.id" class="product-card">

                    <!-- IMAGE -->

                    <div class="product-image-wrapper">

                        <Link :href="`/products/${product.slug}`" class="product-image-link">

                            <img :src="getImageUrl(product.image)" :alt="product.name" class="product-image" />

                        </Link>


                        <!-- NEW BADGE -->

                        <div class="new-badge">
                            NEW
                        </div>


                        <!-- SOLD OUT -->

                        <div v-if="Number(product.stock_quantity) <= 0" class="sold-out-overlay">
                            SOLD OUT
                        </div>


                        <!-- QUICK VIEW -->

                        <Link :href="`/products/${product.slug}`" class="quick-view">

                            <EyeOutlined />

                            <span>
                                Quick View
                            </span>

                        </Link>

                    </div>



                    <!-- DETAILS -->

                    <div class="product-details">

                        <!-- CATEGORY -->

                        <div class="product-category">
                            {{ product.category?.name || 'Collection' }}
                        </div>


                        <!-- PRODUCT NAME + PRICE -->

                        <div class="name-price-row">

                            <Link :href="`/products/${product.slug}`" class="product-name">
                                {{ product.name }}
                            </Link>

                            <div class="price-row">

                                <!-- SALE / CURRENT PRICE -->

                                <span class="product-price">
                                    {{ formatPrice(
                                        product.sale_price ?? product.price
                                    ) }}
                                </span>

                                <!-- OLD PRICE -->

                                <span v-if="
                                    product.sale_price &&
                                    Number(product.sale_price) <
                                    Number(product.price)
                                " class="old-price">
                                    {{ formatPrice(product.price) }}
                                </span>

                            </div>

                        </div>


                        <!-- STOCK -->

                        <div class="stock-status" :class="stockClass(product)">

                            <span class="stock-dot"></span>

                            {{ stockLabel(product) }}

                        </div>


                        <!-- ADD TO CART -->

                        <button type="button" class="add-cart-button" :disabled="Number(product.stock_quantity) <= 0
                            " @click="addToCart(product)">

                            <ShoppingCartOutlined />

                            <span>
                                {{
                                    Number(product.stock_quantity) > 0
                                        ? 'Add to Cart'
                                : 'Sold Out'
                                }}
                            </span>

                        </button>

                    </div>

                </article>

            </div>


            <!-- EMPTY -->

            <div v-else class="empty-state">

                <div class="empty-icon">
                    <FireOutlined />
                </div>

                <h3>
                    New arrivals are coming soon
                </h3>

                <p>
                    We're preparing something special.
                    Check back soon for our latest collection.
                </p>

                <Link href="/" class="continue-shopping">
                    Continue Shopping
                    <ArrowRightOutlined />
                </Link>

            </div>


            <!-- ================================= -->
            <!-- PAGINATION -->
            <!-- ================================= -->

            <div v-if="
                props.products &&
                props.products.links &&
                props.products.links.length > 3
            " class="pagination">

                <Link v-for="link in props.products.links" :key="link.label" :href="link.url || '#'"
                    class="pagination-link" :class="{
                        active: link.active,
                        disabled: !link.url,
                    }" v-html="link.label" />

            </div>

        </main>

        
        <!-- =================================================
             FOOTER
        ================================================== -->

        <AppFooter />
        
    </div>

</template>


<style scoped>
.new-arrivals-page {
    min-height: 100vh;
    background: #fff;
    color: #181818;
}


/* ================================= */
/* HERO */
/* ================================= */

.new-arrivals-hero {
    min-height: 390px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;

    background:
        linear-gradient(rgba(0, 0, 0, .45),
            rgba(0, 0, 0, .45)),
        url('/assets/new-arrival.png');

    background-size: cover;
    background-position: center;
}

.hero-content {
    max-width: 720px;
    padding: 50px 20px;
    color: white;
}

.hero-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    font-size: 12px;
    letter-spacing: 3px;
    font-weight: 700;
}

.hero-content h1 {
    margin: 18px 0 14px;

    font-family: Georgia, serif;

    font-size: clamp(42px, 7vw, 78px);
    font-weight: 400;
}

.hero-content p {
    max-width: 580px;
    margin: auto;

    font-size: 16px;
    line-height: 1.8;

    opacity: .92;
}

.hero-line {
    width: 60px;
    height: 1px;

    background: white;

    margin: 28px auto 0;
}


/* ================================= */
/* CONTAINER */
/* ================================= */

.new-arrivals-container {
    max-width: 1400px;

    margin: auto;

    padding:
        70px 35px 100px;
}


/* ================================= */
/* HEADER */
/* ================================= */

.collection-header {
    display: flex;
    justify-content: space-between;
    align-items: end;

    margin-bottom: 40px;

    border-bottom: 1px solid #e7e7e7;

    padding-bottom: 22px;
}

.section-label {
    display: block;

    margin-bottom: 8px;

    font-size: 11px;
    letter-spacing: 2.5px;

    color: #777;

    font-weight: 700;
}

.collection-header h2 {
    margin: 0;

    font-family: Georgia, serif;

    font-size: 34px;

    font-weight: 400;
}

.product-count {
    color: #777;

    font-size: 13px;
}


/* ================================= */
/* GRID */
/* ================================= */

.products-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 32px 22px;
}


/* ================================= */
/* PRODUCT CARD */
/* ================================= */

.product-card {
    min-width: 0;
}

.product-image-wrapper {
    position: relative;

    overflow: hidden;

    background: #f5f5f3;

    aspect-ratio: 3 / 4;
}

.product-image-link {
    display: block;

    width: 100%;
    height: 100%;
}

.product-image {
    width: 100%;
    height: 100%;

    object-fit: cover;

    transition:
        transform .6s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}


/* ================================= */
/* BADGE */
/* ================================= */

.new-badge {
    position: absolute;

    top: 14px;
    left: 14px;

    background: #181818;

    color: white;

    padding: 7px 11px;

    font-size: 8px;

    letter-spacing: 1.5px;

    font-weight: 700;
}


/* ================================= */
/* SOLD OUT */
/* ================================= */

.sold-out-overlay {
    position: absolute;

    inset: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    background: rgba(255, 255, 255, .55);

    color: #111;

    font-size: 13px;

    letter-spacing: 2px;

    font-weight: 700;
}


/* ================================= */
/* QUICK VIEW */
/* ================================= */

.quick-view {
    position: absolute;

    left: 15px;
    right: 15px;
    bottom: 15px;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 8px;

    padding: 12px;

    background: white;

    color: #111;

    text-decoration: none;

    font-size: 12px;

    transform: translateY(70px);

    transition:
        transform .35s ease;
}

.product-card:hover .quick-view {
    transform: translateY(0);
}


/* ================================= */
/* DETAILS */
/* ================================= */

.name-price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    width: 100%;
}

.product-name {
    flex: 1;
    min-width: 0;
}

.price-row {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.product-details {
    padding-top: 17px;
}

.product-category {
    font-size: 8px;

    letter-spacing: 1.5px;

    color: #888;

    text-transform: uppercase;

    margin-bottom: 7px;
}

.product-name {
    display: block;

    color: #181818;

    text-decoration: none;

    font-family: Georgia, serif;

    font-size: 18px;

    line-height: 1.35;
}

.product-name:hover {
    text-decoration: underline;
}


/* ================================= */
/* PRICE */
/* ================================= */

.price-row {
    display: flex;

    align-items: center;

    gap: 10px;

    margin-top: 9px;
}

.product-price {
    font-size: 15px;

    font-weight: 600;
}

.old-price {
    color: #999;

    font-size: 13px;

    text-decoration: line-through;
}


/* ================================= */
/* STOCK */
/* ================================= */

.stock-status {
    display: flex;

    align-items: center;

    gap: 7px;

    margin-top: 10px;

    font-size: 11px;

    font-weight: 600;
}

.stock-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: currentColor;
}

.stock-available {
    color: #287a48;
}

.stock-low {
    color: #b56b00;
}

.stock-sold-out {
    color: #a33;
}


/* ================================= */
/* CART */
/* ================================= */

.add-cart-button {
    width: 100%;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 8px;

    margin-top: 14px;

    padding: 13px 15px;

    border: 1px solid #181818;

    background: #181818;

    color: white;

    cursor: pointer;

    font-size: 12px;

    letter-spacing: .8px;

    transition:
        background .25s ease,
        color .25s ease;
}

.add-cart-button:hover:not(:disabled) {
    background: white;

    color: #181818;
}

.add-cart-button:disabled {
    background: #ddd;

    border-color: #ddd;

    color: #777;

    cursor: not-allowed;
}


/* ================================= */
/* EMPTY */
/* ================================= */

.empty-state {
    text-align: center;

    max-width: 600px;

    margin: 100px auto;
}

.empty-icon {
    font-size: 42px;

    margin-bottom: 20px;
}

.empty-state h3 {
    font-family: Georgia, serif;

    font-size: 28px;

    font-weight: 400;
}

.empty-state p {
    color: #777;

    line-height: 1.7;
}

.continue-shopping {
    display: inline-flex;

    align-items: center;

    gap: 10px;

    margin-top: 20px;

    padding: 13px 25px;

    background: #181818;

    color: white;

    text-decoration: none;

    font-size: 12px;
}


/* ================================= */
/* PAGINATION */
/* ================================= */

.pagination {
    display: flex;

    justify-content: center;

    gap: 7px;

    margin-top: 60px;
}

.pagination-link {
    min-width: 38px;

    padding: 10px;

    text-align: center;

    border: 1px solid #ddd;

    color: #222;

    text-decoration: none;

    font-size: 12px;
}

.pagination-link.active {
    background: #181818;

    color: white;

    border-color: #181818;
}

.pagination-link.disabled {
    opacity: .4;

    pointer-events: none;
}


/* ================================= */
/* RESPONSIVE */
/* ================================= */

@media (max-width: 1100px) {

    .products-grid {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

}

@media (max-width: 760px) {

    .new-arrivals-container {
        padding:
            45px 18px 70px;
    }

    .new-arrivals-hero {
        min-height: 320px;
    }

    .collection-header {
        align-items: start;

        flex-direction: column;

        gap: 12px;
    }

    .products-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 28px 12px;
    }

    .product-name {
        font-size: 15px;
    }

    .quick-view {
        display: none;
    }

}

@media (max-width: 480px) {

    .products-grid {
        grid-template-columns: 1fr 1fr;
    }

    .product-details {
        padding-top: 12px;
    }

    .add-cart-button {
        padding: 11px 8px;
    }

}
</style>