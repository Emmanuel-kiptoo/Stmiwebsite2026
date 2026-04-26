<?php
// index.php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'stmitrust2026';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Get homepage settings (for Know Us image)
$settings_result = $conn->query("SELECT know_us_image FROM homepage_settings WHERE id = 1");
$know_us_image = '';
if ($settings_result && $settings_result->num_rows > 0) {
    $row = $settings_result->fetch_assoc();
    $know_us_image = $row['know_us_image'] ?? '';
}

// Get active programs count for Know Us Better section
$programs_result = $conn->query("SELECT COUNT(*) as count FROM programs WHERE status = 'active'");
$programs_count = $programs_result->fetch_assoc()['count'] ?? 0;

// Get other stats
$communities_result = $conn->query("SELECT COUNT(*) as count FROM communities WHERE status = 'active'");
$communities_count = $communities_result->fetch_assoc()['count'] ?? 120;

$lives_result = $conn->query("SELECT SUM(impacted_lives) as total FROM impact_stats");
$lives_count = $lives_result->fetch_assoc()['total'] ?? 50000;

// Get all active partners
$partners_result = $conn->query("SELECT id, name, logo_path, website_url FROM partners WHERE status = 'active' ORDER BY display_order ASC");
$partners = [];
if ($partners_result) {
    while ($row = $partners_result->fetch_assoc()) {
        $partners[] = $row;
    }
}

$conn->close();

