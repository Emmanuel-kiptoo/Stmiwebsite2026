
<?php
// top-bar.php - Complete Top Bar with Navigation
?>
<!-- FIRST SUBSECTION: NAVY BLUE -->
<div class="subsection-navy">
    <div class="navy-content">
        <div class="org-name">
            SOKA TOTO MUDA INITIATIVE TRUST
        </div>
        <div class="follow-section">
            <span class="follow-text">Follow us:</span>
            <div class="social-icons">
                <a href="https://www.facebook.com/soka.toto.muda.initiative.2025/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/SokaToto7779?t=FVXzAOleEynT5uazhoyzYA&s=09" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/sokatotomudainitiativetrust?igsh=MTBrM2JiNmt5Zmo4ZA=='" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@stmitrustorg?_t=ZM-8wetN7kmz5S&_r=1" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="https://api.whatsapp.com/send/?phone=254728274304&text&type=phone_number&app_absent=0'" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="http://www.youtube.com/@SOKATOTOMUDAINITIATIVETRUST" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- SECOND SUBSECTION: WHITE - Responsive Block Layout -->
<div class="subsection-white">
    <div class="white-content">
        <div class="logo-left">
            <div class="logo-placeholder">
                <img src="images/logo/sk_logo.png" alt="Soka Toto Muda Initiative Trust Logo" class="site-logo">
            </div>
        </div>
        
        <div class="contact-row">
            <div class="contact-field">
                <div class="contact-label">
                    <i class="fas fa-phone-alt"></i> Call us
                </div>
                <div class="contact-value">+254 704118683</div>
            </div>
            
            <div class="contact-field">
                <div class="contact-label">
                    <i class="fas fa-envelope"></i> Email address
                </div>
                <div class="contact-value">
                    <a href="mailto:info@stmitrust.org">info@stmitrust.org</a>
                </div>
            </div>
            
            <div class="contact-field">
                <div class="contact-label">
                    <i class="fas fa-map-marker-alt"></i> Address
                </div>
                <div class="contact-value">105-00508 Nairobi, Kenya</div>
            </div>
        </div>
    </div>
</div>

<!-- THIRD SUBSECTION: GOLDEN - FIXED AT TOP -->
<div class="subsection-golden" id="stickyNav">
    <div class="golden-content">
        <ul class="nav-menu" id="navMenu">
            <li class="nav-item"><a href="index.php">Home</a></li>
            
            <!-- ABOUT DROPDOWN with correct links to about.php sections -->
            <li class="nav-item">
                <a href="about.php">About <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i></a>
                <div class="dropdown-content">
                    <a href="about.php#who-we-are"><i class="fas fa-users"></i> Who We Are</a>
                    <a href="about.php#core-values"><i class="fas fa-gem"></i> Our Core Values</a>
                    <a href="about.php#our-programs"><i class="fas fa-chalkboard-user"></i> Our Programs</a>
                    <a href="about.php#history"><i class="fas fa-landmark"></i> Organisational History</a>
                </div>
            </li>
            
            <!-- MEDIA DROPDOWN -->
            <li class="nav-item">
                <a href="#">Media <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i></a>
                <div class="dropdown-content">
                    <a href="moments.php"><i class="fas fa-image"></i> Gallery</a>
                    <a href="teams.php"><i class="fas fa-users"></i> Teams</a>
                    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
                    <a href="events.php"><i class="fas fa-calendar-alt"></i> Our Events</a>
                </div>
            </li>
            
            <li class="nav-item"><a href="contact.php">Contact</a></li>
            <li class="nav-item"><a href="blog.php">Blog</a></li>
            <li class="nav-item"><a href="donate.php" class="donate-link">Donate</a></li>
        </ul>
        
        <!-- Hamburger Menu Button -->
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</div>

