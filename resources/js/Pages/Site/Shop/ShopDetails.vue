<script setup>

import { Head, Link, router } from "@inertiajs/vue3";

import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";

import { ref } from "vue";

const props = defineProps({

    category: {
        type: Object,
        required: true,
    },

    products: {
        type: Object,
        required: true,
    },

});


// =====================================================
// SORT
// =====================================================

const sort = ref("latest");

const changeSort = () => {

    router.get(
        `/shop/${props.category.slug}`,
        {
            sort: sort.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );

};

</script>


<template>

    <Head
        :title="category.name"
    />


    <div class="shop-page">

        <Header />


        <!-- =========================================
             CATEGORY HEADER
        ========================================== -->

        <section class="shop-header">

            <span>
                FASHION STYLES
            </span>

            <h1>
                {{ category.name }}
            </h1>

            <p>
                {{ category.description }}
            </p>

        </section>


        <!-- =========================================
             SHOP CONTROLS
        ========================================== -->

        <section class="shop-controls">

            <div>

                {{ products.total }} items

            </div>


            <select
                v-model="sort"
                @change="changeSort"
            >

                <option value="latest">
                    Newest
                </option>

                <option value="price_low">
                    Price: Low to High
                </option>

                <option value="price_high">
                    Price: High to Low
                </option>

            </select>

        </section>


        <!-- =========================================
             PRODUCTS
        ========================================== -->

        <main class="products-grid">

            <article
                v-for="product in products.data"
                :key="product.id"
                class="product-card"
            >

                <Link
                    :href="`/product/${product.slug}`"
                    class="product-image"
                >

                    <img
                        :src="`/storage/${product.main_image}`"
                        :alt="product.name"
                    />

                </Link>


                <div class="product-info">

                    <h2>
                        {{ product.name }}
                    </h2>

                    <p>
                        K{{ product.price }}
                    </p>

                    <button
                        type="button"
                        class="add-cart-button"
                    >
                        ADD TO CART
                    </button>

                </div>

            </article>

        </main>


        <AppFooter />

    </div>

</template>