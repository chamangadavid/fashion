<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";
import axios from "axios";
import Swal from "sweetalert2";

import {
    ArrowLeftOutlined,
    ShoppingOutlined,
    EnvironmentOutlined,
    PhoneOutlined,
    MailOutlined,
    CreditCardOutlined,
    BankOutlined,
    CheckCircleOutlined,
    LoadingOutlined,
    SafetyOutlined,
    UserOutlined,
} from "@ant-design/icons-vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    cart: {
        type: Array,
        default: () => [],
    },

    subtotal: {
        type: [Number, String],
        default: 0,
    },

    totalItems: {
        type: Number,
        default: 0,
    },

    paymentSettings: {
        type: Object,
        default: () => ({}),
    },

    shippingSettings: {
        type: Object,
        default: () => ({}),
    },
});

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = useForm({
    customer_email: "",
    first_name: "",
    last_name: "",
    address: "",
    city: "",
    country: "Zambia",
    customer_phone: "",
    payment_method: "",
    mobile_money_number: "",
    cardholder_name: "",
    card_number: "",
    card_expiry: "",
    card_cvv: "",
});

// const form = useForm({
//     email: "",
//     first_name: "",
//     last_name: "",
//     address: "",
//     city: "",
//     country: "Zambia",
//     phone: "",
//     payment_method: "",
//     mobile_money_number: "",
//     cardholder_name: "",
// });

/*
|--------------------------------------------------------------------------
| SHIPPING
|--------------------------------------------------------------------------
*/

const shipping = ref(0);
const shippingLabel = ref("Free Shipping");
const calculatingShipping = ref(false);

/*
|--------------------------------------------------------------------------
| PAYMENT METHODS
|--------------------------------------------------------------------------
*/

const paymentMethods = computed(() => {
    const methods = [];

    if (props.paymentSettings.cash_on_delivery) {
        methods.push({
            value: "cash_on_delivery",
            label: "Cash on Delivery",
            description: "Pay when your order is delivered.",
            icon: BankOutlined,
        });
    }

    if (props.paymentSettings.mobile_money) {
        methods.push({
            value: "mobile_money",
            label: "Mobile Money",
            description: props.paymentSettings.mobile_money_provider
                ? `Pay using ${props.paymentSettings.mobile_money_provider}.`
                : "Pay using mobile money.",
            icon: CreditCardOutlined,
        });
    }

    if (props.paymentSettings.card) {
        methods.push({
            value: "card",
            label: "Card Payment",
            description: props.paymentSettings.card_provider
                ? `Pay using ${props.paymentSettings.card_provider}.`
                : "Pay securely using your card.",
            icon: CreditCardOutlined,
        });
    }

    return methods;
});

/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/

const total = computed(() => {
    return Number(props.subtotal || 0) + Number(shipping.value || 0);
});

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    return Number(amount || 0).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

/*
|--------------------------------------------------------------------------
| ITEM PRICE
|--------------------------------------------------------------------------
*/

const itemPrice = (item) => {
    return Number(item.sale_price ?? item.price ?? 0);
};

const itemTotal = (item) => {
    return itemPrice(item) * Number(item.quantity || 0);
};

