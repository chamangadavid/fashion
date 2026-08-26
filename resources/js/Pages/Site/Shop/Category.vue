<script setup>

import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";

import Swal from "sweetalert2";

import {
    ShoppingOutlined,
    ArrowLeftOutlined,
    ArrowRightOutlined,
    CheckCircleOutlined,
} from "@ant-design/icons-vue";


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    category: {
        type: Object,
        required: true,
    },

    products: {
        type: Object,
        required: true,
    },

});


/*
|--------------------------------------------------------------------------
| ADDING PRODUCT
|--------------------------------------------------------------------------
*/

const addingProduct = ref(null);


/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {

    if (!image) {
        return null;
    }

    /*
    |----------------------------------------------------------------------
    | If image already contains /storage/
    |----------------------------------------------------------------------
    */

    if (image.startsWith("/storage/")) {
        return image;
    }

    /*
    |----------------------------------------------------------------------
    | Normal Laravel storage image
    |----------------------------------------------------------------------
    */

    return `/storage/${image}`;
};


/*
|--------------------------------------------------------------------------
| PRODUCT PRICE
|--------------------------------------------------------------------------
*/

const productPrice = (product) => {

    if (product.sale_price) {
        return product.sale_price;
    }

    return product.price;
};


/*
|--------------------------------------------------------------------------
| ADD TO BAG
|--------------------------------------------------------------------------
*/

