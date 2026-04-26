<?php
// Start session
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

// Set charset
$conn->set_charset("utf8mb4");

// Get page settings (for header image)
$settings_result = $conn->query("SELECT * FROM teams_page_settings WHERE id = 1");
$settings = $settings_result->fetch_assoc();

/**
 * Fetch team members from database with optional department filter
 */
function getTeamMembers($conn, $department = null) {
    $sql = "SELECT * FROM teams WHERE status = 'active'";
    
    if ($department && $department !== 'all') {
        $sql .= " AND department = '" . $conn->real_escape_string($department) . "'";
    }
    
    $sql .= " ORDER BY FIELD(department, 'leadership', 'board', 'operations', 'volunteers'), display_order ASC";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        return [];
    }
    
    $teams = [];
    while($row = $result->fetch_assoc()) {
        $row['name'] = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
        $row['position'] = htmlspecialchars($row['position'], ENT_QUOTES, 'UTF-8');
        $row['bio'] = htmlspecialchars($row['bio'] ?? '', ENT_QUOTES, 'UTF-8');
        $row['email'] = htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8');
        $row['phone'] = htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8');
        $teams[] = $row;
    }
    
    $result->free();
    return $teams;
}

// Get current department filter from URL
$currentDepartment = isset($_GET['dept']) ? $_GET['dept'] : 'all';
$validDepartments = ['all', 'leadership', 'operations', 'volunteers', 'board'];

if (!in_array($currentDepartment, $validDepartments)) {
    $currentDepartment = 'all';
}

// Fetch team members
$teamMembers = getTeamMembers($conn, $currentDepartment);

// Get counts for each department
$countAll = count($teamMembers);
$counts = [];
$depts = ['leadership', 'operations', 'volunteers', 'board'];
foreach ($depts as $dept) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM teams WHERE department = '$dept' AND status = 'active'");
    if ($result) {
        $row = $result->fetch_assoc();
        $counts[$dept] = $row['cnt'];
        $result->free();
    } else {
        $counts[$dept] = 0;
    }
}

// Close connection
$conn->close();