/*
|--------------------------------------------------------------------------
| CALCULATE SHIPPING
|--------------------------------------------------------------------------
*/
const calculateShipping = async () => {
    if (!form.country) {
        return;
    }

    calculatingShipping.value = true;

    try {
        const response = await axios.post(
            "/client/checkout/shipping",
            {
                city: form.city,
                country: form.country,
            }
        );

        const data = response.data;

        if (data.success) {
            shipping.value = Number(data.shipping || 0);

            shippingLabel.value =
                data.shipping_label || "Shipping";
        }
    } catch (error) {
        console.error(
            "Shipping calculation failed:",
            error
        );

        if (error.response?.status === 419) {
            console.error(
                "CSRF token mismatch. Refresh the page and try again."
            );
        }
    } finally {
        calculatingShipping.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| SUBMIT
|--------------------------------------------------------------------------
*/

const submit = () => {
    // Basic protection
    if (form.processing) {
        return;
    }

    // Make sure a payment method has been selected
    if (!form.payment_method) {
        Swal.fire({
            icon: "warning",
            title: "Payment Method Required",
            text: "Please select a payment method before placing your order.",
            confirmButtonText: "OK",
            confirmButtonColor: "#d97706",
        });

        return;
    }

    // Confirm order before submitting
    Swal.fire({
        title: "Place your order?",
        text: `Your order total is K${formatMoney(total.value)}.`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Place Order",
        cancelButtonText: "Review Order",
        confirmButtonColor: "#111827",
        cancelButtonColor: "#d97706",
        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        // Show processing message
        Swal.fire({
            title: "Processing your order...",
            text: "Please wait while we place your order.",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        form.post("/client/checkout", {
            preserveScroll: true,

            onSuccess: () => {
                /*
                 * Laravel redirects to:
                 * checkout.confirmation
                 *
                 * Therefore Inertia will automatically
                 * navigate to the confirmation page.
                 */

                Swal.close();
            },

            onError: (errors) => {
                Swal.close();

                console.error("Checkout errors:", errors);

                let message =
                    "Please check your information and try again.";

                if (errors.cart) {
                    message = errors.cart;
                } else if (errors.customer_email) {
                    message = errors.customer_email;
                } else if (errors.first_name) {
                    message = errors.first_name;
                } else if (errors.last_name) {
                    message = errors.last_name;
                } else if (errors.address) {
                    message = errors.address;
                } else if (errors.city) {
                    message = errors.city;
                } else if (errors.country) {
                    message = errors.country;
                } else if (errors.customer_phone) {
                    message = errors.customer_phone;
                } else if (errors.payment_method) {
                    message = errors.payment_method;
                } else if (errors.mobile_money_number) {
                    message = errors.mobile_money_number;
                } else if (errors.cardholder_name) {
                    message = errors.cardholder_name;
                } else if (errors.card_number) {
                    message = errors.card_number;
                } else if (errors.card_expiry) {
                    message = errors.card_expiry;
                } else if (errors.card_cvv) {
                    message = errors.card_cvv;
                }

                Swal.fire({
                    icon: "error",
                    title: "Order Could Not Be Placed",
                    text: message,
                    confirmButtonText: "Try Again",
                    confirmButtonColor: "#d97706",
                });
            },

            onFinish: () => {
                // Processing state is automatically reset by useForm
            },
        });
    });
};

// const submit = () => {
//     form.post(route("client.checkout.store"), {
//         preserveScroll: true,
//     });
// };

// const submit = () => {
//     form.post("/client/checkout", {
//         preserveScroll: true,
//     });
// };

/*
|--------------------------------------------------------------------------
| INPUT CLASS
|--------------------------------------------------------------------------
*/

const inputClass =
    "checkout-input";

/*
|--------------------------------------------------------------------------
| SELECT FIRST PAYMENT METHOD
|--------------------------------------------------------------------------
*/

onMounted(() => {
    if (!form.payment_method && paymentMethods.value.length > 0) {
        form.payment_method = paymentMethods.value[0].value;
    }

    calculateShipping();
});
</script>

<template>

    <Head title="Checkout" />
    <UserFashionLayout>
        <div class="checkout-page">

            <!-- ============================================================
             HEADER
        ============================================================= -->

            <header class="checkout-header">
                <div class="checkout-header-inner">

                    <div class="header-left">

                        <Link href="/client/cart" class="back-button">
                            <ArrowLeftOutlined />
                            <span>Back to Cart</span>
                        </Link>

                        <div class="header-divider"></div>

                        <div>
                            <h1>Checkout</h1>

                            <p>
                                Complete your order securely
                            </p>
                        </div>

                    </div>

                    <div class="secure-checkout">
                        <SafetyOutlined />

                        <span>
                            Secure Checkout
                        </span>
                    </div>

                </div>
            </header>


            <!-- ============================================================
             MAIN
        ============================================================= -->

            <main class="checkout-container">

                <!-- CHECKOUT STEPS -->

                <div class="checkout-steps">

                    <div class="checkout-step active">
                        <div class="step-number">
                            1
                        </div>

                        <div>
                            <strong>
                                Checkout
                            </strong>

                            <span>
                                Delivery & Payment
                            </span>
                        </div>
                    </div>

                    <div class="step-line"></div>

                    <div class="checkout-step">
                        <div class="step-number muted">
                            2
                        </div>

                        <div>
                            <strong>
                                Confirmation
                            </strong>

                            <span>
                                Order placed
                            </span>
                        </div>
                    </div>

                </div>


                <!-- ========================================================
                 GRID
            ========================================================= -->

                <form @submit.prevent="submit" class="checkout-grid">

                    <!-- ====================================================
                     LEFT COLUMN
                ===================================================== -->

                    <div class="checkout-left">


                        <!-- =================================================
                         CONTACT INFORMATION
                    ================================================== -->

                        <section class="checkout-card">

                            <div class="card-heading">

                                <div class="heading-icon">
                                    <MailOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Contact Information
                                    </h2>

                                    <p>
                                        We'll use this information to contact
                                        you about your order.
                                    </p>
                                </div>

                            </div>


                            <div class="form-group">

                                <label>
                                    Email Address
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper">

                                    <MailOutlined />

                                    <input v-model="form.customer_email" type="email" placeholder="you@example.com"
                                        :class="inputClass" />

                                </div>

                                <p v-if="form.errors.customer_email" class="error-message">
                                    {{ form.errors.customer_email }}
                                </p>

                            </div>

                        </section>


                        <!-- =================================================
                         DELIVERY INFORMATION
                    ================================================== -->

                        <section class="checkout-card">

                            <div class="card-heading">

                                <div class="heading-icon">
                                    <EnvironmentOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Delivery Information
                                    </h2>

                                    <p>
                                        Tell us where you would like your order
                                        delivered.
                                    </p>
                                </div>

                            </div>


                            <!-- NAME -->

                            <div class="form-grid">

                                <div class="form-group">

                                    <label>
                                        First Name
                                        <span>*</span>
                                    </label>

                                    <div class="input-wrapper">

                                        <UserOutlined />

                                        <input v-model="form.first_name" type="text" placeholder="First name"
                                            :class="inputClass" />

                                    </div>

                                    <p v-if="form.errors.first_name" class="error-message">
                                        {{ form.errors.first_name }}
                                    </p>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Last Name
                                        <span>*</span>
                                    </label>

                                    <div class="input-wrapper">

                                        <UserOutlined />

                                        <input v-model="form.last_name" type="text" placeholder="Last name"
                                            :class="inputClass" />

                                    </div>

                                    <p v-if="form.errors.last_name" class="error-message">
                                        {{ form.errors.last_name }}
                                    </p>

                                </div>

                            </div>


                            <!-- ADDRESS -->

                            <div class="form-group">

                                <label>
                                    Delivery Address
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper">

                                    <EnvironmentOutlined />

                                    <input v-model="form.address" type="text" placeholder="Street address"
                                        :class="inputClass" />

                                </div>

                                <p v-if="form.errors.address" class="error-message">
                                    {{ form.errors.address }}
                                </p>

                            </div>


                            <!-- CITY + COUNTRY -->

                            <div class="form-grid">

                                <div class="form-group">

                                    <label>
                                        City
                                        <span>*</span>
                                    </label>

                                    <input v-model="form.city" type="text" placeholder="Lusaka" :class="inputClass"
                                        @blur="calculateShipping" />

                                    <p v-if="form.errors.city" class="error-message">
                                        {{ form.errors.city }}
                                    </p>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Country
                                        <span>*</span>
                                    </label>

                                    <input v-model="form.country" type="text" placeholder="Zambia" :class="inputClass"
                                        @blur="calculateShipping" />

                                    <p v-if="form.errors.country" class="error-message">
                                        {{ form.errors.country }}
                                    </p>

                                </div>

                            </div>


                            <!-- PHONE -->

                            <div class="form-group">

                                <label>
                                    Phone Number
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper">

                                    <PhoneOutlined />

                                    <input v-model="form.customer_phone" type="tel" placeholder="+260 97 123 4567"
                                        :class="inputClass" />

                                </div>

                                <p v-if="form.errors.customer_phone" class="error-message">
                                    {{ form.errors.customer_phone }}
                                </p>

                            </div>

                        </section>


                        <!-- =================================================
                         PAYMENT
                    ================================================== -->

                        <section class="checkout-card">

                            <div class="card-heading">

                                <div class="heading-icon">
                                    <CreditCardOutlined />
                                </div>

                                <div>
                                    <h2>
                                        Payment Method
                                    </h2>

                                    <p>
                                        Choose how you would like to pay.
                                    </p>
                                </div>

                            </div>


                            <!-- PAYMENT METHODS -->

                            <div v-if="paymentMethods.length" class="payment-list">

                                <label v-for="method in paymentMethods" :key="method.value" class="payment-option"
                                    :class="{
                                        selected:
                                            form.payment_method ===
                                            method.value,
                                    }">

                                    <input v-model="form.payment_method" type="radio" :value="method.value" />

                                    <div class="payment-icon">
                                        <component :is="method.icon" />
                                    </div>

                                    <div class="payment-content">

                                        <div class="payment-title">
                                            {{ method.label }}
                                        </div>

                                        <div class="payment-description">
                                            {{ method.description }}
                                        </div>

                                    </div>

                                    <div v-if="
                                        form.payment_method ===
                                        method.value
                                    " class="selected-check">
                                        <CheckCircleOutlined />
                                    </div>

                                </label>

                            </div>


                            <!-- NO PAYMENT -->

                            <div v-else class="payment-empty">
                                No payment methods are currently
                                available. Please contact the store.
                            </div>


                            <!-- MOBILE MONEY -->

                            <div v-if="
                                form.payment_method ===
                                'mobile_money'
                            " class="conditional-payment">

                                <label>
                                    Mobile Money Number
                                    <span>*</span>
                                </label>

                                <input v-model="form.mobile_money_number
                                    " type="text" placeholder="Enter mobile money number" :class="inputClass" />

                                <p v-if="
                                    form.errors
                                        .mobile_money_number
                                " class="error-message">
                                    {{
                                        form.errors
                                            .mobile_money_number
                                    }}
                                </p>

                            </div>


                            <!-- CARD -->

                            <div v-if="
                                form.payment_method === 'card'
                            " class="conditional-payment">

                                <label>
                                    Cardholder Name
                                    <span>*</span>
                                </label>

                                <input v-model="form.cardholder_name
                                    " type="text" placeholder="Name on card" :class="inputClass" />

                                <p v-if="
                                    form.errors.cardholder_name
                                " class="error-message">
                                    {{
                                        form.errors.cardholder_name
                                    }}
                                </p>

                            </div>

                            <div v-if="form.payment_method === 'card'" class="mt-5 space-y-5">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Cardholder Name
                                    </label>

                                    <input v-model="form.cardholder_name" type="text" placeholder="Name on card"
                                        :class="inputClass" />

                                    <p v-if="form.errors.cardholder_name" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.cardholder_name }}
                                    </p>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        Card Number
                                    </label>

                                    <input v-model="form.card_number" type="text" inputmode="numeric"
                                        autocomplete="cc-number" placeholder="Card number" :class="inputClass" />

                                    <p v-if="form.errors.card_number" class="mt-1 text-sm text-red-500">
                                        {{ form.errors.card_number }}
                                    </p>
                                </div>

                                <div class="grid gap-5 sm:grid-cols-2">

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Expiry Date
                                        </label>

                                        <input v-model="form.card_expiry" type="text" maxlength="5" placeholder="MM/YY"
                                            :class="inputClass" />

                                        <p v-if="form.errors.card_expiry" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.card_expiry }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            CVV
                                        </label>

                                        <input v-model="form.card_cvv" type="password" maxlength="4" inputmode="numeric"
                                            autocomplete="cc-csc" placeholder="CVV" :class="inputClass" />

                                        <p v-if="form.errors.card_cvv" class="mt-1 text-sm text-red-500">
                                            {{ form.errors.card_cvv }}
                                        </p>
                                    </div>

                                </div>
                            </div>

                        </section>

                    </div>


                    <!-- ====================================================
                     RIGHT COLUMN
                ===================================================== -->

                    <aside class="checkout-right">

                        <div class="order-summary">


                            <!-- SUMMARY HEADER -->

                            <div class="summary-header">

                                <div>

                                    <h2>
                                        Your Order
                                    </h2>

                                    <p>
                                        {{ totalItems }}
                                        {{ totalItems === 1 ? "item" : "items" }}
                                    </p>

                                </div>

                                <ShoppingOutlined />

                            </div>


                            <!-- ORDER ITEMS -->

                            <div class="order-items">

                                <div v-for="item in cart" :key="item.id" class="order-item">

                                    <div class="product-image">

                                        <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" />

                                        <ShoppingOutlined v-else />

                                    </div>


                                    <div class="product-info">

                                        <h3>
                                            {{ item.name }}
                                        </h3>

                                        <p>
                                            SKU:
                                            {{ item.sku || "N/A" }}
                                        </p>

                                        <div class="product-bottom">

                                            <span>
                                                Qty:
                                                {{ item.quantity }}
                                            </span>

                                            <strong>
                                                K{{
                                                    formatMoney(
                                                        itemTotal(item)
                                                    )
                                                }}
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- TOTALS -->

                            <div class="summary-divider"></div>

                            <div class="summary-row">

                                <span>
                                    Subtotal
                                </span>

                                <strong>
                                    K{{ formatMoney(subtotal) }}
                                </strong>

                            </div>


                            <div class="summary-row">

                                <span>
                                    {{ shippingLabel }}
                                </span>

                                <strong>

                                    <span v-if="calculatingShipping" class="calculating">
                                        <LoadingOutlined spin />
                                        Calculating
                                    </span>

                                    <span v-else>
                                        K{{ formatMoney(shipping) }}
                                    </span>

                                </strong>

                            </div>


                            <div class="summary-divider"></div>


                            <!-- GRAND TOTAL -->

                            <div class="grand-total">

                                <span>
                                    Total
                                </span>

                                <strong>
                                    K{{ formatMoney(total) }}
                                </strong>

                            </div>


                            <!-- PLACE ORDER -->
                            <!-- PLACE ORDER -->
                            <button type="submit" class="place-order-button" :disabled="form.processing ||
                                paymentMethods.length === 0 ||
                                calculatingShipping
                                ">
                                <LoadingOutlined v-if="form.processing" spin />

                                <CheckCircleOutlined v-else />

                                <span>
                                    {{
                                        form.processing
                                            ? "Processing Order..."
                                    : "Place Order"
                                    }}
                                </span>
                            </button>

                            <!-- <button
                            type="submit"
                            class="place-order-button"
                            :disabled="
                                form.processing ||
                                paymentMethods.length === 0 ||
                                calculatingShipping
                            "
                        >

                            <LoadingOutlined
                                v-if="form.processing"
                                spin
                            />

                            <CheckCircleOutlined
                                v-else
                            />

                            <span>
                                {{
                                    form.processing
                                        ? "Processing Order..."
                                        : "Place Order"
                                }}
                            </span>

                        </button> -->


                            <!-- SECURITY -->

                            <div class="secure-note">

                                <SafetyOutlined />

                                <span>
                                    Your information is protected
                                    and securely processed.
                                </span>

                            </div>


                            <p class="terms">
                                By placing your order, you agree to
                                our terms and conditions.
                            </p>

                        </div>

                    </aside>

                </form>

            </main>

        </div>
    </UserFashionLayout>

