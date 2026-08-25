<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import {
    UserOutlined,
    MailOutlined,
    PhoneOutlined,
    EnvironmentOutlined,
    EditOutlined,
    ArrowLeftOutlined,
    ShoppingOutlined,
    LockOutlined,
} from "@ant-design/icons-vue";

const props = defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
});

/*
|--------------------------------------------------------------------------
| USER INITIALS
|--------------------------------------------------------------------------
*/

const initials = computed(() => {
    const first = props.user?.name?.charAt(0) || "";
    const last = props.user?.last_name?.charAt(0) || "";

    return `${first}${last}`.toUpperCase() || "U";
});

/*
|--------------------------------------------------------------------------
| DISPLAY NAME
|--------------------------------------------------------------------------
*/

const displayName = computed(() => {
    if (props.user?.name) {
        return props.user.name;
    }

    return "User";
});
</script>

<template>
    <UserFashionLayout>
        <Head title="My Profile" />

        <div class="profile-page">

            <!-- ========================================================= -->
            <!-- HEADER -->
            <!-- ========================================================= -->

            <div class="profile-header">

                <div>
                    <div class="profile-breadcrumb">
                        <Link href="/client/dashboard">
                            <ArrowLeftOutlined />
                            Dashboard
                        </Link>

                        <span>/</span>

                        <span>My Profile</span>
                    </div>

                    <h1>
                        My Profile
                    </h1>

                    <p>
                        Manage your personal information and account details.
                    </p>
                </div>

                <Link
                    href="/client/shop"
                    class="continue-shopping"
                >
                    <ShoppingOutlined />
                    Continue Shopping
                </Link>

            </div>


            <!-- ========================================================= -->
            <!-- PROFILE CONTENT -->
            <!-- ========================================================= -->

            <div class="profile-grid">

                <!-- ===================================================== -->
                <!-- PROFILE CARD -->
                <!-- ===================================================== -->

                <div class="profile-card">

                    <div class="profile-card-header">
                        <h2>
                            Profile Information
                        </h2>

                        <Link
                            href="/client/my-profile/edit"
                            class="edit-button"
                        >
                            <EditOutlined />
                            Edit Profile
                        </Link>
                    </div>


                    <!-- PROFILE AVATAR -->

                    <div class="profile-avatar-section">

                        <div class="profile-avatar">
                            {{ initials }}
                        </div>

                        <div>
                            <h3>
                                {{ displayName }}
                            </h3>

                            <p>
                                {{ user?.email || "No email address" }}
                            </p>
                        </div>

                    </div>


                    <!-- DETAILS -->

                    <div class="profile-details">

                        <!-- NAME -->

                        <div class="profile-detail">

                            <div class="detail-icon">
                                <UserOutlined />
                            </div>

                            <div>
                                <span class="detail-label">
                                    Full Name
                                </span>

                                <strong>
                                    {{ user?.name || "Not provided" }}
                                </strong>
                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div class="profile-detail">

                            <div class="detail-icon">
                                <MailOutlined />
                            </div>

                            <div>
                                <span class="detail-label">
                                    Email Address
                                </span>

                                <strong>
                                    {{ user?.email || "Not provided" }}
                                </strong>
                            </div>

                        </div>


                        <!-- PHONE -->

                        <div class="profile-detail">

                            <div class="detail-icon">
                                <PhoneOutlined />
                            </div>

                            <div>
                                <span class="detail-label">
                                    Phone Number
                                </span>

                                <strong>
                                    {{ user?.phone || "Not provided" }}
                                </strong>
                            </div>

                        </div>


                        <!-- ADDRESS -->

                        <div class="profile-detail">

                            <div class="detail-icon">
                                <EnvironmentOutlined />
                            </div>

                            <div>
                                <span class="detail-label">
                                    Address
                                </span>

                                <strong>
                                    {{ user?.address || "Not provided" }}
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- ===================================================== -->
                <!-- ACCOUNT CARD -->
                <!-- ===================================================== -->

                <div class="account-card">

                    <div class="account-card-header">
                        <h2>
                            Account
                        </h2>

                        <UserOutlined />
                    </div>


                    <div class="account-menu">

                        <Link
                            href="/client/my-profile/edit"
                            class="account-menu-item"
                        >
                            <div class="account-menu-icon">
                                <EditOutlined />
                            </div>

                            <div>
                                <strong>
                                    Edit Profile
                                </strong>

                                <span>
                                    Update your personal information
                                </span>
                            </div>
                        </Link>


                        <!-- <Link
                            href="/client/password"
                            class="account-menu-item"
                        >
                            <div class="account-menu-icon">
                                <LockOutlined />
                            </div>

                            <div>
                                <strong>
                                    Change Password
                                </strong>

                                <span>
                                    Update your account password
                                </span>
                            </div>
                        </Link> -->


                        <Link
                            href="/client/orders"
                            class="account-menu-item"
                        >
                            <div class="account-menu-icon">
                                <ShoppingOutlined />
                            </div>

                            <div>
                                <strong>
                                    My Orders
                                </strong>

                                <span>
                                    View your order history
                                </span>
                            </div>
                        </Link>

                    </div>

                </div>

            </div>

        </div>
    </UserFashionLayout>
