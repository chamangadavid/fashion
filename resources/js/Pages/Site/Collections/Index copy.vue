<!-- resources\js\Pages\Site\Collections\Index.vue -->
<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";


// =====================================================
// COLLECTION CATEGORIES
// =====================================================

const categories = [
    {
        name: "ALL",
        value: "all",
    },
    {
        name: "WOMEN",
        value: "women",
    },
    {
        name: "MEN",
        value: "men",
    },
    {
        name: "ACCESSORIES",
        value: "accessories",
    },
    {
        name: "BEAUTY",
        value: "beauty",
    },
];


// =====================================================
// COLLECTIONS
// =====================================================

const collections = [
    {
        id: 1,
        category: "women",
        title: "The Women's Edit",
        subtitle: "Effortless silhouettes, timeless pieces and contemporary styles designed for every moment.",
        image: "/assets/collections/women/womens-main.png",
        href: "/clothing/women",
        featured: true,

        items: [
            {
                title: "Dresses",
                image: "/assets/collections/women/dresses.jpg", 
                href: "/shop/dresses",
                // href: "/clothing/women/dresses",
            },

            {
                title: "Tops & Blouses",
                image:
                    "/assets/collections/women/tops.jpg",
                href: "/clothing/women/tops",
            },

            {
                title: "Sets & Co-ords",
                image:
                    "/assets/collections/women/sets.jpg",
                href: "/clothing/women/sets",
            },

            {
                title: "Denim",
                image:
                    "/assets/collections/women/denim.jpg",
                href: "/clothing/denim",
            },
        ],
    },

    {
        id: 2,
        category: "men",

        title: "The Men's Collection",

        subtitle:
            "Modern essentials, refined tailoring and everyday pieces created for confident style.",

        image:
            "/assets/collections/men/mens-main.jpg",

        href: "/clothing/men",

        featured: true,

        items: [
            {
                title: "Jackets",
                image:
                    "/assets/collections/men/jackets.jpg",
                href: "/men/jackets",
            },
            {
                title: "Shirts",
                image:
                    "/assets/collections/men/shirts.jpg",
                href: "/men/shirts",
            },

            {
                title: "T-Shirts",
                image:
                    "/assets/collections/men/tshirts.jpg",
                href: "/men/t-shirts",
            },

            {
                title: "Trousers",
                image:
                    "/assets/collections/men/trousers.jpg",
                href: "/men/trousers",
            },

            
        ],
    },

    {
        id: 3,
        category: "accessories",

        title: "Accessories",

        subtitle:
            "Complete your look with carefully selected accessories that make every outfit memorable.",

        image:
            "/assets/collections/accessories/accessories-main.jpg",

        href: "/accessories",

        featured: true,

        items: [
            {
                title: "Handbags",
                image:
                    "/assets/collections/accessories/handbags.jpg",
                href: "/accessories/handbags",
            },

            {
                title: "Shoes",
                image:
                    "/assets/collections/accessories/shoes.jpg",
                href: "/accessories/shoes",
            },

            {
                title: "Jewellery",
                image:
                    "/assets/collections/accessories/jewellery.jpg",
                href: "/accessories/jewellery",
            },

            {
                title: "Sunglasses",
                image:
                    "/assets/collections/accessories/sunglasses.jpg",
                href: "/accessories/sunglasses",
            },
        ],
    },

    {
        id: 4,
        category: "beauty",

        title: "Beauty & Fragrance",

        subtitle:
            "Discover fragrances, body care and beauty essentials created to complement your personal style.",

        image:
            "/assets/collections/beauty/beauty-main.jpg",

        href: "/accessories/beauty",

        featured: true,

        items: [
            {
                title: "Ladies' Perfumes",
                image:
                    "/assets/collections/beauty/ladies-perfume.jpg",
                href: "/accessories/perfumes/ladies",
            },

            {
                title: "Men's Perfumes",
                image:
                    "/assets/collections/beauty/mens-perfume.jpg",
                href: "/accessories/perfumes/men",
            },

            {
                title: "Body Lotions",
                image:
                    "/assets/collections/beauty/lotions.jpg",
                href: "/accessories/lotions",
            },

            {
                title: "Beauty Essentials",
                image:
                    "/assets/collections/beauty/essentials.jpg",
                href: "/accessories/beauty",
            },
        ],
    },
];
</script>


