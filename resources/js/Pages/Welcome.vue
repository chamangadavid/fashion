<!-- Resources/js/Pages/Welcome.vue -->
<script setup>
import { ref, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import AnimatedHero from "@/Components/AnimatedHero.vue";
import QuickShop from "@/Components/QuickShop.vue";
import AboutBrand from "@/Components/AboutBrand.vue";
import NewArrivals from "@/Components/NewArrivals.vue";
import CategorySection from "@/Components/CategorySection.vue";

import FeaturedProducts from "@/Components/FeaturedProducts.vue";
import SignatureStyles from "@/Components/SignatureStyles.vue";
import AccessoriesSection from "@/Components/AccessoriesSection.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";
import AppFooter from "@/Components/AppFooter.vue";


const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,

    quickShopCategories: {
        type: Array,
        default: () => [],
    },

    newArrivals: {
        type: Array,
        default: () => [],
    },

    featuredProducts: {
        type: Array,
        default: () => [],
    },

    productCategories: {
        type: Array,
        default: () => [],
    },


});

/*
|--------------------------------------------------------------------------
| CLOTHING CATEGORIES
|--------------------------------------------------------------------------
*/
const clothingCategories = computed(() => {
    return props.productCategories.filter(
        category => category.group === "clothing"
    );
});

/*
|--------------------------------------------------------------------------
| ACCESSORIES CATEGORIES
|--------------------------------------------------------------------------
*/
const accessoryCategories = computed(() => {
    return props.productCategories.filter(
        category => category.group === "accessories"
    );
});


/*
|--------------------------------------------------------------------------
| Denim Products
|--------------------------------------------------------------------------
*/

// const denimProducts = [

//     {
//         id: 1,
//         name: "Contour Denim Jacket",
//         price: "K66.99",
//         image: "/assets/categories/denim/denim-jacket.jpg",
//         colors: ["#50769d", "#252525"],
//         href: "/products/contour-denim-jacket",
//     },

//     {
//         id: 2,
//         name: "High-Waisted Wide-Leg Jeans with Cuffed Hem",
//         price: "K69.99",
//         image: "/assets/categories/denim/wide-leg-jeans.jpg",
//         colors: ["#91a9bd"],
//         href: "/products/high-waisted-wide-leg-jeans",
//     },

//     {
//         id: 3,
//         name: "Button Up Long Sleeve Denim Shirt",
//         price: "K52.99",
//         image: "/assets/categories/denim/denim-shirt.jpg",
//         colors: ["#416589"],
//         href: "/products/button-up-denim-shirt",
//     },

//     {
//         id: 4,
//         name: "Raw Hem High Waist Bootcut Jeans",
//         price: "K58.99",
//         image: "/assets/categories/denim/pink-jeans.jpg",
//         colors: ["#df7896"],
//         href: "/products/raw-hem-bootcut-jeans",
//     },

// ];


/*
|--------------------------------------------------------------------------
| Blouses Products
|--------------------------------------------------------------------------
*/

// const blouseProducts = [

//     {
//         id: 1,
//         name: "Heart Print Button Up Flounce Sleeve Shirt",
//         originalPrice: "K74.99",
//         price: "K60.99",
//         discount: "18%",
//         sale: true,
//         image: "/assets/categories/blouses/heart-print.jpg",
//         colors: ["#3b3b3b"],
//         href: "/products/heart-print-shirt",
//     },

//     {
//         id: 2,
//         name: "Women's Elegant Floral Embroidered V-Neck Blouse",
//         price: "K49.99",
//         image: "/assets/categories/blouses/floral-blouse.jpg",
//         colors: ["#c7b2a5"],
//         href: "/products/floral-embroidered-blouse",
//     },

//     {
//         id: 3,
//         name: "The Silk-Feel Button-Up Blouse",
//         price: "K49.99",
//         image: "/assets/categories/blouses/silk-blouse.jpg",
//         colors: [
//             "#7f304d",
//             "#c5b8a7",
//             "#7f91a0",
//             "#222222",
//         ],
//         extraColors: 2,
//         href: "/products/silk-feel-blouse",
//     },

