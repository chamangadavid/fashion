<!-- resources/js/Layouts/UserFashionLayout.vue -->

<script setup>

import { ref, computed } from "vue";
import { Head, Link, router, usePage, } from '@inertiajs/vue3'
import Swal from 'sweetalert2'


import {
    DashboardOutlined,
    ShoppingOutlined,
    ShoppingCartOutlined,
    CreditCardOutlined,
    FileTextOutlined,
    ClockCircleOutlined,
    UserOutlined,
    LogoutOutlined,
    RightOutlined,
    AppstoreOutlined,
    MenuOutlined,
    CloseOutlined,
    SearchOutlined,
    ArrowDownOutlined,
    CheckCircleOutlined,
} from "@ant-design/icons-vue";


/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

const page = usePage();


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
|
| The parent page can provide:
|
| cartCount
| totalOrders
| pendingOrders
| cartTotal
| recentOrder
| storeName
|
|--------------------------------------------------------------------------
*/

const props = defineProps({

    cartCount: {
        type: Number,
        default: 0,
    },

    totalOrders: {
        type: Number,
        default: 0,
    },

    pendingOrders: {
        type: Number,
        default: 0,
    },

    cartTotal: {
        type: [Number, String],
        default: 0,
    },

    recentOrder: {
        type: Object,
        default: () => null,
    },

    storeName: {
        type: String,
        default: "IN STYLE",
    },

});


/*
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
*/

const sidebarOpen = ref(false);


/*
|--------------------------------------------------------------------------
| DROPDOWN
|--------------------------------------------------------------------------
*/

const openDropdown = ref(null);

const toggleDropdown = (label) => {

    if (openDropdown.value === label) {

        openDropdown.value = null;

    } else {

        openDropdown.value = label;

    }

};


const closeDropdown = () => {

    openDropdown.value = null;

};


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = ref("");


/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

const user = computed(() => {

    return page.props.auth?.user ?? {};

});


/*
|--------------------------------------------------------------------------
| USER NAME
|--------------------------------------------------------------------------
*/

const userName = computed(() => {

    return user.value?.name || "Customer";

});


/*
|--------------------------------------------------------------------------
| USER INITIAL
|--------------------------------------------------------------------------
*/

const userInitial = computed(() => {

    return userName.value
        .charAt(0)
        .toUpperCase();

});


/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/

const navigation = [
    {
        label: "Dashboard",
        href: "/client/user-dashboard",
        icon: DashboardOutlined,
    },

    {
        label: "My Cart",
        href: "/client/cart",
        icon: ShoppingCartOutlined,
    },

    {
        label: "My Orders",
        icon: ShoppingOutlined,
        children: [
            {
                label: "All Orders",
                href: "/client/orders",
            },
            {
                label: "Pending Orders",
                href: "/client/orders/pending",
            },
            {
                label: "Processing Orders",
                href: "/client/orders/processing",
            },
            {
                label: "Completed Orders",
                href: "/client/orders/completed",
            },
            {
                label: "Cancelled Orders",
                href: "/client/orders/cancelled",
            },
        ],
    },

    {
        label: "Payment History",
        href: "/client/payments",
        icon: CreditCardOutlined,
    },

    {
        label: "Continue Shopping",
        href: "/client/shop",
        icon: AppstoreOutlined,
    },

    {
        label: "My Profile",
        href: "/client/my-profile",
        icon: UserOutlined,
    },
];

/*
|--------------------------------------------------------------------------
| CURRENT URL
|--------------------------------------------------------------------------
*/

const currentUrl = computed(() => {

    return page.url ?? window.location.pathname;

});


/*
|--------------------------------------------------------------------------
| ACTIVE LINK
|--------------------------------------------------------------------------
*/

