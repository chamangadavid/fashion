```vue
<script setup>
import { Link } from "@inertiajs/vue3";

const products = [
    {
        id: 1,
        name: "Bubu Gowns",
        image: "/assets/featured/bubu-gown.jpg",
        sale: true,
        href: "/collections/bubu-gowns",
    },
    {
        id: 2,
        name: "Custom Made",
        image: "/assets/featured/custom-made.jpg",
        sale: true,
        href: "/custom-made",
    },
    {
        id: 3,
        name: "Corporate Wears",
        image: "/assets/featured/corporate-wear.jpg",
        sale: true,
        href: "/collections/corporate-wears",
    },
    {
        id: 4,
        name: "Aso oke Bridal Outfit",
        image: "/assets/featured/aso-oke.jpg",
        sale: true,
        href: "/collections/aso-oke",
    },
    {
        id: 5,
        name: "Wedding Gown",
        image: "/assets/featured/wedding-gown.jpg",
        sale: true,
        href: "/collections/wedding-gowns",
    },
     {
        id: 6,
        name: "Aso oke Bridal Outfit",
        image: "/assets/featured/aso-oke.jpg",
        sale: true,
        href: "/collections/aso-oke",
    },
];
</script>


<template>

    <section class="featured-products">

        <div class="featured-container">

            <!-- =====================================
                 TITLE
            ====================================== -->

            <h2 class="featured-title">
                Featured Products
            </h2>


            <!-- =====================================
                 PRODUCTS
            ====================================== -->

            <div class="featured-grid">

                <div
                    v-for="product in products"
                    :key="product.id"
                    class="featured-card"
                >

                    <Link
                        :href="product.href"
                        class="featured-image-wrapper"
                    >

                        <!-- Sale Badge -->

                        <span
                            v-if="product.sale"
                            class="sale-badge"
                        >
                            Sale
                        </span>


                        <img
                            :src="product.image"
                            :alt="product.name"
                            class="featured-image"
                        />

                    </Link>


                    <!-- Product Name -->

                    <Link
                        :href="product.href"
                        class="featured-name"
                    >
                        {{ product.name }}
                    </Link>


                    <!-- Add To Cart -->

                    <button
                        type="button"
                        class="add-cart-button"
                    >
                        Add to Cart
                    </button>

                </div>

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

    width: min(840px, calc(100% - 40px));

    margin: 0 auto;

}


/* =========================================
   TITLE
========================================= */

.featured-title {

    margin: 0 0 45px;

    text-align: center;

    color: #075d5d;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 31px;

    line-height: 1.2;

    font-weight: 700;

}


/* =========================================
   GRID
========================================= */

.featured-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 42px 18px;

}


/* =========================================
   CARD
========================================= */

.featured-card {

    min-width: 0;

}


/* =========================================
   IMAGE WRAPPER
========================================= */

.featured-image-wrapper {

    position: relative;

    display: block;

    width: 100%;

    aspect-ratio: 0.82 / 1;

    overflow: hidden;

    background: #eeeeee;

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
        transform 0.45s ease;

}


.featured-image-wrapper:hover
.featured-image {

    transform: scale(1.04);

}


/* =========================================
   SALE BADGE
========================================= */

.sale-badge {

    position: absolute;

    top: 0;

    left: 0;

    z-index: 2;

    padding: 5px 10px;

    background: #b49743;

    color: #ffffff;

    font-size: 11px;

    line-height: 1;

    font-weight: 400;

}


/* =========================================
   PRODUCT NAME
========================================= */

.featured-name {

    display: block;

    margin-top: 14px;

    color: #a28636;

    font-size: 13px;

    line-height: 1.3;

    font-weight: 700;

    text-decoration: none;

}


.featured-name:hover {

    text-decoration: underline;

}


/* =========================================
   ADD TO CART
========================================= */

.add-cart-button {

    width: 100%;

    margin-top: 11px;

    padding: 10px 8px;

    border: none;

    border-radius: 0;

    background: #075d5d;

    color: #ffffff;

    font-size: 13px;

    line-height: 1.2;

    font-weight: 700;

    cursor: pointer;

    transition:
        background 0.25s ease,
        transform 0.2s ease;

}


.add-cart-button:hover {

    background: #064848;

    transform: translateY(-1px);

}


/* =========================================
   TABLET
========================================= */

@media (max-width: 800px) {

    .featured-container {

        width: calc(100% - 30px);

    }


    .featured-grid {

        gap: 30px 12px;

    }


    .featured-title {

        font-size: 27px;

    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 600px) {

    .featured-products {

        padding: 30px 0 35px;

    }


    .featured-title {

        margin-bottom: 28px;

        font-size: 25px;

    }


    .featured-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 28px 10px;

    }


    .featured-name {

        font-size: 11px;

    }


    .add-cart-button {

        font-size: 11px;

        padding: 9px 5px;

    }

}

</style>
```
