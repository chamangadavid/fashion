<!-- resources/js/Pages/MyFashions/Products/Create.vue -->

<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import {
    Link,
    useForm,
} from '@inertiajs/vue3';

import {
    ArrowLeftOutlined,
    SaveOutlined,
    UploadOutlined,
} from '@ant-design/icons-vue';

import { ref } from 'vue';


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

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

    product_category_id: '',

    name: '',

    sku: '',

    description: '',

    price: '',

    compare_price: '',

    cost_price: '',

    is_new_arrival: false,

    stock_quantity: 0,

    low_stock_threshold: 5,

    image: null,



    status: 'active',

    featured: false,

});


/*
|--------------------------------------------------------------------------
| IMAGE PREVIEW
|--------------------------------------------------------------------------
*/

const imagePreview = ref(null);


const handleImageUpload = (event) => {

    const file = event.target.files[0];

    if (!file) {
        return;
    }

    form.image = file;

    imagePreview.value = URL.createObjectURL(file);

};


/*
|--------------------------------------------------------------------------
| SUBMIT
|--------------------------------------------------------------------------
*/

const submit = () => {

    form.post('/fashion/products/store', {

        forceFormData: true,

        preserveScroll: true,

        onSuccess: () => {

            form.reset();

            imagePreview.value = null;

        },

    });

};

</script>


