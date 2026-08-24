<!-- resources\js\Components\AdminBoard.vue -->
<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";

import {
    DashboardOutlined,
    ShoppingOutlined,
    ShoppingCartOutlined,
    UserOutlined,
    LogoutOutlined,
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
    MoreOutlined,
    DownloadOutlined,
    EyeOutlined,
    ArrowUpOutlined,
    ArrowDownOutlined,
    DollarOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
} from "@ant-design/icons-vue";


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({})
    },

    stats: {
        type: Object,
        default: () => ({
            total_orders: 0,
            pending_orders: 0,
            completed_orders: 0,
            total_revenue: 0,
            total_customers: 0,
            products: 0,
        })
    },

    charts: {
        type: Object,
        default: () => ({})
    },

    orders: {
        type: Array,
        default: () => []
    },

    reports: {
        type: Array,
        default: () => []
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

const filteredOrders = computed(() => {
    if (!search.value.trim()) {
        return props.orders;
    }

    const term = search.value.toLowerCase();

    return props.orders.filter((order) => {
        return (
            String(order.id ?? "")
                .toLowerCase()
                .includes(term) ||

            String(order.customer_name ?? "")
                .toLowerCase()
                .includes(term) ||

            String(order.status ?? "")
                .toLowerCase()
                .includes(term)
        );
    });
});


/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/

const navigation = [
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

            // {
            //     label: "Add User",
            //     href: "/fashion/users/create",
            // },

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
| RECENT ORDERS
|--------------------------------------------------------------------------
*/

const demoOrders = [
    {
        id: "#ORD-1048",
        customer_name: "Grace Mwila",
        product: "Elegant Evening Dress",
        amount: 850,
        status: "Pending",
        date: "10 minutes ago",
    },

    {
        id: "#ORD-1047",
        customer_name: "Mary Banda",
        product: "Silk Button-Up Blouse",
        amount: 450,
        status: "Processing",
        date: "1 hour ago",
    },

    {
        id: "#ORD-1046",
        customer_name: "Linda Phiri",
        product: "Premium Denim Jacket",
        amount: 620,
        status: "Completed",
        date: "2 hours ago",
    },

    {
        id: "#ORD-1045",
        customer_name: "Esther Chanda",
        product: "Custom Made Gown",
        amount: 1250,
        status: "Completed",
        date: "3 hours ago",
    },
];


const displayedOrders = computed(() => {
    const source =
        props.orders?.length
            ? props.orders
            : demoOrders;

    if (!search.value.trim()) {
        return source;
    }

    const term = search.value.toLowerCase();

    return source.filter((order) => {
        return (
            String(order.id ?? "")
                .toLowerCase()
                .includes(term) ||

            String(order.customer_name ?? "")
                .toLowerCase()
                .includes(term) ||

            String(order.product ?? "")
                .toLowerCase()
                .includes(term)
        );
    });
});


/*
|--------------------------------------------------------------------------
| THREAD FEED
|--------------------------------------------------------------------------
*/

const threadFeed = [
    {
        icon: ShoppingCartOutlined,
        title: "New order received",
        description: "Order #ORD-1048 was placed",
        time: "10 minutes ago",
    },

    {
        icon: UserOutlined,
        title: "New customer registered",
        description: "Grace Mwila created an account",
        time: "1 hour ago",
    },

    {
        icon: AppstoreOutlined,
        title: "New product added",
        description: "Elegant Evening Dress added",
        time: "1 hour ago",
    },

    {
        icon: ShoppingOutlined,
        title: "Order completed",
        description: "Order #ORD-1046 completed",
        time: "2 hours ago",
    },

    {
        icon: DollarOutlined,
        title: "Payment received",
        description: "Payment for #ORD-1045 confirmed",
        time: "3 hours ago",
    },
];


/*
|--------------------------------------------------------------------------
| REPORTS
|--------------------------------------------------------------------------
*/

const demoReports = [
    {
        type: "PDF",
        title: "Monthly Sales Report",
        date: "Generated on Aug 20, 2026",
    },

    {
        type: "CSV",
        title: "Customer Report",
        date: "Generated on Aug 20, 2026",
    },

    {
        type: "PDF",
        title: "Product Performance Report",
        date: "Generated on Aug 19, 2026",
    },

    {
        type: "XLS",
        title: "Inventory Summary",
        date: "Generated on Aug 19, 2026",
    },

    {
        type: "PDF",
        title: "Revenue Analysis Report",
        date: "Generated on Aug 18, 2026",
    },

    {
        type: "CSV",
        title: "Order Analysis Report",
        date: "Generated on Aug 18, 2026",
    },
];


const displayedReports = computed(() => {
    return props.reports?.length
        ? props.reports
        : demoReports;
});


/*
|--------------------------------------------------------------------------
| STATS
|--------------------------------------------------------------------------
*/

const totalOrders = computed(() => {
    return Number(
        props.stats?.total_orders ??
        demoOrders.length
    );
});


const pendingOrders = computed(() => {
    return Number(
        props.stats?.pending_orders ?? 12
    );
});


const completedOrders = computed(() => {
    return Number(
        props.stats?.completed_orders ?? 86
    );
});


const revenue = computed(() => {
    return Number(
        props.stats?.total_revenue ?? 24580
    );
});


const customers = computed(() => {
    return Number(
        props.stats?.total_customers ?? 348
    );
});


const products = computed(() => {
    return Number(
        props.stats?.products ?? 126
    );
});


/*
|--------------------------------------------------------------------------
| FORMAT MONEY
|--------------------------------------------------------------------------
*/

const formatMoney = (amount) => {
    return new Intl.NumberFormat("en-ZM", {
        style: "currency",
        currency: "ZMW",
        minimumFractionDigits: 2,
    }).format(amount);
};


/*
|--------------------------------------------------------------------------
| CLOSE MOBILE SIDEBAR
|--------------------------------------------------------------------------
*/

const closeSidebar = () => {
    sidebarOpen.value = false;
    openDropdown.value = null;
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
                 LOGO
            ====================================================== -->

            <div class="sidebar-brand">

                <Link
                    href="/"
                    class="admin-logo"
                >

                   <img src="/assets/aaib.png" alt="Fashion Style" class="logo-image" />

                    <span class="logo-text">

                        IN STYLE

                        <small>
                           ASARA
                        </small>

                    </span>

                </Link>


                <!-- MOBILE CLOSE -->

                <button
                    class="mobile-close"
                    @click="closeSidebar"
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


                <!-- NAVIGATION ITEMS -->

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
                            active: item.label === 'Dashboard'
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
                                openDropdown === item.label
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
                                @click="closeSidebar"
                            >

                                {{ child.label }}

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


                <!-- MOBILE MENU -->

                <button
                    class="mobile-menu-button"
                    @click="sidebarOpen = true"
                >

                    <MenuOutlined />

                </button>



                <!-- TITLE -->

                <div class="dashboard-title">

                    <h1>
                        Dashboard
                    </h1>

                    <p>
                        Here's what's happening with your store today.
                    </p>

                </div>



                <!-- HEADER ACTIONS -->

                <div class="header-actions">


                    <!-- SEARCH -->

                    <div class="dashboard-search">

                        <SearchOutlined />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search anything"
                        />

                        <span class="search-shortcut">
                            ⌘
                        </span>

                        <span class="search-shortcut">
                            F
                        </span>

                    </div>



                    <!-- NEW ORDER -->

                    <a-tooltip
                        title="Create New Order"
                        placement="bottom"
                    >

                        <Link
                            href="/orders/create"
                            class="new-action-button"
                        >

                            <PlusOutlined />

                            <span>
                                New Order
                            </span>

                        </Link>

                    </a-tooltip>



                    <!-- USER -->

                    <!-- USER ACCOUNT DROPDOWN -->
<a-dropdown
    placement="bottomRight"
    trigger="click"
>
    <a-tooltip
        title="Account"
        placement="bottom"
    >
        <button
            type="button"
            class="profile-button"
        >
            <UserOutlined />
        </button>
    </a-tooltip>

    <template #overlay>
        <a-menu class="account-menu">

            <!-- PROFILE -->
            <a-menu-item key="profile">
                <Link
                    :href="route('profile.edit')"
                    class="account-menu-link"
                >
                    <UserOutlined />
                    <span>Profile</span>
                </Link>
            </a-menu-item>

            <!-- LOGOUT -->
            <a-menu-item key="logout">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="account-menu-link logout-link"
                >
                    <LogoutOutlined />
                    <span>Log Out</span>
                </Link>
            </a-menu-item>

        </a-menu>
    </template>
</a-dropdown>

                    <!-- <a-tooltip
                        title="Account"
                        placement="bottom"
                    >

                        <Link
                            href="/profile"
                            class="profile-button"
                        >

                            <UserOutlined />

                        </Link>

                    </a-tooltip> -->

                </div>

            </header>



            <!-- =====================================================
                 CONTENT
            ====================================================== -->

            <div class="dashboard-content">


                <!-- =================================================
                     STATISTICS
                ================================================== -->

                <section class="stats-grid">


                    <!-- ORDERS -->

                    <div class="stat-card">

                        <div class="stat-card-top">

                            <div class="stat-icon orders">

                                <ShoppingCartOutlined />

                            </div>

                            <span class="stat-growth positive">

                                <ArrowUpOutlined />

                                12.5%

                            </span>

                        </div>

                        <p>
                            Total Orders
                        </p>

                        <h2>
                            {{ totalOrders }}
                        </h2>

                    </div>



                    <!-- REVENUE -->

                    <div class="stat-card">

                        <div class="stat-card-top">

                            <div class="stat-icon revenue">

                                <DollarOutlined />

                            </div>

                            <span class="stat-growth positive">

                                <ArrowUpOutlined />

                                8.2%

                            </span>

                        </div>

                        <p>
                            Total Revenue
                        </p>

                        <h2>
                            {{ formatMoney(revenue) }}
                        </h2>

                    </div>



                    <!-- CUSTOMERS -->

                    <div class="stat-card">

                        <div class="stat-card-top">

                            <div class="stat-icon customers">

                                <TeamOutlined />

                            </div>

                            <span class="stat-growth positive">

                                <ArrowUpOutlined />

                                5.8%

                            </span>

                        </div>

                        <p>
                            Customers
                        </p>

                        <h2>
                            {{ customers }}
                        </h2>

                    </div>



                    <!-- PRODUCTS -->

                    <div class="stat-card">

                        <div class="stat-card-top">

                            <div class="stat-icon products">

                                <AppstoreOutlined />

                            </div>

                            <span class="stat-growth">
                                {{ products }}
                            </span>

                        </div>

                        <p>
                            Products
                        </p>

                        <h2>
                            {{ products }}
                        </h2>

                    </div>

                </section>



                <!-- =================================================
                     ORDERS + ACTIVITY
                ================================================== -->

                <section class="dashboard-two-column">


                    <!-- RECENT ORDERS -->

                    <div class="dashboard-panel orders-panel">

                        <div class="panel-header">

                            <div>

                                <h3>
                                    Recent Orders
                                </h3>

                                <p>
                                    Latest activity from your store
                                </p>

                            </div>

                            <a-tooltip
                                title="More options"
                                placement="bottom"
                            >

                                <button class="panel-menu">

                                    <MoreOutlined />

                                </button>

                            </a-tooltip>

                        </div>


                        <!-- ORDERS -->

                        <div class="orders-grid">

                            <div
                                v-for="order in displayedOrders.slice(0, 4)"
                                :key="order.id"
                                class="order-item"
                            >

                                <div class="order-product-icon">

                                    <ShoppingOutlined />

                                </div>


                                <div class="order-information">

                                    <strong>
                                        {{ order.id }}
                                    </strong>

                                    <span>
                                        {{ order.customer_name }}
                                    </span>

                                    <small>
                                        {{ order.product }}
                                    </small>

                                </div>


                                <div class="order-status">

                                    <span
                                        class="status-badge"
                                        :class="
                                            String(order.status)
                                                .toLowerCase()
                                                .replace(' ', '-')
                                        "
                                    >
                                        {{ order.status }}
                                    </span>

                                    <strong>
                                        {{ formatMoney(order.amount) }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        <!-- VIEW ALL -->

                        <div class="panel-footer">

                            <Link href="/orders">
                                View all orders
                            </Link>

                        </div>

                    </div>



                    <!-- THREAD FEED -->

                    <div class="dashboard-panel feed-panel">

                        <div class="panel-header">

                            <div>

                                <h3>
                                    Thread Feed
                                </h3>

                                <p>
                                    Latest store activity
                                </p>

                            </div>

                            <span class="feed-count">
                                {{ threadFeed.length }}
                            </span>

                        </div>


                        <div class="feed-list">

                            <div
                                v-for="(feed, index) in threadFeed"
                                :key="index"
                                class="feed-item"
                            >

                                <div class="feed-icon">

                                    <component
                                        :is="feed.icon"
                                    />

                                </div>


                                <div class="feed-content">

                                    <strong>
                                        {{ feed.title }}
                                    </strong>

                                    <span>
                                        {{ feed.description }}
                                    </span>

                                    <small>
                                        {{ feed.time }}
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =================================================
                     REPORTS
                ================================================== -->

                <section class="dashboard-panel reports-panel">

                    <div class="panel-header">

                        <div>

                            <h3>
                                Recent Reports
                            </h3>

                            <p>
                                Download and view your latest reports
                            </p>

                        </div>


                        <a-tooltip
                            title="More options"
                            placement="bottom"
                        >

                            <button class="panel-menu">

                                <MoreOutlined />

                            </button>

                        </a-tooltip>

                    </div>


                    <!-- REPORT GRID -->

                    <div class="reports-grid">

                        <div
                            v-for="(report, index) in displayedReports"
                            :key="index"
                            class="report-item"
                        >

                            <div
                                class="report-file"
                                :class="report.type.toLowerCase()"
                            >

                                <FileTextOutlined />

                                <span>
                                    {{ report.type }}
                                </span>

                            </div>


                            <div class="report-information">

                                <strong>
                                    {{ report.title }}
                                </strong>

                                <small>
                                    {{ report.date }}
                                </small>

                            </div>


                            <div class="report-actions">

                                <a-tooltip
                                    title="View Report"
                                    placement="top"
                                >

                                    <button>

                                        <EyeOutlined />

                                    </button>

                                </a-tooltip>


                                <a-tooltip
                                    title="Download Report"
                                    placement="top"
                                >

                                    <button>

                                        <DownloadOutlined />

                                    </button>

                                </a-tooltip>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =================================================
                     BOTTOM PROGRESS
                ================================================== -->

                <section class="bottom-section">


                    <div class="progress-card">

                        <div class="progress-circle">

                            <span>
                                86%
                            </span>

                        </div>


                        <div class="progress-information">

                            <strong>
                                Store Performance
                            </strong>

                            <span>
                                Excellent performance this month
                            </span>

                        </div>


                        <div class="progress-bar">

                            <div
                                class="progress-value"
                                style="width: 86%"
                            ></div>

                        </div>

                    </div>


                    <div class="quick-summary">

                        <div>

                            <ClockCircleOutlined />

                            <span>
                                {{ pendingOrders }} Pending
                            </span>

                        </div>


                        <div>

                            <CheckCircleOutlined />

                            <span>
                                {{ completedOrders }} Completed
                            </span>

                        </div>

                    </div>

                </section>

            </div>

        </main>

    </div>

</template>


<style scoped>

/* =========================================================
   ROOT
========================================================= */

.admin-dashboard {
    min-height: 100vh;
    display: flex;
    background: #f5f5f3;
    color: #222;

    font-family:
        Inter,
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        sans-serif;
}

/* =========================================================
   USER ACCOUNT
========================================================= */


.account-menu {
    min-width: 180px;
    padding: 6px;
    border-radius: 10px;
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

.profile-button {
    width: 38px;
    height: 38px;
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


/* =========================================================
   SIDEBAR
========================================================= */

.admin-sidebar {
    width: 235px;
    min-width: 235px;
    min-height: 100vh;

    background: #ffffff;
    border-right: 1px solid #e8e8e8;

    display: flex;
    flex-direction: column;

    position: sticky;
    top: 0;

    height: 100vh;
    z-index: 1000;
}


/* =========================================================
   BRAND
========================================================= */

.sidebar-brand {
    height: 76px;
    padding: 0 20px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    border-bottom: 1px solid #f0f0f0;
}


.admin-logo {
    display: flex;
    align-items: center;
    gap: 10px;

    text-decoration: none;
    color: #064f4c;
}

.logo-image {
    width: 42px;
    height: 42px;
    object-fit: contain;
    display: block;
}


.logo-text {
    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 15px;
    font-weight: 700;

    letter-spacing: 1px;
    line-height: 1;
}


.logo-text small {
    display: block;

    font-family: Arial, sans-serif;

    font-size: 7px;
    letter-spacing: 3px;

    margin-top: 4px;
}


.mobile-close {
    display: none;

    border: 0;
    background: transparent;

    font-size: 20px;
    cursor: pointer;
}


/* =========================================================
   NAVIGATION
========================================================= */

.sidebar-navigation {
    padding: 25px 12px;
    flex: 1;

    overflow-y: auto;
}


.nav-heading {
    font-size: 9px;
    letter-spacing: 1.5px;

    color: #999;
    font-weight: 700;

    padding: 0 12px;
    margin: 0 0 10px;
}


.nav-item {
    margin-bottom: 4px;
}


.sidebar-link {
    display: flex;
    align-items: center;

    gap: 13px;

    height: 43px;
    width: 100%;

    padding: 0 13px;
    margin-bottom: 0;

    border-radius: 8px;

    text-decoration: none;

    color: #777;

    font-size: 12px;

    transition:
        background .2s ease,
        color .2s ease;

    box-sizing: border-box;
}


.sidebar-link:hover {
    background: #f2f7f6;
    color: #064f4c;
}


.sidebar-link.active {
    background: #f0f2f1;
    color: #111;

    font-weight: 600;
}


.sidebar-icon {
    font-size: 17px;
    flex: 0 0 17px;
}


/* =========================================================
   DROPDOWN TRIGGER
========================================================= */

.sidebar-dropdown-trigger {
    border: 0;
    background: transparent;

    cursor: pointer;

    font-family: inherit;
    text-align: left;
}


.sidebar-link-text {
    flex: 1;
}


.dropdown-arrow {
    margin-left: auto;

    font-size: 10px;

    transition:
        transform .2s ease;
}


.dropdown-arrow.rotated {
    transform: rotate(180deg);
}


.sidebar-dropdown-trigger.dropdown-open {
    background: #f2f7f6;
    color: #064f4c;
}


/* =========================================================
   SUBMENU
========================================================= */

.sidebar-submenu {
    margin: 3px 0 7px;

    padding-left: 30px;

    overflow: hidden;
}


.sidebar-submenu-link {
    display: flex;
    align-items: center;

    min-height: 34px;

    padding: 0 10px;

    margin-bottom: 2px;

    color: #999;

    text-decoration: none;

    font-size: 11px;

    border-radius: 6px;

    transition:
        background .2s ease,
        color .2s ease;
}


.sidebar-submenu-link:hover {
    background: #f2f7f6;
    color: #064f4c;
}


.sidebar-submenu-link::before {
    content: "";

    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: #d5d5d5;

    margin-right: 9px;

    transition:
        background .2s ease;
}


.sidebar-submenu-link:hover::before {
    background: #064f4c;
}


/* =========================================================
   DROPDOWN ANIMATION
========================================================= */

.sidebar-dropdown-enter-active,
.sidebar-dropdown-leave-active {
    transition:
        opacity .2s ease,
        transform .2s ease;
}


.sidebar-dropdown-enter-from,
.sidebar-dropdown-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}


/* =========================================================
   SIDEBAR BOTTOM
========================================================= */

.sidebar-bottom {
    padding: 15px;
}


.store-status {
    background: #f5f7f6;

    border-radius: 10px;

    padding: 12px;

    display: flex;
    align-items: center;

    gap: 10px;
}


.status-ring {
    width: 31px;
    height: 31px;

    border-radius: 50%;

    background: #fff;
    color: #08766f;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 15px;
}


.store-status strong {
    display: block;

    font-size: 10px;
}


.store-status small {
    display: block;

    font-size: 8px;
    color: #999;

    margin-top: 3px;
}


/* =========================================================
   MAIN
========================================================= */

.admin-main {
    flex: 1;
    min-width: 0;
}


/* =========================================================
   HEADER
========================================================= */

.dashboard-header {
    min-height: 76px;

    background: #fff;
    border-bottom: 1px solid #e8e8e8;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 28px;

    gap: 20px;
}


.dashboard-title h1 {
    margin: 0;

    font-size: 20px;
    font-weight: 600;

    letter-spacing: -.3px;
}


.dashboard-title p {
    margin: 4px 0 0;

    font-size: 10px;
    color: #999;
}


.header-actions {
    display: flex;
    align-items: center;

    gap: 10px;
}


/* =========================================================
   SEARCH
========================================================= */

.dashboard-search {
    width: 220px;
    height: 36px;

    border: 1px solid #e5e5e5;
    border-radius: 7px;

    display: flex;
    align-items: center;

    gap: 7px;

    padding: 0 9px;

    color: #999;
    background: #fafafa;
}


.dashboard-search input {
    flex: 1;
    min-width: 0;

    border: 0;
    outline: 0;

    background: transparent;

    font-size: 10px;
}


.search-shortcut {
    border: 1px solid #ddd;

    border-radius: 3px;

    min-width: 17px;
    height: 17px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 8px;

    color: #999;
    background: white;
}


/* =========================================================
   NEW ACTION
========================================================= */

.new-action-button {
    height: 36px;

    padding: 0 14px;

    display: flex;
    align-items: center;

    gap: 6px;

    background: #064f4c;
    color: white;

    border-radius: 7px;

    text-decoration: none;

    font-size: 10px;
    font-weight: 600;
}


.new-action-button:hover {
    background: #043e3b;
}


/* =========================================================
   PROFILE
========================================================= */

.profile-button {
    width: 36px;
    height: 36px;

    border-radius: 50%;

    background: #e7efed;
    color: #064f4c;

    display: flex;
    align-items: center;
    justify-content: center;

    text-decoration: none;
}


/* =========================================================
   MOBILE MENU
========================================================= */

.mobile-menu-button {
    display: none;

    border: 0;
    background: transparent;

    font-size: 20px;
    cursor: pointer;
}


/* =========================================================
   CONTENT
========================================================= */

.dashboard-content {
    padding: 24px;

    max-width: 1500px;
    margin: auto;
}


/* =========================================================
   STATISTICS
========================================================= */

.stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 15px;

    margin-bottom: 16px;
}


.stat-card {
    background: white;

    border: 1px solid #e9e9e9;
    border-radius: 10px;

    padding: 16px;
}


.stat-card-top {
    display: flex;

    justify-content: space-between;
    align-items: center;
}


.stat-icon {
    width: 35px;
    height: 35px;

    border-radius: 8px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 16px;
}


.stat-icon.orders {
    background: #edf4ff;
    color: #3274d8;
}


.stat-icon.revenue {
    background: #edf8f3;
    color: #25855b;
}


.stat-icon.customers {
    background: #f8f0ff;
    color: #8956b8;
}


.stat-icon.products {
    background: #fff4e8;
    color: #d17b28;
}


.stat-growth {
    font-size: 9px;
    color: #999;
}


.stat-growth.positive {
    color: #27865c;
}


.stat-card p {
    margin: 13px 0 3px;

    color: #888;
    font-size: 10px;
}


.stat-card h2 {
    margin: 0;

    font-size: 20px;
    font-weight: 600;
}


/* =========================================================
   TWO COLUMNS
========================================================= */

.dashboard-two-column {
    display: grid;

    grid-template-columns:
        minmax(0, 1.55fr)
        minmax(280px, .9fr);

    gap: 15px;

    margin-bottom: 15px;
}


/* =========================================================
   PANELS
========================================================= */

.dashboard-panel {
    background: #fff;

    border: 1px solid #e8e8e8;
    border-radius: 10px;

    overflow: hidden;
}


.panel-header {
    min-height: 58px;

    padding: 12px 15px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    border-bottom: 1px solid #eee;
}


.panel-header h3 {
    margin: 0;

    font-size: 13px;
    font-weight: 600;
}


.panel-header p {
    margin: 3px 0 0;

    font-size: 9px;
    color: #999;
}


.panel-menu {
    width: 28px;
    height: 28px;

    border: 0;

    background: #f7f7f7;

    border-radius: 6px;

    cursor: pointer;

    color: #777;
}


.feed-count {
    width: 26px;
    height: 26px;

    border-radius: 50%;

    background: #f3f3f3;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 9px;
}


/* =========================================================
   ORDERS
========================================================= */

.orders-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));
}


.order-item {
    min-height: 105px;

    padding: 14px;

    border-right: 1px solid #eee;
    border-bottom: 1px solid #eee;

    display: flex;
    align-items: center;

    gap: 10px;
}


.order-product-icon {
    width: 38px;
    height: 38px;

    flex: 0 0 38px;

    border-radius: 7px;

    background: #f3f5f4;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #064f4c;

    font-size: 17px;
}


.order-information {
    min-width: 0;
    flex: 1;
}


.order-information strong {
    display: block;

    font-size: 10px;
}


.order-information span {
    display: block;

    margin-top: 3px;

    font-size: 9px;
    color: #666;
}


.order-information small {
    display: block;

    margin-top: 4px;

    font-size: 8px;
    color: #aaa;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.order-status {
    text-align: right;
}


.order-status strong {
    display: block;

    margin-top: 7px;

    font-size: 9px;
}


.status-badge {
    padding: 3px 6px;

    border-radius: 4px;

    font-size: 7px;

    display: inline-block;
}


.status-badge.pending {
    background: #fff4d8;
    color: #9d7118;
}


.status-badge.processing {
    background: #e9f2ff;
    color: #3470c5;
}


.status-badge.completed {
    background: #e5f6ed;
    color: #26805a;
}


.panel-footer {
    padding: 10px 15px;

    text-align: center;

    border-top: 1px solid #eee;
}


.panel-footer a {
    color: #064f4c;

    text-decoration: none;

    font-size: 9px;
}


/* =========================================================
   FEED
========================================================= */

.feed-list {
    padding: 0;
}


.feed-item {
    min-height: 64px;

    padding: 10px 14px;

    display: flex;
    align-items: center;

    gap: 10px;

    border-bottom: 1px solid #eee;
}


.feed-icon {
    width: 30px;
    height: 30px;

    flex: 0 0 30px;

    border-radius: 50%;

    background: #f4f7f6;
    color: #064f4c;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 13px;
}


.feed-content {
    min-width: 0;
}


.feed-content strong {
    display: block;

    font-size: 9px;
}


.feed-content span {
    display: block;

    font-size: 8px;
    color: #777;

    margin-top: 3px;
}


.feed-content small {
    display: block;

    font-size: 7px;
    color: #aaa;

    margin-top: 3px;
}


/* =========================================================
   REPORTS
========================================================= */

.reports-panel {
    margin-bottom: 15px;
}


.reports-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));
}


