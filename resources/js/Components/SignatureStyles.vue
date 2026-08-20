<script setup>
const styles = [
    {
        title: "Ready to Wear",
        description:
            "Effortless, stylish pieces designed for everyday comfort without compromising elegance.",
        class: "style-ready",
    },
    {
        title: "Custom Order",
        description:
            "Tailored to your unique style — from fabric choice to final stitch, we bring your vision to life.",
        class: "style-custom",
    },
    {
        title: "Special Occasion Wear",
        description:
            "Graceful designs for weddings, parties, and events crafted to make every moment unforgettable.",
        class: "style-special",
    },
    {
        title: "Accessories",
        description:
            "Complete your look with timeless scarves, belts, and finishing touches made to pair perfectly.",
        class: "style-accessories",
    },
];
</script>

<template>
    <section class="signature-section">

        <!-- Background overlay -->
        <div class="signature-overlay"></div>

        <div class="signature-container">

            <!-- =====================================
                 TITLE
            ====================================== -->

            <div class="signature-heading">
                <h2>Signature Styles</h2>
            </div>


            <!-- =====================================
                 CONTENT
            ====================================== -->

            <div class="signature-content">

                <!-- =================================
                     LEFT STYLE CARDS
                ================================== -->

                <div class="signature-styles">

                    <div
                        v-for="style in styles"
                        :key="style.title"
                        class="signature-card"
                        :class="style.class"
                    >

                        <h3>
                            {{ style.title }}
                        </h3>

                        <p>
                            {{ style.description }}
                        </p>

                    </div>

                </div>


                <!-- =================================
                     RIGHT IMAGE COLLAGE
                ================================== -->

                <div class="signature-gallery">

                    <div class="gallery-image gallery-image-large">

                        <img
                            src="/assets/signature-styles/signature-main.jpg"
                            alt="Signature fashion style"
                        />

                    </div>

                    <div class="gallery-image gallery-image-small">

                        <img
                            src="/assets/signature-styles/signature-model.jpg"
                            alt="Custom fashion design"
                        />

                    </div>

                </div>

            </div>

        </div>

    </section>
</template>


<style scoped>

/* =====================================================
   MAIN SECTION
===================================================== */

.signature-section {

    position: relative;

    width: calc(100% - 72px);

    min-height: 645px;

    margin: 32px 36px 0;

    overflow: hidden;

    background-image:
        url("/assets/signature-styles/signature.jpg");

    background-size: cover;

    background-position: center;

    background-repeat: no-repeat;

    display: flex;

    align-items: center;

}


/* =====================================================
   DARK / SOFT OVERLAY
===================================================== */

.signature-overlay {

    position: absolute;

    inset: 0;

    background:
        rgba(52, 42, 40, 0.48);

    z-index: 1;

}


/* =====================================================
   MAIN CONTAINER
===================================================== */

.signature-container {

    position: relative;

    z-index: 2;

    width: 100%;

    max-width: 900px;

    margin: 0 auto;

    padding: 55px 30px 65px;

}


/* =====================================================
   HEADING
===================================================== */

.signature-heading {

    text-align: center;

    margin-bottom: 38px;

}


.signature-heading h2 {

    margin: 0;
    color: #fff;

    /* color: #00615f; */

    font-family: Georgia, "Times New Roman", serif;

    font-size: 38px;

    line-height: 1.1;

    font-weight: 700;

    letter-spacing: -0.5px;

}


/* =====================================================
   CONTENT
===================================================== */

.signature-content {

    display: grid;

    grid-template-columns: 1fr 1fr;

    align-items: center;

    gap: 65px;

}


/* =====================================================
   LEFT CARDS
===================================================== */

.signature-styles {

    display: flex;

    flex-direction: column;

    gap: 11px;

}


/* =====================================================
   INDIVIDUAL CARD
===================================================== */

.signature-card {

    min-height: 79px;

    padding: 12px 14px;

    border-radius: 11px;

    display: flex;

    flex-direction: column;

    justify-content: center;

    color: #ffffff;

    cursor: pointer;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        filter 0.3s ease;

}


.signature-card:hover {

    transform: translateX(7px);

    box-shadow:
        0 8px 20px rgba(0, 0, 0, 0.18);

    filter: brightness(1.05);

}


.signature-card h3 {

    margin: 0 0 5px;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 17px;

    line-height: 1.15;

    font-weight: 600;

}


.signature-card p {

    margin: 0;

    max-width: 330px;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 400;

}


/* =====================================================
   CARD COLORS
===================================================== */

.style-ready {

    background: #00615f;

}


.style-custom {

    background: #247d7b;

}


.style-special {

    background: #003c3b;

}


.style-accessories {

    background: #16716f;

}


/* =====================================================
   RIGHT IMAGE COLLAGE
===================================================== */

.signature-gallery {

    position: relative;

    width: 100%;

    height: 355px;

}


/* =====================================================
   GALLERY IMAGES
===================================================== */

.gallery-image {

    position: absolute;

    overflow: hidden;

    border-radius: 8px;

    background: #ddd;

}


.gallery-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;

    transition:
        transform 0.6s ease;

}


.gallery-image:hover img {

    transform: scale(1.05);

}


/* =====================================================
   LARGE IMAGE
===================================================== */

.gallery-image-large {

    top: 0;

    right: 0;

    width: 74%;

    height: 100%;

    border-radius: 0 9px 9px 0;

}


/* =====================================================
   SMALL IMAGE
===================================================== */

.gallery-image-small {

    left: 0;

    bottom: 0;

    width: 51%;

    height: 76%;

    border-radius: 8px 0 0 8px;

    z-index: 3;

}


/* =====================================================
   DESKTOP DECORATIVE BORDER
===================================================== */

.gallery-image-small::after {

    content: "";

    position: absolute;

    inset: 0;

    border-right: 1px solid rgba(255,255,255,0.2);

    pointer-events: none;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 900px) {

    .signature-section {

        width: calc(100% - 40px);

        margin-left: 20px;

        margin-right: 20px;

    }


    .signature-content {

        gap: 35px;

    }


    .signature-heading h2 {

        font-size: 32px;

    }


    .signature-gallery {

        height: 320px;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 700px) {

    .signature-section {

        width: calc(100% - 24px);

        min-height: auto;

        margin: 20px 12px 0;

        background-position: center;

    }


    .signature-container {

        padding: 40px 20px 45px;

    }


    .signature-heading {

        margin-bottom: 28px;

    }


    .signature-heading h2 {

        font-size: 29px;

    }


    .signature-content {

        grid-template-columns: 1fr;

        gap: 30px;

    }


    .signature-card {

        min-height: 75px;

    }


    .signature-card h3 {

        font-size: 16px;

    }


    .signature-card p {

        font-size: 10px;

    }


    .signature-gallery {

        height: 330px;

        max-width: 420px;

        margin: 0 auto;

    }


    .gallery-image-large {

        width: 75%;

    }


    .gallery-image-small {

        width: 52%;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 450px) {

    .signature-container {

        padding: 35px 15px 40px;

    }


    .signature-heading h2 {

        font-size: 26px;

    }


    .signature-gallery {

        height: 280px;

    }


    .signature-card {

        padding: 11px 13px;

    }

}

</style>