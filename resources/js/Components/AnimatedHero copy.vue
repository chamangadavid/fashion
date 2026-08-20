<!-- resources\js\Components\AnimatedHero.vue -->
<template>

    <section class="hero">

        <!-- Hero background -->

        <div class="hero-image"></div>

        <!-- Dark/soft overlay -->

        <div class="hero-overlay"></div>


        <!-- Hero content -->

        <div class="hero-content">

            <p class="hero-small">
                FROM OUR STORE
            </p>

            <h1>
                FASHION
            </h1>

            <p class="hero-subtitle">
                Fresh from our farm to your table
            </p>

            <a
                href="#products"
                class="hero-button"
            >
                EXPLORE OUR PRODUCTS
            </a>

        </div>


        <!-- Slider dots -->

        <div class="slider-dots">

            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>

        </div>

    </section>

</template>


<style scoped>

.hero {
    position: relative;

    width: 100%;

    height: 440px;

    overflow: hidden;

    background: #ddd;
}


/* =========================================
   BACKGROUND IMAGE
========================================= */

.hero-image {

    position: absolute;

    inset: 0;

    background-image:
        url('/assets/bg.png');

    background-size: cover;

    background-position: center;

    transform: scale(1.02);

    animation: heroZoom 12s ease-in-out infinite alternate;

}


/* =========================================
   OVERLAY
========================================= */

.hero-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,0.02),
            rgba(0,0,0,0.08)
        );

}


/* =========================================
   CONTENT
========================================= */

.hero-content {

    position: absolute;

    inset: 0;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

    color: white;

    padding-top: 20px;

}


/* Small text */

.hero-small {

    font-size: 15px;

    letter-spacing: 6px;

    margin: 0 0 0 6px;

    text-transform: uppercase;

}


/* Main logo */

.hero-content h1 {

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: clamp(70px, 10vw, 125px);

    font-weight: 300;

    letter-spacing: 8px;

    line-height: 0.9;

    margin: 10px 0;

    text-shadow:
        0 2px 20px rgba(0,0,0,0.15);

}


/* Subtitle */

.hero-subtitle {

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 25px;

    font-weight: 300;

    margin: 10px 0 25px;

}


/* =========================================
   BUTTON
========================================= */

.hero-button {

    border: 1px solid rgba(255,255,255,0.85);

    padding: 12px 28px;

    color: white;

    text-decoration: none;

    font-size: 9px;

    letter-spacing: 2px;

    text-transform: uppercase;

    transition:
        background 0.3s ease,
        color 0.3s ease;

}


.hero-button:hover {

    background: white;

    color: #222;

}


/* =========================================
   SLIDER DOTS
========================================= */

.slider-dots {

    position: absolute;

    bottom: 25px;

    left: 0;
    right: 0;

    display: flex;

    justify-content: center;

    gap: 12px;

}


.dot {

    width: 6px;
    height: 6px;

    border-radius: 50%;

    border: 1px solid white;

}


.dot.active {

    background: white;

}


/* =========================================
   ANIMATION
========================================= */

@keyframes heroZoom {

    from {
        transform: scale(1);
    }

    to {
        transform: scale(1.07);
    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 768px) {

    .hero {
        height: 500px;
    }

    .hero-content h1 {
        font-size: 65px;
        letter-spacing: 3px;
    }

    .hero-small {
        font-size: 10px;
        letter-spacing: 4px;
    }

    .hero-subtitle {
        font-size: 18px;
    }

}

</style>