// Include top bar
include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Homepage | Soka Toto Muda Initiative Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            line-height: 1.5;
            overflow-x: hidden;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ========== SLIDER SECTION ========== */
        .slider-section {
            position: relative;
            width: 100%;
            overflow: hidden;
            background-color: #0f172a;
        }

        .slider-container {
            position: relative;
            width: 100%;
            height: 60vh;
            min-height: 450px;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.8s ease-in-out, visibility 0.8s;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding-bottom: 5rem;
            padding-left: 6%;
        }

        .slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 2;
        }

        .slide::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.1) 100%);
            z-index: 1;
        }

        .slide-content {
            position: relative;
            z-index: 3;
            max-width: 650px;
            text-align: left;
            color: white;
        }

        .slide-content h1 {
            font-size: 3.4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
            line-height: 1.2;
            opacity: 0;
            transform: translateX(-40px);
            transition: transform 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1), opacity 0.5s ease;
        }

        .slide-content .subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0;
            transform: translateY(30px);
            transition: transform 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1) 0.2s, opacity 0.5s ease 0.2s;
            font-weight: 400;
            max-width: 90%;
        }

        .slide-content .btn-group {
            opacity: 0;
            transform: translateY(30px);
            transition: transform 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1) 0.4s, opacity 0.5s ease 0.4s;
        }

        .slide.active .slide-content h1 {
            opacity: 1;
            transform: translateX(0);
        }

        .slide.active .slide-content .subtitle {
            opacity: 1;
            transform: translateY(0);
        }

        .slide.active .slide-content .btn-group {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-group {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 40px;
            transition: all 0.25s ease;
            font-size: 1rem;
            font-family: inherit;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #000283;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .btn-primary:hover {
            background-color: #060146;
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn-outline:hover {
            background-color: white;
            color: #0f172a;
            transform: translateY(-2px);
        }

        .slider-dots {
            position: absolute;
            bottom: 24px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 12px;
            z-index: 15;
        }

        .dot {
            width: 12px;
            height: 12px;
            background-color: rgba(255,255,255,0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dot.active-dot {
            background-color: #f5e509;
            width: 28px;
            border-radius: 20px;
        }

        /* ========== SECTION STYLES - REDUCED SPACING ========== */
        .section {
            padding: 50px 0;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
            color: #0B1E3E;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .section-title.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title span {
            border-bottom: 4px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        /* ========== KNOW US BETTER SECTION ========== */
        .know-us-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            align-items: center;
            justify-content: space-between;
        }

        .know-text {
            flex: 1;
            min-width: 260px;
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.7s ease;
        }

        .know-text.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .know-text h3 {
            font-size: 1.6rem;
            margin-bottom: 15px;
            color: #0B1E3E;
        }

        .know-text p {
            color: #334155;
            margin-bottom: 18px;
            font-size: 1rem;
            line-height: 1.6;
        }

        .know-stats {
            display: flex;
            gap: 32px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .stat {
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.5s ease;
        }

        .stat.animate {
            opacity: 1;
            transform: scale(1);
        }

        .stat:nth-child(1) { transition-delay: 0.1s; }
        .stat:nth-child(2) { transition-delay: 0.2s; }
        .stat:nth-child(3) { transition-delay: 0.3s; }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fabc01;
        }

        /* IMPROVED KNOW IMAGE STYLES - PROPER RESIZING */
        .know-img {
            flex: 1;
            min-width: 280px;
            max-width: 500px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.7s ease;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
        }

        .know-img.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .know-img img {
            width: 100%;
            height: auto;
            min-height: 250px;
            max-height: 350px;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform 0.5s ease;
        }

        .know-img:hover img {
            transform: scale(1.03);
        }

        .know-img-placeholder {
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            min-height: 250px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
            font-size: 1rem;
            flex-direction: column;
            gap: 15px;
            padding: 40px 20px;
        }

        .know-img-placeholder i {
            font-size: 4rem;
        }

        .know-img-placeholder p {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }

        /* ========== GET INVOLVED SECTION ========== */
        .get-involved-section {
            background-color: #f8fafc;
        }

        .involved-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .involved-card {
            background: white;
            border-radius: 20px;
            padding: 30px 25px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.6s ease;
        }

        .involved-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .involved-card:nth-child(1) { transition-delay: 0.1s; }
        .involved-card:nth-child(2) { transition-delay: 0.2s; }
        .involved-card:nth-child(3) { transition-delay: 0.3s; }
        .involved-card:nth-child(4) { transition-delay: 0.4s; }

        .involved-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            border-color: #fabc01;
        }

        .involved-icon {
            width: 70px;
            height: 70px;
            background: #fef9e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .involved-card:hover .involved-icon {
            background: #fabc01;
        }

        .involved-icon i {
            font-size: 2rem;
            color: #0B1E3E;
            transition: all 0.3s ease;
        }

        .involved-card:hover .involved-icon i {
            color: white;
        }

        .involved-card h3 {
            font-size: 1.3rem;
            color: #0B1E3E;
            margin-bottom: 12px;
        }

        .involved-card p {
            color: #475569;
            line-height: 1.5;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .involved-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: #0B1E3E;
            padding: 8px 22px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #0B1E3E;
            font-size: 0.9rem;
        }

        .involved-btn:hover {
            background: #0B1E3E;
            color: #fabc01;
            gap: 12px;
            transform: translateY(-2px);
        }

        /* ========== PARTNERSHIP SECTION - CENTERED ========== */
        .partners-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .partners-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 10px 0;
            cursor: grab;
            scroll-behavior: smooth;
            width: 100%;
        }

        .partners-scroll::-webkit-scrollbar {
            display: none;
        }

        .partners-scroll:active {
            cursor: grabbing;
        }

        .partner-logos {
            display: inline-flex;
            gap: 30px;
            padding: 0 20px;
            white-space: nowrap;
        }

        /* Center the partner items when they don't overflow */
        .partners-scroll:not(.overflowing) {
            display: flex;
            justify-content: center;
        }

        .partner-item {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: white;
            padding: 20px 35px;
            border-radius: 60px;
            transition: all 0.3s ease;
            min-width: 140px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.5s ease;
        }

        .partner-item.animate {
            opacity: 1;
            transform: scale(1);
        }

        .partner-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #fabc01;
        }

        .partner-item i {
            font-size: 2rem;
            color: #0B1E3E;
            margin-bottom: 8px;
            display: inline-block;
        }

        .partner-item span {
            display: block;
            font-weight: 600;
            color: #0B1E3E;
            font-size: 0.85rem;
        }

        .partner-logo-img {
            max-width: 80px;
            max-height: 50px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        /* Scroll Indicators - Centered */
        .scroll-indicators {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease 0.5s;
        }

        .scroll-indicators.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-indicator {
            width: 35px;
            height: 35px;
            background: #0B1E3E;
            color: #fabc01;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .scroll-indicator:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: scale(1.1);
        }

        .partner-cta {
            text-align: center;
            margin-top: 25px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease 0.6s;
            display: flex;
            justify-content: center;
        }

        .partner-cta.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-partner {
            background: #0B1E3E;
            color: #fabc01;
            padding: 10px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-partner:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-3px);
        }

        /* ========== BACK TO TOP BUTTON ========== */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background-color: #0B1E3E;
            color: #fabc01;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            border: none;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: #fabc01;
            color: #0B1E3E;
            transform: translateY(-3px);
        }

        /* ========== RESPONSIVE BREAKPOINTS ========== */
        
        /* Tablet (768px - 992px) */
        @media (max-width: 992px) {
            .know-img {
                max-width: 100%;
            }
            
            .know-img img {
                min-height: 220px;
                max-height: 300px;
            }
            
            .section {
                padding: 40px 0;
            }
            
            .section-title {
                font-size: 1.8rem;
                margin-bottom: 30px;
            }
        }

        /* Mobile (480px - 768px) */
        @media (max-width: 768px) {
            .slide-content h1 {
                font-size: 2rem;
            }
            
            .slide-content .subtitle {
                font-size: 1rem;
            }
            
            .slide-content {
                max-width: 90%;
            }
            
            .slide {
                padding-left: 5%;
                padding-bottom: 3rem;
            }
            
            .section {
                padding: 35px 0;
            }
            
            .section-title {
                font-size: 1.6rem;
                margin-bottom: 25px;
            }
            
            .know-us-grid {
                flex-direction: column;
            }
            
            .know-text {
                text-align: center;
            }
            
            .know-stats {
                justify-content: center;
            }
            
            .know-img {
                width: 100%;
                max-width: 100%;
                margin-top: 20px;
            }
            
            .know-img img {
                min-height: 200px;
                max-height: 280px;
            }
            
            .know-text h3 {
                font-size: 1.4rem;
                text-align: center;
            }
            
            .know-text p {
                text-align: center;
                font-size: 0.95rem;
            }
            
            .involved-grid {
                gap: 20px;
            }
            
            .involved-card {
                padding: 25px 20px;
            }
            
            .involved-card h3 {
                font-size: 1.2rem;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .partner-item {
                padding: 12px 20px;
                min-width: 120px;
            }
        }

        /* Small Mobile (up to 480px) */
        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }
            
            .section {
                padding: 30px 0;
            }
            
            .section-title {
                font-size: 1.4rem;
                margin-bottom: 20px;
            }
            
            .know-img img {
                min-height: 180px;
                max-height: 220px;
            }
            
            .know-img-placeholder i {
                font-size: 3rem;
            }
            
            .know-img-placeholder p {
                font-size: 0.85rem;
            }
            
            .know-stats {
                gap: 20px;
            }
            
            .stat-number {
                font-size: 1.3rem;
            }
            
            .stat div:last-child {
                font-size: 0.75rem;
            }
            
            .back-to-top {
                bottom: 15px;
                right: 15px;
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
            
            .partner-item {
                padding: 10px 18px;
                min-width: 100px;
            }
            
            .partner-item i {
                font-size: 1.5rem;
            }
            
            .involved-icon {
                width: 55px;
                height: 55px;
            }
            
            .involved-icon i {
                font-size: 1.5rem;
            }
            
            .btn, .involved-btn, .btn-partner {
                padding: 8px 18px;
                font-size: 0.8rem;
            }
        }

        /* Large Desktop (above 1280px) */
        @media (min-width: 1280px) {
            .know-img img {
                min-height: 280px;
                max-height: 380px;
            }
        }
    </style>