//     {
//         id: 4,
//         name: "Women's Elegant Floral Blouse – Keyhole Neckline",
//         originalPrice: "K45.99",
//         price: "K39.99",
//         discount: "13%",
//         sale: true,
//         image: "/assets/categories/blouses/purple-blouse.jpg",
//         colors: [
//             "#bca5c5",
//             "#222222",
//             "#d8d1c9",
//             "#d0a85c",
//         ],
//         extraColors: 1,
//         href: "/products/floral-keyhole-blouse",
//     },

// ];


/*
|--------------------------------------------------------------------------
| Skirts & Bottoms
|--------------------------------------------------------------------------
*/

// const bottomsProducts = [

//     {
//         id: 1,
//         name: "High-Waisted Pleated Wide-Leg Cropped Pants",
//         price: "K49.99",
//         image: "/assets/categories/bottoms/pleated-pants.jpg",
//         colors: ["#b8aa7f"],
//         href: "/products/pleated-wide-leg-pants",
//     },

//     {
//         id: 2,
//         name: "The Tailored Palazzo",
//         price: "K69.99",
//         image: "/assets/categories/bottoms/palazzo.jpg",
//         colors: ["#4b4d3c", "#222222"],
//         href: "/products/tailored-palazzo",
//     },

//     {
//         id: 3,
//         name: "Honey Tied High Waist Wide Leg Pants for Women",
//         price: "K65.99",
//         image: "/assets/categories/bottoms/wide-leg-pants.jpg",
//         colors: [
//             "#e8e2d7",
//             "#a99482",
//             "#687995",
//             "#384d69",
//         ],
//         extraColors: 4,
//         href: "/products/honey-tied-wide-leg-pants",
//     },

//     {
//         id: 4,
//         name: "Leather High-Waist Side Slit Maxi Skirt",
//         originalPrice: "K66.99",
//         price: "K59.99",
//         discount: "10%",
//         sale: true,
//         image: "/assets/categories/bottoms/leather-skirt.jpg",
//         colors: ["#222222"],
//         href: "/products/leather-maxi-skirt",
//     },

// ];

</script>



<template>

    <Head title="In Styles Asara" />

    <div class="min-h-screen bg-white text-gray-800">

        <!-- ========================= -->
        <!-- HEADER -->
        <!-- ========================= -->

        <Header :canLogin="canLogin" :canRegister="canRegister" />


        <!-- ========================= -->
        <!-- HERO -->
        <!-- ========================= -->

        <AnimatedHero />

        <!-- ========================= -->
        <!-- MAIN CONTENT -->
        <!-- ========================= -->

        <main>

            <!-- ========================= -->
            <!-- QUICK SHOP -->
            <!-- ========================= -->

            <QuickShop :categories="quickShopCategories" />

            <!-- ========================= -->
            <!-- ABOUT THE BRAND -->
            <!-- ========================= -->

            <section>
                <AboutBrand />
            </section>

            <!-- ========================= -->
            <!-- NEW ARRIVALS -->
            <!-- ========================= -->

            <section>
                <NewArrivals :products="newArrivals" />
            </section>

            <section>
                <FeaturedProducts :products="featuredProducts" />
            </section>

            <!-- <section>
                <FeaturedProducts />
            </section> -->

            <!-- ========================= -->
            <!-- SIGNATURE STYLES -->
            <!-- ========================= -->

            <section>
                <SignatureStyles />
            </section>

            <!-- categories types -->
            <section>

                <!-- CLOTHING -->
                <CategorySection title="Clothing" :categories="clothingCategories" view-more-href="/categories" />

                <!-- ACCESSORIES -->
                <CategorySection title="Accessories" :categories="accessoryCategories" view-more-href="/categories" />

            </section>

        </main>

        <!-- ========================= -->
        <!-- FOOTER -->
        <!-- ========================= -->
        <section>
            <AppFooter />
        </section>


        <!-- ========================= -->
        <!-- PAYMENT METHODS -->
        <!-- ========================= -->
        <section>
            <PaymentMethods />
        </section>


    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
}
</style>