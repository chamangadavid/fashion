<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({

    title: {
        type: String,
        required: true,
    },

    products: {
        type: Array,
        required: true,
    },

    viewMoreHref: {
        type: String,
        default: "#",
    },

});
</script>


<template>

    <section class="category-products">

        <div class="category-container">

            <!-- =====================================
                 SECTION TITLE
            ====================================== -->

            <h2 class="category-title">
                {{ title }}
            </h2>


            <!-- =====================================
                 PRODUCT GRID
            ====================================== -->

            <div class="category-grid">

                <Link
                    v-for="product in products"
                    :key="product.id"
                    :href="product.href || '#'"
                    class="category-card"
                >

                    <!-- =================================
                         IMAGE
                    ================================== -->

                    <div class="category-image-wrapper">

                        <!-- Sale -->

                        <span
                            v-if="product.sale"
                            class="category-sale"
                        >
                            Sale
                        </span>


                        <img
                            :src="product.image"
                            :alt="product.name"
                            class="category-image"
                        />

                    </div>


                    <!-- =================================
                         PRODUCT DETAILS
                    ================================== -->

                    <div class="category-details">

                        <h3 class="category-product-name">
                            {{ product.name }}
                        </h3>


                        <!-- Regular Price -->

                        <div
                            v-if="!product.originalPrice"
                            class="category-price"
                        >
                            {{ product.price }}
                        </div>


                        <!-- Sale Price -->

                        <div
                            v-else
                            class="category-sale-price"
                        >

                            <span class="original-price">
                                {{ product.originalPrice }}
                            </span>

                            <span class="now-price">
                                Now {{ product.price }}
                            </span>

                            <span
                                v-if="product.discount"
                                class="discount"
                            >
                                ({{ product.discount }} off)
                            </span>

                        </div>


                        <!-- =================================
                             COLOR SWATCHES
                        ================================== -->

                        <div
                            v-if="product.colors?.length"
                            class="category-colors"
                        >

                            <span
                                v-for="(color, index) in product.colors"
                                :key="index"
                                class="category-color"
                                :style="{ background: color }"
                            ></span>


                            <span
                                v-if="product.extraColors"
                                class="extra-colors"
                            >
                                +{{ product.extraColors }}
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
                    :href="viewMoreHref"
                    class="view-more"
                >
                    View More
                </Link>

            </div>

        </div>

    </section>

</template>


<style scoped>

/* =========================================
   SECTION
========================================= */

.category-products {

    width: 100%;

    background: #ffffff;

    padding: 30px 0 35px;

}


/* =========================================
   CONTAINER
========================================= */

.category-container {

    width: min(1070px, calc(100% - 70px));

    margin: 0 auto;

}


/* =========================================
   TITLE
========================================= */

.category-title {

    margin: 0 0 32px;

    color: #161616;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 31px;

    line-height: 1.2;

    font-weight: 400;

    letter-spacing: -0.3px;

}


/* =========================================
   GRID
========================================= */

.category-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 16px;

}


/* =========================================
   CARD
========================================= */

.category-card {

    display: block;

    min-width: 0;

    color: #222222;

    text-decoration: none;

}


/* =========================================
   IMAGE
========================================= */

.category-image-wrapper {

    position: relative;

    width: 100%;

    aspect-ratio: 1 / 1.08;

    overflow: hidden;

    background: #eeeeee;

}


.category-image {

    width: 100%;

    height: 100%;

    display: block;

    object-fit: cover;

    transition:
        transform 0.4s ease;

}


.category-card:hover
.category-image {

    transform: scale(1.035);

}


/* =========================================
   SALE BADGE
========================================= */

.category-sale {

    position: absolute;

    top: 0;

    right: 0;

    z-index: 2;

    padding: 6px 10px;

    background: #c77b50;

    color: #ffffff;

    font-size: 10px;

    line-height: 1;

}


/* =========================================
   DETAILS
========================================= */

.category-details {

    padding-top: 14px;

    text-align: center;

}


/* =========================================
   PRODUCT NAME
========================================= */

.category-product-name {

    margin: 0;

    color: #333333;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 400;

}


/* =========================================
   PRICE
========================================= */

.category-price {

    margin-top: 6px;

    color: #222222;

    font-size: 13px;

    line-height: 1.2;

}


/* =========================================
   SALE PRICE
========================================= */

.category-sale-price {

    margin-top: 6px;

    font-size: 12px;

    line-height: 1.3;

}


.original-price {

    margin-right: 5px;

    color: #777777;

    text-decoration: line-through;

}


.now-price {

    color: #d44b42;

}


.discount {

    margin-left: 3px;

    color: #d44b42;

}


/* =========================================
   COLORS
========================================= */

.category-colors {

    min-height: 22px;

    margin-top: 10px;

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 6px;

}


.category-color {

    width: 15px;

    height: 15px;

    flex: 0 0 15px;

    border-radius: 50%;

    border: 1px solid #d7d7d7;

}


.extra-colors {

    margin-left: 2px;

    color: #333333;

    font-size: 11px;

}


/* =========================================
   VIEW MORE
========================================= */

.view-more-wrapper {

    display: flex;

    justify-content: center;

    margin-top: 29px;

}


.view-more {

    color: #333333;

    font-size: 12px;

    text-decoration: underline;

    text-underline-offset: 3px;

    transition:
        color 0.25s ease;

}


.view-more:hover {

    color: #075d5d;

}


/* =========================================
   TABLET
========================================= */

@media (max-width: 850px) {

    .category-container {

        width: calc(100% - 40px);

    }


    .category-grid {

        gap: 12px;

    }


    .category-title {

        font-size: 27px;

    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 650px) {

    .category-products {

        padding: 25px 0 30px;

    }


    .category-container {

        width: calc(100% - 24px);

    }


    .category-title {

        margin-bottom: 23px;

        font-size: 23px;

    }


    .category-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 25px 10px;

    }


    .category-product-name {

        font-size: 10px;

    }


    .category-price {

        font-size: 11px;

    }


    .category-sale-price {

        font-size: 10px;

    }

}

</style>
```
