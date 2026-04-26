<?php
// Start session
session_start();

// Include top bar
include 'top-bar.php';

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

// Get category filter from URL
$currentCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
$validCategories = ['all', 'success_story', 'impact_story', 'testimonial', 'featured'];

if (!in_array($currentCategory, $validCategories)) {
    $currentCategory = 'all';
}

// Fetch stories based on category
function getStories($conn, $category = 'all', $limit = null) {
    $sql = "SELECT * FROM stories WHERE status = 'active'";
    
    if ($category === 'featured') {
        $sql .= " AND featured = 1";
    } elseif ($category !== 'all') {
        $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
    }
    
    $sql .= " ORDER BY featured DESC, display_order ASC, created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $result = $conn->query($sql);
    
    if (!$result) {
        return [];
    }
    
    $stories = [];
    while($row = $result->fetch_assoc()) {
        $row['title'] = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $row['author'] = htmlspecialchars($row['author'] ?? '', ENT_QUOTES, 'UTF-8');
        $row['content'] = htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8');
        $row['excerpt'] = htmlspecialchars($row['excerpt'] ?? '', ENT_QUOTES, 'UTF-8');
        $stories[] = $row;
    }
    
    $result->free();
    return $stories;
}

// Get category counts
function getCategoryCounts($conn) {
    $counts = ['all' => 0, 'success_story' => 0, 'impact_story' => 0, 'testimonial' => 0, 'featured' => 0];
    
    $result = $conn->query("SELECT category, COUNT(*) as cnt FROM stories WHERE status = 'active' GROUP BY category");
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $counts[$row['category']] = $row['cnt'];
            $counts['all'] += $row['cnt'];
        }
        $result->free();
    }
    
    $featuredResult = $conn->query("SELECT COUNT(*) as cnt FROM stories WHERE status = 'active' AND featured = 1");
    if ($featuredResult) {
        $featuredRow = $featuredResult->fetch_assoc();
        $counts['featured'] = $featuredRow['cnt'];
        $featuredResult->free();
    }
    
    return $counts;
}

// Fetch stories and counts
$stories = getStories($conn, $currentCategory);
$categoryCounts = getCategoryCounts($conn);

// Get featured stories for hero section
$featuredStories = getStories($conn, 'featured', 3);

// Close connection
$conn->close();

// Category labels
$categoryLabels = [
    'all' => 'All Stories',
    'success_story' => 'Success Stories',
    'impact_story' => 'Impact Stories',
    'testimonial' => 'Testimonials',
    'featured' => 'Featured'
];