<template>

    <MyFashionLayout>

        <div class="product-create-page">


            <!-- =====================================================
                 PAGE HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <div class="breadcrumb">

                        <Link href="/products">
                            Products
                        </Link>

                        <span>
                            /
                        </span>

                        <span>
                            Add Product
                        </span>

                    </div>


                    <h1>
                        Add Product
                    </h1>

                    <p>
                        Create a new fashion product.
                    </p>

                </div>


                <Link href="/products" class="back-button">

                    <ArrowLeftOutlined />

                    <span>
                        Back to Products
                    </span>

                </Link>

            </div>



            <!-- =====================================================
                 FORM
            ====================================================== -->

            <form @submit.prevent="submit" enctype="multipart/form-data">


                <!-- =================================================
                     BASIC INFORMATION
                ================================================== -->

                <div class="content-card">

                    <div class="section-header">

                        <div>

                            <h2>
                                Product Information
                            </h2>

                            <p>
                                Enter the basic information about your product.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">


                        <!-- PRODUCT NAME -->

                        <div class="form-group full-width">

                            <label>
                                Product Name
                                <span>*</span>
                            </label>

                            <input v-model="form.name" type="text" placeholder="e.g. Elegant Evening Dress"
                                class="form-control" />

                            <div v-if="form.errors.name" class="error">
                                {{ form.errors.name }}
                            </div>

                        </div>



                        <!-- SKU -->

                        <div class="form-group">

                            <label>
                                SKU
                                <span>*</span>
                            </label>

                            <input v-model="form.sku" type="text" placeholder="e.g. DRESS-001" class="form-control" />

                            <div v-if="form.errors.sku" class="error">
                                {{ form.errors.sku }}
                            </div>

                        </div>



                        <!-- CATEGORY -->

                        <div class="form-group">

                            <label>
                                Category
                            </label>

                            <select v-model="form.product_category_id" class="form-control">

                                <option value="">
                                    Select Category
                                </option>

                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>

                            </select>

                            <div v-if="form.errors.product_category_id" class="error">
                                {{ form.errors.product_category_id }}
                            </div>

                        </div>



                        <!-- DESCRIPTION -->

                        <div class="form-group full-width">

                            <label>
                                Description
                            </label>

                            <textarea v-model="form.description" rows="5" placeholder="Describe the product..."
                                class="form-control"></textarea>

                            <div v-if="form.errors.description" class="error">
                                {{ form.errors.description }}
                            </div>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     PRICING
                ================================================== -->

                <div class="content-card">

                    <div class="section-header">

                        <div>

                            <h2>
                                Pricing
                            </h2>

                            <p>
                                Set the selling and cost prices.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">


                        <!-- PRICE -->

                        <div class="form-group">

                            <label>
                                Selling Price
                                <span>*</span>
                            </label>

                            <div class="input-with-prefix">

                                <span>
                                    ZMW
                                </span>

                                <input v-model="form.price" type="number" min="0" step="0.01" placeholder="0.00" />

                            </div>

                            <div v-if="form.errors.price" class="error">
                                {{ form.errors.price }}
                            </div>

                        </div>



                        <!-- COMPARE PRICE -->

                        <div class="form-group">

                            <label>
                                Compare Price
                            </label>

                            <div class="input-with-prefix">

                                <span>
                                    ZMW
                                </span>

                                <input v-model="form.compare_price" type="number" min="0" step="0.01"
                                    placeholder="0.00" />

                            </div>

                            <div v-if="form.errors.compare_price" class="error">
                                {{ form.errors.compare_price }}
                            </div>

                        </div>



                        <!-- COST PRICE -->

                        <div class="form-group">

                            <label>
                                Cost Price
                            </label>

                            <div class="input-with-prefix">

                                <span>
                                    ZMW
                                </span>

                                <input v-model="form.cost_price" type="number" min="0" step="0.01" placeholder="0.00" />

                            </div>

                            <div v-if="form.errors.cost_price" class="error">
                                {{ form.errors.cost_price }}
                            </div>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     INVENTORY
                ================================================== -->

                <div class="content-card">

                    <div class="section-header">

                        <div>

                            <h2>
                                Inventory
                            </h2>

                            <p>
                                Manage product stock levels.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">


                        <!-- STOCK -->

                        <div class="form-group">

                            <label>
                                Stock Quantity
                            </label>

                            <input v-model="form.stock_quantity" type="number" min="0" class="form-control" />

                            <div v-if="form.errors.stock_quantity" class="error">
                                {{ form.errors.stock_quantity }}
                            </div>

                        </div>



                        <!-- LOW STOCK -->

                        <div class="form-group">

                            <label>
                                Low Stock Threshold
                            </label>

                            <input v-model="form.low_stock_threshold" type="number" min="0" class="form-control" />

                            <div v-if="form.errors.low_stock_threshold" class="error">
                                {{ form.errors.low_stock_threshold }}
                            </div>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     PRODUCT IMAGE
                ================================================== -->

                <div class="content-card">

                    <div class="section-header">

                        <div>

                            <h2>
                                Product Image
                            </h2>

                            <p>
                                Upload the main image for this product.
                            </p>

                        </div>

                    </div>


                    <div class="image-upload-area">


                        <div v-if="imagePreview" class="image-preview">

                            <img :src="imagePreview" alt="Product preview" />

                        </div>


                        <label v-else class="upload-box">

                            <UploadOutlined />

                            <strong>
                                Upload Product Image
                            </strong>

                            <span>
                                PNG, JPG or JPEG
                            </span>

                            <input type="file" accept="image/png,image/jpeg,image/jpg" @change="handleImageUpload" />

                        </label>


                        <div v-if="imagePreview" class="change-image">

                            <label>

                                <UploadOutlined />

                                Change Image

                                <input type="file" accept="image/png,image/jpeg,image/jpg"
                                    @change="handleImageUpload" />

                            </label>

                        </div>


                        <div v-if="form.errors.image" class="error">
                            {{ form.errors.image }}
                        </div>

                    </div>

                </div>



                <!-- =================================================
                     STATUS
                ================================================== -->

                <div class="content-card">

                    <div class="section-header">

                        <div>

                            <h2>
                                Product Status
                            </h2>

                            <p>
                                Control how this product appears in your store.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        <!-- STATUS -->

                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select v-model="form.status" class="form-control">

                                <option value="active">
                                    Active
                                </option>

                                <option value="draft">
                                    Draft
                                </option>

                                <option value="out_of_stock">
                                    Out of Stock
                                </option>

                            </select>

                            <div v-if="form.errors.status" class="error">
                                {{ form.errors.status }}
                            </div>

                        </div>


                        <!-- FEATURED -->

                        <div class="form-group checkbox-group">

                            <label class="checkbox-label">

                                <input v-model="form.featured" type="checkbox" />

                                <span>
                                    Featured Product
                                </span>

                            </label>

                            <small>
                                Display this product in featured sections.
                            </small>

                        </div>


                        <!-- NEW ARRIVAL -->

                        <div class="form-group checkbox-group">

                            <label class="checkbox-label">

                                <input id="is_new_arrival" v-model="form.is_new_arrival" type="checkbox" />

                                <span>
                                    New Arrival
                                </span>

                            </label>

                            <small>
                                Display this product in the New Arrivals section.
                            </small>

                        </div>

                    </div>



                </div>



                <!-- =================================================
                     FORM ACTIONS
                ================================================== -->

                <div class="form-actions">

                    <Link href="/products" class="cancel-button">
                        Cancel
                    </Link>


                    <button type="submit" class="save-button" :disabled="form.processing">

                        <SaveOutlined />

                        <span>
                            {{
                                form.processing
                                    ? 'Saving...'
                                    : 'Save Product'
                            }}
                        </span>

                    </button>

                </div>


            </form>

        </div>

    </MyFashionLayout>

