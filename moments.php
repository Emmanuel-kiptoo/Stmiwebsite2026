<?php
// Start session at the very beginning
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'stmitrust2026';
$username = 'root';
$password = '';

// Enable output buffering for better performance
ob_start();

// Create connection with error handling
try {
    $conn = new mysqli($host, $username, $password, $dbname);
    
    // Set connection timeout
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Connection failed, show error and exit
    die(json_encode([
        'error' => true,
        'message' => 'Database connection failed',
        'details' => $e->getMessage()
    ]));
}

/**
 * Fetch active images from database with optimized query
 */
function getActiveImages($conn) {
    // Use prepared statement for security
    $sql = "SELECT id, title, description, file_path, display_order 
            FROM moments_images 
            WHERE status = 'active' 
            ORDER BY display_order ASC 
            LIMIT 50"; // Limit to prevent loading too many images
    
    $result = $conn->query($sql);
    
    if (!$result) {
        // Log error but don't break the page
        error_log("Image query error: " . $conn->error);
        return [];
    }
    
    $images = [];
    while($row = $result->fetch_assoc()) {
        // Validate and sanitize data
        $row['file_path'] = trim($row['file_path']);
        $row['title'] = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $row['description'] = htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8');
        $images[] = $row;
    }
    
    $result->free();
    return $images;
}

/**
 * Fetch active videos from database with optimized query
 */
function getActiveVideos($conn) {
    // Use prepared statement for security
    $sql = "SELECT id, title, description, file_path, duration, display_order 
            FROM moments_videos 
            WHERE status = 'active' 
            ORDER BY display_order ASC 
            LIMIT 20"; // Limit to prevent loading too many videos
    
    $result = $conn->query($sql);
    
    if (!$result) {
        // Log error but don't break the page
        error_log("Video query error: " . $conn->error);
        return [];
    }
    
    $videos = [];
    while($row = $result->fetch_assoc()) {
        // Validate and sanitize data
        $row['file_path'] = trim($row['file_path']);
        $row['title'] = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $row['description'] = htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8');
        
        // Format duration if exists
        if (!empty($row['duration'])) {
            $duration = intval($row['duration']);
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;
            $row['formatted_duration'] = sprintf('%d:%02d', $minutes, $seconds);
        } else {
            $row['formatted_duration'] = '0:00';
        }
        
        $videos[] = $row;
    }
    
    $result->free();
    return $videos;
}

/**
 * Optimized function to get file path without expensive file_exists checks
 */
function getFilePath($path) {
    if (empty($path)) {
        return '';
    }
    
    // Trim whitespace
    $path = trim($path);
    
    // If it's already a full URL, return as-is
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    
    // If it's an absolute path starting with /, return as-is
    if (strpos($path, '/') === 0) {
        return $path;
    }
    
    // For relative paths, ensure they're properly formatted
    // Remove any leading ./ or ../
    $path = preg_replace('/^\.\.?\//', '', $path);
    
    // Return with ./ prefix for web access
    return './' . $path;
}

/**
 * Generate video thumbnail URL - SIMPLIFIED WORKING VERSION
 */
function getVideoThumbnail($videoPath, $title) {
    // Create a simple thumbnail that shows actual content
    $encodedTitle = urlencode(substr($title, 0, 30));
    
    // For local files, we'll create a thumbnail using a different approach
    // We'll use JavaScript to show the first frame
    // For now, show a video icon with title
    return "https://placehold.co/350x250/1a237e/ffffff.png?text=▶+" . $encodedTitle . "&font=montserrat";
}

// Fetch data efficiently
$startTime = microtime(true);

$images = getActiveImages($conn);
$videos = getActiveVideos($conn);

$endTime = microtime(true);
$queryTime = round(($endTime - $startTime) * 1000, 2); // Time in milliseconds

// Close database connection early
$conn->close();

// Set headers to prevent caching if needed
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Set cache control for dynamic content
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Generate JSON data for JavaScript
$pageData = [
    'images' => $images,
    'videos' => $videos,
    'stats' => [
        'image_count' => count($images),
        'video_count' => count($videos),
        'query_time_ms' => $queryTime,
        'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
        'memory_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB'
    ]
];

// Log performance (for debugging)
error_log("Gallery loaded: " . count($images) . " images, " . count($videos) . " videos in " . $queryTime . "ms");