<template>

    <Head title="Our Collections" />


    <div class="collections-page">

        <!-- =================================================
             HEADER
        ================================================== -->

        <Header />


        <!-- =================================================
             HERO
        ================================================== -->

        <section class="collections-hero">

            <div class="hero-background"></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">
                    FASHION STYLES
                </span>

                <h1>
                    Our Collections
                </h1>

                <p>
                    Discover carefully curated styles designed
                    to express who you are.
                </p>

            </div>

        </section>


        <!-- =================================================
             INTRODUCTION
        ================================================== -->

        <section class="collections-intro">

            <span class="section-eyebrow">
                EXPLORE OUR WORLD
            </span>

            <h2>
                Designed for Every Expression
            </h2>

            <p>
                From everyday essentials to statement pieces,
                explore our collections for women, men,
                accessories and beauty.
            </p>

        </section>


        <!-- =================================================
             CATEGORY NAVIGATION
        ================================================== -->

        <div class="collection-navigation">

            <a
                v-for="category in categories"
                :key="category.value"
                :href="`#${category.value}`"
                class="collection-nav-link"
            >
                {{ category.name }}
            </a>

        </div>


        <!-- =================================================
             COLLECTION SECTIONS
        ================================================== -->

        <main class="collections-container">

            <section
                v-for="collection in collections"
                :key="collection.id"
                :id="collection.category"
                class="collection-section"
            >

                <!-- =================================================
                     COLLECTION HEADER
                ================================================== -->

                <div class="collection-heading">

                    <div>

                        <span class="collection-number">
                            0{{ collection.id }}
                        </span>

                        <h2>
                            {{ collection.title }}
                        </h2>

                    </div>

                    <p>
                        {{ collection.subtitle }}
                    </p>

                </div>


                <!-- =================================================
                     FEATURED IMAGE
                ================================================== -->

                <Link
                    :href="collection.href"
                    class="collection-feature"
                >

                    <img
                        :src="collection.image"
                        :alt="collection.title"
                    />

                    <div class="feature-overlay"></div>

                    <div class="feature-content">

                        <span>
                            DISCOVER
                        </span>

                        <h3>
                            {{ collection.title }}
                        </h3>

                        <div class="feature-arrow">
                            →
                        </div>

                    </div>

                </Link>


                <!-- =================================================
                     SUB COLLECTIONS
                ================================================== -->

                <div class="subcollections">

                    <Link
                        v-for="item in collection.items"
                        :key="item.title"
                        :href="item.href"
                        class="subcollection"
                    >

                        <div class="subcollection-image">

                            <img
                                :src="item.image"
                                :alt="item.title"
                            />

                            <div class="subcollection-overlay"></div>

                        </div>

                        <div class="subcollection-info">

                            <h3>
                                {{ item.title }}
                            </h3>

                            <span>
                                SHOP NOW →
                            </span>

                        </div>

                    </Link>

                </div>


                <!-- =================================================
                     DIVIDER
                ================================================== -->

                <div class="collection-divider"></div>

            </section>

        </main>


        <!-- =================================================
             FINAL CTA
        ================================================== -->

        <section class="collections-cta">

            <div class="cta-content">

                <span>
                    YOUR STYLE. YOUR STORY.
                </span>

                <h2>
                    Find Your Next Favourite
                </h2>

                <p>
                    Explore our complete range and discover
                    pieces made for your individual style.
                </p>

                <Link
                    href="/new-arrivals"
                    class="cta-button"
                >
                    SHOP NEW ARRIVALS
                </Link>

            </div>

        </section>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>

/* =====================================================
   PAGE
===================================================== */

.collections-page {

    width: 100%;

    background: #ffffff;

    color: #222;

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

    background-image: url("/assets/collections/accessories/collections-hero.jpg");

    background-size: cover;

    background-position: center;

    transform: scale(1.02);

}


