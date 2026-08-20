<script setup>
const paymentMethods = [
    {
        name: "Airtel Money",
        image: "/assets/payments/airtel-money.png",
        class: "airtel",
    },
    {
        name: "MTN Mobile Money",
        image: "/assets/payments/mtn-mobile-money.png",
        class: "mtn",
    },
    {
        name: "Zamtel Money",
        image: "/assets/payments/zamtel-money.png",
        class: "zamtel",
    },
    {
        name: "PayPal",
        image: "/assets/payments/paypal.png",
        class: "paypal",
    },
    {
        name: "Visa",
        image: "/assets/payments/visa.png", 
        class: "visa",
    },
    {
        name: "Master Card",
        image: "/assets/payments/master.png",
        class: "mastercard",
    },
];
</script>


<template>

    <section class="payment-section">

        <div class="payment-container">

            <!-- =====================================
                 LEFT INFORMATION
            ====================================== -->

            <div class="payment-introduction">

                <div class="payment-lock">
                    🔒
                </div>

                <div>

                    <h3>
                        Secure Payments
                    </h3>

                    <p>
                        Shop with confidence. We accept
                        trusted payment methods.
                    </p>

                </div>

            </div>


            <!-- =====================================
                 PAYMENT METHODS
            ====================================== -->

            <div class="payment-methods">

                <div
                    v-for="method in paymentMethods"
                    :key="method.name"
                    class="payment-method"
                    :class="method.class"
                >

                    <img
                        :src="method.image"
                        :alt="method.name"
                        class="payment-logo"
                    />

                </div>

            </div>


            <!-- =====================================
                 SECURITY MESSAGE
            ====================================== -->

            <div class="payment-security">

                <span class="security-icon">
                    ✓
                </span>

                <span>
                    Safe &amp; Secure Checkout
                </span>

            </div>

        </div>

    </section>

</template>


<style scoped>

/* =====================================================
   MAIN SECTION
===================================================== */

.payment-section {

    width: 100%;

    background: #ffffff;

    border-top: 1px solid #e8e3db;

    border-bottom: 1px solid #e8e3db;

}


/* =====================================================
   CONTAINER
===================================================== */

.payment-container {

    width: 100%;

    max-width: 1180px;

    margin: 0 auto;

    padding: 24px 35px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 30px;

    box-sizing: border-box;

}


/* =====================================================
   INTRODUCTION
===================================================== */

.payment-introduction {

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 205px;

}


.payment-lock {

    width: 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 1px solid #d8cdbb;

    border-radius: 50%;

    font-size: 14px;

}


.payment-introduction h3 {

    margin: 0 0 3px;

    color: #075c59;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 15px;

    font-weight: 600;

}


.payment-introduction p {

    margin: 0;

    color: #777;

    font-size: 8px;

    line-height: 1.4;

}


/* =====================================================
   PAYMENT METHODS
===================================================== */

.payment-methods {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 12px;

    flex: 1;

}


/* =====================================================
   INDIVIDUAL PAYMENT BOX
===================================================== */

.payment-method {

    width: 76px;

    height: 43px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #ffffff;

    border: 1px solid #e4e0da;

    border-radius: 6px;

    padding: 6px 9px;

    box-sizing: border-box;

    transition:
        transform .25s ease,
        border-color .25s ease,
        box-shadow .25s ease;

}


.payment-method:hover {

    transform: translateY(-3px);

    border-color: #bdb3a5;

    box-shadow:
        0 5px 15px rgba(0, 0, 0, .08);

}


/* =====================================================
   PAYMENT LOGO
===================================================== */

.payment-logo {

    display: block;

    max-width: 100%;

    max-height: 30px;

    width: auto;

    height: auto;

    object-fit: contain;

}


/* =====================================================
   MOBILE MONEY LOGOS
===================================================== */

.airtel {

    background: #fffafa;

}


.mtn {

    background: #fffdf0;

}


.zamtel {

    background: #f8fffa;

}


/* =====================================================
   INTERNATIONAL PAYMENTS
===================================================== */

.paypal {

    background: #fafcff;

}


.visa {

    background: #f9fbff;

}


/* =====================================================
   SECURITY
===================================================== */

.payment-security {

    display: flex;

    align-items: center;

    gap: 6px;

    white-space: nowrap;

    color: #777;

    font-size: 8px;

}


.security-icon {

    width: 17px;

    height: 17px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #075c59;

    color: #ffffff;

    font-size: 9px;

    font-weight: bold;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 950px) {

    .payment-container {

        flex-wrap: wrap;

        justify-content: center;

        gap: 18px;

    }


    .payment-introduction {

        width: 100%;

        justify-content: center;

    }


    .payment-methods {

        flex: none;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 600px) {

    .payment-container {

        padding: 22px 15px;

        flex-direction: column;

        gap: 18px;

    }


    .payment-introduction {

        width: auto;

        text-align: center;

    }


    .payment-methods {

        width: 100%;

        display: flex;

        flex-wrap: wrap;

        gap: 8px;

    }


    .payment-method {

        width: calc(20% - 7px);

        min-width: 58px;

        height: 40px;

        padding: 5px;

    }


    .payment-logo {

        max-height: 25px;

        max-width: 90%;

    }


    .payment-security {

        justify-content: center;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 400px) {

    .payment-method {

        width: 72px;

    }


    .payment-methods {

        justify-content: center;

    }

}

</style>