.report-item {
    min-height: 100px;

    padding: 14px;

    border-right: 1px solid #eee;
    border-bottom: 1px solid #eee;

    display: flex;
    align-items: center;

    gap: 10px;
}


.report-file {
    width: 32px;
    height: 38px;

    border-radius: 5px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    font-size: 13px;
}


.report-file span {
    font-size: 6px;

    margin-top: 2px;

    font-weight: 700;
}


.report-file.pdf {
    background: #fff0f0;
    color: #d93030;
}


.report-file.csv {
    background: #eef9ef;
    color: #2c8b4f;
}


.report-file.xls {
    background: #edf5ff;
    color: #3374ca;
}


.report-information {
    flex: 1;
    min-width: 0;
}


.report-information strong {
    display: block;

    font-size: 9px;
}


.report-information small {
    display: block;

    color: #aaa;

    font-size: 7px;

    margin-top: 4px;
}


.report-actions {
    display: flex;

    gap: 3px;
}


.report-actions button {
    border: 0;

    width: 25px;
    height: 25px;

    border-radius: 5px;

    background: #f7f7f7;

    color: #777;

    cursor: pointer;
}


.report-actions button:hover {
    background: #edf5f3;
    color: #064f4c;
}


/* =========================================================
   BOTTOM
========================================================= */

