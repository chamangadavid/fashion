<script setup>

import { Head, Link } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";

const props = defineProps({

    collection: {
        type: Object,
        required: true,
    },

});

</script>


<template>

    <Head :title="collection.name" />

    <div class="collection-page">

        <Header />


        <!-- =================================================
             HERO
        ================================================== -->

        <section class="collection-hero">

            <div
                class="hero-background"
                :style="{
                    backgroundImage: collection.image
                        ? `url('${collection.image}')`
                        : 'none'
                }"
            ></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">
                    COLLECTION
                </span>

                <h1>
                    {{ collection.name }}
                </h1>

                <p v-if="collection.description">
                    {{ collection.description }}
                </p>

            </div>

        </section>


        <!-- =================================================
             COLLECTION INTRO
        ================================================== -->

        <section class="collection-intro">

            <span class="section-eyebrow">
                DISCOVER THE COLLECTION
            </span>

            <h2>
                {{ collection.name }}
            </h2>

            <p v-if="collection.description">
                {{ collection.description }}
            </p>

        </section>


        <!-- =================================================
             PRODUCTS
        ================================================== -->

        <main class="products-container">

            <div class="products-header">

                <div>

                    <span class="products-eyebrow">
                        SHOP THE COLLECTION
                    </span>

                    <h2>
                        {{ collection.products.length }}
                        {{ collection.products.length === 1 ? 'Item' : 'Items' }}
                    </h2>

                </div>

                <Link
                    href="/collections"
                    class="back-link"
                >
                    ← BACK TO COLLECTIONS
                </Link>

            </div>


            <!-- =================================================
                 PRODUCT GRID
            ================================================== -->

            <div
                v-if="collection.products.length"
                class="product-grid"
            >

                <Link
                    v-for="product in collection.products"
                    :key="product.id"
                    :href="`/products/${product.slug}`"
                    class="product-card"
                >

                    <div class="product-image">

                        <img
                            v-if="product.image"
                            :src="product.image"
                            :alt="product.name"
                        />

                        <div
                            v-else
                            class="image-placeholder"
                        >
                            NO IMAGE
                        </div>


                        <span
                            v-if="product.sale_price"
                            class="sale-badge"
                        >
                            SALE
                        </span>

                        <span
                            v-if="product.is_featured"
                            class="featured-badge"
                        >
                            FEATURED
                        </span>

                    </div>


                    <div class="product-info">

                        <h3>
                            {{ product.name }}
                        </h3>

                        <div class="price">

                            <span
                                v-if="product.sale_price"
                                class="sale-price"
                            >
                                {{ product.sale_price }}
                            </span>

                            <span
                                :class="{
                                    'old-price': product.sale_price
                                }"
                            >
                                {{ product.price }}
                            </span>

                        </div>

                    </div>

                </Link>

            </div>


            <!-- =================================================
                 EMPTY STATE
            ================================================== -->

            <div
                v-else
                class="empty-state"
            >

                <h2>
                    No Products Yet
                </h2>

                <p>
                    There are currently no products in this collection.
                </p>

                <Link
                    href="/collections"
                    class="shop-button"
                >
                    VIEW COLLECTIONS
                </Link>

            </div>

        </main>


        <!-- =================================================
             CTA
        ================================================== -->

        <section class="collection-cta">

            <div>

                <span>
                    FIND YOUR STYLE
                </span>

                <h2>
                    Explore More Collections
                </h2>

                <Link
                    href="/collections"
                    class="cta-button"
                >
                    VIEW ALL COLLECTIONS
                </Link>

            </div>

        </section>


        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>

.collection-page {
    width: 100%;
    background: #fff;
    color: #222;
}


/* =====================================================
   HERO
===================================================== */

.collection-hero {
    position: relative;
    height: 470px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    inset: 0;

    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;

    transform: scale(1.02);
}

.hero-overlay {
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,.10),
            rgba(0,0,0,.55)
        );
}

.hero-content {
    position: relative;
    z-index: 2;

    max-width: 750px;

    padding: 30px;

    text-align: center;

    color: white;
}

.hero-eyebrow {
    display: block;

    margin-bottom: 15px;

    font-size: 9px;

    letter-spacing: 4px;
}

.hero-content h1 {
    margin: 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: clamp(
        45px,
        7vw,
        80px
    );

    font-weight: 400;
}

.hero-content p {
    max-width: 550px;

    margin: 18px auto 0;

    font-size: 11px;

    line-height: 1.8;
}


/* =====================================================
   INTRO
===================================================== */

