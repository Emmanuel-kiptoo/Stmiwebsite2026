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

// Get program ID from URL
$program_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$program = null;

// Fetch program details
if ($program_id > 0) {
    $sql = "SELECT * FROM programs WHERE id = $program_id AND status = 'active'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $program = $result->fetch_assoc();
    }
}

// If program not found, redirect to about page
if (!$program) {
    header('Location: about.php#our-programs');
    exit;
}

// Update view count
$conn->query("UPDATE programs SET views = views + 1 WHERE id = $program_id");

// Get related programs (other active programs excluding current)
$related_programs = [];
$related_sql = "SELECT id, title, short_description, image_path FROM programs WHERE status = 'active' AND id != $program_id ORDER BY display_order ASC LIMIT 3";
$related_result = $conn->query($related_sql);
if ($related_result) {
    while ($row = $related_result->fetch_assoc()) {
        $related_programs[] = $row;
    }
}

$conn->close();

// Helper function to get header image
function getProgramHeaderImage($program) {
    if (!empty($program['header_image'])) {
        return htmlspecialchars($program['header_image']);
    }
    // Fallback to program image or default gradient
    if (!empty($program['image_path'])) {
        return htmlspecialchars($program['image_path']);
    }
    return '';
}

// Include top bar
include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?php echo htmlspecialchars($program['title']); ?> | Soka Toto Muda Initiative Trust</title>
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
            background-color: #f8fafc;
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
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 80px 0;
            text-align: center;
            isolation: isolate;
        }
        
        /* Gradient overlay for header */
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
        
        /* If header image exists, use it */
        .page-header.has-image::before {
            background: linear-gradient(135deg, rgba(11, 30, 62, 0.75) 0%, rgba(26, 58, 110, 0.85) 100%);
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
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 15px 0;
            margin-bottom: 0;
        }

        .breadcrumb ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .breadcrumb li {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .breadcrumb li a {
            color: #0B1E3E;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb li a:hover {
            color: #fabc01;
        }

        .breadcrumb li:not(:last-child)::after {
            content: '/';
            color: #cbd5e1;
        }

        /* ========== PROGRAM DETAIL SECTION ========== */
        .program-detail {
            padding: 50px 0;
        }

        .program-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
        }

        .program-image {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 100px;
        }

        .program-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .program-info h2 {
            font-size: 2rem;
            color: #0B1E3E;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .program-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .meta-item i {
            color: #fabc01;
            width: 20px;
        }

        .program-description {
            color: #334155;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .program-description p {
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .program-features {
            background: #f1f5f9;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .program-features h3 {
            color: #0B1E3E;
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .program-features h3 i {
            color: #fabc01;
        }

        .program-features ul {
            list-style: none;
            padding: 0;
        }

        .program-features li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
            font-size: 0.95rem;
        }

        .program-features li i {
            color: #10b981;
            font-size: 0.8rem;
        }

        .program-cta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-donate {
            background: #0B1E3E;
            color: #fabc01;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-donate:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-3px);
        }

        .btn-contact {
            background: transparent;
            color: #0B1E3E;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid #0B1E3E;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-contact:hover {
            background: #0B1E3E;
            color: #fabc01;
            transform: translateY(-3px);
        }

        /* ========== RELATED PROGRAMS SECTION ========== */
        .related-programs {
            background: #f8fafc;
            padding: 60px 0;
        }

        .related-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .related-title h3 {
            font-size: 1.8rem;
            color: #0B1E3E;
            font-weight: 700;
        }

        .related-title span {
            border-bottom: 4px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .related-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            display: block;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            border-color: #fabc01;
        }

        .related-image {
            height: 180px;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
        }

        .related-content {
            padding: 20px;
        }

        .related-content h4 {
            font-size: 1.2rem;
            color: #0B1E3E;
            margin-bottom: 10px;
        }

        .related-content p {
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .read-more-link {
            color: #0B1E3E;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 12px;
            font-size: 0.85rem;
        }

        .read-more-link:hover {
            color: #fabc01;
            gap: 10px;
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
        @media (max-width: 992px) {
            .program-detail-grid {
                grid-template-columns: 1fr;
                gap: 35px;
            }
            .program-image {
                position: relative;
                top: 0;
                max-width: 500px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .program-info h2 {
                font-size: 1.6rem;
            }
            .program-meta {
                gap: 15px;
            }
            .related-grid {
                gap: 20px;
            }
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            .page-header {
                padding: 50px 0;
                background-attachment: scroll;
            }
        }

        @media (max-width: 480px) {
            .program-cta {
                flex-direction: column;
            }
            .btn-donate, .btn-contact {
                text-align: center;
                justify-content: center;
            }
            .related-content h4 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- Page Header with Background Image -->
<?php $headerImage = getProgramHeaderImage($program); ?>
<section class="page-header <?php echo !empty($headerImage) ? 'has-image' : ''; ?>" 
         style="<?php echo !empty($headerImage) ? 'background-image: url(' . $headerImage . ');' : 'background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);'; ?>">
    <div class="container">
        <h1><?php echo htmlspecialchars($program['title']); ?></h1>
        <p>Learn more about our program and how you can get involved</p>
    </div>
</section>

<!-- Breadcrumb -->
<div class="container">
    <div class="breadcrumb">
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="about.php#our-programs">Our Programs</a></li>
            <li><?php echo htmlspecialchars($program['title']); ?></li>
        </ul>
    </div>
</div>

<!-- Program Detail Section -->
<section class="program-detail">
    <div class="container">
        <div class="program-detail-grid">
            <div class="program-image">
                <img src="<?php echo !empty($program['image_path']) ? htmlspecialchars($program['image_path']) : 'https://placehold.co/600x400/0B1E3E/fabc01?text=' . urlencode($program['title']); ?>" 
                     alt="<?php echo htmlspecialchars($program['title']); ?>">
            </div>
            <div class="program-info">
                <h2><?php echo htmlspecialchars($program['title']); ?></h2>
                <div class="program-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Started: <?php echo date('F Y', strtotime($program['created_at'])); ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <span><?php echo number_format($program['views'] ?? 0); ?> views</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span><?php echo htmlspecialchars($program['category'] ?? 'Program'); ?></span>
                    </div>
                </div>
                <div class="program-description">
                    <?php echo nl2br(htmlspecialchars_decode($program['description'])); ?>
                </div>
                
                <?php if (!empty($program['features'])): ?>
                <div class="program-features">
                    <h3><i class="fas fa-check-circle"></i> Key Features</h3>
                    <ul>
                        <?php 
                        $features = explode("\n", $program['features']);
                        foreach ($features as $feature):
                            if (trim($feature)):
                        ?>
                        <li><i class="fas fa-check"></i> <?php echo htmlspecialchars(trim($feature)); ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="program-cta">
                    <a href="donate.php?program=<?php echo $program['id']; ?>" class="btn-donate">
                        <i class="fas fa-heart"></i> Support This Program
                    </a>
                    <a href="contact.php" class="btn-contact">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Programs Section -->
<?php if (count($related_programs) > 0): ?>
<section class="related-programs">
    <div class="container">
        <div class="related-title">
            <h3><span>Related Programs</span></h3>
        </div>
        <div class="related-grid">
            <?php foreach ($related_programs as $related): ?>
            <a href="program_detail.php?id=<?php echo $related['id']; ?>" class="related-card">
                <div class="related-image" style="background-image: url('<?php echo !empty($related['image_path']) ? htmlspecialchars($related['image_path']) : 'https://placehold.co/600x400/0B1E3E/fabc01?text=' . urlencode($related['title']); ?>');"></div>
                <div class="related-content">
                    <h4><?php echo htmlspecialchars($related['title']); ?></h4>
                    <p><?php echo htmlspecialchars(substr(strip_tags($related['short_description'] ?: ''), 0, 100)); ?>...</p>
                    <div class="read-more-link">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    // Back to Top Button
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