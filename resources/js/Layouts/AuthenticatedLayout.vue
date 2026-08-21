<script setup>

import { ref, watch, onMounted, onUnmounted } from 'vue';
import { debounce } from 'lodash';
import { router, usePage, Link } from '@inertiajs/vue3';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

import axios from 'axios';


/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

const page = usePage();

const auth = page.props.auth;


/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const showingNavigationDropdown = ref(false);


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const searchValue = ref('');
const searchResults = ref([]);
const showSuggestions = ref(false);
const isLoading = ref(false);


/*
|--------------------------------------------------------------------------
| Permission Check
|--------------------------------------------------------------------------
*/

const can = (permission) => {

    // Super Admin
    if (
        auth?.user?.roles?.includes('Super Admin')
    ) {
        return true;
    }

    return auth?.permissions?.includes(permission) ?? false;
};


/*
|--------------------------------------------------------------------------
| Search Users
|--------------------------------------------------------------------------
*/

const debouncedSearch = debounce(async (value) => {

    if (value.length < 2) {

        searchResults.value = [];
        showSuggestions.value = false;

        return;
    }

    isLoading.value = true;

    try {

        const response = await axios.get(
            route('users.search'),
            {
                params: {
                    query: value
                }
            }
        );

        searchResults.value = response.data;

        showSuggestions.value = true;

    } catch (error) {

        console.error('Search error:', error);

        searchResults.value = [];
        showSuggestions.value = false;

    } finally {

        isLoading.value = false;

    }

}, 500);


/*
|--------------------------------------------------------------------------
| Watch Search
|--------------------------------------------------------------------------
*/

watch(searchValue, (newVal) => {

    if (newVal) {

        debouncedSearch(newVal);

    } else {

        searchResults.value = [];
        showSuggestions.value = false;

    }

});


/*
|--------------------------------------------------------------------------
| Select User
|--------------------------------------------------------------------------
*/

const selectUser = (user) => {

    router.visit(
        route('users.show', {
            user: user.id
        })
    );

    searchValue.value = '';
    searchResults.value = [];
    showSuggestions.value = false;

};


/*
|--------------------------------------------------------------------------
| Close Search
|--------------------------------------------------------------------------
*/

const handleClickOutside = (event) => {

    const searchContainer =
        document.querySelector('.search-container');

    if (
        searchContainer &&
        !searchContainer.contains(event.target)
    ) {

        showSuggestions.value = false;

    }

};


/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {

    document.addEventListener(
        'click',
        handleClickOutside
    );

});


onUnmounted(() => {

    document.removeEventListener(
        'click',
        handleClickOutside
    );

});

</script>