// Clean output buffer
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moments Gallery - STMI Trust</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" crossorigin>
    <link rel="preconnect" href="https://placehold.co">
    
    <!-- Inline critical CSS for faster rendering -->
    <style id="critical-css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            flex-direction: column;
            gap: 15px;
        }
        
        .loader {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4a6ee0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .moments-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .moments-container.loaded {
            opacity: 1;
        }
    </style>
    
    <!-- Non-critical CSS loaded asynchronously -->
    <style>
        /* All your existing CSS styles go here */
        
        .section-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0f0;
        }
        
        .section-header h2 {
            color: #000275ff;
            font-size: 28px;
            font-weight: 600;
        }
        
        /* ENHANCED IMAGE GALLERY CONTAINER */
        .images-wrapper {
            position: relative;
            overflow: hidden;
            height: 350px; /* Increased height for better viewing */
            margin: 0 auto 30px;
            max-width: 1000px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        
        .images-track {
            display: flex;
            height: 100%;
            align-items: center;
            position: absolute;
            left: 0;
            transition: transform 0.5s ease-out;
            gap: 20px; /* Increased gap */
            padding: 0 20px;
        }
        
        /* UNIFORM LANDSCAPE IMAGE CONTAINER */
        .image-item {
            flex: 0 0 auto;
            width: 300px; /* Fixed width for landscape */
            height: 250px; /* Fixed height for landscape */
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            background: #f8f9fa;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .image-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* UNIFORM LANDSCAPE IMAGE STYLING */
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Changed back to cover for uniform look */
            object-position: center; /* Center the image */
            background-color: #f5f5f5;
            transition: transform 0.5s ease;
        }
        
        /* For portrait images, we'll use object-fit: contain and add background */
        .image-item.portrait img {
            object-fit: contain;
            background-color: #f8f9fa;
        }
        
        .image-item:hover img {
            transform: scale(1.05);
        }
        
        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 15px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        
        .image-item:hover .image-overlay {
            transform: translateY(0);
        }
        
        /* PERFECTED FIXED POSITION ARROWS - ALWAYS IN SAME POSITION */
        .gallery-nav {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 10;
            pointer-events: none; /* Allows clicking through to images */
        }
        
        .nav-arrow {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            pointer-events: auto; /* Enable clicking on arrows */
            color: #000275ff;
            font-size: 22px;
            border: 2px solid #000275ff;
            position: relative;
            overflow: hidden;
        }
        
        .nav-arrow::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000275ff;
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
            z-index: -1;
        }
        
        .nav-arrow:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.35);
        }
        
        .nav-arrow:hover::before {
            transform: scale(1);
        }
        
        .nav-arrow:active {
            transform: scale(0.95);
        }
        
        .nav-arrow.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background: rgba(255, 255, 255, 0.7);
            border-color: #999;
            color: #999;
        }
        
        .nav-arrow.disabled:hover {
            background: rgba(255, 255, 255, 0.7);
            color: #999;
            transform: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .nav-arrow.disabled::before {
            display: none;
        }
        
        .image-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .control-btn {
            padding: 10px 20px;
            background: #4a6ee0;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .control-btn:hover {
            background: #3a5bd0;
            transform: translateY(-2px);
        }
        
        .videos-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .video-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .video-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        /* Video container - SIMPLIFIED FOR RELIABLE PREVIEW */
        .video-hover-container {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
            background: #000;
        }
        
        /* Video element will serve as both preview and hover player */
        .video-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            transition: opacity 0.3s ease;
        }
        
        /* This is the preview that always shows */
        .video-preview.paused {
            opacity: 1;
            z-index: 2;
        }
        
        /* This is the hover video */
        .video-preview.playing {
            opacity: 0;
            z-index: 1;
        }
        
        .video-item:hover .video-preview.paused {
            opacity: 0;
        }
        
        .video-item:hover .video-preview.playing {
            opacity: 1;
        }
        
        .click-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 4;
        }
        
        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            transition: opacity 0.3s ease;
            z-index: 3;
            background: rgba(0, 0, 0, 0.2);
        }
        
        .video-item:hover .play-overlay {
            opacity: 0;
        }
        
        .play-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a6ee0;
            font-size: 24px;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .video-item:hover .play-icon {
            transform: scale(1.1);
        }
        
        .video-status {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 5;
            transition: opacity 0.3s ease;
        }
        
        .video-item:hover .video-status {
            opacity: 0;
        }
        
        .video-duration {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 5;
        }
        
        .video-overlay {
            padding: 20px;
            background: white;
        }
        
        .video-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        /* ENHANCED LIGHTBOX WITH PERFECT NAVIGATION ARROWS */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .lightbox img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            transition: opacity 0.3s ease;
        }
        
        /* PERFECTED LIGHTBOX NAVIGATION ARROWS - FIXED POSITION */
        .lightbox-nav {
            position: fixed;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1001;
        }
        
        .lightbox-arrow {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.4);
            color: #000;
            font-size: 26px;
            border: 2px solid #000;
            position: relative;
            overflow: hidden;
        }
        
        .lightbox-arrow::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
            z-index: -1;
        }
        
        .lightbox-arrow:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.5);
        }
        
        .lightbox-arrow:hover::before {
            transform: scale(1);
        }
        
        .lightbox-arrow:active {
            transform: scale(0.95);
        }
        
        .lightbox-arrow.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background: rgba(255, 255, 255, 0.7);
            border-color: #666;
            color: #666;
        }
        
        .lightbox-arrow.disabled:hover {
            background: rgba(255, 255, 255, 0.7);
            color: #666;
            transform: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .lightbox-arrow.disabled::before {
            display: none;
        }
        
        .lightbox-info {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            text-align: center;
            z-index: 1001;
        }
        
        /* Perfect close button for lightbox */
        .lightbox-close {
            position: fixed;
            top: 25px;
            right: 25px;
            background: rgba(255, 255, 255, 0.95);
            color: #000;
            border: none;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            font-size: 30px;
            cursor: pointer;
            z-index: 1002;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
        
        .lightbox-close::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ff0000;
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
            z-index: -1;
        }
        
        .lightbox-close:hover {
            color: white;
            transform: rotate(90deg);
            box-shadow: 0 6px 25px rgba(255, 0, 0, 0.4);
        }
        
        .lightbox-close:hover::before {
            transform: scale(1);
        }
        
        .lightbox-close:active {
            transform: rotate(90deg) scale(0.95);
        }
        
        /* Popup Overlay */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 1000;
        }
        
        /* Video Popup */
        .video-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 900px;
            max-height: 80vh;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1001;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
        }
        
        .video-popup-content {
            width: 100%;
            height: 100%;
            position: relative;
            padding: 20px;
        }
        
        .popup-video {
            width: 100%;
            height: 100%;
            max-height: calc(80vh - 40px);
            object-fit: contain;
            background-color: #000;
        }
        
        .close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 24px;
            cursor: pointer;
            z-index: 1002;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-popup:hover {
            background: rgba(255, 0, 0, 0.7);
        }
        
        .video-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            color: white;
            padding: 20px;
            padding-top: 40px;
        }
        
        /* Fullscreen button in popup */
        .fullscreen-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            backdrop-filter: blur(5px);
            z-index: 1002;
        }
        
        .fullscreen-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #666;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #999;
        }
        
        /* Ensure arrows are always above images */
        .image-item {
            z-index: 1;
        }
        
        /* Responsive styles with perfect arrow adjustments */
        @media (max-width: 768px) {
            .images-wrapper {
                height: 250px;
            }
            
            .image-item {
                width: 200px;
                height: 160px;
            }
            
            .gallery-nav {
                padding: 0 15px;
            }
            
            .nav-arrow {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
            
            .videos-container {
                grid-template-columns: 1fr;
            }
            
            .video-hover-container {
                height: 200px;
            }
            
            .control-btn {
                padding: 8px 15px;
                font-size: 14px;
            }
            
            .video-popup {
                width: 95%;
                height: auto;
                aspect-ratio: 16/9;
            }
            
            .play-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .lightbox-arrow {
                width: 50px;
                height: 50px;
                font-size: 22px;
            }
            
            .lightbox-close {
                width: 45px;
                height: 45px;
                font-size: 26px;
                top: 20px;
                right: 20px;
            }
            
            .lightbox-nav {
                padding: 0 25px;
            }
        }
        
        @media (max-width: 480px) {
            .section-header h2 {
                font-size: 22px;
            }
            
            .images-wrapper {
                height: 200px;
            }
            
            .image-item {
                width: 160px;
                height: 130px;
            }
            
            .nav-arrow {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            .video-hover-container {
                height: 180px;
            }
            
            .video-popup {
                width: 98%;
            }
            
            .lightbox-arrow {
                width: 45px;
                height: 45px;
                font-size: 20px;
                padding: 0 15px;
            }
            
            .lightbox-nav {
                padding: 0 15px;
            }
            
            .lightbox-close {
                width: 40px;
                height: 40px;
                font-size: 24px;
                top: 15px;
                right: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader"></div>
        <div id="loadingText">Loading Moments Gallery...</div>
        <div id="loadingDetails" style="font-size: 12px; color: #666; margin-top: 10px;">
            Found <?php echo count($images); ?> images and <?php echo count($videos); ?> videos
        </div>
    </div>

    <?php include 'top-bar.php'; ?>

    <div class="moments-container" id="momentsContainer">
        <!-- IMAGES SECTION -->
        <section class="images-section">
            <div class="section-header">
                <h2><i class="fas fa-images"></i> Image Gallery</h2>
                <?php if (count($images) <= 5 && count($images) > 0): ?>
                <p style="color: #666; font-size: 14px; margin-top: 5px;">(Static display - add more than 5 images for auto-scroll)</p>
                <?php endif; ?>
            </div>
            
            <div class="images-wrapper">
                <!-- PERFECT NAVIGATION ARROWS - FIXED POSITION -->
                <div class="gallery-nav">
                    <button class="nav-arrow prev-arrow" onclick="prevImage()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-arrow next-arrow" onclick="nextImage()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
                <div class="images-track" id="imagesTrack">
                    <?php if (empty($images)): ?>
                        <div class="empty-state" style="width: 100%; text-align: center; background: transparent;">
                            <i class="fas fa-image"></i>
                            <h3>No Images Available</h3>
                            <p>Images will appear here once added by the admin.</p>
                        </div>
                    <?php else: ?>
                        <!-- Single set of images -->
                        <?php foreach ($images as $index => $image): ?>
                        <div class="image-item" data-index="<?php echo $index; ?>">
                            <?php 
                            $imagePath = getFilePath($image['file_path']);
                            ?>
                            <img src="<?php echo $imagePath; ?>" 
                                 alt="<?php echo $image['title']; ?>"
                                 loading="lazy"
                                 data-src="<?php echo $imagePath; ?>"
                                 class="lazy-image"
                                 onload="checkImageOrientation(this)">
                            <div class="image-overlay">
                                <div style="font-size: 12px; font-weight: 500;"><?php echo $image['title']; ?></div>
                                <?php if (!empty($image['description'])): ?>
                                    <div style="font-size: 10px; margin-top: 3px;"><?php echo $image['description']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($images) > 5): ?>
                        <!-- Duplicate images for seamless scrolling -->
                        <?php foreach ($images as $index => $image): ?>
                        <div class="image-item" data-index="<?php echo $index + count($images); ?>">
                            <?php 
                            $imagePath = getFilePath($image['file_path']);
                            ?>
                            <img src="<?php echo $imagePath; ?>" 
                                 alt="<?php echo $image['title']; ?>"
                                 loading="lazy"
                                 data-src="<?php echo $imagePath; ?>"
                                 class="lazy-image"
                                 onload="checkImageOrientation(this)">
                            <div class="image-overlay">
                                <div style="font-size: 12px; font-weight: 500;"><?php echo $image['title']; ?></div>
                                <?php if (!empty($image['description'])): ?>
                                    <div style="font-size: 10px; margin-top: 3px;"><?php echo $image['description']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($images) && count($images) > 5): ?>
            <div class="image-controls">
                <button class="control-btn" onclick="pauseImages()" id="pauseBtn">
                    <i class="fas fa-pause"></i> Pause
                </button>
                <button class="control-btn" onclick="playImages()" id="playBtn" style="display: none;">
                    <i class="fas fa-play"></i> Play
                </button>
                <button class="control-btn" onclick="speedUpImages()">
                    <i class="fas fa-forward"></i> Faster
                </button>
                <button class="control-btn" onclick="slowDownImages()">
                    <i class="fas fa-backward"></i> Slower
                </button>
            </div>
            <?php elseif (!empty($images)): ?>
            <div class="image-controls">
                <div style="text-align: center; color: #666; font-size: 14px; padding: 10px;">
                    <i class="fas fa-info-circle"></i> Add more than 5 images for auto-scroll
                </div>
            </div>
            <?php endif; ?>
        </section>

        <!-- VIDEOS SECTION -->
        <section class="videos-section">
            <div class="section-header">
                <h2><i class="fas fa-video"></i> Video Gallery</h2>
                
            </div>
            
            <div class="videos-container" id="videosContainer">
                <?php if (empty($videos)): ?>
                    <div class="empty-state" style="width: 100%;">
                        <i class="fas fa-video"></i>
                        <h3>No Videos Available</h3>
                        <p>Videos will appear here once added by the admin.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($videos as $index => $video): ?>
                    <?php 
                    $videoPath = getFilePath($video['file_path']);
                    $thumbnailPath = getVideoThumbnail($videoPath, $video['title']);
                    ?>
                    
                    <div class="video-item" data-index="<?php echo $index; ?>">
                        <!-- Video container - SIMPLIFIED APPROACH -->
                        <div class="video-hover-container">
                            <!-- Preview video (paused at 0.01 seconds) -->
                            <video class="video-preview paused" 
                                   preload="metadata"
                                   muted
                                   playsinline
                                   data-src="<?php echo $videoPath; ?>"
                                   poster="<?php echo $thumbnailPath; ?>">
                                <source src="<?php echo $videoPath; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            
                            <!-- Hover video (will play on hover) -->
                            <video class="video-preview playing" 
                                   preload="metadata"
                                   muted
                                   loop
                                   playsinline
                                   data-src="<?php echo $videoPath; ?>">
                                <source src="<?php echo $videoPath; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            
                            <!-- Click Overlay (handles click for popup) -->
                            <div class="click-overlay" onclick="openVideoPopup('<?php echo $videoPath; ?>', '<?php echo htmlspecialchars($video['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($video['description'] ?? '', ENT_QUOTES); ?>')"></div>
                            
                            <!-- Play Overlay (shown on hover) -->
                            <div class="play-overlay">
                                <div class="play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            
                            <!-- Video Status -->
                            <div class="video-status">
                                <i class="fas fa-eye"></i>
                                <span>Hover to preview</span>
                            </div>
                            
                            <!-- Video Duration -->
                            <div class="video-duration">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $video['formatted_duration']; ?></span>
                            </div>
                        </div>
                        
                        <!-- Video Info -->
                        <div class="video-overlay">
                            <div class="video-title"><?php echo $video['title']; ?></div>
                            <div style="font-size: 12px; opacity: 0.8; margin-bottom: 5px;">
                                <i class="fas fa-video"></i> <?php echo $video['formatted_duration']; ?> • Click for full view
                            </div>
                            <?php if (!empty($video['description'])): ?>
                            <div style="font-size: 13px; opacity: 0.7; margin-top: 8px; line-height: 1.4;">
                                <?php echo $video['description']; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- ENHANCED Lightbox for images with PERFECT NAVIGATION -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <img id="lightboxImage" src="" alt="">
        </div>
        
        <!-- PERFECT LIGHTBOX NAVIGATION ARROWS - FIXED POSITION -->
        <div class="lightbox-nav">
            <button class="lightbox-arrow prev-lightbox-arrow" onclick="prevLightboxImage()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="lightbox-arrow next-lightbox-arrow" onclick="nextLightboxImage()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Lightbox info -->
        <div class="lightbox-info" id="lightboxInfo">
            <h3 id="lightboxTitle"></h3>
            <p id="lightboxDescription"></p>
        </div>
        
        <!-- PERFECT Close button -->
        <button class="lightbox-close" onclick="closeLightbox()">×</button>
    </div>
    
    <!-- Popup Overlay -->
    <div class="popup-overlay" id="popupOverlay" onclick="closeVideoPopup()"></div>
    
    <!-- Video Popup -->
    <div class="video-popup" id="videoPopup">
        <div class="video-popup-content" onclick="event.stopPropagation()">
            <button class="close-popup" onclick="closeVideoPopup()">×</button>
            
            <video id="popupVideo" class="popup-video" controls>
                <!-- Video source will be added dynamically -->
                Your browser does not support the video tag.
            </video>
            
            <div class="video-info">
                <h3 id="popupVideoTitle"></h3>
                <p id="popupVideoDescription"></p>
            </div>
            
            <button class="fullscreen-btn" onclick="toggleFullscreen()">
                <i class="fas fa-expand"></i> Fullscreen
            </button>
        </div>
    </div>

    <!-- JavaScript with PERFECT arrow functionality -->
    <script>
    // Store page data from PHP
    const pageData = <?php echo json_encode($pageData); ?>;
    
    // Animation variables
    let imageAnimationId = null;
    let currentSpeed = 1;
    let isPlaying = true;
    let imageTrack = null;
    let imageItems = null;
    let totalWidth = 0;
    let position = 0;
    let baseSpeed = 0.5;
    
    // Lightbox variables
    let currentLightboxIndex = 0;
    let isLightboxOpen = false;
    
    // Image gallery navigation
    let currentImageIndex = 0;
    let imagesPerView = 3;
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Gallery loaded:', pageData.stats);
        
        // Hide loading overlay after a short delay
        setTimeout(function() {
            const overlay = document.getElementById('loadingOverlay');
            const container = document.getElementById('momentsContainer');
            
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    overlay.style.display = 'none';
                    if (container) {
                        container.classList.add('loaded');
                    }
                    
                    // Initialize gallery functionality
                    initGallery();
                }, 500);
            }
        }, 300);
        
        // Set timeout to force hide loading after 5 seconds
        setTimeout(() => {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay && overlay.style.display !== 'none') {
                overlay.style.display = 'none';
                document.getElementById('momentsContainer').classList.add('loaded');
                initGallery();
            }
        }, 5000);
        
        // Calculate images per view based on screen width
        calculateImagesPerView();
        window.addEventListener('resize', calculateImagesPerView);
    });
    
    // Calculate how many images fit in the view
    function calculateImagesPerView() {
        const screenWidth = window.innerWidth;
        if (screenWidth <= 480) {
            imagesPerView = 1;
        } else if (screenWidth <= 768) {
            imagesPerView = 2;
        } else {
            imagesPerView = 3;
        }
        updateNavigationArrows();
    }
    
    // Check image orientation and apply appropriate styling
    function checkImageOrientation(img) {
        if (img.naturalWidth && img.naturalHeight) {
            const aspectRatio = img.naturalWidth / img.naturalHeight;
            const container = img.closest('.image-item');
            
            if (aspectRatio < 0.8) { // Portrait image (height > width)
                container.classList.add('portrait');
            } else { // Landscape or square image
                container.classList.remove('portrait');
            }
        }
    }
    
    // Initialize gallery functions
    function initGallery() {
        // Setup image gallery
        setupImageGallery();
        
        // Setup video previews
        setupVideoPreviews();
        
        // Setup lazy loading for images
        initLazyLoading();
        
        // Setup image click events for lightbox
        setupImageClickEvents();
        
        // Start image animation if needed
        if (pageData.images.length > 5) {
            updateAnimationSpeed();
        }
        
        // Update navigation arrows
        updateNavigationArrows();
    }
    
    // Lazy loading implementation
    function initLazyLoading() {
        const lazyImages = document.querySelectorAll('.lazy-image');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy-image');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }
    
    // Setup video previews
    function setupVideoPreviews() {
        const videoItems = document.querySelectorAll('.video-item');
        
        videoItems.forEach(item => {
            const container = item.querySelector('.video-hover-container');
            const previewVideo = container.querySelector('.video-preview.paused');
            const hoverVideo = container.querySelector('.video-preview.playing');
            
            if (!previewVideo || !hoverVideo) return;
            
            // Set the source for both videos
            const videoSrc = previewVideo.getAttribute('data-src');
            previewVideo.src = videoSrc;
            hoverVideo.src = videoSrc;
            
            // Setup preview video (paused at 0.01 seconds)
            previewVideo.addEventListener('loadedmetadata', function() {
                // Seek to 0.01 seconds to show content
                this.currentTime = 0.01;
                
                // Once seeking is complete, pause the video
                this.addEventListener('seeked', function() {
                    this.pause();
                    // Ensure it's paused at the right time
                    if (this.currentTime < 0.01) {
                        this.currentTime = 0.01;
                    }
                }, { once: true });
                
                // Trigger seek
                this.currentTime = 0.01;
            });
            
            // Setup hover video
            hoverVideo.addEventListener('loadedmetadata', function() {
                this.muted = true;
                this.loop = true;
                this.playsInline = true;
            });
            
            // Hover behavior
            container.addEventListener('mouseenter', function() {
                if (hoverVideo.readyState >= 2) {
                    hoverVideo.currentTime = 0.01;
                    hoverVideo.play().catch(e => {
                        console.log("Hover play prevented:", e);
                    });
                }
            });
            
            container.addEventListener('mouseleave', function() {
                hoverVideo.pause();
                hoverVideo.currentTime = 0.01;
            });
            
            // Load both videos
            previewVideo.load();
            hoverVideo.load();
        });
    }
    
    // Setup image gallery animation
    function setupImageGallery() {
        imageTrack = document.getElementById('imagesTrack');
        if (!imageTrack) return;
        
        imageItems = imageTrack.querySelectorAll('.image-item');
        if (imageItems.length === 0) return;
        
        // Calculate total width
        totalWidth = 0;
        imageItems.forEach(item => {
            totalWidth += item.offsetWidth + 20; // 20px gap
        });
        
        // Start animation if we have enough images
        if (pageData.images.length > 5) {
            startImageAnimation();
        }
    }
    
    // Setup image click events for lightbox
    function setupImageClickEvents() {
        const imageItems = document.querySelectorAll('.image-item');
        imageItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                const actualIndex = index % pageData.images.length;
                openLightbox(actualIndex);
            });
        });
    }
    
    // Start image animation
    function startImageAnimation() {
        if (imageAnimationId) {
            cancelAnimationFrame(imageAnimationId);
        }
        
        function animate() {
            if (isPlaying) {
                position -= baseSpeed * currentSpeed;
                
                // Reset position when scrolled past all duplicate images
                if (position <= -totalWidth / 2) {
                    position = 0;
                }
                
                if (imageTrack) {
                    imageTrack.style.transform = `translateX(${position}px)`;
                }
            }
            
            imageAnimationId = requestAnimationFrame(animate);
        }
        
        animate();
    }
    
    // Update animation speed
    function updateAnimationSpeed() {
        if (imageAnimationId) {
            cancelAnimationFrame(imageAnimationId);
            startImageAnimation();
        }
    }
    
    // PERFECTED IMAGE NAVIGATION FUNCTIONS
    function prevImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            scrollToImage(currentImageIndex);
        }
        updateNavigationArrows();
    }
    
    function nextImage() {
        const maxIndex = Math.max(0, pageData.images.length - imagesPerView);
        if (currentImageIndex < maxIndex) {
            currentImageIndex++;
            scrollToImage(currentImageIndex);
        }
        updateNavigationArrows();
    }
    
    function scrollToImage(index) {
        if (!imageTrack || !imageItems.length) return;
        
        const itemWidth = imageItems[0].offsetWidth + 20; // Include gap
        const scrollPosition = -index * itemWidth;
        
        // Smooth scroll animation
        imageTrack.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        imageTrack.style.transform = `translateX(${scrollPosition}px)`;
        
        // Remove transition after animation completes
        setTimeout(() => {
            imageTrack.style.transition = '';
        }, 400);
    }
    
    function updateNavigationArrows() {
        const prevArrow = document.querySelector('.prev-arrow');
        const nextArrow = document.querySelector('.next-arrow');
        
        if (prevArrow && nextArrow) {
            // Disable prev arrow if at start
            if (currentImageIndex <= 0) {
                prevArrow.classList.add('disabled');
            } else {
                prevArrow.classList.remove('disabled');
            }
            
            // Disable next arrow if at end
            const maxIndex = Math.max(0, pageData.images.length - imagesPerView);
            if (currentImageIndex >= maxIndex) {
                nextArrow.classList.add('disabled');
            } else {
                nextArrow.classList.remove('disabled');
            }
        }
    }
    
    // Image control functions
    function pauseImages() {
        isPlaying = false;
        document.getElementById('pauseBtn').style.display = 'none';
        document.getElementById('playBtn').style.display = 'flex';
    }
    
    function playImages() {
        isPlaying = true;
        document.getElementById('playBtn').style.display = 'none';
        document.getElementById('pauseBtn').style.display = 'flex';
    }
    
    function speedUpImages() {
        currentSpeed = Math.min(currentSpeed + 0.5, 3);
        updateAnimationSpeed();
    }
    
    function slowDownImages() {
        currentSpeed = Math.max(currentSpeed - 0.5, 0.5);
        updateAnimationSpeed();
    }
    
    // PERFECTED LIGHTBOX FUNCTIONS
    function openLightbox(index) {
        if (pageData.images.length === 0) return;
        
        currentLightboxIndex = index;
        isLightboxOpen = true;
        
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxDescription = document.getElementById('lightboxDescription');
        const image = pageData.images[index];
        
        if (!lightbox || !lightboxImage || !image) return;
        
        const imagePath = image.file_path.startsWith('./') ? image.file_path : './' + image.file_path;
        
        // Set image source
        lightboxImage.src = imagePath;
        lightboxImage.alt = image.title;
        
        // Set title and description
        if (lightboxTitle) lightboxTitle.textContent = image.title;
        if (lightboxDescription) {
            lightboxDescription.textContent = image.description || '';
            lightboxDescription.style.display = image.description ? 'block' : 'none';
        }
        
        // Show lightbox
        lightbox.style.display = 'flex';
        
        // Update lightbox navigation arrows
        updateLightboxArrows();
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Add keyboard navigation
        document.addEventListener('keydown', handleLightboxKeyboard);
    }
    
    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox) return;
        
        lightbox.style.display = 'none';
        isLightboxOpen = false;
        
        // Restore body scroll
        document.body.style.overflow = '';
        
        // Remove keyboard navigation
        document.removeEventListener('keydown', handleLightboxKeyboard);
    }
    
    function prevLightboxImage() {
        if (pageData.images.length === 0) return;
        
        currentLightboxIndex--;
        if (currentLightboxIndex < 0) {
            currentLightboxIndex = pageData.images.length - 1;
        }
        
        updateLightboxImage();
    }
    
    function nextLightboxImage() {
        if (pageData.images.length === 0) return;
        
        currentLightboxIndex++;
        if (currentLightboxIndex >= pageData.images.length) {
            currentLightboxIndex = 0;
        }
        
        updateLightboxImage();
    }
    
    function updateLightboxImage() {
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxDescription = document.getElementById('lightboxDescription');
        const image = pageData.images[currentLightboxIndex];
        
        if (!lightboxImage || !image) return;
        
        const imagePath = image.file_path.startsWith('./') ? image.file_path : './' + image.file_path;
        
        // Add fade effect
        lightboxImage.style.opacity = '0';
        
        setTimeout(() => {
            // Set image source
            lightboxImage.src = imagePath;
            lightboxImage.alt = image.title;
            
            // Set title and description
            if (lightboxTitle) lightboxTitle.textContent = image.title;
            if (lightboxDescription) {
                lightboxDescription.textContent = image.description || '';
                lightboxDescription.style.display = image.description ? 'block' : 'none';
            }
            
            // Fade in
            setTimeout(() => {
                lightboxImage.style.opacity = '1';
            }, 50);
        }, 300);
        
        // Update lightbox navigation arrows
        updateLightboxArrows();
    }
    
    function updateLightboxArrows() {
        const prevArrow = document.querySelector('.prev-lightbox-arrow');
        const nextArrow = document.querySelector('.next-lightbox-arrow');
        
        if (prevArrow && nextArrow) {
            // Always show arrows, never disable them (circular navigation)
            prevArrow.classList.remove('disabled');
            nextArrow.classList.remove('disabled');
            
            // Add subtle animation on arrow click
            prevArrow.style.transform = 'scale(0.9)';
            nextArrow.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                prevArrow.style.transform = '';
                nextArrow.style.transform = '';
            }, 300);
        }
    }
    
    function handleLightboxKeyboard(e) {
        if (!isLightboxOpen) return;
        
        switch(e.key) {
            case 'ArrowLeft':
                prevLightboxImage();
                break;
            case 'ArrowRight':
                nextLightboxImage();
                break;
            case 'Escape':
                closeLightbox();
                break;
        }
    }
    
    // Open video popup with sound
    function openVideoPopup(videoPath, title, description) {
        // Stop any hover videos from playing
        stopAllHoverVideos();
        
        const popupOverlay = document.getElementById('popupOverlay');
        const videoPopup = document.getElementById('videoPopup');
        const popupVideo = document.getElementById('popupVideo');
        const videoTitle = document.getElementById('popupVideoTitle');
        const videoDesc = document.getElementById('popupVideoDescription');
        
        if (!popupVideo || !videoPopup) return;
        
        // Set video source
        popupVideo.innerHTML = `<source src="${videoPath}" type="video/mp4">`;
        
        // Set title and description
        if (videoTitle) videoTitle.textContent = title;
        if (videoDesc) videoDesc.textContent = description || '';
        
        // Configure video for popup
        popupVideo.muted = false;
        popupVideo.loop = false;
        popupVideo.controls = true;
        
        // Show popup and overlay
        popupOverlay.style.display = 'block';
        videoPopup.style.display = 'block';
        
        // Load and play video
        popupVideo.load();
        
        // Try to play automatically
        const playPromise = popupVideo.play();
        
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.log("Autoplay was prevented:", error);
                // Video will need to be manually played by user
            });
        }
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    // Close video popup
    function closeVideoPopup() {
        const popupOverlay = document.getElementById('popupOverlay');
        const videoPopup = document.getElementById('videoPopup');
        const popupVideo = document.getElementById('popupVideo');
        
        if (popupVideo) {
            popupVideo.pause();
            popupVideo.currentTime = 0;
        }
        
        popupOverlay.style.display = 'none';
        videoPopup.style.display = 'none';
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
    
    // Stop all hover videos
    function stopAllHoverVideos() {
        const hoverVideos = document.querySelectorAll('.video-preview.playing');
        hoverVideos.forEach(video => {
            video.pause();
            video.currentTime = 0.01;
        });
    }
    
    // Toggle fullscreen for popup video
    function toggleFullscreen() {
        const videoPopup = document.getElementById('videoPopup');
        
        if (!document.fullscreenElement) {
            if (videoPopup.requestFullscreen) {
                videoPopup.requestFullscreen();
            } else if (videoPopup.webkitRequestFullscreen) {
                videoPopup.webkitRequestFullscreen();
            } else if (videoPopup.mozRequestFullScreen) {
                videoPopup.mozRequestFullScreen();
            } else if (videoPopup.msRequestFullscreen) {
                videoPopup.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }
    
    // Handle ESC key for closing modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (isLightboxOpen) {
                closeLightbox();
            } else {
                closeVideoPopup();
            }
            
            // Exit fullscreen if in fullscreen mode
            if (document.fullscreenElement) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }
    });
    
    // Handle fullscreen change
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    
    function handleFullscreenChange() {
        const fullscreenBtn = document.querySelector('.fullscreen-btn i');
        if (fullscreenBtn) {
            if (document.fullscreenElement || document.webkitFullscreenElement) {
                fullscreenBtn.className = 'fas fa-compress';
                fullscreenBtn.parentNode.innerHTML = '<i class="fas fa-compress"></i> Exit Fullscreen';
            } else {
                fullscreenBtn.className = 'fas fa-expand';
                fullscreenBtn.parentNode.innerHTML = '<i class="fas fa-expand"></i> Fullscreen';
            }
        }
    }
    
    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            calculateImagesPerView();
            
            if (imageTrack && imageItems) {
                // Recalculate total width
                totalWidth = 0;
                imageItems.forEach(item => {
                    totalWidth += item.offsetWidth + 20;
                });
            }
            
            // Adjust video popup position on resize
            if (document.getElementById('videoPopup').style.display === 'block') {
                const videoPopup = document.getElementById('videoPopup');
                videoPopup.style.transform = 'translate(-50%, -50%)';
            }
        }, 250);
    });
    
    // Prevent right-click on videos
    document.addEventListener('contextmenu', function(e) {
        if (e.target.tagName === 'VIDEO') {
            e.preventDefault();
            return false;
        }
    });
    
    // Handle popup video ended event
    document.addEventListener('DOMContentLoaded', function() {
        const popupVideo = document.getElementById('popupVideo');
        if (popupVideo) {
            popupVideo.addEventListener('ended', function() {
                // Loop the video in popup
                this.currentTime = 0;
                this.play();
            });
        }
    });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>