<script setup>

import { Link } from "@inertiajs/vue3";

import {
    ref,
    onMounted,
    onBeforeUnmount,
} from "vue";


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    categories: {

        type: Array,

        default: () => [],

    },

});


/*
|--------------------------------------------------------------------------
| CATEGORY URL
|--------------------------------------------------------------------------
*/

const categoryHref = (category) => {

    if (category.group === "clothing") {

        return `/clothing/${category.slug}`;

    }

    if (category.group === "accessories") {

        return `/accessories/${category.slug}`;

    }

    return `/categories/${category.slug}`;

};


/*
|--------------------------------------------------------------------------
| CATEGORY IMAGE
|--------------------------------------------------------------------------
*/

const categoryImage = (category) => {

    if (!category.image) {

        return "/assets/placeholder.jpg";

    }


    if (
        category.image.startsWith("http://") ||
        category.image.startsWith("https://")
    ) {

        return category.image;

    }


    /*
    |--------------------------------------------------------------------------
    | If image is already stored with /storage/
    |--------------------------------------------------------------------------
    */

    if (category.image.startsWith("/storage/")) {

        return category.image;

    }


    return `/storage/${category.image}`;

};


/*
|--------------------------------------------------------------------------
| CAROUSEL
|--------------------------------------------------------------------------
*/

const quickShopItems = ref(null);

let autoScrollTimer = null;


/*
|--------------------------------------------------------------------------
| NUMBER OF CATEGORIES
|--------------------------------------------------------------------------
*/

const categoryCount = () => {

    return props.categories.length;

};


/*
|--------------------------------------------------------------------------
| GET ITEM WIDTH
|--------------------------------------------------------------------------
|
| Calculates the actual width of one category + the gap.
|
*/

const getScrollAmount = () => {

    if (!quickShopItems.value) {

        return 0;

    }


    const item =
        quickShopItems.value.querySelector(
            ".quick-shop-item"
        );


    if (!item) {

        return 0;

    }


    const itemWidth = item.offsetWidth;


    const styles =
        window.getComputedStyle(
            quickShopItems.value
        );


    const gap =
        parseFloat(styles.gap) || 0;


    return itemWidth + gap;

};


/*
|--------------------------------------------------------------------------
| SCROLL TO NEXT CATEGORY
|--------------------------------------------------------------------------
*/

const scrollNext = () => {

    if (!quickShopItems.value) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Don't scroll if there aren't enough categories.
    |--------------------------------------------------------------------------
    */

    if (categoryCount() <= 1) {

        return;

    }


    const scrollAmount =
        getScrollAmount();


    if (!scrollAmount) {

        return;

    }


    const element =
        quickShopItems.value;


    const maxScroll =
        element.scrollWidth -
        element.clientWidth;


    /*
    |--------------------------------------------------------------------------
    | If we reached the end
    |--------------------------------------------------------------------------
    |
    | Smoothly return to the beginning.
    |
    */

    if (
        element.scrollLeft >=
        maxScroll - 2
    ) {

        element.scrollTo({

            left: 0,

            behavior: "smooth",

        });

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Move exactly ONE category.
    |--------------------------------------------------------------------------
    */

    element.scrollBy({

        left: scrollAmount,

        behavior: "smooth",

    });

};


/*
|--------------------------------------------------------------------------
| START AUTOMATIC SCROLL
|--------------------------------------------------------------------------
*/

const startAutoScroll = () => {

    stopAutoScroll();


    /*
    |--------------------------------------------------------------------------
    | Only start when there is something to scroll.
    |--------------------------------------------------------------------------
    */

    if (categoryCount() <= 1) {

        return;

    }


    autoScrollTimer = setInterval(() => {

        scrollNext();

    }, 3000);

};


/*
|--------------------------------------------------------------------------
| STOP AUTOMATIC SCROLL
|--------------------------------------------------------------------------
*/

const stopAutoScroll = () => {

    if (autoScrollTimer !== null) {

        clearInterval(autoScrollTimer);

        autoScrollTimer = null;

    }

};


/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/

onMounted(() => {

    /*
    |--------------------------------------------------------------------------
    | Small delay allows the DOM to calculate widths.
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {

        startAutoScroll();

    }, 800);

});


/*
|--------------------------------------------------------------------------
| UNMOUNT
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {

    stopAutoScroll();

});

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
        <!-- CATEGORY CONTAINER -->
        <!-- ============================= -->

        <div class="quick-shop-container">


            <!-- ============================= -->
            <!-- CATEGORY ITEMS -->
            <!-- ============================= -->

            <div
                v-if="categories.length"

                ref="quickShopItems"

                class="quick-shop-items"

                @mouseenter="stopAutoScroll"

                @mouseleave="startAutoScroll"
            >


                <Link
                    v-for="category in categories"

                    :key="category.id"

                    :href="categoryHref(category)"

                    class="quick-shop-item"
                >


                    <!-- ============================= -->
                    <!-- IMAGE -->
                    <!-- ============================= -->

                    <div class="quick-shop-image-wrapper">

                        <img
                            :src="categoryImage(category)"

                            :alt="category.name"

                            class="quick-shop-image"

                            loading="lazy"
                        />

                    </div>



                    <!-- ============================= -->
                    <!-- LABEL -->
                    <!-- ============================= -->

                    <span class="quick-shop-label">

                        {{ category.name }}

                    </span>


                </Link>


            </div>



            <!-- ============================= -->
            <!-- EMPTY STATE -->
            <!-- ============================= -->

            <div
                v-else

                class="quick-shop-empty"
            >

                No categories available.

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


/* =========================================
   MAIN TEXT
========================================= */

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


/* =========================================
   "OR"
========================================= */

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

    overflow: hidden;

}


/* =========================================
   CATEGORY ITEMS
========================================= */
.quick-shop-items {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;

    gap: 12px;

    /*
    |--------------------------------------------------------------------------
    | Display 5 categories
    |--------------------------------------------------------------------------
    */
    width: calc((48px * 5) + (12px * 4));

    max-width: 100%;

    overflow-x: auto;
    overflow-y: hidden;

    scroll-behavior: smooth;

    scrollbar-width: none;

    -ms-overflow-style: none;

    overscroll-behavior-x: contain;

    -webkit-overflow-scrolling: touch;
}




/* =========================================
   HIDE WEBKIT SCROLLBAR
========================================= */

.quick-shop-items::-webkit-scrollbar {

    display: none;

}


/* =========================================
   INDIVIDUAL CATEGORY
========================================= */

.quick-shop-item {

    width: 48px;

    flex: 0 0 48px;

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


/* =========================================
   LABEL HOVER
========================================= */

.quick-shop-item:hover
.quick-shop-label {

    color: #000000;

}


/* =========================================
   EMPTY STATE
========================================= */

.quick-shop-empty {

    color: #999999;

    font-size: 8px;

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 768px) {

    .quick-shop {

        padding-top: 12px;

        padding-bottom: 12px;

    }


    .quick-shop-container {

        justify-content: flex-start;

        overflow: hidden;

    }


    .quick-shop-items {

        width: 100%;

        max-width: none;

        justify-content: flex-start;

        overflow-x: auto;

        padding: 0 18px;

        gap: 14px;

        scrollbar-width: none;

        -webkit-overflow-scrolling: touch;

    }


    .quick-shop-items::-webkit-scrollbar {

        display: none;

    }


    .quick-shop-item {

        flex: 0 0 48px;

    }

}

</style>