const addToBag = (product) => {

    /*
    |--------------------------------------------------------------------------
    | Prevent double click
    |--------------------------------------------------------------------------
    */

    if (addingProduct.value === product.id) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Product unavailable
    |--------------------------------------------------------------------------
    */

    if (
        !product.is_active ||
        product.stock_quantity <= 0
    ) {
        return;
    }


    addingProduct.value = product.id;


    /*
    |--------------------------------------------------------------------------
    | ADD TO CART
    |--------------------------------------------------------------------------
    */

    router.post(
        `/cart/add/${product.id}`,
        {
            quantity: 1,
        },
        {
            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({

                    icon: "success",

                    title: "Added to Bag!",

                    text: `${product.name} has been added to your bag.`,

                    confirmButtonText: "Continue Shopping",

                    timer: 2500,

                    timerProgressBar: true,

                });

            },

            onError: (errors) => {

                console.error(
                    "Failed to add product to bag:",
                    errors
                );


                Swal.fire({

                    icon: "error",

                    title: "Unable to Add",

                    text:
                        "Something went wrong while adding this product to your bag.",

                    confirmButtonText: "OK",

                });

            },

            onFinish: () => {

                addingProduct.value = null;

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

    <Head :title="category.name" />


    <div class="category-page">

        <!-- =========================================================
             HEADER
        ========================================================== -->

        <Header />


        <!-- =========================================================
             MAIN
        ========================================================== -->

        <main class="category-container">


            <!-- =====================================================
                 CATEGORY HERO
            ====================================================== -->

            <section class="category-hero">

                <div class="category-hero-content">

                    <Link
                        href="/"
                        class="back-home"
                    >
                        <ArrowLeftOutlined />

                        Back to Home
                    </Link>


                    <span class="category-label">
                        {{ category.group === 'clothing'
                            ? 'CLOTHING'
                            : 'HANDBAGS & ACCESSORIES'
                        }}
                    </span>


                    <h1>
                        {{ category.name }}
                    </h1>


                    <p v-if="category.description">
                        {{ category.description }}
                    </p>

                </div>


                <!-- CATEGORY IMAGE -->

                <div
                    v-if="category.image"
                    class="category-hero-image"
                >

                    <img
                        :src="imageUrl(category.image)"
                        :alt="category.name"
                    />

                </div>

            </section>


            <!-- =====================================================
                 CATEGORY TOOLBAR
            ====================================================== -->

            <section class="products-section">

                <div class="products-header">

                    <div>

                        <span class="products-label">
                            SHOP
                        </span>

                        <h2>
                            {{ category.name }}
                        </h2>

                    </div>


                    <div class="product-count">

                        {{ products.total }}

                        {{ products.total === 1
                            ? 'Product'
                            : 'Products'
                        }}

                    </div>

                </div>


                <!-- =================================================
                     PRODUCTS
                ================================================== -->

                <div
                    v-if="products.data && products.data.length"
                    class="products-grid"
                >

                    <article
                        v-for="product in products.data"
                        :key="product.id"
                        class="product-card"
                    >


                        <!-- =========================================
                             PRODUCT IMAGE
                        ========================================== -->

                        <Link
                            :href="`/products/${product.slug}`"
                            class="product-image-wrapper"
                        >

                            <img
                                v-if="product.image"
                                :src="imageUrl(product.image)"
                                :alt="product.name"
                                class="product-image"
                            />

                            <div
                                v-else
                                class="no-image"
                            >
                                <ShoppingOutlined />

                                <span>
                                    NO IMAGE
                                </span>
                            </div>


                            <!-- SALE -->

                            <span
                                v-if="product.sale_price"
                                class="sale-badge"
                            >
                                SALE
                            </span>


                            <!-- NEW -->

                            <span
                                v-if="product.is_new_arrival"
                                class="new-badge"
                            >
                                NEW
                            </span>

                        </Link>


                        <!-- =========================================
                             PRODUCT DETAILS
                        ========================================== -->

                        <div class="product-details">


                            <span
                                v-if="category.name"
                                class="product-category"
                            >
                                {{ category.name }}
                            </span>


                            <Link
                                :href="`/products/${product.slug}`"
                                class="product-name"
                            >
                                {{ product.name }}
                            </Link>


                            <div class="product-price">

                                <span
                                    v-if="product.sale_price"
                                    class="sale-price"
                                >
                                    K{{ product.sale_price }}
                                </span>


                                <span
                                    :class="{
                                        'old-price':
                                            product.sale_price
                                    }"
                                >
                                    K{{ product.price }}
                                </span>

                            </div>


                            <!-- =====================================
                                 STOCK
                            ====================================== -->

                            <div class="stock-status">

                                <span
                                    v-if="product.stock_quantity > 0"
                                    class="in-stock"
                                >
                                    <CheckCircleOutlined />

                                    In Stock
                                </span>

                                <span
                                    v-else
                                    class="out-stock"
                                >
                                    Out of Stock
                                </span>

                            </div>


                            <!-- =====================================
                                 ADD TO BAG
                            ====================================== -->

                            <button
                                type="button"
                                class="add-to-bag"
                                :disabled="
                                    addingProduct === product.id ||
                                    !product.is_active ||
                                    product.stock_quantity <= 0
                                "
                                @click="addToBag(product)"
                            >

                                <!-- LOADING -->

                                <span
                                    v-if="
                                        addingProduct === product.id
                                    "
                                    class="button-loading"
                                >

                                    <span class="spinner"></span>

                                    ADDING...

                                </span>


                                <!-- NORMAL -->

                                <span
                                    v-else
                                >

                                    <ShoppingOutlined />

                                    {{
                                        !product.is_active
                                            ? 'UNAVAILABLE'
                                            : product.stock_quantity > 0
                                                ? 'ADD TO BAG'
                                                : 'OUT OF STOCK'
                                    }}

                                </span>

                            </button>

                        </div>

                    </article>

                </div>


                <!-- =================================================
                     EMPTY
                ================================================== -->

                <div
                    v-else
                    class="empty-products"
                >

                    <ShoppingOutlined />

                    <h2>
                        No Products Found
                    </h2>

                    <p>
                        There are currently no products available
                        in this category.
                    </p>

                    <Link
                        href="/collections"
                        class="browse-button"
                    >
                        Browse Collections
                    </Link>

                </div>


                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div
                    v-if="
                        products.links &&
                        products.last_page > 1
                    "
                    class="pagination"
                >

                    <button
                        v-for="(link, index) in products.links"
                        :key="index"
                        :disabled="
                            !link.url ||
                            link.active
                        "
                        :class="{
                            active: link.active
                        }"
                        @click="goToPage(link.url)"
                        v-html="link.label"
                    ></button>

                </div>


            </section>

        </main>


        <!-- =========================================================
             FOOTER
        ========================================================== -->

        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.category-page {
    min-height: 100vh;
    background: #fff;
    color: #111;
}


/*
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
*/

.category-container {
    width: 100%;
}


/*
|--------------------------------------------------------------------------
| CATEGORY HERO
|--------------------------------------------------------------------------
*/

.category-hero {

    min-height: 360px;

    display: grid;

    grid-template-columns: 1fr 1fr;

    background: #f7f5f1;

}


.category-hero-content {

    display: flex;

    flex-direction: column;

    justify-content: center;

    padding: 70px;

}


.back-home {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    width: fit-content;

    margin-bottom: 35px;

    color: #555;

    text-decoration: none;

    font-size: 13px;

    text-transform: uppercase;

    letter-spacing: 1px;

}


.back-home:hover {
    color: #000;
}


.category-label {

    font-size: 12px;

    letter-spacing: 3px;

    font-weight: 600;

    color: #777;

    margin-bottom: 12px;

}


.category-hero h1 {

    margin: 0;

    font-size: 52px;

    font-weight: 500;

    letter-spacing: -1px;

}


.category-hero p {

    max-width: 550px;

    margin: 18px 0 0;

    color: #666;

    line-height: 1.7;

}


.category-hero-image {

    height: 360px;

    overflow: hidden;

}


.category-hero-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

}