.bottom-section {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        auto;

    gap: 15px;
}


.progress-card {
    background: #fff;

    border: 1px solid #e8e8e8;

    border-radius: 10px;

    padding: 15px;

    display: flex;
    align-items: center;

    gap: 14px;
}


.progress-circle {
    width: 48px;
    height: 48px;

    border-radius: 50%;

    border: 4px solid #e5efed;
    border-top-color: #064f4c;

    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 48px;
}


.progress-circle span {
    font-size: 9px;
    font-weight: 700;
}


.progress-information {
    min-width: 130px;
}


.progress-information strong {
    display: block;

    font-size: 10px;
}


.progress-information span {
    display: block;

    font-size: 8px;
    color: #999;

    margin-top: 3px;
}


.progress-bar {
    flex: 1;

    height: 6px;

    background: #edf0ef;

    border-radius: 10px;

    overflow: hidden;
}


.progress-value {
    height: 100%;

    background: #064f4c;

    border-radius: inherit;
}


.quick-summary {
    background: white;

    border: 1px solid #e8e8e8;

    border-radius: 10px;

    padding: 10px 15px;

    display: flex;
    align-items: center;

    gap: 18px;
}


.quick-summary div {
    display: flex;
    align-items: center;

    gap: 6px;

    font-size: 9px;
    color: #777;
}