const isActive = (href) => {

    if (!href) {

        return false;

    }


    const current = currentUrl.value;


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    if (
        href === "/user/dashboard" ||
        href === "/dashboard"
    ) {

        return (
            current === "/user/dashboard" ||
            current === "/dashboard"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXACT URL
    |--------------------------------------------------------------------------
    */

    if (current === href) {

        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | CHILD ROUTES
    |--------------------------------------------------------------------------
    */

    return current.startsWith(`${href}/`);

};


/*
|--------------------------------------------------------------------------
| ACTIVE DROPDOWN
|--------------------------------------------------------------------------
*/

const isDropdownActive = (item) => {

    if (!item.children) {

        return isActive(item.href);

    }


    return item.children.some((child) => {

        return isActive(child.href);

    });

};


/*
|--------------------------------------------------------------------------
| MOBILE SIDEBAR
|--------------------------------------------------------------------------
*/

const closeSidebar = () => {

    sidebarOpen.value = false;

    openDropdown.value = null;

};


const openSidebar = () => {

    sidebarOpen.value = true;

};


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const handleSearch = () => {

    /*
    |--------------------------------------------------------------------------
    | Search can later be connected to:
    |
    | /fashion/search
    |
    |--------------------------------------------------------------------------
    */

    if (!search.value.trim()) {

        return;

    }

};


/*
|--------------------------------------------------------------------------
| CART TOTAL
|--------------------------------------------------------------------------
*/

const formattedCartTotal = computed(() => {
    const amount = globalCartTotal.value;

    return amount.toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
});

// const formattedCartTotal = computed(() => {

//     const amount = Number(props.cartTotal || 0);

//     return amount.toLocaleString("en-ZM", {
//         minimumFractionDigits: 2,
//         maximumFractionDigits: 2,
//     });

// });


/*
|--------------------------------------------------------------------------
| RECENT ORDER
|--------------------------------------------------------------------------
*/

const recentOrderNumber = computed(() => {

    if (!props.recentOrder) {

        return null;

    }

    return (
        props.recentOrder.order_number ??
        props.recentOrder.number ??
        props.recentOrder.id ??
        null
    );

});


const globalCart = computed(() => {
    return page.props.cart ?? {
        item_count: 0,
        total: 0,
    };
});

const globalCartCount = computed(() => {
    return Number(globalCart.value.item_count || 0);
});

const globalCartTotal = computed(() => {
    return Number(globalCart.value.total || 0);
});

/*
|--------------------------------------------------------------------------
| RECENT ORDER STATUS
|--------------------------------------------------------------------------
*/

const recentOrderStatus = computed(() => {

    if (!props.recentOrder) {

        return null;

    }

    return (
        props.recentOrder.status ??
        "Pending"
    );

});


/*
|--------------------------------------------------------------------------
| RECENT ORDER TOTAL
|--------------------------------------------------------------------------
*/

const recentOrderTotal = computed(() => {

    if (!props.recentOrder) {

        return "0.00";

    }


    const total =
        props.recentOrder.total ??
        props.recentOrder.total_amount ??
        0;


    return Number(total).toLocaleString("en-ZM", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

});

</script>


<template>

    <Head title="My Orders" />

    <div class="user-fashion-dashboard">


        <!-- =========================================================
             MOBILE OVERLAY
        ========================================================== -->

        <transition name="fade">

            <div v-if="sidebarOpen" class="sidebar-overlay" @click="closeSidebar"></div>

        </transition>


        <!-- =========================================================
             SIDEBAR
        ========================================================== -->

        <aside class="user-sidebar" :class="{
            'sidebar-mobile-open': sidebarOpen
        }">


            <!-- =====================================================
                 SIDEBAR BRAND
            ====================================================== -->

            <div class="sidebar-brand">

                <Link href="/user/dashboard" class="user-logo" @click="closeSidebar">

                    <img src="/assets/aaib.png" alt="Fashion Style" class="logo-image" />

                    <span class="logo-text">

                        {{ storeName }}

                        <small>
                            ASARA
                        </small>

                    </span>

                </Link>


                <!-- MOBILE CLOSE -->

                <button type="button" class="mobile-close" @click="closeSidebar" aria-label="Close menu">

                    <CloseOutlined />

                </button>

            </div>


            <!-- =====================================================
                 USER MINI PROFILE
            ====================================================== -->

            <div class="sidebar-user">

                <div class="user-avatar">

                    <span>
                        {{ userInitial }}
                    </span>

                </div>

                <div class="user-info">

                    <strong>
                        {{ userName }}
                    </strong>

                    <small>
                        Customer Account
                    </small>

                </div>

            </div>


            <!-- =====================================================
                 NAVIGATION
            ====================================================== -->

            <nav class="sidebar-navigation">

                <p class="nav-heading">
                    MY ACCOUNT
                </p>


                <!-- =================================================
                     NAVIGATION ITEMS
                ================================================== -->

                <div v-for="item in navigation" :key="item.label" class="nav-item">


                    <!-- =================================================
                         NORMAL LINK
                    ================================================== -->

                    <Link v-if="!item.children" :href="item.href" class="sidebar-link" :class="{
                        active: isActive(item.href)
                    }" @click="closeSidebar">

                        <component :is="item.icon" class="sidebar-icon" />

                        <span class="sidebar-link-text">

                            {{ item.label }}

                        </span>


                        <!-- CART BADGE -->

                        <span v-if="item.label === 'My Cart' && cartCount > 0" class="nav-badge">

                            <!-- {{ cartCount }} -->
                            {{ globalCartCount }}

                        </span>

                    </Link>


                    <!-- =================================================
                         DROPDOWN BUTTON
                    ================================================== -->

                    <button v-else type="button" class="sidebar-link sidebar-dropdown-trigger" :class="{
                        'dropdown-open':
                            openDropdown === item.label,

                        active:
                            isDropdownActive(item)
                    }" @click="toggleDropdown(item.label)">

                        <component :is="item.icon" class="sidebar-icon" />

                        <span class="sidebar-link-text">

                            {{ item.label }}

                        </span>


                        <ArrowDownOutlined class="dropdown-arrow" :class="{
                            rotated:
                                openDropdown === item.label
                        }" />

                    </button>


                    <!-- =================================================
                         DROPDOWN CHILDREN
                    ================================================== -->

                    <transition name="sidebar-dropdown">

                        <div v-if="
                            item.children &&
                            openDropdown === item.label
                        " class="sidebar-submenu">

                            <Link v-for="child in item.children" :key="child.label" :href="child.href"
                                class="sidebar-submenu-link" :class="{
                                    active:
                                        isActive(child.href)
                                }" @click="closeSidebar">

                                <span class="submenu-dot"></span>

                                <span>
                                    {{ child.label }}
                                </span>

                            </Link>

                        </div>

                    </transition>

                </div>

            </nav>


            <!-- =====================================================
                 SHOPPING CART SUMMARY
            ====================================================== -->

            <div class="sidebar-cart-card">

                <div class="cart-card-header">

                    <div class="cart-card-icon">

                        <ShoppingCartOutlined />

                    </div>

                    <div>

                        <strong>
                            Your Shopping Cart
                        </strong>

                        <small>
                            {{ cartCount }} item{{ cartCount === 1 ? '' : 's' }}
                        </small>

                    </div>

                </div>


                <div class="cart-card-total">

                    <span>
                        Total
                    </span>

                    <strong>
                        K{{ formattedCartTotal }}
                    </strong>

                </div>


                <Link href="/client/cart" class="view-cart-button" @click="closeSidebar">

                    View Cart

                    <RightOutlined />

                </Link>

            </div>

            <!-- =====================================================
                 SIDEBAR BOTTOM
            ====================================================== -->

            <div class="sidebar-bottom">

                <div class="store-status">

                    <div class="status-ring">

                        <CheckCircleOutlined />

                    </div>

                    <div>

                        <strong>
                            Store Active
                        </strong>

                        <small>
                            Online & accepting orders
                        </small>

                    </div>

                </div>

            </div>

        </aside>


        <!-- =========================================================
             MAIN CONTENT
        ========================================================== -->

        <main class="user-main">


            <!-- =====================================================
                 TOP HEADER
            ====================================================== -->

            <header class="dashboard-header">


                <!-- =================================================
                     MOBILE MENU
                ================================================== -->

                <button type="button" class="mobile-menu-button" @click="openSidebar" aria-label="Open menu">

                    <MenuOutlined />

                </button>


                <!-- =================================================
                     TITLE
                ================================================== -->

                <div class="dashboard-title">

                    <h1>

                        <slot name="title">

                            Dashboard

                        </slot>

                    </h1>

                    <p>

                        <slot name="subtitle">

                            Welcome back, {{ userName }}.

                        </slot>

                    </p>

                </div>


                <!-- =================================================
                     HEADER ACTIONS
                ================================================== -->

                <div class="header-actions">


                    <!-- =================================================
                         SEARCH
                    ================================================== -->

                    <div class="dashboard-search">

                        <SearchOutlined />

                        <input v-model="search" type="text" placeholder="Search products" @keyup.enter="handleSearch" />

                        <span class="search-shortcut">
                            ⌘
                        </span>

                        <span class="search-shortcut">
                            F
                        </span>

                    </div>


                    <!-- =================================================
                         CART BUTTON
                    ================================================== -->

                    <a-tooltip title="Shopping Cart" placement="bottom">

                        <Link href="/client/cart" class="header-cart-button">

                            <ShoppingCartOutlined />

                            <span v-if="cartCount > 0" class="header-cart-badge">
                                {{ cartCount }}
                            </span>

                        </Link>

                    </a-tooltip>


                    <!-- =================================================
                         PROFILE
                    ================================================== -->

                    <a-dropdown placement="bottomRight" trigger="click">

                        <a-tooltip title="Account" placement="bottom">

                            <button type="button" class="profile-button">

                                <span class="profile-initial">
                                    {{ userInitial }}
                                </span>

                            </button>

                        </a-tooltip>


                        <template #overlay>

                            <a-menu class="account-menu">


                                <!-- USER -->

                                <div class="account-user">

                                    <div class="account-avatar">

                                        {{ userInitial }}

                                    </div>

                                    <div>

                                        <strong>
                                            {{ userName }}
                                        </strong>

                                        <small>
                                            {{ user.email }}
                                        </small>

                                    </div>

                                </div>


                                <a-menu-divider />


                                <!-- PROFILE -->

                                <a-menu-item key="profile">

                                    <Link :href="route('profile.edit')" class="account-menu-link">

                                        <UserOutlined />

                                        <span>
                                            Profile
                                        </span>

                                    </Link>

                                </a-menu-item>


                                <!-- ORDERS -->

                                <a-menu-item key="orders">

                                    <Link href="/client/orders" class="account-menu-link">

                                        <FileTextOutlined />

                                        <span>
                                            My Orders
                                        </span>

                                    </Link>

                                </a-menu-item>


                                <!-- CART -->

                                <a-menu-item key="cart">

                                    <Link href="/client/cart" class="account-menu-link">

                                        <ShoppingCartOutlined />

                                        <span>
                                            My Cart
                                        </span>

                                    </Link>

                                </a-menu-item>


                                <a-menu-divider />


                                <!-- LOGOUT -->

                                <a-menu-item key="logout">

                                    <Link :href="route('logout')" method="post" as="button"
                                        class="account-menu-link logout-link">

                                        <LogoutOutlined />

                                        <span>
                                            Log Out
                                        </span>

                                    </Link>

                                </a-menu-item>

                            </a-menu>

                        </template>

                    </a-dropdown>

                </div>

            </header>


            <!-- =====================================================
                 DASHBOARD SUMMARY
            ====================================================== -->

            <!-- <div class="dashboard-summary">

              
                <Link
                   href="/client/orders"
                    class="summary-card"
                >

                    <div class="summary-icon orders-icon">

                        <ShoppingOutlined />

                    </div>

                    <div class="summary-info">

                        <span>
                            Total Orders
                        </span>

                        <strong>
                            {{ totalOrders }}
                        </strong>

                    </div>

                    <RightOutlined class="summary-arrow" />

                </Link>


                <Link
                    href="/client/orders/pending"
                    class="summary-card"
                >

                    <div class="summary-icon pending-icon">

                        <ClockCircleOutlined />

                    </div>

                    <div class="summary-info">

                        <span>
                            Pending Orders
                        </span>

                        <strong>
                            {{ pendingOrders }}
                        </strong>

                    </div>

                    <RightOutlined class="summary-arrow" />

                </Link>


                <Link
                    href="/client/cart"
                    class="summary-card"
                >

                    <div class="summary-icon cart-icon">

                        <ShoppingCartOutlined />

                    </div>

                    <div class="summary-info">

                        <span>
                            My Cart
                        </span>

                        <strong>
                            {{ cartCount }}
                        </strong>

                    </div>

                    <RightOutlined class="summary-arrow" />

                </Link>

            </div> -->


            <!-- =====================================================
                 MAIN PAGE CONTENT
            ====================================================== -->

            <div class="dashboard-content">

                <slot />

            </div>

        </main>

    </div>

