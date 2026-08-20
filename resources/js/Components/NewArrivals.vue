```vue
<script setup>
import { Link } from "@inertiajs/vue3";

/*
|--------------------------------------------------------------------------
| New Arrival Products
|--------------------------------------------------------------------------
*/

const products = [
    {
        id: 1,
        name: "Chic Checkered Floral Jacket",
        price: "K84.99",
        image: "/assets/new-arrivals/jacket.jpg",
        colors: [
            "#d9d9d9",
        ],
        href: "/products/chic-checkered-floral-jacket",
    },

    {
        id: 2,
        name: "Yolk Pattern Crew Neck Sweater – Cozy Fall Knit",
        price: "K59.99",
        image: "/assets/new-arrivals/white-sweater.jpg",
        colors: [
            "#f4f0e8",
        ],
        href: "/products/yolk-pattern-crew-neck-sweater",
    },

    {
        id: 3,
        name: "Casual & Classy Button-Up Long Sleeve Coat",
        price: "K55.99",
        image: "/assets/new-arrivals/beige-coat.jpg",
        colors: [
            "#ded2c2",
            "#222222",
            "#8d7868",
            "#8d1d29",
        ],
        href: "/products/casual-classy-button-up-coat",
    },

    {
        id: 4,
        name: "Oversized Chunky Knit Mock Neck Sweater",
        price: "K54.99",
        image: "/assets/new-arrivals/red-sweater.jpg",
        colors: [
            "#8d1d29",
            "#6f5143",
            "#d9d1c6",
            "#ece5da",
        ],
        extraColors: "+4",
        href: "/products/oversized-chunky-knit-sweater",
    },

    {
        id: 5,
        name: "Icon Openwork V-Neck Sweater",
        price: "K45.99",
        image: "/assets/new-arrivals/beige-vneck.jpg",
        colors: [
            "#ded6ca",
            "#222222",
            "#9ba8b1",
            "#b75e4e",
        ],
        extraColors: "+2",
        href: "/products/icon-openwork-v-neck-sweater",
    },

    {
        id: 6,
        name: "Turtleneck Dropped Shoulder Sweater",
        price: "K49.99",
        image: "/assets/new-arrivals/black-sweater.jpg",
        colors: [
            "#242424",
            "#9aa9b6",
            "#a52232",
        ],
        href: "/products/turtleneck-dropped-shoulder-sweater",
    },
];
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

                <h2 class="new-arrivals-title">
                    New Arrivals
                </h2>

            </div>


            <!-- =====================================
                 PRODUCT GRID
            ====================================== -->

            <div class="products-grid">

                <Link
                    v-for="product in products"
                    :key="product.id"
                    :href="product.href"
                    class="product-card"
                >

                    <!-- =================================
                         PRODUCT IMAGE
                    ================================== -->

                    <div class="product-image-wrapper">

                        <img
                            :src="product.image"
                            :alt="product.name"
                            class="product-image"
                        />

                    </div>


                    <!-- =================================
                         PRODUCT INFORMATION
                    ================================== -->

                    <div class="product-information">

                        <h3 class="product-name">
                            {{ product.name }}
                        </h3>


                        <p class="product-price">
                            {{ product.price }}
                        </p>


                        <!-- =============================
                             COLOR SWATCHES
                        ============================== -->

                        <div
                            v-if="product.colors?.length"
                            class="product-colors"
                        >

                            <span
                                v-for="(color, index) in product.colors"
                                :key="index"
                                class="color-swatch"
                                :style="{ backgroundColor: color }"
                            ></span>


                            <span
                                v-if="product.extraColors"
                                class="extra-colors"
                            >
                                {{ product.extraColors }}
                            </span>

                        </div>

                    </div>

                </Link>

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

    width: min(1045px, calc(100% - 70px));

    margin: 0 auto;

}


/* =========================================
   HEADER
========================================= */

.new-arrivals-header {

    margin-bottom: 30px;

}


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

    column-gap: 17px;

    row-gap: 38px;

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
   PRODUCT IMAGE
========================================= */

.product-image-wrapper {

    width: 100%;

    aspect-ratio: 1 / 1.13;

    overflow: hidden;

    background: #eeeeee;

}


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
   PRODUCT INFORMATION
========================================= */

.product-information {

    width: 100%;

    text-align: center;

    padding-top: 16px;

}


/* =========================================
   PRODUCT NAME
========================================= */

.product-name {

    width: 100%;

    margin: 0;

    color: #292929;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 11px;

    line-height: 1.4;

    font-weight: 400;

}


/* =========================================
   PRODUCT PRICE
========================================= */

.product-price {

    margin: 6px 0 11px;

    color: #292929;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 12px;

    line-height: 1.2;

    font-weight: 400;

}


/* =========================================
   COLOR SWATCHES
========================================= */

.product-colors {

    min-height: 17px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

}


/* =========================================
   COLOR CIRCLE
========================================= */

.color-swatch {

    width: 15px;

    height: 15px;

    display: block;

    flex: 0 0 15px;

    border-radius: 50%;

    border: 1px solid #dddddd;

    box-shadow:
        0 0 0 1px rgba(0, 0, 0, 0.02);

}


/* =========================================
   EXTRA COLORS
========================================= */

.extra-colors {

    margin-left: 1px;

    color: #333333;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 11px;

    line-height: 1;

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


    .product-information {

        padding-top: 11px;

    }


    .product-name {

        font-size: 10px;

        line-height: 1.35;

    }


    .product-price {

        font-size: 11px;

        margin-top: 5px;

        margin-bottom: 9px;

    }


    .color-swatch {

        width: 13px;

        height: 13px;

        flex-basis: 13px;

    }


    .product-colors {

        gap: 5px;

    }


    .extra-colors {

        font-size: 10px;

    }


    .view-more-wrapper {

        margin-top: 32px;

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

}

</style>
```