<template>

    <div class="min-h-screen bg-gray-50">

        <!-- =====================================================
             AUTHENTICATED HEADER
        ====================================================== -->

        <nav class="dashboard-navbar">

            <div class="dashboard-navbar-inner">


                <!-- =================================================
                     LOGO
                ================================================== -->

                <div class="dashboard-brand">

                    <Link
                        :href="route('dashboard')"
                        class="dashboard-logo"
                    >

                        <ApplicationLogo
                            class="dashboard-logo-image"
                        />

                    </Link>

                </div>


                <!-- =================================================
                     DESKTOP NAVIGATION
                ================================================== -->

                <div class="dashboard-navigation">

                    <NavLink
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                        class="dashboard-nav-link"
                    >

                        Dashboard

                    </NavLink>


                    <NavLink
                        v-if="can('manage access control')"
                        :href="route('admin.rolesAndPermission')"
                        :active="
                            route().current(
                                'admin.rolesAndPermission'
                            )
                        "
                        class="dashboard-nav-link"
                    >

                        Roles & Permissions

                    </NavLink>

                </div>


                <!-- =================================================
                     RIGHT SIDE
                ================================================== -->

                <div class="dashboard-right">


                    <!-- SEARCH -->

                    <!-- <div
                        class="search-container dashboard-search"
                    >

                        <a-input
                            v-model:value="searchValue"
                            placeholder="Search users..."
                            allowClear
                            @focus="
                                showSuggestions =
                                searchResults.length > 0
                            "
                        />


                        <div
                            v-if="showSuggestions"
                            class="search-results"
                        >

                            <div
                                v-for="user in searchResults"
                                :key="user.id"
                                class="search-result"
                                @click="selectUser(user)"
                            >

                                <div class="user-avatar">

                                    {{
                                        user.name
                                            ?.charAt(0)
                                            ?.toUpperCase()
                                    }}

                                </div>


                                <div>

                                    <div class="user-name">
                                        {{ user.name }}
                                    </div>

                                    <div class="user-email">
                                        {{ user.email }}
                                    </div>

                                </div>

                            </div>


                            <div
                                v-if="isLoading"
                                class="search-status"
                            >

                                Searching...

                            </div>



                            <div
                                v-if="
                                    !isLoading &&
                                    searchResults.length === 0 &&
                                    searchValue.length >= 2
                                "
                                class="search-status"
                            >

                                No users found.

                            </div>

                        </div>

                    </div> -->


                    <!-- ROLE -->

                    <div class="user-role">

                        <span
                            v-if="
                                auth?.user?.roles &&
                                auth.user.roles.length
                            "
                        >

                            {{ auth.user.roles[0] }}

                        </span>

                    </div>


                    <!-- ACCOUNT -->

                    <Dropdown
                        align="right"
                        width="48"
                    >

                        <template #trigger>

                            <button
                                type="button"
                                class="account-button"
                            >

                                <span class="account-avatar">

                                    {{
                                        auth.user.name
                                            ?.charAt(0)
                                            ?.toUpperCase()
                                    }}

                                </span>


                                <span class="account-name">

                                    {{ auth.user.name }}

                                </span>


                                <svg
                                    class="account-arrow"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >

                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />

                                </svg>

                            </button>

                        </template>


                        <template #content>

                            <DropdownLink
                                :href="route('profile.edit')"
                            >

                                Profile

                            </DropdownLink>


                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >

                                Log Out

                            </DropdownLink>

                        </template>

                    </Dropdown>


                    <!-- MOBILE MENU BUTTON -->

                    <button
                        type="button"
                        class="mobile-menu-button"
                        @click="
                            showingNavigationDropdown =
                            !showingNavigationDropdown
                        "
                    >

                        <svg
                            v-if="!showingNavigationDropdown"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />

                        </svg>


                        <svg
                            v-else
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />

                        </svg>

                    </button>

                </div>

            </div>


            <!-- =====================================================
                 MOBILE NAVIGATION
            ====================================================== -->

            <transition name="mobile-menu">

                <div
                    v-if="showingNavigationDropdown"
                    class="mobile-navigation"
                >

                    <ResponsiveNavLink
                        :href="route('dashboard')"
                        :active="
                            route().current('dashboard')
                        "
                    >

                        Dashboard

                    </ResponsiveNavLink>


                    <ResponsiveNavLink
                        v-if="can('manage access control')"
                        :href="
                            route(
                                'admin.rolesAndPermission'
                            )
                        "
                        :active="
                            route().current(
                                'admin.rolesAndPermission'
                            )
                        "
                    >

                        Roles & Permissions

                    </ResponsiveNavLink>


                    <div class="mobile-divider"></div>


                    <div class="mobile-user">

                        <div class="mobile-avatar">

                            {{
                                auth.user.name
                                    ?.charAt(0)
                                    ?.toUpperCase()
                            }}

                        </div>


                        <div>

                            <div class="mobile-user-name">

                                {{ auth.user.name }}

                            </div>

                            <div class="mobile-user-email">

                                {{ auth.user.email }}

                            </div>

                        </div>

                    </div>


                    <ResponsiveNavLink
                        :href="route('profile.edit')"
                    >

                        My Profile

                    </ResponsiveNavLink>


                    <ResponsiveNavLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >

                        Log Out

                    </ResponsiveNavLink>

                </div>

            </transition>

        </nav>


        <!-- =====================================================
             PAGE CONTENT
        ====================================================== -->

        <main>

            <slot />

        </main>

    </div>

</template>

<style scoped>

.dashboard-navbar {

    position: relative;

    z-index: 1000;

    background:
        linear-gradient(
            135deg,
            #005c52 0%,
            #00796b 50%,
            #009688 100%
        );

    color: white;

    box-shadow:
        0 4px 20px rgba(0, 0, 0, 0.12);

}


.dashboard-navbar-inner {

    max-width: 1400px;

    margin: auto;

    min-height: 70px;

    padding:
        0 24px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 25px;

}


/* =====================================================
   BRAND
===================================================== */

.dashboard-brand {

    display: flex;

    align-items: center;

    flex-shrink: 0;

}


.dashboard-logo {

    display: flex;

    align-items: center;

}


.dashboard-logo-image {

    width: 125px;

    height: auto;

    filter:
        brightness(0)
        invert(1);

}


/* =====================================================
   NAVIGATION
===================================================== */

.dashboard-navigation {

    display: flex;

    align-items: center;

    gap: 8px;

    flex: 1;

}


.dashboard-nav-link {

    padding:
        10px 16px;

    border-radius: 8px;

    color: rgba(255,255,255,.85) !important;

    font-size: 14px;

    font-weight: 500;

    transition:
        all .25s ease;

}


.dashboard-nav-link:hover {

    color: white !important;

    background:
        rgba(255,255,255,.12);

}


.dashboard-nav-link.active {

    color: white !important;

    background:
        rgba(255,255,255,.18);

}


/* =====================================================
   RIGHT SIDE
===================================================== */

.dashboard-right {

    display: flex;

    align-items: center;

    gap: 14px;

}


/* =====================================================
   SEARCH
===================================================== */

.dashboard-search {

    position: relative;

    width: 280px;

}