</template>


<style scoped>
/*
|--------------------------------------------------------------------------
| MAIN DASHBOARD
|--------------------------------------------------------------------------
*/

.user-fashion-dashboard {

    min-height: 100vh;

    display: flex;

    background: #f7f8fa;

    color: #1f2937;

}


/*
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
*/

.user-sidebar {

    width: 280px;

    min-width: 280px;

    height: 100vh;

    position: fixed;

    top: 0;

    left: 0;

    z-index: 1000;

    display: flex;

    flex-direction: column;

    background: #ffffff;

    border-right: 1px solid #e5e7eb;

    transition:
        transform 0.3s ease,
        width 0.3s ease;

}


/*
|--------------------------------------------------------------------------
| BRAND
|--------------------------------------------------------------------------
*/

.sidebar-brand {

    height: 78px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 20px;

    border-bottom: 1px solid #f0f0f0;

}


.user-logo {

    display: flex;

    align-items: center;

    gap: 11px;

    text-decoration: none;

    color: #111827;

}


.logo-image {

    width: 42px;

    height: 42px;

    object-fit: contain;

    display: block;

}


.logo-text {

    display: flex;

    flex-direction: column;

    font-size: 15px;

    line-height: 1.1;

    font-weight: 800;

    letter-spacing: 1.2px;

}


