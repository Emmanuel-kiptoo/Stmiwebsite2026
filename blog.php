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

// Create connection FIRST
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// NOW get blog page settings (after connection is established)
$blog_header_image = '';
$blog_settings_result = $conn->query("SELECT * FROM blog_page_settings WHERE id = 1");
if ($blog_settings_result && $blog_settings_result->num_rows > 0) {
    $blog_settings = $blog_settings_result->fetch_assoc();
    $blog_header_image = $blog_settings['header_image'] ?? '';
}

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$posts_per_page = 6;
$offset = ($page - 1) * $posts_per_page;

// Build query conditions
$where_conditions = ["status = 'published'"];
$params = [];
$types = "";

if (!empty($category)) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($search)) {
    $where_conditions[] = "(title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

$where_clause = implode(" AND ", $where_conditions);

// Get total posts count
$count_sql = "SELECT COUNT(*) as total FROM blog_posts WHERE $where_clause";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_result = $count_stmt->get_result();
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Fetch posts
$sql = "SELECT id, title, slug, excerpt, featured_image, category, author, views, published_at 
        FROM blog_posts 
        WHERE $where_clause 
        ORDER BY published_at DESC 
        LIMIT ? OFFSET ?";

$params[] = $posts_per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$posts = $stmt->get_result();

// Fetch all categories for filter
$categories_result = $conn->query("SELECT name, slug FROM blog_categories WHERE status = 'active' ORDER BY display_order ASC");
$categories = [];
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch featured posts for sidebar
$featured_posts = $conn->query("SELECT id, title, slug, featured_image, published_at FROM blog_posts WHERE status = 'published' AND featured = 1 ORDER BY published_at DESC LIMIT 3");

// Fetch recent posts for sidebar
$recent_posts = $conn->query("SELECT id, title, slug, published_at FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 5");

$conn->close();

// Helper function to format date
function formatBlogDate($date) {
    return date('F j, Y', strtotime($date));
}

// Helper function to truncate text
function truncateText($text, $length = 120) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Blog | Soka Toto Muda Initiative Trust</title>
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
            padding: 60px 0 40px 0;
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
        
        .page-header.has-image::before {
            background: linear-gradient(135deg, rgba(11, 30, 62, 0.75) 0%, rgba(26, 58, 110, 0.85) 100%);
        }

        .page-header h1 {
            font-size: clamp(1.8rem, 5vw, 2.8rem);
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .page-header h1 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .page-header p {
            font-size: clamp(0.95rem, 3vw, 1.1rem);
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* ========== BLOG LAYOUT ========== */
        .blog-wrapper {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 40px;
            margin: 50px 0 60px;
        }

        /* ========== MAIN CONTENT ========== */
        .blog-main {
            background: transparent;
        }

        /* Search Bar */
        .search-bar {
            display: flex;
            margin-bottom: 30px;
        }
        .search-bar input {
            flex: 1;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
            border-radius: 50px 0 0 50px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            background: white;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #fabc01;
        }
        .search-bar button {
            background: #0B1E3E;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 0 50px 50px 0;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-bar button:hover {
            background: #fabc01;
            color: #0B1E3E;
        }

        /* Category Filter */
        .category-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        .filter-btn {
            padding: 8px 20px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #0B1E3E;
            text-decoration: none;
            transition: all 0.3s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #0B1E3E;
            color: #fabc01;
            border-color: #0B1E3E;
        }
        .clear-filter {
            background: #f1f5f9;
        }
        .clear-filter:hover {
            background: #e11d48;
            color: white;
            border-color: #e11d48;
        }

        /* Posts Grid */
        .posts-grid {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .post-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            display: flex;
            flex-wrap: wrap;
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px -12px rgba(0, 0, 0, 0.15);
            border-color: #fabc01;
        }
        .post-image {
            width: 280px;
            flex-shrink: 0;
            background-size: cover;
            background-position: center;
            background-color: #cbd5e1;
        }
        .post-content {
            flex: 1;
            padding: 25px;
        }
        .post-category {
            display: inline-block;
            background: #fef9e6;
            color: #fabc01;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .post-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .post-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s;
        }
        .post-title a:hover {
            color: #fabc01;
        }
        .post-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 0.8rem;
            color: #64748b;
        }
        .post-meta i {
            margin-right: 5px;
            color: #fabc01;
        }
        .post-excerpt {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .read-more {
            color: #0B1E3E;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s;
        }
        .read-more:hover {
            gap: 12px;
            color: #fabc01;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #0B1E3E;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .page-link:hover, .page-link.active {
            background: #0B1E3E;
            color: #fabc01;
            border-color: #0B1E3E;
        }
        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ========== SIDEBAR ========== */
        .blog-sidebar {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .sidebar-widget {
            background: white;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #e2e8f0;
        }
        .widget-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #fabc01;
            display: inline-block;
        }
        .featured-list, .recent-list {
            list-style: none;
        }
        .featured-item, .recent-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .featured-item:last-child, .recent-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .featured-img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
        }
        .featured-info h4, .recent-info h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .featured-info a, .recent-info a {
            color: #0B1E3E;
            text-decoration: none;
            transition: color 0.3s;
        }
        .featured-info a:hover, .recent-info a:hover {
            color: #fabc01;
        }
        .featured-date, .recent-date {
            font-size: 0.7rem;
            color: #94a3b8;
        }
        .recent-info h4 {
            font-size: 0.85rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 20px;
        }
        .empty-state i {
            font-size: 48px;
            color: #fabc01;
            margin-bottom: 20px;
        }
        .empty-state h3 {
            color: #0B1E3E;
            margin-bottom: 10px;
        }

        /* Back to Top Button */
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
            transition: all 0.3s;
            opacity: 0;
            visibility: hidden;
            border: none;
            font-size: 1.1rem;
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
            .blog-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }
        @media (max-width: 768px) {
            .post-card {
                flex-direction: column;
            }
            .post-image {
                width: 100%;
                height: 200px;
            }
            .post-title {
                font-size: 1.2rem;
            }
            .category-filter {
                gap: 8px;
            }
            .filter-btn {
                padding: 6px 15px;
                font-size: 0.8rem;
            }
            .page-header {
                background-attachment: scroll;
            }
        }
        @media (max-width: 480px) {
            .post-content {
                padding: 20px;
            }
            .post-meta {
                flex-wrap: wrap;
                gap: 10px;
            }
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
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
<section class="page-header <?php echo !empty($blog_header_image) ? 'has-image' : ''; ?>" 
         style="<?php echo !empty($blog_header_image) ? 'background-image: url(' . htmlspecialchars($blog_header_image) . ');' : ''; ?>">
    <div class="container">
        <h1><i class="fas fa-blog"></i> Our Blog</h1>
        <p>Stories, updates, and insights from our work transforming lives in the community.</p>
    </div>
</section>

<div class="container">
    <div class="blog-wrapper">
        <!-- MAIN CONTENT -->
        <div class="blog-main">
            <!-- Search Bar -->
            <form method="GET" action="" class="search-bar">
                <input type="text" name="search" placeholder="Search articles..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
                <?php if (!empty($category)): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <?php endif; ?>
            </form>

            <!-- Category Filter -->
            <div class="category-filter">
                <a href="?<?php echo $search ? 'search=' . urlencode($search) : ''; ?>" class="filter-btn <?php echo empty($category) ? 'active' : ''; ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?php echo urlencode($cat['name']); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="filter-btn <?php echo $category == $cat['name'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
                <?php if (!empty($category) || !empty($search)): ?>
                    <a href="blog.php" class="filter-btn clear-filter"><i class="fas fa-times"></i> Clear</a>
                <?php endif; ?>
            </div>

            <!-- Posts Grid -->
            <?php if ($posts && $posts->num_rows > 0): ?>
                <div class="posts-grid">
                    <?php while ($post = $posts->fetch_assoc()): ?>
                        <div class="post-card">
                            <div class="post-image" style="background-image: url('<?php echo !empty($post['featured_image']) ? $post['featured_image'] : 'https://placehold.co/600x400/0B1E3E/fabc01?text=STMI+Blog'; ?>');"></div>
                            <div class="post-content">
                                <span class="post-category"><?php echo htmlspecialchars($post['category']); ?></span>
                                <h2 class="post-title">
                                    <a href="blog-single.php?slug=<?php echo urlencode($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                </h2>
                                <div class="post-meta">
                                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                                    <span><i class="fas fa-calendar-alt"></i> <?php echo formatBlogDate($post['published_at']); ?></span>
                                    <span><i class="fas fa-eye"></i> <?php echo number_format($post['views']); ?> views</span>
                                </div>
                                <p class="post-excerpt"><?php echo truncateText(strip_tags($post['excerpt'] ?: ''), 150); ?></p>
                                <a href="blog-single.php?slug=<?php echo urlencode($post['slug']); ?>" class="read-more">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <a href="?page=<?php echo max(1, $page - 1); ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="page-link <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                               class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        <a href="?page=<?php echo min($total_pages, $page + 1); ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                           class="page-link <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>No posts found</h3>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                    <a href="blog.php" class="read-more" style="margin-top: 15px; display: inline-block;">View all posts</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR -->
        <div class="blog-sidebar">
            <!-- Featured Posts Widget -->
            <?php if ($featured_posts && $featured_posts->num_rows > 0): ?>
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-star"></i> Featured Posts</h3>
                <div class="featured-list">
                    <?php while ($post = $featured_posts->fetch_assoc()): ?>
                        <div class="featured-item">
                            <div class="featured-img" style="background-image: url('<?php echo !empty($post['featured_image']) ? $post['featured_image'] : 'https://placehold.co/70x70/0B1E3E/fabc01?text=STMI'; ?>');"></div>
                            <div class="featured-info">
                                <h4><a href="blog-single.php?slug=<?php echo urlencode($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h4>
                                <div class="featured-date"><i class="fas fa-calendar-alt"></i> <?php echo formatBlogDate($post['published_at']); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Posts Widget -->
            <?php if ($recent_posts && $recent_posts->num_rows > 0): ?>
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-clock"></i> Recent Posts</h3>
                <div class="recent-list">
                    <?php while ($post = $recent_posts->fetch_assoc()): ?>
                        <div class="recent-item">
                            <div class="recent-info">
                                <h4><a href="blog-single.php?slug=<?php echo urlencode($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h4>
                                <div class="recent-date"><i class="fas fa-calendar-alt"></i> <?php echo formatBlogDate($post['published_at']); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

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