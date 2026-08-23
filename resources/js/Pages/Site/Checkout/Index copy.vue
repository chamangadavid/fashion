<!-- resources\js\Pages\Site\Checkout\Index.vue -->
<script setup>

import { reactive, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import MyFashionLayout from "@/Layouts/MyFashionLayout.vue";
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";
import PaymentMethods from "@/Components/PaymentMethods.vue";
import Swal from "sweetalert2";



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

     paymentSettings: {
        type: Object,
        default: () => ({
            cash_on_delivery: true,
            mobile_money: true,
            card: false,
            mobile_money_provider: '',
            mobile_money_number: '',
            card_provider: '',
            currency: 'ZMW',
            payment_instructions: '',
        }),
    },

});


//const paymentMethod = ref("cash_on_delivery");

const paymentMethod = ref(
    props.paymentSettings.cash_on_delivery
        ? 'cash_on_delivery'
        : props.paymentSettings.mobile_money
            ? 'mobile_money'
            : props.paymentSettings.card
                ? 'card'
                : null
)

const processing = ref(false);


const form = reactive({

    // Contact
    customer_email: "",

    // Customer
    first_name: "",
    last_name: "",

    // Address
    address: "",
    city: "",
    country: "Zambia",
    customer_phone: "",

    // Payment
    mobile_money_number: "",

    cardholder_name: "",
    card_number: "",
    card_expiry: "",
    card_cvv: "",

});

const submitOrder = () => {
    processing.value = true;

    router.post(
        "/checkout",
        {
            ...form,
            payment_method: paymentMethod.value,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Order Placed!",
                    text: "Your order has been placed successfully.",
                    confirmButtonText: "Continue",
                    confirmButtonColor: "#075c59",
                    timer: 3000,
                    timerProgressBar: true,
                });
            },

            onError: (errors) => {
                console.log(errors);

                Swal.fire({
                    icon: "error",
                    title: "Unable to Place Order",
                    text: "Please check the form and try again.",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#075c59",
                });
            },

            onFinish: () => {
                processing.value = false;
            },
        }
    );
};


const formatPrice = (amount) => {

    return new Intl.NumberFormat("en-ZM", {
        style: "currency",
        currency: "ZMW",
    }).format(Number(amount));

};

</script>

<template>

    <Head title="Checkout" />

    <div class="checkout-page">

        <Header />


        <!-- =========================================
             HEADER
        ========================================== -->

        <!-- <section class="checkout-header">

            <span>
                SECURE CHECKOUT
            </span>

            <h1>
                Checkout
            </h1>

            <p>
                Complete your order details below.
            </p>

        </section> -->


                <!-- ============================= -->
        <!-- PAGE HEADER -->
        <!-- ============================= -->
           <section class="collections-hero">

            <div class="hero-background"></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">
                    SECURE CHECKOUT
                </span>

                <h1>
                     Checkout
                </h1>

                <p>
                     Complete your order details below.
                </p>

            </div>

        </section>


        <!-- =========================================
             CHECKOUT
        ========================================== -->

        <main class="checkout-container">


            <!-- =====================================
                 CUSTOMER DETAILS
            ====================================== -->

            <section class="checkout-form">

                <div class="section">

                    <h2>
                        Contact Information
                    </h2>

                    <div class="form-group">

                        <label>
                            Email Address
                        </label>

                        <input
                            v-model="form.customer_email"
                            type="email"
                            placeholder="you@example.com"
                        />

                    </div>

                </div>


                <!-- =================================
                     SHIPPING ADDRESS
                ================================== -->

                <div class="section">

                    <h2>
                        Shipping Address
                    </h2>


                    <div class="form-row">

                        <div class="form-group">

                            <label>
                                First Name
                            </label>

                            <input
                             v-model="form.first_name"
                                type="text"
                                placeholder="First name"
                            />

                        </div>


                        <div class="form-group">

                            <label>
                                Last Name
                            </label>

                            <input
                             v-model="form.last_name"
                                type="text"
                                placeholder="Last name"
                            />

                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            Address
                        </label>

                        <input
                         v-model="form.address"
                            type="text"
                            placeholder="Street address"
                        />

                    </div>


                    <div class="form-row">

                        <div class="form-group">

                            <label>
                                City
                            </label>

                            <input
                             v-model="form.city"
                                type="text"
                                placeholder="City"
                            />

                        </div>


                        <div class="form-group">

                            <label>
                                Country
                            </label>

                            <input
                               v-model="form.country"
                                type="text"
                                placeholder="Country"
                            />

                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            Phone Number
                        </label>

                        <input
                         v-model="form.customer_phone"
                            type="tel"
                            placeholder="+260"
                        />

                    </div>

                </div>

                <!-- =================================
     ORDER ITEMS
================================== -->

