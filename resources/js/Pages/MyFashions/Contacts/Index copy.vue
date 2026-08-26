<script setup>
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

import {
    MailOutlined,
    PhoneOutlined,
    UserOutlined,
    EyeOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
} from '@ant-design/icons-vue'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    messages: {
        type: Array,
        default: () => [],
    },

})


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const search = ref('')


/*
|--------------------------------------------------------------------------
| FILTERED MESSAGES
|--------------------------------------------------------------------------
*/

const filteredMessages = computed(() => {

    const value = search.value
        .toLowerCase()
        .trim()

    if (!value) {
        return props.messages
    }

    return props.messages.filter((message) => {

        return (
            message.name?.toLowerCase().includes(value) ||
            message.email?.toLowerCase().includes(value) ||
            message.phone?.toLowerCase().includes(value) ||
            message.subject?.toLowerCase().includes(value) ||
            message.message?.toLowerCase().includes(value) ||
            message.status?.toLowerCase().includes(value)
        )

    })

})


/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const statusClass = (status) => {

    switch (status) {

        case 'pending':
            return 'status-pending'

        case 'read':
            return 'status-read'

        case 'replied':
            return 'status-replied'

        case 'closed':
            return 'status-closed'

        default:
            return 'status-default'

    }

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const formatDate = (date) => {

    if (!date) {
        return '-'
    }

    return new Date(date).toLocaleString(
        'en-ZM',
        {
            dateStyle: 'medium',
            timeStyle: 'short',
        }
    )

}

</script>


<template>

    <Head title="Contact Messages" />

    <MyFashionLayout>
        <div class="contact-messages-page">


            <!-- =========================================================
             HEADER
        ========================================================== -->

            <div class="page-header">

                <div>

                    <h1>
                        Contact Messages
                    </h1>

                    <p>
                        View and manage messages submitted through the contact form.
                    </p>

                </div>


                <Link href="/dashboard" class="back-button">
                    Back to Dashboard
                </Link>

            </div>


            <!-- =========================================================
             STATISTICS
        ========================================================== -->

            <div class="stats-grid">


                <div class="stat-card">

                    <div class="stat-icon">
                        <MailOutlined />
                    </div>

                    <div>

                        <span>
                            Total Messages
                        </span>

                        <strong>
                            {{ messages.length }}
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon pending">
                        <ClockCircleOutlined />
                    </div>

                    <div>

                        <span>
                            Pending
                        </span>

                        <strong>
                            {{
                                messages.filter(
                                    message => message.status === 'pending'
                                ).length
                            }}
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon replied">
                        <CheckCircleOutlined />
                    </div>

                    <div>

                        <span>
                            Replied
                        </span>

                        <strong>
                            {{
                                messages.filter(
                                    message => message.status === 'replied'
                                ).length
                            }}
                        </strong>

                    </div>

                </div>


            </div>


            <!-- =========================================================
             TABLE CARD
        ========================================================== -->

            <div class="table-card">


                <!-- =====================================================
                 TABLE HEADER
            ====================================================== -->

                <div class="table-header">

                    <div>

                        <h2>
                            All Contact Messages
                        </h2>

                        <p>
                            {{ filteredMessages.length }} message(s)
                        </p>

                    </div>


                    <div class="search-box">

                        <MailOutlined />

                        <input v-model="search" type="text" placeholder="Search messages..." />

                    </div>

                </div>


                <!-- =====================================================
                 TABLE
            ====================================================== -->

                <div class="table-wrapper">

                    <table class="messages-table">

                        <thead>

                            <tr>

                                <th>
                                    Sender
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Message
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <tr v-for="message in filteredMessages" :key="message.id">


                                <!-- SENDER -->

                                <td>

                                    <div class="sender">

                                        <div class="avatar">

                                            <UserOutlined />

                                        </div>

                                        <div>

                                            <strong>
                                                {{ message.name }}
                                            </strong>

                                            <small v-if="message.user">
                                                User #{{ message.user.id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- CONTACT -->

                                <td>

                                    <div class="contact-info">

                                        <div>

                                            <MailOutlined />

                                            {{ message.email }}

                                        </div>

                                        <div v-if="message.phone">

                                            <PhoneOutlined />

                                            {{ message.phone }}

                                        </div>

                                    </div>

                                </td>


                                <!-- SUBJECT -->

                                <td>

                                    <strong class="subject">
                                        {{ message.subject }}
                                    </strong>

                                </td>


                                <!-- MESSAGE -->

                                <td>

                                    <div class="message-preview">

                                        {{ message.message }}

                                    </div>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span class="status" :class="statusClass(message.status)">

                                        {{ message.status || 'pending' }}

                                    </span>

                                </td>


                                <!-- DATE -->

                                <td>

                                    <span class="date">
                                        {{ formatDate(message.created_at) }}
                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <button type="button" class="view-button">

                                        <EyeOutlined />

                                        View

                                    </button>

                                </td>


                            </tr>


                            <!-- =================================================
                             EMPTY
                        ================================================== -->

                            <tr v-if="filteredMessages.length === 0">

                                <td colspan="7" class="empty-state">

                                    <MailOutlined />

                                    <strong>
                                        No contact messages found
                                    </strong>

                                    <span>
                                        Messages submitted through the contact
                                        form will appear here.
                                    </span>

                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </MyFashionLayout>




</template>


<style scoped>
.contact-messages-page {
    padding: 28px;
    background: #f7f8fa;
    min-height: 100vh;
}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.page-header h1 {
    margin: 0;
    color: #111827;
    font-size: 24px;
    font-weight: 700;
}

.page-header p {
    margin: 5px 0 0;
    color: #9ca3af;
    font-size: 13px;
}

.back-button {
    padding: 9px 14px;
    border-radius: 8px;
    background: #111827;
    color: #ffffff;
    font-size: 12px;
    text-decoration: none;
}


/*
|--------------------------------------------------------------------------
| STATS
|--------------------------------------------------------------------------
*/

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
}

