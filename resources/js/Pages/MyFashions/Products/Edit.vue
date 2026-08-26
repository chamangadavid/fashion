<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({

    product: {
        type: Object,
        required: true,
    },

    categories: {
        type: Array,
        default: () => [],
    },

});


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = useForm({

    product_category_id:
        props.product.product_category_id ?? '',

    name:
        props.product.name ?? '',

    sku:
        props.product.sku ?? '',

    description:
        props.product.description ?? '',

    price:
        props.product.price ?? '',

    sale_price:
        props.product.sale_price ?? '',

    cost_price:
        props.product.cost_price ?? '',

    stock_quantity:
        props.product.stock_quantity ?? 0,

    low_stock_threshold:
        props.product.low_stock_threshold ?? 5,

    is_active:
        Boolean(props.product.is_active),

    is_featured: Boolean(props.product.is_featured),

    is_new_arrival: Boolean(props.product.is_new_arrival),

    image: null,

});


/*
|--------------------------------------------------------------------------
| IMAGE PREVIEW
|--------------------------------------------------------------------------
*/

const imagePreview = computed(() => {

    if (form.image) {

        return URL.createObjectURL(form.image);

    }

    if (props.product.image) {

        return `/storage/${props.product.image}`;

    }

    return null;

});


/*
|--------------------------------------------------------------------------
| IMAGE SELECT
|--------------------------------------------------------------------------
*/

const handleImageChange = (event) => {

    const file = event.target.files?.[0];

    if (!file) {

        return;

    }

    form.image = file;

};


/*
|--------------------------------------------------------------------------
| SUBMIT
|--------------------------------------------------------------------------
*/

const submit = () => {

    form
        .transform((data) => ({
            ...data,
            _method: 'PUT',
        }))
        .post(`/fashion/products/${props.product.id}`, {

            forceFormData: true,

            preserveScroll: true,

            onSuccess: () => {

                // Inertia will redirect from the controller.

            },

        });

};

</script>