/*
|--------------------------------------------------------------------------
| PRODUCTS SECTION
|--------------------------------------------------------------------------
*/

.products-section {

    max-width: 1400px;

    margin: 0 auto;

    padding: 70px 35px;

}


/*
|--------------------------------------------------------------------------
| PRODUCTS HEADER
|--------------------------------------------------------------------------
*/

.products-header {

    display: flex;

    justify-content: space-between;

    align-items: flex-end;

    margin-bottom: 40px;

    border-bottom: 1px solid #eee;

    padding-bottom: 20px;

}


.products-label {

    display: block;

    font-size: 11px;

    letter-spacing: 3px;

    color: #888;

    margin-bottom: 8px;

}


.products-header h2 {

    margin: 0;

    font-size: 30px;

    font-weight: 500;

}


.product-count {

    color: #777;

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| PRODUCTS GRID
|--------------------------------------------------------------------------
*/

.products-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 30px 22px;

}


/*
|--------------------------------------------------------------------------
| PRODUCT CARD
|--------------------------------------------------------------------------
*/

.product-card {

    min-width: 0;

    background: #fff;

}


/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE
|--------------------------------------------------------------------------
*/

.product-image-wrapper {

    position: relative;

    display: block;

    width: 100%;

    aspect-ratio: 3 / 4;

    background: #f5f5f5;

    overflow: hidden;

}


.product-image {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform .5s ease;

}


.product-image-wrapper:hover
.product-image {

    transform: scale(1.04);

}


/*
|--------------------------------------------------------------------------
| NO IMAGE
|--------------------------------------------------------------------------
*/

.no-image {

    width: 100%;

    height: 100%;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    gap: 10px;

    color: #aaa;

}


.no-image :deep(svg) {
    font-size: 35px;
}


.no-image span {

    font-size: 11px;

    letter-spacing: 2px;

}


/*
|--------------------------------------------------------------------------
| BADGES
|--------------------------------------------------------------------------
*/

.sale-badge,
.new-badge {

    position: absolute;

    top: 12px;

    padding: 6px 10px;

    font-size: 10px;

    letter-spacing: 1px;

    font-weight: 700;

}


.sale-badge {

    left: 12px;

    background: #111;

    color: #fff;

}


.new-badge {

    right: 12px;

    background: #fff;

    color: #111;

}


/*
|--------------------------------------------------------------------------
| PRODUCT DETAILS
|--------------------------------------------------------------------------
*/

.product-details {

    padding-top: 15px;

}


