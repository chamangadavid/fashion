<!-- resources\js\Pages\MyFashions\Settings\Store.vue -->
<!-- resources/js/Pages/MyFashions/Settings/Store.vue -->

<script setup>

import { reactive, ref } from 'vue';

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import { Head, router } from '@inertiajs/vue3';

import Swal from 'sweetalert2';


const props = defineProps({

    settings: {
        type: Object,
        default: () => ({
            store_name: '',
            store_tagline: '',
            store_email: '',
            store_phone: '',
            store_address: '',
            store_city: '',
            store_country: 'Zambia',
            store_currency: 'ZMW',
            store_description: '',
            store_status: 'open',
            store_logo: '',
        }),
    },

});


const processing = ref(false);


const form = reactive({

    store_name: props.settings.store_name || '',

    store_tagline: props.settings.store_tagline || '',

    store_email: props.settings.store_email || '',

    store_phone: props.settings.store_phone || '',

    store_address: props.settings.store_address || '',

    store_city: props.settings.store_city || '',

    store_country: props.settings.store_country || 'Zambia',

    store_currency: props.settings.store_currency || 'ZMW',

    store_description: props.settings.store_description || '',

    store_status: props.settings.store_status || 'open',

    store_logo: props.settings.store_logo || '',

});


const saveSettings = () => {

    processing.value = true;

    router.put(
        route('fashions.settings.store.update'),
        form,
        {
            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({
                    icon: 'success',
                    title: 'Settings Saved',
                    text: 'Store settings updated successfully.',
                    confirmButtonColor: '#075c59',
                    timer: 2500,
                    timerProgressBar: true,
                });

            },

            onError: (errors) => {

                console.log(errors);

                Swal.fire({
                    icon: 'error',
                    title: 'Unable to Save',
                    text: 'Please check the form and try again.',
                    confirmButtonColor: '#075c59',
                });

            },

            onFinish: () => {

                processing.value = false;

            },
        }
    );

};

</script>


<template>

    <Head title="Store Settings" />


    <MyFashionLayout>

        <div class="settings-page">

            <!-- HEADER -->

            <div class="page-header">

                <div>

                    <h1>
                        Store Settings
                    </h1>

                    <p>
                        Configure your fashion store information.
                    </p>

                </div>

            </div>


            <!-- SETTINGS FORM -->

            <form
                @submit.prevent="saveSettings"
                class="settings-card"
            >

                <!-- STORE INFORMATION -->

                <div class="settings-section">

                    <h2>
                        Store Information
                    </h2>

                    <p class="section-description">
                        Basic information about your fashion store.
                    </p>


                    <div class="form-grid">

                        <!-- STORE NAME -->

                        <div class="form-group">

                            <label>
                                Store Name
                            </label>

                            <input
                                v-model="form.store_name"
                                type="text"
                                placeholder="My Fashion Store"
                            />

                        </div>


                        <!-- TAGLINE -->

                        <div class="form-group">

                            <label>
                                Store Tagline
                            </label>

                            <input
                                v-model="form.store_tagline"
                                type="text"
                                placeholder="Style that defines you"
                            />

                        </div>


                        <!-- EMAIL -->

                        <div class="form-group">

                            <label>
                                Store Email
                            </label>

                            <input
                                v-model="form.store_email"
                                type="email"
                                placeholder="info@example.com"
                            />

                        </div>


                        <!-- PHONE -->

                        <div class="form-group">

                            <label>
                                Store Phone
                            </label>

                            <input
                                v-model="form.store_phone"
                                type="tel"
                                placeholder="+260 97 123 4567"
                            />

                        </div>


                        <!-- ADDRESS -->

                        <div class="form-group full">

                            <label>
                                Store Address
                            </label>

                            <input
                                v-model="form.store_address"
                                type="text"
                                placeholder="Street address"
                            />

                        </div>


                        <!-- CITY -->

                        <div class="form-group">

                            <label>
                                City
                            </label>

                            <input
                                v-model="form.store_city"
                                type="text"
                                placeholder="Lusaka"
                            />

                        </div>


                        <!-- COUNTRY -->

                        <div class="form-group">

                            <label>
                                Country
                            </label>

                            <input
                                v-model="form.store_country"
                                type="text"
                                placeholder="Zambia"
                            />

                        </div>

                    </div>

                </div>


                <!-- STORE DESCRIPTION -->

                <div class="settings-section">

                    <h2>
                        Store Description
                    </h2>

                    <p class="section-description">
                        Describe your fashion store.
                    </p>

                    <div class="form-group">

                        <textarea
                            v-model="form.store_description"
                            rows="5"
                            placeholder="Tell customers about your fashion store..."
                        ></textarea>

                    </div>

                </div>


                <!-- STORE CONFIGURATION -->

                <div class="settings-section">

                    <h2>
                        Store Configuration
                    </h2>

                    <p class="section-description">
                        Configure your store status and currency.
                    </p>


                    <div class="form-grid">

                        <!-- CURRENCY -->

                        <div class="form-group">

                            <label>
                                Currency
                            </label>

                            <select
                                v-model="form.store_currency"
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


                        <!-- STATUS -->

                        <div class="form-group">

                            <label>
                                Store Status
                            </label>

                            <select
                                v-model="form.store_status"
                            >

                                <option value="open">
                                    Open
                                </option>

                                <option value="closed">
                                    Closed
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- STATUS NOTICE -->

                    <div
                        v-if="form.store_status === 'closed'"
                        class="closed-notice"
                    >

                        <strong>
                            Store Closed
                        </strong>

                        <p>
                            Customers will be informed that the store is
                            currently closed.
                        </p>

                    </div>

                </div>


                <!-- LOGO -->

                <div class="settings-section">

                    <h2>
                        Store Logo
                    </h2>

                    <p class="section-description">
                        Add the URL/path of your store logo.
                    </p>


                    <div class="form-group">

                        <label>
                            Logo URL
                        </label>

                        <input
                            v-model="form.store_logo"
                            type="text"
                            placeholder="/storage/store/logo.png"
                        />

                    </div>


                    <div
                        v-if="form.store_logo"
                        class="logo-preview"
                    >

                        <img
                            :src="form.store_logo"
                            alt="Store Logo"
                        />

                    </div>

                </div>


                <!-- ACTIONS -->

                <div class="settings-actions">

                    <button
                        type="submit"
                        class="save-button"
                        :disabled="processing"
                    >

                        {{
                            processing
                                ? 'SAVING...'
                                : 'SAVE STORE SETTINGS'
                        }}

                    </button>

                </div>

            </form>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.settings-page {

    padding: 30px;

    max-width: 1100px;

    margin: auto;

}


