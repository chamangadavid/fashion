<!-- resources\js\Pages\MyFashions\Settings\Payments.vue -->
<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    settings: {
        type: Object,
        default: () => ({}),
    },

})

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = useForm({

    cash_on_delivery:
        props.settings?.cash_on_delivery ?? true,

    mobile_money:
        props.settings?.mobile_money ?? true,

    card:
        props.settings?.card ?? false,

    mobile_money_provider:
        props.settings?.mobile_money_provider ?? '',

    mobile_money_number:
        props.settings?.mobile_money_number ?? '',

    card_provider:
        props.settings?.card_provider ?? '',

    currency:
        props.settings?.currency ?? 'ZMW',

    payment_instructions:
        props.settings?.payment_instructions ?? '',

})

/*
|--------------------------------------------------------------------------
| SUBMIT
|--------------------------------------------------------------------------
*/

const saveSettings = () => {

    form.post('/fashion/settings/payments', {

        preserveScroll: true,

        onSuccess: () => {

            Swal.fire({

                icon: 'success',

                title: 'Payment Settings Saved',

                text: 'Your payment settings have been updated successfully.',

                timer: 1800,

                showConfirmButton: false,

            })

        },

        onError: () => {

            Swal.fire({

                icon: 'error',

                title: 'Save Failed',

                text: 'Unable to save payment settings. Please check the form and try again.',

            })

        },

    })

}

</script>


