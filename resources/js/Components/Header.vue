<!-- resources\js\Components\Header.vue -->
<script setup>
import { Link } from "@inertiajs/vue3";
import { Tooltip } from "ant-design-vue";

import {
    UserOutlined,
    UserAddOutlined,
    SearchOutlined,
    HeartOutlined,
    ShoppingOutlined,
    ShoppingCartOutlined,
    MenuOutlined,
    DownOutlined,
    RightOutlined,
} from "@ant-design/icons-vue";


defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>

    <header class="site-header">

        <!-- ============================= -->
        <!-- TOP BLACK ANNOUNCEMENT BAR -->
        <!-- ============================= -->

        <div class="top-bar">

            <div class="social-links">
                <span>●</span>
                <span>◎</span>
                <span>◉</span>
            </div>

            <div class="top-message">
                NOTHING OVER K50.00 - SHOP NOW
            </div>

        </div>


        <!-- ============================= -->
        <!-- LOGO + ICONS -->
        <!-- ============================= -->




        <!-- ============================= -->
        <!-- LOGO + ICONS -->
        <!-- ============================= -->

        <div class="main-header">

            <div class="header-spacer"></div>


            <!-- ============================= -->
            <!-- LOGO -->
            <!-- ============================= -->

            <Link href="/" class="brand-logo">
                FASHION STYLES
            </Link>


            <!-- ============================= -->
            <!-- HEADER ICONS -->
            <!-- ============================= -->

            <div class="header-icons">


                <!-- ========================= -->
                <!-- SEARCH -->
                <!-- ========================= -->

                <a-tooltip title="Search" placement="bottom">

                    <Link href="/search" class="header-icon" aria-label="Search">
                        <SearchOutlined />
                    </Link>

                </a-tooltip>


                <!-- ========================= -->
                <!-- LOGIN / ACCOUNT -->
                <!-- ========================= -->

                <a-tooltip v-if="canLogin" title="Login" placement="bottom">

                    <Link href="/login" class="header-icon" aria-label="Login">
                        <UserOutlined />
                    </Link>

                </a-tooltip>


                <!-- ========================= -->
                <!-- REGISTER / WISHLIST -->
                <!-- ========================= -->

                <a-tooltip v-if="canRegister" title="Create Account" placement="bottom">
                    <Link href="/register" class="header-icon" aria-label="Create Account">
                        <UserAddOutlined />
                    </Link>
                </a-tooltip>


                <!-- ========================= -->
                <!-- CART -->
                <!-- ========================= -->

                <a-tooltip title="Shopping Cart" placement="bottom">

                    <Link href="/cart" class="header-icon" aria-label="Shopping Cart">
                        <ShoppingOutlined />
                    </Link>

                </a-tooltip>

            </div>

        </div>

        <!-- ============================= -->
        <!-- NAVIGATION -->
        <!-- ============================= -->

        <!-- <nav class="navigation">

            <Link href="/">
                HOME
            </Link>

            <Link href="#about">
                ABOUT US
            </Link>

            <Link href="#products">
                PRODUCTS
            </Link>

            <Link href="#farming">
                OUR FARM
            </Link>

            <Link href="#news">
                NEWS
            </Link>

            <Link href="#contact">
                CONTACT
            </Link>

        </nav> -->

        <!-- ============================= -->
        <!-- NAVIGATION -->
        <!-- ============================= -->

        <nav class="navigation">

            <!-- LINKS -->
            <Link href="/" class="nav-link"> HOME </Link>
            <Link href="/new-arrivals" class="nav-link sale-link"> NEW ARRIVALS</Link>


            <!-- ============================= -->
            <!-- CLOTHING DROPDOWN -->
            <!-- ============================= -->

            <div class="nav-dropdown">
                <button class="nav-link dropdown-trigger">CLOTHING
                    <span class="dropdown-arrow"> ▾ </span>
                </button>
                <div class="dropdown-menu">
                    <Link href="/clothing/women"> WOMEN </Link>
                    <Link href="/clothing/men"> MEN </Link>
                    <Link href="/clothing/kids"> KIDS </Link>
                    <Link href="/clothing/beauty"> BEAUTY </Link>
                </div>
            </div>


            <!-- ============================= -->
            <!-- HANDBAGS & ACCESSORIES -->
            <!-- ============================= -->

            <div class="nav-dropdown">
                <button class="nav-link dropdown-trigger"> HANDBAGS & ACCESSORIES
                    <span class="dropdown-arrow"> ▾ </span>
                </button>
                <div class="dropdown-menu">
                    <Link href="/accessories/handbags"> HANDBAGS </Link>
                    <Link href="/accessories/shoes"> SHOES </Link>
                    <Link href="/accessories/jewellery"> JEWELLERY </Link>
                    <Link href="/accessories/belts"> BELTS </Link>
                    <Link href="/accessories/sunglasses"> SUNGLASSES </Link>
                    <Link href="/accessories/watches"> WATCHES </Link>
                </div>
            </div>


            <!-- ============================= -->
            <!-- MENS COLLECTION -->
            <!-- ============================= -->

            <div class="nav-dropdown">
                <button class="nav-link dropdown-trigger"> MENS COLLECTION
                    <span class="dropdown-arrow"> ▾ </span>
                </button>


                <div class="dropdown-menu">
                    <Link href="/men/new-arrivals"> NEW ARRIVALS </Link>
                    <Link href="/men/shirts"> SHIRTS </Link>
                    <Link href="/men/t-shirts"> T-SHIRTS </Link>
                    <Link href="/men/trousers"> TROUSERS </Link>
                    <Link href="/men/jackets"> JACKETS </Link>
                    <Link href="/men/accessories"> ACCESSORIES </Link>
                </div>
            </div>


            <!-- CONTACT -->
            <Link href="/contact" class="nav-link"> CONTACT </Link>

            <!-- OUR COLLECTIONS -->
            <Link href="/collections" class="nav-link"> OUR COLLECTIONS </Link>

        </nav>
    </header>