</head>
<body>

<!-- ========== SLIDER SECTION ========== -->
<section class="slider-section">
    <div class="slider-container">
        <div class="slide active" style="background-image: url('images/slider/mother1.jpeg');">
            <div class="slide-content">
                <h1>Soka Toto Muda Initiative Trust</h1>
                <div class="subtitle">Transforming Children's Lives through Sports, Creative Arts, and Psychosocial Support for Teen and young Mothers.</div>
                <div class="btn-group">
                    <a href="donate.php" class="btn btn-primary">SUPPORT TODAY</a>
                    <a href="about.php#our-programs" class="btn btn-outline">Our Programmes</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('images/slider/banner_1.jpg');">
            <div class="slide-content">
                <h1>Our Vision Statement</h1>
                <div class="subtitle">To empower children with opportunities to explore their talents, receive support with dignity and grow into confident, independent individuals.</div>
                <div class="btn-group">
                    <a href="donate.php" class="btn btn-primary">SUPPORT TODAY</a>
                    <a href="programs.php" class="btn btn-outline">Our Programmes</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('images/slider/child1.jpeg');">
            <div class="slide-content">
                <h1>Our Mission Statement</h1>
                <div class="subtitle">To holistically transform our children through talent exploration so that they are excellent, independent decision-makers and resourceful people in society.</div>
                <div class="btn-group">
                    <a href="donate.php" class="btn btn-primary">SUPPORT TODAY</a>
                    <a href="programs.php" class="btn btn-outline">Our Programmes</a>
                </div>
            </div>
        </div>
        <div class="slider-dots" id="sliderDots"></div>
    </div>
