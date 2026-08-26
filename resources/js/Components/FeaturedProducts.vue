<!-- resources/js/Components/FeaturedProducts.vue -->

<script setup>
import { Link, router } from "@inertiajs/vue3";
import { message } from "ant-design-vue";

import {
    ShoppingCartOutlined,
    EyeOutlined,
    ArrowRightOutlined,
} from "@ant-design/icons-vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
});

/*
|--------------------------------------------------------------------------
| FORMAT PRICE
|--------------------------------------------------------------------------
*/

const formatPrice = (price) => {
    return `K${Number(price || 0).toFixed(2)}`;
};

/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE
|--------------------------------------------------------------------------
*/

const getImageUrl = (image) => {
    if (!image) {
        return "/images/placeholder-product.jpg";
    }

    if (
        image.startsWith("http://") ||
        image.startsWith("https://")
    ) {
        return image;
    }

    if (image.startsWith("/storage/")) {
        return image;
    }

    return `/storage/${image}`;
};

/*
|--------------------------------------------------------------------------
| PRODUCT URL
|--------------------------------------------------------------------------
*/

const productHref = (product) => {
    return `/products/${product.slug}`;
};

/*
|--------------------------------------------------------------------------
| ADD TO CART
|--------------------------------------------------------------------------
*/

const addToCart = (product) => {
    if (
        !product.stock_quantity ||
        Number(product.stock_quantity) <= 0
    ) {
        message.warning(
            "This product is currently out of stock."
        );

        return;
    }

    router.post(
        route("cart.add", product.id),
        {
            quantity: 1,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                message.success(
                    `${product.name} added to your shopping cart.`
                );
            },

            onError: (errors) => {
                console.error(
                    "Failed to add product to cart:",
                    errors
                );

                message.error(
                    "Unable to add this product to your shopping cart."
                );
            },
        }
    );
};

/*
|--------------------------------------------------------------------------
| STOCK LABEL
|--------------------------------------------------------------------------
*/

const stockLabel = (product) => {
    const stock = Number(
        product.stock_quantity || 0
    );

    if (stock <= 0) {
        return "Sold Out";
    }

    if (
        stock <= Number(
            product.low_stock_threshold || 5
        )
    ) {
        return `Only ${stock} left`;
    }

    return `${stock} available`;
};

/*
|--------------------------------------------------------------------------
| STOCK CLASS
|--------------------------------------------------------------------------
*/

const stockClass = (product) => {
    const stock = Number(
        product.stock_quantity || 0
    );

    if (stock <= 0) {
        return "stock-sold-out";
    }

    if (
        stock <= Number(
            product.low_stock_threshold || 5
        )
    ) {
        return "stock-low";
    }

    return "stock-available";
};
</script>


<template>

    <section class="featured-products">

        <div class="featured-container">

            <!-- =====================================
                 TITLE
            ====================================== -->

            <div class="featured-header">

                <div>

                    <span class="featured-kicker">
                        FEATURED
                    </span>

                    <h2 class="featured-title">
                        Featured Products
                    </h2>

                </div>

            </div>


            <!-- =====================================
                 PRODUCTS
            ====================================== -->

            <div
                v-if="products.length"
                class="featured-grid"
            >

                <article
                    v-for="product in products"
                    :key="product.id"
                    class="featured-card"
                >

                    <!-- =================================
                         IMAGE
                    ================================== -->

                    <div class="featured-image-wrapper">

                        <Link
                            :href="productHref(product)"
                            class="featured-image-link"
                        >

                            <img
                                :src="getImageUrl(product.image)"
                                :alt="product.name"
                                class="featured-image"
                                loading="lazy"
                            />

                        </Link>


                        <!-- SALE BADGE -->

                        <div
                            v-if="
                                product.sale_price &&
                                Number(product.sale_price) <
                                Number(product.price)
                            "
                            class="sale-badge"
                        >
                            SALE
                        </div>


                        <!-- FEATURED BADGE -->

                        <div class="featured-badge">
                            FEATURED
                        </div>


                        <!-- SOLD OUT -->

                        <div
                            v-if="
                                Number(product.stock_quantity) <= 0
                            "
                            class="sold-out-overlay"
                        >
                            SOLD OUT
                        </div>


                        <!-- QUICK VIEW -->

                        <Link
                            :href="productHref(product)"
                            class="quick-view"
                        >
                            <EyeOutlined />

                            <span>
                                Quick View
                            </span>
                        </Link>

                    </div>


                    <!-- =================================
                         INFORMATION
                    ================================== -->

                    <div class="featured-information">

                        <!-- CATEGORY -->

                        <div class="product-category">
                            {{
                                product.category?.name ||
                                "Collection"
                            }}
                        </div>


                        <!-- NAME -->

                        <Link
                            :href="productHref(product)"
                            class="featured-name"
                        >
                            {{ product.name }}
                        </Link>


                        <!-- PRICE -->

                        <div class="price-row">

                            <span class="product-price">
                                {{
                                    formatPrice(
                                        product.sale_price ??
                                        product.price
                                    )
                                }}
                            </span>


                            <span
                                v-if="
                                    product.sale_price &&
                                    Number(product.sale_price) <
                                    Number(product.price)
                                "
                                class="old-price"
                            >
                                {{
                                    formatPrice(
                                        product.price
                                    )
                                }}
                            </span>

                        </div>


                        <!-- STOCK -->

                        <div
                            class="stock-status"
                            :class="stockClass(product)"
                        >

                            <span class="stock-dot"></span>

                            {{ stockLabel(product) }}

                        </div>


                        <!-- ADD TO CART -->

                        <button
                            type="button"
                            class="add-cart-button"
                            :disabled="
                                Number(product.stock_quantity) <= 0
                            "
                            @click="addToCart(product)"
                        >

                            <ShoppingCartOutlined />

                            <span>
                                {{
                                    Number(product.stock_quantity) > 0
                                        ? "Add to Cart"
                                        : "Sold Out"
                                }}
                            </span>

                        </button>

                    </div>

                </article>

            </div>


            <!-- =====================================
                 EMPTY STATE
            ====================================== -->

            <div
                v-else
                class="empty-state"
            >

                <div class="empty-icon">
                    ★
                </div>

                <h3>
                    Featured products are coming soon
                </h3>

                <p>
                    Check back soon for our featured collection.
                </p>

            </div>


            <!-- =====================================
                 VIEW MORE
            ====================================== -->

            <div
                v-if="products.length"
                class="view-more-wrapper"
            >

                <Link
                    href="/products"
                    class="view-more-button"
                >
                    View All Products

                    <ArrowRightOutlined />
                </Link>

            </div>

        </div>

    </section>