</template>


<style scoped>
.site-header {
    width: 100%;
    background: #ffffff;
    position: relative;
    z-index: 50;
}


.header-icons {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 18px;
}

.header-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 24px;
    height: 24px;

    color: #4d4842;

    font-size: 18px;

    text-decoration: none;

    cursor: pointer;

    transition:
        color 0.3s ease,
        transform 0.3s ease;
}

.header-icon:hover {
    color: #000000;
    transform: translateY(-1px);
}

/* =========================================
   TOP BAR
========================================= */

.top-bar {
    height: 36px;

    background: #050505;
    color: #ffffff;

    display: flex;
    align-items: center;
    justify-content: center;

    position: relative;

    font-size: 9px;
    letter-spacing: 0.4px;
}

.social-links {
    position: absolute;

    left: 52px;

    display: flex;
    gap: 10px;

    font-size: 9px;
}

.top-message {
    text-decoration: underline;
    text-underline-offset: 3px;
}


/* =========================================
   MAIN HEADER
========================================= */

.main-header {
    height: 52px;

    display: grid;

    grid-template-columns: 1fr auto 1fr;

    align-items: center;

    padding: 0 28px;
}

.header-spacer {
    width: 100%;
}


/* =========================================
   LOGO
========================================= */

.brand-logo {
    color: #111111;

    font-family:
        Georgia,
        "Times New Roman",
        serif;

    font-size: 30px;

    font-weight: 400;

    letter-spacing: -1.5px;

    text-decoration: none;

    white-space: nowrap;
}


/* =========================================
   HEADER ICONS
========================================= */

.header-icons {

    display: flex;

    justify-content: flex-end;

    align-items: center;

    gap: 18px;
}


/* Link instead of button */

.header-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 24px;

    height: 24px;

    color: #4d4842;

    text-decoration: none;

    font-size: 22px;

    font-weight: 300;

    cursor: pointer;

    transition:
        color 0.25s ease,
        transform 0.25s ease;
}


/* Hover */

.header-icon:hover {

    color: #111111;

    transform: translateY(-1px);

}


/* =========================================
   NAVIGATION
========================================= */

/* =========================================
   NAVIGATION
========================================= */

.navigation {

    min-height: 42px;

    border-top: 1px solid #eeeeee;
    border-bottom: 1px solid #eeeeee;

    display: flex;

    justify-content: center;
    align-items: center;

    gap: 34px;

    padding: 0 20px;

    background: #ffffff;

    position: relative;

    z-index: 100;
}


/* =========================================
   NORMAL NAVIGATION LINKS
========================================= */

.nav-link {

    position: relative;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    height: 42px;

    color: #444444;

    background: transparent;

    border: none;

    padding: 0;

    font-size: 9px;

    font-weight: 400;

    letter-spacing: 0.35px;

    text-decoration: none;

    white-space: nowrap;

    cursor: pointer;

    transition:
        color 0.35s ease,
        opacity 0.35s ease;
}