.collection-intro {
    max-width: 700px;

    margin: auto;

    padding: 75px 25px 50px;

    text-align: center;
}

.section-eyebrow,
.products-eyebrow {
    color: #a0833d;

    font-size: 8px;

    font-weight: 600;

    letter-spacing: 3px;
}

.collection-intro h2 {
    margin: 12px 0;

    color: #075c59;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 36px;

    font-weight: 400;
}

.collection-intro p {
    max-width: 550px;

    margin: auto;

    color: #777;

    font-size: 11px;

    line-height: 1.8;
}


/* =====================================================
   PRODUCTS
===================================================== */

.products-container {
    max-width: 1150px;

    margin: auto;

    padding: 0 25px 80px;
}

.products-header {
    display: flex;

    align-items: end;

    justify-content: space-between;

    margin-bottom: 30px;

    padding-bottom: 18px;

    border-bottom: 1px solid #eee;
}

.products-header h2 {
    margin: 7px 0 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 28px;

    font-weight: 400;
}

.back-link {
    color: #555;

    font-size: 8px;

    letter-spacing: 1px;

    text-decoration: none;
}

.back-link:hover {
    color: #075c59;
}


/* =====================================================
   PRODUCT GRID
===================================================== */

.product-grid {
    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;
}

.product-card {
    display: block;

    color: #222;

    text-decoration: none;
}

.product-image {
    position: relative;

    height: 330px;

    overflow: hidden;

    background: #f4f4f4;
}

.product-image img {
    width: 100%;
    height: 100%;

    display: block;

    object-fit: cover;

    transition:
        transform .6s ease;
}

.product-card:hover
.product-image img {
    transform: scale(1.06);
}

.image-placeholder {
    width: 100%;
    height: 100%;

    display: flex;

    align-items: center;
    justify-content: center;

    color: #999;

    font-size: 9px;

    letter-spacing: 1px;
}

.product-info {
    padding: 13px 2px;
}

.product-info h3 {
    margin: 0 0 8px;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 15px;

    font-weight: 500;
}

.price {
    display: flex;

    gap: 10px;

    align-items: center;

    font-size: 10px;
}

.sale-price {
    color: #075c59;
}

.old-price {
    color: #888;

    text-decoration: line-through;
}

.sale-badge,
.featured-badge {
    position: absolute;

    top: 12px;

    padding: 6px 9px;

    color: white;

    font-size: 7px;

    letter-spacing: 1px;
}

.sale-badge {
    left: 12px;

    background: #075c59;
}

.featured-badge {
    right: 12px;

    background: #a0833d;
}


/* =====================================================
   EMPTY
===================================================== */

.empty-state {
    padding: 80px 20px;

    text-align: center;

    border: 1px solid #eee;
}

.empty-state h2 {
    margin: 0 0 10px;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 30px;

    font-weight: 400;
}

.empty-state p {
    margin-bottom: 25px;

    color: #777;

    font-size: 11px;
}

.shop-button {
    display: inline-flex;

    padding: 12px 22px;

    background: #075c59;

    color: white;

    font-size: 8px;

    letter-spacing: 1.5px;

    text-decoration: none;
}


/* =====================================================
   CTA
===================================================== */

.collection-cta {
    min-height: 320px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    background:
        linear-gradient(
            rgba(0,0,0,.45),
            rgba(0,0,0,.45)
        ),
        url("/assets/accessories-banner.jpg")
        center / cover
        no-repeat;

    color: white;
}

.collection-cta span {
    font-size: 8px;

    letter-spacing: 3px;
}

.collection-cta h2 {
    margin: 12px 0 25px;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 35px;

    font-weight: 400;
}

.cta-button {
    display: inline-flex;

    padding: 12px 24px;

    border: 1px solid white;

    color: white;

    font-size: 8px;

    letter-spacing: 1.5px;

    text-decoration: none;
}

.cta-button:hover {
    background: white;

    color: #222;
}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 900px) {

    .product-grid {
        grid-template-columns:
            repeat(2, 1fr);
    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 600px) {

    .collection-hero {
        height: 390px;
    }

    .products-container {
        padding-left: 18px;
        padding-right: 18px;
    }

    .products-header {
        align-items: flex-start;

        flex-direction: column;

        gap: 15px;
    }

    .product-grid {
        grid-template-columns:
            repeat(2, 1fr);

        gap: 12px;
    }

    .product-image {
        height: 240px;
    }

    .product-info h3 {
        font-size: 13px;
    }

}

</style>