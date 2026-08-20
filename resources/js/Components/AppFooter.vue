```vue
<script setup>
import { Link } from "@inertiajs/vue3";
</script>


<template>

    <footer class="site-footer">

        <!-- =========================================
             MAIN FOOTER
        ========================================== -->

        <div class="footer-main">

            <div class="footer-container">


                <!-- =====================================
                     NEWSLETTER
                ====================================== -->

                <div class="newsletter-box">

                    <h3 class="newsletter-title">
                        Join Our Fashion Circle – Get Style
                        Tips & 10% Off Your First Order
                    </h3>


                    <form
                        class="newsletter-form"
                        @submit.prevent
                    >

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            class="newsletter-input name-input"
                        />


                        <label class="form-label">
                            Email *
                        </label>

                        <div class="email-row">

                            <input
                                type="email"
                                class="newsletter-input email-input"
                            />

                            <button
                                type="submit"
                                class="join-button"
                            >
                                Join
                            </button>

                        </div>


                        <label class="subscribe-option">

                            <input
                                type="checkbox"
                            />

                            <span>
                                I want to subscribe to your
                                mailing list.
                            </span>

                        </label>

                    </form>

                </div>


                <!-- =====================================
                     NAVIGATION
                ====================================== -->

                <div class="footer-column navigation-column">

                    <h3 class="footer-heading">
                        Navigation
                    </h3>

                    <nav class="footer-links">

                        <Link href="/about">
                            About
                        </Link>

                        <Link href="/shop">
                            Shop
                        </Link>

                        <Link href="/services">
                            Services
                        </Link>

                        <Link href="/contact">
                            Contact
                        </Link>

                        <Link href="/privacy-policy">
                            Privacy Policy
                        </Link>

                    </nav>

                </div>


                <!-- =====================================
                     CONTACT
                ====================================== -->

                <div class="footer-column contact-column">

                    <h3 class="footer-heading">
                        Contact
                    </h3>


                    <div class="contact-item">

                        <span class="contact-icon">
                            ☎
                        </span>

                        <div>

                            <strong>
                                Contact Us
                            </strong>

                            <span>
                                +234-815-554-9606
                            </span>

                        </div>

                    </div>


                    <div class="contact-item">

                        <span class="contact-icon">
                            💬
                        </span>

                        <div>

                            <strong>
                                Mail Us
                            </strong>

                            <span>
                                Seraphinestyles@gmail.com
                            </span>

                        </div>

                    </div>

                </div>


                <!-- =====================================
                     SOCIALS
                ====================================== -->

                <div class="footer-column socials-column">

                    <h3 class="footer-heading">
                        Socials
                    </h3>


                    <div class="social-links">

                        <a
                            href="#"
                            aria-label="Instagram"
                        >
                            ◎
                        </a>

                        <a
                            href="#"
                            aria-label="Facebook"
                        >
                            f
                        </a>

                        <a
                            href="#"
                            aria-label="Twitter"
                        >
                            ♥
                        </a>

                        <a
                            href="#"
                            aria-label="LinkedIn"
                        >
                            in
                        </a>

                        <a
                            href="#"
                            aria-label="TikTok"
                        >
                            ♪
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- =========================================
             COPYRIGHT
        ========================================== -->

        <div class="footer-bottom">

            <div class="footer-bottom-content">

                <span>
                    © 2025 - All rights reserved
                </span>

                <span>
                    Powered by : Seraphine Styles
                </span>

                <span>
                    Designed by Faves_Code.
                </span>

            </div>

        </div>

    </footer>

</template>


<style scoped>

/* =========================================
   FOOTER
========================================= */

.site-footer {

    width: 100%;

    background: #e6dac7;

    color: #075d5d;

    border-top: 5px solid #064848;

}


/* =========================================
   MAIN FOOTER
========================================= */

.footer-main {

    width: 100%;

    padding: 43px 0 27px;

}


/* =========================================
   CONTAINER
========================================= */

.footer-container {

    width: min(930px, calc(100% - 50px));

    margin: 0 auto;

    display: grid;

    grid-template-columns:
        1.35fr
        0.8fr
        1fr
        0.55fr;

    column-gap: 48px;

    align-items: start;

}


/* =========================================
   NEWSLETTER
========================================= */

.newsletter-box {

    width: 100%;

    padding: 23px 21px;

    border: 1px solid #087070;

    border-radius: 28px;

    box-sizing: border-box;

}


.newsletter-title {

    margin: 0 0 14px;

    color: #075d5d;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 13px;

    line-height: 1.45;

    font-weight: 700;

}


.newsletter-form {

    width: 100%;

}


.form-label {

    display: block;

    margin-bottom: 5px;

    color: #075d5d;

    font-size: 10px;

}


.newsletter-input {

    height: 37px;

    box-sizing: border-box;

    border: 1px solid #087070;

    border-radius: 14px;

    outline: none;

    background: transparent;

    color: #333333;

    font-size: 11px;

}


.newsletter-input:focus {

    box-shadow: 0 0 0 2px rgba(7, 93, 93, 0.1);

}


.name-input {

    width: 100%;

    margin-bottom: 12px;

}


.email-row {

    display: flex;

    gap: 7px;

}


.email-input {

    width: calc(100% - 54px);

}


.join-button {

    width: 50px;

    height: 37px;

    border: none;

    border-radius: 18px;

    background: #075d5d;

    color: #ffffff;

    font-size: 10px;

    font-weight: 700;

    cursor: pointer;

}


.subscribe-option {

    margin-top: 15px;

    display: flex;

    align-items: flex-start;

    gap: 8px;

    color: #075d5d;

    font-size: 10px;

    line-height: 1.4;

}


.subscribe-option input {

    width: 16px;

    height: 16px;

    flex: 0 0 16px;

    margin: 0;

    accent-color: #075d5d;

}


/* =========================================
   FOOTER COLUMNS
========================================= */

.footer-column {

    min-height: 170px;

    padding-left: 38px;

    border-left: 1px dotted #075d5d;

}


.footer-heading {

    margin: 0 0 20px;

    color: #075d5d;

    font-family: Georgia, "Times New Roman", serif;

    font-size: 16px;

    line-height: 1.2;

    font-weight: 700;

}


/* =========================================
   NAVIGATION
========================================= */

.footer-links {

    display: flex;

    flex-direction: column;

    gap: 12px;

}


.footer-links a {

    color: #77716a;

    font-size: 11px;

    text-decoration: none;

    transition:
        color 0.2s ease;

}


.footer-links a:hover {

    color: #075d5d;

}


/* =========================================
   CONTACT
========================================= */

.contact-item {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 23px;

}


.contact-icon {

    width: 28px;

    flex: 0 0 28px;

    color: #075d5d;

    font-size: 25px;

    line-height: 1;

}


.contact-item div {

    display: flex;

    flex-direction: column;

    gap: 2px;

}


.contact-item strong {

    color: #77716a;

    font-size: 11px;

    font-weight: 700;

}


.contact-item span {

    color: #77716a;

    font-size: 9px;

}


/* =========================================
   SOCIALS
========================================= */

.social-links {

    display: flex;

    flex-direction: column;

    gap: 13px;

}


.social-links a {

    color: #8c8a87;

    font-size: 17px;

    line-height: 1;

    text-decoration: none;

    transition:
        color 0.2s ease,
        transform 0.2s ease;

}


.social-links a:hover {

    color: #075d5d;

    transform: translateX(2px);

}


/* =========================================
   COPYRIGHT
========================================= */

.footer-bottom {

    width: 100%;

    border-top: 1px dotted #075d5d;

    padding: 17px 15px;

    box-sizing: border-box;

}


.footer-bottom-content {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-wrap: wrap;

    gap: 5px 8px;

    color: #075d5d;

    font-size: 9px;

    text-align: center;

}


/* =========================================
   TABLET
========================================= */

@media (max-width: 900px) {

    .footer-container {

        grid-template-columns:
            1.2fr 0.8fr;

        gap: 30px;

    }


    .footer-column {

        padding-left: 25px;

    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 600px) {

    .footer-main {

        padding: 30px 0 25px;

    }


    .footer-container {

        width: calc(100% - 30px);

        grid-template-columns: 1fr;

        gap: 28px;

    }


    .newsletter-box {

        padding: 20px;

    }


    .footer-column {

        min-height: auto;

        padding-left: 0;

        padding-top: 25px;

        border-left: none;

        border-top: 1px dotted #075d5d;

    }


    .footer-heading {

        margin-bottom: 14px;

    }


    .social-links {

        flex-direction: row;

        gap: 20px;

    }


    .footer-bottom-content {

        flex-direction: column;

        gap: 4px;

    }

}

</style>
```
