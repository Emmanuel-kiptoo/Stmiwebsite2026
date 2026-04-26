<?php
session_start();

$host = 'localhost';
$dbname = 'stmitrust2026';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$report = null;

if ($id > 0) {
    // Update view count
    $conn->query("UPDATE reports SET views = views + 1 WHERE id = $id");
    
    $result = $conn->query("SELECT * FROM reports WHERE id = $id AND status = 'active'");
    if ($result && $result->num_rows > 0) {
        $report = $result->fetch_assoc();
    }
}

if (!$report) {
    header('Location: reports.php');
    exit;
}

include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($report['title']); ?> | STMI Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        
        /* Page Header */
        .report-header {
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            color: white;
            padding: 60px 0 40px;
            text-align: center;
        }
        .report-header h1 { font-size: 2.2rem; margin-bottom: 15px; }
        .report-meta {
            display: flex;
            justify-content: center;
            gap: 25px;
            font-size: 0.9rem;
            opacity: 0.9;
            flex-wrap: wrap;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 40px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .back-link:hover { background: #fabc01; color: #0B1E3E; }
        
        /* Report Detail - Image and Content Side by Side */
        .report-detail {
            margin: 50px 0;
        }
        
        .report-detail-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 40px;
            align-items: start;
        }
        
        /* Cover Image Section - With Max Height and Width */
        .report-cover {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            text-align: center;
            position: sticky;
            top: 100px;
        }
        
        .report-cover img {
            max-width: 100%;
            max-height: 250px;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 12px;
            margin: 0 auto;
            display: block;
        }
        
        .download-btn {
            display: block;
            background: #0B1E3E;
            color: #fabc01;
            text-align: center;
            padding: 12px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .download-btn:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-2px);
        }
        
        /* Content Section */
        .report-content {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .report-subtitle {
            font-size: 1.1rem;
            color: #fabc01;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .report-content p {
            margin-bottom: 20px;
            line-height: 1.7;
            color: #334155;
        }
        
        .report-content h2, .report-content h3 {
            margin: 25px 0 15px;
            color: #0B1E3E;
        }
        
        /* Share Section */
        .share-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .share-title {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .share-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .share-btn {
            width: 38px;
            height: 38px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0B1E3E;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .share-btn:hover {
            background: #fabc01;
            transform: translateY(-2px);
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: #0B1E3E;
            color: #fabc01;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 1000;
        }
        .back-to-top.show { opacity: 1; visibility: visible; }
        .back-to-top:hover { background: #fabc01; color: #0B1E3E; transform: translateY(-3px); }
        
        @media (max-width: 992px) {
            .report-detail-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            .report-cover {
                position: static;
                max-width: 350px;
                margin: 0 auto;
            }
            .report-cover img {
                max-height: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .report-header h1 { font-size: 1.6rem; }
            .report-meta { flex-wrap: wrap; gap: 12px; }
            .report-content { padding: 25px; }
            .report-detail-grid { gap: 20px; }
            .report-cover img {
                max-height: 180px;
            }
        }
        
        @media (max-width: 480px) {
            .report-cover { padding: 15px; }
            .report-cover img {
                max-height: 150px;
            }
            .report-content { padding: 20px; }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn"><i class="fas fa-arrow-up"></i></a>

<!-- Page Header -->
<section class="report-header">
    <div class="container">
        <a href="reports.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Reports</a>
        <h1><?php echo htmlspecialchars($report['title']); ?></h1>
        <div class="report-meta">
            <span><i class="fas fa-calendar-alt"></i> <?php echo date('F Y', strtotime($report['report_date'] ?? $report['created_at'])); ?></span>
            <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($report['category'] ?? 'Report'); ?></span>
            <span><i class="fas fa-eye"></i> <?php echo number_format($report['views']); ?> views</span>
        </div>
    </div>
</section>

<div class="container">
    <div class="report-detail">
        <div class="report-detail-grid">
            <!-- Left Side: Cover Image with Size Limits -->
            <div class="report-cover">
                <?php if (!empty($report['cover_image'])): ?>
                    <img src="<?php echo $report['cover_image']; ?>" alt="<?php echo htmlspecialchars($report['title']); ?>">
                <?php else: ?>
                    <img src="https://placehold.co/300x200/0B1E3E/fabc01?text=Report" alt="Report Cover">
                <?php endif; ?>
                
                <?php if (!empty($report['file_path'])): ?>
                    <a href="<?php echo $report['file_path']; ?>" class="download-btn" download>
                        <i class="fas fa-download"></i> Download Full Report (PDF)
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Right Side: Content -->
            <div class="report-content">
                <?php if (!empty($report['subtitle'])): ?>
                    <div class="report-subtitle"><?php echo htmlspecialchars($report['subtitle']); ?></div>
                <?php endif; ?>
                <?php echo nl2br(htmlspecialchars_decode($report['content'])); ?>
                
                <!-- Share Section -->
                <div class="share-section">
                    <div class="share-title"><i class="fas fa-share-alt"></i> Share this Report</div>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($report['title']); ?>" target="_blank" class="share-btn"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&title=<?php echo urlencode($report['title']); ?>" target="_blank" class="share-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="mailto:?subject=<?php echo urlencode($report['title']); ?>&body=Check out this report: <?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-btn"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const btn = document.getElementById('backToTopBtn');
    window.addEventListener('scroll', () => { if (window.scrollY > 300) btn.classList.add('show'); else btn.classList.remove('show'); });
    btn.addEventListener('click', (e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
</script>

<?php include 'footer.php'; ?>
</body>
</html>