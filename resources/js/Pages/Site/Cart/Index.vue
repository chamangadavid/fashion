<script setup>

import { Head, Link, router } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";

const props = defineProps({

    cart: {
        type: Array,
        default: () => [],
    },

    subtotal: {
        type: Number,
        default: 0,
    },

    totalItems: {
        type: Number,
        default: 0,
    },

});

const updateQuantity = (item, quantity) => {

    if (quantity < 1) {
        removeItem(item);
        return;
    }

    router.put(
        `/cart/${item.id}`,
        {
            quantity,
        },
        {
            preserveScroll: true,
        }
    );
};


const removeItem = (item) => {

    router.delete(
        `/cart/${item.id}`,
        {
            preserveScroll: true,
        }
    );
};


const clearBag = () => {

    router.delete(
        '/cart',
        {
            preserveScroll: true,
        }
    );
};

</script>


<template>

    <Head title="Shopping Bag" />

    <div class="cart-page">

        <Header />


        <!-- ============================= -->
        <!-- PAGE HEADER -->
        <!-- ============================= -->
           <section class="collections-hero">

            <div class="hero-background"></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">
                    YOUR SHOPPING BAG
                </span>

                <h1>
                     Shopping Bag
                </h1>

                <p>
                     Review the items you've selected before checkout.
                </p>

            </div>

        </section>


        <!-- ============================= -->
        <!-- CART -->
        <!-- ============================= -->

        <main class="cart-container">

            <div
                v-if="cart.length"
                class="cart-layout"
            >

                <!-- ITEMS -->

                <div class="cart-items">

                    <div
                        v-for="item in cart"
                        :key="item.id"
                        class="cart-item"
                    >

                        <div class="cart-image">

                            <img
                                :src="item.image
                                    ? `/storage/${item.image}`
                                    : '/assets/placeholder.jpg'"
                                :alt="item.name"
                            />

                        </div>


                        <div class="cart-details">

                            <h2>
                                {{ item.name }}
                            </h2>

                            <p>
                                SKU: {{ item.sku }}
                            </p>
                            
                          <div class="cart-price">

                                <template v-if="item.sale_price !== null && item.sale_price !== undefined">

                                    <!-- ORIGINAL PRICE -->
                                    <span class="old-price">
                                        K{{ Number(item.price).toFixed(2) }}
                                    </span>

                                    <!-- SALE PRICE -->
                                    <strong class="sale-price">
                                        K{{ Number(item.sale_price).toFixed(2) }}
                                    </strong>

                                </template>

                                <!-- REGULAR PRICE -->
                                <strong v-else class="regular-price">
                                    K{{ Number(item.price).toFixed(2) }}
                                </strong>

                            </div>
                        </div>

                        <div class="cart-quantity">

                            <button type="button" @click="updateQuantity(item, item.quantity - 1)">
                                −
                            </button>

                            <span>
                                {{ item.quantity }}
                            </span>

                            <button type="button" :disabled="item.quantity >= item.stock_quantity"
                                @click="updateQuantity(item, item.quantity + 1)">
                                +
                            </button>

                        </div>


                        <div class="cart-total">

                            K{{
                                (
                                    (item.sale_price ?? item.price)
                                    * item.quantity
                                ).toFixed(2)
                            }}

                        </div>
                        <button type="button" class="remove-button" @click="removeItem(item)">
                            REMOVE
                        </button>

                    </div>

                </div>


                <!-- SUMMARY -->

                <aside class="cart-summary">

                    <h2>
                        Order Summary
                    </h2>

                    <div class="summary-row">

                        <span>
                            Items
                        </span>

                        <span>
                            {{ totalItems }}
                        </span>

                    </div>


                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            K{{ subtotal.toFixed(2) }}
                        </strong>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            K{{ subtotal.toFixed(2) }}
                        </strong>

                    </div>
                   <button type="button" class="clear-button" @click="clearBag">
                        CLEAR BAG
                    </button>

                    <!-- <button class="checkout-button">
                        PROCEED TO CHECKOUT
                    </button> -->

                  <Link href="/checkout">
                        <!-- PROCEED TO CHECKOUT -->
                        <button class="checkout-button">
                        PROCEED TO CHECKOUT
                    </button>
                    </Link>


                    <Link
                        href="/collections"
                        class="continue-button"
                    >
                        CONTINUE SHOPPING
                    </Link>

                </aside>

            </div>


            <!-- EMPTY CART -->

            <div
                v-else
                class="empty-cart"
            >

                <div class="empty-icon">
                    🛍
                </div>

                <h2>
                    Your Shopping Bag Is Empty
                </h2>

                <p>
                    You haven't added anything to your bag yet.
                </p>

                <Link
                    href="/collections"
                    class="shop-button"
                >
                    EXPLORE COLLECTIONS
                </Link>

            </div>

        </main>


        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>