/* =========================================
   SMOOTH UNDERLINE
========================================= */

.nav-link::after {

    content: "";

    position: absolute;

    left: 50%;

    bottom: 7px;

    width: 0;

    height: 1px;

    background: #111111;

    transform:
        translateX(-50%);

    transition:
        width 0.35s ease;
}


/* Hover */

.nav-link:hover {

    color: #111111;

}


/* Expand underline */

.nav-link:hover::after {

    width: 100%;

}


/* =========================================
   SALE
========================================= */

.sale-link {

    color: #b42323;

}

.sale-link:hover {

    color: #8f1515;

}


/* =========================================
   DROPDOWN CONTAINER
========================================= */

.nav-dropdown {

    position: relative;

    height: 42px;

    display: flex;

    align-items: center;

}


/* =========================================
   DROPDOWN BUTTON
========================================= */

.dropdown-trigger {

    font-family: inherit;

}


/* =========================================
   ARROW
========================================= */

.dropdown-arrow {

    margin-left: 5px;

    font-size: 8px;

    line-height: 1;

    transition:
        transform 0.35s ease;

}


/* Rotate arrow on hover */

.nav-dropdown:hover .dropdown-arrow {

    transform: rotate(180deg);

}


/* =========================================
   DROPDOWN MENU
========================================= */

.dropdown-menu {

    position: absolute;

    top: calc(100% + 1px);

    left: 50%;

    transform:
        translateX(-50%) translateY(10px);

    min-width: 190px;

    padding: 12px 0;

    background: #ffffff;

    border: 1px solid #eeeeee;

    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.08);

    opacity: 0;

    visibility: hidden;

    pointer-events: none;

    transition:

        opacity 0.3s ease,

        transform 0.3s ease,

        visibility 0.3s ease;

}


/* =========================================
   SHOW DROPDOWN
========================================= */

.nav-dropdown:hover .dropdown-menu {

    opacity: 1;

    visibility: visible;

    pointer-events: auto;

    transform:
        translateX(-50%) translateY(0);

}


/* =========================================
   DROPDOWN LINKS
========================================= */

.dropdown-menu a {

    position: relative;

    display: flex;

    align-items: center;

    width: 100%;

    min-height: 38px;

    padding: 0 22px;

    color: #555555;

    background: #ffffff;

    font-size: 9px;

    letter-spacing: 0.4px;

    text-decoration: none;

    white-space: nowrap;

    transition:

        color 0.25s ease,

        background 0.25s ease,

        padding-left 0.25s ease;

}


/* Dropdown hover */

.dropdown-menu a:hover {

    color: #111111;

    background: #f8f8f8;

    padding-left: 27px;

}


/* =========================================
   DROPDOWN TOP LINE
========================================= */

.dropdown-menu::before {

    content: "";

    position: absolute;

    top: 0;

    left: 20px;

    right: 20px;

    height: 1px;

    background: #111111;

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 1100px) {

    .navigation {

        gap: 20px;

    }

    .nav-link {

        font-size: 8px;

    }

}


@media (max-width: 768px) {

    .navigation {

        justify-content: flex-start;

        overflow-x: auto;

        gap: 22px;

        padding: 0 15px;

        scrollbar-width: none;

    }


    .navigation::-webkit-scrollbar {

        display: none;

    }


    .nav-link {

        height: 42px;

        font-size: 8px;

    }


    /*
       On mobile, dropdowns are still accessible,
       but the menu opens when the parent is active.
    */

    .dropdown-menu {

        left: 0;

        transform:
            translateY(10px);

    }


    .nav-dropdown:hover .dropdown-menu {

        transform:
            translateY(0);

    }

}


/* =========================================
   INFORMATION BAR
========================================= */

.information-bar {

    height: 28px;

    background: #eef1f3;

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    align-items: center;

    text-align: center;

    color: #777777;

    font-size: 8px;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 768px) {

    .social-links {

        left: 15px;

    }


    .top-message {

        font-size: 7px;

    }


    .main-header {

        height: 60px;

    }


    .brand-logo {

        font-size: 25px;

    }


    .header-icons {

        gap: 8px;

    }


    .header-icon {

        width: 20px;

        height: 20px;

        font-size: 18px;

    }


    .navigation {

        gap: 18px;

        overflow-x: auto;

        justify-content: flex-start;

        padding: 0 15px;

    }


    .navigation a {

        white-space: nowrap;

        font-size: 8px;

    }


    .information-bar {

        font-size: 7px;

    }

}
</style>