$categoryIcons = [
    'all' => 'fa-book-open',
    'success_story' => 'fa-trophy',
    'impact_story' => 'fa-chart-line',
    'testimonial' => 'fa-quote-left',
    'featured' => 'fa-star'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Our Stories | Soka Toto Muda Initiative Trust</title>
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

        /* ========== PAGE HEADER ========== */
        .page-header {
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            color: white;
            padding: 60px 0 40px 0;
            text-align: center;
        }

        .page-header h1 {
            font-size: clamp(1.8rem, 5vw, 2.8rem);
            font-weight: 800;
            margin-bottom: 12px;
        }

        .page-header p {
            font-size: clamp(0.95rem, 3vw, 1.1rem);
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
        }

        /* ========== FEATURED STORIES HERO ========== */
        .featured-hero {
            margin: 50px 0 30px;
        }

        .featured-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .featured-title h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0B1E3E;
        }

        .featured-title h2 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .featured-title span {
            border-bottom: 3px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .featured-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px -12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .featured-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px -15px rgba(0, 0, 0, 0.2);
            border-color: #fabc01;
        }

        .featured-image {
            height: 220px;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
            position: relative;
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #fabc01;
            color: #0B1E3E;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .featured-content {
            padding: 25px;
        }

        .featured-content h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 10px;
        }

        .featured-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .featured-meta i {
            margin-right: 5px;
        }

        .featured-excerpt {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        /* ========== CATEGORY FILTER TABS ========== */
        .filter-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 50px 0 40px;
        }

        .filter-btn {
            padding: 10px 28px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #0B1E3E;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .filter-btn i {
            margin-right: 8px;
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

        .category-count {
            background: #e2e8f0;
            color: #0B1E3E;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 0.7rem;
            margin-left: 8px;
        }

        .filter-btn.active .category-count {
            background: #fabc01;
            color: #0B1E3E;
        }

        /* ========== STORIES GRID ========== */
        .stories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 35px;
            margin-bottom: 60px;
        }

        .story-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .story-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            border-color: #fabc01;
        }

        .story-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
            position: relative;
        }

        .story-category {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: #0B1E3E;
            color: #fabc01;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .story-content {
            padding: 22px;
        }

        .story-content h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .story-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .story-meta i {
            margin-right: 5px;
        }

        .story-excerpt {
            color: #475569;
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .read-more {
            color: #0B1E3E;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .read-more:hover {
            color: #fabc01;
            gap: 12px;
        }

        /* ========== STORY MODAL (Lightbox for full story) ========== */
        .story-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
        }

        .story-modal.active {
            display: flex;
        }

        .story-modal-content {
            background: white;
            max-width: 800px;
            width: 90%;
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            margin: 40px auto;
        }

        .story-modal-close {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 35px;
            color: white;
            cursor: pointer;
            z-index: 10001;
            transition: color 0.3s;
            background: rgba(0,0,0,0.5);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .story-modal-close:hover {
            background: #fabc01;
            color: #0B1E3E;
        }

        .story-modal-image {
            width: 100%;
            height: 300px;
            background-size: cover;
            background-position: center;
        }

        .story-modal-body {
            padding: 30px;
        }

        .story-modal-body h2 {
            font-size: 1.8rem;
            color: #0B1E3E;
            margin-bottom: 10px;
        }

        .story-modal-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #64748b;
        }

        .story-modal-content-text {
            font-size: 1rem;
            line-height: 1.7;
            color: #334155;
        }

        /* ========== EMPTY STATE ========== */
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
            .featured-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .stories-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .filter-tabs {
                gap: 8px;
                margin: 35px 0 30px;
            }
            
            .filter-btn {
                padding: 7px 18px;
                font-size: 0.8rem;
            }
            
            .story-modal-body {
                padding: 20px;
            }
            
            .story-modal-body h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .filter-tabs {
                gap: 6px;
            }
            
            .filter-btn {
                padding: 5px 14px;
                font-size: 0.7rem;
            }
            
            .featured-content h3 {
                font-size: 1.1rem;
            }
            
            .story-content h3 {
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

<!-- Page Header -->


<div class="container">
    <!-- FEATURED STORIES SECTION -->
    <?php if (!empty($featuredStories)): ?>
    <div class="featured-hero">
        <div class="featured-title">
            <h2><i class="fas fa-star"></i> <span>Featured Stories</span></h2>
        </div>
        <div class="featured-grid">
            <?php foreach ($featuredStories as $story): ?>
            <div class="featured-card" onclick="openStoryModal(<?php echo $story['id']; ?>)">
                <div class="featured-image" style="background-image: url('<?php echo !empty($story['image_path']) ? $story['image_path'] : 'https://placehold.co/600x400/0B1E3E/fabc01?text=STMI+Story'; ?>');">
                    <div class="featured-badge">
                        <i class="fas fa-star"></i> Featured
                    </div>
                </div>
                <div class="featured-content">
                    <h3><?php echo $story['title']; ?></h3>
                    <div class="featured-meta">
                        <span><i class="fas fa-user"></i> <?php echo $story['author'] ?: 'STMI Team'; ?></span>
                        <span><i class="fas fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($story['story_date'] ?: $story['created_at'])); ?></span>
                    </div>
                    <p class="featured-excerpt"><?php echo $story['excerpt'] ?: substr($story['content'], 0, 120) . '...'; ?></p>
                    <a href="#" class="read-more" onclick="event.stopPropagation(); openStoryModal(<?php echo $story['id']; ?>)">Read Full Story <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- CATEGORY FILTER TABS -->
    <div class="filter-tabs">
        <?php foreach ($categoryLabels as $key => $label): ?>
            <a href="?category=<?php echo $key; ?>" class="filter-btn <?php echo $currentCategory == $key ? 'active' : ''; ?>">
                <i class="fas <?php echo $categoryIcons[$key]; ?>"></i> <?php echo $label; ?>
                <span class="category-count"><?php echo $categoryCounts[$key]; ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- STORIES GRID -->
    <?php if (empty($stories)): ?>
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h3>No Stories Found</h3>
            <p>Check back soon for inspiring stories from our community.</p>
        </div>
    <?php else: ?>
        <div class="stories-grid">
            <?php foreach ($stories as $story): ?>
                <div class="story-card" onclick="openStoryModal(<?php echo $story['id']; ?>)">
                    <div class="story-image" style="background-image: url('<?php echo !empty($story['image_path']) ? $story['image_path'] : 'https://placehold.co/600x400/0B1E3E/fabc01?text=STMI+Story'; ?>');">
                        <div class="story-category">
                            <?php 
                                $catLabels = [
                                    'success_story' => 'Success Story',
                                    'impact_story' => 'Impact Story',
                                    'testimonial' => 'Testimonial'
                                ];
                                echo $catLabels[$story['category']] ?? ucfirst(str_replace('_', ' ', $story['category']));
                            ?>
                        </div>
                    </div>
                    <div class="story-content">
                        <h3><?php echo $story['title']; ?></h3>
                        <div class="story-meta">
                            <span><i class="fas fa-user"></i> <?php echo $story['author'] ?: 'STMI Team'; ?></span>
                            <span><i class="fas fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($story['story_date'] ?: $story['created_at'])); ?></span>
                        </div>
                        <p class="story-excerpt"><?php echo $story['excerpt'] ?: substr($story['content'], 0, 100) . '...'; ?></p>
                        <a href="#" class="read-more" onclick="event.stopPropagation(); openStoryModal(<?php echo $story['id']; ?>)">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- STORY MODAL (Lightbox for full story) -->
<div class="story-modal" id="storyModal">
    <div class="story-modal-content">
        <span class="story-modal-close" onclick="closeStoryModal()">&times;</span>
        <div class="story-modal-image" id="modalImage"></div>
        <div class="story-modal-body">
            <h2 id="modalTitle"></h2>
            <div class="story-modal-meta">
                <span id="modalAuthor"><i class="fas fa-user"></i></span>
                <span id="modalDate"><i class="fas fa-calendar-alt"></i></span>
                <span id="modalCategory"><i class="fas fa-tag"></i></span>
            </div>
            <div class="story-modal-content-text" id="modalContent"></div>
        </div>
    </div>
</div>

<script>
    // Store stories data from PHP
    const storiesData = <?php 
        $allStories = getStories($conn, 'all');
        echo json_encode($allStories);
    ?>;
    
    // Open story modal
    function openStoryModal(storyId) {
        const story = storiesData.find(s => s.id == storyId);
        if (!story) return;
        
        const modal = document.getElementById('storyModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const modalAuthor = document.getElementById('modalAuthor');
        const modalDate = document.getElementById('modalDate');
        const modalCategory = document.getElementById('modalCategory');
        const modalContent = document.getElementById('modalContent');
        
        // Set image
        const imageUrl = story.image_path ? story.image_path : 'https://placehold.co/800x400/0B1E3E/fabc01?text=STMI+Story';
        modalImage.style.backgroundImage = `url('${imageUrl}')`;
        
        // Set content
        modalTitle.textContent = story.title;
        modalAuthor.innerHTML = `<i class="fas fa-user"></i> ${story.author || 'STMI Team'}`;
        modalDate.innerHTML = `<i class="fas fa-calendar-alt"></i> ${new Date(story.story_date || story.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`;
        
        // Set category label
        const categoryLabels = {
            'success_story': 'Success Story',
            'impact_story': 'Impact Story',
            'testimonial': 'Testimonial'
        };
        modalCategory.innerHTML = `<i class="fas fa-tag"></i> ${categoryLabels[story.category] || story.category}`;
        
        // Format content with line breaks
        modalContent.innerHTML = story.content.replace(/\n/g, '<br>');
        
        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Close story modal
    function closeStoryModal() {
        const modal = document.getElementById('storyModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('storyModal');
        if (modal.classList.contains('active') && e.key === 'Escape') {
            closeStoryModal();
        }
    });
    
    // Close modal when clicking outside content
    document.getElementById('storyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStoryModal();
        }
    });
    
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