<div class="section">

    <h2>
        Your Items
    </h2>

    <div class="checkout-items">

        <div
            v-for="item in cart"
            :key="item.id"
            class="checkout-item"
        >

            <div class="checkout-item-image">

                <img
                    v-if="item.image"
                    :src="item.image"
                    :alt="item.name"
                />

                <div
                    v-else
                    class="no-item-image"
                >
                    NO IMAGE
                </div>

            </div>

            <div class="checkout-item-details">

                <h3>
                    {{ item.name }}
                </h3>

                <p>
                    SKU: {{ item.sku }}
                </p>

                <p>
                    Quantity: {{ item.quantity }}
                </p>

            </div>

            <div class="checkout-item-price">

                {{ formatPrice(item.price * item.quantity) }}

            </div>

        </div>

    </div>

</div>


                

          <!-- =================================
            PAYMENT METHOD
        ================================== -->

                <div class="section">

                    <h2>
                        Payment Method
                    </h2>

                    <div class="payment-methods">

                        <!-- CASH ON DELIVERY -->

                        <label class="payment-option" :class="{ selected: paymentMethod === 'cash_on_delivery' }">

                            <input type="radio" value="cash_on_delivery" v-model="paymentMethod" />

                            <div class="payment-icon">
                                💵
                            </div>

                            <div class="payment-info">

                                <strong>
                                    Cash on Delivery
                                </strong>

                                <p>
                                    Pay when your order is delivered.
                                </p>

                            </div>

                        </label>


                        <!-- MOBILE MONEY -->

                        <label class="payment-option" :class="{ selected: paymentMethod === 'mobile_money' }">

                            <input type="radio" value="mobile_money" v-model="paymentMethod" />

                            <div class="payment-icon">
                                📱
                            </div>

                            <div class="payment-info">

                                <strong>
                                    Mobile Money
                                </strong>

                                <p>
                                    Pay using your mobile money account.
                                </p>

                            </div>

                        </label>


                        <!-- VISA / CARD -->

                        <label class="payment-option" :class="{ selected: paymentMethod === 'card' }">

                            <input type="radio" value="card" v-model="paymentMethod" />

                            <div class="payment-icon">
                                💳
                            </div>

                            <div class="payment-info">

                                <strong>
                                    Visa / Card
                                </strong>

                                <p>
                                    Pay securely using your bank card.
                                </p>

                            </div>

                        </label>

                    </div>


                    <!-- MOBILE MONEY DETAILS -->

                    <div v-if="paymentMethod === 'mobile_money'" class="payment-details">

                        <div class="form-group">

                            <label>
                                Mobile Money Number
                            </label>

                            <input v-model="form.mobile_money_number" type="tel" placeholder="+260 97 123 4567" />

                        </div>

                        <p class="payment-note">
                            You will receive instructions to complete your
                            mobile money payment.
                        </p>

                    </div>


                    <!-- CARD DETAILS -->

                    <div v-if="paymentMethod === 'card'" class="payment-details">

                        <div class="form-group">

                            <label>
                                Cardholder Name
                            </label>

                            <input v-model="form.cardholder_name" type="text" placeholder="Name on card" />

                        </div>

                        <div class="form-group">

                            <label>
                                Card Number
                            </label>

                            <input v-model="form.card_number" type="text" inputmode="numeric" maxlength="19"
                                placeholder="•••• •••• •••• ••••" />

                        </div>

                        <div class="form-row">

                            <div class="form-group">

                                <label>
                                    Expiry Date
                                </label>

                                <input v-model="form.card_expiry" type="text" maxlength="5" placeholder="MM/YY" />

                            </div>


                            <div class="form-group">

                                <label>
                                    CVV
                                </label>

                                <input v-model="form.card_cvv" type="password" maxlength="4" placeholder="•••" />

                            </div>

                        </div>

                        <p class="payment-note">
                            Your card details will be handled securely by the
                            payment gateway when payment processing is enabled.
                        </p>

                    </div>

                </div>

            </section>


            <!-- =====================================
                 ORDER SUMMARY
            ====================================== -->



            <aside class="order-summary">

    <h2>
        Your Order
    </h2>

    <div class="summary-row">

        <span>
            Items
        </span>

        <strong>
            {{ totalItems }}
        </strong>

    </div>

    <div class="summary-row">

        <span>
            Subtotal
        </span>

        <strong>
            {{ formatPrice(subtotal) }}
        </strong>

    </div>

    <div class="summary-row">

        <span>
            Shipping
        </span>

        <strong>
            FREE
        </strong>

    </div>

    <div class="summary-divider"></div>

    <div class="summary-total">

        <span>
            Total
        </span>

        <strong>
            {{ formatPrice(subtotal) }}
        </strong>

    </div>

    <button
    type="button"
    class="place-order-button"
    :disabled="processing || cart.length === 0"
    @click="submitOrder"
>
    {{ processing ? 'PROCESSING...' : 'PLACE ORDER' }}
</button>

    <!-- <button
        type="button"
        class="place-order-button"
        @click="submitOrder"
    >
        PLACE ORDER
    </button> -->

    <Link
        href="/cart"
        class="back-to-bag"
    >
        ← BACK TO BAG
    </Link>

