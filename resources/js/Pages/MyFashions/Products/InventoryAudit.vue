<script setup>

import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';


const props = defineProps({

    audits: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            adjustment_type: '',
            product_id: '',
        }),
    },

});


const search = ref(
    props.filters.search ?? ''
);


const adjustmentType = ref(
    props.filters.adjustment_type ?? ''
);


const applyFilters = () => {

    router.get(
        '/fashion/products/inventory/audit',
        {
            search: search.value,
            adjustment_type: adjustmentType.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );

};


const clearFilters = () => {

    search.value = '';

    adjustmentType.value = '';

    applyFilters();

};


const formatDate = (date) => {

    if (!date) {
        return '-';
    }

    return new Date(date).toLocaleString();

};


const adjustmentLabel = (type) => {

    const labels = {

        restock: 'Restock',

        sale: 'Sale',

        return: 'Return',

        damage: 'Damage',

        loss: 'Loss',

        correction: 'Correction',

        initial_stock: 'Initial Stock',

        other: 'Other',

    };

    return labels[type] ?? type;

};


const adjustmentClass = (quantity) => {

    if (quantity > 0) {
        return 'positive';
    }

    if (quantity < 0) {
        return 'negative';
    }

    return 'neutral';

};

</script>


<template>

    <MyFashionLayout>

        <div class="audit-page">

            <!-- HEADER -->

            <div class="page-header">

                <div>

                    <h1>
                        Inventory Audit Trail
                    </h1>

                    <p>
                        Track every stock movement and adjustment.
                    </p>

                </div>


                <a
                    href="/fashion/products/inventory"
                    class="back-button"
                >
                    Back to Inventory
                </a>

            </div>


            <!-- FILTERS -->

            <div class="filter-card">

                <div class="search-box">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search product, SKU, reason..."
                        @keyup.enter="applyFilters"
                    >

                </div>


                <select
                    v-model="adjustmentType"
                    @change="applyFilters"
                >

                    <option value="">
                        All Adjustment Types
                    </option>

                    <option value="restock">
                        Restock
                    </option>

                    <option value="sale">
                        Sale
                    </option>

                    <option value="return">
                        Return
                    </option>

                    <option value="damage">
                        Damage
                    </option>

                    <option value="loss">
                        Loss
                    </option>

                    <option value="correction">
                        Correction
                    </option>

                    <option value="initial_stock">
                        Initial Stock
                    </option>

                    <option value="other">
                        Other
                    </option>

                </select>


                <button
                    class="filter-button"
                    @click="applyFilters"
                >
                    Search
                </button>


                <button
                    class="clear-button"
                    @click="clearFilters"
                >
                    Clear
                </button>

            </div>


            <!-- TABLE -->

            <div class="content-card">

                <div class="table-header">

                    <div>

                        <h2>
                            Stock Movements
                        </h2>

                        <p>
                            {{ audits.total }} audit records
                        </p>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    SKU
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Previous
                                </th>

                                <th>
                                    Adjustment
                                </th>

                                <th>
                                    New Stock
                                </th>

                                <th>
                                    User
                                </th>

                                <th>
                                    Reason
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="audit in audits.data"
                                :key="audit.id"
                            >

                                <td>
                                    {{ formatDate(audit.created_at) }}
                                </td>


                                <td>

                                    <strong>
                                        {{ audit.product?.name ?? 'Deleted Product' }}
                                    </strong>

                                </td>


                                <td>
                                    {{ audit.product?.sku ?? '-' }}
                                </td>


                                <td>

                                    <span class="type-badge">

                                        {{ adjustmentLabel(
                                            audit.adjustment_type
                                        ) }}

                                    </span>

                                </td>


                                <td>
                                    {{ audit.previous_quantity }}
                                </td>


                                <td>

                                    <span
                                        class="adjustment"
                                        :class="
                                            adjustmentClass(
                                                audit.adjustment_quantity
                                            )
                                        "
                                    >

                                        {{
                                            audit.adjustment_quantity > 0
                                                ? '+'
                                                : ''
                                        }}

                                        {{ audit.adjustment_quantity }}

                                    </span>

                                </td>


                                <td>

                                    <strong>
                                        {{ audit.new_quantity }}
                                    </strong>

                                </td>


                                <td>
                                    {{ audit.user?.name ?? 'System' }}
                                </td>


                                <td>
                                    {{ audit.reason ?? '-' }}
                                </td>

                            </tr>


                            <tr
                                v-if="!audits.data.length"
                            >

                                <td
                                    colspan="9"
                                    class="empty-state"
                                >

                                    No inventory audit records found.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- PAGINATION -->

                <div
                    v-if="audits.last_page > 1"
                    class="pagination"
                >

                    <a
                        v-for="link in audits.links"
                        :key="link.label"
                        href="#"
                        :class="{
                            active: link.active,
                            disabled: !link.url
                        }"
                        @click.prevent="
                            link.url &&
                            router.get(link.url)
                        "
                        v-html="link.label"
                    ></a>

                </div>

            </div>

        </div>

    </MyFashionLayout>

</template>


<style scoped>

.audit-page {
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
    margin-top: 5px;
    color: #777;
}

.back-button {
    padding: 10px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    background: white;
}

.filter-card {
    display: flex;
    gap: 12px;
    align-items: center;
    background: white;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.search-box {
    flex: 1;
}

.search-box input,
.filter-card select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: white;
}

.filter-card select {
    width: 200px;
}

.filter-button,
.clear-button {
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.filter-button {
    background: #111827;
    color: white;
}

.clear-button {
    background: #f3f4f6;
    color: #333;
}

.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    overflow: hidden;
}

.table-header {
    padding: 20px 24px;
    border-bottom: 1px solid #eee;
}

.table-header h2 {
    margin: 0;
    font-size: 18px;
}

.table-header p {
    margin-top: 5px;
    color: #777;
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
    padding: 14px 16px;
    border-bottom: 1px solid #eee;
    text-align: left;
    white-space: nowrap;
}

th {
    background: #f9fafb;
    font-size: 13px;
    color: #555;
}

td {
    font-size: 14px;
}

.type-badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 20px;
    background: #f3f4f6;
    font-size: 12px;
}

.adjustment {
    font-weight: 700;
}

.adjustment.positive {
    color: #16a34a;
}

.adjustment.negative {
    color: #dc2626;
}

.adjustment.neutral {
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 50px;
    color: #777;
}

.pagination {
    display: flex;
    gap: 6px;
    padding: 20px;
    justify-content: flex-end;
}

.pagination a {
    padding: 7px 11px;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
}

.pagination a.active {
    background: #111827;
    color: white;
}

.pagination a.disabled {
    opacity: .4;
    pointer-events: none;
}

@media (max-width: 768px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .filter-card {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-card select {
        width: 100%;
    }

}

</style>