.hero-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,.10),
            rgba(0,0,0,.42)
        );

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

    font-size: clamp(
        48px,
        7vw,
        85px
    );

    font-weight: 400;

    letter-spacing: -1px;

}


.hero-content p {

    max-width: 500px;

    margin: 18px auto 0;

    font-size: 12px;

    line-height: 1.7;

}


/* =====================================================
   INTRO
===================================================== */

.collections-intro {

    max-width: 680px;

    margin: 0 auto;

    padding: 75px 25px 45px;

    text-align: center;

}


.section-eyebrow {

    color: #a0833d;

    font-size: 8px;

    font-weight: 600;

    letter-spacing: 3px;

}


.collections-intro h2 {

    margin: 12px 0;

    color: #075c59;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 34px;

    font-weight: 500;

}


.collections-intro p {

    margin: 0 auto;

    max-width: 550px;

    color: #777;

    font-size: 11px;

    line-height: 1.8;

}


/* =====================================================
   CATEGORY NAVIGATION
===================================================== */

.collection-navigation {

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 35px;

    margin: 0 auto 70px;

    padding: 18px 20px;

    border-top: 1px solid #eeeeee;

    border-bottom: 1px solid #eeeeee;

}


.collection-nav-link {

    position: relative;

    color: #555;

    font-size: 9px;

    letter-spacing: .8px;

    text-decoration: none;

    transition:
        color .3s ease;

}


.collection-nav-link::after {

    content: "";

    position: absolute;

    left: 50%;

    bottom: -7px;

    width: 0;

    height: 1px;

    background: #075c59;

    transform: translateX(-50%);

    transition:
        width .3s ease;

}


.collection-nav-link:hover {

    color: #075c59;

}


.collection-nav-link:hover::after {

    width: 100%;

}


/* =====================================================
   COLLECTION CONTAINER
===================================================== */

.collections-container {

    max-width: 1150px;

    margin: 0 auto;

    padding: 0 25px;

}


/* =====================================================
   COLLECTION SECTION
===================================================== */

.collection-section {

    margin-bottom: 80px;

    scroll-margin-top: 30px;

}


/* =====================================================
   COLLECTION HEADING
===================================================== */

.collection-heading {

    display: grid;

    grid-template-columns: 1fr 1fr;

    align-items: end;

    gap: 50px;

    margin-bottom: 25px;

}


.collection-number {

    display: block;

    margin-bottom: 8px;

    color: #a0833d;

    font-size: 8px;

    letter-spacing: 2px;

}


.collection-heading h2 {

    margin: 0;

    color: #222;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 35px;

    font-weight: 400;

}


.collection-heading p {

    margin: 0;

    color: #777;

    font-size: 10px;

    line-height: 1.8;

}


/* =====================================================
   FEATURED IMAGE
===================================================== */

.collection-feature {

    position: relative;

    display: block;

    height: 470px;

    overflow: hidden;

    text-decoration: none;

}


.collection-feature img {

    width: 100%;

    height: 100%;

    display: block;

    object-fit: cover;

    transition:
        transform .8s ease;

}


.collection-feature:hover img {

    transform: scale(1.045);

}


/* =====================================================
   FEATURE OVERLAY
===================================================== */

.feature-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to top,
            rgba(0,0,0,.55),
            transparent 60%
        );

}


.feature-content {

    position: absolute;

    left: 35px;

    right: 35px;

    bottom: 30px;

    color: white;

}


.feature-content span {

    font-size: 8px;

    letter-spacing: 2px;

}


.feature-content h3 {

    margin: 8px 0 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 30px;

    font-weight: 400;

}


.feature-arrow {

    position: absolute;

    right: 0;

    bottom: 0;

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 1px solid rgba(255,255,255,.7);

    border-radius: 50%;

    font-size: 17px;

    transition:
        background .3s ease,
        color .3s ease;

}


.collection-feature:hover
.feature-arrow {

    background: white;

    color: #222;

}


/* =====================================================
   SUB COLLECTIONS
===================================================== */

