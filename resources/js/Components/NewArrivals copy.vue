<!-- resources/js/Components/NewArrivals.vue -->

<script setup>
import { Link, router } from "@inertiajs/vue3";
import { message } from "ant-design-vue";

import {
    ShoppingCartOutlined,
    EyeOutlined,
    FireOutlined,
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
        message.warning("This product is currently out of stock.");
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
    const stock = Number(product.stock_quantity || 0);

    if (stock <= 0) {
        return "Sold Out";
    }

    if (
        stock <= Number(product.low_stock_threshold || 5)
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
    const stock = Number(product.stock_quantity || 0);

    if (stock <= 0) {
        return "stock-sold-out";
    }

    if (
        stock <= Number(product.low_stock_threshold || 5)
    ) {
        return "stock-low";
    }

    return "stock-available";
};
</script>

<template>
    <section class="new-arrivals">

        <!-- =========================================
             CONTAINER
        ========================================== -->

        <div class="new-arrivals-container">

            <!-- =====================================
                 TITLE
            ====================================== -->

            <div class="new-arrivals-header">
                <div>
                    <span class="section-kicker">
                        <FireOutlined />
                        JUST IN
                    </span>

                    <h2 class="new-arrivals-title">
                        New Arrivals
                    </h2>
                </div>
            </div>


            <!-- =====================================
                 PRODUCT GRID
            ====================================== -->

            <div
                v-if="products.length"
                class="products-grid"
            >

                <article
                    v-for="product in products"
                    :key="product.id"
                    class="product-card"
                >

                    <!-- =================================
                         PRODUCT IMAGE
                    ================================== -->

                    <div class="product-image-wrapper">

                        <Link
                            :href="productHref(product)"
                            class="product-image-link"
                        >
                            <img
                                :src="getImageUrl(product.image)"
                                :alt="product.name"
                                class="product-image"
                                loading="lazy"
                            />
                        </Link>


                        <!-- =============================
                             NEW BADGE
                        ============================== -->

                        <div class="new-badge">
                            NEW
                        </div>


                        <!-- =============================
                             SOLD OUT
                        ============================== -->

                        <div
                            v-if="Number(product.stock_quantity) <= 0"
                            class="sold-out-overlay"
                        >
                            SOLD OUT
                        </div>


                        <!-- =============================
                             QUICK VIEW
                        ============================== -->

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
                         PRODUCT INFORMATION
                    ================================== -->

                    <div class="product-information">

                        <!-- CATEGORY -->

                        <div class="product-category">
                            {{ product.category?.name || "Collection" }}
                        </div>


                        <!-- NAME -->

                        <Link
                            :href="productHref(product)"
                            class="product-name"
                        >
                            {{ product.name }}
                        </Link>


                        <!-- PRICE -->

                        <div class="price-row">

                            <!-- SALE PRICE -->

                            <span class="product-price">
                                {{
                                    formatPrice(
                                        product.sale_price ??
                                        product.price
                                    )
                                }}
                            </span>


                            <!-- ORIGINAL PRICE -->

                            <span
                                v-if="
                                    product.sale_price &&
                                    Number(product.sale_price) <
                                    Number(product.price)
                                "
                                class="old-price"
                            >
                                {{
                                    formatPrice(product.price)
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
                    <FireOutlined />
                </div>

                <h3>
                    New arrivals are coming soon
                </h3>

                <p>
                    We're preparing something special.
                    Check back soon for our latest collection.
                </p>
            </div>


            <!-- =====================================
                 VIEW MORE
            ====================================== -->

            <div class="view-more-wrapper">

                <Link
                    href="/new-arrivals"
                    class="view-more-button"
                >
                    View More

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

.new-arrivals {
    width: 100%;
    background: #ffffff;
    padding: 32px 0 34px;
}


/* =========================================
   CONTAINER
========================================= */

.new-arrivals-container {
    width: min(
        1045px,
        calc(100% - 70px)
    );

    margin: 0 auto;
}


/* =========================================
   HEADER
========================================= */

.new-arrivals-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 30px;
}


/* =========================================
   KICKER
========================================= */

.section-kicker {
    display: flex;
    align-items: center;
    gap: 5px;

    margin-bottom: 7px;

    color: #777777;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 9px;

    letter-spacing: 1.2px;

    text-transform: uppercase;
}


/* =========================================
   TITLE
========================================= */

.new-arrivals-title {
    margin: 0;

    color: #111111;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 31px;

    line-height: 1.1;

    font-weight: 700;

    letter-spacing: -0.5px;
}


/* =========================================
   PRODUCT GRID
========================================= */

.products-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    column-gap: 14px;

    row-gap: 30px;
}


/* =========================================
   PRODUCT CARD
========================================= */

.product-card {
    display: block;

    min-width: 0;

    color: #222222;

    text-decoration: none;
}


/* =========================================
   IMAGE WRAPPER
   MEDIUM SIZE
========================================= */

.product-image-wrapper {
    width: 85%;

    aspect-ratio: 1 / 1;

    overflow: hidden;

    background: #eeeeee;

    position: relative;

    margin: 0 auto;
}


/* =========================================
   IMAGE LINK
========================================= */

.product-image-link {
    display: block;

    width: 100%;

    height: 100%;
}


/* =========================================
   PRODUCT IMAGE
========================================= */

.product-image {
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

.product-card:hover
.product-image {
    transform: scale(1.035);

    filter: brightness(0.96);
}


/* =========================================
   NEW BADGE
========================================= */

.new-badge {
    position: absolute;

    top: 10px;

    left: 10px;

    padding: 6px 9px;

    background: #111111;

    color: #ffffff;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 8px;

    letter-spacing: 1px;

    z-index: 2;
}


/* =========================================
   SOLD OUT OVERLAY
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

    z-index: 3;
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


.product-card:hover .quick-view {
    opacity: 1;

    transform:
        translate(-50%, 0);
}


.quick-view:hover {
    background: #111111;

    color: #ffffff;
}


/* =========================================
   PRODUCT INFORMATION
========================================= */

.product-information {
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
   PRODUCT NAME
========================================= */

.product-name {
    display: block;

    width: 100%;

    margin: 0;

    color: #292929;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 11px;

    line-height: 1.4;

    font-weight: 400;

    text-decoration: none;
}


.product-name:hover {
    color: #000000;

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
   STOCK STATUS
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

    border: 1px solid #cccccc;

    background: #ffffff;

    color: #222222;

    font-size: 9px;

    cursor: pointer;

    transition:
        background 0.25s ease,
        color 0.25s ease,
        border-color 0.25s ease;
}


.add-cart-button:hover:not(:disabled) {
    background: #111111;

    border-color: #111111;

    color: #ffffff;
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

    color: #aaaaaa;

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
    min-width: 126px;

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

    .new-arrivals-container {
        width: calc(100% - 40px);
    }


    .new-arrivals-title {
        font-size: 28px;
    }


    .products-grid {
        gap: 25px 12px;
    }


    .product-image-wrapper {
        width: 90%;
    }
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 650px) {

    .new-arrivals {
        padding: 28px 0 32px;
    }


    .new-arrivals-container {
        width: calc(100% - 28px);
    }


    .new-arrivals-header {
        margin-bottom: 22px;
    }


    .new-arrivals-title {
        font-size: 25px;
    }


    .products-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        column-gap: 10px;

        row-gap: 30px;
    }


    /*
    |------------------------------------------------
    | MOBILE IMAGE
    |------------------------------------------------
    */

    .product-image-wrapper {
        width: 100%;

        aspect-ratio: 1 / 1;
    }


    .product-information {
        padding-top: 11px;
    }


    .product-name {
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

    .new-arrivals-container {
        width: calc(100% - 20px);
    }


    .products-grid {
        column-gap: 8px;
    }


    .new-arrivals-title {
        font-size: 23px;
    }


    .product-image-wrapper {
        width: 100%;
    }
}

</style>