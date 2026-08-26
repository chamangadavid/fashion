<!-- resources\js\Components\AnimatedHero.vue -->
<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";



//Hero slides with images and directions for animation
const slides = [
   {
    image: "/assets/bg5.png",
    direction: "right",
  },
  {
    image: "/assets/bg-hero.png",
    direction: "right",
  },
 
  {
    image: "/assets/bg4.png",
    direction: "bottom",
  },
  {
    image: "/assets/bg.png",
    direction: "left",
  },
  {
    image: "/assets/bg2.png",
    direction: "right",
  },
  {
    image: "/assets/bg6.png",
    direction: "right",
  },
  {
    image: "/assets/bg3.png",
    direction: "top",
  },

];


// =====================================================
// CURRENT SLIDE
// =====================================================

const currentSlide = ref(0);

let slideTimer = null;


// =====================================================
// NEXT SLIDE
// =====================================================

const nextSlide = () => {

  currentSlide.value =
    (currentSlide.value + 1) % slides.length;

};


// =====================================================
// GO TO SPECIFIC SLIDE
// =====================================================

const goToSlide = (index) => {

  currentSlide.value = index;

};


// =====================================================
// START SLIDER
// =====================================================

onMounted(() => {

  slideTimer = setInterval(() => {

    nextSlide();

  }, 8500);

});


// =====================================================
// CLEANUP
// =====================================================

onBeforeUnmount(() => {

  if (slideTimer) {

    clearInterval(slideTimer);

  }

});

</script>


<template>

  <section class="hero">


    <!-- =================================================
             BACKGROUND SLIDES
        ================================================== -->

    <div class="hero-background">

      <div v-for="(slide, index) in slides" :key="slide.image" class="hero-slide" :class="[
        {
          active: currentSlide === index
        },

        `direction-${slide.direction}`
      ]" :style="{
                  backgroundImage: `url('${slide.image}')`
                }"></div>

    </div>


    <!-- =================================================
             OVERLAY
        ================================================== -->

    <div class="hero-overlay"></div>


    <!-- =================================================
             HERO CONTENT
        ================================================== -->

    <div class="hero-content">

      <p class="hero-small">
        FROM OUR STORE
      </p>


      <h1>
        IN STYLE
      </h1>


      <p class="hero-subtitle">
        ASARA - Discover your signature style
      </p>


      <a href="#products" class="hero-button">
        EXPLORE OUR COLLECTION
      </a>

    </div>


    <!-- =================================================
             SLIDER DOTS
        ================================================== -->

    <div class="slider-dots">

      <button v-for="(slide, index) in slides" :key="index" type="button" class="dot" :class="{
        active: currentSlide === index
      }" :aria-label="`Go to slide ${index + 1}`" @click="goToSlide(index)"></button>

    </div>

  </section>

</template>


<style scoped>
/* =====================================================
   HERO
===================================================== */

.hero {

  position: relative;

  width: 100%;

  height: 440px;

  overflow: hidden;

  background: #ddd;

}


/* =====================================================
   BACKGROUND CONTAINER
===================================================== */

.hero-background {

  position: absolute;

  inset: 0;

  overflow: hidden;

}


/* =====================================================
   INDIVIDUAL SLIDE
===================================================== */

.hero-slide {

  position: absolute;

  inset: 0;

  width: 100%;

  height: 100%;

  background-size: cover;

  background-position: center;

  background-repeat: no-repeat;

  opacity: 0;

  transform: scale(1.08);

  z-index: 1;

  transition:
    opacity 2.2s ease-in-out,
    transform 8.5s cubic-bezier(.22, .61, .36, 1);

}


/* =====================================================
   ACTIVE SLIDE
===================================================== */

.hero-slide.active {

  opacity: 1;

  transform: scale(1);

  z-index: 2;

}


/* =====================================================
   LEFT → RIGHT
===================================================== */

.hero-slide.direction-left {

  transform:
    translateX(-100%) scale(1.08);

}


.hero-slide.direction-left.active {

  transform:
    translateX(0) scale(1);

}


/* =====================================================
   RIGHT → LEFT
===================================================== */

