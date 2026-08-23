<!-- resources\js\Pages\MyFashions\Settings\Shipping.vue -->
<!-- resources/js/Pages/MyFashions/Settings/Shipping.vue -->

<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import Swal from 'sweetalert2'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    settings: {
        type: Object,
        default: () => ({
            shipping_enabled: true,
            shipping_method: 'flat_rate',
            shipping_amount: '0.00',
            free_shipping_enabled: false,
            free_shipping_minimum: '0.00',
            shipping_note: '',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = reactive({

    shipping_enabled:
        props.settings?.shipping_enabled ?? true,

    shipping_method:
        props.settings?.shipping_method || 'flat_rate',

    shipping_amount:
        props.settings?.shipping_amount || '0.00',

    free_shipping_enabled:
        props.settings?.free_shipping_enabled ?? false,

    free_shipping_minimum:
        props.settings?.free_shipping_minimum || '0.00',

    shipping_note:
        props.settings?.shipping_note || '',

})


/*
|--------------------------------------------------------------------------
| PROCESSING
|--------------------------------------------------------------------------
*/

const processing = ref(false)


/*
|--------------------------------------------------------------------------
| SAVE
|--------------------------------------------------------------------------
*/

const saveSettings = () => {

    processing.value = true

    router.post(
        '/fashion/settings/shipping',
        form,
        {

            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({

                    icon: 'success',

                    title: 'Shipping Settings Saved',

                    text: 'Your shipping settings have been updated successfully.',

                    timer: 1800,

                    showConfirmButton: false,

                })

            },

            onError: () => {

                Swal.fire({

                    icon: 'error',

                    title: 'Save Failed',

                    text: 'Unable to save shipping settings.',

                })

            },

            onFinish: () => {

                processing.value = false

            },

        }
    )

}

</script>


<template>

    <Head title="Shipping Settings" />


    <MyFashionLayout>

        <div class="settings-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Shipping Settings
                    </h1>

                    <p>
                        Configure how customers receive their orders.
                    </p>

                </div>

            </div>


            <!-- =====================================================
                 SHIPPING CARD
            ====================================================== -->

            <div class="settings-card">


                <!-- CARD HEADER -->

                <div class="card-header">

                    <div>

                        <h2>
                            Shipping Configuration
                        </h2>

                        <p>
                            Manage shipping charges and delivery options.
                        </p>

                    </div>

                </div>


                <!-- =================================================
                     FORM
                ================================================== -->

                <div class="form-body">


                    <!-- SHIPPING ENABLED -->

                    <div class="setting-row">

                        <div>

                            <strong>
                                Enable Shipping
                            </strong>

                            <p>
                                Allow customers to receive orders through
                                your shipping service.
                            </p>

                        </div>


                        <label class="switch">

                            <input
                                v-model="form.shipping_enabled"
                                type="checkbox"
                            />

                            <span class="slider"></span>

                        </label>

                    </div>


                    <!-- SHIPPING METHOD -->

                    <div class="form-group">

                        <label>
                            Shipping Method
                        </label>

                        <select
                            v-model="form.shipping_method"
                        >

                            <option value="free">
                                Free Shipping
                            </option>

                            <option value="flat_rate">
                                Flat Rate
                            </option>

                        </select>

                        <small>
                            Select how shipping charges are calculated.
                        </small>

                    </div>


                    <!-- SHIPPING AMOUNT -->

                    <div
                        v-if="form.shipping_method === 'flat_rate'"
                        class="form-group"
                    >

                        <label>
                            Shipping Amount
                        </label>

                        <input
                            v-model="form.shipping_amount"
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        />

                        <small>
                            The amount charged for delivery.
                        </small>

                    </div>


                    <!-- FREE SHIPPING -->

                    <div class="setting-row">

                        <div>

                            <strong>
                                Free Shipping
                            </strong>

                            <p>
                                Allow customers to qualify for free shipping
                                based on their order amount.
                            </p>

                        </div>


                        <label class="switch">

                            <input
                                v-model="form.free_shipping_enabled"
                                type="checkbox"
                            />

                            <span class="slider"></span>

                        </label>

                    </div>


                    <!-- FREE SHIPPING MINIMUM -->

                    <div
                        v-if="form.free_shipping_enabled"
                        class="form-group"
                    >

                        <label>
                            Free Shipping Minimum
                        </label>

                        <input
                            v-model="form.free_shipping_minimum"
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="500.00"
                        />

                        <small>
                            Orders at or above this amount qualify
                            for free shipping.
                        </small>

                    </div>


                    <!-- SHIPPING NOTE -->

                    <div class="form-group">

                        <label>
                            Shipping Note
                        </label>

                        <textarea
                            v-model="form.shipping_note"
                            rows="4"
                            placeholder="Example: Delivery within Lusaka takes 1–2 business days."
                        ></textarea>

                        <small>
                            This message can be displayed to customers
                            during checkout.
                        </small>

                    </div>


                </div>


                <!-- =================================================
                     FOOTER
                ================================================== -->

                <div class="card-footer">

                    <button
                        type="button"
                        class="save-button"
                        :disabled="processing"
                        @click="saveSettings"
                    >

                        <span v-if="processing">
                            Saving...
                        </span>

                        <span v-else>
                            Save Shipping Settings
                        </span>

                    </button>

                </div>


            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.settings-page {

    width: 100%;

    padding: 10px;

}


.page-header {

    margin-bottom: 22px;

}


.page-header h1 {

    margin: 0;

    color: #111827;

    font-size: 28px;

    font-weight: 700;

}


.page-header p {

    margin: 6px 0 0;

    color: #6b7280;

    font-size: 13px;

}


.settings-card {

    max-width: 900px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

    overflow: hidden;

}


.card-header {

    padding: 20px;

    border-bottom: 1px solid #edf0f2;

}


.card-header h2 {

    margin: 0;

    color: #111827;

    font-size: 16px;

    font-weight: 700;

}


.card-header p {

    margin: 5px 0 0;

    color: #777;

    font-size: 11px;

}


.form-body {

    padding: 22px;

}


.setting-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 18px 0;

    border-bottom: 1px solid #edf0f2;

    margin-bottom: 22px;

}


.setting-row strong {

    display: block;

    color: #111827;

    font-size: 12px;

}


.setting-row p {

    margin: 5px 0 0;

    color: #777;

    font-size: 10px;

    line-height: 1.5;

}


.form-group {

    margin-bottom: 22px;

}


.form-group label {

    display: block;

    margin-bottom: 7px;

    color: #374151;

    font-size: 11px;

    font-weight: 700;

}


.form-group input,
.form-group textarea,
.form-group select {

    width: 100%;

    box-sizing: border-box;

    padding: 11px 12px;

    border: 1px solid #dfe3e6;

    border-radius: 7px;

    background: white;

    color: #111827;

    font-size: 12px;

    outline: none;

}


.form-group textarea {

    resize: vertical;

}


.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {

    border-color: #075c59;

    box-shadow:
        0 0 0 3px rgba(7, 92, 89, .08);

}


.form-group small {

    display: block;

    margin-top: 5px;

    color: #9ca3af;

    font-size: 9px;

}


/* =====================================================
   SWITCH
===================================================== */

.switch {

    position: relative;

    width: 44px;

    height: 24px;

    flex-shrink: 0;

}


.switch input {

    opacity: 0;

    width: 0;

    height: 0;

}


.slider {

    position: absolute;

    inset: 0;

    background: #d1d5db;

    border-radius: 30px;

    cursor: pointer;

    transition: .2s;

}


.slider:before {

    content: "";

    position: absolute;

    width: 18px;

    height: 18px;

    left: 3px;

    top: 3px;

    background: white;

    border-radius: 50%;

    transition: .2s;

    box-shadow: 0 1px 3px rgba(0,0,0,.2);

}


.switch input:checked + .slider {

    background: #075c59;

}


.switch input:checked + .slider:before {

    transform: translateX(20px);

}


/* =====================================================
   FOOTER
===================================================== */

.card-footer {

    display: flex;

    justify-content: flex-end;

    padding: 16px 22px;

    border-top: 1px solid #edf0f2;

    background: #fafafa;

}


.save-button {

    min-width: 180px;

    padding: 10px 18px;

    border: none;

    border-radius: 7px;

    background: #075c59;

    color: white;

    font-size: 11px;

    font-weight: 700;

    cursor: pointer;

}


.save-button:hover:not(:disabled) {

    background: #064b48;

}


.save-button:disabled {

    opacity: .6;

    cursor: not-allowed;

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 650px) {

    .settings-page {

        padding: 5px;

    }


    .page-header h1 {

        font-size: 24px;

    }


    .form-body {

        padding: 16px;

    }


    .setting-row {

        align-items: flex-start;

    }


    .card-footer {

        padding: 14px 16px;

    }


    .save-button {

        width: 100%;

    }

}

</style>