<?php
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

// ========== FETCH ABOUT PAGE CONTENT ==========
$about_result = $conn->query("SELECT * FROM about_page_content WHERE id = 1 AND status = 'active'");
$about_content = null;
if ($about_result && $about_result->num_rows > 0) {
    $about_content = $about_result->fetch_assoc();
}

// ========== FETCH FOUNDER INFORMATION ==========
$founder_result = $conn->query("SELECT * FROM organisation_founder WHERE id = 1 AND status = 'active'");
$founder = null;
if ($founder_result && $founder_result->num_rows > 0) {
    $founder = $founder_result->fetch_assoc();
}

// ========== FETCH CORE VALUES ==========
$values_result = $conn->query("SELECT * FROM core_values WHERE status = 'active' ORDER BY display_order ASC");
$core_values = [];
if ($values_result) {
    while ($row = $values_result->fetch_assoc()) {
        $core_values[] = $row;
    }
}

// ========== FETCH HISTORY TIMELINE AND GROUP BY YEAR ==========
$history_result = $conn->query("SELECT * FROM organisation_history WHERE status = 'active' ORDER BY year DESC, display_order ASC");
$history_items_grouped = [];
if ($history_result) {
    while ($row = $history_result->fetch_assoc()) {
        $year = $row['year'];
        if (!isset($history_items_grouped[$year])) {
            $history_items_grouped[$year] = [];
        }
        $history_items_grouped[$year][] = $row;
    }
}

// ========== FETCH PROGRAMS (Managed Separately) ==========
$programs = [];
$programs_result = $conn->query("SELECT id, title, short_description, image_path FROM programs WHERE status = 'active' ORDER BY display_order ASC");
if ($programs_result && $programs_result->num_rows > 0) {
    while ($row = $programs_result->fetch_assoc()) {
        $programs[] = $row;
    }
}

$conn->close();

// Helper function to get header background image
function getHeaderBgImage($about_content) {
    if ($about_content && !empty($about_content['header_bg_image'])) {
        return htmlspecialchars($about_content['header_bg_image']);
    }
    return 'images/header/about-bg.jpg';
}

// Helper function to get section title image or default icon
function getSectionTitleImage($about_content, $field, $defaultIcon) {
    if ($about_content && !empty($about_content[$field])) {
        return '<img src="' . htmlspecialchars($about_content[$field]) . '" alt="Section Icon" class="section-icon">';
    }
    return '<i class="' . $defaultIcon . '"></i>';
}

// Helper function to get page title
function getPageTitle($about_content) {
    if ($about_content && !empty($about_content['page_header_title'])) {
        return htmlspecialchars($about_content['page_header_title']);
    }
    return 'About Us';
}

// Helper function to get page subtitle
function getPageSubtitle($about_content) {
    if ($about_content && !empty($about_content['page_header_subtitle'])) {
        return htmlspecialchars($about_content['page_header_subtitle']);
    }
    return 'Discover who we are, what we stand for, and how we\'re making a difference in the lives of children and young mothers.';
}

