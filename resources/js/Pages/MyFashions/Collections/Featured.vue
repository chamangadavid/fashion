<!-- resources/js/Pages/MyFashions/Collections/Featured.vue -->

<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import {
    Head,
    Link,
    router,
} from '@inertiajs/vue3';

import {
    ref,
    computed,
} from 'vue';


// =====================================================
// PROPS
// =====================================================

const props = defineProps({

    collections: {
        type: Array,
        default: () => [],
    },

});


// =====================================================
// SEARCH
// =====================================================

const search = ref('');


// =====================================================
// FILTERED COLLECTIONS
// =====================================================

const filteredCollections = computed(() => {

    const term = search.value
        .trim()
        .toLowerCase();

    if (!term) {
        return props.collections;
    }

    return props.collections.filter(collection => {

        return (
            collection.name?.toLowerCase().includes(term) ||
            collection.slug?.toLowerCase().includes(term) ||
            collection.description?.toLowerCase().includes(term)
        );

    });

});


// =====================================================
// TOGGLE FEATURED
// =====================================================

const toggleFeatured = (collection) => {

    router.patch(
        `/fashion/collections/${collection.id}/toggle-featured`,
        {},
        {
            preserveScroll: true,
        }
    );

};

</script>


<template>

<Head title="Featured Collections" />


<MyFashionLayout>

    <div class="page">


        <!-- =================================================
             HEADER
        ================================================== -->

        <div class="page-header">

            <div>

                <div class="eyebrow">
                    COLLECTIONS
                </div>

                <h1>
                    Featured Collections
                </h1>

                <p>
                    Manage the collections highlighted
                    throughout your fashion store.
                </p>

            </div>


            <Link
                href="/fashion/collections"
                class="back-button"
            >
                ← All Collections
            </Link>

        </div>


        <!-- =================================================
             SUMMARY
        ================================================== -->

        <div class="summary-grid">

            <div class="summary-card">

                <span>
                    Featured Collections
                </span>

                <strong>
                    {{ collections.length }}
                </strong>

            </div>


            <div class="summary-card">

                <span>
                    Total Products
                </span>

                <strong>

                    {{
                        collections.reduce(
                            (total, collection) =>
                                total +
                                (collection.products_count || 0),
                            0
                        )
                    }}

                </strong>

            </div>


            <div class="summary-card">

                <span>
                    Active Collections
                </span>

                <strong>

                    {{
                        collections.filter(
                            collection => collection.is_active
                        ).length
                    }}

                </strong>

            </div>

        </div>


        <!-- =================================================
             SEARCH
        ================================================== -->

        <div class="toolbar">

            <div class="search-box">

                <span class="search-icon">
                    🔍
                </span>

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search featured collections..."
                />

            </div>

        </div>


        <!-- =================================================
             EMPTY STATE
        ================================================== -->

        <div
            v-if="filteredCollections.length === 0"
            class="empty-state"
        >

            <div class="empty-icon">
                ☆
            </div>

            <h2>
                No Featured Collections
            </h2>

            <p>
                {{
                    search
                        ? 'No collections match your search.'
                        : 'You have not featured any collections yet.'
                }}
            </p>

            <Link
                href="/fashion/collections"
                class="primary-button"
            >
                Browse Collections
            </Link>

        </div>


        <!-- =================================================
             COLLECTION GRID
        ================================================== -->

        <div
            v-else
            class="collections-grid"
        >

            <div
                v-for="collection in filteredCollections"
                :key="collection.id"
                class="collection-card"
            >

                <!-- IMAGE -->

                <div class="collection-image">

                    <img
                        v-if="collection.image"
                        :src="`/storage/${collection.image}`"
                        :alt="collection.name"
                    />

                    <div
                        v-else
                        class="image-placeholder"
                    >
                        No Image
                    </div>


                    <!-- FEATURED BADGE -->

                    <div class="featured-badge">
                        ★ Featured
                    </div>

                </div>


                <!-- CONTENT -->

                <div class="collection-content">

                    <div class="collection-top">

                        <div>

                            <h2>
                                {{ collection.name }}
                            </h2>

                            <span class="slug">
                                /{{ collection.slug }}
                            </span>

                        </div>


                        <span
                            :class="[
                                'status-badge',
                                collection.is_active
                                    ? 'active'
                                    : 'inactive'
                            ]"
                        >

                            {{
                                collection.is_active
                                    ? 'Active'
                                    : 'Inactive'
                            }}

                        </span>

                    </div>


                    <p
                        v-if="collection.description"
                        class="description"
                    >
                        {{ collection.description }}
                    </p>


                    <!-- META -->

                    <div class="collection-meta">

                        <div>

                            <strong>
                                {{ collection.products_count || 0 }}
                            </strong>

                            <span>
                                Products
                            </span>

                        </div>

                    </div>


                    <!-- ACTIONS -->

                    <div class="actions">

                        <Link
                            :href="`/fashion/collections/${collection.id}`"
                            class="action view"
                        >
                            View
                        </Link>


                        <Link
                            :href="`/fashion/collections/${collection.id}/edit`"
                            class="action edit"
                        >
                            Edit
                        </Link>


                        <button
                            type="button"
                            class="action remove"
                            @click="toggleFeatured(collection)"
                        >
                            Remove Featured
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</MyFashionLayout>