</template>


<style scoped>
/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.checkout-page {
    min-height: 100vh;
    background: #faf9f7;
    color: #1f2937;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.checkout-header {
    width: 100%;
    background: #ffffff;
    border-bottom: 1px solid #eee9e2;
}

.checkout-header-inner {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    padding: 22px 24px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 24px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 18px;
}

.header-left h1 {
    margin: 0;
    font-size: 25px;
    font-weight: 800;
    color: #111827;
}

.header-left p {
    margin: 4px 0 0;
    color: #9ca3af;
    font-size: 13px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 10px 14px;

    border: 1px solid #e5e7eb;
    border-radius: 10px;

    color: #4b5563;
    background: #ffffff;

    font-size: 13px;
    font-weight: 600;

    text-decoration: none;

    transition: all 0.2s ease;
}

.back-button:hover {
    color: #d97706;
    border-color: #f3c76b;
    background: #fffbeb;
}

.header-divider {
    width: 1px;
    height: 42px;
    background: #e5e7eb;
}

.secure-checkout {
    display: flex;
    align-items: center;
    gap: 8px;

    color: #059669;
    font-size: 13px;
    font-weight: 600;
}


/*
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
*/

.checkout-container {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;

    padding: 30px 24px 60px;
}


/*
|--------------------------------------------------------------------------
| STEPS
|--------------------------------------------------------------------------
*/

