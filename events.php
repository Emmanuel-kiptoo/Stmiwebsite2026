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

/**
 * Fetch events by type
 */
function getEventsByType($conn, $eventType, $limit = null) {
    $sql = "SELECT * FROM events WHERE status = 'active' AND event_type = '$eventType' ORDER BY start_date ASC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $result = $conn->query($sql);
    
    if (!$result) {
        return [];
    }
    
    $events = [];
    while($row = $result->fetch_assoc()) {
        $row['title'] = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $row['description'] = htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8');
        $row['location'] = htmlspecialchars($row['location'] ?? '', ENT_QUOTES, 'UTF-8');
        $row['venue'] = htmlspecialchars($row['venue'] ?? '', ENT_QUOTES, 'UTF-8');
        $events[] = $row;
    }
    
    $result->free();
    return $events;
}

// Fetch events by type
$ongoingEvents = getEventsByType($conn, 'ongoing');
$upcomingEvents = getEventsByType($conn, 'upcoming');
$pastEvents = getEventsByType($conn, 'past');

// Get featured upcoming event (only if there are upcoming events)
$featuredEvent = null;
if (count($upcomingEvents) > 0) {
    $featuredResult = $conn->query("SELECT * FROM events WHERE status = 'active' AND event_type = 'upcoming' AND featured = 1 ORDER BY start_date ASC LIMIT 1");
    if ($featuredResult && $featuredResult->num_rows > 0) {
        $featuredEvent = $featuredResult->fetch_assoc();
        $featuredEvent['title'] = htmlspecialchars($featuredEvent['title'], ENT_QUOTES, 'UTF-8');
        $featuredEvent['description'] = htmlspecialchars($featuredEvent['description'] ?? '', ENT_QUOTES, 'UTF-8');
        $featuredEvent['venue'] = htmlspecialchars($featuredEvent['venue'] ?? '', ENT_QUOTES, 'UTF-8');
        $featuredEvent['location'] = htmlspecialchars($featuredEvent['location'] ?? '', ENT_QUOTES, 'UTF-8');
        $featuredResult->free();
    }
}

$conn->close();

// Helper function to format date
function formatEventDate($start_date, $end_date = null) {
    $start = date('M d, Y', strtotime($start_date));
    if ($end_date && $end_date != $start_date) {
        return $start . ' - ' . date('M d, Y', strtotime($end_date));
    }
    return $start;
}

// Helper function to get status badge class
function getStatusBadge($eventType) {
    switch($eventType) {
        case 'ongoing':
            return 'status-ongoing';
        case 'upcoming':
            return 'status-upcoming';
        case 'past':
            return 'status-past';
        default:
            return '';
    }
}

function getStatusText($eventType) {
    switch($eventType) {
        case 'ongoing':
            return 'Ongoing';
        case 'upcoming':
            return 'Upcoming';
        case 'past':
            return 'Completed';
        default:
            return '';
    }
}

function getStatusIcon($eventType) {
    switch($eventType) {
        case 'ongoing':
            return '<i class="fas fa-play"></i>';
        case 'upcoming':
            return '<i class="fas fa-clock"></i>';
        case 'past':
            return '<i class="fas fa-check-circle"></i>';
        default:
            return '';
    }
}

// IMPROVED: Helper function to get event image with multiple fallbacks
function getEventImage($event) {
    // Default placeholder images
    $placeholders = [
        'ongoing' => '/stmi/images/events/placeholder-ongoing.jpg',
        'upcoming' => '/stmi/images/events/placeholder-upcoming.jpg',
        'past' => '/stmi/images/events/placeholder-past.jpg'
    ];
    
    $defaultPlaceholder = '/stmi/images/events/placeholder.jpg';
    
    // If no image path in database, return placeholder
    if (empty($event['image_path'])) {
        return $placeholders[$event['event_type']] ?? $defaultPlaceholder;
    }
    
    $imagePath = $event['image_path'];
    
    // Define possible URL paths to try (in order of preference)
    $possiblePaths = [
        // Direct path from web root
        '/' . ltrim($imagePath, './'),
        // With stmi prefix (if site is in subdirectory)
        '/stmi/' . ltrim($imagePath, './'),
        // Original path as stored
        $imagePath,
        // Just the filename
        '/stmi/images/events/' . basename($imagePath),
        '/images/events/' . basename($imagePath)
    ];
    
    // Return the first path that we think will work
    // We can't check file_exists from here reliably, so return the most likely path
    // The browser will show broken image if wrong, but we try our best
    
    // Check if path already has a leading slash
    if (strpos($imagePath, '/') === 0) {
        return $imagePath;
    }
    
    // If it starts with images/ (no leading slash)
    if (strpos($imagePath, 'images/') === 0) {
        return '/stmi/' . $imagePath;
    }
    
    // Default: return with /stmi/ prefix
    return '/stmi/images/events/' . basename($imagePath);
}