// Include top bar
include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Our Teams | Soka Toto Muda Initiative Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* ========== DEPARTMENT FILTER TABS ========== */
        .filter-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 40px 0 50px;
        }

        .filter-btn {
            padding: 10px 28px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #0B1E3E;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .filter-btn i {
            margin-right: 8px;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            border-color: #fabc01;
            background: #fef9e6;
            transform: translateY(-2px);
        }

        .filter-btn.active {
            background: #0B1E3E;
            border-color: #0B1E3E;
            color: #fabc01;
        }

        /* ========== TEAMS GRID ========== */
        .teams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 35px;
            margin-bottom: 60px;
        }

        .team-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.2);
            border-color: #fabc01;
        }

        .team-image {
            width: 100%;
            height: 300px;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
            position: relative;
        }

        .team-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            color: white;
            font-size: 3rem;
        }

        .department-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(0, 0, 0, 0.7);
            color: #fabc01;
        }

        .team-info {
            padding: 22px;
            text-align: center;
        }

        .team-info h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 5px;
        }

        .team-position {
            color: #fabc01;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: inline-block;
            background: #fef9e6;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .team-bio {
            color: #475569;
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 18px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .team-contact {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .team-contact a {
            color: #64748b;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s;
        }

        .team-contact a:hover {
            color: #fabc01;
        }

        .team-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .team-social a {
            width: 35px;
            height: 35px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0B1E3E;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .team-social a:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-3px);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin: 40px 0;
        }

        .empty-state i {
            font-size: 48px;
            color: #fabc01;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #0B1E3E;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: #64748b;
        }

        /* Department count badges */
        .dept-count {
            background: #e2e8f0;
            color: #0B1E3E;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 0.7rem;
            margin-left: 8px;
        }

        .filter-btn.active .dept-count {
            background: #fabc01;
            color: #0B1E3E;
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

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .filter-tabs {
                gap: 8px;
                margin: 30px 0 35px;
            }
            .filter-btn {
                padding: 7px 18px;
                font-size: 0.85rem;
            }
            .teams-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            .team-image {
                height: 260px;
            }
            .page-header {
                padding: 50px 0;
                background-attachment: scroll;
            }
        }

        @media (max-width: 480px) {
            .filter-tabs {
                gap: 6px;
            }
            .filter-btn {
                padding: 5px 14px;
                font-size: 0.75rem;
            }
            .team-info h3 {
                font-size: 1.2rem;
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
<?php 
$headerImage = '';
if ($settings && !empty($settings['header_image'])) {
    $headerImage = $settings['header_image'];
}
?>
<section class="page-header <?php echo !empty($headerImage) ? 'has-image' : ''; ?>" 
         style="<?php echo !empty($headerImage) ? 'background-image: url(' . htmlspecialchars($headerImage) . ');' : 'background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);'; ?>">
    <div class="container">
        <h1>Our Teams</h1>
        <p>Meet the dedicated individuals behind our mission to transform children's lives.</p>
    </div>
</section>

<!-- Department Filter Tabs -->
<div class="container">
    <div class="filter-tabs">
        <a href="?dept=all" class="filter-btn <?php echo $currentDepartment == 'all' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> All
            <span class="dept-count"><?php echo $countAll; ?></span>
        </a>
        <a href="?dept=leadership" class="filter-btn <?php echo $currentDepartment == 'leadership' ? 'active' : ''; ?>">
            <i class="fas fa-crown"></i> Leadership
            <span class="dept-count"><?php echo $counts['leadership'] ?? 0; ?></span>
        </a>
        <a href="?dept=operations" class="filter-btn <?php echo $currentDepartment == 'operations' ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-user"></i> Operations
            <span class="dept-count"><?php echo $counts['operations'] ?? 0; ?></span>
        </a>
        <a href="?dept=volunteers" class="filter-btn <?php echo $currentDepartment == 'volunteers' ? 'active' : ''; ?>">
            <i class="fas fa-hand-peace"></i> Volunteers
            <span class="dept-count"><?php echo $counts['volunteers'] ?? 0; ?></span>
        </a>
        <a href="?dept=board" class="filter-btn <?php echo $currentDepartment == 'board' ? 'active' : ''; ?>">
            <i class="fas fa-building"></i> Board of Directors
            <span class="dept-count"><?php echo $counts['board'] ?? 0; ?></span>
        </a>
    </div>
</div>

<!-- Teams Grid Section -->
<section class="teams-section">
    <div class="container">
        <?php if (empty($teamMembers)): ?>
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <h3>No Team Members Found</h3>
                <p>Team members will appear here once added by the administrator.</p>
            </div>
        <?php else: ?>
            <div class="teams-grid">
                <?php foreach ($teamMembers as $member): ?>
                    <div class="team-card">
                        <div class="team-image" style="background-image: url('<?php echo !empty($member['image_path']) ? htmlspecialchars($member['image_path']) : ''; ?>');">
                            <?php if (empty($member['image_path'])): ?>
                                <div class="team-image-placeholder">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            <?php endif; ?>
                            <div class="department-badge">
                                <?php 
                                    $deptLabels = [
                                        'leadership' => 'Leadership',
                                        'operations' => 'Operations',
                                        'volunteers' => 'Volunteer',
                                        'board' => 'Board Member'
                                    ];
                                    echo $deptLabels[$member['department']] ?? ucfirst($member['department']);
                                ?>
                            </div>
                        </div>
                        <div class="team-info">
                            <h3><?php echo $member['name']; ?></h3>
                            <div class="team-position"><?php echo $member['position']; ?></div>
                            <p class="team-bio"><?php echo nl2br($member['bio']); ?></p>
                            
                            <div class="team-contact">
                                <?php if (!empty($member['email'])): ?>
                                    <a href="mailto:<?php echo $member['email']; ?>">
                                        <i class="fas fa-envelope"></i> Email
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($member['phone'])): ?>
                                    <a href="tel:<?php echo $member['phone']; ?>">
                                        <i class="fas fa-phone-alt"></i> Call
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="team-social">
                                <?php if (!empty($member['social_facebook'])): ?>
                                    <a href="<?php echo $member['social_facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($member['social_twitter'])): ?>
                                    <a href="<?php echo $member['social_twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($member['social_linkedin'])): ?>
                                    <a href="<?php echo $member['social_linkedin']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($member['social_instagram'])): ?>
                                    <a href="<?php echo $member['social_instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

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