.checkout-steps {
    display: flex;
    align-items: center;

    max-width: 620px;
    margin: 0 auto 34px;
}

.checkout-step {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.step-number {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #f59e0b;
    color: white;

    font-size: 14px;
    font-weight: 700;
}

.step-number.muted {
    background: #e5e7eb;
    color: #6b7280;
}

.checkout-step strong {
    display: block;

    font-size: 13px;
    font-weight: 700;
    color: #111827;
}

.checkout-step span {
    display: block;

    margin-top: 2px;

    font-size: 11px;
    color: #9ca3af;
}

.step-line {
    flex: 1;
    height: 1px;
    margin: 0 18px;

    background: #e5e7eb;
}


/*
|--------------------------------------------------------------------------
| MAIN GRID
|--------------------------------------------------------------------------
*/

.checkout-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 390px;

    gap: 28px;

    align-items: start;
}

.checkout-left {
    min-width: 0;

    display: flex;
    flex-direction: column;
    gap: 22px;
}

.checkout-right {
    min-width: 0;
}


/*
|--------------------------------------------------------------------------
| CARDS
|--------------------------------------------------------------------------
*/

.checkout-card,
.order-summary {
    width: 100%;

    background: #ffffff;

    border: 1px solid #eee9e2;

    border-radius: 18px;

    box-shadow:
        0 3px 15px rgba(0, 0, 0, 0.035);
}