</template>


<style scoped>
.product-create-page {
    padding: 10px;
    max-width: 1200px;
    margin: 0 auto;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 25px;
    gap: 20px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 13px;
    color: #777;
}

.breadcrumb a {
    color: #111827;
    text-decoration: none;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.page-header p {
    margin: 5px 0 0;
    color: #777;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    background: white;
}

.back-button:hover {
    background: #f8f8f8;
}


/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

.content-card {
    background: white;
    padding: 28px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
}

.section-header {
    margin-bottom: 24px;
}

.section-header h2 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: #111827;
}

.section-header p {
    margin: 5px 0 0;
    color: #777;
    font-size: 14px;
}


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

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
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.form-group label span {
    color: #dc2626;
}

.form-control {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    color: #111827;
    font-size: 14px;
    outline: none;
    transition: .2s;
}

.form-control:focus {
    border-color: #111827;
    box-shadow: 0 0 0 3px rgba(17, 24, 39, .08);
}

textarea.form-control {
    resize: vertical;
}


/*
|--------------------------------------------------------------------------
| MONEY INPUT
|--------------------------------------------------------------------------
*/

.input-with-prefix {
    display: flex;
    align-items: stretch;
}

.input-with-prefix span {
    display: flex;
    align-items: center;
    padding: 0 13px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-size: 13px;
    color: #555;
}

.input-with-prefix input {
    flex: 1;
    min-width: 0;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 0 8px 8px 0;
    outline: none;
    font-size: 14px;
}

.input-with-prefix input:focus {
    border-color: #111827;
}


/*
|--------------------------------------------------------------------------
| IMAGE UPLOAD
|--------------------------------------------------------------------------
*/

.image-upload-area {
    width: 100%;
}

.upload-box {
    min-height: 220px;
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: .2s;
}

.upload-box:hover {
    border-color: #111827;
    background: #fafafa;
}

.upload-box :deep(svg) {
    font-size: 30px;
    margin-bottom: 10px;
}

.upload-box strong {
    font-size: 15px;
    color: #111827;
}

.upload-box span {
    margin-top: 5px;
    color: #888;
    font-size: 13px;
}

.upload-box input,
.change-image input {
    display: none;
}

.image-preview {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #ddd;
}

.image-preview img {
    display: block;
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.change-image {
    text-align: center;
    margin-top: 12px;
}

.change-image label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    color: #111827;
    font-size: 14px;
}


/*
|--------------------------------------------------------------------------
| CHECKBOX
|--------------------------------------------------------------------------
*/

.checkbox-group {
    justify-content: center;
}

.checkbox-label {
    display: flex !important;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-label input {
    width: 17px;
    height: 17px;
}

.checkbox-group small {
    margin-left: 27px;
    color: #888;
}


/*
|--------------------------------------------------------------------------
| ERRORS
|--------------------------------------------------------------------------
*/

.error {
    margin-top: 6px;
    color: #dc2626;
    font-size: 13px;
}


/*
|--------------------------------------------------------------------------
| ACTIONS
|--------------------------------------------------------------------------
*/

.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 10px;
    margin-bottom: 30px;
}

.cancel-button,
.save-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

.cancel-button {
    border: 1px solid #d1d5db;
    color: #374151;
    background: white;
}

.save-button {
    border: none;
    background: #111827;
    color: white;
}

.save-button:hover {
    background: #000;
}

.save-button:disabled {
    opacity: .6;
    cursor: not-allowed;
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 768px) {

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: auto;
    }

    .content-card {
        padding: 20px;
    }

    .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .cancel-button,
    .save-button {
        width: 100%;
    }

}
</style>