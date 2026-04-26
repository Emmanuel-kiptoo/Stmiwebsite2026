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

// Get page settings
$settings_result = $conn->query("SELECT * FROM reports_page_settings WHERE id = 1");
$settings = [];
if ($settings_result && $settings_result->num_rows > 0) {
    $settings = $settings_result->fetch_assoc();
} else {
    $settings = ['page_title' => 'Reports', 'page_subtitle' => '', 'header_image' => ''];
}

// Get filter parameters
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : 0;

// Build query
$sql = "SELECT * FROM reports WHERE status = 'active'";
if ($selected_year > 0) {
    $sql .= " AND year = $selected_year";
}
$sql .= " ORDER BY year DESC, display_order ASC";
$reports = $conn->query($sql);

if (!$reports) {
    $reports = [];
    $reports_num_rows = 0;
} else {
    $reports_num_rows = $reports->num_rows;
}

// Get unique years for sidebar filter
$years = [];
$years_result = $conn->query("SELECT DISTINCT year FROM reports WHERE status = 'active' ORDER BY year DESC");
if ($years_result && $years_result->num_rows > 0) {
    while ($row = $years_result->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Get downloadable reports
$downloadable = $conn->query("SELECT id, title, file_path, year, report_date FROM reports WHERE status = 'active' AND file_path IS NOT NULL AND file_path != '' ORDER BY year DESC LIMIT 10");

include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['page_title'] ?? 'Reports'); ?> | STMI Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        
        /* Page Header */
        .page-header {
            position: relative;
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            color: white;
            padding: 40px 0 20px;
            text-align: left;
            background-size: cover;
            background-position: center;
        }
        .page-header h1 { font-size: 2.5rem; margin-bottom: 15px; }
        .page-header h1 i { color: #fabc01; margin-right: 10px; }
        .page-header p { font-size: 1.1rem; opacity: 0.9; max-width: 700px; margin: 0 auto; }
        
        /* Reports Layout */
        .reports-wrapper {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 40px;
            margin: 50px 0 60px;
        }
        
        /* Sidebar */
        .reports-sidebar {
            background: white;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #e2e8f0;
            height: fit-content;
            position: sticky;
            top: 100px;
        }
        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #fabc01;
        }
        .year-list {
            list-style: none;
            margin-bottom: 30px;
        }
        .year-list li { margin-bottom: 10px; }
        .year-list a {
            display: block;
            padding: 8px 15px;
            background: #f8fafc;
            border-radius: 10px;
            color: #0B1E3E;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }
        .year-list a:hover,
        .year-list a.active {
            background: #0B1E3E;
            color: #fabc01;
        }
        
        /* Downloadable Reports Section */
        .downloadable-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .download-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .download-item:last-child { border-bottom: none; }
        .download-item i { font-size: 1.5rem; color: #e11d48; }
        .download-info { flex: 1; }
        .download-title { font-weight: 600; color: #0B1E3E; font-size: 0.9rem; }
        .download-date { font-size: 0.7rem; color: #64748b; }
        .download-link { color: #fabc01; }
        .download-link:hover { color: #0B1E3E; }
        
        /* Reports Grid */
        .reports-grid {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .report-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            display: flex;
            flex-wrap: wrap;
        }
        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: #fabc01;
        }
        .report-image {
            width: 280px;
            flex-shrink: 0;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f8fafc;
            min-height: 200px;
        }
        .report-content {
            flex: 1;
            padding: 25px;
        }
        .report-year {
            display: inline-block;
            background: #fef9e6;
            color: #fabc01;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .report-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 10px;
        }
        .report-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .report-description {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #0B1E3E;
            font-weight: 600;
            text-decoration: none;
            transition: gap 0.3s;
        }
        .read-more:hover { gap: 12px; color: #fabc01; }
        
        .empty-state {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
        }
        
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
            .reports-wrapper { grid-template-columns: 1fr; }
            .reports-sidebar { position: static; }
        }
        @media (max-width: 768px) {
            .report-card { flex-direction: column; }
            .report-image { width: 100%; height: 200px; background-size: contain; }
            .page-header h1 { font-size: 1.8rem; }
            .page-header { background-attachment: scroll; }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn"><i class="fas fa-arrow-up"></i></a>

<!-- Page Header -->
<?php $header_image = !empty($settings['header_image']) ? $settings['header_image'] : ''; ?>
<section class="page-header" style="<?php echo !empty($header_image) ? "background-image: url('$header_image'); background-size: cover; background-position: center;" : ''; ?>">
    <div class="container">
        <h1> <?php echo htmlspecialchars($settings['page_title'] ?? 'Reports'); ?></h1>
        <p><?php echo htmlspecialchars($settings[''] ?? ''); ?></p>
    </div>
</section>

<div class="container">
    <div class="reports-wrapper">
        <!-- Sidebar -->
        <aside class="reports-sidebar">
            <h3 class="sidebar-title"><i class="fas fa-calendar-alt"></i> Browse by Year</h3>
            <ul class="year-list">
                <li><a href="reports.php" class="<?php echo $selected_year == 0 ? 'active' : ''; ?>">All Reports</a></li>
                <?php if (!empty($years)): ?>
                    <?php foreach ($years as $year): ?>
                    <li><a href="?year=<?php echo $year; ?>" class="<?php echo $selected_year == $year ? 'active' : ''; ?>"><?php echo $year; ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><span style="color:#94a3b8; font-size:0.85rem;">No reports available</span></li>
                <?php endif; ?>
            </ul>
            
            <div class="downloadable-section">
                <h3 class="sidebar-title"><i class="fas fa-download"></i> Downloadable Reports</h3>
                <?php if ($downloadable && $downloadable->num_rows > 0): ?>
                    <?php while ($item = $downloadable->fetch_assoc()): ?>
                    <div class="download-item">
                        <i class="fas fa-file-pdf"></i>
                        <div class="download-info">
                            <div class="download-title"><?php echo htmlspecialchars(substr($item['title'], 0, 40)); ?></div>
                            <div class="download-date"><?php echo $item['year']; ?></div>
                        </div>
                        <a href="<?php echo $item['file_path']; ?>" class="download-link" download><i class="fas fa-download"></i></a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #64748b; font-size: 0.85rem;">No downloadable reports available.</p>
                <?php endif; ?>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="reports-main">
            <?php if ($reports && $reports_num_rows > 0): ?>
                <div class="reports-grid">
                    <?php while ($report = $reports->fetch_assoc()): ?>
                    <div class="report-card">
                        <div class="report-image" style="background-image: url('<?php echo !empty($report['cover_image']) ? $report['cover_image'] : 'https://placehold.co/400x300/0B1E3E/fabc01?text=Report'; ?>');"></div>
                        <div class="report-content">
                            <span class="report-year"><i class="fas fa-calendar-alt"></i> <?php echo $report['year']; ?></span>
                            <h2 class="report-title"><?php echo htmlspecialchars($report['title']); ?></h2>
                            <?php if (!empty($report['subtitle'])): ?>
                                <div class="report-subtitle"><?php echo htmlspecialchars($report['subtitle']); ?></div>
                            <?php endif; ?>
                            <p class="report-description"><?php echo htmlspecialchars(substr(strip_tags($report['content']), 0, 150)); ?>...</p>
                            <a href="report-detail.php?id=<?php echo $report['id']; ?>" class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-file-alt" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
                    <h3>No Reports Found</h3>
                    <p>Check back later for new reports and publications.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const backToTopBtn = document.getElementById('backToTopBtn');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) backToTopBtn.classList.add('show');
        else backToTopBtn.classList.remove('show');
    });
    backToTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>