<!-- resources/js/Pages/MyFashions/Clients/Cart/Index.vue -->

<script setup>
import { computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    ShoppingCartOutlined,
    DeleteOutlined,
    MinusOutlined,
    PlusOutlined,
    ArrowLeftOutlined,
    ShoppingOutlined,
    RightOutlined,
    InboxOutlined,
} from "@ant-design/icons-vue";

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    cart: {
        type: Array,
        default: () => [],
    },

    subtotal: {
        type: [Number, String],
        default: 0,
    },

    totalItems: {
        type: [Number, String],
        default: 0,
    },

    cartCount: {
        type: Number,
        default: 0,
    },
});

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

const cartItems = computed(() => {
    return Array.isArray(props.cart)
        ? props.cart
        : [];
});

/*
|--------------------------------------------------------------------------
| EMPTY CART
|--------------------------------------------------------------------------
*/

const isEmpty = computed(() => {
    return cartItems.value.length === 0;
});

/*
|--------------------------------------------------------------------------
| FORMATTED SUBTOTAL
|--------------------------------------------------------------------------
*/

const formattedSubtotal = computed(() => {
    return Number(props.subtotal || 0).toLocaleString(
        "en-ZM",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }
    );
});

/*
|--------------------------------------------------------------------------
| ITEM PRICE
|--------------------------------------------------------------------------
*/

const getItemPrice = (item) => {
    return Number(
        item.sale_price !== null &&
        item.sale_price !== undefined
            ? item.sale_price
            : item.price || 0
    );
};

/*
|--------------------------------------------------------------------------
| FORMATTED ITEM PRICE
|--------------------------------------------------------------------------
*/

const formatPrice = (price) => {
    return Number(price || 0).toLocaleString(
        "en-ZM",
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }
    );
};

/*
|--------------------------------------------------------------------------
| ITEM TOTAL
|--------------------------------------------------------------------------
*/

const itemTotal = (item) => {
    return (
        getItemPrice(item) *
        Number(item.quantity || 0)
    );
};

/*
|--------------------------------------------------------------------------
| CHECK SALE
|--------------------------------------------------------------------------
*/

const hasSalePrice = (item) => {
    return (
        item.sale_price !== null &&
        item.sale_price !== undefined &&
        Number(item.sale_price) < Number(item.price)
    );
};

/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {
    if (!image) {
        return "/images/placeholder.jpg";
    }

    if (
        image.startsWith("http://") ||
        image.startsWith("https://")
    ) {
        return image;
    }

    return `/storage/${image}`;
};

/*
|--------------------------------------------------------------------------
| UPDATE QUANTITY
|--------------------------------------------------------------------------
*/

const updateQuantity = (item, quantity) => {
    quantity = Number(quantity);

    if (quantity < 1) {
        quantity = 1;
    }

    const stock = Number(item.stock_quantity || 0);

    if (stock > 0 && quantity > stock) {
        Swal.fire({
            icon: "warning",
            title: "Stock limit reached",
            text: `Only ${stock} item${
                stock === 1 ? "" : "s"
            } available.`,
            confirmButtonColor: "#111827",
        });

        return;
    }

    router.patch(
        `/client/cart/${item.id}`,
        {
            quantity: quantity,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "Cart updated",
                    showConfirmButton: false,
                    timer: 1400,
                });
            },

            onError: (errors) => {
                Swal.fire({
                    icon: "error",
                    title: "Unable to update",
                    text:
                        errors?.quantity ||
                        errors?.error ||
                        "Something went wrong.",
                    confirmButtonColor: "#111827",
                });
            },
        }
    );
};

/*
|--------------------------------------------------------------------------
| INCREASE
|--------------------------------------------------------------------------
*/

const increaseQuantity = (item) => {
    const current = Number(item.quantity || 0);

    const stock = Number(
        item.stock_quantity || 0
    );

    if (stock > 0 && current >= stock) {
        Swal.fire({
            icon: "warning",
            title: "Maximum stock reached",
            text: `Only ${stock} item${
                stock === 1 ? "" : "s"
            } available.`,
            confirmButtonColor: "#111827",
        });

        return;
    }

    updateQuantity(item, current + 1);
};

/*
|--------------------------------------------------------------------------
| DECREASE
|--------------------------------------------------------------------------
*/

