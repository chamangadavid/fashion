<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },

    categories: {
        type: Array,
        default: () => [],
    },

    viewMoreHref: {
        type: String,
        default: "#",
    },
});

/*
|--------------------------------------------------------------------------
| CATEGORY IMAGE
|--------------------------------------------------------------------------
*/

const getImageUrl = (image) => {
    if (!image) {
        return "/images/placeholder-product.jpg";
    }

    if (
        image.startsWith("http://") ||
        image.startsWith("https://")
    ) {
        return image;
    }

    if (image.startsWith("/storage/")) {
        return image;
    }

    return `/storage/${image}`;
};

/*
|--------------------------------------------------------------------------
| CATEGORY URL
|--------------------------------------------------------------------------
*/

const categoryHref = (category) => {
    if (category.group === "accessories") {
        return `/accessories/${category.slug}`;
    }

    return `/clothing/${category.slug}`;
};
</script>

<template>

    <section
        v-if="categories.length"
        class="category-section"
    >

        <div class="category-section-container">

            <!-- =====================================
                 TITLE
            ====================================== -->

            <h2 class="category-section-title">
                {{ title }}
            </h2>


            <!-- =====================================
                 CATEGORY GRID
            ====================================== -->

            <div class="category-grid">

                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="categoryHref(category)"
                    class="category-card"
                >

                    <!-- IMAGE -->

                    <div class="category-image-wrapper">

                        <img
                            :src="getImageUrl(category.image)"
                            :alt="category.name"
                            class="category-image"
                            loading="lazy"
                        />

                    </div>


                    <!-- NAME -->

                    <div class="category-name">
                        {{ category.name }}
                    </div>

                </Link>

            </div>


            <!-- =====================================
                 VIEW MORE
            ====================================== -->

            <div class="view-more-wrapper">

                <Link
                    :href="viewMoreHref"
                    class="view-more"
                >
                    View More
                </Link>

            </div>

        </div>

    </section>

</template>


<style scoped>

/* =========================================
   MAIN SECTION
========================================= */

.category-section {

    width: 100%;

    background: #ffffff;

    padding: 35px 0 40px;

}


/* =========================================
   CONTAINER
========================================= */

.category-section-container {

    width: min(
        1070px,
        calc(100% - 70px)
    );

    margin: 0 auto;

}


/* =========================================
   TITLE
========================================= */

.category-section-title {

    margin: 0 0 30px;

    color: #161616;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 28px;

    line-height: 1.2;

    font-weight: 500;

    text-align: center;

}


/* =========================================
   GRID
========================================= */

.category-grid {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 16px;

}


/* =========================================
   CATEGORY CARD
========================================= */

.category-card {

    display: block;

    min-width: 0;

    color: #222222;

    text-decoration: none;

}


/* =========================================
   IMAGE WRAPPER
========================================= */

.category-image-wrapper {

    position: relative;

    width: 100%;

    aspect-ratio: 1 / 1.08;

    overflow: hidden;

    background: #eeeeee;

}


/* =========================================
   IMAGE
========================================= */

.category-image {

    width: 100%;

    height: 100%;

    display: block;

    object-fit: cover;

    transition:
        transform 0.4s ease;
}


/* =========================================
   IMAGE HOVER
========================================= */

.category-card:hover
.category-image {

    transform: scale(1.035);

}


/* =========================================
   CATEGORY NAME
========================================= */

.category-name {

    margin-top: 12px;

    color: #333333;

    font-family: Arial, Helvetica, sans-serif;

    font-size: 12px;

    line-height: 1.3;

    font-weight: 500;

    text-align: center;

    transition:
        color 0.25s ease;

}


.category-card:hover
.category-name {

    color: #075d5d;

}


/* =========================================
   VIEW MORE
========================================= */

.view-more-wrapper {

    display: flex;

    justify-content: center;

    margin-top: 28px;

}


.view-more {

    color: #333333;

    font-size: 12px;

    text-decoration: underline;

    text-underline-offset: 3px;

    transition:
        color 0.25s ease;

}


.view-more:hover {

    color: #075d5d;

}


/* =========================================
   TABLET
========================================= */

@media (max-width: 850px) {

    .category-section-container {

        width: calc(100% - 40px);

    }


    .category-grid {

        gap: 12px;

    }


    .category-section-title {

        font-size: 26px;

    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 650px) {

    .category-section {

        padding: 28px 0 32px;

    }


    .category-section-container {

        width: calc(100% - 24px);

    }


    .category-section-title {

        margin-bottom: 22px;

        font-size: 23px;

    }


    .category-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 24px 10px;

    }


    .category-name {

        font-size: 10px;

    }

}

</style>