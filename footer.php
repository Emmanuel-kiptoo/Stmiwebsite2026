<!-- FOOTER SECTION -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-row">
            <!-- Column 1: Logo and About -->
            <div class="footer-col">
                <div class="footer-logo">
                    <img src="images/logo/sk_logo.png" alt="Soka Toto Muda Initiative Trust Logo" class="footer-logo-img">
                </div>
                <p class="footer-description">
                    Empowers children and teen mothers in informal settlements through talent exploration, psychosocial support, skills development, and mentorship, fostering resilience, independence, and a brighter future.
                </p>
                <div class="footer-social">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/soka.toto.muda.initiative.2025/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/SokaToto7779?t=FVXzAOleEynT5uazhoyzYA&s=09" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/sokatotomudainitiativetrust?igsh=MTBrM2JiNmt5Zmo4ZA=='" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.tiktok.com/@stmitrustorg?_t=ZM-8wetN7kmz5S&_r=1" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone=254728274304&text&type=phone_number&app_absent=0'" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="http://www.youtube.com/@SOKATOTOMUDAINITIATIVETRUST" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Address -->
            <div class="footer-col">
                <h4>Address</h4>
                <ul class="footer-contact">
                    <li><i class="fas fa-envelope"></i> <a href="mailto:info@stmitrust.org">info@stmitrust.org</a></li>
                    <li><i class="fas fa-phone-alt"></i> <a href="tel:+254704118683">+254 704 118683</a></li>
                    <li><i class="fas fa-map-marker-alt"></i> 105-00508 Nairobi</li>
                    <li><i class="fas fa-location-dot"></i> Kabiria, Kenya</li>
                </ul>
            </div>

            <!-- Column 3: Quick Links - No Lines -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="quick-links">
                    <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="about.php#our-programs"><i class="fas fa-chevron-right"></i> Our Programs</a></li>
                    <li><a href="moments.php"><i class="fas fa-chevron-right"></i> Media</a></li>
                    <li><a href="blog.php"><i class="fas fa-chevron-right"></i> Blogs</a></li>
                    <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                </ul>
            </div>

            <!-- Column 4: Get Involved -->
            <div class="footer-col">
                <h4>Get Involved</h4>
                <p class="get-involved-text">Join us in making a difference! Volunteer your time and skills to support our mission.</p>
                <a href="volunteer.php" class="volunteer-btn"> Volunteer <i class="fas fa-hand-peace"></i></a>
                <p class="donation-text">Your contribution, whether time or resources, helps transform lives in informal settlements.</p>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Soka Toto Muda Initiative Trust. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* ========== FOOTER STYLES ========== */
    .footer {
        background-color: #0B1E3E;
        color: #ffffff;
        padding: 60px 0 20px;
        margin-top: 60px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        margin-bottom: 40px;
    }

    /* Column 1 - Logo & About */
    .footer-logo {
        margin-bottom: 20px;
    }

    .footer-logo-img {
        max-height: 70px;
        width: auto;
        filter: brightness(0) invert(1);
    }

    .footer-description {
        font-size: 0.85rem;
        line-height: 1.6;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .footer-social h4 {
        font-size: 1rem;
        margin-bottom: 15px;
        color: #fabc01;
    }

    .social-links {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-links a:hover {
        background: #fabc01;
        color: #0B1E3E;
        transform: translateY(-3px);
    }

    /* Column Headers */
    .footer-col h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #fabc01;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-col h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: #fabc01;
        border-radius: 2px;
    }

    /* Column 2 - Contact */
    .footer-contact {
        list-style: none;
    }

    .footer-contact li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 0.85rem;
        color: #cbd5e1;
        word-break: break-word;
    }

    .footer-contact li i {
        width: 20px;
        color: #fabc01;
        font-size: 1rem;
    }

    .footer-contact li a {
        color: #cbd5e1;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-contact li a:hover {
        color: #fabc01;
    }

    /* ========== COLUMN 3 - QUICK LINKS (NO LINES) ========== */
    .quick-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .quick-links li {
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .quick-links li a {
        color: #cbd5e1;
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-links li a i {
        font-size: 0.7rem;
        color: #fabc01;
        transition: transform 0.3s ease;
    }

    .quick-links li a:hover {
        color: #fabc01;
        transform: translateX(8px);
    }

    .quick-links li a:hover i {
        transform: translateX(3px);
    }

    /* Column 4 - Get Involved */
    .get-involved-text {
        font-size: 0.85rem;
        line-height: 1.6;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .volunteer-btn {
        display: inline-block;
        background: linear-gradient(135deg, #fabc01 0%, #ffd700 100%);
        color: #0B1E3E;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .volunteer-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(250, 188, 1, 0.4);
    }

    .donation-text {
        font-size: 0.8rem;
        line-height: 1.5;
        color: #cbd5e1;
        font-style: italic;
    }

    /* Footer Bottom */
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .footer-bottom p {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .footer-bottom-links {
        display: flex;
        gap: 20px;
    }

    .footer-bottom-links a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.75rem;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: #fabc01;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .footer-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }
    }

    @media (max-width: 768px) {
        .quick-links li a {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .footer-row {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .footer {
            padding: 40px 0 20px;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .footer-bottom-links {
            justify-content: center;
        }

        .footer-col h4::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-col {
            text-align: center;
        }

        .footer-contact li {
            justify-content: center;
        }

        .social-links {
            justify-content: center;
        }

        .quick-links li a {
            justify-content: center;
        }
    }
</style>