<script setup>

import { ref, computed } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';


import {
    PlusOutlined,
    SearchOutlined,
    EditOutlined,
    DeleteOutlined,
    StarOutlined,
    StarFilled,
    CheckCircleOutlined,
    StopOutlined,
    AppstoreOutlined,
    MoreOutlined,
} from '@ant-design/icons-vue'

import Swal from 'sweetalert2'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    categories: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
        }),
    },

    stats: {
        type: Object,
        default: () => ({
            total: 0,
            active: 0,
            inactive: 0,
            featured: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: 'all',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = ref(
    props.filters?.search ?? ''
)


const status = ref(
    props.filters?.status ?? 'all'
)


const performSearch = () => {

    router.get(
        '/fashion/products/categories',
        {
            search: search.value,
            status: status.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| MODAL
|--------------------------------------------------------------------------
*/

const showModal = ref(false)

const editingCategory = ref(null)


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = ref({

    name: '',
    description: '',
    image: null,
    is_active: true,
    is_featured: false,
    sort_order: 0,

})


const resetForm = () => {

    form.value = {

        name: '',
        description: '',
        image: null,
        is_active: true,
        is_featured: false,
        sort_order: 0,

    }

    editingCategory.value = null

}


/*
|--------------------------------------------------------------------------
| OPEN CREATE
|--------------------------------------------------------------------------
*/

const openCreate = () => {

    resetForm()

    showModal.value = true

}


/*
|--------------------------------------------------------------------------
| OPEN EDIT
|--------------------------------------------------------------------------
*/

const openEdit = (category) => {

    editingCategory.value = category

    form.value = {

        name: category.name ?? '',

        description:
            category.description ?? '',

        image: null,

        is_active:
            Boolean(category.is_active),

        is_featured:
            Boolean(category.is_featured),

        sort_order:
            category.sort_order ?? 0,

    }

    showModal.value = true

}


/*
|--------------------------------------------------------------------------
| CLOSE MODAL
|--------------------------------------------------------------------------
*/

const closeModal = () => {

    showModal.value = false

    resetForm()

}


/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

const handleImage = (event) => {

    form.value.image =
        event.target.files[0] ?? null

}


/*
|--------------------------------------------------------------------------
| SUBMIT
|--------------------------------------------------------------------------
*/

const submit = () => {

    const data = new FormData()

    data.append(
        'name',
        form.value.name
    )

    data.append(
        'description',
        form.value.description ?? ''
    )

    data.append(
        'is_active',
        form.value.is_active ? '1' : '0'
    )

    data.append(
        'is_featured',
        form.value.is_featured ? '1' : '0'
    )

    data.append(
        'sort_order',
        form.value.sort_order ?? 0
    )


    if (form.value.image) {

        data.append(
            'image',
            form.value.image
        )

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    if (editingCategory.value) {

        data.append(
            '_method',
            'PUT'
        )

        router.post(
            `/fashion/products/categories/${editingCategory.value.id}`,
            data,
            {
                preserveScroll: true,

                onSuccess: () => {

                    closeModal()

                },
            }
        )

        return

    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    router.post(
        '/fashion/products/categories',
        data,
        {
            preserveScroll: true,

            onSuccess: () => {

                closeModal()

            },
        }
    )

}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

const deleteCategory = (category) => {

    Swal.fire({

        title: 'Delete Category?',

        text:
            `Are you sure you want to delete "${category.name}"?`,

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Yes, delete it',

        cancelButtonText: 'Cancel',

    }).then((result) => {

        if (!result.isConfirmed) {
            return
        }


        router.delete(
            `/products/categories/${category.id}`,
            {
                preserveScroll: true,
            }
        )

    })

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

const toggleStatus = (category) => {

    router.patch(
        `/products/categories/${category.id}/toggle-status`,
        {},
        {
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| FEATURED
|--------------------------------------------------------------------------
*/

const toggleFeatured = (category) => {

    router.patch(
        `/products/categories/${category.id}/toggle-featured`,
        {},
        {
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const goToPage = (page) => {

    if (
        page < 1 ||
        page > props.categories.last_page
    ) {
        return
    }


    router.get(
        '/fashion/products/categories',
        {
            search: search.value,
            status: status.value,
            page,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {

    if (!image) {
        return null
    }

    return `/storage/${image}`

}

</script>


<template>

    <Head title="Product Categories" />
      <MyFashionLayout>
  <div class="categories-page">


        <!-- =========================================================
             HEADER
        ========================================================== -->

        <div class="page-header">

            <div>

                <h1>
                    Product Categories
                </h1>

                <p>
                    Manage your fashion product categories.
                </p>

            </div>


            <button
                class="primary-button"
                @click="openCreate"
            >

                <PlusOutlined />

                Add Category

            </button>

        </div>


        <!-- =========================================================
             STATISTICS
        ========================================================== -->

        <div class="stats-grid">


            <div class="stat-card">

                <div class="stat-icon">
                    <AppstoreOutlined />
                </div>

                <div>

                    <span>
                        Total Categories
                    </span>

                    <strong>
                        {{ props.stats.total }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon active">
                    <CheckCircleOutlined />
                </div>

                <div>

                    <span>
                        Active
                    </span>

                    <strong>
                        {{ props.stats.active }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon inactive">
                    <StopOutlined />
                </div>

                <div>

                    <span>
                        Inactive
                    </span>

                    <strong>
                        {{ props.stats.inactive }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon featured">
                    <StarFilled />
                </div>

                <div>

                    <span>
                        Featured
                    </span>

                    <strong>
                        {{ props.stats.featured }}
                    </strong>

                </div>

            </div>

        </div>


        <!-- =========================================================
             FILTERS
        ========================================================== -->

        <div class="filters-card">

            <div class="search-box">

                <SearchOutlined />

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search categories..."
                    @keyup.enter="performSearch"
                />

            </div>


            <select
                v-model="status"
                @change="performSearch"
            >

                <option value="all">
                    All Status
                </option>

                <option value="active">
                    Active
                </option>

                <option value="inactive">
                    Inactive
                </option>

            </select>


            <button
                class="search-button"
                @click="performSearch"
            >

                Search

            </button>

        </div>


        <!-- =========================================================
             TABLE
        ========================================================== -->

        <div class="table-card">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Category
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Featured
                            </th>

                            <th>
                                Order
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="category in props.categories.data"
                            :key="category.id"
                        >


                            <!-- CATEGORY -->

                            <td>

                                <div class="category-info">


                                    <div class="category-image">

                                        <img
                                            v-if="imageUrl(category.image)"
                                            :src="imageUrl(category.image)"
                                            :alt="category.name"
                                        />

                                        <AppstoreOutlined
                                            v-else
                                        />

                                    </div>


                                    <div>

                                        <strong>
                                            {{ category.name }}
                                        </strong>

                                        <small>
                                            {{ category.slug }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- DESCRIPTION -->

                            <td>

                                <span class="description">

                                    {{
                                        category.description ||
                                        'No description'
                                    }}

                                </span>

                            </td>


                            <!-- STATUS -->

                            <td>

                                <button
                                    class="status-button"
                                    :class="{
                                        active:
                                            category.is_active,
                                        inactive:
                                            !category.is_active
                                    }"
                                    @click="
                                        toggleStatus(category)
                                    "
                                >

                                    <CheckCircleOutlined
                                        v-if="category.is_active"
                                    />

                                    <StopOutlined
                                        v-else
                                    />

                                    {{
                                        category.is_active
                                            ? 'Active'
                                            : 'Inactive'
                                    }}

                                </button>

                            </td>


                            <!-- FEATURED -->

                            <td>

                                <button
                                    class="featured-button"
                                    @click="
                                        toggleFeatured(category)
                                    "
                                >

                                    <StarFilled
                                        v-if="
                                            category.is_featured
                                        "
                                    />

                                    <StarOutlined
                                        v-else
                                    />

                                    {{
                                        category.is_featured
                                            ? 'Featured'
                                            : 'Normal'
                                    }}

                                </button>

                            </td>


                            <!-- SORT -->

                            <td>

                                {{ category.sort_order }}

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div class="actions">

                                    <button
                                        title="Edit"
                                        @click="
                                            openEdit(category)
                                        "
                                    >

                                        <EditOutlined />

                                    </button>


                                    <button
                                        title="Delete"
                                        class="delete"
                                        @click="
                                            deleteCategory(category)
                                        "
                                    >

                                        <DeleteOutlined />

                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- EMPTY -->

                        <tr
                            v-if="
                                !props.categories.data.length
                            "
                        >

                            <td
                                colspan="6"
                                class="empty-state"
                            >

                                <AppstoreOutlined />

                                <h3>
                                    No categories found
                                </h3>

                                <p>
                                    Create your first product category.
                                </p>

                                <button
                                    class="primary-button"
                                    @click="openCreate"
                                >

                                    <PlusOutlined />

                                    Add Category

                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- =====================================================
                 PAGINATION
            ====================================================== -->

            <div
                v-if="
                    props.categories.last_page > 1
                "
                class="pagination"
            >

                <button
                    :disabled="
                        props.categories.current_page === 1
                    "
                    @click="
                        goToPage(
                            props.categories.current_page - 1
                        )
                    "
                >
                    Previous
                </button>


                <span>

                    Page
                    {{ props.categories.current_page }}
                    of
                    {{ props.categories.last_page }}

                </span>


                <button
                    :disabled="
                        props.categories.current_page ===
                        props.categories.last_page
                    "
                    @click="
                        goToPage(
                            props.categories.current_page + 1
                        )
                    "
                >
                    Next
                </button>

            </div>

        </div>


        <!-- =========================================================
             MODAL
        ========================================================== -->

        <div
            v-if="showModal"
            class="modal-overlay"
            @click.self="closeModal"
        >

            <div class="category-modal">


                <div class="modal-header">

                    <div>

                        <h2>

                            {{
                                editingCategory
                                    ? 'Edit Category'
                                    : 'Create Category'
                            }}

                        </h2>

                        <p>
                            {{
                                editingCategory
                                    ? 'Update category details.'
                                    : 'Add a new product category.'
                            }}
                        </p>

                    </div>


                    <button
                        @click="closeModal"
                    >

                        ×

                    </button>

                </div>


                <form
                    @submit.prevent="submit"
                >


                    <!-- NAME -->

                    <div class="form-group">

                        <label>
                            Category Name
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Dresses"
                            required
                        />

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="form-group">

                        <label>
                            Description
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Describe this category..."
                        ></textarea>

                    </div>


                    <!-- IMAGE -->

                    <div class="form-group">

                        <label>
                            Category Image
                        </label>

                        <input
                            type="file"
                            accept="image/*"
                            @change="handleImage"
                        />

                    </div>


                    <!-- SORT -->

                    <div class="form-group">

                        <label>
                            Sort Order
                        </label>

                        <input
                            v-model.number="
                                form.sort_order
                            "
                            type="number"
                            min="0"
                        />

                    </div>


                    <!-- OPTIONS -->

                    <div class="form-options">

                        <label>

                            <input
                                v-model="
                                    form.is_active
                                "
                                type="checkbox"
                            />

                            Active

                        </label>


                        <label>

                            <input
                                v-model="
                                    form.is_featured
                                "
                                type="checkbox"
                            />

                            Featured

                        </label>

                    </div>


                    <!-- ACTIONS -->

                    <div class="modal-actions">

                        <button
                            type="button"
                            class="cancel-button"
                            @click="closeModal"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="primary-button"
                        >

                            {{
                                editingCategory
                                    ? 'Update Category'
                                    : 'Create Category'
                            }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
      </MyFashionLayout>


  

</template>


<style scoped>

.categories-page {
    padding: 30px;
    min-height: 100vh;
    background: #f7f8fa;
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
    margin: 6px 0 0;
    color: #777;
}

.primary-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    background: #111;
    color: white;
    padding: 11px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 22px;
}

.stat-card {
    background: white;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-icon.active {
    background: #e9f9ef;
    color: #198754;
}

.stat-icon.inactive {
    background: #fff0f0;
    color: #dc3545;
}

.stat-icon.featured {
    background: #fff8df;
    color: #d99a00;
}

.stat-card span {
    display: block;
    color: #777;
    font-size: 13px;
}

.stat-card strong {
    display: block;
    font-size: 24px;
    margin-top: 3px;
}

.filters-card {
    background: white;
    padding: 18px;
    border-radius: 12px;
    border: 1px solid #eee;
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.search-box {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0 12px;
}

.search-box input {
    border: none;
    outline: none;
    width: 100%;
    padding: 11px 0;
}

.filters-card select {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0 14px;
}

.search-button {
    border: none;
    background: #222;
    color: white;
    border-radius: 8px;
    padding: 0 20px;
    cursor: pointer;
}

.table-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #eee;
    overflow: hidden;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    font-size: 13px;
    color: #777;
    background: #fafafa;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-image {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: #f1f1f1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-info strong {
    display: block;
}

.category-info small {
    color: #999;
}

.description {
    color: #666;
}

.status-button,
.featured-button {
    border: none;
    border-radius: 20px;
    padding: 7px 12px;
    cursor: pointer;
    display: inline-flex;
    gap: 6px;
    align-items: center;
}

.status-button.active {
    background: #e9f9ef;
    color: #198754;
}

.status-button.inactive {
    background: #fff0f0;
    color: #dc3545;
}

.featured-button {
    background: #fff8df;
    color: #b17b00;
}

.actions {
    display: flex;
    gap: 7px;
}

.actions button {
    border: none;
    background: #f4f4f4;
    width: 34px;
    height: 34px;
    border-radius: 7px;
    cursor: pointer;
}

.actions button.delete {
    color: #dc3545;
}

.empty-state {
    text-align: center;
    padding: 60px !important;
    color: #888;
}

.empty-state > span {
    font-size: 35px;
}

.empty-state h3 {
    color: #333;
    margin-bottom: 5px;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    padding: 20px;
}

.pagination button {
    border: 1px solid #ddd;
    background: white;
    padding: 8px 15px;
    border-radius: 7px;
    cursor: pointer;
}

.pagination button:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    padding: 20px;
}

.category-modal {
    background: white;
    width: 100%;
    max-width: 600px;
    border-radius: 14px;
    padding: 25px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.modal-header h2 {
    margin: 0;
}

.modal-header p {
    color: #777;
    margin-top: 5px;
}

.modal-header > button {
    border: none;
    background: none;
    font-size: 25px;
    cursor: pointer;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 7px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 11px;
    outline: none;
}

.form-options {
    display: flex;
    gap: 25px;
    margin-bottom: 25px;
}

.form-options label {
    display: flex;
    align-items: center;
    gap: 7px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.cancel-button {
    border: 1px solid #ddd;
    background: white;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
}

@media (max-width: 900px) {

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .categories-page {
        padding: 18px;
    }

    .page-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .filters-card {
        flex-direction: column;
    }

    .filters-card select,
    .search-button {
        min-height: 42px;
    }

}

</style>