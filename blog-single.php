<?php
session_start();
include 'top-bar.php';

$host = 'localhost';
$dbname = 'stmitrust2026';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = null;

if (!empty($slug)) {
    // Update view count
    $conn->query("UPDATE blog_posts SET views = views + 1 WHERE slug = '$slug'");
    
    // Fetch post
    $result = $conn->query("SELECT * FROM blog_posts WHERE slug = '$slug' AND status = 'published'");
    if ($result && $result->num_rows > 0) {
        $post = $result->fetch_assoc();
    }
}

$conn->close();

if (!$post) {
    header('Location: blog.php');
    exit;
}

// Helper function to get header background image
function getPostHeaderImage($post) {
    if (!empty($post['header_image'])) {
        return $post['header_image'];
    }
    if (!empty($post['featured_image'])) {
        return $post['featured_image'];
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> | STMI Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }
        .container { max-width: 800px; margin: 0 auto; padding: 0 24px; }
        
        /* ========== POST HEADER WITH IMG TAG ========== */
        .post-header {
            position: relative;
            color: white;
            padding: 80px 0 40px 0;
            text-align: center;
            overflow: hidden;
            min-height: 300px;
            display: flex;
            align-items: center;
        }
        
        .header-image-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        
        .header-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .header-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(11, 30, 62, 0.75) 0%, rgba(26, 58, 110, 0.85) 100%);
        }
        
        .post-header .container {
            position: relative;
            z-index: 1;
            width: 100%;
        }
        
        .post-header h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .post-meta {
            display: flex;
            justify-content: center;
            gap: 20px;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .back-to-blog {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            margin-bottom: 15px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .back-to-blog:hover {
            background: #fabc01;
            color: #0B1E3E;
        }
        
        .post-featured-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 20px;
            margin: 15px 0 20px 0;
        }
        
        .post-content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            margin: 5px 0 10px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .post-content p {
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .post-content h2, .post-content h3 {
            margin: 25px 0 15px;
            color: #0B1E3E;
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
        
        @media (max-width: 768px) { 
            .post-header h1 { font-size: 1.8rem; } 
            .post-content { padding: 20px; margin: 15px 0 30px 0; }
            .post-header { padding: 60px 0 30px 0; min-height: 250px; }
            .post-featured-image { margin: 10px 0 15px 0; }
            .back-to-blog { margin-bottom: 10px; }
        }
        
        @media (max-width: 480px) {
            .post-header h1 { font-size: 1.5rem; }
            .post-meta { flex-direction: column; gap: 8px; }
            .post-content { padding: 15px; }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn"><i class="fas fa-arrow-up"></i></a>

<!-- Post Header with Image -->
<?php $headerImage = getPostHeaderImage($post); ?>
<section class="post-header">
    <?php if (!empty($headerImage)): ?>
        <div class="header-image-container">
            <img src="<?php echo htmlspecialchars($headerImage); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="header-bg-img">
            <div class="header-overlay"></div>
        </div>
    <?php else: ?>
        <div class="header-image-container" style="background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);"></div>
    <?php endif; ?>
    <div class="container">
        <a href="blog.php" class="back-to-blog"><i class="fas fa-arrow-left"></i> Back to Blog</a>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="post-meta">
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?></span>
            <span><i class="fas fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
            <span><i class="fas fa-eye"></i> <?php echo number_format($post['views']); ?> views</span>
        </div>
    </div>
</section>

<div class="container">
    <?php if (!empty($post['featured_image'])): ?>
        <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-featured-image">
    <?php endif; ?>
    
    <div class="post-content">
        <?php echo $post['content']; ?>
    </div>
</div>

<script>
    // Back to Top Button
    const btn = document.getElementById('backToTopBtn');
    window.addEventListener('scroll', () => { if (window.scrollY > 300) btn.classList.add('show'); else btn.classList.remove('show'); });
    btn.addEventListener('click', (e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
</script>

<?php include 'footer.php'; ?>
</body>
</html>