const decreaseQuantity = (item) => {
    const current = Number(item.quantity || 1);

    if (current <= 1) {
        return;
    }

    updateQuantity(item, current - 1);
};

/*
|--------------------------------------------------------------------------
| REMOVE ITEM
|--------------------------------------------------------------------------
*/

const removeItem = (item) => {
    Swal.fire({
        title: "Remove item?",
        text: `"${item.name}" will be removed from your shopping bag.`,
        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Yes, remove it",
        cancelButtonText: "Keep it",

        confirmButtonColor: "#111827",
        cancelButtonColor: "#d1d5db",

        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        router.delete(
            `/client/cart/${item.id}`,
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "Item removed",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                },

                onError: () => {
                    Swal.fire({
                        icon: "error",
                        title: "Unable to remove",
                        text: "Please try again.",
                        confirmButtonColor: "#111827",
                    });
                },
            }
        );
    });
};

/*
|--------------------------------------------------------------------------
| CLEAR CART
|--------------------------------------------------------------------------
*/

const clearCart = () => {
    if (isEmpty.value) {
        return;
    }

    Swal.fire({
        title: "Clear shopping bag?",
        text: "All products will be removed from your shopping bag.",
        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Yes, clear bag",
        cancelButtonText: "Cancel",

        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",

        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        router.delete(
            "/client/cart/clear",
            {
                preserveScroll: true,

                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "Shopping bag cleared",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                },

                onError: () => {
                    Swal.fire({
                        icon: "error",
                        title: "Unable to clear bag",
                        text: "Please try again.",
                        confirmButtonColor: "#111827",
                    });
                },
            }
        );
    });
};
</script>