</section>

<!-- ========== KNOW US BETTER SECTION ========== -->
<section class="section">
    <div class="container">
        <div class="section-title"><span>Know Us Better</span></div>
        <div class="know-us-grid">
            <div class="know-text">
                <h3>We are driven by compassion & action</h3>
                <p>We are a Christian founded, non-profit making organization. We reach out to vulnerable and talented children through Sports (SOKA TOTO), Creative Arts (MUDA), Mentorship, Discipleship and Outreaches, Life skills, empowerment and psycho-Social Support to Young Mothers.</p>
                <p>We believe that by touching the heart of a child, we have impacted the community at large. Moreover, by supporting the young mothers, their children will have wide range of opportunities when they grow up because of empowerment.</p>
                <div class="know-stats">
                    <div class="stat"><div class="stat-number"><?php echo $communities_count; ?>+</div><div>Communities</div></div>
                    <div class="stat"><div class="stat-number"><?php echo number_format($lives_count); ?>+</div><div>Lives impacted</div></div>
                    <div class="stat"><div class="stat-number"><?php echo $programs_count; ?></div><div>Active programs</div></div>
                </div>
            </div>
            <div class="know-img">
                <?php if (!empty($know_us_image) && file_exists($know_us_image)): ?>
                    <img src="<?php echo htmlspecialchars($know_us_image); ?>" alt="Our Impact" loading="lazy">
                <?php else: ?>
                    <div class="know-img-placeholder">
                        <i class="fas fa-hands-helping"></i>
                        <p>Our Impact Image</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ========== GET INVOLVED SECTION ========== -->
<section class="get-involved-section section">
    <div class="container">
        <div class="section-title"><span>Get Involved</span></div>
        <div class="involved-grid">
            <!-- Donate Card -->
            <div class="involved-card">
                <div class="involved-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Donate</h3>
                <p>Your generous donation helps us provide essential services, programs, and support to children and young mothers in need.</p>
                <a href="donate.php" class="involved-btn">Support Us <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <!-- Volunteer Card -->
            <div class="involved-card">
                <div class="involved-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Volunteer</h3>
                <p>Join our team of dedicated volunteers and make a direct impact in the lives of children and families in our community.</p>
                <a href="volunteer.php" class="involved-btn">Join Us <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <!-- Partner Card -->
            <div class="involved-card">
                <div class="involved-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Partner With Us</h3>
                <p>Collaborate with us to create sustainable change. We welcome corporate partners, foundations, and organizations.</p>
                <a href="partner.php" class="involved-btn">Partner Now <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <!-- Sponsor Card -->
            <div class="involved-card">
                <div class="involved-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Sponsor a Program</h3>
                <p>Support a program that reach many children by providing education, mentorship and essential resources for lasting impact.</p>
                <a href="donate.php" class="involved-btn">Sponsor Today <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- ========== PARTNERSHIP SECTION ========== -->