</template>


<style scoped>

/* =========================================
   MAIN SECTION
========================================= */

.featured-products {

    width: 100%;

    background: #f9f6f1;

    padding: 38px 0 42px;

}


/* =========================================
   CONTAINER
========================================= */

.featured-container {

    width: min(
        1045px,
        calc(100% - 70px)
    );

    margin: 0 auto;

}


/* =========================================
   HEADER
========================================= */

.featured-header {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    margin-bottom: 30px;

}


/* =========================================
   KICKER
========================================= */

.featured-kicker {

    display: block;

    margin-bottom: 6px;

    color: #777777;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 9px;

    letter-spacing: 1.2px;

    text-transform: uppercase;

}


/* =========================================
   TITLE
========================================= */

.featured-title {

    margin: 0;

    color: #075d5d;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 30px;

    line-height: 1.15;

    font-weight: 700;

}


/* =========================================
   GRID
========================================= */

.featured-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    column-gap: 14px;

    row-gap: 30px;

}


/* =========================================
   CARD
========================================= */

.featured-card {

    display: block;

    min-width: 0;

}


/* =========================================
   IMAGE WRAPPER
========================================= */

.featured-image-wrapper {

    width: 100%;

    aspect-ratio: 1 / 1.13;

    overflow: hidden;

    background: #eeeeee;

    position: relative;

}


/* =========================================
   IMAGE LINK
========================================= */

.featured-image-link {

    display: block;

    width: 100%;

    height: 100%;

}


/* =========================================
   IMAGE
========================================= */

.featured-image {

    width: 100%;

    height: 100%;

    display: block;

    object-fit: cover;

    transition:
        transform 0.45s ease,
        filter 0.35s ease;

}


/* =========================================
   IMAGE HOVER
========================================= */

.featured-card:hover
.featured-image {

    transform: scale(1.035);

    filter: brightness(0.96);

}


/* =========================================
   SALE BADGE
========================================= */

.sale-badge {

    position: absolute;

    top: 12px;

    left: 12px;

    padding: 6px 9px;

    background: #b49743;

    color: #ffffff;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 8px;

    letter-spacing: 1px;

    z-index: 3;

}


/* =========================================
   FEATURED BADGE
========================================= */

.featured-badge {

    position: absolute;

    top: 12px;

    right: 12px;

    padding: 6px 9px;

    background: #075d5d;

    color: #ffffff;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 8px;

    letter-spacing: 1px;

    z-index: 2;

}


/* =========================================
   SOLD OUT
========================================= */

.sold-out-overlay {

    position: absolute;

    inset: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    background: rgba(0, 0, 0, 0.42);

    color: #ffffff;

    font-size: 13px;

    font-weight: 600;

    letter-spacing: 1px;

    z-index: 4;

}


/* =========================================
   QUICK VIEW
========================================= */

