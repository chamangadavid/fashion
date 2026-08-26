<script setup>
import MyFashionLayout from '@/Layouts/MyFashionLayout.vue';

import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import Swal from 'sweetalert2'

import {
    MailOutlined,
    PhoneOutlined,
    UserOutlined,
    EyeOutlined,
    CheckCircleOutlined,
    ClockCircleOutlined,
    DeleteOutlined,
    SendOutlined,
    CloseOutlined,
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
| VIEW MODAL
|--------------------------------------------------------------------------
*/

const viewModal = ref(false)

const selectedMessage = ref(null)


/*
|--------------------------------------------------------------------------
| REPLY MODAL
|--------------------------------------------------------------------------
*/

const replyModal = ref(false)

const replyMessage = ref('')

const replying = ref(false)


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

const deleting = ref(false)


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


/*
|--------------------------------------------------------------------------
| OPEN VIEW
|--------------------------------------------------------------------------
*/

const openView = (message) => {

    selectedMessage.value = message

    viewModal.value = true

}


/*
|--------------------------------------------------------------------------
| CLOSE VIEW
|--------------------------------------------------------------------------
*/

const closeView = () => {

    viewModal.value = false

    selectedMessage.value = null

}


/*
|--------------------------------------------------------------------------
| MARK AS READ
|--------------------------------------------------------------------------
*/

const markAsRead = (contact) => {

    if (contact.status === 'read') {
        return
    }

    router.patch(
        `/admin/contact-messages/${contact.id}/read`,
        {},
        {
            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({
                    icon: 'success',
                    title: 'Marked as Read',
                    text: 'The contact message has been marked as read.',
                    timer: 1800,
                    showConfirmButton: false,
                })

                fetchContacts()
            },

            onError: () => {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to mark the message as read.',
                })

            },
        }
    )
}

// const markAsRead = (message) => {

//     router.patch(
//         `/admin/contact-messages/${message.id}/read`,
//         {},
//         {
//             preserveScroll: true,
//         }
//     )

// }


/*
|--------------------------------------------------------------------------
| OPEN REPLY
|--------------------------------------------------------------------------
*/

const openReply = (message) => {

    selectedMessage.value = message

    replyMessage.value = ''

    replyModal.value = true

}


/*
|--------------------------------------------------------------------------
| CLOSE REPLY
|--------------------------------------------------------------------------
*/

const closeReply = () => {

    replyModal.value = false

    replyMessage.value = ''

    selectedMessage.value = null

}


/*
|--------------------------------------------------------------------------
| SEND REPLY
|--------------------------------------------------------------------------
*/

const sendReply = () => {

    if (!selectedMessage.value) {
        return
    }

    if (!replyMessage.value.trim()) {
        return
    }

    replying.value = true

    router.post(
        `/admin/contact-messages/${selectedMessage.value.id}/reply`,
        {
            reply: replyMessage.value,
        },
        {
            preserveScroll: true,

            onSuccess: () => {

                closeReply()

            },

            onFinish: () => {

                replying.value = false

            },

        }
    )

}


/*
|--------------------------------------------------------------------------
| DELETE MESSAGE
|--------------------------------------------------------------------------
*/

const deleteMessage = (contact) => {

    Swal.fire({
        title: 'Delete Message?',
        text: 'This contact message will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
    }).then((result) => {

        if (!result.isConfirmed) {
            return
        }

        router.delete(
            `/admin/contact-messages/${contact.id}`,
            {
                preserveScroll: true,

                onSuccess: () => {

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: 'Contact message deleted successfully.',
                        timer: 1800,
                        showConfirmButton: false,
                    })

                    fetchContacts()
                },

                onError: () => {

                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: 'Unable to delete the contact message.',
                    })

                },
            }
        )

    })
}

// const deleteMessage = (message) => {

//     if (
//         !confirm(
//             `Are you sure you want to delete the message from ${message.name}?`
//         )
//     ) {
//         return
//     }

//     deleting.value = true

//     router.delete(
//         `/admin/contact-messages/${message.id}`,
//         {
//             preserveScroll: true,

//             onFinish: () => {

//                 deleting.value = false

//             },

//         }
//     )