.product-category {

    display: block;

    font-size: 10px;

    color: #999;

    text-transform: uppercase;

    letter-spacing: 1.5px;

    margin-bottom: 6px;

}


.product-name {

    display: block;

    color: #111;

    text-decoration: none;

    font-size: 15px;

    font-weight: 500;

    line-height: 1.4;

}


.product-name:hover {

    text-decoration: underline;

}


/*
|--------------------------------------------------------------------------
| PRICE
|--------------------------------------------------------------------------
*/

.product-price {

    display: flex;

    gap: 9px;

    align-items: center;

    margin-top: 9px;

    font-size: 14px;

}


.sale-price {

    font-weight: 700;

}


.old-price {

    color: #999;

    text-decoration: line-through;

}


/*
|--------------------------------------------------------------------------
| STOCK
|--------------------------------------------------------------------------
*/

.stock-status {

    margin-top: 8px;

    font-size: 11px;

}


.in-stock {

    color: #22863a;

    display: inline-flex;

    align-items: center;

    gap: 5px;

}


.out-stock {

    color: #c62828;

}


/*
|--------------------------------------------------------------------------
| ADD TO BAG
|--------------------------------------------------------------------------
*/

.add-to-bag {

    width: 100%;

    margin-top: 13px;

    height: 43px;

    border: 1px solid #111;

    background: #111;

    color: #fff;

    cursor: pointer;

    font-size: 11px;

    font-weight: 600;

    letter-spacing: 1.5px;

    transition:
        background .25s ease,
        color .25s ease;

}


.add-to-bag:hover:not(:disabled) {

    background: #fff;

    color: #111;

}


.add-to-bag:disabled {

    opacity: .5;

    cursor: not-allowed;

}


.add-to-bag > span {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

}


/*
|--------------------------------------------------------------------------
| SPINNER
|--------------------------------------------------------------------------
*/

.spinner {

    width: 14px;

    height: 14px;

    border: 2px solid rgba(255,255,255,.4);

    border-top-color: #fff;

    border-radius: 50%;

    animation: spin .7s linear infinite;

}


@keyframes spin {

    to {
        transform: rotate(360deg);
    }

}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-products {

    min-height: 350px;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    text-align: center;

    border: 1px solid #eee;

}


.empty-products :deep(svg) {

    font-size: 45px;

    color: #aaa;

}


.empty-products h2 {

    margin: 15px 0 5px;

    font-size: 22px;

}


.empty-products p {

    margin: 0 0 20px;

    color: #777;

}


.browse-button {

    display: inline-flex;

    align-items: center;

    padding: 12px 22px;

    background: #111;

    color: #fff;

    text-decoration: none;

    font-size: 12px;

    letter-spacing: 1px;

    text-transform: uppercase;

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.pagination {

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 7px;

    margin-top: 55px;

}


.pagination button {

    min-width: 38px;

    height: 38px;

    padding: 0 10px;

    border: 1px solid #ddd;

    background: #fff;

    color: #333;

    cursor: pointer;

}


.pagination button:hover:not(:disabled) {

    border-color: #111;

}


.pagination button.active {

    background: #111;

    color: #fff;

    border-color: #111;

}


.pagination button:disabled {

    opacity: .4;

    cursor: not-allowed;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 1100px) {

    .products-grid {

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

    }

}


@media (max-width: 800px) {

    .category-hero {

        grid-template-columns: 1fr;

    }


    .category-hero-content {

        padding: 50px 25px;

    }


    .category-hero h1 {

        font-size: 40px;

    }


    .category-hero-image {

        height: 300px;

    }


    .products-section {

        padding: 50px 20px;

    }


    .products-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 25px 15px;

    }

}


@media (max-width: 500px) {

    .products-grid {

        grid-template-columns: 1fr 1fr;

        gap: 20px 10px;

    }


    .products-header {

        align-items: flex-start;

        flex-direction: column;

        gap: 10px;

    }


    .product-name {

        font-size: 13px;

    }


    .add-to-bag {

        font-size: 9px;

        letter-spacing: 1px;

    }

}

</style>