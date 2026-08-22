<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Swal from 'sweetalert2'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    collections: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            current_page: 1,
            last_page: 1,
            total: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            active: '',
            featured: '',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

const search = ref(
    props.filters?.search || ''
)

const activeFilter = ref(
    props.filters?.active ?? ''
)

const featuredFilter = ref(
    props.filters?.featured ?? ''
)


/*
|--------------------------------------------------------------------------
| COLLECTION DATA
|--------------------------------------------------------------------------
*/

const collectionData = computed(() => {

    return props.collections?.data || []

})


/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {

    if (!image) {
        return '/assets/placeholder.jpg'
    }

    if (
        image.startsWith('http://') ||
        image.startsWith('https://') ||
        image.startsWith('/')
    ) {
        return image
    }

    return `/storage/${image}`

}


/*
|--------------------------------------------------------------------------
| APPLY FILTERS
|--------------------------------------------------------------------------
*/

const applyFilters = () => {

    router.get(
        '/fashion/collections',
        {
            search: search.value || undefined,

            active:
                activeFilter.value !== ''
                    ? activeFilter.value
                    : undefined,

            featured:
                featuredFilter.value !== ''
                    ? featuredFilter.value
                    : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| CLEAR FILTERS
|--------------------------------------------------------------------------
*/

const clearFilters = () => {

    search.value = ''

    activeFilter.value = ''

    featuredFilter.value = ''

    router.get(
        '/fashion/collections',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| DELETE COLLECTION
|--------------------------------------------------------------------------
*/

const deleteCollection = (collection) => {

    Swal.fire({

        title: 'Delete Collection?',

        text:
            `Are you sure you want to delete "${collection.name}"?`,

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc2626',

        cancelButtonColor: '#6b7280',

        confirmButtonText: 'Yes, Delete',

        cancelButtonText: 'Cancel',

    }).then((result) => {

        if (!result.isConfirmed) {
            return
        }

        router.delete(
            `/fashion/collections/${collection.id}`,
            {

                preserveScroll: true,

                onSuccess: () => {

                    Swal.fire({

                        icon: 'success',

                        title: 'Deleted',

                        text:
                            'Collection deleted successfully.',

                        timer: 1800,

                        showConfirmButton: false,

                    })

                },

            }
        )

    })

}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const goToPage = (url) => {

    if (!url) {
        return
    }

    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {

    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleDateString(
        'en-GB',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    )

}

</script>


<template>

    <Head title="Collections" />


    <MyFashionLayout>

        <div class="collections-page">


            <!-- =====================================================
                 HEADER
            ====================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Collections
                    </h1>

                    <p>
                        Organize your products into curated fashion collections.
                    </p>

                </div>


                <Link href="/fashion/collections/create" class="btn-primary">

                    <span class="btn-icon">
                        +
                    </span>

                    Add Collection

                </Link>

            </div>


            <!-- =====================================================
                 FILTERS
            ====================================================== -->

            <div class="filter-card">

                <div class="filter-grid">


                    <!-- SEARCH -->

                    <div class="filter-group search-group">

                        <label>
                            Search
                        </label>

                        <div class="search-wrapper">

                            <span class="search-icon">
                                🔍
                            </span>

                            <input v-model="search" type="text" placeholder="Search collections..."
                                @keyup.enter="applyFilters" />

                        </div>

                    </div>


                    <!-- ACTIVE -->

                    <div class="filter-group">

                        <label>
                            Status
                        </label>

                        <select v-model="activeFilter">

                            <option value="">
                                All Status
                            </option>

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                    </div>


                    <!-- FEATURED -->

                    <div class="filter-group">

                        <label>
                            Featured
                        </label>

                        <select v-model="featuredFilter">

                            <option value="">
                                All Collections
                            </option>

                            <option value="1">
                                Featured
                            </option>

                            <option value="0">
                                Not Featured
                            </option>

                        </select>

                    </div>


                    <!-- ACTIONS -->

                    <div class="filter-actions">

                        <button type="button" class="filter-button" @click="applyFilters">
                            Search
                        </button>

                        <button type="button" class="clear-button" @click="clearFilters">
                            Clear
                        </button>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 SUMMARY
            ====================================================== -->

            <div class="summary-row">

                <div>

                    <strong>
                        {{ collections.total || 0 }}
                    </strong>

                    <span>
                        Total Collections
                    </span>

                </div>


                <div>

                    <strong>
                        {{collectionData.filter(c => c.is_active).length}}
                    </strong>

                    <span>
                        Active
                    </span>

                </div>


                <div>

                    <strong>
                        {{collectionData.filter(c => c.is_featured).length}}
                    </strong>

                    <span>
                        Featured
                    </span>

                </div>

            </div>


            <!-- =====================================================
                 TABLE CARD
            ====================================================== -->

            <div class="content-card">


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div v-if="collectionData.length" class="table-wrapper">

                    <table class="collections-table">

                        <thead>

                            <tr>

                                <th>
                                    Collection
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Products
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
                                    Created
                                </th>

                                <th class="actions-column">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr v-for="collection in collectionData" :key="collection.id">


                                <!-- COLLECTION -->

                                <td>

                                    <div class="collection-cell">

                                        <div class="collection-image">

                                            <img :src="imageUrl(collection.image)" :alt="collection.name"
                                                @error="$event.target.src = '/assets/placeholder.jpg'" />

                                        </div>


                                        <div class="collection-info">

                                            <Link :href="`/fashion/collections/${collection.id}`"
                                                class="collection-name">

                                                {{ collection.name }}

                                            </Link>


                                            <span class="collection-slug">

                                                /{{ collection.slug }}

                                            </span>

                                        </div>

                                    </div>

                                </td>


                                <!-- DESCRIPTION -->

                                <td>

                                    <div class="description" :title="collection.description">

                                        {{
                                            collection.description
                                                ? (
                                                    collection.description.length > 80
                                                        ? collection.description.substring(0, 80) + '...'
                                                        : collection.description
                                                )
                                                : 'No description'
                                        }}

                                    </div>

                                </td>


                                <!-- PRODUCTS -->

                                <td>

                                    <span class="product-count">

                                        {{ collection.products_count || 0 }}

                                        {{
                                            collection.products_count === 1
                                                ? 'Product'
                                                : 'Products'
                                        }}

                                    </span>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span :class="[
                                        'status-badge',
                                        collection.is_active
                                            ? 'status-active'
                                            : 'status-inactive'
                                    ]">

                                        <span class="status-dot"></span>

                                        {{
                                            collection.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}

                                    </span>

                                </td>


                                <!-- FEATURED -->

                                <td>

                                    <span :class="[
                                        'featured-badge',
                                        collection.is_featured
                                            ? 'featured-yes'
                                            : 'featured-no'
                                    ]">

                                        {{
                                            collection.is_featured
                                                ? 'Featured'
                                                : 'No'
                                        }}

                                    </span>

                                </td>


                                <!-- SORT ORDER -->

                                <td>

                                    <span class="sort-order">

                                        {{ collection.sort_order ?? 0 }}

                                    </span>

                                </td>


                                <!-- CREATED -->

                                <td>

                                    <span class="date">

                                        {{ formatDate(collection.created_at) }}

                                    </span>

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="actions">


                                        <!-- VIEW -->

                                        <Link :href="`/fashion/collections/${collection.id}`" class="action-button view"
                                            title="View Collection">

                                            👁

                                        </Link>


                                        <!-- EDIT -->

                                        <Link :href="`/fashion/collections/${collection.id}/edit`"
                                            class="action-button edit" title="Edit Collection">

                                            ✏️

                                        </Link>


                                        <!-- DELETE -->

                                        <button type="button" class="action-button delete" title="Delete Collection"
                                            @click="deleteCollection(collection)">

                                            🗑

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     EMPTY STATE
                ================================================== -->

                <div v-else class="empty-state">

                    <div class="empty-icon">
                        🏷️
                    </div>

                    <h2>
                        No Collections Found
                    </h2>

                    <p>
                        {{
                            search ||
                                activeFilter !== '' ||
                                featuredFilter !== ''
                                ? 'Try changing your search or filters.'
                                : 'Create your first fashion collection to get started.'
                        }}
                    </p>


                    <button v-if="
                        search ||
                        activeFilter !== '' ||
                        featuredFilter !== ''
                    " type="button" class="clear-empty-button" @click="clearFilters">

                        Clear Filters

                    </button>


                    <Link v-else href="/fashion/collections/create" class="btn-primary empty-button">

                        + Add Collection

                    </Link>

                </div>


                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div v-if="
                    collectionData.length &&
                    collections.last_page > 1
                " class="pagination-wrapper">

                    <div class="pagination-info">

                        Showing

                        <strong>
                            {{ collections.from }}
                        </strong>

                        to

                        <strong>
                            {{ collections.to }}
                        </strong>

                        of

                        <strong>
                            {{ collections.total }}
                        </strong>

                        collections

                    </div>


                    <div class="pagination">

                        <button v-for="link in collections.links" :key="link.label" type="button" :disabled="!link.url"
                            :class="[
                                'page-button',
                                {
                                    active: link.active,
                                }
                            ]" @click="goToPage(link.url)">

                            <span v-html="link.label"></span>

                        </button>

                    </div>

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

.collections-page {
    padding: 10px;
    width: 100%;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

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
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 14px;
}


/*
|--------------------------------------------------------------------------
| PRIMARY BUTTON
|--------------------------------------------------------------------------
*/

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 17px;
    background: #111827;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s ease;
}

.btn-primary:hover {
    background: #075c59;
}

.btn-icon {
    font-size: 18px;
    line-height: 1;
}


/*
|--------------------------------------------------------------------------
| FILTER CARD
|--------------------------------------------------------------------------
*/

.filter-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 18px;
    border: 1px solid #edf0f2;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
}

.filter-grid {
    display: grid;
    grid-template-columns: minmax(250px, 1fr) 180px 180px auto;
    align-items: end;
    gap: 15px;
}

.filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 12px;
    font-weight: 600;
}

.filter-group input,
.filter-group select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #dfe3e8;
    border-radius: 7px;
    background: white;
    color: #374151;
    font-size: 13px;
    outline: none;
}

