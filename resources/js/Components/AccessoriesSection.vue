<script setup>
import { Link } from "@inertiajs/vue3";

const accessories = [
    {
        title: "Ladies' Perfumes",
        subtitle: "Elegant fragrances for every occasion",
        image: "/assets/accessories/ladies-perfume.jpg",
        href: "/accessories/perfumes/ladies",
        button: "Shop Ladies' Fragrance",
    },
    {
        title: "Men's Perfumes",
        subtitle: "Bold. Refined. Unforgettable.",
        image: "/assets/accessories/mens-perfume.jpg",
        href: "/accessories/perfumes/men",
        button: "Shop Men's Fragrance",
    },
    {
        title: "Ladies' Body Lotion",
        subtitle: "Soft, smooth & beautifully scented",
        image: "/assets/accessories/ladies-lotion.jpg",
        href: "/accessories/lotions/ladies",
        button: "Shop Ladies' Care",
    },
    {
        title: "Men's Body Lotion",
        subtitle: "Fresh skin. Clean confidence.",
        image: "/assets/accessories/mens-lotion.jpg",
        href: "/accessories/lotions/men",
        button: "Shop Men's Care",
    },
    {
        title: "Beauty Essentials",
        subtitle: "Complete your signature look",
        image: "/assets/accessories/beauty-essentials.jpg",
        href: "/accessories/beauty",
        button: "Explore Beauty",
    },
    {
        title: "Gift Collection",
        subtitle: "Thoughtful gifts, beautifully presented",
        image: "/assets/accessories/gift-collection.jpg",
        href: "/accessories/gifts",
        button: "Shop Gifts",
    },
];
</script>


<template>

    <section class="accessories-section">

        <!-- =========================================
             HEADER
        ========================================== -->

        <div class="accessories-header">

            <span class="accessories-eyebrow">
                COMPLETE YOUR LOOK
            </span>

            <h2>
                Beauty & Accessories
            </h2>

            <p>
                Discover beautiful fragrances, luxurious body care
                and finishing touches designed for her and for him.
            </p>

        </div>


        <!-- =========================================
             FEATURED BANNER
        ========================================== -->

        <div class="accessories-banner">

            <div class="banner-content">

                <span class="banner-small-title">
                    THE FINISHING TOUCH
                </span>

                <h3>
                    Fragrance is part<br>
                    of your signature.
                </h3>

                <p>
                    Discover scents and body care essentials
                    selected to complement your personal style.
                </p>

                <Link
                    href="/accessories"
                    class="banner-button"
                >
                    Explore Accessories
                </Link>

            </div>

            <div class="banner-image">

                <img
                    src="/assets/accessories/accessories-banner.jpg"
                    alt="Luxury perfumes and beauty accessories"
                />

            </div>

        </div>


        <!-- =========================================
             CATEGORY GRID
        ========================================== -->

        <div class="accessories-grid">

            <article
                v-for="item in accessories"
                :key="item.title"
                class="accessory-card"
            >

                <!-- Image -->

                <div class="accessory-image">

                    <img
                        :src="item.image"
                        :alt="item.title"
                    />

                    <div class="image-overlay"></div>

                    <span class="shop-label">
                        SHOP
                    </span>

                </div>


                <!-- Content -->

                <div class="accessory-content">

                    <h3>
                        {{ item.title }}
                    </h3>

                    <p>
                        {{ item.subtitle }}
                    </p>

                    <Link
                        :href="item.href"
                        class="accessory-link"
                    >
                        {{ item.button }}
                        <span>→</span>
                    </Link>

                </div>

            </article>

        </div>


        <!-- =========================================
             BOTTOM MESSAGE
        ========================================== -->

        <div class="accessories-bottom">

            <div class="bottom-line"></div>

            <p>
                BEAUTY • FRAGRANCE • SELF CARE • STYLE
            </p>

            <div class="bottom-line"></div>

        </div>

    </section>

</template>


<style scoped>

/* =====================================================
   MAIN SECTION
===================================================== */

.accessories-section {

    width: 100%;

    background: #f8f5f0;

    padding: 75px 6% 65px;

    box-sizing: border-box;

}


/* =====================================================
   HEADER
===================================================== */

.accessories-header {

    max-width: 650px;

    margin: 0 auto 48px;

    text-align: center;

}


.accessories-eyebrow {

    display: block;

    margin-bottom: 10px;

    color: #a0833d;

    font-size: 9px;

    font-weight: 600;

    letter-spacing: 2.5px;

}


.accessories-header h2 {

    margin: 0;

    color: #075c59;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 38px;

    line-height: 1.15;

    font-weight: 600;

}


.accessories-header p {

    max-width: 550px;

    margin: 15px auto 0;

    color: #666;

    font-size: 12px;

    line-height: 1.7;

}


/* =====================================================
   FEATURED BANNER
===================================================== */

.accessories-banner {

    position: relative;

    display: grid;

    grid-template-columns: 1fr 1fr;

    max-width: 1100px;

    min-height: 370px;

    margin: 0 auto 60px;

    overflow: hidden;

    background: #ded3c4;

}


/* =====================================================
   BANNER CONTENT
===================================================== */

.banner-content {

    display: flex;

    flex-direction: column;

    justify-content: center;

    padding: 55px;

    background: #e4d9ca;

    z-index: 2;

}