// }



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

                                    <!-- <button type="button" class="view-button">

                                        <EyeOutlined />

                                        View

                                    </button> -->

                                    <div class="action-buttons">

                                        <!-- VIEW -->

                                        <a-tooltip title="View Message">

                                            <button type="button" class="action-button view" @click="openView(message)">

                                                <EyeOutlined />

                                            </button>

                                        </a-tooltip>


                                        <!-- MARK AS READ -->

                                        <a-tooltip v-if="message.status === 'pending'" title="Mark as Read">

                                            <button type="button" class="action-button read"
                                                @click="markAsRead(message)">

                                                <CheckCircleOutlined />

                                            </button>

                                        </a-tooltip>


                                        <!-- REPLY -->

                                        <a-tooltip title="Reply">

                                            <button type="button" class="action-button reply"
                                                @click="openReply(message)">

                                                <SendOutlined />

                                            </button>

                                        </a-tooltip>


                                        <!-- DELETE -->

                                        <a-tooltip title="Delete">

                                            <button type="button" class="action-button delete"
                                                @click="deleteMessage(message)">

                                                <DeleteOutlined />

                                            </button>

                                        </a-tooltip>

                                    </div>


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

            <!-- =========================================================
     VIEW MESSAGE MODAL
========================================================== -->

            <a-modal v-model:open="viewModal" title="Contact Message" :footer="null" width="650px">

                <div v-if="selectedMessage" class="message-details">

                    <!-- SENDER -->

                    <div class="detail-section">

                        <h3>
                            Sender
                        </h3>

                        <div class="sender-large">

                            <div class="avatar-large">

                                <UserOutlined />

                            </div>

                            <div>

                                <strong>
                                    {{ selectedMessage.name }}
                                </strong>

                                <span>
                                    {{ selectedMessage.email }}
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- CONTACT -->

                    <div class="detail-grid">

                        <div>

                            <label>
                                Email
                            </label>

                            <span>
                                {{ selectedMessage.email }}
                            </span>

                        </div>


                        <div>

                            <label>
                                Phone
                            </label>

                            <span>
                                {{ selectedMessage.phone || '-' }}
                            </span>

                        </div>


                        <div>

                            <label>
                                Status
                            </label>

                            <span class="status" :class="statusClass(
                                selectedMessage.status
                            )
                                ">
                                {{ selectedMessage.status }}
                            </span>

                        </div>


                        <div>

                            <label>
                                Received
                            </label>

                            <span>
                                {{
                                    formatDate(
                                        selectedMessage.created_at
                                    )
                                }}
                            </span>

                        </div>

                    </div>


                    <!-- SUBJECT -->

                    <div class="detail-section">

                        <h3>
                            Subject
                        </h3>

                        <p class="message-subject">
                            {{ selectedMessage.subject }}
                        </p>

                    </div>


                    <!-- MESSAGE -->

                    <div class="detail-section">

                        <h3>
                            Message
                        </h3>

                        <div class="full-message">

                            {{ selectedMessage.message }}

                        </div>

                    </div>


                    <!-- REPLIED -->

                    <div v-if="selectedMessage.replied_at" class="detail-section">

                        <h3>
                            Replied At
                        </h3>

                        <p>
                            {{
                                formatDate(
                                    selectedMessage.replied_at
                                )
                            }}
                        </p>

                    </div>


                    <!-- ACTIONS -->

                    <div class="modal-actions">

                        <button v-if="selectedMessage.status === 'pending'" type="button" class="modal-button read"
                            @click="
                                markAsRead(selectedMessage);
                            closeView()
                                ">

                            <CheckCircleOutlined />

                            Mark as Read

                        </button>


                        <!-- <button
                type="button"
                class="modal-button reply"
                @click="
                    closeView();
                    openReply(selectedMessage)
                "
            >

                <SendOutlined />

                Reply

            </button> -->

                    </div>

                </div>

            </a-modal>

            <!-- =========================================================
     REPLY MODAL
========================================================== -->

            <a-modal v-model:open="replyModal" title="Reply to Contact Message" :confirm-loading="replying"
                ok-text="Send Reply" cancel-text="Cancel" @ok="sendReply" @cancel="closeReply" width="650px">

                <div v-if="selectedMessage" class="reply-form">

                    <!-- RECIPIENT -->

                    <div class="reply-recipient">

                        <MailOutlined />

                        <div>

                            <span>
                                Replying to
                            </span>

                            <strong>
                                {{ selectedMessage.name }}
                            </strong>

                            <small>
                                {{ selectedMessage.email }}
                            </small>

                        </div>

                    </div>


                    <!-- ORIGINAL SUBJECT -->

                    <div class="original-subject">

                        <strong>
                            Subject:
                        </strong>

                        {{ selectedMessage.subject }}

                    </div>


                    <!-- ORIGINAL MESSAGE -->

                    <div class="original-message">

                        <span>
                            Original Message
                        </span>

                        <p>
                            {{ selectedMessage.message }}
                        </p>

                    </div>


                    <!-- REPLY -->

                    <div class="reply-input">

                        <label>
                            Your Reply
                        </label>

                        <textarea v-model="replyMessage" rows="7" placeholder="Write your reply..."></textarea>

                    </div>

                </div>

            </a-modal>



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
| ACTION BUTTONS
|--------------------------------------------------------------------------
*/