<section class="section">
    <div class="container">
        <div class="section-title"><span>Our Partners</span></div>
        
        <div class="partners-wrapper">
            <div class="partners-scroll" id="partnersScroll">
                <div class="partner-logos" id="partnerLogos">
                    <?php if (count($partners) > 0): ?>
                        <?php foreach ($partners as $partner): ?>
                        <div class="partner-item">
                            <?php if (!empty($partner['logo_path'])): ?>
                                <img src="<?php echo htmlspecialchars($partner['logo_path']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>" class="partner-logo-img">
                            <?php else: ?>
                                <i class="fas fa-handshake"></i>
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($partner['name']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="partner-item"><i class="fas fa-building"></i><span>Global Aid</span></div>
                        <div class="partner-item"><i class="fas fa-hand-holding-heart"></i><span>Hope Foundation</span></div>
                        <div class="partner-item"><i class="fas fa-chalkboard-user"></i><span>EduAction</span></div>
                        <div class="partner-item"><i class="fas fa-leaf"></i><span>GreenFuture</span></div>
                        <div class="partner-item"><i class="fas fa-handshake"></i><span>UN Volunteers</span></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="scroll-indicators" id="scrollIndicators" style="display: none;">
                <button class="scroll-indicator" id="scrollLeftBtn"><i class="fas fa-chevron-left"></i></button>
                <button class="scroll-indicator" id="scrollRightBtn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        
        <div class="partner-cta">
            <a href="partner.php" class="btn-partner">Become a partner →</a>
        </div>
    </div>
</section>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>

<script>
    // ========== SLIDER LOGIC ==========
    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.getElementById('sliderDots');
    let currentIndex = 0;
    let slideInterval;

    function createDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        slides.forEach((_, idx) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (idx === currentIndex) dot.classList.add('active-dot');
            dot.addEventListener('click', () => goToSlide(idx));
            dotsContainer.appendChild(dot);
        });
    }

    function updateDots() {
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, idx) => {
            if (idx === currentIndex) {
                dot.classList.add('active-dot');
            } else {
                dot.classList.remove('active-dot');
            }
        });
    }

    function goToSlide(index) {
        slides[currentIndex].classList.remove('active');
        currentIndex = (index + slides.length) % slides.length;
        slides[currentIndex].classList.add('active');
        updateDots();
    }

    function nextSlide() {
        slides[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % slides.length;
        slides[currentIndex].classList.add('active');
        updateDots();
    }

    function startAutoplay() {
        if (slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(() => {
            nextSlide();
        }, 5500);
    }

    createDots();
    startAutoplay();

    const sliderContainer = document.querySelector('.slider-container');
    let touchStartX = 0, touchEndX = 0;
    
    if (sliderContainer) {
        sliderContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        sliderContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 50) {
                nextSlide();
            }
            if (touchEndX > touchStartX + 50) {
                slides[currentIndex].classList.remove('active');
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                slides[currentIndex].classList.add('active');
                updateDots();
            }
        });
    }

    // ========== SCROLL ANIMATION LOGIC ==========
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        return rect.top <= windowHeight - 100 && rect.bottom >= 100;
    }

    function animateOnScroll() {
        // Animate section titles
        document.querySelectorAll('.section-title').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate know-text
        document.querySelectorAll('.know-text').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate know-img
        document.querySelectorAll('.know-img').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate stats
        document.querySelectorAll('.stat').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate involved cards
        document.querySelectorAll('.involved-card').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate partner items
        document.querySelectorAll('.partner-item').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate scroll indicators
        document.querySelectorAll('.scroll-indicators').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
        
        // Animate partner CTA
        document.querySelectorAll('.partner-cta').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
    }

    // Initial check on load
    setTimeout(() => {
        animateOnScroll();
    }, 300);
    
    // Check on scroll
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('resize', animateOnScroll);

    // ========== PARTNERS SCROLL LOGIC - CENTERED ==========
    const partnersScroll = document.getElementById('partnersScroll');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');
    const scrollIndicators = document.getElementById('scrollIndicators');
    
    if (partnersScroll) {
        function checkScrollNeeded() {
            if (partnersScroll.scrollWidth > partnersScroll.clientWidth) {
                scrollIndicators.style.display = 'flex';
                partnersScroll.classList.add('overflowing');
                partnersScroll.style.justifyContent = 'flex-start';
            } else {
                scrollIndicators.style.display = 'none';
                partnersScroll.classList.remove('overflowing');
                partnersScroll.style.justifyContent = 'center';
            }
        }
        
        let autoScrollInterval;
        let isDragging = false;
        let startX, scrollLeft;
        
        function startAutoScroll() {
            if (autoScrollInterval) clearInterval(autoScrollInterval);
            if (partnersScroll.scrollWidth > partnersScroll.clientWidth) {
                autoScrollInterval = setInterval(() => {
                    if (!isDragging) {
                        if (partnersScroll.scrollLeft + partnersScroll.clientWidth >= partnersScroll.scrollWidth - 2) {
                            partnersScroll.scrollLeft = 0;
                        } else {
                            partnersScroll.scrollLeft += 1;
                        }
                    }
                }, 30);
            }
        }
        
        function stopAutoScroll() {
            if (autoScrollInterval) clearInterval(autoScrollInterval);
        }
        
        // Mouse/Touch drag scrolling
        partnersScroll.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - partnersScroll.offsetLeft;
            scrollLeft = partnersScroll.scrollLeft;
            stopAutoScroll();
        });
        
        partnersScroll.addEventListener('mouseleave', () => {
            isDragging = false;
            startAutoScroll();
        });
        
        partnersScroll.addEventListener('mouseup', () => {
            isDragging = false;
            startAutoScroll();
        });
        
        partnersScroll.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - partnersScroll.offsetLeft;
            const walk = (x - startX) * 2;
            partnersScroll.scrollLeft = scrollLeft - walk;
        });
        
        // Touch events for mobile
        partnersScroll.addEventListener('touchstart', (e) => {
            isDragging = true;
            startX = e.touches[0].pageX - partnersScroll.offsetLeft;
            scrollLeft = partnersScroll.scrollLeft;
            stopAutoScroll();
        });
        
        partnersScroll.addEventListener('touchend', () => {
            isDragging = false;
            startAutoScroll();
        });
        
        partnersScroll.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const x = e.touches[0].pageX - partnersScroll.offsetLeft;
            const walk = (x - startX) * 2;
            partnersScroll.scrollLeft = scrollLeft - walk;
        });
        
        if (scrollLeftBtn) {
            scrollLeftBtn.addEventListener('click', () => {
                partnersScroll.scrollLeft -= 300;
                stopAutoScroll();
                setTimeout(startAutoScroll, 2000);
            });
        }
        
        if (scrollRightBtn) {
            scrollRightBtn.addEventListener('click', () => {
                partnersScroll.scrollLeft += 300;
                stopAutoScroll();
                setTimeout(startAutoScroll, 2000);
            });
        }
        
        checkScrollNeeded();
        window.addEventListener('resize', checkScrollNeeded);
        
        setTimeout(() => {
            if (partnersScroll.scrollWidth > partnersScroll.clientWidth) {
                startAutoScroll();
            }
        }, 1000);
        
        partnersScroll.addEventListener('mouseenter', stopAutoScroll);
        partnersScroll.addEventListener('mouseleave', startAutoScroll);
    }

    // ========== BACK TO TOP BUTTON ==========
    const backToTopBtn = document.getElementById('backToTopBtn');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });

    backToTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>