.quick-view {

    position: absolute;

    left: 50%;

    bottom: 14px;

    transform:
        translate(-50%, 12px);

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    min-width: 105px;

    padding: 9px 12px;

    background: rgba(255, 255, 255, 0.94);

    color: #222222;

    font-size: 9px;

    text-decoration: none;

    opacity: 0;

    transition:
        opacity 0.3s ease,
        transform 0.3s ease;

}


.featured-card:hover .quick-view {

    opacity: 1;

    transform:
        translate(-50%, 0);

}


.quick-view:hover {

    background: #111111;

    color: #ffffff;

}


/* =========================================
   INFORMATION
========================================= */

.featured-information {

    width: 100%;

    text-align: center;

    padding-top: 15px;

}


/* =========================================
   CATEGORY
========================================= */

.product-category {

    margin-bottom: 5px;

    color: #888888;

    font-size: 8px;

    text-transform: uppercase;

    letter-spacing: 0.8px;

}


/* =========================================
   NAME
========================================= */

.featured-name {

    display: block;

    width: 100%;

    margin: 0;

    color: #a28636;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 12px;

    line-height: 1.4;

    font-weight: 700;

    text-decoration: none;

}


.featured-name:hover {

    color: #075d5d;

    text-decoration: underline;

    text-underline-offset: 3px;

}


/* =========================================
   PRICE
========================================= */

.price-row {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    margin-top: 6px;

    margin-bottom: 8px;

}


.product-price {

    color: #292929;

    font-size: 12px;

    font-weight: 500;

}


.old-price {

    color: #999999;

    font-size: 10px;

    text-decoration: line-through;

}


/* =========================================
   STOCK
========================================= */

.stock-status {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    margin-bottom: 10px;

    font-size: 8px;

}


.stock-dot {

    width: 6px;

    height: 6px;

    border-radius: 50%;

    display: inline-block;

}


.stock-available {

    color: #3f7a48;

}


.stock-available .stock-dot {

    background: #3f7a48;

}


.stock-low {

    color: #a66a00;

}


.stock-low .stock-dot {

    background: #a66a00;

}


.stock-sold-out {

    color: #b00020;

}


.stock-sold-out .stock-dot {

    background: #b00020;

}


/* =========================================
   ADD TO CART
========================================= */

.add-cart-button {

    width: 100%;

    min-height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    border: 1px solid #075d5d;

    background: #075d5d;

    color: #ffffff;

    font-size: 9px;

    cursor: pointer;

    transition:
        background 0.25s ease,
        color 0.25s ease,
        border-color 0.25s ease;

}


.add-cart-button:hover:not(:disabled) {

    background: #064848;

    border-color: #064848;

}


.add-cart-button:disabled {

    cursor: not-allowed;

    opacity: 0.55;

}


/* =========================================
   EMPTY STATE
========================================= */

.empty-state {

    padding: 70px 20px;

    text-align: center;

}


.empty-icon {

    margin-bottom: 15px;

    color: #b49743;

    font-size: 30px;

}


.empty-state h3 {

    margin: 0 0 8px;

    color: #333333;

    font-size: 18px;

    font-weight: 500;

}


.empty-state p {

    margin: 0;

    color: #888888;

    font-size: 11px;

}


/* =========================================
   VIEW MORE
========================================= */

.view-more-wrapper {

    display: flex;

    justify-content: center;

    margin-top: 39px;

}


.view-more-button {

    min-width: 140px;

    padding: 11px 22px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    border: 1px solid #aaaaaa;

    border-radius: 3px;

    background: #ffffff;

    color: #333333;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 11px;

    line-height: 1;

    text-decoration: none;

    transition:
        background 0.25s ease,
        border-color 0.25s ease,
        color 0.25s ease;

}


.view-more-button:hover {

    background: #111111;

    border-color: #111111;

    color: #ffffff;

}


/* =========================================
   TABLET
========================================= */

@media (max-width: 900px) {

    .featured-container {

        width: calc(100% - 40px);

    }


    .featured-title {

        font-size: 27px;

    }


    .featured-grid {

        gap: 25px 12px;

    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 650px) {

    .featured-products {

        padding: 28px 0 32px;

    }


    .featured-container {

        width: calc(100% - 28px);

    }


    .featured-header {

        margin-bottom: 22px;

    }


    .featured-title {

        font-size: 25px;

    }


    .featured-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        column-gap: 10px;

        row-gap: 30px;

    }


    .featured-information {

        padding-top: 11px;

    }


    .featured-name {

        font-size: 10px;

        line-height: 1.35;

    }


    .product-price {

        font-size: 11px;

    }


    .add-cart-button {

        min-height: 32px;

        font-size: 8px;

    }

}


/* =========================================
   VERY SMALL MOBILE
========================================= */

@media (max-width: 400px) {

    .featured-container {

        width: calc(100% - 20px);

    }


    .featured-grid {

        column-gap: 8px;

    }


    .featured-title {

        font-size: 23px;

    }

}

</style>