<template>

    <Head title="Payment Settings" />

    <MyFashionLayout>

        <div class="settings-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Payment Settings
                    </h1>

                    <p>
                        Configure the payment methods available to your customers.
                    </p>

                </div>

            </div>


            <!-- =====================================================
                 PAYMENT METHODS
            ====================================================== -->

            <div class="settings-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Payment Methods
                        </h2>

                        <p>
                            Enable or disable payment methods for your store.
                        </p>

                    </div>

                </div>


                <div class="payment-methods">


                    <!-- =================================================
                         CASH ON DELIVERY
                    ================================================== -->

                    <div class="payment-method">

                        <div class="payment-info">

                            <div class="payment-icon">
                                💵
                            </div>

                            <div>

                                <strong>
                                    Cash on Delivery
                                </strong>

                                <span>
                                    Customers pay when their order is delivered.
                                </span>

                            </div>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                v-model="form.cash_on_delivery"
                            />

                            <span class="slider"></span>

                        </label>

                    </div>


                    <!-- =================================================
                         MOBILE MONEY
                    ================================================== -->

                    <div class="payment-method">

                        <div class="payment-info">

                            <div class="payment-icon">
                                📱
                            </div>

                            <div>

                                <strong>
                                    Mobile Money
                                </strong>

                                <span>
                                    Accept payments through mobile money.
                                </span>

                            </div>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                v-model="form.mobile_money"
                            />

                            <span class="slider"></span>

                        </label>

                    </div>


                    <!-- =================================================
                         CARD
                    ================================================== -->

                    <div class="payment-method">

                        <div class="payment-info">

                            <div class="payment-icon">
                                💳
                            </div>

                            <div>

                                <strong>
                                    Card Payment
                                </strong>

                                <span>
                                    Accept Visa, Mastercard and other supported cards.
                                </span>

                            </div>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                v-model="form.card"
                            />

                            <span class="slider"></span>

                        </label>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 MOBILE MONEY CONFIGURATION
            ====================================================== -->

            <div
                v-if="form.mobile_money"
                class="settings-card"
            >

                <div class="card-header">

                    <div>

                        <h2>
                            Mobile Money Configuration
                        </h2>

                        <p>
                            Configure the mobile money account used to receive payments.
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <!-- PROVIDER -->

                    <div class="form-group">

                        <label>
                            Mobile Money Provider
                        </label>

                        <select
                            v-model="form.mobile_money_provider"
                            class="form-control"
                        >

                            <option value="">
                                Select Provider
                            </option>

                            <option value="Airtel Money">
                                Airtel Money
                            </option>

                            <option value="MTN Money">
                                MTN Money
                            </option>

                            <option value="Zamtel Money">
                                Zamtel Money
                            </option>

                        </select>

                        <div
                            v-if="form.errors.mobile_money_provider"
                            class="error"
                        >
                            {{ form.errors.mobile_money_provider }}
                        </div>

                    </div>


                    <!-- NUMBER -->

                    <div class="form-group">

                        <label>
                            Mobile Money Number
                        </label>

                        <input
                            type="text"
                            v-model="form.mobile_money_number"
                            class="form-control"
                            placeholder="e.g. 0977123456"
                        />

                        <div
                            v-if="form.errors.mobile_money_number"
                            class="error"
                        >
                            {{ form.errors.mobile_money_number }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 CARD CONFIGURATION
            ====================================================== -->

            <div
                v-if="form.card"
                class="settings-card"
            >

                <div class="card-header">

                    <div>

                        <h2>
                            Card Payment Configuration
                        </h2>

                        <p>
                            Configure your card payment provider.
                        </p>

                    </div>

                </div>


                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Card Payment Provider
                        </label>

                        <select
                            v-model="form.card_provider"
                            class="form-control"
                        >

                            <option value="">
                                Select Provider
                            </option>

                            <option value="Flutterwave">
                                Flutterwave
                            </option>

                            <option value="Paystack">
                                Paystack
                            </option>

                            <option value="Stripe">
                                Stripe
                            </option>

                        </select>

                        <div
                            v-if="form.errors.card_provider"
                            class="error"
                        >
                            {{ form.errors.card_provider }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 GENERAL PAYMENT CONFIGURATION
            ====================================================== -->

            <div class="settings-card">

                <div class="card-header">

                    <div>

                        <h2>
                            General Payment Configuration
                        </h2>

                        <p>
                            Configure general payment information for your store.
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <!-- CURRENCY -->

                    <div class="form-group">

                        <label>
                            Currency
                        </label>

                        <select
                            v-model="form.currency"
                            class="form-control"
                        >

                            <option value="ZMW">
                                ZMW - Zambian Kwacha
                            </option>

                            <option value="USD">
                                USD - US Dollar
                            </option>

                            <option value="EUR">
                                EUR - Euro
                            </option>

                            <option value="GBP">
                                GBP - British Pound
                            </option>

                        </select>

                    </div>

                </div>


                <!-- PAYMENT INSTRUCTIONS -->

                <div class="form-group">

                    <label>
                        Payment Instructions
                    </label>

                    <textarea
                        v-model="form.payment_instructions"
                        class="form-control textarea"
                        rows="5"
                        placeholder="Enter payment instructions that customers should see during checkout..."
                    ></textarea>

                    <small>
                        These instructions can be displayed to customers during checkout.
                    </small>

                </div>

            </div>


            <!-- =====================================================
                 SAVE
            ====================================================== -->

            <div class="save-section">

                <button
                    type="button"
                    class="save-button"
                    :disabled="form.processing"
                    @click="saveSettings"
                >

                    <span v-if="form.processing">
                        Saving...
                    </span>

                    <span v-else>
                        Save Payment Settings
                    </span>

                </button>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

.settings-page {

    padding: 10px;

    width: 100%;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {

    margin-bottom: 25px;

}

.page-header h1 {

    margin: 0;

    color: #111827;

    font-size: 28px;

    font-weight: 700;

}

.page-header p {

    margin: 7px 0 0;

    color: #6b7280;

    font-size: 13px;

}


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.settings-card {

    margin-bottom: 18px;

    overflow: hidden;

    background: #ffffff;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

}


/*
|--------------------------------------------------------------------------
| CARD HEADER
|--------------------------------------------------------------------------
*/

.card-header {

    padding: 18px;

    border-bottom: 1px solid #edf0f2;

}

.card-header h2 {

    margin: 0;

    color: #111827;

    font-size: 15px;

    font-weight: 700;

}

.card-header p {

    margin: 5px 0 0;

    color: #777;

    font-size: 11px;

}


/*
|--------------------------------------------------------------------------
| PAYMENT METHODS
|--------------------------------------------------------------------------
*/

.payment-methods {

    padding: 5px 18px 18px;

}

.payment-method {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 17px 0;

    border-bottom: 1px solid #f0f1f2;

}

.payment-method:last-child {

    border-bottom: none;

}

.payment-info {

    display: flex;

    align-items: center;

    gap: 13px;

}

.payment-icon {

    width: 42px;

    height: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: #f3f7f6;

    font-size: 20px;

}

.payment-info strong {

    display: block;

    color: #111827;

    font-size: 13px;

}

.payment-info span {

    display: block;

    margin-top: 4px;

    color: #888;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| SWITCH
|--------------------------------------------------------------------------
*/

.switch {

    position: relative;

    display: inline-block;

    width: 46px;

    height: 24px;

    flex-shrink: 0;

}

.switch input {

    width: 0;

    height: 0;

    opacity: 0;

}

.slider {

    position: absolute;

    inset: 0;

    cursor: pointer;

    background: #d1d5db;

    border-radius: 30px;

    transition: .25s ease;

}

.slider::before {

    content: "";

    position: absolute;

    width: 18px;

    height: 18px;

    left: 3px;

    top: 3px;

    background: white;

    border-radius: 50%;

    box-shadow: 0 1px 3px rgba(0,0,0,.2);

    transition: .25s ease;

}

.switch input:checked + .slider {

    background: #075c59;

}

.switch input:checked + .slider::before {

    transform: translateX(22px);

}


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

.form-grid {

    display: grid;

    grid-template-columns: repeat(2, minmax(0, 1fr));

    gap: 18px;

    padding: 20px;

}

.form-group {

    display: flex;

    flex-direction: column;

    gap: 7px;

    padding: 0 20px 20px;

}

.form-grid .form-group {

    padding: 0;

}

.form-group label {

    color: #374151;

    font-size: 11px;

    font-weight: 700;

}

.form-control {

    width: 100%;

    box-sizing: border-box;

    padding: 10px 12px;

    border: 1px solid #dfe3e6;

    border-radius: 7px;

    background: #ffffff;

    color: #374151;

    font-size: 12px;

    outline: none;

    transition: .2s ease;

}

.form-control:focus {

    border-color: #075c59;

    box-shadow:
        0 0 0 3px rgba(7, 92, 89, .08);

}

.textarea {

    resize: vertical;

    min-height: 110px;

}

.form-group small {

    color: #9ca3af;

    font-size: 9px;

}

.error {

    color: #dc2626;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

.save-section {

    display: flex;

    justify-content: flex-end;

    padding: 5px 0 20px;

}

.save-button {

    border: none;

    border-radius: 8px;

    padding: 11px 20px;

    background: #075c59;

    color: white;

    font-size: 11px;

    font-weight: 700;

    cursor: pointer;

    transition: .2s ease;

}

.save-button:hover:not(:disabled) {

    background: #064d4a;

}

.save-button:disabled {

    cursor: not-allowed;

    opacity: .6;

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 700px) {

    .settings-page {

        padding: 5px;

    }

    .page-header h1 {

        font-size: 23px;

    }

    .form-grid {

        grid-template-columns: 1fr;

    }

    .payment-method {

        align-items: flex-start;

    }

    .payment-info {

        align-items: flex-start;

    }

    .save-section {

        justify-content: stretch;

    }

    .save-button {

        width: 100%;

    }

}

</style>