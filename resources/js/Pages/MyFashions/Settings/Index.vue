<!-- resources\js\Pages\MyFashions\Settings\Index.vue -->
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
            store_name: '',
            store_email: '',
            store_phone: '',
            store_address: '',
            store_city: '',
            store_country: 'Zambia',
            currency: 'ZMW',
            timezone: 'Africa/Lusaka',
        }),
    },

})

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = reactive({

    store_name: props.settings?.store_name || '',
    store_email: props.settings?.store_email || '',
    store_phone: props.settings?.store_phone || '',
    store_address: props.settings?.store_address || '',
    store_city: props.settings?.store_city || '',
    store_country: props.settings?.store_country || 'Zambia',
    currency: props.settings?.currency || 'ZMW',
    timezone: props.settings?.timezone || 'Africa/Lusaka',

})

/*
|--------------------------------------------------------------------------
| LOADING
|--------------------------------------------------------------------------
*/

const processing = ref(false)

/*
|--------------------------------------------------------------------------
| SAVE SETTINGS
|--------------------------------------------------------------------------
*/

const saveSettings = () => {

    processing.value = true

    router.post('/fashion/settings', form,
        {
            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({

                    icon: 'success',

                    title: 'Settings Saved',

                    text: 'General store settings have been updated successfully.',

                    timer: 1800,

                    showConfirmButton: false,

                })

            },

            onError: () => {

                Swal.fire({

                    icon: 'error',

                    title: 'Save Failed',

                    text: 'Unable to save the store settings.',

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

    <Head title="General Settings" />

    <MyFashionLayout>

        <div class="settings-page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        General Settings
                    </h1>

                    <p>
                        Manage your fashion store's basic information.
                    </p>

                </div>

            </div>


            <!-- =====================================================
                 SETTINGS CARD
            ====================================================== -->

            <div class="settings-card">

                <div class="card-header">

                    <div>

                        <h2>
                            Store Information
                        </h2>

                        <p>
                            Basic information displayed throughout your store.
                        </p>

                    </div>

                </div>


                <div class="form-body">

                    <!-- =================================================
                         STORE NAME
                    ================================================== -->

                    <div class="form-group">

                        <label>
                            Store Name
                        </label>

                        <input v-model="form.store_name" type="text" placeholder="Enter store name" />

                        <small>
                            The name of your fashion store.
                        </small>

                    </div>


                    <!-- =================================================
                         EMAIL
                    ================================================== -->

                    <div class="form-row">

                        <div class="form-group">

                            <label>
                                Store Email
                            </label>

                            <input v-model="form.store_email" type="email" placeholder="store@example.com" />

                            <small>
                                Main email address for your store.
                            </small>

                        </div>


                        <!-- PHONE -->

                        <div class="form-group">

                            <label>
                                Store Phone
                            </label>

                            <input v-model="form.store_phone" type="text" placeholder="+260 XXX XXX XXX" />

                            <small>
                                Main contact number.
                            </small>

                        </div>

                    </div>


                    <!-- =================================================
                         ADDRESS
                    ================================================== -->

                    <div class="form-group">

                        <label>
                            Store Address
                        </label>

                        <textarea v-model="form.store_address" rows="3" placeholder="Enter store address"></textarea>

                        <small>
                            Physical address of your store.
                        </small>

                    </div>


                    <!-- =================================================
                         CITY / COUNTRY
                    ================================================== -->

                    <div class="form-row">

                        <div class="form-group">

                            <label>
                                City
                            </label>

                            <input v-model="form.store_city" type="text" placeholder="Lusaka" />

                        </div>


                        <div class="form-group">

                            <label>
                                Country
                            </label>

                            <input v-model="form.store_country" type="text" placeholder="Zambia" />

                        </div>

                    </div>


                    <!-- =================================================
                         CURRENCY / TIMEZONE
                    ================================================== -->

                    <div class="form-row">

                        <div class="form-group">

                            <label>
                                Currency
                            </label>

                            <select v-model="form.currency">

                                <option value="ZMW">
                                    ZMW - Zambian Kwacha
                                </option>

                                <option value="USD">
                                    USD - US Dollar
                                </option>

                                <option value="GBP">
                                    GBP - British Pound
                                </option>

                                <option value="EUR">
                                    EUR - Euro
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Timezone
                            </label>

                            <select v-model="form.timezone">

                                <option value="Africa/Lusaka">
                                    Africa/Lusaka
                                </option>

                                <option value="Africa/Johannesburg">
                                    Africa/Johannesburg
                                </option>

                                <option value="Africa/Nairobi">
                                    Africa/Nairobi
                                </option>

                                <option value="UTC">
                                    UTC
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- =====================================================
                     FOOTER
                ====================================================== -->

                <div class="card-footer">

                    <button type="button" class="save-button" :disabled="processing" @click="saveSettings">

                        <span v-if="processing">
                            Saving...
                        </span>

                        <span v-else>
                            Save Settings
                        </span>

                    </button>

                </div>

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

    width: 100%;

    padding: 10px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| CARD
|--------------------------------------------------------------------------
*/

.settings-card {

    max-width: 900px;

    background: white;

    border: 1px solid #edf0f2;

    border-radius: 12px;

    box-shadow:
        0 2px 10px rgba(0, 0, 0, .04);

    overflow: hidden;

}


/*
|--------------------------------------------------------------------------
| CARD HEADER
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

.form-body {

    padding: 22px;

}

.form-row {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;

}

.form-group {

    margin-bottom: 20px;

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

    transition: .2s ease;

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


/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.card-footer {

    display: flex;

    justify-content: flex-end;

    padding: 16px 22px;

    border-top: 1px solid #edf0f2;

    background: #fafafa;

}


/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

.save-button {

    min-width: 130px;

    padding: 10px 18px;

    border: none;

    border-radius: 7px;

    background: #075c59;

    color: white;

    font-size: 11px;

    font-weight: 700;

    cursor: pointer;

    transition: .2s ease;

}

.save-button:hover:not(:disabled) {

    background: #064b48;

}

.save-button:disabled {

    opacity: .6;

    cursor: not-allowed;

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 650px) {

    .settings-page {

        padding: 5px;

    }

    .page-header h1 {

        font-size: 24px;

    }

    .form-row {

        grid-template-columns: 1fr;

        gap: 0;

    }

    .form-body {

        padding: 16px;

    }

    .card-footer {

        padding: 14px 16px;

    }

    .save-button {

        width: 100%;

    }

}
</style>