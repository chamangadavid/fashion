<script setup>
import { Link } from "@inertiajs/vue3";

const categories = [
    {
        name: "New Arrivals",
        image: "/assets/quick-shop/new-arrivals.jpg",
        href: "/new-arrivals",
    },
    {
        name: "Dresses",
        image: "/assets/quick-shop/dresses.jpg",
        href: "/clothing/women/dresses",
    },
    {
        name: "Denim Drops",
        image: "/assets/quick-shop/denim.jpg",
        href: "/clothing/denim",
    },
    {
        name: "Tops & Blouses",
        image: "/assets/quick-shop/tops-blouses.jpg",
        href: "/clothing/women/tops",
    },
    {
        name: "Sets / Co-ords",
        image: "/assets/quick-shop/sets.jpg",
        href: "/clothing/women/sets",
    },
    {
        name: "Plus Sizes / Curvy",
        image: "/assets/quick-shop/plus-size.jpg",
        href: "/clothing/women/plus-size",
    },
    {
        name: "Mega Sale",
        image: "/assets/quick-shop/mega-sale.jpg",
        href: "/sale",
    },
];
</script>


<template>

    <section class="quick-shop">

        <!-- ============================= -->
        <!-- INTRODUCTION -->
        <!-- ============================= -->

        <div class="quick-shop-intro">

            <p class="quick-shop-title">
                Shop what you love—faster and easier.
            </p>

            <p class="account-links">

                <Link href="/login">
                    Sign In
                </Link>

                <span class="separator">
                    or
                </span>

                <Link href="/register">
                    Create an Account
                </Link>

            </p>

        </div>


        <!-- ============================= -->
        <!-- CATEGORY ITEMS -->
        <!-- ============================= -->

        <div class="quick-shop-container">

            <div class="quick-shop-items">

                <Link
                    v-for="category in categories"
                    :key="category.name"
                    :href="category.href"
                    class="quick-shop-item"
                >

                    <!-- Image -->

                    <div class="quick-shop-image-wrapper">

                        <img
                            :src="category.image"
                            :alt="category.name"
                            class="quick-shop-image"
                        />

                    </div>


                    <!-- Label -->

                    <span class="quick-shop-label">
                        {{ category.name }}
                    </span>

                </Link>

            </div>

        </div>

    </section>

</template>


<style scoped>

/* =========================================
   MAIN SECTION
========================================= */

.quick-shop {

    width: 100%;

    background: #ffffff;

    border-bottom: 1px solid #eeeeee;

    padding-top: 13px;

    padding-bottom: 14px;

}


/* =========================================
   INTRODUCTION
========================================= */

.quick-shop-intro {

    text-align: center;

    margin-bottom: 14px;

}


/* Main text */

.quick-shop-title {

    margin: 0 0 3px;

    color: #333333;

    font-size: 8px;

    line-height: 1.4;

    font-weight: 400;

    letter-spacing: 0.15px;

}


/* =========================================
   ACCOUNT LINKS
========================================= */

.account-links {

    margin: 0;

    color: #777777;

    font-size: 8px;

    line-height: 1.4;

}


.account-links a {

    color: #555555;

    text-decoration: underline;

    text-underline-offset: 2px;

    transition:
        color 0.25s ease;

}


.account-links a:hover {

    color: #000000;

}


/* "or" */

.separator {

    margin: 0 3px;

    color: #999999;

}


/* =========================================
   CATEGORY CONTAINER
========================================= */

.quick-shop-container {

    width: 100%;

    display: flex;

    justify-content: center;

}


/* =========================================
   CATEGORY ITEMS
========================================= */

.quick-shop-items {

    display: flex;

    align-items: flex-start;

    justify-content: center;

    gap: 12px;

}


/* =========================================
   INDIVIDUAL CATEGORY
========================================= */

.quick-shop-item {

    width: 48px;

    display: flex;

    flex-direction: column;

    align-items: center;

    text-decoration: none;

    color: #333333;

}


/* =========================================
   IMAGE WRAPPER
========================================= */

.quick-shop-image-wrapper {

    width: 48px;

    height: 48px;

    overflow: hidden;

    background: #eeeeee;

    position: relative;

}


/* =========================================
   CATEGORY IMAGE
========================================= */

.quick-shop-image {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform 0.45s ease,
        filter 0.45s ease;

}


/* =========================================
   IMAGE HOVER
========================================= */

.quick-shop-item:hover
.quick-shop-image {

    transform: scale(1.08);

    filter: brightness(0.94);

}


/* =========================================
   CATEGORY LABEL
========================================= */

.quick-shop-label {

    width: 65px;

    margin-top: 6px;

    color: #333333;

    font-size: 7px;

    line-height: 1.15;

    text-align: center;

    font-weight: 400;

    transition:
        color 0.25s ease;

}


/* Label hover */

.quick-shop-item:hover
.quick-shop-label {

    color: #000000;

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 768px) {

    .quick-shop {

        padding-top: 12px;

        padding-bottom: 12px;

    }


    .quick-shop-items {

        width: 100%;

        justify-content: flex-start;

        overflow-x: auto;

        padding: 0 18px;

        gap: 14px;

        scrollbar-width: none;

    }


    .quick-shop-items::-webkit-scrollbar {

        display: none;

    }


    .quick-shop-item {

        flex: 0 0 48px;

    }

}

</style>