.filter-group input:focus,
.filter-group select:focus {
    border-color: #075c59;
    box-shadow: 0 0 0 3px rgba(7, 92, 89, .08);
}

.search-wrapper {
    position: relative;
}

.search-wrapper input {
    padding-left: 36px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    opacity: .6;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.filter-button,
.clear-button {
    height: 40px;
    padding: 0 17px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.filter-button {
    border: none;
    background: #111827;
    color: white;
}

.filter-button:hover {
    background: #075c59;
}

.clear-button {
    border: 1px solid #dfe3e8;
    background: white;
    color: #555;
}

.clear-button:hover {
    background: #f8f8f8;
}


/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

.summary-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 18px;
}

.summary-row>div {
    background: white;
    border: 1px solid #edf0f2;
    border-radius: 10px;
    padding: 15px 18px;
}

.summary-row strong {
    display: block;
    color: #111827;
    font-size: 22px;
    font-weight: 700;
}

.summary-row span {
    display: block;
    margin-top: 3px;
    color: #777;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| CONTENT CARD
|--------------------------------------------------------------------------
*/

.content-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #edf0f2;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
    overflow: hidden;
}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.collections-table {
    width: 100%;
    min-width: 1100px;
    border-collapse: collapse;
}

.collections-table th {
    padding: 14px 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    color: #6b7280;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .4px;
    text-align: left;
    white-space: nowrap;
}

.collections-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f1f2;
    color: #374151;
    font-size: 12px;
    vertical-align: middle;
}