.banner-small-title {

    margin-bottom: 14px;

    color: #a0833d;

    font-size: 9px;

    font-weight: 600;

    letter-spacing: 2px;

}


.banner-content h3 {

    margin: 0;

    color: #075c59;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 35px;

    line-height: 1.15;

    font-weight: 600;

}


.banner-content p {

    max-width: 350px;

    margin: 18px 0 25px;

    color: #555;

    font-size: 11px;

    line-height: 1.7;

}


/* =====================================================
   BANNER BUTTON
===================================================== */

.banner-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    width: fit-content;

    padding: 11px 22px;

    background: #075c59;

    color: white;

    text-decoration: none;

    font-size: 10px;

    letter-spacing: .3px;

    transition:
        background .25s ease,
        transform .25s ease;

}


.banner-button:hover {

    background: #043f3d;

    transform: translateY(-2px);

}


/* =====================================================
   BANNER IMAGE
===================================================== */

.banner-image {

    position: relative;

    overflow: hidden;

}


.banner-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition: transform .7s ease;

}


.accessories-banner:hover
.banner-image img {

    transform: scale(1.04);

}


/* =====================================================
   ACCESSORIES GRID
===================================================== */

.accessories-grid {

    max-width: 1100px;

    margin: 0 auto;

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 25px;

}


/* =====================================================
   CARD
===================================================== */

.accessory-card {

    background: #fff;

    overflow: hidden;

    transition:
        transform .35s ease,
        box-shadow .35s ease;

}


.accessory-card:hover {

    transform: translateY(-6px);

    box-shadow:
        0 14px 30px rgba(40, 30, 20, .12);

}


/* =====================================================
   IMAGE
===================================================== */

.accessory-image {

    position: relative;

    height: 310px;

    overflow: hidden;

    background: #eee;

}


.accessory-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform .6s ease;

}


.accessory-card:hover
.accessory-image img {

    transform: scale(1.06);

}


/* =====================================================
   IMAGE OVERLAY
===================================================== */

.image-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to bottom,
            transparent 60%,
            rgba(0,0,0,.35)
        );

    pointer-events: none;

}


/* =====================================================
   SHOP LABEL
===================================================== */

.shop-label {

    position: absolute;

    top: 12px;

    left: 12px;

    padding: 6px 9px;

    background: rgba(255,255,255,.92);

    color: #075c59;

    font-size: 7px;

    font-weight: 600;

    letter-spacing: 1.5px;

}


/* =====================================================
   CARD CONTENT
===================================================== */

.accessory-content {

    padding: 18px 18px 20px;

    text-align: center;

}


.accessory-content h3 {

    margin: 0;

    color: #222;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 18px;

    font-weight: 600;

}


.accessory-content p {

    margin: 7px 0 14px;

    color: #777;

    font-size: 10px;

    line-height: 1.5;

}


/* =====================================================
   CARD LINK
===================================================== */

.accessory-link {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    color: #075c59;

    text-decoration: none;

    font-size: 9px;

    font-weight: 600;

    transition: gap .25s ease;

}


.accessory-link span {

    font-size: 14px;

}


.accessory-link:hover {

    gap: 11px;

}


/* =====================================================
   BOTTOM DECORATION
===================================================== */

.accessories-bottom {

    max-width: 900px;

    margin: 55px auto 0;

    display: flex;

    align-items: center;

    gap: 20px;

}


.accessories-bottom p {

    margin: 0;

    white-space: nowrap;

    color: #9b8250;

    font-size: 8px;

    letter-spacing: 2px;

}


.bottom-line {

    flex: 1;

    height: 1px;

    background: #cfc4b5;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 900px) {

    .accessories-section {

        padding-left: 4%;

        padding-right: 4%;

    }


    .accessories-grid {

        grid-template-columns: repeat(2, 1fr);

    }


    .accessories-banner {

        grid-template-columns: 1fr 1fr;

    }


    .banner-content {

        padding: 35px;

    }


    .banner-content h3 {

        font-size: 29px;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 650px) {

    .accessories-section {

        padding: 50px 18px;

    }


    .accessories-header {

        margin-bottom: 35px;

    }


    .accessories-header h2 {

        font-size: 29px;

    }


    .accessories-header p {

        font-size: 10px;

    }


    /* Banner becomes vertical */

    .accessories-banner {

        grid-template-columns: 1fr;

        min-height: auto;

    }


    .banner-content {

        padding: 35px 28px;

        order: 1;

    }


    .banner-content h3 {

        font-size: 27px;

    }


    .banner-image {

        height: 300px;

        order: 2;

    }


    /* Two-column cards */

    .accessories-grid {

        grid-template-columns: repeat(2, 1fr);

        gap: 12px;

    }


    .accessory-image {

        height: 220px;

    }


    .accessory-content {

        padding: 14px 8px 17px;

    }


    .accessory-content h3 {

        font-size: 14px;

    }


    .accessory-content p {

        font-size: 8px;

    }


    .accessory-link {

        font-size: 8px;

    }


    .accessories-bottom {

        gap: 10px;

    }


    .accessories-bottom p {

        font-size: 6px;

        letter-spacing: 1px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 400px) {

    .accessories-grid {

        grid-template-columns: 1fr;

    }


    .accessory-image {

        height: 300px;

    }


    .accessories-bottom {

        display: none;

    }

}

</style>