<style>
    /* ========== FIRST SUBSECTION (NAVY BLUE) ========== */
    .subsection-navy {
        background-color: #0B1E3E;
        color: white;
        padding: 10px 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
    }
    .navy-content {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .org-name {
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .follow-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .follow-text {
        font-size: 0.75rem;
        font-weight: 500;
        color: #e0e7ff;
    }
    .social-icons {
        display: flex;
        gap: 12px;
    }
    .social-icons a {
        color: white;
        font-size: 0.9rem;
        transition: transform 0.2s, color 0.2s;
        display: inline-block;
    }
    .social-icons a:hover {
        color: #FFD966;
        transform: translateY(-2px);
    }

    /* ========== SECOND SUBSECTION (WHITE) - DESKTOP ========== */
    .subsection-white {
        background-color: white;
        padding: 15px 10%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .white-content {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .logo-left {
        flex-shrink: 0;
    }
    .site-logo {
        max-height: 70px;
        width: auto;
        display: block;
        transition: transform 0.3s ease;
    }
    .site-logo:hover {
        transform: scale(1.05);
    }
    .logo-placeholder {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }
    .contact-row {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        align-items: flex-start;
    }
    .contact-field {
        text-align: right;
    }
    .contact-label {
        font-weight: 700;
        font-size: 0.8rem;
        color: #0B1E3E;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .contact-label i {
        font-size: 0.85rem;
        width: 16px;
    }
    .contact-value {
        font-size: 0.8rem;
        color: #2c3e66;
        font-weight: 500;
    }
    .contact-value a {
        color: #2c3e66;
        text-decoration: none;
    }
    .contact-value a:hover {
        color: #0B1E3E;
        text-decoration: underline;
    }

    /* ========== SECOND SUBSECTION - RESPONSIVE BLOCK LAYOUT ========== */
    @media (max-width: 768px) {
        .subsection-white {
            padding: 15px 5%;
        }
        
        .white-content {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .logo-left {
            width: 100%;
            text-align: center;
        }
        
        .logo-placeholder {
            align-items: center;
            width: 100%;
        }
        
        .site-logo {
            max-height: 60px;
            margin: 0 auto;
        }
        
        .contact-row {
            flex-direction: column;
            align-items: center;
            gap: 25px;
            width: 100%;
        }
        
        .contact-field {
            text-align: center;
            width: 100%;
            padding: 0;
        }
        
        .contact-label {
            justify-content: center;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
        
        .contact-label i {
            font-size: 1rem;
            width: 20px;
        }
        
        .contact-value {
            font-size: 0.85rem;
            font-weight: 500;
        }
    }

    /* Extra small devices */
    @media (max-width: 480px) {
        .site-logo {
            max-height: 50px;
        }
        
        .contact-label {
            font-size: 0.8rem;
        }
        
        .contact-value {
            font-size: 0.8rem;
        }
        
        .contact-row {
            gap: 20px;
        }
    }

    /* ========== THIRD SUBSECTION (GOLDEN) - FIXED AT TOP ========== */
    .subsection-golden {
        background: linear-gradient(135deg, #fabc01 0%, #ffd700 100%);
        padding: 0;
        display: flex;
        justify-content: center;
        position: sticky;
        top: 0;
        z-index: 1000;
        width: 100%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .golden-content {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        padding: 0 5%;
    }

    /* Hamburger menu button - hidden on desktop */
    .menu-toggle {
        display: none;
        background: rgba(11, 30, 62, 0.9);
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        color: #fabc01;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1001;
        backdrop-filter: blur(5px);
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .menu-toggle:hover {
        background: #0B1E3E;
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Navigation Menu */
    .nav-menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        list-style: none;
        gap: 0;
        transition: all 0.3s ease;
        margin: 0 auto;
    }

    .nav-item {
        position: relative;
        display: flex;
        align-items: center;
    }

    .nav-item:not(:last-child)::after {
        content: "|";
        color: rgba(11, 30, 62, 0.4);
        font-size: 1.2rem;
        font-weight: 300;
        margin: 0 2px;
    }

    .nav-item > a {
        display: inline-block;
        padding: 16px 20px;
        font-weight: 600;
        color: #0B1E3E;
        text-decoration: none;
        font-size: 1rem;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .nav-item > a:hover {
        background-color: rgba(11, 30, 62, 0.1);
        transform: translateY(-2px);
    }

    .nav-item > a i.fa-chevron-down {
        transition: transform 0.3s ease;
    }

    .nav-item:hover > a i.fa-chevron-down {
        transform: rotate(180deg);
    }

    /* Donate button */
    .donate-link {
        background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
        color: #ffffff !important;
        border-radius: 50px;
        padding: 8px 28px !important;
        margin: 6px 8px;
        display: inline-block;
        font-weight: 700 !important;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(11, 30, 62, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .donate-link:hover {
        background: linear-gradient(135deg, #1a3a6e 0%, #0B1E3E 100%);
        transform: translateY(-2px);
        color: #fabc01 !important;
    }

    /* Dropdown menu */
    .dropdown-content {
        position: absolute;
        top: 100%;
        left: 0;
        background: linear-gradient(135deg, #fabc01 0%, #ffd700 100%);
        min-width: 240px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 100;
        padding: 10px 0;
        border-radius: 12px;
        margin-top: 5px;
    }

    .dropdown-content::before {
        content: '';
        position: absolute;
        top: -8px;
        left: 20px;
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #fabc01;
    }

    .dropdown-content a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: #0B1E3E;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .dropdown-content a i {
        width: 24px;
        font-size: 1rem;
        color: #0B1E3E;
    }

    .dropdown-content a:hover {
        background-color: rgba(11, 30, 62, 0.1);
        color: #0B1E3E;
        padding-left: 28px;
    }

    .nav-item:hover .dropdown-content {
        opacity: 1;
        visibility: visible;
        margin-top: 0;
    }

    /* ========== MOBILE MENU RESPONSIVE ========== */
    @media (max-width: 1024px) {
        .nav-item > a {
            padding: 14px 16px;
            font-size: 0.9rem;
        }
        .donate-link {
            padding: 6px 20px !important;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 768px) {
        /* First subsection mobile */
        .subsection-navy {
            padding: 10px 5%;
        }
        .navy-content {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }
        .org-name {
            font-size: 0.7rem;
        }
        .follow-section {
            justify-content: center;
        }
        .social-icons {
            gap: 10px;
        }
        .social-icons a {
            font-size: 0.85rem;
        }
        
        /* Third subsection mobile menu */
        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .nav-menu {
            display: none;
            flex-direction: column;
            width: 100%;
            background: linear-gradient(135deg, #fabc01 0%, #ffd700 100%);
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            padding: 15px 0;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border-radius: 0 0 20px 20px;
        }
        
        .nav-menu.active {
            display: flex;
        }
        
        .golden-content {
            justify-content: center;
            padding: 8px 5%;
        }
        
        .nav-item {
            width: 100%;
            margin: 2px 0;
        }
        
        .nav-item:not(:last-child)::after {
            content: none;
        }
        
        .nav-item > a {
            padding: 12px 20px;
            width: 90%;
            text-align: center;
            margin: 0 auto;
            border-radius: 50px;
            display: block;
        }
        
        .dropdown-content {
            position: static;
            opacity: 1;
            visibility: visible;
            display: none;
            margin-top: 8px;
            border-radius: 12px;
            width: 85%;
            margin-left: auto;
            margin-right: auto;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .dropdown-content::before {
            display: none;
        }
        
        .nav-item.active-mobile .dropdown-content {
            display: block;
        }
        
        .donate-link {
            margin: 8px auto;
            padding: 10px 25px !important;
        }
    }

    @media (max-width: 480px) {
        .site-logo {
            max-height: 50px;
        }
        .nav-item > a {
            padding: 10px 16px;
            font-size: 0.85rem;
        }
        .menu-toggle {
            width: 40px;
            height: 40px;
            font-size: 1.3rem;
        }
        .org-name {
            font-size: 0.65rem;
        }
        .follow-text {
            font-size: 0.65rem;
        }
    }
</style>

<script>
    // Hamburger menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Mobile dropdown handling
    const navItems = document.querySelectorAll('.nav-item');
    
    function handleMobileDropdown() {
        if (window.innerWidth <= 768) {
            navItems.forEach(item => {
                const link = item.querySelector('a');
                if (item.querySelector('.dropdown-content')) {
                    // Remove existing event listeners by cloning to avoid duplicates
                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);
                    newLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        navItems.forEach(otherItem => {
                            if (otherItem !== item && otherItem.classList.contains('active-mobile')) {
                                otherItem.classList.remove('active-mobile');
                            }
                        });
                        item.classList.toggle('active-mobile');
                    });
                }
            });
        } else {
            navItems.forEach(item => {
                item.classList.remove('active-mobile');
            });
        }
    }
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            const isClickInsideMenu = navMenu && navMenu.contains(event.target);
            const isClickOnToggle = menuToggle && menuToggle.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnToggle && navMenu && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                if (menuToggle) {
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }
    });
    
    // Initialize mobile dropdown handling
    handleMobileDropdown();
    
    // Re-run on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            if (navMenu) {
                navMenu.classList.remove('active');
            }
            if (menuToggle) {
                const icon = menuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
        handleMobileDropdown();
    });
</script>