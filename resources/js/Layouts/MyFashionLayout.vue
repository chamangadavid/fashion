<!-- resources/js/Layouts/MyFashionLayout.vue -->

<script setup>

import { ref, computed } from "vue";

import {
    Link,
    usePage,
} from "@inertiajs/vue3";

import {
    DashboardOutlined,
    ShoppingOutlined,
    ShoppingCartOutlined,
    UserOutlined,
    TeamOutlined,
    AppstoreOutlined,
    TagsOutlined,
    BarChartOutlined,
    SettingOutlined,
    FileTextOutlined,
    MenuOutlined,
    CloseOutlined,
    SearchOutlined,
    PlusOutlined,
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
| NAVIGATION
|--------------------------------------------------------------------------
|
| All MyFashion pages are kept under:
|
| resources/js/Pages/MyFashions/
|
|--------------------------------------------------------------------------
*/

const navigation = [

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    {
        label: "Dashboard",

        href: "/dashboard",

        icon: DashboardOutlined,
    },


    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    {
        label: "Orders",

        icon: ShoppingCartOutlined,

        children: [

            {
                label: "All Orders",

                href: "/fashion/orders",
            },

            {
                label: "Pending Orders",

                href: "/fashion/orders/pending",
            },

            {
                label: "Processing Orders",

                href: "/fashion/orders/processing",
            },

            {
                label: "Completed Orders",

                href: "/fashion/orders/completed",
            },

             {
                label: "Cancelled Orders",
                href: "/fashion/orders/cancelled",
            },

        ],
    },


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    {
        label: "Products",

        icon: AppstoreOutlined,

        children: [

            {
                label: "All Products",

                href: "/fashion/products",
            },

            {
                label: "Add Product",

                href: "/fashion/products/create",
            },

            {
                label: "Categories",

                href: "/fashion/products/categories",
            },

            {
                label: "Inventory",

                href: "/fashion/products/inventory",
            },

        ],
    },


    /*
    |--------------------------------------------------------------------------
    | COLLECTIONS
    |--------------------------------------------------------------------------
    */

    {
        label: "Collections",

        icon: TagsOutlined,

        children: [

            {
                label: "All Collections",

                href: "/fashion/collections",
            },

            {
                label: "Create Collection",

                href: "/fashion/collections/create",
            },

            {
                label: "Featured Collections",

                href: "/fashion/collections/featured",
            },

        ],
    },


    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS
    |--------------------------------------------------------------------------
    */

    {
        label: "Customers",

        icon: TeamOutlined,

        children: [

            {
                label: "All Customers",

                href: "/fashion/customers",
            },

            {
                label: "Customer Groups",

                href: "/fashion/customers/groups",
            },

            {
                label: "VIP Customers",

                href: "/fashion/customers/vip",
            },

        ],
    },


    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */

    {
        label: "Reports",

        icon: BarChartOutlined,

        children: [

            {
                label: "Sales Reports",

                href: "/fashion/reports/sales",
            },

            {
                label: "Product Reports",

                href: "/fashion/reports/products",
            },

            {
                label: "Customer Reports",

                href: "/fashion/reports/customers",
            },

            {
                label: "Revenue Reports",

                href: "/fashion/reports/revenue",
            },

        ],
    },


    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    {
        label: "Users",

        icon: UserOutlined,

        children: [

            {
                label: "All Users",

                href: "/fashion/users",
            },

            {
                label: "Add User",

                href: "/fashion/users/create",
            },

            {
                label: "Roles & Permissions",

                href: "/fashion/users/roles",
            },

        ],
    },


    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */

    {
        label: "Settings",

        icon: SettingOutlined,

        children: [

            {
                label: "General Settings",

                href: "/fashion/settings",
            },

            {
                label: "Store Settings",

                href: "/fashion/settings/store",
            },

            {
                label: "Payment Settings",

                href: "/fashion/settings/payments",
            },

            {
                label: "Shipping Settings",

                href: "/fashion/settings/shipping",
            },

        ],
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
    | Dashboard
    |--------------------------------------------------------------------------
    */

    if (href === "/dashboard") {

        return current === "/dashboard";

    }


    /*
    |--------------------------------------------------------------------------
    | Exact URL
    |--------------------------------------------------------------------------
    */

    if (current === href) {

        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | Child routes
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


/*
|--------------------------------------------------------------------------
| OPEN MOBILE SIDEBAR
|--------------------------------------------------------------------------
*/

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
    | Search is intentionally local for now.
    |
    | Later we can connect this to:
    |
    | /fashion/search
    |
    | or search orders/products/customers dynamically.
    |--------------------------------------------------------------------------
    */

};


/*
|--------------------------------------------------------------------------
| NEW ORDER
|--------------------------------------------------------------------------
*/

const createNewOrder = () => {

    closeSidebar();

};

</script>


<template>

    <div class="admin-dashboard">


        <!-- =========================================================
             MOBILE OVERLAY
        ========================================================== -->

        <transition name="fade">

            <div
                v-if="sidebarOpen"
                class="sidebar-overlay"
                @click="closeSidebar"
            ></div>

        </transition>



        <!-- =========================================================
             SIDEBAR
        ========================================================== -->

        <aside
            class="admin-sidebar"
            :class="{
                'sidebar-mobile-open': sidebarOpen
            }"
        >


            <!-- =====================================================
                 SIDEBAR BRAND
            ====================================================== -->

            <div class="sidebar-brand">


                <!-- LOGO -->

                <Link
                    href="/dashboard"
                    class="admin-logo"
                    @click="closeSidebar"
                >

                    <span class="logo-mark">

                        FS

                    </span>


                    <span class="logo-text">

                        FASHION

                        <small>

                            STYLES

                        </small>

                    </span>

                </Link>



                <!-- MOBILE CLOSE -->

                <button
                    type="button"
                    class="mobile-close"
                    @click="closeSidebar"
                    aria-label="Close menu"
                >

                    <CloseOutlined />

                </button>

            </div>



            <!-- =====================================================
                 NAVIGATION
            ====================================================== -->

            <nav class="sidebar-navigation">


                <p class="nav-heading">

                    MAIN MENU

                </p>



                <!-- =================================================
                     NAVIGATION ITEMS
                ================================================== -->

                <div
                    v-for="item in navigation"
                    :key="item.label"
                    class="nav-item"
                >


                    <!-- =================================================
                         NORMAL LINK
                    ================================================== -->

                    <Link
                        v-if="!item.children"
                        :href="item.href"
                        class="sidebar-link"
                        :class="{
                            active: isActive(item.href)
                        }"
                        @click="closeSidebar"
                    >

                        <component
                            :is="item.icon"
                            class="sidebar-icon"
                        />


                        <span>

                            {{ item.label }}

                        </span>

                    </Link>



                    <!-- =================================================
                         DROPDOWN BUTTON
                    ================================================== -->

                    <button
                        v-else
                        type="button"
                        class="sidebar-link sidebar-dropdown-trigger"
                        :class="{
                            'dropdown-open':
                                openDropdown === item.label,

                            active:
                                isDropdownActive(item)
                        }"
                        @click="toggleDropdown(item.label)"
                    >


                        <component
                            :is="item.icon"
                            class="sidebar-icon"
                        />


                        <span class="sidebar-link-text">

                            {{ item.label }}

                        </span>


                        <ArrowDownOutlined
                            class="dropdown-arrow"
                            :class="{
                                rotated:
                                    openDropdown === item.label
                            }"
                        />

                    </button>



                    <!-- =================================================
                         DROPDOWN CHILDREN
                    ================================================== -->

                    <transition name="sidebar-dropdown">

                        <div
                            v-if="
                                item.children &&
                                openDropdown === item.label
                            "
                            class="sidebar-submenu"
                        >

                            <Link
                                v-for="child in item.children"
                                :key="child.label"
                                :href="child.href"
                                class="sidebar-submenu-link"
                                :class="{
                                    active:
                                        isActive(child.href)
                                }"
                                @click="closeSidebar"
                            >

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

        <main class="admin-main">


            <!-- =====================================================
                 TOP HEADER
            ====================================================== -->

            <header class="dashboard-header">


                <!-- =================================================
                     MOBILE MENU
                ================================================== -->

                <button
                    type="button"
                    class="mobile-menu-button"
                    @click="openSidebar"
                    aria-label="Open menu"
                >

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

                            Manage your fashion store.

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


                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search anything"
                            @keyup.enter="handleSearch"
                        />


                        <span class="search-shortcut">

                            ⌘

                        </span>


                        <span class="search-shortcut">

                            F

                        </span>

                    </div>



                    <!-- =================================================
                         NEW ORDER
                    ================================================== -->

                    <a-tooltip
                        title="Create New Order"
                        placement="bottom"
                    >

                        <Link
                            href="/fashion/orders/create"
                            class="new-action-button"
                            @click="createNewOrder"
                        >

                            <PlusOutlined />


                            <span>

                                New Order

                            </span>

                        </Link>

                    </a-tooltip>



                    <!-- =================================================
                         PROFILE
                    ================================================== -->

                    <a-tooltip
                        title="Account"
                        placement="bottom"
                    >

                        <Link
                            href="/profile"
                            class="profile-button"
                        >

                            <UserOutlined />

                        </Link>

                    </a-tooltip>


                </div>

            </header>



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