.dashboard-search :deep(.ant-input-affix-wrapper) {

    height: 40px;

    border-radius: 9px;

    background:
        rgba(255,255,255,.13);

    border:
        1px solid
        rgba(255,255,255,.25);

    box-shadow: none;

}


.dashboard-search :deep(input) {

    background: transparent;

    color: white;

}


.dashboard-search :deep(input::placeholder) {

    color:
        rgba(255,255,255,.7);

}


.dashboard-search :deep(.ant-input-clear-icon) {

    color: white;

}


/* =====================================================
   SEARCH RESULTS
===================================================== */

.search-results {

    position: absolute;

    top: 48px;

    left: 0;

    right: 0;

    background: white;

    color: #333;

    border-radius: 10px;

    overflow: hidden;

    box-shadow:
        0 12px 35px
        rgba(0,0,0,.18);

    max-height: 320px;

    overflow-y: auto;

}


.search-result {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 12px 15px;

    cursor: pointer;

    transition:
        background .2s ease;

}


.search-result:hover {

    background: #f0fdfa;

}


.user-avatar {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #00897b,
            #26a69a
        );

    color: white;

    font-weight: 700;

}


.user-name {

    font-size: 14px;

    font-weight: 600;

    color: #1f2937;

}


.user-email {

    font-size: 12px;

    color: #6b7280;

}


.search-status {

    padding: 15px;

    text-align: center;

    font-size: 13px;

    color: #6b7280;

}


/* =====================================================
   ROLE
===================================================== */

.user-role span {

    padding:
        6px 11px;

    border-radius: 20px;

    background:
        rgba(255,255,255,.12);

    border:
        1px solid
        rgba(255,255,255,.2);

    font-size: 12px;

    font-weight: 600;

    white-space: nowrap;

}


/* =====================================================
   ACCOUNT
===================================================== */

.account-button {

    display: flex;

    align-items: center;

    gap: 9px;

    padding:
        6px 10px;

    border-radius: 9px;

    border:
        1px solid
        rgba(255,255,255,.2);

    background:
        rgba(255,255,255,.08);

    color: white;

    cursor: pointer;

    transition:
        all .2s ease;

}


.account-button:hover {

    background:
        rgba(255,255,255,.16);

}


.account-avatar {

    width: 32px;

    height: 32px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: white;

    color: #00796b;

    font-weight: 700;

}


.account-name {

    max-width: 120px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    font-size: 13px;

}


.account-arrow {

    width: 16px;

    height: 16px;

}


/* =====================================================
   MOBILE BUTTON
===================================================== */

.mobile-menu-button {

    display: none;

    width: 42px;

    height: 42px;

    border-radius: 8px;

    border:
        1px solid
        rgba(255,255,255,.2);

    background:
        rgba(255,255,255,.08);

    color: white;

    align-items: center;

    justify-content: center;

}


.mobile-menu-button svg {

    width: 23px;

    height: 23px;

}


/* =====================================================
   MOBILE NAVIGATION
===================================================== */

.mobile-navigation {

    display: none;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 1100px) {

    .dashboard-search {

        width: 210px;

    }

    .user-role {

        display: none;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 768px) {

    .dashboard-navbar-inner {

        min-height: 64px;

        padding:
            0 16px;

    }


    .dashboard-navigation {

        display: none;

    }


    .dashboard-right {

        margin-left: auto;

    }


    .dashboard-search {

        display: none;

    }


    .account-name {

        display: none;

    }


    .account-button {

        border: none;

        background: transparent;

        padding: 2px;

    }


    .account-arrow {

        display: none;

    }


    .mobile-menu-button {

        display: flex;

    }


    .mobile-navigation {

        display: block;

        background: white;

        color: #374151;

        border-top:
            1px solid
            rgba(255,255,255,.15);

        padding:
            10px 16px 16px;

        box-shadow:
            0 10px 20px
            rgba(0,0,0,.12);

    }


    .mobile-divider {

        height: 1px;

        background: #e5e7eb;

        margin:
            10px 0;

    }


    .mobile-user {

        display: flex;

        align-items: center;

        gap: 12px;

        padding:
            12px 8px;

    }


    .mobile-avatar {

        width: 42px;

        height: 42px;

        border-radius: 50%;

        background:
            linear-gradient(
                135deg,
                #00897b,
                #26a69a
            );

        color: white;

        display: flex;

        align-items: center;

        justify-content: center;

        font-weight: 700;

    }


    .mobile-user-name {

        font-size: 14px;

        font-weight: 600;

        color: #111827;

    }


    .mobile-user-email {

        font-size: 12px;

        color: #6b7280;

    }

}


/* =====================================================
   MOBILE TRANSITION
===================================================== */

.mobile-menu-enter-active,
.mobile-menu-leave-active {

    transition:
        all .25s ease;

}


.mobile-menu-enter-from,
.mobile-menu-leave-to {

    opacity: 0;

    transform:
        translateY(-10px);

}

</style>