<template>
    <Head title="My Cart" />

    <UserFashionLayout
        :cart-count="Number(props.cartCount || props.totalItems || 0)"
    >
        <div class="cart-page">

            <!-- =========================================================
                 PAGE HEADER
            ========================================================== -->

            <section class="cart-header">

                <div class="header-inner">

                    <div>
                        <div class="breadcrumb">

                            <Link
                                href="/client/dashboard"
                                class="breadcrumb-link"
                            >
                                Dashboard
                            </Link>

                            <RightOutlined
                                class="breadcrumb-icon"
                            />

                            <span>
                                My Cart
                            </span>

                        </div>

                        <div class="title-row">

                            <div class="title-icon">
                                <ShoppingCartOutlined />
                            </div>

                            <div>
                                <h1>
                                    My Shopping Bag
                                </h1>

                                <p>
                                    Review your items before
                                    proceeding to checkout.
                                </p>
                            </div>

                        </div>
                    </div>

                    <div
                        v-if="!isEmpty"
                        class="header-count"
                    >
                        <strong>
                            {{ props.totalItems }}
                        </strong>

                        <span>
                            {{
                                Number(props.totalItems) === 1
                                    ? "Item"
                                    : "Items"
                            }}
                        </span>
                    </div>

                </div>

            </section>


            <!-- =========================================================
                 MAIN CONTENT
            ========================================================== -->

            <main class="cart-container">

                <!-- =====================================================
                     EMPTY CART
                ====================================================== -->

                <div
                    v-if="isEmpty"
                    class="empty-cart"
                >

                    <div class="empty-icon">
                        <InboxOutlined />
                    </div>

                    <h2>
                        Your shopping bag is empty
                    </h2>

                    <p>
                        You haven't added anything to your
                        shopping bag yet. Explore our collection
                        and find something you love.
                    </p>

                    <Link
                        href="/client/shop"
                        class="shop-button"
                    >
                        <ShoppingOutlined />

                        Continue Shopping
                    </Link>

                </div>


                <!-- =====================================================
                     CART CONTENT
                ====================================================== -->

                <div
                    v-else
                    class="cart-layout"
                >

                    <!-- =================================================
                         ITEMS
                    ================================================== -->

                    <section class="cart-items-section">

                        <div class="section-heading">

                            <div>
                                <h2>
                                    Your Items
                                </h2>

                                <p>
                                    {{
                                        props.totalItems
                                    }}
                                    {{
                                        Number(props.totalItems) === 1
                                            ? "item"
                                            : "items"
                                    }}
                                    in your shopping bag
                                </p>
                            </div>

                            <button
                                type="button"
                                class="clear-button"
                                @click="clearCart"
                            >
                                <DeleteOutlined />

                                Clear Bag
                            </button>

                        </div>


                        <!-- ITEMS -->

                        <div class="items-list">

                            <article
                                v-for="item in cartItems"
                                :key="item.id"
                                class="cart-item"
                            >

                                <!-- IMAGE -->

                                <div class="product-image-wrapper">

                                    <img
                                        :src="imageUrl(item.image)"
                                        :alt="item.name"
                                        class="product-image"
                                    />

                                    <span
                                        v-if="hasSalePrice(item)"
                                        class="sale-badge"
                                    >
                                        SALE
                                    </span>

                                </div>


                                <!-- PRODUCT DETAILS -->

                                <div class="product-details">

                                    <div class="product-main">

                                        <div>

                                            <span class="product-sku">
                                                SKU:
                                                {{ item.sku }}
                                            </span>

                                            <h3>
                                                {{ item.name }}
                                            </h3>

                                            <Link
                                                :href="`/client/shop/${item.slug}`"
                                                class="view-product"
                                            >
                                                View Product
                                            </Link>

                                        </div>

                                        <!-- REMOVE DESKTOP -->

                                        <button
                                            type="button"
                                            class="remove-button desktop-remove"
                                            @click="removeItem(item)"
                                        >
                                            <DeleteOutlined />

                                            Remove
                                        </button>

                                    </div>


                                    <!-- PRICE / QUANTITY -->

                                    <div class="product-bottom">

                                        <div class="price-area">

                                            <div class="current-price">
                                                K
                                                {{
                                                    formatPrice(
                                                        getItemPrice(item)
                                                    )
                                                }}
                                            </div>

                                            <div
                                                v-if="hasSalePrice(item)"
                                                class="old-price"
                                            >
                                                K
                                                {{
                                                    formatPrice(
                                                        item.price
                                                    )
                                                }}
                                            </div>

                                        </div>


                                        <!-- QUANTITY -->

                                        <div class="quantity-area">

                                            <span class="quantity-label">
                                                Quantity
                                            </span>

                                            <div class="quantity-control">

                                                <button
                                                    type="button"
                                                    @click="
                                                        decreaseQuantity(item)
                                                    "
                                                    :disabled="
                                                        Number(item.quantity) <= 1
                                                    "
                                                >
                                                    <MinusOutlined />
                                                </button>

                                                <span>
                                                    {{ item.quantity }}
                                                </span>

                                                <button
                                                    type="button"
                                                    @click="
                                                        increaseQuantity(item)
                                                    "
                                                    :disabled="
                                                        Number(item.quantity) >=
                                                        Number(
                                                            item.stock_quantity
                                                        )
                                                    "
                                                >
                                                    <PlusOutlined />
                                                </button>

                                            </div>

                                            <small>
                                                {{
                                                    item.stock_quantity
                                                }}
                                                available
                                            </small>

                                        </div>


                                        <!-- TOTAL -->

                                        <div class="line-total">

                                            <span>
                                                Total
                                            </span>

                                            <strong>
                                                K
                                                {{
                                                    formatPrice(
                                                        itemTotal(item)
                                                    )
                                                }}
                                            </strong>

                                        </div>


                                        <!-- REMOVE MOBILE -->

                                        <button
                                            type="button"
                                            class="remove-button mobile-remove"
                                            @click="removeItem(item)"
                                        >
                                            <DeleteOutlined />

                                            Remove
                                        </button>

                                    </div>

                                </div>

                            </article>

                        </div>


                        <!-- CONTINUE SHOPPING -->

                        <div class="continue-shopping">

                            <Link
                                href="/client/shop"
                                class="continue-link"
                            >
                                <ArrowLeftOutlined />

                                Continue Shopping
                            </Link>

                        </div>

                    </section>


                    <!-- =================================================
                         SUMMARY
                    ================================================== -->

                    <aside class="cart-summary">

                        <div class="summary-card">

                            <div class="summary-title">
                                <ShoppingCartOutlined />

                                <h2>
                                    Order Summary
                                </h2>
                            </div>


                            <div class="summary-line">

                                <span>
                                    Subtotal
                                </span>

                                <strong>
                                    K {{ formattedSubtotal }}
                                </strong>

                            </div>


                            <div class="summary-line">

                                <span>
                                    Shipping
                                </span>

                                <span class="free-shipping">
                                    Calculated at checkout
                                </span>

                            </div>


                            <div class="summary-divider"></div>


                            <div class="summary-total">

                                <span>
                                    Estimated Total
                                </span>

                                <strong>
                                    K {{ formattedSubtotal }}
                                </strong>

                            </div>


                            <Link
                                href="/client/checkout"
                                class="checkout-button"
                            >
                                Proceed to Checkout

                                <RightOutlined />
                            </Link>


                            <div class="secure-checkout">

                                <span class="secure-icon">
                                    ✓
                                </span>

                                <span>
                                    Secure checkout
                                </span>

                            </div>

                        </div>


                        <!-- BENEFITS -->

                        <div class="benefits-card">

                            <div class="benefit">

                                <div class="benefit-icon">
                                    <ShoppingOutlined />
                                </div>

                                <div>
                                    <strong>
                                        Quality Products
                                    </strong>

                                    <span>
                                        Carefully selected for you
                                    </span>
                                </div>

                            </div>


                            <div class="benefit">

                                <div class="benefit-icon">
                                    <CheckCircleOutlined />
                                </div>

                                <div>
                                    <strong>
                                        Secure Shopping
                                    </strong>

                                    <span>
                                        Your information is protected
                                    </span>
                                </div>

                            </div>

                        </div>

                    </aside>

                </div>

            </main>

        </div>
    </UserFashionLayout>