<template>

    <Head :title="`Edit ${product.name}`" />

    <MyFashionLayout>

        <div class="page">

            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Edit Product
                    </h1>

                    <p>
                        Update product information, pricing,
                        inventory and image.
                    </p>

                </div>


                <div class="header-actions">

                    <Link
                        :href="`/fashion/products/${product.id}`"
                        class="secondary-button"
                    >
                        View Product
                    </Link>

                    <Link
                        href="/fashion/products"
                        class="secondary-button"
                    >
                        Back to Products
                    </Link>

                </div>

            </div>


            <!-- =====================================================
                 FORM
            ====================================================== -->

            <form
                @submit.prevent="submit"
                class="product-form"
            >

                <!-- =================================================
                     BASIC INFORMATION
                ================================================== -->

                <div class="content-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Product Information
                            </h2>

                            <p>
                                Basic information about your product.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        <!-- PRODUCT NAME -->

                        <div class="form-group">

                            <label>
                                Product Name
                                <span>*</span>
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Enter product name"
                                class="form-input"
                            />

                            <small
                                v-if="form.errors.name"
                                class="error"
                            >
                                {{ form.errors.name }}
                            </small>

                        </div>


                        <!-- SKU -->

                        <div class="form-group">

                            <label>
                                SKU
                                <span>*</span>
                            </label>

                            <input
                                v-model="form.sku"
                                type="text"
                                placeholder="e.g. DRESS-001"
                                class="form-input"
                            />

                            <small
                                v-if="form.errors.sku"
                                class="error"
                            >
                                {{ form.errors.sku }}
                            </small>

                        </div>


                        <!-- CATEGORY -->

                        <div class="form-group">

                            <label>
                                Category
                                <span>*</span>
                            </label>

                            <select
                                v-model="form.product_category_id"
                                class="form-input"
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>

                            </select>

                            <small
                                v-if="form.errors.product_category_id"
                                class="error"
                            >
                                {{ form.errors.product_category_id }}
                            </small>

                        </div>


                        <!-- DESCRIPTION -->

                        <div class="form-group full-width">

                            <label>
                                Description
                            </label>

                            <textarea
                                v-model="form.description"
                                rows="5"
                                placeholder="Describe your product..."
                                class="form-input"
                            ></textarea>

                            <small
                                v-if="form.errors.description"
                                class="error"
                            >
                                {{ form.errors.description }}
                            </small>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     PRICING
                ================================================== -->

                <div class="content-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Pricing
                            </h2>

                            <p>
                                Set your product prices.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        <!-- PRICE -->

                        <div class="form-group">

                            <label>
                                Price
                                <span>*</span>
                            </label>

                            <div class="input-prefix">

                                <span>
                                    ZMW
                                </span>

                                <input
                                    v-model="form.price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                />

                            </div>

                            <small
                                v-if="form.errors.price"
                                class="error"
                            >
                                {{ form.errors.price }}
                            </small>

                        </div>


                        <!-- SALE PRICE -->

                        <div class="form-group">

                            <label>
                                Sale Price
                            </label>

                            <div class="input-prefix">

                                <span>
                                    ZMW
                                </span>

                                <input
                                    v-model="form.sale_price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                />

                            </div>

                            <small
                                v-if="form.errors.sale_price"
                                class="error"
                            >
                                {{ form.errors.sale_price }}
                            </small>

                        </div>


                        <!-- COST PRICE -->

                        <div class="form-group">

                            <label>
                                Cost Price
                            </label>

                            <div class="input-prefix">

                                <span>
                                    ZMW
                                </span>

                                <input
                                    v-model="form.cost_price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                />

                            </div>

                            <small
                                v-if="form.errors.cost_price"
                                class="error"
                            >
                                {{ form.errors.cost_price }}
                            </small>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     INVENTORY
                ================================================== -->

                <div class="content-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Inventory
                            </h2>

                            <p>
                                Manage product stock.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        <!-- STOCK -->

                        <div class="form-group">

                            <label>
                                Stock Quantity
                                <span>*</span>
                            </label>

                            <input
                                v-model="form.stock_quantity"
                                type="number"
                                min="0"
                                class="form-input"
                            />

                            <small
                                v-if="form.errors.stock_quantity"
                                class="error"
                            >
                                {{ form.errors.stock_quantity }}
                            </small>

                        </div>


                        <!-- LOW STOCK -->

                        <div class="form-group">

                            <label>
                                Low Stock Threshold
                            </label>

                            <input
                                v-model="form.low_stock_threshold"
                                type="number"
                                min="0"
                                class="form-input"
                            />

                            <small
                                v-if="form.errors.low_stock_threshold"
                                class="error"
                            >
                                {{ form.errors.low_stock_threshold }}
                            </small>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     IMAGE
                ================================================== -->

                <div class="content-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Product Image
                            </h2>

                            <p>
                                Upload a new image or keep the current image.
                            </p>

                        </div>

                    </div>


                    <div class="image-section">

                        <!-- PREVIEW -->

                        <div
                            v-if="imagePreview"
                            class="image-preview"
                        >

                            <img
                                :src="imagePreview"
                                alt="Product image"
                            />

                        </div>


                        <div class="image-upload">

                            <label>
                                Change Product Image
                            </label>

                            <input
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                @change="handleImageChange"
                                class="file-input"
                            />

                            <small>
                                JPG, PNG or WEBP. Maximum recommended size
                                2MB.
                            </small>

                            <small
                                v-if="form.errors.image"
                                class="error"
                            >
                                {{ form.errors.image }}
                            </small>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     STATUS
                ================================================== -->

                <div class="content-card">

                    <div class="card-header">

                        <div>

                            <h2>
                                Product Status
                            </h2>

                            <p>
                                Control product visibility and promotion.
                            </p>

                        </div>

                    </div>


                    <div class="switches">

                        <!-- ACTIVE -->

                        <label class="switch-row">

                            <input
                                v-model="form.is_active"
                                type="checkbox"
                            />

                            <div>

                                <strong>
                                    Active Product
                                </strong>

                                <span>
                                    Customers can see and purchase
                                    this product.
                                </span>

                            </div>

                        </label>


                        <!-- FEATURED -->

                        <label class="switch-row">

                            <input
                                v-model="form.is_featured"
                                type="checkbox"
                            />

                            <div>

                                <strong>
                                    Featured Product
                                </strong>

                                <span>
                                    Display this product in featured
                                    product sections.
                                </span>

                            </div>

                        </label>

                         <!-- NEW ARRIVAL -->

                        <label class="switch-row">

                            <input
                                v-model="form.is_new_arrival"
                                type="checkbox"
                            />

                            <div>

                                <strong>
                                     New Arrival
                                </strong>

                                <span>
                                    Display this product in the New Arrivals section.

                                </span>

                            </div>

                        </label>

                    </div>

                </div>


                <!-- =================================================
                     ACTIONS
                ================================================== -->

                <div class="form-actions">

                    <Link
                        href="/fashion/products"
                        class="cancel-button"
                    >
                        Cancel
                    </Link>


                    <button
                        type="submit"
                        class="save-button"
                        :disabled="form.processing"
                    >

                        <span v-if="form.processing">
                            Updating...
                        </span>

                        <span v-else>
                            Update Product
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.page {
    padding: 10px;
    max-width: 1100px;
    margin: auto;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.page-header p {
    margin-top: 6px;
    color: #6b7280;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.secondary-button {
    display: inline-flex;
    align-items: center;
    padding: 9px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    text-decoration: none;
    color: #374151;
    background: white;
}

.secondary-button:hover {
    background: #f9fafb;
}

.content-card {
    background: white;
    border-radius: 12px;
    padding: 28px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
}

.card-header {
    margin-bottom: 22px;
}

.card-header h2 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: #111827;
}

