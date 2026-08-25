<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";
import {
    CheckCircleFilled,
    ShoppingOutlined,
    EnvironmentOutlined,
    PhoneOutlined,
    MailOutlined,
    ArrowRightOutlined,
    HomeOutlined,
    FileTextOutlined,
} from "@ant-design/icons-vue";

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    return Number(amount || 0).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

/*
|--------------------------------------------------------------------------
| ORDER ITEMS
|--------------------------------------------------------------------------
*/

const orderItems = computed(() => {
    return props.order?.items || [];
});

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

const orderStatus = computed(() => {
    return props.order?.status || "pending";
});

/*
|--------------------------------------------------------------------------
| PAYMENT METHOD
|--------------------------------------------------------------------------
*/

const paymentMethod = computed(() => {
    const method = props.order?.payment_method;

    const methods = {
        cash_on_delivery: "Cash on Delivery",
        mobile_money: "Mobile Money",
        card: "Card Payment",
    };

    return methods[method] || method || "Not specified";
});

/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

const paymentStatus = computed(() => {
    const status = props.order?.payment_status || "pending";

    return status.charAt(0).toUpperCase() + status.slice(1);
});

/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
*/

const imageUrl = (item) => {
    if (!item?.product) {
        return null;
    }

    const image =
        item.product.image ||
        item.product.featured_image ||
        item.product.thumbnail;

    if (!image) {
        return null;
    }

    if (
        image.startsWith("http://") ||
        image.startsWith("https://") ||
        image.startsWith("/")
    ) {
        return image;
    }

    return `/storage/${image}`;
};
</script>