/* =========================================================
   OVERLAY
========================================================= */

.sidebar-overlay {
    display: none;
}


/* =========================================================
   FADE
========================================================= */

.fade-enter-active,
.fade-leave-active {
    transition: opacity .25s ease;
}


.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1100px) {

    .admin-sidebar {
        width: 210px;
        min-width: 210px;
    }


    .stats-grid {
        grid-template-columns:
            repeat(2, 1fr);
    }


    .dashboard-two-column {
        grid-template-columns: 1fr;
    }


    .reports-grid {
        grid-template-columns:
            repeat(2, 1fr);
    }


    .dashboard-search {
        width: 170px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .admin-sidebar {
        position: fixed;

        left: 0;
        top: 0;
        bottom: 0;

        width: 260px;
        min-width: 260px;

        height: 100vh;

        transform: translateX(-105%);

        transition:
            transform .3s ease;

        box-shadow:
            10px 0 30px rgba(0,0,0,.12);
    }


    .admin-sidebar.sidebar-mobile-open {
        transform: translateX(0);
    }


    .mobile-close {
        display: block;
    }


    .sidebar-overlay {
        display: block;

        position: fixed;

        inset: 0;

        background: rgba(0,0,0,.38);

        z-index: 999;
    }


    .admin-main {
        width: 100%;
    }


    .dashboard-header {
        min-height: 65px;

        padding: 10px 15px;
    }


    .mobile-menu-button {
        display: flex;

        align-items: center;
        justify-content: center;

        width: 36px;
        height: 36px;

        flex: 0 0 36px;

        border-radius: 7px;

        background: #f3f5f4;
        color: #064f4c;
    }


    .dashboard-title {
        flex: 1;
    }


    .dashboard-title h1 {
        font-size: 17px;
    }


    .dashboard-title p {
        font-size: 8px;
    }


    .header-actions {
        gap: 5px;
    }


    .dashboard-search {
        display: none;
    }


    .new-action-button {
        width: 36px;

        padding: 0;

        justify-content: center;
    }


    .new-action-button span {
        display: none;
    }


    .dashboard-content {
        padding: 12px;
    }


    .stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 9px;
    }


    .stat-card {
        padding: 12px;
    }


    .stat-card h2 {
        font-size: 16px;
    }


    .stat-card p {
        font-size: 9px;
    }


    .dashboard-two-column {
        grid-template-columns: 1fr;
        gap: 10px;
    }


    .orders-grid {
        grid-template-columns: 1fr;
    }


    .order-item {
        border-right: 0;
    }


    .reports-grid {
        grid-template-columns: 1fr;
    }


    .report-item {
        border-right: 0;
    }


    .bottom-section {
        grid-template-columns: 1fr;
    }


    .quick-summary {
        justify-content: space-around;
    }

}


/* =========================================================
   SMALL PHONES
========================================================= */

@media (max-width: 480px) {

    .dashboard-header {
        gap: 8px;
    }


    .profile-button {
        width: 32px;
        height: 32px;
    }


    .new-action-button {
        width: 32px;
        height: 32px;
    }


    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }


    .stat-icon {
        width: 30px;
        height: 30px;

        font-size: 13px;
    }


    .stat-card h2 {
        font-size: 14px;
    }


    .order-item {
        padding: 12px;
    }


    .order-status strong {
        font-size: 8px;
    }


    .progress-card {
        flex-wrap: wrap;
    }


    .progress-bar {
        flex-basis: 100%;
    }

}

</style>