</template>


<style scoped>
/* ============================================================
   PAGE
============================================================ */

.cart-page {
    min-height: 100vh;
    background:
        linear-gradient(
            135deg,
            #fffdf8 0%,
            #fff8ed 45%,
            #fff3d9 100%
        );
    color: #1f2937;
}


/* ============================================================
   HEADER
============================================================ */

.cart-header {
    border-bottom: 1px solid #eadfce;
    background: rgba(255, 255, 255, 0.86);
    backdrop-filter: blur(10px);
}

.header-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 34px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 17px;
    font-size: 13px;
    color: #8b8174;
}

.breadcrumb-link {
    color: #6b6258;
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: #111827;
}

.breadcrumb-icon {
    font-size: 10px;
    color: #b7aa99;
}

.title-row {
    display: flex;
    align-items: center;
    gap: 17px;
}

.title-icon {
    width: 52px;
    height: 52px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #111827;
    color: white;
    font-size: 23px;
    box-shadow: 0 10px 25px rgba(17, 24, 39, 0.15);
}

.title-row h1 {
    margin: 0;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -0.6px;
    color: #111827;
}

.title-row p {
    margin: 5px 0 0;
    color: #7b7166;
    font-size: 14px;
}

.header-count {
    min-width: 82px;
    padding: 14px 20px;
    border-radius: 16px;
    background: #fff8e8;
    border: 1px solid #ead7b4;
    text-align: center;
}

.header-count strong {
    display: block;
    font-size: 23px;
    color: #111827;
}

.header-count span {
    display: block;
    margin-top: 2px;
    color: #8b8174;
    font-size: 12px;
}


/* ============================================================
   CONTAINER
============================================================ */

.cart-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 42px 28px 70px;
}


/* ============================================================
   EMPTY CART
============================================================ */

.empty-cart {
    max-width: 650px;
    margin: 50px auto;
    padding: 65px 35px;
    text-align: center;
    background: rgba(255, 255, 255, 0.88);
    border: 1px solid #eadfce;
    border-radius: 24px;
    box-shadow: 0 18px 50px rgba(80, 60, 30, 0.08);
}

.empty-icon {
    width: 88px;
    height: 88px;
    margin: 0 auto 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff4d8;
    color: #8a6a2f;
    font-size: 40px;
}

.empty-cart h2 {
    margin: 0;
    color: #111827;
    font-size: 26px;
    font-weight: 800;
}

.empty-cart p {
    max-width: 480px;
    margin: 13px auto 27px;
    line-height: 1.7;
    color: #7b7166;
    font-size: 14px;
}

.shop-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 13px 23px;
    border-radius: 11px;
    background: #111827;
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    transition: all 0.25s ease;
}

.shop-button:hover {
    background: #2b3444;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(17, 24, 39, 0.18);
}


/* ============================================================
   CART LAYOUT
============================================================ */

.cart-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 370px;
    gap: 30px;
    align-items: start;
}


/* ============================================================
   ITEMS SECTION
============================================================ */

.cart-items-section {
    min-width: 0;
}

.section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.section-heading h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #111827;
}