.checkout-card {
    padding: 26px;
}

.card-heading {
    display: flex;
    align-items: flex-start;
    gap: 14px;

    margin-bottom: 24px;
}

.heading-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #fff7e6;
    color: #d97706;

    font-size: 18px;
}

.card-heading h2 {
    margin: 0;

    font-size: 17px;
    font-weight: 800;

    color: #111827;
}

.card-heading p {
    margin: 5px 0 0;

    color: #9ca3af;

    font-size: 12px;
    line-height: 1.5;
}


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;
}

.form-group {
    min-width: 0;
    margin-bottom: 18px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label,
.conditional-payment label {
    display: block;

    margin-bottom: 8px;

    font-size: 13px;
    font-weight: 700;

    color: #374151;
}

.form-group label span,
.conditional-payment label span {
    color: #ef4444;
    margin-left: 2px;
}

.input-wrapper {
    position: relative;
}

.input-wrapper> :first-child {
    position: absolute;

    left: 14px;
    top: 50%;

    transform: translateY(-50%);

    color: #9ca3af;

    z-index: 1;
}

.input-wrapper .checkout-input {
    padding-left: 42px;
}

.checkout-input {
    width: 100%;
    height: 46px;

    padding: 0 14px;

    border: 1px solid #e5e7eb;

    border-radius: 10px;

    background: #ffffff;

    color: #1f2937;

    font-size: 13px;

    outline: none;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;

    box-sizing: border-box;
}

.checkout-input::placeholder {
    color: #b6bbc3;
}

.checkout-input:focus {
    border-color: #f59e0b;

    box-shadow:
        0 0 0 3px rgba(245, 158, 11, 0.1);
}

.error-message {
    margin: 6px 0 0;

    color: #ef4444;

    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

.payment-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.payment-option {
    position: relative;

    display: flex;
    align-items: flex-start;
    gap: 13px;

    width: 100%;

    padding: 15px;

    border: 1px solid #e5e7eb;
    border-radius: 12px;

    cursor: pointer;

    transition:
        border-color 0.2s ease,
        background 0.2s ease,
        box-shadow 0.2s ease;

    box-sizing: border-box;
}

.payment-option:hover {
    border-color: #f3c76b;
    background: #fffdf8;
}

.payment-option.selected {
    border-color: #f59e0b;
    background: #fffaf0;

    box-shadow:
        0 0 0 1px rgba(245, 158, 11, 0.08);
}

.payment-option input {
    width: 16px;
    height: 16px;

    margin-top: 3px;

    accent-color: #f59e0b;

    flex-shrink: 0;
}

.payment-icon {
    width: 38px;
    height: 38px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #fff3d6;
    color: #d97706;

    font-size: 17px;
}

.payment-content {
    min-width: 0;
    flex: 1;
}

.payment-title {
    color: #111827;

    font-size: 13px;
    font-weight: 700;
}

.payment-description {
    margin-top: 3px;

    color: #9ca3af;

    font-size: 11px;
    line-height: 1.5;
}

.selected-check {
    color: #f59e0b;

    font-size: 18px;
}

.payment-empty {
    padding: 14px;

    border-radius: 10px;

    background: #fef2f2;

    color: #dc2626;

    font-size: 12px;
}

.conditional-payment {
    margin-top: 18px;

    padding-top: 18px;

    border-top: 1px solid #f1f1f1;
}


/*
|--------------------------------------------------------------------------
| ORDER SUMMARY
|--------------------------------------------------------------------------
*/

.order-summary {
    position: sticky;
    top: 24px;

    padding: 24px;
}

.summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding-bottom: 18px;

    border-bottom: 1px solid #f0ede8;
}

.summary-header h2 {
    margin: 0;

    font-size: 18px;
    font-weight: 800;

    color: #111827;
}

.summary-header p {
    margin: 4px 0 0;

    color: #9ca3af;

    font-size: 11px;
}

.summary-header>svg {
    color: #f59e0b;
    font-size: 22px;
}


/*
|--------------------------------------------------------------------------
| ORDER ITEMS
|--------------------------------------------------------------------------
*/

.order-items {
    display: flex;
    flex-direction: column;

    gap: 15px;

    padding: 20px 0;
}

.order-item {
    display: flex;

    gap: 12px;

    min-width: 0;
}

.product-image {
    width: 62px;
    height: 68px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;

    border-radius: 10px;

    background: #f7f7f7;

    color: #b6bbc3;
}

.product-image img {
    width: 100%;
    height: 100%;

    display: block;

    object-fit: cover;
}

.product-info {
    min-width: 0;
    flex: 1;
}

.product-info h3 {
    margin: 0;

    overflow: hidden;

    color: #111827;

    font-size: 12px;
    font-weight: 700;

    white-space: nowrap;
    text-overflow: ellipsis;
}

.product-info>p {
    margin: 4px 0 8px;

    color: #9ca3af;

    font-size: 10px;
}

.product-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 8px;
}