// Check if there are any events at all
$hasEvents = (count($ongoingEvents) > 0 || count($upcomingEvents) > 0 || count($pastEvents) > 0);

// Get base URL for assets
$baseUrl = '/stmi/';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Events | Soka Toto Muda Initiative Trust</title>
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

    .page-header h1 i {
        color: #fabc01;
        margin-right: 10px;
    }

    .page-header p {
        font-size: clamp(0.95rem, 3vw, 1.1rem);
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
    }

    /* ========== FEATURED EVENT SECTION ========== */
    .featured-section {
        margin: 50px 0 30px;
    }

    .featured-card {
        background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.2);
    }

    .featured-image {
        flex: 1;
        min-width: 280px;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #0B1E3E;
        position: relative;
        min-height: 300px;
    }

    .featured-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #fabc01;
        color: #0B1E3E;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 2;
    }

    .featured-content {
        flex: 1;
        padding: 40px;
        color: white;
    }

    .featured-content h2 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        color: #fabc01;
    }

    .featured-date {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }

    .featured-date i {
        margin-right: 5px;
    }

    .featured-description {
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 25px;
        opacity: 0.95;
    }

    .featured-location {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        font-size: 0.9rem;
    }

    .featured-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fabc01;
        color: #0B1E3E;
        padding: 12px 28px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .featured-btn:hover {
        background: white;
        transform: translateY(-3px);
    }

    /* ========== SECTION STYLES ========== */
    .section {
        padding: 50px 0;
    }

    .section-alt {
        background-color: #ffffff;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-header h2 {
        font-size: 1.6rem;
        color: #0B1E3E;
        font-weight: 700;
    }

    .section-header h2 i {
        color: #fabc01;
        margin-right: 10px;
    }

    .section-header span {
        border-left: 3px solid #fabc01;
        padding-left: 12px;
    }

    .event-count {
        background: #e2e8f0;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #0B1E3E;
    }

    /* ========== EVENTS GRID ========== */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
    }

    .event-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
        border-color: #fabc01;
    }

    /* SHOWS FULL IMAGE WITHOUT CROPPING */
    .event-image {
        height: 200px;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #f8fafc;
        position: relative;
    }

    /* Alternative using img tag - BEST OPTION */
    .event-img-container {
        height: 200px;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .event-img-container img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    .event-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
        z-index: 2;
    }

    .status-ongoing {
        background: #10b981;
        color: white;
    }

    .status-upcoming {
        background: #fabc01;
        color: #0B1E3E;
    }

    .status-past {
        background: #64748b;
        color: white;
    }

    .event-content {
        padding: 22px;
    }

    .event-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0B1E3E;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .event-date {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.8rem;
        color: #64748b;
    }

    .event-date i {
        width: 16px;
        color: #fabc01;
    }

    .event-time {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.8rem;
        color: #64748b;
    }

    .event-time i {
        width: 16px;
        color: #fabc01;
    }

    .event-location {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 0.8rem;
        color: #64748b;
    }

    .event-location i {
        width: 16px;
        color: #fabc01;
    }

    .event-description {
        color: #475569;
        font-size: 0.85rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .event-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .event-link {
        color: #0B1E3E;
        font-weight: 600;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .event-link:hover {
        color: #fabc01;
        gap: 12px;
    }

    /* ========== NO EVENTS STATE ========== */
    .no-events-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        margin: 40px 0;
    }

    .no-events-state i {
        font-size: 64px;
        color: #fabc01;
        margin-bottom: 20px;
    }

    .no-events-state h3 {
        color: #0B1E3E;
        margin-bottom: 10px;
        font-size: 1.5rem;
    }

    .no-events-state p {
        color: #64748b;
        max-width: 500px;
        margin: 0 auto;
    }

    /* ========== CTA SECTION ========== */
    .cta-section {
        background: linear-gradient(135deg, #fef9e6, #fff9f0);
        border-radius: 24px;
        padding: 50px;
        text-align: center;
        margin: 40px 0 60px;
    }

    .cta-section h3 {
        font-size: 1.6rem;
        color: #0B1E3E;
        margin-bottom: 15px;
    }

    .cta-section p {
        color: #475569;
        margin-bottom: 25px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-btn {
        background: #0B1E3E;
        color: #fabc01;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .cta-btn:hover {
        background: #fabc01;
        color: #0B1E3E;
        transform: translateY(-3px);
    }

    .cta-btn-secondary {
        background: transparent;
        color: #0B1E3E;
        border: 2px solid #0B1E3E;
    }

    .cta-btn-secondary:hover {
        background: #0B1E3E;
        color: #fabc01;
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
        .featured-card {
            flex-direction: column;
        }
        .featured-image {
            height: 200px;
            min-height: 200px;
        }
        .featured-content {
            padding: 30px;
        }
        .featured-content h2 {
            font-size: 1.4rem;
        }
        .events-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        .section-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        .cta-section {
            padding: 30px 20px;
        }
        .cta-section h3 {
            font-size: 1.3rem;
        }
        .no-events-state {
            padding: 50px 20px;
        }
        .no-events-state i {
            font-size: 48px;
        }
        .event-image {
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .featured-content {
            padding: 25px;
        }
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        .cta-btn {
            width: 100%;
            justify-content: center;
        }
        .event-image {
            height: 160px;
        }
    }
</style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- ========== PAGE HEADER ========== -->
<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-calendar-alt"></i> Our Events</h1>
        <p>Stay updated with our latest events, programs, and activities. Join us in making a difference in the lives of children and young mothers.</p>
    </div>
</section>

<div class="container">
    <?php if (!$hasEvents): ?>
        <div class="no-events-state">
            <i class="fas fa-calendar-alt"></i>
            <h3>No Events Available</h3>
            <p>There are currently no events scheduled. Please check back later for updates on our upcoming programs and activities.</p>
        </div>
    <?php else: ?>
        
        <!-- Featured Event Section -->
        <?php if ($featuredEvent): ?>
        <div class="featured-section">
            <div class="featured-card">
                <div class="featured-image" style="background-image: url('<?php echo getEventImage($featuredEvent); ?>');"></div>
                <div class="featured-content">
                    <h2><?php echo $featuredEvent['title']; ?></h2>
                    <div class="featured-date">
                        <span><i class="fas fa-calendar-alt"></i> <?php echo formatEventDate($featuredEvent['start_date'], $featuredEvent['end_date']); ?></span>
                        <?php if (!empty($featuredEvent['start_time'])): ?>
                        <span><i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($featuredEvent['start_time'])); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="featured-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo !empty($featuredEvent['venue']) ? $featuredEvent['venue'] : $featuredEvent['location']; ?></span>
                    </div>
                    <p class="featured-description"><?php echo nl2br(htmlspecialchars_decode(substr($featuredEvent['description'], 0, 300))) . (strlen($featuredEvent['description']) > 300 ? '...' : ''); ?></p>
                    <?php if (!empty($featuredEvent['registration_link'])): ?>
                    <a href="<?php echo $featuredEvent['registration_link']; ?>" class="featured-btn" target="_blank">
                        <i class="fas fa-ticket-alt"></i> Register Now
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ONGOING EVENTS SECTION -->
        <?php if (count($ongoingEvents) > 0): ?>
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-play-circle"></i> <span>Ongoing Events</span></h2>
                <div class="event-count"><?php echo count($ongoingEvents); ?> active events</div>
            </div>
            <div class="events-grid">
                <?php foreach ($ongoingEvents as $event): ?>
                <div class="event-card">
                    <div class="event-image-wrapper">
                        <img src="<?php echo getEventImage($event); ?>" alt="<?php echo $event['title']; ?>">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title"><?php echo $event['title']; ?></h3>
                        <div class="event-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?php echo formatEventDate($event['start_date'], $event['end_date']); ?></span>
                        </div>
                        <?php if (!empty($event['start_time'])): ?>
                        <div class="event-time">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('g:i A', strtotime($event['start_time'])); ?> <?php echo !empty($event['end_time']) ? '- ' . date('g:i A', strtotime($event['end_time'])) : ''; ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo !empty($event['venue']) ? $event['venue'] : $event['location']; ?></span>
                        </div>
                        <p class="event-description"><?php echo nl2br(htmlspecialchars_decode(substr($event['description'], 0, 120))) . (strlen($event['description']) > 120 ? '...' : ''); ?></p>
                        <div class="event-footer">
                            <?php if (!empty($event['registration_link'])): ?>
                            <a href="<?php echo $event['registration_link']; ?>" class="event-link" target="_blank">
                                Register <i class="fas fa-arrow-right"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($event['contact_email'])): ?>
                            <a href="mailto:<?php echo $event['contact_email']; ?>" class="event-link">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- UPCOMING EVENTS SECTION -->
        <?php if (count($upcomingEvents) > 0): ?>
        <div class="section section-alt">
            <div class="section-header">
                <h2><i class="fas fa-calendar-week"></i> <span>Upcoming Events</span></h2>
                <div class="event-count"><?php echo count($upcomingEvents); ?> upcoming events</div>
            </div>
            <div class="events-grid">
                <?php foreach ($upcomingEvents as $event): ?>
                <div class="event-card">
                    <div class="event-image" style="background-image: url('<?php echo getEventImage($event); ?>');"></div>
                    <div class="event-content">
                        <h3 class="event-title"><?php echo $event['title']; ?></h3>
                        <div class="event-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?php echo formatEventDate($event['start_date'], $event['end_date']); ?></span>
                        </div>
                        <?php if (!empty($event['start_time'])): ?>
                        <div class="event-time">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('g:i A', strtotime($event['start_time'])); ?> <?php echo !empty($event['end_time']) ? '- ' . date('g:i A', strtotime($event['end_time'])) : ''; ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo !empty($event['venue']) ? $event['venue'] : $event['location']; ?></span>
                        </div>
                        <p class="event-description"><?php echo nl2br(htmlspecialchars_decode(substr($event['description'], 0, 120))) . (strlen($event['description']) > 120 ? '...' : ''); ?></p>
                        <div class="event-footer">
                            <?php if (!empty($event['registration_link'])): ?>
                            <a href="<?php echo $event['registration_link']; ?>" class="event-link" target="_blank">
                                Register <i class="fas fa-arrow-right"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($event['contact_email'])): ?>
                            <a href="mailto:<?php echo $event['contact_email']; ?>" class="event-link">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- PAST EVENTS SECTION -->
        <?php if (count($pastEvents) > 0): ?>
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-history"></i> <span>Past Events</span></h2>
                <div class="event-count"><?php echo count($pastEvents); ?> completed events</div>
            </div>
            <div class="events-grid">
                <?php foreach ($pastEvents as $event): ?>
                <div class="event-card">
                    <div class="event-image" style="background-image: url('<?php echo getEventImage($event); ?>');"></div>
                    <div class="event-content">
                        <h3 class="event-title"><?php echo $event['title']; ?></h3>
                        <div class="event-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?php echo formatEventDate($event['start_date'], $event['end_date']); ?></span>
                        </div>
                        <?php if (!empty($event['start_time'])): ?>
                        <div class="event-time">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('g:i A', strtotime($event['start_time'])); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo !empty($event['venue']) ? $event['venue'] : $event['location']; ?></span>
                        </div>
                        <p class="event-description"><?php echo nl2br(htmlspecialchars_decode(substr($event['description'], 0, 120))) . (strlen($event['description']) > 120 ? '...' : ''); ?></p>
                        <div class="event-footer">
                            <a href="#" class="event-link" onclick="alert('Event photos and report coming soon! Contact us for more information.'); return false;">
                                <i class="fas fa-images"></i> View Gallery
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    <?php endif; ?>

    
</div>

<script>
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