include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle($about_content); ?> | Soka Toto Muda Initiative Trust</title>
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

        /* ========== PAGE HEADER WITH BACKGROUND IMAGE ========== */
        .page-header {
            position: relative;
            background-image: url('<?php echo getHeaderBgImage($about_content); ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 80px 0;
            text-align: center;
            isolation: isolate;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(11, 30, 62, 0.85) 0%, rgba(26, 58, 110, 0.85) 100%);
            z-index: -1;
        }

        .page-header h1 {
            font-size: clamp(1.8rem, 5vw, 2.8rem);
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            animation: fadeInDown 1s ease;
        }

        .page-header p {
            font-size: clamp(0.95rem, 3vw, 1.1rem);
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            animation: fadeInUp 1s ease 0.2s both;
        }

        /* ========== ANIMATION KEYFRAMES ========== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* ========== SECTION TITLE WITH IMAGE ========== */
        .section-title {
            font-size: clamp(1.6rem, 5vw, 2.2rem);
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
            color: #0B1E3E;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .section-title.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title .section-icon {
            width: 50px;
            height: 50px;
            object-fit: contain;
            vertical-align: middle;
        }

        .section-title i {
            font-size: 2.2rem;
            color: #fabc01;
        }

        .section-title span {
            border-bottom: 4px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        /* ========== SECTION COMMON ========== */
        .section {
            padding: 50px 0;
            scroll-margin-top: 80px;
        }

        .section-alt {
            background-color: #f8fafc;
        }

        /* ========== WHO WE ARE SECTION ========== */
        .who-we-are-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            align-items: flex-start;
            justify-content: space-between;
        }

        .who-text {
            flex: 1;
            min-width: 280px;
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.7s ease;
        }

        .who-text.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .who-text h3 {
            font-size: clamp(1.2rem, 4vw, 1.6rem);
            margin-bottom: 15px;
            color: #0B1E3E;
        }

        .who-text p {
            color: #334155;
            margin-bottom: 15px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .founder-card {
            flex: 1;
            min-width: 280px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px -12px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.5s ease;
            opacity: 0;
            transform: translateX(50px);
        }

        .founder-card.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .founder-card:hover {
            transform: translateY(-5px);
        }

        .founder-image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 30px 15px 30px;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .founder-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
            border: 3px solid #fabc01;
            box-shadow: 0 10px 25px -8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .founder-image:hover {
            transform: scale(1.03);
        }

        .founder-content {
            padding: 10px 25px 25px 25px;
            text-align: center;
        }

        .founder-content h3 {
            font-size: 1.5rem;
            color: #0B1E3E;
            margin-bottom: 5px;
        }

        .founder-title {
            color: #fabc01;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .founder-bio {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 15px;
        }

        .founder-quote {
            margin-top: 18px;
            padding-top: 15px;
            border-top: 2px solid #fabc01;
            font-style: italic;
            color: #0B1E3E;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* ========== MISSION & VISION SECTION ========== */
        .mission-vision-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .mv-card {
            background: white;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 5px 20px -8px rgba(0,0,0,0.08);
            transition: all 0.5s ease;
            border: 1px solid #e2e8f0;
            opacity: 0;
            transform: translateY(40px);
        }

        .mv-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .mv-card:nth-child(1) { transition-delay: 0.1s; }
        .mv-card:nth-child(2) { transition-delay: 0.2s; }

        .mv-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -12px rgba(0,0,0,0.12);
            border-color: #fabc01;
        }

        .mv-card i {
            font-size: 3rem;
            color: #fabc01;
            margin-bottom: 15px;
        }

        .mv-card h3 {
            font-size: 1.5rem;
            color: #0B1E3E;
            margin-bottom: 15px;
        }

        .mv-card p {
            color: #475569;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* ========== CORE VALUES SECTION ========== */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        .value-card {
            background: white;
            border-radius: 16px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 5px 20px -8px rgba(0,0,0,0.08);
            transition: all 0.5s ease;
            border: 1px solid #e2e8f0;
            opacity: 0;
            transform: translateY(40px);
        }

        .value-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -12px rgba(0,0,0,0.12);
            border-color: #fabc01;
        }

        .value-icon {
            width: 70px;
            height: 70px;
            background: #0B1E3E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .value-icon i {
            font-size: 2rem;
            color: #fabc01;
        }

        .value-card h3 {
            font-size: 1.3rem;
            margin-bottom: 12px;
            color: #0B1E3E;
        }

        .value-card p {
            color: #475569;
            line-height: 1.5;
            font-size: 0.9rem;
        }

        /* ========== OUR PROGRAMS SECTION ========== */
        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .program-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px -8px rgba(0,0,0,0.08);
            transition: all 0.5s ease;
            border: 1px solid #e2e8f0;
            opacity: 0;
            transform: translateY(40px);
        }

        .program-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .program-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px -12px rgba(0,0,0,0.12);
            border-color: #fabc01;
        }

        .program-img {
            height: 200px;
            background-size: cover;
            background-position: center;
            background-color: #94a3b8;
        }

        .program-content {
            padding: 20px;
        }

        .program-content h3 {
            font-size: 1.25rem;
            margin-bottom: 10px;
            color: #0B1E3E;
        }

        .program-content p {
            color: #475569;
            margin-bottom: 12px;
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .program-link {
            color: #0B1E3E;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.3s;
            font-size: 0.9rem;
        }

        .program-link:hover {
            gap: 10px;
            color: #fabc01;
        }

        /* ========== HISTORY TIMELINE SECTION - GROUPED BY YEAR ========== */
        .history-timeline {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
        }

        .history-timeline::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #fabc01, #0B1E3E);
        }

        .timeline-group {
            position: relative;
            margin-bottom: 35px;
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.6s ease;
        }

        .timeline-group.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .timeline-year-badge {
            min-width: 80px;
            background: #0B1E3E;
            color: #fabc01;
            padding: 10px 20px;
            border-radius: 40px;
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .timeline-content-group {
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .timeline-item {
            padding: 18px 25px;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .timeline-item:last-child {
            border-bottom: none;
        }

        .timeline-item:hover {
            background: #fef9e6;
            transform: translateX(5px);
        }

        .timeline-item p {
            color: #475569;
            line-height: 1.6;
            font-size: 0.95rem;
            margin: 0;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.5s ease;
        }

        .empty-state.animate {
            opacity: 1;
            transform: scale(1);
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #64748b;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

        /* Responsive */
        @media (max-width: 768px) {
            .section {
                padding: 35px 0;
            }
            .section-title {
                margin-bottom: 25px;
                gap: 10px;
            }
            .section-title .section-icon {
                width: 40px;
                height: 40px;
            }
            .page-header {
                padding: 50px 0;
                background-attachment: scroll;
            }
            .history-timeline::before {
                left: 20px;
            }
            .timeline-year-badge {
                min-width: 60px;
                font-size: 0.85rem;
                padding: 6px 12px;
            }
            .timeline-item {
                padding: 12px 18px;
            }
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            .values-grid, .programs-grid {
                gap: 20px;
            }
            .who-we-are-grid {
                gap: 30px;
                flex-direction: column;
            }
            .founder-image {
                width: 150px;
                height: 150px;
            }
            .mission-vision-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }
            .timeline-year-badge {
                min-width: 50px;
                font-size: 0.75rem;
                padding: 4px 10px;
            }
            .timeline-item {
                padding: 10px 15px;
            }
            .back-to-top {
                bottom: 15px;
                right: 15px;
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
            .value-icon {
                width: 60px;
                height: 60px;
            }
            .value-icon i {
                font-size: 1.6rem;
            }
            .value-card {
                padding: 20px 15px;
            }
            .program-content {
                padding: 16px;
            }
            .program-img {
                height: 170px;
            }
            .founder-image {
                width: 120px;
                height: 120px;
            }
            .section-title .section-icon {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>

<!-- ========== BACK TO TOP BUTTON ========== -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- ========== PAGE HEADER WITH BACKGROUND IMAGE ========== -->
<section class="page-header">
    <div class="container">
        <h1><?php echo getPageTitle($about_content); ?></h1>
        <p><?php echo getPageSubtitle($about_content); ?></p>
    </div>
</section>

<!-- ========== SECTION 1: WHO WE ARE ========== -->
<section id="who-we-are" class="section">
    <div class="container">
        <div class="section-title">
            <?php echo getSectionTitleImage($about_content, 'who_we_are_image', 'fas fa-users'); ?>
            <span>Who We Are</span>
        </div>
        <div class="who-we-are-grid">
            <div class="who-text">
                <h3><?php echo $about_content ? htmlspecialchars($about_content['who_we_are_title']) : 'Soka Toto Muda Initiative Trust'; ?></h3>
                <?php if ($about_content && !empty($about_content['who_we_are_content'])): ?>
                    <?php echo nl2br(htmlspecialchars_decode($about_content['who_we_are_content'])); ?>
                <?php else: ?>
                    <p>We are a Christian founded, non-profit making organization. We reach out to vulnerable and talented children through Sports (SOKA TOTO), Creative Arts (MUDA), Mentorship, Discipleship and Outreaches, Life skills, empowerment and psycho-Social Support to Young Mothers.</p>
                    <p>We believe that by touching the heart of a child, we have impacted the community at large. Moreover, by supporting the young mothers, their children will have wide range of opportunities when they grow up because of empowerment.</p>
                    <p>We therefore ensure that no child is left out/behind by exposing them to various activities which suitably fits their abilities. This will enable them become more disciplined, God fearing, cultivate critical thinking, problem solving and finally be holistically self reliant citizens in the society.</p>
                <?php endif; ?>
            </div>
            
            <?php if ($founder): ?>
            <div class="founder-card">
                <div class="founder-image-wrapper">
                    <div class="founder-image" style="background-image: url('<?php echo !empty($founder['image']) ? htmlspecialchars($founder['image']) : 'images/my-profile.jpeg'; ?>');"></div>
                </div>
                <div class="founder-content">
                    <h3><?php echo htmlspecialchars($founder['name']); ?></h3>
                    <div class="founder-title"><?php echo htmlspecialchars($founder['title']); ?></div>
                    <?php if (!empty($founder['bio'])): ?>
                        <div class="founder-bio"><?php echo nl2br(htmlspecialchars($founder['bio'])); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($founder['quote'])): ?>
                        <div class="founder-quote">
                            <i class="fas fa-quote-left" style="color: #fabc01; margin-right: 6px;"></i> "<?php echo nl2br(htmlspecialchars($founder['quote'])); ?>"
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="founder-card">
                <div class="founder-image-wrapper">
                    <div class="founder-image" style="background-image: url('images/my-profile.jpeg');"></div>
                </div>
                <div class="founder-content">
                    <h3>Jane Mwangi</h3>
                    <div class="founder-title">Founder & Executive Director</div>
                    <div class="founder-quote">
                        <i class="fas fa-quote-left" style="color: #fabc01; margin-right: 6px;"></i> "By touching a child's heart, we not only transform a community but also create a future filled with hope, opportunity, and empowered generations who will make a lasting impact."
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== SECTION 2: MISSION & VISION ========== -->
<?php if (($about_content && !empty($about_content['mission'])) || ($about_content && !empty($about_content['vision']))): ?>
<section id="mission-vision" class="section section-alt">
    <div class="container">
        <div class="section-title">
            <?php echo getSectionTitleImage($about_content, 'mission_vision_image', 'fas fa-bullseye'); ?>
            <span>Mission & Vision</span>
        </div>
        <div class="mission-vision-grid">
            <?php if ($about_content && !empty($about_content['mission'])): ?>
            <div class="mv-card">
                <i class="fas fa-bullseye"></i>
                <h3>Our Mission</h3>
                <p><?php echo nl2br(htmlspecialchars_decode($about_content['mission'])); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($about_content && !empty($about_content['vision'])): ?>
            <div class="mv-card">
                <i class="fas fa-eye"></i>
                <h3>Our Vision</h3>
                <p><?php echo nl2br(htmlspecialchars_decode($about_content['vision'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== SECTION 3: OUR CORE VALUES ========== -->
<section id="core-values" class="section">
    <div class="container">
        <div class="section-title">
            <?php echo getSectionTitleImage($about_content, 'core_values_image', 'fas fa-heart'); ?>
            <span>Our Core Values</span>
        </div>
        <?php if (count($core_values) > 0): ?>
        <div class="values-grid">
            <?php foreach ($core_values as $value): ?>
            <div class="value-card">
                <div class="value-icon">
                    <i class="<?php echo htmlspecialchars($value['icon']); ?>"></i>
                </div>
                <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                <p><?php echo htmlspecialchars($value['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-heart"></i>
            <p>Core values will be added soon.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== SECTION 4: OUR PROGRAMS ========== -->
<section id="our-programs" class="section section-alt">
    <div class="container">
        <div class="section-title">
            <?php echo getSectionTitleImage($about_content, 'our_programs_image', 'fas fa-chalkboard-user'); ?>
            <span>Our Programs</span>
        </div>
        <?php if (count($programs) > 0): ?>
        <div class="programs-grid">
            <?php foreach ($programs as $program): ?>
            <div class="program-card">
                <div class="program-img" style="background-image: url('<?php echo !empty($program['image_path']) ? htmlspecialchars($program['image_path']) : 'https://placehold.co/600x400/0B1E3E/fabc01?text=' . urlencode($program['title']); ?>');"></div>
                <div class="program-content">
                    <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr(strip_tags($program['short_description']), 0, 120)); ?>...</p>
                    <a href="program_detail.php?id=<?php echo $program['id']; ?>" class="program-link">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-chalkboard-user"></i>
            <p>Programs will be added soon.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== SECTION 5: OUR HISTORY - GROUPED BY YEAR ========== -->
<section id="history" class="section">
    <div class="container">
        <div class="section-title">
            <?php echo getSectionTitleImage($about_content, 'history_image', 'fas fa-history'); ?>
            <span>Our History</span>
        </div>
        <?php if (count($history_items_grouped) > 0): ?>
        <div class="history-timeline">
            <?php foreach ($history_items_grouped as $year => $items): ?>
            <div class="timeline-group">
                <div class="timeline-year-badge"><?php echo $year; ?></div>
                <div class="timeline-content-group">
                    <?php foreach ($items as $item): ?>
                    <div class="timeline-item">
                        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <p>History timeline will be added soon.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
    // ========== SCROLL ANIMATION LOGIC ==========
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const triggerPoint = windowHeight - 100;
        return rect.top <= triggerPoint && rect.bottom >= 100;
    }
    
    function animateOnScroll() {
        document.querySelectorAll('.section-title, .who-text, .founder-card, .mv-card, .value-card, .program-card, .timeline-group, .empty-state').forEach(el => {
            if (isElementInViewport(el) && !el.classList.contains('animate')) {
                el.classList.add('animate');
            }
        });
    }
    
    setTimeout(() => { animateOnScroll(); }, 100);
    window.addEventListener('scroll', animateOnScroll);
    window.addEventListener('resize', animateOnScroll);
    
    // ========== BACK TO TOP BUTTON LOGIC ==========
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>