.product-bottom span {
    color: #6b7280;

    font-size: 10px;
}

.product-bottom strong {
    color: #374151;

    font-size: 12px;
}


/*
|--------------------------------------------------------------------------
| SUMMARY TOTALS
|--------------------------------------------------------------------------
*/

.summary-divider {
    width: 100%;
    height: 1px;

    background: #f0ede8;

    margin: 3px 0 15px;
}

.summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 12px;
}

.summary-row span {
    color: #6b7280;

    font-size: 12px;
}

.summary-row strong {
    color: #374151;

    font-size: 12px;
}

.calculating {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    color: #9ca3af;
}

.grand-total {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;
}

.grand-total span {
    color: #111827;

    font-size: 16px;
    font-weight: 800;
}

.grand-total strong {
    color: #d97706;

    font-size: 23px;
    font-weight: 800;
}


/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

.place-order-button {
    width: 100%;
    height: 50px;

    margin-top: 22px;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 9px;

    border: 0;
    border-radius: 11px;

    background: #111827;
    color: #ffffff;

    font-size: 13px;
    font-weight: 700;

    cursor: pointer;

    transition:
        background 0.2s ease,
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.place-order-button:hover:not(:disabled) {
    background: #d97706;

    transform: translateY(-1px);

    box-shadow:
        0 7px 18px rgba(217, 119, 6, 0.2);
}

.place-order-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}