</aside>

        </main>


        <AppFooter />

        <PaymentMethods />

    </div>

</template>


<style scoped>

.checkout-page {
    min-height: 100vh;
    background: #fff;
    color: #222;
}


/* =========================================
   HEADER
========================================= */

.checkout-header {
    padding: 70px 25px 45px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.checkout-header span {
    color: #a0833d;
    font-size: 8px;
    letter-spacing: 3px;
}

.checkout-header h1 {
    margin: 12px 0;
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 46px;
    font-weight: 400;
}

.checkout-header p {
    margin: 0;
    color: #777;
    font-size: 11px;
}


/* =========================================
   CONTAINER
========================================= */

.checkout-container {
    max-width: 1100px;
    margin: auto;
    padding: 60px 25px;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 70px;
}


/* =========================================
   SECTIONS
========================================= */

.section {
    margin-bottom: 45px;
}

.section h2 {
    margin-bottom: 25px;
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 22px;
    font-weight: 400;
}


/* =========================================
   FORM
========================================= */

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-size: 9px;
    letter-spacing: .7px;
}

.form-group input {
    width: 100%;
    box-sizing: border-box;
    padding: 13px;
    border: 1px solid #ddd;
    outline: none;
    font-size: 11px;
}

.form-group input:focus {
    border-color: #075c59;
}


/* =========================================
   PAYMENT
========================================= */

.payment-option {
    display: flex;
    gap: 12px;
    padding: 18px;
    border: 1px solid #ddd;
}

.payment-option strong {
    font-size: 11px;
}

.payment-option p {
    margin: 5px 0 0;
    color: #777;
    font-size: 9px;
}


/* =========================================
   SUMMARY
========================================= */

.order-summary {
    align-self: start;
    padding: 30px;
    background: #fafafa;
}

.order-summary h2 {
    margin-top: 0;
    margin-bottom: 30px;
    font-family:
        Georgia,
        "Times New Roman",
        serif;
    font-size: 23px;
    font-weight: 400;
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


/* =========================================
   CHECKOUT ITEMS
========================================= */

.checkout-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.checkout-item {
    display: grid;
    grid-template-columns: 80px 1fr auto;
    gap: 15px;
    align-items: center;

    padding: 12px;

    border-bottom: 1px solid #eee;
}

.checkout-item-image {
    width: 80px;
    height: 95px;

    background: #f4f4f4;

    overflow: hidden;
}

.checkout-item-image img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}

.no-item-image {
    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #999;
    font-size: 7px;
}

.checkout-item-details h3 {
    margin: 0 0 6px;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 15px;
    font-weight: 400;
}

.checkout-item-details p {
    margin: 3px 0;

    color: #888;

    font-size: 8px;
}

.checkout-item-price {
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
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

    background-image: url("/assets/checkout-1.png");

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

.place-order-button:disabled {
    opacity: .6;
    cursor: not-allowed;
}

/* =========================================
   PAYMENT METHODS
========================================= */

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}


.payment-option {
    display: flex;
    align-items: center;
    gap: 15px;

    padding: 18px;

    border: 1px solid #ddd;

    cursor: pointer;

    transition:
        border-color .25s ease,
        background .25s ease;
}


.payment-option:hover {
    border-color: #075c59;
}


.payment-option.selected {
    border-color: #075c59;
    background: #f7fbfa;
}


.payment-option input {
    accent-color: #075c59;
}


.payment-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #eee;
    border-radius: 50%;

    font-size: 17px;
}


.payment-info strong {
    display: block;

    font-size: 11px;
    font-weight: 600;
}


.payment-info p {
    margin: 5px 0 0;

    color: #777;

    font-size: 9px;
    line-height: 1.5;
}


/* =========================================
   PAYMENT DETAILS
========================================= */

.payment-details {

    margin-top: 15px;

    padding: 20px;

    border: 1px solid #eee;

    background: #fafafa;
}


.payment-details .form-group:last-child {
    margin-bottom: 0;
}


.payment-note {

    margin: 10px 0 0;

    color: #888;

    font-size: 8px;

    line-height: 1.6;
}


/* =========================================
   PLACE ORDER
========================================= */

.place-order-button {
    width: 100%;
    margin-top: 30px;
    padding: 16px;
    border: none;
    background: #075c59;
    color: white;
    cursor: pointer;
    font-size: 9px;
    letter-spacing: 2px;
}

.place-order-button:hover {
    background: #064b48;
}


/* =========================================
   BACK
========================================= */

.back-to-bag {
    display: block;
    margin-top: 20px;
    text-align: center;
    color: #555;
    text-decoration: none;
    font-size: 8px;
    letter-spacing: 1px;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 800px) {

    .checkout-container {
        grid-template-columns: 1fr;
        gap: 40px;
    }

}


@media (max-width: 550px) {

    .form-row {
        grid-template-columns: 1fr;
    }

    .checkout-header h1 {
        font-size: 38px;
    }

    .checkout-container {
        padding: 40px 18px;
    }

}

</style>