.action-buttons {
    display: flex;
    align-items: center;
    gap: 5px;
}

.action-button {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.action-button.view {
    background: #f3f4f6;
    color: #374151;
}

.action-button.view:hover {
    background: #111827;
    color: #ffffff;
}

.action-button.read {
    background: #f0fdf4;
    color: #16a34a;
}

.action-button.read:hover {
    background: #16a34a;
    color: #ffffff;
}

.action-button.reply {
    background: #eff6ff;
    color: #2563eb;
}

.action-button.reply:hover {
    background: #2563eb;
    color: #ffffff;
}

.action-button.delete {
    background: #fef2f2;
    color: #dc2626;
}

.action-button.delete:hover {
    background: #dc2626;
    color: #ffffff;
}


/*
|--------------------------------------------------------------------------
| MESSAGE DETAILS
|--------------------------------------------------------------------------
*/

.message-details {
    padding-top: 5px;
}

.detail-section {
    margin-bottom: 20px;
}

.detail-section h3 {
    margin: 0 0 8px;
    color: #6b7280;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sender-large {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar-large {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f0fdfa;
    color: #0f766e;
    font-size: 18px;
}

.sender-large strong {
    display: block;
    color: #111827;
    font-size: 14px;
}

.sender-large span {
    display: block;
    margin-top: 3px;
    color: #9ca3af;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| DETAIL GRID
|--------------------------------------------------------------------------
*/

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 10px;
    background: #f9fafb;
}

.detail-grid>div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-grid label {
    color: #9ca3af;
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
}

.detail-grid span {
    color: #374151;
    font-size: 11px;
}


/*
|--------------------------------------------------------------------------
| SUBJECT
|--------------------------------------------------------------------------
*/

.message-subject {
    margin: 0;
    color: #111827;
    font-size: 14px;
    font-weight: 600;
}


/*
|--------------------------------------------------------------------------
| FULL MESSAGE
|--------------------------------------------------------------------------
*/

.full-message {
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
    background: #ffffff;
    color: #374151;
    font-size: 12px;
    line-height: 1.7;
    white-space: pre-wrap;
}


/*
|--------------------------------------------------------------------------
| MODAL ACTIONS
|--------------------------------------------------------------------------
*/

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.modal-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 13px;
    border: none;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
}

.modal-button.read {
    background: #f0fdf4;
    color: #16a34a;
}

.modal-button.reply {
    background: #111827;
    color: #ffffff;
}


/*
|--------------------------------------------------------------------------
| REPLY FORM
|--------------------------------------------------------------------------
*/

.reply-form {
    padding-top: 5px;
}

.reply-recipient {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 9px;
    background: #f9fafb;
    color: #2563eb;
}

.reply-recipient>.anticon {
    font-size: 18px;
}

.reply-recipient span,
.reply-recipient strong,
.reply-recipient small {
    display: block;
}

.reply-recipient span {
    color: #9ca3af;
    font-size: 9px;
}

.reply-recipient strong {
    margin-top: 2px;
    color: #111827;
    font-size: 12px;
}

.reply-recipient small {
    margin-top: 2px;
    color: #6b7280;
    font-size: 10px;
}


/*
|--------------------------------------------------------------------------
| ORIGINAL SUBJECT
|--------------------------------------------------------------------------
*/

.original-subject {
    margin-top: 15px;
    padding: 10px 12px;
    border-bottom: 1px solid #f0f0f0;
    color: #374151;
    font-size: 11px;
}

.original-subject strong {
    color: #9ca3af;
}


/*
|--------------------------------------------------------------------------
| ORIGINAL MESSAGE
|--------------------------------------------------------------------------
*/

.original-message {
    margin-top: 15px;
    padding: 12px;
    border-radius: 8px;
    background: #f9fafb;
}

.original-message>span {
    display: block;
    margin-bottom: 7px;
    color: #9ca3af;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
}

.original-message p {
    margin: 0;
    color: #6b7280;
    font-size: 11px;
    line-height: 1.6;
}


/*
|--------------------------------------------------------------------------
| REPLY INPUT
|--------------------------------------------------------------------------
*/

.reply-input {
    margin-top: 18px;
}

.reply-input label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 11px;
    font-weight: 600;
}

.reply-input textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 11px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    outline: none;
    resize: vertical;
    color: #111827;
    font-family: inherit;
    font-size: 11px;
    line-height: 1.6;
}

.reply-input textarea:focus {
    border-color: #9ca3af;
}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 600px) {

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-wrap: wrap;
    }

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