/*
|--------------------------------------------------------------------------
| SECURITY
|--------------------------------------------------------------------------
*/

.secure-note {
    display: flex;
    align-items: flex-start;
    justify-content: center;

    gap: 7px;

    margin-top: 16px;

    color: #059669;

    text-align: center;

    font-size: 10px;
    line-height: 1.5;
}

.secure-note span {
    color: #6b7280;
}

.terms {
    margin: 12px 0 0;

    color: #a1a1aa;

    text-align: center;

    font-size: 9px;
    line-height: 1.5;
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE - TABLET
|--------------------------------------------------------------------------
*/

@media (max-width: 1100px) {

    .checkout-grid {
        grid-template-columns:
            minmax(0, 1fr) 340px;

        gap: 20px;
    }

    .checkout-card {
        padding: 22px;
    }

    .order-summary {
        padding: 20px;
    }

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE - MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .checkout-header-inner {
        padding: 18px;
    }

    .checkout-container {
        padding: 24px 18px 50px;
    }

    .checkout-grid {
        grid-template-columns: 1fr;

        gap: 22px;
    }

    .checkout-right {
        order: -1;
    }

    .order-summary {
        position: relative;
        top: auto;
    }

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE - SMALL TABLET
|--------------------------------------------------------------------------
*/

@media (max-width: 640px) {

    .checkout-header-inner {
        align-items: flex-start;
    }

    .header-left {
        gap: 12px;
    }

    .header-divider {
        display: none;
    }

    .header-left h1 {
        font-size: 21px;
    }

    .header-left p {
        font-size: 11px;
    }

    .secure-checkout {
        display: none;
    }

    .back-button span {
        display: none;
    }

    .back-button {
        width: 38px;
        height: 38px;

        padding: 0;

        justify-content: center;
    }

    .checkout-steps {
        margin-bottom: 25px;
    }

    .checkout-step span {
        display: none;
    }

    .step-line {
        margin: 0 12px;
    }

    .checkout-card {
        padding: 18px;

        border-radius: 15px;
    }

    .order-summary {
        padding: 18px;

        border-radius: 15px;
    }

    .form-grid {
        grid-template-columns: 1fr;

        gap: 0;
    }

    .card-heading {
        margin-bottom: 20px;
    }

}


/*
|--------------------------------------------------------------------------
| VERY SMALL SCREENS
|--------------------------------------------------------------------------
*/

@media (max-width: 420px) {

    .checkout-container {
        padding-left: 12px;
        padding-right: 12px;
    }

    .checkout-header-inner {
        padding-left: 12px;
        padding-right: 12px;
    }

    .checkout-card,
    .order-summary {
        border-radius: 13px;
    }

    .checkout-card {
        padding: 15px;
    }

    .order-summary {
        padding: 15px;
    }

    .card-heading h2 {
        font-size: 15px;
    }

    .card-heading p {
        font-size: 11px;
    }

    .grand-total strong {
        font-size: 20px;
    }

}
</style>