.card-header p {
    margin-top: 5px;
    color: #6b7280;
    font-size: 14px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 7px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.form-group label span {
    color: #dc2626;
}

.form-input {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    outline: none;
    background: white;
    font-size: 14px;
}

.form-input:focus {
    border-color: #111827;
    box-shadow: 0 0 0 2px rgba(17, 24, 39, .08);
}

textarea.form-input {
    resize: vertical;
}

.input-prefix {
    display: flex;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
}

.input-prefix span {
    display: flex;
    align-items: center;
    padding: 0 12px;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 13px;
}

.input-prefix input {
    width: 100%;
    padding: 11px 13px;
    border: 0;
    outline: none;
}

.error {
    margin-top: 5px;
    color: #dc2626;
    font-size: 12px;
}

.image-section {
    display: flex;
    align-items: center;
    gap: 30px;
}

.image-preview {
    width: 180px;
    height: 180px;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    flex-shrink: 0;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-upload {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.image-upload label {
    font-size: 14px;
    font-weight: 600;
}

.image-upload small {
    color: #6b7280;
}

.file-input {
    padding: 10px;
    border: 1px dashed #d1d5db;
    border-radius: 8px;
}

.switches {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.switch-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
}

.switch-row input {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    accent-color: #111827;
}

.switch-row div {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.switch-row strong {
    font-size: 14px;
}

.switch-row span {
    font-size: 13px;
    color: #6b7280;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 30px;
}

.cancel-button,
.save-button {
    padding: 11px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.cancel-button {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.save-button {
    border: 0;
    background: #111827;
    color: white;
}

.save-button:disabled {
    opacity: .6;
    cursor: not-allowed;
}

@media (max-width: 768px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .secondary-button {
        flex: 1;
        justify-content: center;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: auto;
    }

    .image-section {
        flex-direction: column;
        align-items: flex-start;
    }

}

</style>