<script setup>
import { Link, Head } from "@inertiajs/vue3";
import AppFooter from "@/Components/AppFooter.vue";
import Header from "@/Components/Header.vue";

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

    clothingCategories: {
        type: Array,
        default: () => [],
    },

    accessoryCategories: {
        type: Array,
        default: () => [],
    },

});


/*
|--------------------------------------------------------------------------
| CATEGORY IMAGE
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
| CATEGORY URL
|--------------------------------------------------------------------------
*/

const categoryHref = (category) => {

    if (category.group === "accessories") {
        return `/accessories/${category.slug}`;
    }

    return `/clothing/${category.slug}`;
};

</script>


<template>

    <Head title="Categories" />
<!-- =========================================
             HEADER
        ========================================== -->

        <Header />


    <div class="categories-page">

           
          <!-- =========================================
             PAGE HERO
        ========================================== -->

        <section class="collections-hero">

            <div class="hero-background"></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">

                    EXPLORE OUR COLLECTION

                </span>

                <h1>

                    Categories

                </h1>

                <p>

                     Discover our complete collection of clothing,
                    accessories and more.

                </p>

            </div>

        </section>


        <!-- =====================================================
             ALL CATEGORIES
        ====================================================== -->

        <section class="categories-section">

            <div class="categories-container">

                <div class="section-heading">

                    <span class="section-kicker">
                        SHOP BY CATEGORY
                    </span>

                    <h2>
                        All Categories
                    </h2>

                </div>


                <div
                    v-if="categories.length"
                    class="categories-grid"
                >

                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="categoryHref(category)"
                        class="category-card"
                    >

                        <div class="category-image-wrapper">

                            <img
                                :src="getImageUrl(category.image)"
                                :alt="category.name"
                                class="category-image"
                                loading="lazy"
                            />

                            <div class="category-overlay">

                                <span>
                                    Shop Now
                                </span>

                            </div>

                        </div>


                        <div class="category-info">

                            <span class="category-group">
                                {{
                                    category.group === "clothing"
                                        ? "Clothing"
                                        : "Accessories"
                                }}
                            </span>

                            <h3>
                                {{ category.name }}
                            </h3>

                        </div>

                    </Link>

                </div>


                <div
                    v-else
                    class="empty-state"
                >

                    <h3>
                        No categories available
                    </h3>

                    <p>
                        Please check back soon.
                    </p>

                </div>

            </div>

        </section>



        <!-- =====================================================
             CLOTHING
        ====================================================== -->

        <section
            v-if="clothingCategories.length"
            class="categories-section clothing-section"
        >

            <div class="categories-container">

                <div class="section-heading">

                    <span class="section-kicker">
                        FASHION COLLECTION
                    </span>

                    <h2>
                        Clothing
                    </h2>

                    <p>
                        Explore our clothing collections.
                    </p>

                </div>


                <div class="categories-grid">

                    <Link
                        v-for="category in clothingCategories"
                        :key="category.id"
                        :href="categoryHref(category)"
                        class="category-card"
                    >

                        <div class="category-image-wrapper">

                            <img
                                :src="getImageUrl(category.image)"
                                :alt="category.name"
                                class="category-image"
                                loading="lazy"
                            />

                            <div class="category-overlay">

                                <span>
                                    Shop Now
                                </span>

                            </div>

                        </div>


                        <div class="category-info">

                            <h3>
                                {{ category.name }}
                            </h3>

                        </div>

                    </Link>

                </div>

            </div>

        </section>



        <!-- =====================================================
             ACCESSORIES
        ====================================================== -->

        <section
            v-if="accessoryCategories.length"
            class="categories-section accessories-section"
        >

            <div class="categories-container">

                <div class="section-heading">

                    <span class="section-kicker">
                        COMPLETE YOUR STYLE
                    </span>

                    <h2>
                        Accessories
                    </h2>

                    <p>
                        Discover accessories to complete your look.
                    </p>

                </div>


                <div class="categories-grid">

                    <Link
                        v-for="category in accessoryCategories"
                        :key="category.id"
                        :href="categoryHref(category)"
                        class="category-card"
                    >

                        <div class="category-image-wrapper">

                            <img
                                :src="getImageUrl(category.image)"
                                :alt="category.name"
                                class="category-image"
                                loading="lazy"
                            />

                            <div class="category-overlay">

                                <span>
                                    Shop Now
                                </span>

                            </div>

                        </div>


                        <div class="category-info">

                            <h3>
                                {{ category.name }}
                            </h3>

                        </div>

                    </Link>

                </div>

            </div>

        </section>

  <!-- =========================================
             FOOTER
        ========================================== -->

        <AppFooter />


    </div>

</template>


<style scoped>

/* =========================================================
   PAGE
========================================================= */

.categories-page {

    width: 100%;

    background: #ffffff;

}


/* =====================================================
   HERO
===================================================== */

.collections-hero {

    position: relative;

    height: 440px;

    overflow: hidden;

    display: flex;

    align-items: center;

    justify-content: center;

}



.hero-background {

    position: absolute;

    inset: 0;

    background-image: url("/assets/contact.png");

    background-size: cover;

    background-position: center;

    transform: scale(1.02);

}