</template>


<style scoped>

/* =====================================================
   PAGE
===================================================== */

.page {
    padding: 10px;
}


/* =====================================================
   HEADER
===================================================== */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 28px;
}

.eyebrow {
    margin-bottom: 7px;
    color: #a0833d;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
}

.page-header h1 {
    margin: 0;
    font-size: 30px;
    font-weight: 700;
    color: #111827;
}

.page-header p {
    margin: 7px 0 0;
    color: #777;
    font-size: 14px;
}

.back-button {
    padding: 10px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    color: #333;
    text-decoration: none;
    background: white;
}


/* =====================================================
   SUMMARY
===================================================== */

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}

.summary-card {
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.summary-card span {
    display: block;
    color: #777;
    font-size: 12px;
}

.summary-card strong {
    display: block;
    margin-top: 7px;
    color: #111827;
    font-size: 28px;
}


/* =====================================================
   TOOLBAR
===================================================== */

.toolbar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 22px;
}

.search-box {
    width: 360px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 13px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
}

.search-icon {
    font-size: 13px;
}

.search-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 13px;
}


/* =====================================================
   GRID
===================================================== */

.collections-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}


/* =====================================================
   CARD
===================================================== */

.collection-card {
    overflow: hidden;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    border: 1px solid #eee;
}


/* =====================================================
   IMAGE
===================================================== */

.collection-image {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #f4f4f4;
}

.collection-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform .5s ease;
}

.collection-card:hover .collection-image img {
    transform: scale(1.04);
}

.image-placeholder {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}


/* =====================================================
   FEATURED BADGE
===================================================== */

.featured-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 6px 10px;
    border-radius: 20px;
    background: #a0833d;
    color: white;
    font-size: 10px;
    font-weight: 600;
}


/* =====================================================
   CONTENT
===================================================== */

.collection-content {
    padding: 18px;
}

.collection-top {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}

.collection-top h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.slug {
    display: block;
    margin-top: 4px;
    color: #999;
    font-size: 11px;
}

.description {
    margin: 12px 0;
    color: #777;
    font-size: 12px;
    line-height: 1.6;
}


/* =====================================================
   STATUS
===================================================== */

.status-badge {
    height: fit-content;
    padding: 5px 8px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 600;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #f3f4f6;
    color: #6b7280;
}


/* =====================================================
   META
===================================================== */

.collection-meta {
    padding: 12px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.collection-meta strong {
    margin-right: 6px;
    font-size: 16px;
}

.collection-meta span {
    color: #777;
    font-size: 11px;
}


/* =====================================================
   ACTIONS
===================================================== */

.actions {
    display: flex;
    gap: 7px;
    margin-top: 14px;
}

.action {
    flex: 1;
    padding: 8px 6px;
    border-radius: 7px;
    text-align: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 11px;
}

.view {
    background: #f3f4f6;
    color: #333;
}

.edit {
    background: #111827;
    color: white;
}

.remove {
    background: #fef2f2;
    color: #dc2626;
}


/* =====================================================
   EMPTY
===================================================== */

.empty-state {
    padding: 80px 20px;
    text-align: center;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.empty-icon {
    margin-bottom: 15px;
    font-size: 40px;
    color: #a0833d;
}

.empty-state h2 {
    margin: 0;
}

.empty-state p {
    color: #777;
    font-size: 13px;
}

.primary-button {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    border-radius: 8px;
    background: #111827;
    color: white;
    text-decoration: none;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 1000px) {

    .collections-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 700px) {

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

    .search-box {
        width: 100%;
    }

    .collections-grid {
        grid-template-columns: 1fr;
    }

}

</style>