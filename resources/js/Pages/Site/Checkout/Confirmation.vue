<!-- resources\js\Pages\Site\Checkout\Confirmation.vue -->
<script setup>

import { Head, Link } from "@inertiajs/vue3";

import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";


const props = defineProps({

    order: {
        type: Object,
        required: true,
    },

});

</script>


<template>

    <Head title="Order Confirmation" />

    <div class="confirmation-page">

        <Header />


        <!-- =========================================
             HERO
        ========================================== -->

        <section class="confirmation-hero">

            <div class="hero-background"></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">
                    ORDER CONFIRMED
                </span>

                <h1>
                    Thank You
                </h1>

                <p>
                    Your order has been successfully placed.
                </p>

            </div>

        </section>


        <!-- =========================================
             CONFIRMATION
        ========================================== -->

        <main class="confirmation-container">


            <!-- SUCCESS MESSAGE -->

            <section class="success-section">

                <div class="success-icon">
                    ✓
                </div>

                <span class="section-eyebrow">
                    THANK YOU FOR SHOPPING WITH US
                </span>

                <h2>
                    Your Order Has Been Received
                </h2>

                <p>
                    We have received your order and will begin
                    processing it shortly.
                </p>

                <div class="order-number">

                    <span>
                        ORDER NUMBER
                    </span>

                    <strong>
                        {{ order.order_number }}
                    </strong>

                </div>

            </section>


            <!-- =========================================
                 ORDER CONTENT
            ========================================== -->

            <div class="confirmation-grid">


                <!-- =====================================
                     ORDER ITEMS
                ====================================== -->

                <section class="order-items">

                    <h2>
                        Order Details
                    </h2>


                    <div
                        v-for="item in order.items"
                        :key="item.id"
                        class="order-item"
                    >

                        <div class="item-image">

                            <img
                                v-if="item.product?.image"
                                :src="item.product.image"
                                :alt="item.product_name"
                            />

                            <div
                                v-else
                                class="no-image"
                            >
                                NO IMAGE
                            </div>

                        </div>


                        <div class="item-details">

                            <h3>
                                {{ item.product_name }}
                            </h3>

                            <span>
                                SKU: {{ item.sku }}
                            </span>

                            <p>
                                Quantity: {{ item.quantity }}
                            </p>

                        </div>


                        <div class="item-price">

                            ZMW
                            {{ Number(item.total_price).toFixed(2) }}

                        </div>

                    </div>

                </section>


                <!-- =====================================
                     ORDER SUMMARY
                ====================================== -->

                <aside class="order-summary">

                    <h2>
                        Order Summary
                    </h2>


                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            ZMW
                            {{ Number(order.subtotal).toFixed(2) }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Shipping
                        </span>

                        <strong>
                            ZMW
                            {{ Number(order.shipping_amount || 0).toFixed(2) }}
                        </strong>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            ZMW
                            {{ Number(order.total_amount).toFixed(2) }}
                        </strong>

                    </div>


                    <!-- PAYMENT -->

                    <div class="summary-section">

                        <span class="summary-label">
                            PAYMENT METHOD
                        </span>

                        <strong>
                            <span
                                v-if="order.payment_method === 'cash_on_delivery'"
                            >
                                Cash on Delivery
                            </span>

                            <span
                                v-else-if="order.payment_method === 'mobile_money'"
                            >
                                Mobile Money
                            </span>

                            <span
                                v-else-if="order.payment_method === 'card'"
                            >
                                Visa / Card
                            </span>

                            <span v-else>
                                {{ order.payment_method }}
                            </span>
                        </strong>

                    </div>


                    <!-- STATUS -->

                    <div class="summary-section">

                        <span class="summary-label">
                            ORDER STATUS
                        </span>

                        <span class="status-badge">
                            {{ order.status }}
                        </span>

                    </div>


                    <div class="summary-section">

                        <span class="summary-label">
                            PAYMENT STATUS
                        </span>

                        <span class="payment-status">
                            {{ order.payment_status }}
                        </span>

                    </div>

                </aside>

            </div>


            <!-- =========================================
                 SHIPPING INFORMATION
            ========================================== -->

            <section class="shipping-section">

                <h2>
                    Shipping Information
                </h2>

                <div class="shipping-grid">

                    <div>

                        <span>
                            CUSTOMER
                        </span>

                        <strong>
                            {{ order.shipping_first_name }}
                            {{ order.shipping_last_name }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            EMAIL
                        </span>

                        <strong>
                            {{ order.customer_email }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            PHONE
                        </span>

                        <strong>
                            {{ order.customer_phone }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            ADDRESS
                        </span>

                        <strong>
                            {{ order.shipping_address }},
                            {{ order.shipping_city }},
                            {{ order.shipping_country }}
                        </strong>

                    </div>

                </div>

            </section>


            <!-- =========================================
                 ACTIONS
            ========================================== -->

            <section class="confirmation-actions">

                <Link
                    href="/"
                    class="continue-button"
                >
                    CONTINUE SHOPPING
                </Link>

                <Link
                    href="/collections"
                    class="collections-button"
                >
                    EXPLORE COLLECTIONS
                </Link>

            </section>

        </main>


        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>

.confirmation-page {

    min-height: 100vh;

    background: #fff;

    color: #222;

}


/* =========================================
   HERO
========================================= */

.confirmation-hero {

    position: relative;

    height: 380px;

    display: flex;

    align-items: center;

    justify-content: center;

    overflow: hidden;

}


.hero-background {

    position: absolute;

    inset: 0;

    background:
        url("/assets/checkout-1.png")
        center / cover
        no-repeat;

}


.hero-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,.15),
            rgba(0,0,0,.50)
        );

}


.hero-content {

    position: relative;

    z-index: 2;

    text-align: center;

    color: white;

}


.hero-eyebrow {

    display: block;

    margin-bottom: 15px;

    font-size: 9px;

    letter-spacing: 4px;

}


.hero-content h1 {

    margin: 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 70px;

    font-weight: 400;

}


.hero-content p {

    margin-top: 15px;

    font-size: 11px;

}


/* =========================================
   CONTAINER
========================================= */

.confirmation-container {

    max-width: 1100px;

    margin: auto;

    padding: 70px 25px;

}


/* =========================================
   SUCCESS
========================================= */

.success-section {

    text-align: center;

    max-width: 650px;

    margin: 0 auto 70px;

}


.success-icon {

    width: 60px;

    height: 60px;

    margin: 0 auto 20px;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 1px solid #075c59;

    border-radius: 50%;

    color: #075c59;

    font-size: 25px;

}


.section-eyebrow {

    color: #a0833d;

    font-size: 8px;

    letter-spacing: 3px;

}


.success-section h2 {

    margin: 12px 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 32px;

    font-weight: 400;

}


.success-section p {

    color: #777;

    font-size: 11px;

}


.order-number {

    margin-top: 25px;

    padding: 18px;

    background: #fafafa;

}


.order-number span {

    display: block;

    margin-bottom: 7px;

    color: #888;

    font-size: 7px;

    letter-spacing: 2px;

}


.order-number strong {

    color: #075c59;

    font-size: 13px;

    letter-spacing: 1px;

}


/* =========================================
   GRID
========================================= */

.confirmation-grid {

    display: grid;

    grid-template-columns:
        1fr 350px;

    gap: 60px;

}


/* =========================================
   ITEMS
========================================= */

.order-items h2,
.order-summary h2,
.shipping-section h2 {

    margin-top: 0;

    margin-bottom: 25px;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 23px;

    font-weight: 400;

}


.order-item {

    display: grid;

    grid-template-columns:
        85px 1fr auto;

    gap: 18px;

    padding: 18px 0;

    border-bottom: 1px solid #eee;

}


.item-image {

    width: 85px;

    height: 110px;

    background: #f4f4f4;

    overflow: hidden;

}


.item-image img {

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

    font-size: 7px;

}


.item-details h3 {

    margin: 5px 0 8px;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 15px;

    font-weight: 500;

}


.item-details span,
.item-details p {

    color: #888;

    font-size: 8px;

}


.item-price {

    align-self: center;

    color: #075c59;

    font-size: 10px;

}


/* =========================================
   SUMMARY
========================================= */

.order-summary {

    align-self: start;

    padding: 30px;

    background: #fafafa;

}


.summary-row {

    display: flex;

    justify-content: space-between;

    margin-bottom: 15px;

    font-size: 10px;

}


.summary-row strong {

    font-weight: 500;

}


.summary-divider {

    height: 1px;

    margin: 25px 0;

    background: #ddd;

}


.summary-total {

    display: flex;

    justify-content: space-between;

    font-size: 13px;

}


.summary-total strong {

    color: #075c59;

}


.summary-section {

    margin-top: 25px;

    padding-top: 20px;

    border-top: 1px solid #e5e5e5;

}


.summary-label {

    display: block;

    margin-bottom: 7px;

    color: #888;

    font-size: 7px;

    letter-spacing: 1.5px;

}


.summary-section strong {

    font-size: 10px;

}


.status-badge,
.payment-status {

    display: inline-block;

    padding: 5px 9px;

    background: #eef7f5;

    color: #075c59;

    font-size: 8px;

    text-transform: uppercase;

}


/* =========================================
   SHIPPING
========================================= */

.shipping-section {

    margin-top: 70px;

    padding-top: 50px;

    border-top: 1px solid #eee;

}


.shipping-grid {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 30px;

}


.shipping-grid div {

    padding: 18px;

    background: #fafafa;

}


.shipping-grid span {

    display: block;

    margin-bottom: 8px;

    color: #888;

    font-size: 7px;

    letter-spacing: 1.5px;

}


.shipping-grid strong {

    font-size: 10px;

    font-weight: 500;

    line-height: 1.6;

}


/* =========================================
   ACTIONS
========================================= */

.confirmation-actions {

    display: flex;

    justify-content: center;

    gap: 15px;

    margin-top: 55px;

}


.continue-button,
.collections-button {

    padding: 14px 25px;

    border: 1px solid #075c59;

    text-decoration: none;

    font-size: 8px;

    letter-spacing: 1.5px;

}


.continue-button {

    background: #075c59;

    color: white;

}


.collections-button {

    color: #075c59;

    background: white;

}


.continue-button:hover {

    background: #064b48;

}


.collections-button:hover {

    background: #f5faf9;

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 800px) {

    .confirmation-grid {

        grid-template-columns: 1fr;

        gap: 40px;

    }

}


@media (max-width: 600px) {

    .confirmation-hero {

        height: 330px;

    }


    .hero-content h1 {

        font-size: 50px;

    }


    .confirmation-container {

        padding: 50px 18px;

    }


    .order-item {

        grid-template-columns:
            70px 1fr;

    }


    .item-image {

        width: 70px;

        height: 95px;

    }


    .item-price {

        grid-column: 2;

        align-self: start;

    }


    .shipping-grid {

        grid-template-columns: 1fr;

    }


    .confirmation-actions {

        flex-direction: column;

    }


    .continue-button,
    .collections-button {

        text-align: center;

    }

}

</style>