.hero-overlay {

    position: absolute;

    inset: 0;

    background:

        linear-gradient(to bottom,

            rgba(0, 0, 0, .10),

            rgba(0, 0, 0, .42));

}



.hero-content {

    position: relative;

    z-index: 2;

    max-width: 700px;

    padding: 30px;

    text-align: center;

    color: white;

}



.hero-eyebrow {

    display: block;

    margin-bottom: 15px;

    font-size: 9px;

    font-weight: 500;

    letter-spacing: 4px;

}



.hero-content h1 {

    margin: 0;

    font-family:

        Georgia,

        "Times New Roman",

        serif;

    font-size: clamp(48px,

            7vw,

            85px);

    font-weight: 400;

    letter-spacing: -1px;

}



.hero-content p {

    max-width: 500px;

    margin: 18px auto 0;

    font-size: 12px;

    line-height: 1.7;

}



/* =========================================================
   HERO
========================================================= */

/* .categories-hero {

    width: 100%;

    padding: 65px 20px 60px;

    background: #f9f6f1;

    text-align: center;
    

}


.categories-hero-container {

    max-width: 850px;

    margin: 0 auto;
    

}


.hero-kicker {

    display: block;

    margin-bottom: 10px;

    color: #777777;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 9px;

    letter-spacing: 2px;

    text-transform: uppercase;

}


.categories-hero h1 {

    margin: 0;

    color: #111111;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 45px;

    line-height: 1.15;

    font-weight: 500;

}


.categories-hero p {

    max-width: 500px;

    margin: 14px auto 0;

    color: #777777;

    font-size: 13px;

    line-height: 1.6;

} */


/* =========================================================
   SECTION
========================================================= */

.categories-section {

    width: 100%;

    padding: 55px 0 60px;

}


.clothing-section {

    background: #ffffff;

}


.accessories-section {

    background: #f9f6f1;

}


/* =========================================================
   CONTAINER
========================================================= */

.categories-container {

    width: min(
        1070px,
        calc(100% - 70px)
    );

    margin: 0 auto;

}


/* =========================================================
   SECTION HEADING
========================================================= */

.section-heading {

    text-align: center;

    margin-bottom: 35px;

}


.section-kicker {

    display: block;

    margin-bottom: 8px;

    color: #888888;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 9px;

    letter-spacing: 1.8px;

    text-transform: uppercase;

}


.section-heading h2 {

    margin: 0;

    color: #161616;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 32px;

    line-height: 1.2;

    font-weight: 500;

}


.section-heading p {

    margin: 9px 0 0;

    color: #888888;

    font-size: 11px;

}


/* =========================================================
   GRID
========================================================= */

.categories-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 18px;

}


/* =========================================================
   CARD
========================================================= */

.category-card {

    display: block;

    min-width: 0;

    color: #222222;

    text-decoration: none;

}


/* =========================================================
   IMAGE
========================================================= */

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
        transform 0.5s ease;

}


/* =========================================================
   OVERLAY
========================================================= */

.category-overlay {

    position: absolute;

    inset: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    background: rgba(0, 0, 0, 0.25);

    opacity: 0;

    transition:
        opacity 0.3s ease;

}


.category-overlay span {

    padding: 9px 16px;

    background: #ffffff;

    color: #222222;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 9px;

    letter-spacing: 1px;

    text-transform: uppercase;

}


.category-card:hover
.category-image {

    transform: scale(1.045);

}


.category-card:hover
.category-overlay {

    opacity: 1;

}


/* =========================================================
   INFO
========================================================= */

.category-info {

    padding: 13px 5px 0;

    text-align: center;

}


.category-group {

    display: block;

    margin-bottom: 4px;

    color: #999999;

    font-size: 8px;

    letter-spacing: 1px;

    text-transform: uppercase;

}


.category-info h3 {

    margin: 0;

    color: #333333;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 12px;

    line-height: 1.35;

    font-weight: 500;

    transition:
        color 0.25s ease;

}


.category-card:hover
.category-info h3 {

    color: #075d5d;

}


/* =========================================================
   EMPTY
========================================================= */

.empty-state {

    padding: 60px 20px;

    text-align: center;

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


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 850px) {

    .categories-container {

        width: calc(100% - 40px);

    }


    .categories-grid {

        gap: 14px;

    }


    .categories-hero h1 {

        font-size: 40px;

    }


    .section-heading h2 {

        font-size: 29px;

    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 650px) {

    .categories-hero {

        padding: 45px 18px 42px;

    }


    .categories-hero h1 {

        font-size: 34px;

    }


    .categories-hero p {

        font-size: 11px;

    }


    .categories-section {

        padding: 40px 0 45px;

    }


    .categories-container {

        width: calc(100% - 24px);

    }


    .section-heading {

        margin-bottom: 25px;

    }


    .section-heading h2 {

        font-size: 26px;

    }


    .categories-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 25px 10px;

    }


    .category-info {

        padding-top: 10px;

    }


    .category-info h3 {

        font-size: 10px;

    }

}

</style>