.admin-dashboard {

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

.admin-sidebar {

    width: 260px;

    min-width: 260px;

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


.admin-logo {

    display: flex;

    align-items: center;

    gap: 11px;

    text-decoration: none;

    color: #111827;

}


.logo-mark {

    width: 42px;

    height: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background: #111827;

    color: #ffffff;

    font-size: 14px;

    font-weight: 800;

    letter-spacing: 1px;

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
| NAVIGATION
|--------------------------------------------------------------------------
*/

.sidebar-navigation {

    flex: 1;

    overflow-y: auto;

    padding: 22px 14px;

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
| SIDEBAR DROPDOWN TRANSITION
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

    padding: 12px;

    border-radius: 12px;

    background: #f8fafc;

}


.status-ring {

    width: 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #dcfce7;

    color: #16a34a;

    font-size: 16px;

}


.store-status strong {

    display: block;

    color: #111827;

    font-size: 12px;

}


.store-status small {

    display: block;

    margin-top: 2px;

    color: #9ca3af;

    font-size: 10px;

}


/*
|--------------------------------------------------------------------------
| MAIN
|--------------------------------------------------------------------------
*/

.admin-main {

    flex: 1;

    min-width: 0;

    margin-left: 260px;

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
| NEW ORDER BUTTON
|--------------------------------------------------------------------------
*/

.new-action-button {

    height: 40px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    padding: 0 15px;

    border-radius: 9px;

    background: #111827;

    color: #ffffff;

    font-size: 12px;

    font-weight: 600;

    text-decoration: none;

    transition:
        background 0.2s ease,
        transform 0.2s ease;

}


.new-action-button:hover {

    background: #374151;

    color: #ffffff;

    transform: translateY(-1px);

}


/*
|--------------------------------------------------------------------------
| PROFILE BUTTON
|--------------------------------------------------------------------------
*/

.profile-button {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 1px solid #e5e7eb;

    border-radius: 9px;

    background: #ffffff;

    color: #6b7280;

    text-decoration: none;

}


.profile-button:hover {

    color: #111827;

    background: #f9fafb;

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
| FADE TRANSITION
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
| RESPONSIVE - TABLET
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

    .dashboard-content {

        padding: 20px;

    }

}


/*
|--------------------------------------------------------------------------
| RESPONSIVE - MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .admin-sidebar {

        transform: translateX(-100%);

        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.08);

    }


    .admin-sidebar.sidebar-mobile-open {

        transform: translateX(0);

    }


    .admin-main {

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


    .new-action-button {

        width: 40px;

        padding: 0;

    }


    .new-action-button span {

        display: none;

    }


    .profile-button {

        display: none;

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

    .admin-sidebar {

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

}

</style>