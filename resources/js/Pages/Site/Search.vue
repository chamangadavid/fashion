<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";

const props = defineProps({
    search: {
        type: String,
        default: "",
    },

    products: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            meta: {},
        }),
    },
});

const searchTerm = ref(props.search);

let searchTimeout = null;

const performSearch = () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            "/search",
            {
                q: searchTerm.value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 400);
};

watch(searchTerm, () => {
    performSearch();
});

const productImage = (product) => {
    if (!product.image) {
        return "/images/placeholder.jpg";
    }

    if (product.image.startsWith("http")) {
        return product.image;
    }

    return `/storage/${product.image}`;
};

const productPrice = (product) => {
    if (product.sale_price && Number(product.sale_price) > 0) {
        return product.sale_price;
    }

    return product.price;
};
</script>

<template>
    <Head title="Search" />
        <!-- =================================================
             HEADER
        ================================================== -->

        <Header />

                <!-- =================================================
             HERO
        ================================================== -->

        <section class="collections-hero">

            <div class="hero-background"></div>

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-eyebrow">
                    SEARCH IN OUR COLLECTIONS
                </span>

                <h1>
                    Search Items
                </h1>
            </div>

        </section>



    <div class="search-page">

        <!-- ============================= -->
        <!-- SEARCH HEADER -->
        <!-- ============================= -->

        <section class="search-header">

            <h1>TYPE HERE...</h1>

            <div class="search-box">

                <SearchOutlined class="search-box-icon" />

                <input
                    v-model="searchTerm"
                    type="text"
                    placeholder="Search products, categories or collections..."
                    autocomplete="off"
                />

            </div>

        </section>


        <!-- ============================= -->
        <!-- SEARCH RESULTS -->
        <!-- ============================= -->

        <section class="search-results">

            <!-- No search entered -->

            <div
                v-if="!search"
                class="search-message"
            >
                <h2>What are you looking for?</h2>

                <p>
                    Search for products, categories, collections or SKU.
                </p>
            </div>


            <!-- Search entered but no results -->

            <div
                v-else-if="products.data.length === 0"
                class="search-message"
            >
                <h2>No results found</h2>

                <p>
                    We couldn't find anything matching
                    "<strong>{{ search }}</strong>".
                </p>
            </div>


            <!-- Results -->

            <template v-else>

                <div class="results-heading">

                    <h2>
                        Search results for
                        "<span>{{ search }}</span>"
                    </h2>

                    <p>
                        {{ products.total }} product{{ products.total === 1 ? "" : "s" }}
                        found
                    </p>

                </div>


                <!-- PRODUCT GRID -->

                <div class="product-grid">

                    <Link
                        v-for="product in products.data"
                        :key="product.id"
                        :href="`/products/${product.slug}`"
                        class="product-card"
                    >

                        <div class="product-image-wrapper">

                            <img
                                :src="productImage(product)"
                                :alt="product.name"
                                class="product-image"
                            />

                            <span
                                v-if="
                                    product.sale_price &&
                                    Number(product.sale_price) > 0 &&
                                    Number(product.sale_price) < Number(product.price)
                                "
                                class="sale-badge"
                            >
                                SALE
                            </span>

                            <span
                                v-if="product.is_new_arrival"
                                class="new-badge"
                            >
                                NEW
                            </span>

                        </div>


                        <div class="product-info">

                            <p
                                v-if="product.category"
                                class="product-category"
                            >
                                {{ product.category.name }}
                            </p>

                            <h3>
                                {{ product.name }}
                            </h3>


                            <div class="product-price">

                                <span
                                    v-if="
                                        product.sale_price &&
                                        Number(product.sale_price) > 0 &&
                                        Number(product.sale_price) < Number(product.price)
                                    "
                                    class="old-price"
                                >
                                    K{{ Number(product.price).toFixed(2) }}
                                </span>

                                <span class="current-price">
                                    K{{ Number(productPrice(product)).toFixed(2) }}
                                </span>

                            </div>

                        </div>

                    </Link>

                </div>


                <!-- ============================= -->
                <!-- PAGINATION -->
                <!-- ============================= -->

                <div
                    v-if="products.links && products.links.length > 3"
                    class="pagination"
                >

                    <template
                        v-for="(link, index) in products.links"
                        :key="index"
                    >

                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="pagination-link"
                            :class="{
                                active: link.active
                            }"
                            v-html="link.label"
                        />

                        <span
                            v-else
                            class="pagination-link disabled"
                            v-html="link.label"
                        />

                    </template>

                </div>

            </template>

        </section>
        <br/><br/>

        
        <!-- =================================================
             FOOTER
        ================================================== -->

        <AppFooter />

    </div>