.hero-slide.direction-right {

  transform:
    translateX(100%) scale(1.08);

}


.hero-slide.direction-right.active {

  transform:
    translateX(0) scale(1);

}


/* =====================================================
   TOP → BOTTOM
===================================================== */

.hero-slide.direction-top {

  transform:
    translateY(-100%) scale(1.08);

}


.hero-slide.direction-top.active {

  transform:
    translateY(0) scale(1);

}


/* =====================================================
   BOTTOM → TOP
===================================================== */

.hero-slide.direction-bottom {

  transform:
    translateY(100%) scale(1.08);

}


.hero-slide.direction-bottom.active {

  transform:
    translateY(0) scale(1);

}


/* =====================================================
   OVERLAY
===================================================== */

.hero-overlay {

  position: absolute;

  inset: 0;

  z-index: 5;

  background:
    linear-gradient(to bottom,
      rgba(0, 0, 0, 0.03),
      rgba(0, 0, 0, 0.18));

  pointer-events: none;

}


/* =====================================================
   HERO CONTENT
===================================================== */

.hero-content {

  position: absolute;

  inset: 0;

  z-index: 10;

  display: flex;

  flex-direction: column;

  align-items: center;

  justify-content: center;

  text-align: center;

  color: white;

  padding-top: 20px;

}


/* =====================================================
   SMALL TITLE
===================================================== */

.hero-small {

  margin: 0 0 2px 6px;

  font-size: 15px;

  letter-spacing: 6px;

  text-transform: uppercase;

  text-shadow:
    0 2px 10px rgba(0, 0, 0, .25);

}


/* =====================================================
   MAIN TITLE
===================================================== */

.hero-content h1 {

  margin: 10px 0;

  font-family:
    Georgia,
    "Times New Roman",
    serif;

  font-size: clamp(70px,
      10vw,
      125px);

  font-weight: 300;

  letter-spacing: 8px;

  line-height: .9;

  text-shadow:
    0 2px 20px rgba(0, 0, 0, .2);

}


/* =====================================================
   SUBTITLE
===================================================== */

.hero-subtitle {

  margin: 10px 0 25px;

  font-family:
    Georgia,
    "Times New Roman",
    serif;

  font-size: 25px;

  font-weight: 300;

  text-shadow:
    0 2px 10px rgba(0, 0, 0, .25);

}


/* =====================================================
   BUTTON
===================================================== */

.hero-button {

  display: inline-block;

  padding: 12px 28px;

  border: 1px solid rgba(255, 255, 255, .85);

  color: white;

  text-decoration: none;

  font-size: 9px;

  letter-spacing: 2px;

  text-transform: uppercase;

  backdrop-filter: blur(2px);

  transition:
    background .35s ease,
    color .35s ease,
    transform .35s ease;

}


.hero-button:hover {

  background: white;

  color: #222;

  transform: translateY(-2px);

}


/* =====================================================
   SLIDER DOTS
===================================================== */

.slider-dots {

  position: absolute;

  left: 0;

  right: 0;

  bottom: 25px;

  z-index: 20;

  display: flex;

  justify-content: center;

  align-items: center;

  gap: 12px;

}


/* =====================================================
   DOT
===================================================== */

.dot {

  width: 7px;

  height: 7px;

  padding: 0;

  border-radius: 50%;

  border: 1px solid white;

  background: transparent;

  cursor: pointer;

  opacity: .8;

  transition:
    transform .3s ease,
    background .3s ease,
    opacity .3s ease;

}


.dot:hover {

  transform: scale(1.3);

  opacity: 1;

}


.dot.active {

  background: white;

  opacity: 1;

  transform: scale(1.25);

}


/* =====================================================
   MOBILE
===================================================== */

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


  .hero-button {

    padding: 11px 22px;

    font-size: 8px;

  }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 480px) {

  .hero {

    height: 470px;

  }


  .hero-content {

    padding: 20px;

  }

  .hero-content h1 {
    font-size: 52px;
  }
  .hero-subtitle {
    font-size: 16px;
    margin-bottom: 22px;
  }

}
</style>