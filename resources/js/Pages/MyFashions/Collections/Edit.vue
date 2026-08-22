<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    collection: {
        type: Object,
        required: true
    }
})

const form = useForm({
    name: props.collection.name ?? '',
    slug: props.collection.slug ?? '',
    description: props.collection.description ?? '',
    image: null,
    is_active: Boolean(props.collection.is_active),
    is_featured: Boolean(props.collection.is_featured),
    sort_order: props.collection.sort_order ?? 0,
})

const imagePreview = computed(() => {

    if (form.image) {
        return URL.createObjectURL(form.image)
    }

    if (props.collection.image) {
        return `/storage/${props.collection.image}`
    }

    return null

})

const submit = () => {

    form.post(
        `/fashion/collections/${props.collection.id}`,
        {
            forceFormData: true,
            preserveScroll: true,
            _method: 'PUT',
        }
    )

}

</script>

<template>

    <Head title="Edit Collection" />

    <MyFashionLayout>

        <div class="page">

            <div class="page-header">

                <div>

                    <h1>
                        Edit Collection
                    </h1>

                    <p>
                        Update collection information.
                    </p>

                </div>

                <Link
                    href="/fashion/collections"
                    class="back-button"
                >
                    Back to Collections
                </Link>

            </div>


            <form
                @submit.prevent="submit"
                class="content-card"
            >

                <h2>
                    Collection Information
                </h2>

                <p class="section-description">
                    Update the details of this collection.
                </p>


                <!-- NAME -->

                <div class="form-group">

                    <label>
                        Collection Name
                        <span>*</span>
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                    />

                    <div
                        v-if="form.errors.name"
                        class="error"
                    >
                        {{ form.errors.name }}
                    </div>

                </div>


                <!-- SLUG -->

                <div class="form-group">

                    <label>
                        Slug
                    </label>

                    <input
                        v-model="form.slug"
                        type="text"
                    />

                    <div
                        v-if="form.errors.slug"
                        class="error"
                    >
                        {{ form.errors.slug }}
                    </div>

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        v-model="form.description"
                        rows="5"
                    ></textarea>

                    <div
                        v-if="form.errors.description"
                        class="error"
                    >
                        {{ form.errors.description }}
                    </div>

                </div>


                <!-- IMAGE -->

                <div class="form-group">

                    <label>
                        Collection Image
                    </label>

                    <input
                        type="file"
                        accept="image/*"
                        @change="form.image = $event.target.files[0]"
                    />

                    <div
                        v-if="form.errors.image"
                        class="error"
                    >
                        {{ form.errors.image }}
                    </div>

                    <div
                        v-if="imagePreview"
                        class="image-preview"
                    >

                        <img
                            :src="imagePreview"
                            alt="Collection image"
                        />

                    </div>

                </div>


                <!-- SETTINGS -->

                <div class="settings-grid">

                    <div class="setting-box">

                        <div>

                            <strong>
                                Active Collection
                            </strong>

                            <p>
                                Make this collection visible.
                            </p>

                        </div>

                        <label class="switch">

                            <input
                                v-model="form.is_active"
                                type="checkbox"
                            />

                            <span></span>

                        </label>

                    </div>


                    <div class="setting-box">

                        <div>

                            <strong>
                                Featured Collection
                            </strong>

                            <p>
                                Show this collection as featured.
                            </p>

                        </div>

                        <label class="switch">

                            <input
                                v-model="form.is_featured"
                                type="checkbox"
                            />

                            <span></span>

                        </label>

                    </div>

                </div>


                <!-- SORT -->

                <div class="form-group">

                    <label>
                        Sort Order
                    </label>

                    <input
                        v-model="form.sort_order"
                        type="number"
                        min="0"
                    />

                </div>


                <!-- ACTIONS -->

                <div class="form-actions">

                    <Link
                        href="/fashion/collections"
                        class="cancel-button"
                    >
                        Cancel
                    </Link>

                    <button
                        type="submit"
                        class="save-button"
                        :disabled="form.processing"
                    >

                        {{
                            form.processing
                                ? 'Updating...'
                                : 'Update Collection'
                        }}

                    </button>

                </div>

            </form>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.page {
    padding: 10px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    color: #777;
    margin-top: 5px;
}

.back-button,
.cancel-button {
    padding: 9px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    background: white;
}

.content-card {
    max-width: 1000px;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.content-card h2 {
    margin: 0;
}

.section-description {
    color: #777;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 22px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
}

.form-group label span {
    color: #dc2626;
}

.form-group input,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
}

.form-group textarea {
    resize: vertical;
}

.error {
    margin-top: 6px;
    color: #dc2626;
    font-size: 12px;
}

.image-preview {
    width: 220px;
    height: 160px;
    margin-top: 15px;
    overflow: hidden;
    border-radius: 10px;
    background: #f5f5f5;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.settings-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.setting-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px;
    border: 1px solid #eee;
    border-radius: 10px;
}

.setting-box p {
    margin: 5px 0 0;
    color: #777;
    font-size: 12px;
}

.switch {
    position: relative;
    width: 46px;
    height: 24px;
}

.switch input {
    display: none;
}

.switch span {
    position: absolute;
    inset: 0;
    background: #ccc;
    border-radius: 30px;
    cursor: pointer;
}

.switch span::after {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    top: 3px;
    left: 3px;
    background: white;
    border-radius: 50%;
    transition: .2s;
}

.switch input:checked + span {
    background: #075c59;
}

.switch input:checked + span::after {
    transform: translateX(22px);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.save-button {
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    background: #111827;
    color: white;
    cursor: pointer;
}

.save-button:disabled {
    opacity: .6;
}

</style>