</template>


<style scoped>

.profile-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 24px 70px;
}

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.profile-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 35px;
}

.profile-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #777;
    margin-bottom: 12px;
}

.profile-breadcrumb a {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
    text-decoration: none;
}

.profile-breadcrumb a:hover {
    color: #00897b;
}

.profile-header h1 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
    color: #222;
}

.profile-header p {
    margin: 8px 0 0;
    color: #777;
}

/*
|--------------------------------------------------------------------------
| CONTINUE SHOPPING
|--------------------------------------------------------------------------
*/

.continue-shopping {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 8px;
    background: #00897b;
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: 0.25s ease;
}

.continue-shopping:hover {
    background: #00695c;
    color: white;
    transform: translateY(-1px);
}

/*
|--------------------------------------------------------------------------
| GRID
|--------------------------------------------------------------------------
*/

.profile-grid {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
    gap: 25px;
}

/*
|--------------------------------------------------------------------------
| CARDS
|--------------------------------------------------------------------------
*/

.profile-card,
.account-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #eeeeee;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}

.profile-card-header,
.account-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 22px 25px;
    border-bottom: 1px solid #eeeeee;
}

.profile-card-header h2,
.account-card-header h2 {
    margin: 0;
    font-size: 19px;
    color: #222;
}

/*
|--------------------------------------------------------------------------
| EDIT BUTTON
|--------------------------------------------------------------------------
*/

.edit-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 15px;
    border: 1px solid #00897b;
    border-radius: 7px;
    color: #00897b;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
}

.edit-button:hover {
    background: #00897b;
    color: white;
}

/*
|--------------------------------------------------------------------------
| AVATAR
|--------------------------------------------------------------------------
*/

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 30px 25px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(
        135deg,
        #00897b,
        #26a69a
    );
    color: white;
    font-size: 26px;
    font-weight: 700;
}

.profile-avatar-section h3 {
    margin: 0 0 5px;
    font-size: 22px;
}

.profile-avatar-section p {
    margin: 0;
    color: #777;
}

/*
|--------------------------------------------------------------------------
| DETAILS
|--------------------------------------------------------------------------
*/

.profile-details {
    padding: 0 25px 25px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.profile-detail {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 16px;
    background: #fafafa;
    border-radius: 10px;
}

.detail-icon {
    width: 40px;
    height: 40px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e0f2f1;
    color: #00897b;
    font-size: 18px;
}

.profile-detail > div:last-child {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-label {
    font-size: 12px;
    color: #888;
}

.profile-detail strong {
    font-size: 14px;
    color: #333;
}

/*
|--------------------------------------------------------------------------
| ACCOUNT
|--------------------------------------------------------------------------
*/

.account-card-header svg {
    font-size: 20px;
    color: #00897b;
}

.account-menu {
    padding: 10px;
}

.account-menu-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border-radius: 10px;
    text-decoration: none;
    color: #222;
    transition: 0.2s ease;
}

.account-menu-item:hover {
    background: #f5faf9;
}

.account-menu-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #e0f2f1;
    color: #00897b;
    font-size: 18px;
}

.account-menu-item div:last-child {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.account-menu-item strong {
    font-size: 14px;
}

.account-menu-item span {
    font-size: 12px;
    color: #888;
}

/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 850px) {

    .profile-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .profile-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 600px) {

    .profile-page {
        padding: 25px 15px 50px;
    }

    .profile-header h1 {
        font-size: 26px;
    }

    .profile-card-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .profile-details {
        grid-template-columns: 1fr;
    }

    .continue-shopping {
        width: 100%;
        justify-content: center;
    }
}

</style>