.logo-text small {

    margin-top: 3px;

    font-size: 9px;

    letter-spacing: 3px;

    font-weight: 500;

    color: #9ca3af;

}


/*
|--------------------------------------------------------------------------
| MOBILE CLOSE
|--------------------------------------------------------------------------
*/

.mobile-close {

    display: none;

    width: 36px;

    height: 36px;

    align-items: center;

    justify-content: center;

    border: none;

    background: #f3f4f6;

    color: #374151;

    border-radius: 8px;

    cursor: pointer;

}


/*
|--------------------------------------------------------------------------
| USER PROFILE
|--------------------------------------------------------------------------
*/

.sidebar-user {

    display: flex;

    align-items: center;

    gap: 11px;

    padding: 16px;

    margin: 0 14px;

    border-bottom: 1px solid #f0f0f0;

}


.user-avatar {

    width: 40px;

    height: 40px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f0fdfa;

    color: #0f766e;

    font-size: 15px;

    font-weight: 700;

}


.user-info {

    min-width: 0;

}


.user-info strong {

    display: block;

    overflow: hidden;

    color: #111827;

    font-size: 13px;

    font-weight: 700;

    white-space: nowrap;

    text-overflow: ellipsis;

}


.user-info small {

    display: block;

    margin-top: 3px;

    color: #9ca3af;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/

.sidebar-navigation {

    flex: 1;

    overflow-y: auto;

    padding: 18px 14px;

}


.nav-heading {

    margin: 0 10px 10px;

    font-size: 10px;

    font-weight: 700;

    color: #9ca3af;

    letter-spacing: 1.5px;

}


/*
|--------------------------------------------------------------------------
| NAV ITEM
|--------------------------------------------------------------------------
*/

.nav-item {

    margin-bottom: 4px;

}


/*
|--------------------------------------------------------------------------
| SIDEBAR LINK
|--------------------------------------------------------------------------
*/

.sidebar-link {

    width: 100%;

    min-height: 44px;

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 0 12px;

    border: none;

    border-radius: 10px;

    background: transparent;

    color: #6b7280;

    font-size: 14px;

    font-weight: 500;

    text-decoration: none;

    text-align: left;

    cursor: pointer;

    transition:
        background 0.2s ease,
        color 0.2s ease;

}


.sidebar-link:hover {

    background: #f5f5f5;

    color: #111827;

}


.sidebar-link.active {

    background: #111827;

    color: #ffffff;

}


.sidebar-link.dropdown-open {

    background: #f5f5f5;

    color: #111827;

}


.sidebar-link.active.dropdown-open {

    background: #111827;

    color: #ffffff;

}


/*
|--------------------------------------------------------------------------
| ICON
|--------------------------------------------------------------------------
*/

.sidebar-icon {

    width: 18px;

    min-width: 18px;

    font-size: 17px;

}


.sidebar-link-text {

    flex: 1;

}


/*
|--------------------------------------------------------------------------
| NAV BADGE
|--------------------------------------------------------------------------
*/

.nav-badge {

    min-width: 21px;

    height: 21px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0 6px;

    border-radius: 20px;

    background: #111827;

    color: #ffffff;

    font-size: 10px;

    font-weight: 700;

}


.sidebar-link.active .nav-badge {

    background: #ffffff;

    color: #111827;

}


/*
|--------------------------------------------------------------------------
| DROPDOWN ARROW
|--------------------------------------------------------------------------
*/

.dropdown-arrow {

    margin-left: auto;

    font-size: 10px;

    transition: transform 0.25s ease;

}


.dropdown-arrow.rotated {

    transform: rotate(180deg);

}


/*
|--------------------------------------------------------------------------
| SUBMENU
|--------------------------------------------------------------------------
*/

.sidebar-submenu {

    margin: 4px 0 7px 22px;

    padding-left: 15px;

    border-left: 1px solid #e5e7eb;

}


.sidebar-submenu-link {

    min-height: 38px;

    display: flex;

    align-items: center;

    gap: 9px;

    padding: 0 10px;

    border-radius: 8px;

    color: #6b7280;

    font-size: 13px;

    text-decoration: none;

    transition:
        background 0.2s ease,
        color 0.2s ease;

}


.sidebar-submenu-link:hover {

    background: #f5f5f5;

    color: #111827;

}


.sidebar-submenu-link.active {

    background: #f3f4f6;

    color: #111827;

    font-weight: 600;

}


.submenu-dot {

    width: 5px;

    height: 5px;

    flex-shrink: 0;

    border-radius: 50%;

    background: #d1d5db;

}


.sidebar-submenu-link.active .submenu-dot {

    background: #111827;

}


/*
|--------------------------------------------------------------------------
| DROPDOWN TRANSITION
|--------------------------------------------------------------------------
*/

.sidebar-dropdown-enter-active,
.sidebar-dropdown-leave-active {

    transition:
        opacity 0.2s ease,
        transform 0.2s ease;

    transform-origin: top;

}


.sidebar-dropdown-enter-from,
.sidebar-dropdown-leave-to {

    opacity: 0;

    transform: translateY(-5px);

}


/*
|--------------------------------------------------------------------------
| CART CARD
|--------------------------------------------------------------------------
*/

.sidebar-cart-card {

    margin: 0 14px 10px;

    padding: 14px;

    border-radius: 13px;

    background: #f8fafc;

    border: 1px solid #eef0f2;

}


.cart-card-header {

    display: flex;

    align-items: center;

    gap: 10px;

}


.cart-card-icon {

    width: 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: #f0fdfa;

    color: #0f766e;

    font-size: 16px;

}


.cart-card-header strong {

    display: block;

    color: #111827;

    font-size: 11px;

    font-weight: 700;

}


.cart-card-header small {

    display: block;

    margin-top: 2px;

    color: #9ca3af;

    font-size: 9px;

}


.cart-card-total {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 13px;

    padding-top: 10px;

    border-top: 1px solid #e5e7eb;

}


.cart-card-total span {

    color: #9ca3af;

    font-size: 10px;

}


.cart-card-total strong {

    color: #111827;

    font-size: 13px;

}


.view-cart-button {

    height: 32px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    margin-top: 11px;

    border-radius: 8px;

    background: #111827;

    color: #ffffff;

    font-size: 10px;

    font-weight: 600;

    text-decoration: none;

    transition: all 0.2s ease;

}


.view-cart-button:hover {

    background: #374151;

    color: #ffffff;

}


/*
|--------------------------------------------------------------------------
| RECENT ORDER
|--------------------------------------------------------------------------
*/

.recent-order-card {

    margin: 0 14px 10px;

    padding: 13px;

    border: 1px solid #eef0f2;

    border-radius: 13px;

    background: #ffffff;

}


.recent-order-title {

    display: flex;

    align-items: center;

    justify-content: space-between;

    color: #6b7280;

    font-size: 10px;

    font-weight: 600;

}


.recent-order-title :deep(svg) {

    color: #9ca3af;

}


.recent-order-number {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 10px;

}


.recent-order-number strong {

    color: #111827;

    font-size: 12px;

}


.order-status {

    padding: 4px 7px;

    border-radius: 20px;

    background: #f3f4f6;

    color: #6b7280;

    font-size: 8px;

    font-weight: 700;

}


.order-status.pending {

    background: #fff7ed;

    color: #c2410c;

}


.order-status.processing {

    background: #eff6ff;

    color: #2563eb;

}


.order-status.completed {

    background: #f0fdf4;

    color: #16a34a;

}


.order-status.cancelled {

    background: #fef2f2;

    color: #dc2626;

}


.recent-order-details {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 9px;

}


.recent-order-details span {

    color: #9ca3af;

    font-size: 9px;

}


.recent-order-details strong {

    color: #111827;

    font-size: 11px;

}


.recent-order-link {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 10px;

    padding-top: 9px;

    border-top: 1px solid #f0f0f0;

    color: #0f766e;

    font-size: 9px;

    font-weight: 600;

    text-decoration: none;

}


.recent-order-link:hover {

    color: #115e59;

}


.no-order {

    display: flex;

    align-items: center;

    gap: 7px;

    margin-top: 12px;

    color: #9ca3af;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| SIDEBAR BOTTOM
|--------------------------------------------------------------------------
*/

.sidebar-bottom {

    padding: 14px;

    border-top: 1px solid #f0f0f0;

}


.store-status {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px;

    border-radius: 12px;

    background: #f8fafc;

}


.status-ring {

    width: 32px;

    height: 32px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #dcfce7;

    color: #16a34a;

    font-size: 15px;

}


.store-status strong {

    display: block;

    color: #111827;

    font-size: 11px;

}


.store-status small {

    display: block;

    margin-top: 2px;

    color: #9ca3af;

    font-size: 9px;

}


/*
|--------------------------------------------------------------------------
| MAIN
|--------------------------------------------------------------------------
*/

.user-main {

    flex: 1;

    min-width: 0;

    margin-left: 280px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.dashboard-header {

    min-height: 78px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 14px 28px;

    background: #ffffff;

    border-bottom: 1px solid #e5e7eb;

    position: sticky;

    top: 0;

    z-index: 900;

}


.dashboard-title {

    flex: 1;

    min-width: 0;

}


.dashboard-title h1 {

    margin: 0;

    color: #111827;

    font-size: 22px;

    font-weight: 700;

}


.dashboard-title p {

    margin: 4px 0 0;

    color: #9ca3af;

    font-size: 12px;

}


/*
|--------------------------------------------------------------------------
| HEADER ACTIONS
|--------------------------------------------------------------------------
*/

.header-actions {

    display: flex;

    align-items: center;

    gap: 10px;

}


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

.dashboard-search {

    width: 240px;

    height: 40px;

    display: flex;

    align-items: center;

    gap: 8px;

    padding: 0 10px;

    border: 1px solid #e5e7eb;

    border-radius: 9px;

    background: #ffffff;

    color: #9ca3af;

}


.dashboard-search input {

    flex: 1;

    min-width: 0;

    border: none;

    outline: none;

    background: transparent;

    color: #111827;

    font-size: 12px;

}


.dashboard-search input::placeholder {

    color: #9ca3af;

}


.search-shortcut {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 20px;

    height: 20px;

    padding: 0 5px;

    border: 1px solid #e5e7eb;

    border-radius: 4px;

    color: #9ca3af;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| HEADER CART
|--------------------------------------------------------------------------
*/

.header-cart-button {

    width: 40px;

    height: 40px;

    position: relative;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 1px solid #e5e7eb;

    border-radius: 9px;

    background: #ffffff;

    color: #6b7280;

    text-decoration: none;

    transition: all 0.2s ease;

}


.header-cart-button:hover {

    background: #f0fdfa;

    border-color: #ccfbf1;

    color: #0f766e;

}


.header-cart-badge {

    position: absolute;

    top: -5px;

    right: -5px;

    min-width: 18px;

    height: 18px;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 0 5px;

    border-radius: 20px;

    background: #111827;

    color: #ffffff;

    font-size: 9px;

    font-weight: 700;

}


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

.profile-button {

    width: 40px;

    height: 40px;

    border: none;

    border-radius: 50%;

    background: #f0fdfa;

    color: #0f766e;

    display: flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    transition: all 0.2s ease;

}


.profile-button:hover {

    background: #ccfbf1;

    color: #115e59;

    transform: translateY(-1px);

}


.profile-initial {

    font-size: 13px;

    font-weight: 700;

}


/*
|--------------------------------------------------------------------------
| ACCOUNT MENU
|--------------------------------------------------------------------------
*/

.account-menu {

    min-width: 220px;

    padding: 6px;

    border-radius: 10px;

}


.account-user {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px;

}


.account-avatar {

    width: 35px;

    height: 35px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f0fdfa;

    color: #0f766e;

    font-size: 12px;

    font-weight: 700;

}


.account-user strong {

    display: block;

    max-width: 135px;

    overflow: hidden;

    color: #111827;

    font-size: 12px;

    white-space: nowrap;

    text-overflow: ellipsis;

}


.account-user small {

    display: block;

    max-width: 135px;

    margin-top: 2px;

    overflow: hidden;

    color: #9ca3af;

    font-size: 9px;

    white-space: nowrap;

    text-overflow: ellipsis;

}


.account-menu-link {

    display: flex;

    align-items: center;

    gap: 10px;

    width: 100%;

    padding: 9px 12px;

    color: #374151;

    text-decoration: none;

    border-radius: 7px;

    transition: all 0.2s ease;

}


.account-menu-link:hover {

    background: #f0fdfa;

    color: #0f766e;

}


.logout-link {

    color: #dc2626;

}


.logout-link:hover {

    background: #fef2f2;

    color: #b91c1c;

}


/*
|--------------------------------------------------------------------------
| SUMMARY CARDS
|--------------------------------------------------------------------------
*/

.dashboard-summary {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 16px;

    padding: 24px 28px 0;

}


.summary-card {

    min-height: 88px;

    display: flex;

    align-items: center;

    gap: 13px;

    padding: 16px;

    border: 1px solid #e5e7eb;

    border-radius: 13px;

    background: #ffffff;

    text-decoration: none;

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;

}


.summary-card:hover {

    transform: translateY(-2px);

    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);

}


.summary-icon {

    width: 42px;

    height: 42px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 11px;

    font-size: 18px;

}


.orders-icon {

    background: #f3f4f6;

    color: #374151;

}


.pending-icon {

    background: #fff7ed;

    color: #ea580c;

}


.cart-icon {

    background: #f0fdfa;

    color: #0f766e;

}


.summary-info {

    min-width: 0;

    flex: 1;

}


.summary-info span {

    display: block;

    color: #9ca3af;

    font-size: 10px;

    font-weight: 500;

}


.summary-info strong {

    display: block;

    margin-top: 4px;

    color: #111827;

    font-size: 21px;

    font-weight: 700;

}


.summary-arrow {

    color: #d1d5db;

    font-size: 11px;

}


/*
|--------------------------------------------------------------------------
| MOBILE MENU BUTTON
|--------------------------------------------------------------------------
*/

.mobile-menu-button {

    display: none;

    width: 40px;

    height: 40px;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border: 1px solid #e5e7eb;

    border-radius: 9px;

    background: #ffffff;

    color: #374151;

    cursor: pointer;

}


/*
|--------------------------------------------------------------------------
| CONTENT
|--------------------------------------------------------------------------
*/

.dashboard-content {

    padding: 28px;

}


/*
|--------------------------------------------------------------------------
| MOBILE OVERLAY
|--------------------------------------------------------------------------
*/

.sidebar-overlay {

    position: fixed;

    inset: 0;

    z-index: 950;

    background: rgba(0, 0, 0, 0.45);

}


/*
|--------------------------------------------------------------------------
| FADE
|--------------------------------------------------------------------------
*/

.fade-enter-active,
.fade-leave-active {

    transition: opacity 0.25s ease;

}


.fade-enter-from,
.fade-leave-to {

    opacity: 0;

}


/*
|--------------------------------------------------------------------------
| TABLET
|--------------------------------------------------------------------------
*/

@media (max-width: 1100px) {

    .dashboard-search {

        width: 190px;

    }


    .dashboard-header {

        padding-left: 20px;

        padding-right: 20px;

    }


    .dashboard-summary {

        padding-left: 20px;

        padding-right: 20px;

    }


    .dashboard-content {

        padding: 20px;

    }

}


/*
|--------------------------------------------------------------------------
| TABLET / MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .user-sidebar {

        transform: translateX(-100%);

        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.08);

    }


    .user-sidebar.sidebar-mobile-open {

        transform: translateX(0);

    }


    .user-main {

        margin-left: 0;

        width: 100%;

    }


    .mobile-menu-button {

        display: flex;

    }


    .mobile-close {

        display: flex;

    }


    .dashboard-header {

        gap: 12px;

        padding: 12px 16px;

    }


    .dashboard-title h1 {

        font-size: 19px;

    }


    .dashboard-title p {

        display: none;

    }


    .dashboard-search {

        display: none;

    }


    .dashboard-summary {

        grid-template-columns: 1fr;

        gap: 10px;

        padding: 16px 16px 0;

    }


    .summary-card {

        min-height: 72px;

    }


    .dashboard-content {

        padding: 16px;

    }

}


/*
|--------------------------------------------------------------------------
| SMALL MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 500px) {

    .user-sidebar {

        width: 285px;

        min-width: 285px;

    }


    .dashboard-header {

        min-height: 68px;

    }


    .dashboard-title {

        text-align: left;

    }


    .dashboard-title h1 {

        font-size: 17px;

    }


    .header-cart-button {

        width: 36px;

        height: 36px;

    }


    .profile-button {

        width: 36px;

        height: 36px;

    }

}
</style>