<script setup>
import { Head, Link } from "@inertiajs/vue3";

import UserFashionLayout from "@/Layouts/UserFashionLayout.vue";

import UpdateUserProfileInformationForm from "@/Pages/Profile/Partials/UpdateUserProfileInformationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";

import {
    ArrowLeftOutlined,
    UserOutlined,
    LockOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
        default: false,
    },

    status: {
        type: String,
        default: null,
    },
});
</script>

<template>

    <UserFashionLayout>

        <Head title="Edit Profile" />

        <div class="client-profile-page">

            <!-- ===================================================== -->
            <!-- HEADER -->
            <!-- ===================================================== -->

            <div class="page-header">

                <div>

                    <div class="breadcrumb">

                        <Link href="/client/my-profile">

                            <ArrowLeftOutlined />

                            My Profile

                        </Link>

                        <span>/</span>

                        <span>Edit Profile</span>

                    </div>

                    <h1>
                        Edit Profile
                    </h1>

                    <p>
                        Manage your personal information, password,
                        and account settings.
                    </p>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- PROFILE INFORMATION -->
            <!-- ===================================================== -->

            <section class="profile-section">

                <div class="section-heading">

                    <div class="section-icon">
                        <UserOutlined />
                    </div>

                    <div>

                        <h2>
                            Profile Information
                        </h2>

                        <p>
                            Update your name, email address and
                            other personal information.
                        </p>

                    </div>

                </div>

                <div class="form-container">

                    <UpdateUserProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />

                </div>

            </section>


            <!-- ===================================================== -->
            <!-- PASSWORD -->
            <!-- ===================================================== -->

            <section class="profile-section">

                <div class="section-heading">

                    <div class="section-icon">
                        <LockOutlined />
                    </div>

                    <div>

                        <h2>
                            Update Password
                        </h2>

                        <p>
                            Make sure your account uses a strong,
                            secure password.
                        </p>

                    </div>

                </div>

                <div class="form-container">

                    <UpdatePasswordForm />

                </div>

            </section>


            <!-- ===================================================== -->
            <!-- DELETE ACCOUNT -->
            <!-- ===================================================== -->

            <!-- <section class="profile-section danger-section">

                <div class="section-heading">

                    <div class="danger-icon">
                        <DeleteOutlined />
                    </div>

                    <div>

                        <h2>
                            Delete Account
                        </h2>

                        <p>
                            Permanently delete your account and
                            all associated data.
                        </p>

                    </div>

                </div>

                <div class="form-container">

                    <DeleteUserForm />

                </div>

            </section> -->


            <!-- ===================================================== -->
            <!-- BACK -->
            <!-- ===================================================== -->

            <div class="bottom-actions">

                <Link
                    href="/client/my-profile"
                    class="back-button"
                >
                    <ArrowLeftOutlined />
                    Back to My Profile
                </Link>

            </div>

        </div>

    </UserFashionLayout>

</template>


<style scoped>

.client-profile-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 24px 70px;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {
    margin-bottom: 30px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 13px;
    color: #777;
}

.breadcrumb a {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
    text-decoration: none;
}

.breadcrumb a:hover {
    color: #00897b;
}

.page-header h1 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
    color: #222;
}

.page-header p {
    margin: 8px 0 0;
    color: #777;
}


/*
|--------------------------------------------------------------------------
| SECTION
|--------------------------------------------------------------------------
*/

.profile-section {
    background: #fff;
    border: 1px solid #eeeeee;
    border-radius: 16px;
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}


/*
|--------------------------------------------------------------------------
| SECTION HEADING
|--------------------------------------------------------------------------
*/

.section-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 24px 25px;
    border-bottom: 1px solid #eeeeee;
}

.section-icon,
.danger-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;
    font-size: 20px;
}

.section-icon {
    background: #e0f2f1;
    color: #00897b;
}

.danger-icon {
    background: #ffebee;
    color: #d32f2f;
}

.section-heading h2 {
    margin: 0 0 4px;
    font-size: 19px;
    color: #222;
}

.section-heading p {
    margin: 0;
    color: #888;
    font-size: 13px;
}


/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

.form-container {
    padding: 25px;
}


/*
|--------------------------------------------------------------------------
| DANGER
|--------------------------------------------------------------------------
*/

.danger-section {
    border-color: #ffcdd2;
}


/*
|--------------------------------------------------------------------------
| BOTTOM ACTION
|--------------------------------------------------------------------------
*/

.bottom-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 10px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 11px 18px;

    border-radius: 8px;

    background: #f5f5f5;
    color: #555;

    text-decoration: none;
    font-weight: 600;

    transition: 0.2s ease;
}

.back-button:hover {
    background: #eeeeee;
    color: #00897b;
}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 600px) {

    .client-profile-page {
        padding: 25px 15px 50px;
    }

    .page-header h1 {
        font-size: 26px;
    }

    .section-heading {
        padding: 20px;
    }

    .form-container {
        padding: 20px;
    }

}

</style>