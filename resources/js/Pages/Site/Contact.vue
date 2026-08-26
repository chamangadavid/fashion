<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import Swal from 'sweetalert2'
import Header from "@/Components/Header.vue";
import AppFooter from "@/Components/AppFooter.vue";

const page = usePage()

const user = computed(() => page.props.auth?.user ?? null)

const form = useForm({
    name: user.value?.name ?? '',
    email: user.value?.email ?? '',
    phone: '',
    subject: '',
    message: '',
})

const submit = () => {
    form.post('/contact-us', {
        preserveScroll: true,

        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Message Sent',
                text: 'Thank you for contacting us. We will get back to you soon.',
                confirmButtonText: 'OK',
            })

            form.reset('phone', 'subject', 'message')
        },

        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Unable to Send',
                text: 'Please check the form and try again.',
                confirmButtonText: 'OK',
            })
        },
    })
}
</script>

<template>
    <Head title="Contact Us" />

    <div class="contact-page">
         <Header />

        <!-- HERO -->
        <section class="contact-hero">
            <div class="contact-hero-content">
                <span class="contact-label">
                    GET IN TOUCH
                </span>

                <h1>
                    Contact Us
                </h1>

                <p>
                    Have a question, need assistance, or want to know more
                    about our products? We would love to hear from you.
                </p>
            </div>
        </section>

        <!-- CONTENT -->
        <section class="contact-section">

            <div class="contact-container">

                <!-- CONTACT INFORMATION -->
                <div class="contact-information">

                    <span class="section-label">
                        CONTACT
                    </span>

                    <h2>
                        We'd love to hear from you.
                    </h2>

                    <p>
                        Send us a message and our team will get back to you
                        as soon as possible.
                    </p>

                    <div class="contact-details">

                        <div class="contact-detail">
                            <strong>
                                Email
                            </strong>

                            <span>
                                info@instyleasara.com
                            </span>
                        </div>

                        <div class="contact-detail">
                            <strong>
                                Phone
                            </strong>

                            <span>
                                +260 XXX XXX XXX
                            </span>
                        </div>

                        <div class="contact-detail">
                            <strong>
                                Address
                            </strong>

                            <span>
                                Lusaka, Zambia
                            </span>
                        </div>

                    </div>
                </div>

                <!-- FORM -->
                <div class="contact-form-wrapper">

                    <form
                        class="contact-form"
                        @submit.prevent="submit"
                    >

                        <!-- NAME -->
                        <div class="form-group">

                            <label for="name">
                                Name
                            </label>

                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Your name"
                            />

                            <span
                                v-if="form.errors.name"
                                class="form-error"
                            >
                                {{ form.errors.name }}
                            </span>

                        </div>

                        <!-- EMAIL -->
                        <div class="form-group">

                            <label for="email">
                                Email
                            </label>

                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="Your email address"
                            />

                            <span
                                v-if="form.errors.email"
                                class="form-error"
                            >
                                {{ form.errors.email }}
                            </span>

                        </div>

                        <!-- PHONE -->
                        <div class="form-group">

                            <label for="phone">
                                Phone
                                <small>(Optional)</small>
                            </label>

                            <input
                                id="phone"
                                v-model="form.phone"
                                type="text"
                                placeholder="Your phone number"
                            />

                            <span
                                v-if="form.errors.phone"
                                class="form-error"
                            >
                                {{ form.errors.phone }}
                            </span>

                        </div>

                        <!-- SUBJECT -->
                        <div class="form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <input
                                id="subject"
                                v-model="form.subject"
                                type="text"
                                placeholder="What can we help you with?"
                            />

                            <span
                                v-if="form.errors.subject"
                                class="form-error"
                            >
                                {{ form.errors.subject }}
                            </span>

                        </div>

                        <!-- MESSAGE -->
                        <div class="form-group">

                            <label for="message">
                                Message
                            </label>

                            <textarea
                                id="message"
                                v-model="form.message"
                                rows="6"
                                placeholder="Write your message..."
                            ></textarea>

                            <span
                                v-if="form.errors.message"
                                class="form-error"
                            >
                                {{ form.errors.message }}
                            </span>

                        </div>

                        <!-- SUBMIT -->
                        <button
                            type="submit"
                            class="submit-button"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">
                                Sending...
                            </span>

                            <span v-else>
                                Send Message
                            </span>
                        </button>

                    </form>

                </div>

            </div>

        </section>
   <AppFooter />
    </div>
</template>

<style scoped>

.contact-page {
    min-height: 100vh;
    background: #ffffff;
    color: #111827;
}

.contact-hero {
    padding: 90px 24px;
    background: #111111;
    color: #ffffff;
    text-align: center;
     background:
        linear-gradient(rgba(0, 0, 0, .45),
            rgba(0, 0, 0, .45)),
        url('/assets/contact.png');
}

.contact-hero-content {
    max-width: 700px;
    margin: 0 auto;
}

.contact-label,
.section-label {
    display: block;
    margin-bottom: 14px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
}

.contact-hero h1 {
    margin: 0;
    font-size: 46px;
    font-weight: 700;
}

.contact-hero p {
    max-width: 600px;
    margin: 18px auto 0;
    color: #d1d5db;
    font-size: 15px;
    line-height: 1.8;
}

.contact-section {
    padding: 70px 24px;
}

.contact-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    gap: 70px;
}

.contact-information h2 {
    margin: 0;
    font-size: 30px;
    line-height: 1.3;
}

.contact-information > p {
    margin-top: 16px;
    color: #6b7280;
    font-size: 14px;
    line-height: 1.8;
}

.contact-details {
    margin-top: 35px;
}

.contact-detail {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 18px 0;
    border-bottom: 1px solid #eeeeee;
}

.contact-detail strong {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.contact-detail span {
    color: #6b7280;
    font-size: 13px;
}

.contact-form-wrapper {
    padding: 30px;
    border: 1px solid #eeeeee;
    background: #ffffff;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-size: 12px;
    font-weight: 700;
}

.form-group label small {
    color: #9ca3af;
    font-weight: 400;
}

.form-group input,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 13px 14px;
    border: 1px solid #dddddd;
    border-radius: 0;
    outline: none;
    background: #ffffff;
    color: #111827;
    font-family: inherit;
    font-size: 13px;
    transition: border-color 0.2s ease;
}

.form-group textarea {
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #111111;
}

.form-error {
    color: #dc2626;
    font-size: 11px;
}

.submit-button {
    min-height: 48px;
    border: none;
    background: #111111;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.submit-button:hover {
    background: #333333;
}

.submit-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 800px) {

    .contact-hero {
        padding: 65px 20px;
    }

    .contact-hero h1 {
        font-size: 36px;
    }

    .contact-section {
        padding: 45px 20px;
    }

    .contact-container {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .contact-form-wrapper {
        padding: 22px;
    }
}

</style>