.collections-table tbody tr:hover {
    background: #fcfdfd;
}


/*
|--------------------------------------------------------------------------
| COLLECTION CELL
|--------------------------------------------------------------------------
*/

.collection-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 230px;
}

.collection-image {
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    overflow: hidden;
    border-radius: 8px;
    background: #f3f4f6;
}

.collection-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

.collection-info {
    min-width: 0;
}

.collection-name {
    display: block;
    color: #111827;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}

.collection-name:hover {
    color: #075c59;
}

.collection-slug {
    display: block;
    margin-top: 4px;
    color: #9ca3af;
    font-size: 10px;
}


/*
|--------------------------------------------------------------------------
| DESCRIPTION
|--------------------------------------------------------------------------
*/

.description {
    width: 220px;
    color: #6b7280;
    line-height: 1.5;
}


/*
|--------------------------------------------------------------------------
| PRODUCT COUNT
|--------------------------------------------------------------------------
*/

.product-count {
    display: inline-flex;
    padding: 5px 9px;
    background: #f3f4f6;
    border-radius: 6px;
    color: #374151;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.status-active {
    background: #ecfdf3;
    color: #15803d;
}

.status-inactive {
    background: #f3f4f6;
    color: #6b7280;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}


/*
|--------------------------------------------------------------------------
| FEATURED
|--------------------------------------------------------------------------
*/

.featured-badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.featured-yes {
    background: #fff7ed;
    color: #c2410c;
}

.featured-no {
    background: #f3f4f6;
    color: #9ca3af;
}


/*
|--------------------------------------------------------------------------
| SORT ORDER
|--------------------------------------------------------------------------
*/

.sort-order {
    display: inline-flex;
    min-width: 25px;
    justify-content: center;
    color: #555;
    font-weight: 600;
}


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

.date {
    color: #6b7280;
    white-space: nowrap;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| ACTIONS
|--------------------------------------------------------------------------
*/

.actions-column {
    text-align: center !important;
}

.actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.action-button {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    background: white;
    text-decoration: none;
    cursor: pointer;
    font-size: 12px;
    transition: .2s ease;
}

.action-button:hover {
    transform: translateY(-1px);
}

.action-button.view:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
}

.action-button.edit:hover {
    background: #fffbeb;
    border-color: #fde68a;
}

.action-button.delete:hover {
    background: #fef2f2;
    border-color: #fecaca;
}


/*
|--------------------------------------------------------------------------
| EMPTY STATE
|--------------------------------------------------------------------------
*/

.empty-state {
    padding: 80px 25px;
    text-align: center;
}

.empty-icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #f3f4f6;
    font-size: 28px;
}

.empty-state h2 {
    margin: 0 0 8px;
    color: #111827;
    font-size: 19px;
}

.empty-state p {
    max-width: 450px;
    margin: 0 auto 20px;
    color: #777;
    font-size: 12px;
    line-height: 1.6;
}

.empty-button {
    display: inline-flex;
}

.clear-empty-button {
    padding: 9px 16px;
    border: 1px solid #ddd;
    border-radius: 7px;
    background: white;
    color: #555;
    cursor: pointer;
}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 16px 18px;
    border-top: 1px solid #edf0f2;
}