<template>

    <Head title="Order Confirmation" />

    <UserFashionLayout>
        <div class="min-h-screen bg-[#faf9f7]">
            <!-- =========================================================
             HEADER
        ========================================================== -->

            <header class="border-b border-gray-200 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                    <div>
                        <Link href="/" class="text-xl font-bold tracking-tight text-gray-900">
                            FASHION STYLES
                        </Link>

                        <p class="mt-1 text-xs text-gray-500">
                            Order Confirmation
                        </p>
                    </div>

                    <Link href="/client/products"
                        class="hidden items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-amber-400 hover:text-amber-600 sm:inline-flex">
                        <ShoppingOutlined />
                        Continue Shopping
                    </Link>
                </div>
            </header>

            <!-- =========================================================
             MAIN
        ========================================================== -->

            <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
                <!-- SUCCESS MESSAGE -->

                <section class="text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
                        <CheckCircleFilled class="text-5xl text-green-500" />
                    </div>

                    <h1 class="mt-6 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Thank You For Your Order!
                    </h1>

                    <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-gray-500 sm:text-base">
                        Your order has been successfully placed. We have received
                        your order and will process it shortly.
                    </p>

                    <!-- ORDER NUMBER -->

                    <div
                        class="mx-auto mt-6 inline-flex flex-col items-center rounded-2xl border border-amber-200 bg-amber-50 px-8 py-4">
                        <span class="text-xs font-medium uppercase tracking-wider text-amber-700">
                            Order Number
                        </span>

                        <span class="mt-1 text-xl font-bold tracking-wide text-gray-900">
                            {{ order.order_number }}
                        </span>
                    </div>
                </section>

                <!-- =====================================================
                 ORDER CONTENT
            ====================================================== -->

                <div class="mt-10 grid gap-6 lg:grid-cols-3">
                    <!-- =================================================
                     LEFT
                ================================================== -->

                    <div class="space-y-6 lg:col-span-2">
                        <!-- ORDER ITEMS -->

                        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                                <div>
                                    <h2 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                                        <ShoppingOutlined class="text-amber-500" />

                                        Order Items
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ orderItems.length }}
                                        {{ orderItems.length === 1 ? "item" : "items" }}
                                    </p>
                                </div>

                                <span
                                    class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold capitalize text-amber-700">
                                    {{ orderStatus }}
                                </span>
                            </div>

                            <!-- ITEMS -->

                            <div class="divide-y divide-gray-100">
                                <div v-for="item in orderItems" :key="item.id" class="flex gap-4 py-5">
                                    <!-- IMAGE -->

                                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-gray-100">
                                        <img v-if="imageUrl(item)" :src="imageUrl(item)" :alt="item.product_name"
                                            class="h-full w-full object-cover" />

                                        <div v-else
                                            class="flex h-full w-full items-center justify-center text-gray-400">
                                            <ShoppingOutlined class="text-2xl" />
                                        </div>
                                    </div>

                                    <!-- INFORMATION -->

                                    <div class="min-w-0 flex-1">
                                        <h3 class="truncate text-sm font-semibold text-gray-900 sm:text-base">
                                            {{ item.product_name }}
                                        </h3>

                                        <p v-if="item.sku" class="mt-1 text-xs text-gray-400">
                                            SKU: {{ item.sku }}
                                        </p>

                                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                                            <span>
                                                Quantity:
                                                <strong class="text-gray-700">
                                                    {{ item.quantity }}
                                                </strong>
                                            </span>

                                            <span>
                                                Unit Price:
                                                <strong class="text-gray-700">
                                                    K{{ formatMoney(item.unit_price) }}
                                                </strong>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- TOTAL -->

                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900 sm:text-base">
                                            K{{ formatMoney(item.total_price) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- DELIVERY INFORMATION -->

                        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                            <h2 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                                <EnvironmentOutlined class="text-amber-500" />

                                Delivery Information
                            </h2>

                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <!-- CUSTOMER -->

                                <div class="rounded-xl bg-gray-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Customer
                                    </p>

                                    <p class="mt-2 font-semibold text-gray-900">
                                        {{ order.shipping_first_name }}
                                        {{ order.shipping_last_name }}
                                    </p>

                                    <p v-if="order.customer_email"
                                        class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                        <MailOutlined />
                                        {{ order.customer_email }}
                                    </p>

                                    <p v-if="order.customer_phone"
                                        class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                        <PhoneOutlined />
                                        {{ order.customer_phone }}
                                    </p>
                                </div>

                                <!-- ADDRESS -->

                                <div class="rounded-xl bg-gray-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Delivery Address
                                    </p>

                                    <p class="mt-2 text-sm leading-6 text-gray-700">
                                        {{ order.shipping_address }}
                                        <br />
                                        {{ order.shipping_city }}
                                        <br />
                                        {{ order.shipping_country }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- =================================================
                     RIGHT
                ================================================== -->

                    <div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:sticky lg:top-6">
                            <h2 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                                <FileTextOutlined class="text-amber-500" />

                                Order Summary
                            </h2>

                            <!-- TOTALS -->

                            <div class="mt-6 space-y-4 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">
                                        Subtotal
                                    </span>

                                    <span class="font-medium text-gray-900">
                                        K{{ formatMoney(order.subtotal) }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500">
                                        Shipping
                                    </span>

                                    <span class="font-medium text-gray-900">
                                        K{{ formatMoney(order.shipping_amount) }}
                                    </span>
                                </div>
                            </div>

                            <div class="my-5 border-t border-gray-100"></div>

                            <div class="flex items-center justify-between">
                                <span class="text-base font-bold text-gray-900">
                                    Total
                                </span>

                                <span class="text-2xl font-bold text-amber-600">
                                    K{{ formatMoney(order.total_amount) }}
                                </span>
                            </div>

                            <!-- PAYMENT -->

                            <div class="mt-6 rounded-xl bg-gray-50 p-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">
                                        Payment Method
                                    </span>

                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ paymentMethod }}
                                    </span>
                                </div>

                                <div class="mt-3 flex justify-between">
                                    <span class="text-sm text-gray-500">
                                        Payment Status
                                    </span>

                                    <span
                                        class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                        {{ paymentStatus }}
                                    </span>
                                </div>
                            </div>

                            <!-- ACTIONS -->

                            <div class="mt-6 space-y-3">
                                <Link href="/client/products"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                                    <ShoppingOutlined />

                                    Continue Shopping

                                    <ArrowRightOutlined />
                                </Link>

                                <Link :href="`/client/orders/${order.id}`"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3.5 text-sm font-semibold text-gray-700 transition hover:border-amber-400 hover:text-amber-600">
                                    <FileTextOutlined />

                                    View Order
                                </Link>

                                <Link href="/"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-medium text-gray-500 transition hover:text-gray-900">
                                    <HomeOutlined />

                                    Back to Home
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =====================================================
                 MOBILE CONTINUE SHOPPING
            ====================================================== -->

                <div class="mt-8 text-center sm:hidden">
                    <Link href="/client/products"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white">
                        <ShoppingOutlined />
                        Continue Shopping
                    </Link>
                </div>
            </main>
        </div>
    </UserFashionLayout>

</template>

<style scoped>
/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

:deep(*) {
    box-sizing: border-box;
}

:deep(body) {
    margin: 0;
}

/*
|--------------------------------------------------------------------------
| SMOOTH ANIMATION
|--------------------------------------------------------------------------
*/

section {
    animation: fadeUp 0.45s ease both;
}

section:nth-child(2) {
    animation-delay: 0.08s;
}

/*
|--------------------------------------------------------------------------
| FADE UP
|--------------------------------------------------------------------------
*/

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

img {
    transition: transform 0.3s ease;
}

img:hover {
    transform: scale(1.05);
}

/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 640px) {
    main {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
}
</style>