.stat-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #f0fdfa;
    color: #0f766e;
}

.stat-icon.pending {
    background: #fff7ed;
    color: #ea580c;
}

.stat-icon.replied {
    background: #f0fdf4;
    color: #16a34a;
}

.stat-card span {
    display: block;
    color: #9ca3af;
    font-size: 11px;
}

.stat-card strong {
    display: block;
    margin-top: 3px;
    color: #111827;
    font-size: 20px;
}


/*
|--------------------------------------------------------------------------
| TABLE CARD
|--------------------------------------------------------------------------
*/

.table-card {
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 13px;
    background: #ffffff;
}

.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.table-header h2 {
    margin: 0;
    color: #111827;
    font-size: 15px;
    font-weight: 700;
}

.table-header p {
    margin: 3px 0 0;
    color: #9ca3af;
    font-size: 10px;
}


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

.search-box {
    width: 260px;
    height: 38px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 11px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.search-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.messages-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1050px;
}

.messages-table th {
    padding: 12px 16px;
    background: #f9fafb;
    color: #6b7280;
    font-size: 10px;
    font-weight: 700;
    text-align: left;
    white-space: nowrap;
}

.messages-table td {
    padding: 14px 16px;
    border-top: 1px solid #f0f0f0;
    vertical-align: middle;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| SENDER
|--------------------------------------------------------------------------
*/

.sender {
    display: flex;
    align-items: center;
    gap: 9px;
}

.avatar {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 50%;
    background: #f0fdfa;
    color: #0f766e;
}

.sender strong {
    display: block;
    color: #111827;
    font-size: 11px;
}

.sender small {
    display: block;
    margin-top: 3px;
    color: #9ca3af;
    font-size: 9px;
}


/*
|--------------------------------------------------------------------------
| CONTACT
|--------------------------------------------------------------------------
*/

.contact-info div {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 4px;
    color: #6b7280;
    font-size: 10px;
}


/*
|--------------------------------------------------------------------------
| SUBJECT
|--------------------------------------------------------------------------
*/

.subject {
    color: #111827;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| MESSAGE
|--------------------------------------------------------------------------
*/

.message-preview {
    max-width: 250px;
    overflow: hidden;
    color: #6b7280;
    font-size: 10px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status {
    display: inline-flex;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 700;
    text-transform: capitalize;
}

.status-pending {
    background: #fff7ed;
    color: #c2410c;
}

.status-read {
    background: #eff6ff;
    color: #2563eb;
}

.status-replied {
    background: #f0fdf4;
    color: #16a34a;
}

.status-closed {
    background: #f3f4f6;
    color: #4b5563;
}

.status-default {
    background: #f3f4f6;
    color: #6b7280;
}


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

.date {
    color: #9ca3af;
    font-size: 9px;
    white-space: nowrap;
}


/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

.view-button {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 10px;
    border: none;
    border-radius: 7px;
    background: #f3f4f6;
    color: #374151;
    font-size: 10px;
    cursor: pointer;
}

.view-button:hover {
    background: #111827;
    color: #ffffff;
}


/*
|--------------------------------------------------------------------------
| EMPTY
|--------------------------------------------------------------------------
*/

.empty-state {
    height: 220px;
    text-align: center;
}

.empty-state>* {
    display: block;
    margin: 5px auto;
}

.empty-state .anticon {
    color: #d1d5db;
    font-size: 28px;
}

.empty-state strong {
    color: #6b7280;
    font-size: 12px;
}

.empty-state span {
    color: #9ca3af;
    font-size: 10px;
}


/*
|--------------------------------------------------------------------------
| RESPONSIVE
|--------------------------------------------------------------------------
*/

@media (max-width: 900px) {

    .contact-messages-page {
        padding: 16px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .table-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .search-box {
        width: 100%;
    }

}
</style>