.pagination-info {
    color: #777;
    font-size: 11px;
}

.pagination-info strong {
    color: #374151;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 4px;
}

.page-button {
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
    color: #555;
    font-size: 11px;
    cursor: pointer;
}

.page-button:hover:not(:disabled) {
    background: #f9fafb;
}

.page-button.active {
    border-color: #111827;
    background: #111827;
    color: white;
}

.page-button:disabled {
    cursor: not-allowed;
    opacity: .45;
}


/*
|--------------------------------------------------------------------------
| TABLET
|--------------------------------------------------------------------------
*/

@media (max-width: 1000px) {

    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .filter-actions {
        align-self: end;
    }

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 650px) {

    .collections-page {
        padding: 5px;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .btn-primary {
        width: 100%;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        width: 100%;
    }

    .filter-button,
    .clear-button {
        flex: 1;
    }

    .summary-row {
        grid-template-columns: 1fr;
    }

    .pagination-wrapper {
        align-items: flex-start;
        flex-direction: column;
    }

    .pagination {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 3px;
    }

}
</style>

<!-- <script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    collections: {
        type: Array,
        default: () => [],
    },
});

</script>

<template>
  <Head title="All Collections" />

      <MyFashionLayout>

          <div class="p-6">

        <h1 class="text-2xl font-bold">
            All Collections
        </h1>

        <p class="mt-2 text-gray-500">
            Manage fashion collections.
        </p>

    </div>
      </MyFashionLayout>

  

</template> -->