.section-heading p {
    margin: 5px 0 0;
    font-size: 13px;
    color: #8a8177;
}

.clear-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border: 1px solid #e5d6c6;
    background: white;
    color: #9b3b32;
    padding: 9px 13px;
    border-radius: 9px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    transition: all 0.2s ease;
}

.clear-button:hover {
    border-color: #d6a29b;
    background: #fff6f5;
}


/* ============================================================
   ITEM
============================================================ */

.items-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.cart-item {
    display: flex;
    gap: 19px;
    padding: 18px;
    border: 1px solid #eadfce;
    background: rgba(255, 255, 255, 0.94);
    border-radius: 17px;
    box-shadow: 0 7px 25px rgba(80, 60, 30, 0.045);
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 13px 32px rgba(80, 60, 30, 0.08);
}


/* ============================================================
   PRODUCT IMAGE
============================================================ */

.product-image-wrapper {
    position: relative;
    width: 125px;
    height: 145px;
    flex: 0 0 125px;
    overflow: hidden;
    border-radius: 13px;
    background: #f5efe6;
}

.product-image {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.cart-item:hover .product-image {
    transform: scale(1.04);
}

.sale-badge {
    position: absolute;
    top: 9px;
    left: 9px;
    padding: 4px 7px;
    border-radius: 6px;
    background: #111827;
    color: white;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.7px;
}


/* ============================================================
   DETAILS
============================================================ */

.product-details {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-main {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.product-sku {
    color: #a29688;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}

.product-main h3 {
    margin: 5px 0 4px;
    color: #111827;
    font-size: 17px;
    font-weight: 750;
    line-height: 1.35;
}

.view-product {
    color: #8a6a2f;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.view-product:hover {
    text-decoration: underline;
}


/* ============================================================
   REMOVE
============================================================ */

.remove-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 0;
    background: transparent;
    color: #9b3b32;
    cursor: pointer;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.remove-button:hover {
    color: #c62828;
}

.mobile-remove {
    display: none;
}


/* ============================================================
   BOTTOM
============================================================ */

.product-bottom {
    display: flex;
    align-items: flex-end;
    gap: 30px;
    margin-top: 20px;
}


/* ============================================================
   PRICE
============================================================ */

.price-area {
    min-width: 100px;
}

.current-price {
    color: #111827;
    font-size: 16px;
    font-weight: 800;
}

.old-price {
    margin-top: 2px;
    color: #a59b91;
    font-size: 11px;
    text-decoration: line-through;
}


/* ============================================================
   QUANTITY
============================================================ */

.quantity-area {
    text-align: center;
}

.quantity-label {
    display: block;
    margin-bottom: 6px;
    color: #91867a;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
}

.quantity-control {
    display: flex;
    align-items: center;
    height: 34px;
    overflow: hidden;
    border: 1px solid #ded2c2;
    border-radius: 8px;
    background: white;
}

.quantity-control button {
    width: 33px;
    height: 34px;
    border: 0;
    background: #faf7f2;
    color: #4b4036;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.quantity-control button:hover:not(:disabled) {
    background: #f0e8dc;
}

.quantity-control button:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

.quantity-control span {
    width: 38px;
    text-align: center;
    color: #111827;
    font-size: 13px;
    font-weight: 800;
}

.quantity-area small {
    display: block;
    margin-top: 4px;
    color: #9b9186;
    font-size: 9px;
}


/* ============================================================
   LINE TOTAL
============================================================ */

.line-total {
    margin-left: auto;
    min-width: 110px;
    text-align: right;
}

.line-total span {
    display: block;
    margin-bottom: 4px;
    color: #9b9186;
    font-size: 10px;
}

.line-total strong {
    color: #111827;
    font-size: 17px;
}


/* ============================================================
   CONTINUE SHOPPING
============================================================ */

.continue-shopping {
    margin-top: 20px;
}

.continue-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #5f554b;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s ease;
}

.continue-link:hover {
    color: #111827;
}


/* ============================================================
   SUMMARY
============================================================ */

.cart-summary {
    position: sticky;
    top: 25px;
}

.summary-card {
    padding: 25px;
    border: 1px solid #e4d7c6;
    border-radius: 18px;
    background: white;
    box-shadow: 0 13px 38px rgba(70, 50, 25, 0.08);
}

.summary-title {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 18px;
    border-bottom: 1px solid #eee5d9;
}

.summary-title > span {
    color: #8a6a2f;
    font-size: 18px;
}

.summary-title h2 {
    margin: 0;
    color: #111827;
    font-size: 17px;
    font-weight: 800;
}


/* ============================================================
   SUMMARY LINES
============================================================ */

.summary-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 0;
    color: #6f665c;
    font-size: 13px;
}

.summary-line strong {
    color: #111827;
}

.free-shipping {
    max-width: 170px;
    text-align: right;
    color: #8f8477;
    font-size: 11px;
}

.summary-divider {
    height: 1px;
    background: #e9dfd2;
}


/* ============================================================
   TOTAL
============================================================ */

.summary-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 0 21px;
}

.summary-total span {
    color: #111827;
    font-size: 14px;
    font-weight: 700;
}

.summary-total strong {
    color: #111827;
    font-size: 22px;
    font-weight: 850;
}


/* ============================================================
   CHECKOUT
============================================================ */

.checkout-button {
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    border-radius: 10px;
    background: #111827;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    transition:
        background 0.2s ease,
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.checkout-button:hover {
    background: #293344;
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(17, 24, 39, 0.18);
}


/* ============================================================
   SECURE
============================================================ */

.secure-checkout {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 7px;
    margin-top: 15px;
    color: #8b8177;
    font-size: 10px;
}

.secure-icon {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #e8f5e9;
    color: #3d7c43;
    font-size: 9px;
    font-weight: 800;
}


/* ============================================================
   BENEFITS
============================================================ */

.benefits-card {
    margin-top: 15px;
    padding: 18px;
    border: 1px solid #eadfce;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.75);
}

.benefit {
    display: flex;
    align-items: center;
    gap: 12px;
}

.benefit + .benefit {
    margin-top: 17px;
    padding-top: 17px;
    border-top: 1px solid #eee5da;
}

.benefit-icon {
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #fff2d4;
    color: #8a6a2f;
}

.benefit strong {
    display: block;
    color: #40382f;
    font-size: 11px;
}

.benefit span {
    display: block;
    margin-top: 3px;
    color: #93897e;
    font-size: 10px;
}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 1050px) {

    .cart-layout {
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 20px;
    }

    .product-bottom {
        gap: 18px;
    }

    .line-total {
        min-width: 90px;
    }
}


@media (max-width: 850px) {

    .cart-layout {
        grid-template-columns: 1fr;
    }

    .cart-summary {
        position: static;
    }

    .summary-card {
        max-width: none;
    }
}


@media (max-width: 650px) {

    .header-inner {
        padding: 25px 18px;
    }

    .cart-container {
        padding: 28px 15px 50px;
    }

    .title-icon {
        width: 44px;
        height: 44px;
        font-size: 19px;
    }

    .title-row h1 {
        font-size: 24px;
    }

    .title-row p {
        font-size: 12px;
    }

    .header-count {
        display: none;
    }

    .section-heading {
        align-items: flex-start;
    }

    .cart-item {
        padding: 13px;
        gap: 13px;
    }

    .product-image-wrapper {
        width: 90px;
        height: 115px;
        flex-basis: 90px;
    }

    .product-main h3 {
        font-size: 14px;
    }

    .desktop-remove {
        display: none;
    }

    .mobile-remove {
        display: inline-flex;
        margin-left: auto;
    }

    .product-bottom {
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    .line-total {
        margin-left: 0;
        text-align: left;
    }

    .quantity-area {
        text-align: left;
    }
}


@media (max-width: 480px) {

    .breadcrumb {
        margin-bottom: 13px;
    }

    .title-row {
        gap: 11px;
    }

    .title-row h1 {
        font-size: 21px;
    }

    .title-row p {
        display: none;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 82px 1fr;
    }

    .product-image-wrapper {
        width: 82px;
        height: 105px;
    }

    .product-details {
        min-width: 0;
    }

    .product-main {
        display: block;
    }

    .product-main h3 {
        font-size: 13px;
        margin-top: 4px;
    }

    .view-product {
        font-size: 10px;
    }

    .product-bottom {
        margin-top: 14px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: end;
    }

    .line-total {
        grid-column: 1 / -1;
    }

    .mobile-remove {
        grid-column: 1 / -1;
        justify-self: start;
        margin-left: 0;
    }

    .summary-card {
        padding: 20px 17px;
    }

    .empty-cart {
        padding: 45px 20px;
    }
}
</style>