<!-- resources\js\Pages\Site\Products\Show.vue -->
<script setup>

import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";

import Swal from "sweetalert2";


const props = defineProps({

    product: {
        type: Object,
        required: true,
    },

});


/*
|--------------------------------------------------------------------------
| ADD TO BAG LOADING
|--------------------------------------------------------------------------
*/

const addingToBag = ref(false);


/*
|--------------------------------------------------------------------------
| ADD TO BAG
|--------------------------------------------------------------------------
*/

const addToBag = () => {

    // Prevent double clicking
    if (addingToBag.value) {
        return;
    }

    // Product unavailable
    if (
        !props.product.is_active ||
        props.product.stock_quantity <= 0
    ) {
        return;
    }


    addingToBag.value = true;


    router.post(
        `/cart/add/${props.product.id}`,
        {
            quantity: 1,
        },
        {
            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({
                    icon: "success",
                    title: "Added to Bag!",
                    text: `${props.product.name} has been added to your bag.`,
                    confirmButtonText: "Continue Shopping",
                    timer: 2500,
                    timerProgressBar: true,
                });

            },

            onError: (errors) => {

                console.error(
                    "Failed to add product to bag:",
                    errors
                );


                Swal.fire({
                    icon: "error",
                    title: "Unable to Add",
                    text: "Something went wrong while adding this product to your bag.",
                    confirmButtonText: "OK",
                });

            },

            onFinish: () => {

                addingToBag.value = false;

            },
        }
    );

};

</script>

<template>

    <Head :title="product.name" />

    <div class="product-page">

        <Header />

        <main class="product-container">

            <div class="product-image">

                <img v-if="product.image" :src="product.image" :alt="product.name" />

                <div v-else class="no-image">
                    NO IMAGE
                </div>

            </div>


            <div class="product-details">

                <span v-if="product.category" class="category">
                    {{ product.category.name }}
                </span>

                <h1>
                    {{ product.name }}
                </h1>

                <p class="sku">
                    SKU: {{ product.sku }}
                </p>


                <div class="price">

                    <span v-if="product.sale_price" class="sale-price">
                        K{{ product.sale_price }}
                    </span>

                    <span :class="{
                        'old-price': product.sale_price
                    }">
                        K{{ product.price }}
                    </span>

                </div>


                <p v-if="product.description" class="description">
                    {{ product.description }}
                </p>


                <div class="stock">

                    <span v-if="product.stock_quantity > 0">
                        In Stock
                    </span>

                    <span v-else>
                        Out of Stock
                    </span>

                </div>

                <button type="button" class="add-button" :disabled="addingToBag ||
                    !product.is_active ||
                    product.stock_quantity <= 0
                    " @click="addToBag">

                    <!-- LOADING -->
                    <span v-if="addingToBag" class="button-loading">
                        <span class="spinner"></span>
                        ADDING...
                    </span>


                    <!-- NORMAL -->
                    <span v-else>
                        {{
                            !product.is_active
                                ? 'UNAVAILABLE'
                                : product.stock_quantity > 0
                                    ? 'ADD TO BAG'
                        : 'OUT OF STOCK'
                        }}
                    </span>

                </button>




                <Link href="/collections" class="back-link">
                    ← BACK TO COLLECTIONS
                </Link>

            </div>

        </main>


        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>
.product-page {
    background: white;

    color: #222;

    min-height: 100vh;
}

.product-container {
    max-width: 1150px;

    margin: auto;

    padding: 70px 25px;

    display: grid;

    grid-template-columns:
        1.1fr .9fr;

    gap: 70px;
}

.product-image {
    height: 650px;

    background: #f4f4f4;

    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;

    display: flex;

    align-items: center;
    justify-content: center;

    color: #999;

    font-size: 10px;
}

.product-details {
    padding-top: 40px;
}

.category {
    color: #a0833d;

    font-size: 8px;

    letter-spacing: 2px;

    text-transform: uppercase;
}

.product-details h1 {
    margin: 12px 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 42px;

    font-weight: 400;
}

.sku {
    color: #888;

    font-size: 9px;
}

.price {
    display: flex;

    gap: 12px;

    margin: 25px 0;

    font-size: 18px;
}

.sale-price {
    color: #075c59;
}

.old-price {
    color: #999;

    text-decoration: line-through;
}

.description {
    color: #666;

    font-size: 11px;

    line-height: 1.8;

    margin-bottom: 25px;
}

.stock {
    margin-bottom: 25px;

    font-size: 10px;

    letter-spacing: 1px;
}

.add-button {
    width: 100%;

    padding: 15px;

    border: none;

    background: #075c59;

    color: white;

    font-size: 9px;

    letter-spacing: 2px;

    cursor: pointer;
}

.add-button:disabled {
    background: #aaa;

    cursor: not-allowed;
}

.back-link {
    display: inline-block;

    margin-top: 25px;

    color: #555;

    font-size: 8px;

    letter-spacing: 1px;

    text-decoration: none;
}


.add-button {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.add-button:disabled {
    cursor: not-allowed;
    opacity: 0.65;
}

.button-loading {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid currentColor;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 800px) {

    .product-container {
        grid-template-columns: 1fr;

        gap: 30px;

        padding-top: 40px;
    }

    .product-image {
        height: 500px;
    }

    .product-details {
        padding-top: 0;
    }

}
</style>