</template>

<style scoped>
.search-page {
    width: 100%;
    min-height: 70vh;
    padding: 50px 6%;
    background: #fff;
}


/* =====================================================
   HERO
===================================================== */

.collections-hero {

    position: relative;

    height: 440px;

    overflow: hidden;

    display: flex;

    align-items: center;

    justify-content: center;

}


.hero-background {

    position: absolute;

    inset: 0;

    background-image: url("/assets/search.png");

    background-size: cover;

    background-position: center;

    transform: scale(1.02);

}


.hero-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to bottom,
            rgba(0,0,0,.10),
            rgba(0,0,0,.42)
        );

}


.hero-content {

    position: relative;

    z-index: 2;

    max-width: 700px;

    padding: 30px;

    text-align: center;

    color: white;

}


.hero-eyebrow {

    display: block;

    margin-bottom: 15px;

    font-size: 9px;

    font-weight: 500;

    letter-spacing: 4px;

}


.hero-content h1 {

    margin: 0;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: clamp(
        48px,
        7vw,
        85px
    );

    font-weight: 400;

    letter-spacing: -1px;

}


.hero-content p {

    max-width: 500px;

    margin: 18px auto 0;

    font-size: 12px;

    line-height: 1.7;

}



/* ============================= */
/* SEARCH HEADER */
/* ============================= */

.search-header {
    max-width: 900px;
    margin: 0 auto 50px;
    text-align: center;
}

.search-header h1 {
    font-size: 32px;
    font-weight: 600;
    letter-spacing: 3px;
    margin-bottom: 30px;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #222;
    padding: 10px 5px;
}

.search-box-icon {
    font-size: 20px;
    margin-right: 12px;
}

.search-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 17px;
    background: transparent;
    padding: 8px 0;
}

.search-box input::placeholder {
    color: #999;
}


/* ============================= */
/* RESULTS */
/* ============================= */

.search-results {
    max-width: 1400px;
    margin: auto;
}

.results-heading {
    margin-bottom: 30px;
}

.results-heading h2 {
    font-size: 22px;
    font-weight: 500;
}

.results-heading h2 span {
    font-weight: 700;
}

.results-heading p {
    color: #777;
    margin-top: 5px;
}


/* ============================= */
/* PRODUCT GRID */
/* ============================= */

.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px 20px;
}

.product-card {
    text-decoration: none;
    color: #111;
    transition: transform 0.25s ease;
}

.product-card:hover {
    transform: translateY(-4px);
}

.product-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    overflow: hidden;
    background: #f5f5f5;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.product-card:hover .product-image {
    transform: scale(1.04);
}


/* ============================= */
/* BADGES */
/* ============================= */

.sale-badge,
.new-badge {
    position: absolute;
    top: 12px;
    padding: 5px 9px;
    font-size: 10px;
    letter-spacing: 1px;
    background: #111;
    color: #fff;
}

.sale-badge {
    left: 12px;
}

.new-badge {
    right: 12px;
}


/* ============================= */
/* PRODUCT INFO */
/* ============================= */

.product-info {
    padding-top: 14px;
}

.product-category {
    margin: 0 0 5px;
    font-size: 11px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.product-info h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 500;
}

.product-price {
    display: flex;
    gap: 10px;
    margin-top: 8px;
}

.current-price {
    font-weight: 600;
}

.old-price {
    color: #999;
    text-decoration: line-through;
}


/* ============================= */
/* EMPTY SEARCH */
/* ============================= */

.search-message {
    text-align: center;
    padding: 80px 20px;
}

.search-message h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.search-message p {
    color: #777;
}


/* ============================= */
/* PAGINATION */
/* ============================= */

.pagination {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 50px;
    flex-wrap: wrap;
}

.pagination-link {
    min-width: 38px;
    height: 38px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    color: #222;
    text-decoration: none;
    font-size: 13px;
}

.pagination-link.active {
    background: #111;
    color: #fff;
    border-color: #111;
}

.pagination-link.disabled {
    color: #aaa;
    background: #f8f8f8;
}


/* ============================= */
/* RESPONSIVE */
/* ============================= */

@media (max-width: 1000px) {
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 700px) {
    .search-page {
        padding: 35px 20px;
    }

    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px 12px;
    }

    .search-header h1 {
        font-size: 25px;
    }
}

@media (max-width: 450px) {
    .product-grid {
        grid-template-columns: 1fr 1fr;
    }

    .product-info h3 {
        font-size: 13px;
    }
}
</style>