.cart-page {
    width: 100%;
    background: #fff;
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

    background-image: url("/assets/bag-background.png");

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

.cart-price {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 8px;
}

.old-price {
    text-decoration: line-through;
    text-decoration-thickness: 2px;
    opacity: 0.6;
}

.sale-price {
    font-weight: 700;
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


/* =========================================
   HEADER
========================================= */

.cart-header {
    padding: 70px 20px 50px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.cart-header span {
    color: #a0833d;
    font-size: 8px;
    letter-spacing: 3px;
}

.cart-header h1 {
    margin: 12px 0;
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 48px;
    font-weight: 400;
}

.cart-header p {
    color: #777;
    font-size: 11px;
}


/* =========================================
   CONTAINER
========================================= */

.cart-container {
    max-width: 1150px;
    margin: auto;
    padding: 60px 25px;
}


/* =========================================
   LAYOUT
========================================= */

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 50px;
}


/* =========================================
   ITEM
========================================= */

.cart-item {
    display: grid;
    grid-template-columns:
        130px
        1fr
        100px
        100px;
    gap: 20px;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}


.cart-image {
    width: 130px;
    height: 160px;
    background: #f5f5f5;
    overflow: hidden;
}

.cart-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


.cart-details h2 {
    margin: 0 0 8px;
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 18px;
    font-weight: 400;
}

.cart-details p {
    margin: 0 0 12px;
    color: #999;
    font-size: 9px;
}

.cart-details strong {
    font-size: 13px;
}


.cart-quantity {
    font-size: 10px;
    color: #555;
}


.cart-total {
    text-align: right;
    font-size: 13px;
    font-weight: 600;
}

.cart-quantity {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-quantity button {
    width: 28px;
    height: 28px;

    border: 1px solid #ddd;

    background: #fff;

    cursor: pointer;

    font-size: 16px;
}

.cart-quantity button:disabled {
    opacity: .4;
    cursor: not-allowed;
}

.cart-quantity span {
    min-width: 22px;
    text-align: center;
    font-size: 11px;
}

.remove-button {
    margin-top: 10px;

    border: none;

    background: transparent;

    color: #999;

    font-size: 8px;

    letter-spacing: 1px;

    cursor: pointer;
}

.remove-button:hover {
    color: #222;
}

.clear-button {
    width: 100%;

    margin-bottom: 12px;

    padding: 13px;

    border: 1px solid #222;

    background: #fff;

    color: #222;

    font-size: 8px;

    letter-spacing: 1px;

    cursor: pointer;
}

/* =========================================
   SUMMARY
========================================= */

.cart-summary {
    height: fit-content;
    padding: 30px;
    border: 1px solid #eee;
}

.cart-summary h2 {
    margin: 0 0 25px;
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 22px;
    font-weight: 400;
}


.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: #666;
    font-size: 11px;
}


.summary-row strong {
    color: #222;
}


.summary-divider {
    height: 1px;
    background: #eee;
    margin: 20px 0;
}


.summary-total {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
    font-size: 14px;
}


.checkout-button {
    width: 100%;
    padding: 15px;
    border: none;
    background: #075c59;
    color: white;
    font-size: 9px;
    letter-spacing: 1.5px;
    cursor: pointer;
}


.continue-button {
    display: block;
    margin-top: 12px;
    padding: 14px;
    border: 1px solid #222;
    color: #222;
    text-align: center;
    text-decoration: none;
    font-size: 9px;
    letter-spacing: 1px;
}


/* =========================================
   EMPTY CART
========================================= */

.empty-cart {
    padding: 100px 20px;
    text-align: center;
}


.empty-icon {
    margin-bottom: 20px;
    font-size: 45px;
}


.empty-cart h2 {
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 28px;
    font-weight: 400;
}


.empty-cart p {
    margin-bottom: 25px;
    color: #777;
    font-size: 11px;
}


.shop-button {
    display: inline-block;
    padding: 13px 25px;
    background: #111;
    color: white;
    text-decoration: none;
    font-size: 9px;
    letter-spacing: 1.5px;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 800px) {

    .cart-layout {
        grid-template-columns: 1fr;
    }

    .cart-item {
        grid-template-columns:
            100px
            1fr;
    }

    .cart-image {
        width: 100px;
        height: 130px;
    }

    .cart-quantity,
    .cart-total {
        grid-column: 2;
        text-align: left;
    }

}

</style>