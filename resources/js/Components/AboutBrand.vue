```vue
<script setup>
import { Link } from "@inertiajs/vue3";

const fashionImages = [
    {
        image: "/assets/about-brand/fashion-1.jpg",
        alt: "Elegant black woman wearing a white fashion dress",
        class: "image-gold",
    },
    {
        image: "/assets/about-brand/fashion-2.jpg",
        alt: "Modern women's pink fashion collection",
        class: "image-teal",
    },
    {
        image: "/assets/about-brand/fashion-3.jpg",
        alt: "Elegant green women's fashion",
        class: "image-teal",
    },
    {
        image: "/assets/about-brand/fashion-4.jpg",
        alt: "Modern women's evening fashion",
        class: "image-gold",
    },
];
</script>

<template>

    <section class="about-brand">

        <div class="about-brand-container">

            <!-- =========================================
                 TOP CONTENT
            ========================================== -->

            <div class="about-brand-top">

                <!-- =====================================
                     LEFT: BRAND INFORMATION
                ====================================== -->

                <div class="about-brand-content">

                    <h2 class="about-brand-title">
                        About the Brand
                    </h2>

                    <p>
                        At Seraphine Styles, fashion is more than what you wear —
                        it’s how you carry yourself, the confidence you exude,
                        and the story your outfit tells.
                    </p>

                    <p>
                        We are a luxury modest fashion brand dedicated to creating
                        timeless pieces that combine elegance, culture, and individuality.
                    </p>

                    <p>
                        Our collections are designed for the modern woman who values
                        sophistication without compromising on comfort. From ready-to-
                        wear essentials to bespoke creations for life’s most memorable
                        occasions, every piece is crafted with precision, passion, and
                        purpose.
                    </p>

                    <p>
                        What makes us unique is our commitment to you. Whether it’s a
                        wedding gown that makes you feel radiant, a bubu gown that flows
                        with effortless grace,
                    </p>

                </div>


                <!-- =====================================
                     RIGHT: FASHION IMAGE GRID
                ====================================== -->

                <div class="fashion-grid">

                    <div
                        v-for="item in fashionImages"
                        :key="item.image"
                        class="fashion-grid-item"
                        :class="item.class"
                    >

                        <img
                            :src="item.image"
                            :alt="item.alt"
                            class="fashion-image"
                        />

                    </div>

                </div>

            </div>


            <!-- =========================================
                 BRAND CONTINUATION
            ========================================== -->

            <!-- <div class="about-brand-bottom">

                <p>
                    or a custom design made exclusively for your vision, we believe
                    your clothing should celebrate your identity while elevating your
                    presence. At Seraphine Styles, every stitch, fabric, and detail is
                    chosen with care. We don’t just design clothes — we design
                    experiences that leave lasting impressions. Our mission is to make
                    you feel confident, refined, and unforgettable, no matter the occasion.
                </p>

                <p>
                    Our designs are inspired by women who value quality, elegance, and
                    attention to detail. Whether it’s a piece for everyday wear, a special
                    occasion, or a custom creation that embodies your personal style, we
                    ensure every garment is meticulously crafted to meet the highest
                    standards. We embrace sustainability and conscious design, choosing
                    fabrics and processes that are both ethical and luxurious. We invite
                    you to explore our collections, experience the art of tailoring, and
                    discover how modest fashion can be empowering, versatile, and timeless.
                </p>

            </div> -->


            <!-- =========================================
                 OPTIONAL CTA
            ========================================== -->

            <div class="about-brand-action">

                <Link
                    href="/about"
                    class="about-brand-button"
                >
                    Discover Our Story
                </Link>

            </div>

        </div>

    </section>

</template>


<style scoped>

/* =========================================
   MAIN SECTION
========================================= */

.about-brand {

    width: 100%;

    background: #ffffff;

    padding: 45px 0 50px;

    color: #222222;

}


/* =========================================
   MAIN CONTAINER
========================================= */

.about-brand-container {

    width: min(1050px, calc(100% - 50px));

    margin: 0 auto;

}


/* =========================================
   TOP TWO-COLUMN AREA
========================================= */

.about-brand-top {

    display: grid;

    grid-template-columns: 1fr 400px;

    gap: 45px;

    align-items: start;

}


/* =========================================
   LEFT CONTENT
========================================= */

.about-brand-content {

    padding-top: 2px;

}


.about-brand-title {

    margin: 0 0 18px;

    color: #075d5d;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 31px;

    line-height: 1.15;

    font-weight: 700;

}


.about-brand-content p {

    margin: 0 0 16px;

    color: #292929;

    font-size: 13px;

    line-height: 1.45;

    font-weight: 400;

}


.about-brand-content p:last-child {

    margin-bottom: 0;

}


/* =========================================
   IMAGE GRID
========================================= */

.fashion-grid {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 7px;

}


/* =========================================
   IMAGE BOX
========================================= */

.fashion-grid-item {

    width: 100%;

    height: 170px;

    overflow: hidden;

    background: #eeeeee;

    border: 3px solid transparent;

}


.image-gold {

    border-color: #b89545;

    border-radius: 17px 0 0 0;

}


.image-teal {

    border-color: #0a6666;

    border-radius: 0 17px 0 0;

}


.fashion-grid-item:nth-child(3) {

    border-radius: 0 0 0 17px;

}


.fashion-grid-item:nth-child(4) {

    border-radius: 0 0 17px 0;

}


/* =========================================
   IMAGE
========================================= */

.fashion-image {

    width: 100%;

    height: 100%;

    display: block;

    object-fit: cover;

    transition:
        transform 0.5s ease,
        filter 0.5s ease;

}


.fashion-grid-item:hover .fashion-image {

    transform: scale(1.05);

    filter: brightness(0.95);

}


/* =========================================
   BOTTOM TEXT
========================================= */

.about-brand-bottom {

    width: 100%;

    margin-top: 12px;

}


.about-brand-bottom p {

    margin: 0 0 17px;

    color: #292929;

    font-size: 13px;

    line-height: 1.45;

}


.about-brand-bottom p:last-child {

    margin-bottom: 0;

}


/* =========================================
   CTA
========================================= */

/* .about-brand-action {

    margin-top: 25px;

} */


.about-brand-button {

    display: inline-block;

    padding: 9px 19px;

    background: #075d5d;

    color: #ffffff;

    font-size: 11px;

    text-decoration: none;

    border-radius: 2px;

    transition:
        background 0.25s ease,
        transform 0.25s ease;

}


.about-brand-button:hover {

    background: #064747;

    transform: translateY(-1px);

}


/* =========================================
   TABLET
========================================= */

@media (max-width: 900px) {

    .about-brand-top {

        grid-template-columns: 1fr 350px;

        gap: 30px;

    }


    .about-brand-title {

        font-size: 27px;

    }


    .about-brand-content p,
    .about-brand-bottom p {

        font-size: 12px;

    }


    .fashion-grid-item {

        height: 145px;

    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 700px) {

    .about-brand {

        padding: 35px 0 40px;

    }


    .about-brand-container {

        width: calc(100% - 32px);

    }


    .about-brand-top {

        display: flex;

        flex-direction: column;

        gap: 25px;

    }


    .about-brand-content {

        width: 100%;

    }


    .about-brand-title {

        font-size: 26px;

        margin-bottom: 15px;

    }


    .about-brand-content p,
    .about-brand-bottom p {

        font-size: 12px;

        line-height: 1.55;

    }


    .fashion-grid {

        width: 100%;

        grid-template-columns: 1fr 1fr;

        gap: 6px;

    }


    .fashion-grid-item {

        height: 180px;

    }


    .about-brand-bottom {

        margin-top: 20px;

    }


    .about-brand-action {

        margin-top: 22px;

    }

}


/* =========================================
   SMALL MOBILE
========================================= */

@media (max-width: 450px) {

    .fashion-grid-item {

        height: 135px;

    }


    .about-brand-title {

        font-size: 24px;

    }


    .about-brand-content p,
    .about-brand-bottom p {

        font-size: 11px;

    }

}

</style>
```