.subcollections {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 16px;

    margin-top: 18px;

}


.subcollection {

    display: block;

    color: #222;

    text-decoration: none;

}


.subcollection-image {

    position: relative;

    height: 260px;

    overflow: hidden;

    background: #f3f3f3;

}


.subcollection-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform .6s ease;

}


.subcollection:hover
.subcollection-image img {

    transform: scale(1.07);

}


.subcollection-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to bottom,
            transparent 60%,
            rgba(0,0,0,.25)
        );

}


/* =====================================================
   SUB COLLECTION INFO
===================================================== */

.subcollection-info {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    padding: 12px 2px;

}


.subcollection-info h3 {

    margin: 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 14px;

    font-weight: 500;

}


.subcollection-info span {

    color: #777;

    font-size: 7px;

    letter-spacing: .8px;

    white-space: nowrap;

    transition:
        color .25s ease;

}


.subcollection:hover
.subcollection-info span {

    color: #075c59;

}


/* =====================================================
   DIVIDER
===================================================== */

.collection-divider {

    width: 100%;

    height: 1px;

    margin-top: 55px;

    background: #eeeeee;

}


/* =====================================================
   CTA
===================================================== */

.collections-cta {

    position: relative;

    min-height: 360px;

    margin-top: 20px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        url("/assets/accessories-banner.jpg")
        center / cover
        no-repeat;

}


.collections-cta::before {

    content: "";

    position: absolute;

    inset: 0;

    background:
        rgba(0,0,0,.45);

}


.cta-content {

    position: relative;

    z-index: 2;

    max-width: 600px;

    padding: 30px;

    text-align: center;

    color: white;

}


.cta-content span {

    font-size: 8px;

    letter-spacing: 3px;

}


.cta-content h2 {

    margin: 12px 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 38px;

    font-weight: 400;

}


.cta-content p {

    max-width: 450px;

    margin: 0 auto 25px;

    font-size: 10px;

    line-height: 1.7;

}


.cta-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 12px 24px;

    border: 1px solid white;

    color: white;

    text-decoration: none;

    font-size: 8px;

    letter-spacing: 1.5px;

    transition:
        background .3s ease,
        color .3s ease;

}


.cta-button:hover {

    background: white;

    color: #222;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 900px) {

    .collections-container {

        padding: 0 18px;

    }


    .collection-heading {

        grid-template-columns: 1fr;

        gap: 15px;

    }


    .subcollections {

        grid-template-columns:
            repeat(2, 1fr);

    }


    .subcollection-image {

        height: 300px;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 650px) {

    .collections-hero {

        height: 390px;

    }


    .hero-content h1 {

        font-size: 48px;

    }


    .hero-content p {

        font-size: 10px;

    }


    .collections-intro {

        padding: 55px 20px 35px;

    }


    .collections-intro h2 {

        font-size: 28px;

    }


    .collection-navigation {

        justify-content: flex-start;

        overflow-x: auto;

        gap: 25px;

        margin-bottom: 50px;

        padding-left: 20px;

        scrollbar-width: none;

    }


    .collection-navigation::-webkit-scrollbar {

        display: none;

    }


    .collection-nav-link {

        flex: 0 0 auto;

    }


    .collection-heading h2 {

        font-size: 29px;

    }


    .collection-feature {

        height: 390px;

    }


    .feature-content {

        left: 20px;

        right: 20px;

        bottom: 22px;

    }


    .feature-content h3 {

        font-size: 25px;

    }


    .subcollections {

        grid-template-columns:
            repeat(2, 1fr);

        gap: 10px;

    }


    .subcollection-image {

        height: 230px;

    }


    .subcollection-info {

        display: block;

    }


    .subcollection-info h3 {

        font-size: 13px;

    }


    .subcollection-info span {

        display: block;

        margin-top: 5px;

    }


    .collections-cta {

        min-height: 320px;

    }


    .cta-content h2 {

        font-size: 30px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 400px) {

    .collection-feature {

        height: 340px;

    }


    .subcollection-image {

        height: 200px;

    }

}

</style>