.page-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;

}


.page-header h1 {

    margin: 0;

    font-size: 28px;

    font-weight: 600;

}


.page-header p {

    margin-top: 7px;

    color: #777;

    font-size: 13px;

}


.settings-card {

    background: #fff;

    border: 1px solid #eee;

}


.settings-section {

    padding: 30px;

    border-bottom: 1px solid #eee;

}


.settings-section:last-child {

    border-bottom: none;

}


.settings-section h2 {

    margin: 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 21px;

    font-weight: 400;

}


.section-description {

    margin: 7px 0 25px;

    color: #888;

    font-size: 11px;

}


.form-grid {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 20px;

}


.form-group {

    margin-bottom: 5px;

}


.form-group.full {

    grid-column: 1 / -1;

}


.form-group label {

    display: block;

    margin-bottom: 7px;

    font-size: 10px;

    letter-spacing: .5px;

    font-weight: 600;

}


.form-group input,
.form-group select,
.form-group textarea {

    width: 100%;

    box-sizing: border-box;

    padding: 13px;

    border: 1px solid #ddd;

    outline: none;

    background: #fff;

    font-size: 11px;

}


.form-group textarea {

    resize: vertical;

    line-height: 1.6;

}


.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {

    border-color: #075c59;

}


.closed-notice {

    margin-top: 20px;

    padding: 15px;

    border: 1px solid #ead9b2;

    background: #fffaf0;

}


.closed-notice strong {

    display: block;

    font-size: 11px;

}


.closed-notice p {

    margin: 5px 0 0;

    color: #777;

    font-size: 10px;

}


.logo-preview {

    margin-top: 15px;

    width: 120px;

    height: 120px;

    border: 1px solid #eee;

    display: flex;

    align-items: center;

    justify-content: center;

    overflow: hidden;

}


.logo-preview img {

    max-width: 100%;

    max-height: 100%;

    object-fit: contain;

}


.settings-actions {

    padding: 25px 30px;

    display: flex;

    justify-content: flex-end;

}


.save-button {

    padding: 14px 25px;

    border: none;

    background: #075c59;

    color: white;

    font-size: 10px;

    letter-spacing: 1.5px;

    cursor: pointer;

}


.save-button:hover {

    background: #064b48;

}


.save-button:disabled {

    opacity: .6;

    cursor: not-allowed;

}


@media (max-width: 700px) {

    .settings-page {

        padding: 20px;

    }

    .form-grid {

        grid-template-columns: 1fr;

    }

    .form-group.full {

        grid-column: auto;

    }

    .settings-section {

        padding: 22px;

    }

}

</style>