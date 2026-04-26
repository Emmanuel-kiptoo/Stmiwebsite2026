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

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    // Get IP address and user agent
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $phone, $subject, $message, $ip_address, $user_agent);
        
        if ($stmt->execute()) {
            $success_message = 'Thank you for your message. We will get back to you soon!';
            
            // Optional: Send email notification
            $to = 'info@stmitrust.org';
            $email_subject = "New Contact Message: $subject";
            $email_body = "Name: $name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Phone: $phone\n\n";
            $email_body .= "Message:\n$message\n";
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            
            // Uncomment to enable email sending
            // mail($to, $email_subject, $email_body, $headers);
        } else {
            $error_message = 'Sorry, something went wrong. Please try again later.';
        }
        $stmt->close();
    }
}

// Handle newsletter subscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_submit'])) {
    $newsletter_email = filter_var(trim($_POST['newsletter_email'] ?? ''), FILTER_SANITIZE_EMAIL);
    
    if (filter_var($newsletter_email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?) ON DUPLICATE KEY UPDATE status = 'active', subscribed_at = CURRENT_TIMESTAMP");
        $stmt->bind_param("s", $newsletter_email);
        
        if ($stmt->execute()) {
            $newsletter_success = 'Successfully subscribed to our newsletter!';
        } else {
            $newsletter_error = 'Subscription failed. Please try again.';
        }
        $stmt->close();
    } else {
        $newsletter_error = 'Please enter a valid email address.';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Contact Us | Soka Toto Muda Initiative Trust</title>
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

        /* ========== CONTACT GRID ========== */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin: 60px 0 40px 0;
        }

        /* ========== CONTACT FORM SECTION ========== */
        .form-section {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .form-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 8px;
        }

        .form-section h2 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .form-section .subtitle {
            color: #64748b;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #fabc01;
            display: inline-block;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert i {
            font-size: 1.2rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #0B1E3E;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 3px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #fabc01;
            box-shadow: 0 0 0 3px rgba(250, 188, 1, 0.1);
            background: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            background: #0B1E3E;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* ========== LOCATION SECTION ========== */
        .location-section {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .location-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0B1E3E;
            margin-bottom: 8px;
        }

        .location-section h2 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .location-section .subtitle {
            color: #64748b;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #fabc01;
            display: inline-block;
        }

        /* Contact Info Cards */
        .contact-info {
            margin-bottom: 25px;
        }

        .info-card {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-card:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: #fef9e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 1.2rem;
            color: #fabc01;
        }

        .info-content h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #0B1E3E;
            margin-bottom: 5px;
        }

        .info-content p,
        .info-content a {
            color: #475569;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .info-content a:hover {
            color: #fabc01;
        }

        /* Google Maps */
        .map-container {
            border-radius: 16px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 340px;
            border: none;
        }

        /* ========== NEWSLETTER SECTION ========== */
        .newsletter-section {
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            border-radius: 24px;
            padding: 50px 40px;
            margin: 40px 0 60px 0;
            text-align: center;
            color: white;
        }

        .newsletter-section h3 {
            font-size: 1.8rem;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .newsletter-section h3 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .newsletter-section p {
            margin-bottom: 25px;
            opacity: 0.9;
            font-size: 1rem;
        }

        .newsletter-form {
            display: flex;
            max-width: 550px;
            margin: 0 auto;
            gap: 12px;
            flex-wrap: wrap;
        }

        .newsletter-form input {
            flex: 1;
            padding: 14px 22px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            background: white;
        }

        .newsletter-form input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(250, 188, 1, 0.3);
        }

        .newsletter-form button {
            background: #fabc01;
            color: #0B1E3E;
            border: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .newsletter-form button:hover {
            transform: translateY(-2px);
            background: #ffdd44;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .newsletter-alert {
            max-width: 550px;
            margin: 15px auto 0;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .newsletter-alert-success {
            background: rgba(34, 197, 94, 0.2);
            color: #dcfce7;
        }

        .newsletter-alert-error {
            background: rgba(239, 68, 68, 0.2);
            color: #fee2e2;
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
        @media (max-width: 992px) {
            .contact-grid {
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 30px;
                margin: 40px 0 30px 0;
            }
            
            .form-section,
            .location-section {
                padding: 25px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .form-section h2,
            .location-section h2 {
                font-size: 1.5rem;
            }
            
            .newsletter-section {
                padding: 35px 25px;
                margin: 30px 0 50px 0;
            }
            
            .newsletter-section h3 {
                font-size: 1.4rem;
            }
            
            .newsletter-form {
                flex-direction: column;
            }
            
            .newsletter-form button {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .form-section,
            .location-section {
                padding: 20px;
            }
            
            .info-card {
                gap: 12px;
            }
            
            .info-icon {
                width: 40px;
                height: 40px;
            }
            
            .info-icon i {
                font-size: 1rem;
            }
            
            .submit-btn {
                width: 100%;
                justify-content: center;
            }
            
            .map-container iframe {
                height: 260px;
            }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>



<!-- Contact Grid Section: Send Us a Message + Google Map -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            
            <!-- LEFT SIDE: SEND US A MESSAGE FORM -->
            <div class="form-section">
                <h2><i class="fas fa-envelope"></i> Send Us a Message</h2>
                <div class="subtitle">We'll get back to you within 24 hours</div>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="Enter your phone number">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" placeholder="Subject of your message">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Your Message <span class="required">*</span></label>
                        <textarea name="message" placeholder="Write your message here..." required></textarea>
                    </div>
                    
                    <input type="hidden" name="contact_submit" value="1">
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
            
            <!-- RIGHT SIDE: GOOGLE MAP LOCATION -->
            <div class="location-section">
                <h2><i class="fas fa-map-marker-alt"></i> Our Location</h2>
                <div class="subtitle">Find us on the map</div>
                
              
                
                <!-- Google Maps -->
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d255282.32384912053!2d36.68249122540798!3d-1.286779899796878!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1172d84d49a7%3A0xf7cf0254b297924c!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
        
        <!-- NEWSLETTER SECTION - Below the two grids -->
        <div class="newsletter-section">
            <h3><i class="fas fa-newspaper"></i> Subscribe to Our Newsletter</h3>
            <p>Stay updated with our latest news, events, and impact stories.</p>
            
            <?php if (isset($newsletter_success)): ?>
                <div class="newsletter-alert newsletter-alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $newsletter_success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($newsletter_error)): ?>
                <div class="newsletter-alert newsletter-alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $newsletter_error; ?>
                </div>
            <?php endif; ?>
            
            <form class="newsletter-form" method="POST" action="">
                <input type="email" name="newsletter_email" placeholder="Enter your email address" required>
                <input type="hidden" name="newsletter_submit" value="1">
                <button type="submit"><i class="fas fa-paper-plane"></i> Subscribe</button>
            </form>
        </div>
    </div>
</section>

<script>
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
    
    // ========== FORM VALIDATION ==========
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const message = this.querySelector('textarea[name="message"]').value.trim();
            
            if (!name || !email || !message) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
            
            return true;
        });
    }
</script>

<?php include 'footer.php'; ?>
</body>
</html>