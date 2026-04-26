<?php
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

// Get page settings
$settings_result = $conn->query("SELECT * FROM volunteer_page_settings WHERE id = 1");
$settings = $settings_result->fetch_assoc();

// Get active volunteer opportunities (only if status = 'active')
$opportunities_result = $conn->query("SELECT * FROM volunteer_opportunities WHERE status = 'active' ORDER BY display_order ASC");
$has_opportunities = ($opportunities_result && $opportunities_result->num_rows > 0);

// Handle form submission
$form_submitted = false;
$form_error = '';
$application_id = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_volunteer'])) {
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $date_of_birth = !empty($_POST['date_of_birth']) ? $conn->real_escape_string($_POST['date_of_birth']) : null;
    $gender = $conn->real_escape_string($_POST['gender']);
    $occupation = $conn->real_escape_string(trim($_POST['occupation']));
    $skills = $conn->real_escape_string(trim($_POST['skills']));
    $interests = $conn->real_escape_string(trim($_POST['interests']));
    $availability = $conn->real_escape_string($_POST['availability']);
    $availability_days = $conn->real_escape_string(trim($_POST['availability_days']));
    $experience = $conn->real_escape_string(trim($_POST['experience']));
    $motivation = $conn->real_escape_string(trim($_POST['motivation']));
    $emergency_contact_name = $conn->real_escape_string(trim($_POST['emergency_contact_name']));
    $emergency_contact_phone = $conn->real_escape_string(trim($_POST['emergency_contact_phone']));
    $hear_about_us = $conn->real_escape_string(trim($_POST['hear_about_us']));
    
    // Insert application
    $sql = "INSERT INTO volunteer_applications (full_name, email, phone, address, date_of_birth, gender, occupation, skills, interests, availability, availability_days, experience, motivation, emergency_contact_name, emergency_contact_phone, hear_about_us, status, created_at) 
            VALUES ('$full_name', '$email', '$phone', '$address', '$date_of_birth', '$gender', '$occupation', '$skills', '$interests', '$availability', '$availability_days', '$experience', '$motivation', '$emergency_contact_name', '$emergency_contact_phone', '$hear_about_us', 'pending', NOW())";
    
    if ($conn->query($sql)) {
        $form_submitted = true;
        $application_id = $conn->insert_id;
    } else {
        $form_error = "There was an error submitting your application. Please try again.";
    }
}

$conn->close();

// Include top bar
include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Volunteer | Soka Toto Muda Initiative Trust</title>
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
            background-color: #ffffff;
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
            position: relative;
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?php echo !empty($settings['hero_image']) ? $settings['hero_image'] : 'images/volunteer-bg.jpg'; ?>');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: 0;
        }
        
        .page-header .container {
            position: relative;
            z-index: 1;
        }

        .page-header h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 16px;
        }

        .page-header h1 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .page-header p {
            font-size: clamp(1rem, 3vw, 1.2rem);
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
        }

        /* ========== SECTION STYLES ========== */
        .section {
            padding: 60px 0;
        }

        .section-alt {
            background-color: #f8fafc;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #0B1E3E;
        }

        .section-title span {
            border-bottom: 4px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        /* ========== WHY VOLUNTEER SECTION ========== */
        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .why-card {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .why-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #fabc01;
        }

        .why-icon {
            width: 70px;
            height: 70px;
            background: #fef9e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .why-icon i {
            font-size: 2rem;
            color: #fabc01;
        }

        .why-card h3 {
            font-size: 1.3rem;
            color: #0B1E3E;
            margin-bottom: 12px;
        }

        .why-card p {
            color: #475569;
            line-height: 1.6;
        }

        /* ========== OPPORTUNITIES SECTION ========== */
        .opportunities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .opportunity-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .opportunity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #fabc01;
        }

        .opportunity-header {
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            color: white;
            padding: 20px;
        }

        .opportunity-header h3 {
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .opportunity-badge {
            display: inline-block;
            background: #fabc01;
            color: #0B1E3E;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .opportunity-body {
            padding: 20px;
        }

        .opportunity-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .opportunity-info i {
            width: 20px;
            color: #fabc01;
        }

        .opportunity-description {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .opportunity-requirements {
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
        }

        .opportunity-requirements h4 {
            font-size: 0.85rem;
            color: #0B1E3E;
            margin-bottom: 8px;
        }

        .opportunity-requirements p {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
        }

        .apply-btn {
            display: inline-block;
            background: #0B1E3E;
            color: #fabc01;
            padding: 10px 25px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .apply-btn:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-2px);
        }

        /* ========== APPLICATION FORM ========== */
        .application-form {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #0B1E3E;
            font-size: 0.85rem;
        }

        .form-group label i {
            color: #fabc01;
            margin-right: 6px;
        }

        .form-group label .required {
            color: #e11d48;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #fabc01;
            box-shadow: 0 0 0 3px rgba(250, 188, 1, 0.1);
        }

        .submit-btn {
            background: #0B1E3E;
            color: #fabc01;
            padding: 14px 35px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-2px);
        }

        /* ========== SUCCESS TOAST NOTIFICATION ========== */
        .toast-notification {
            position: fixed;
            top: 100px;
            right: 30px;
            background: #22c55e;
            color: white;
            padding: 16px 28px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            font-weight: 500;
            font-size: 0.95rem;
            min-width: 320px;
            backdrop-filter: blur(8px);
            border-left: 4px solid #15803d;
        }
        
        .toast-notification.show {
            opacity: 1;
            visibility: visible;
        }
        
        .toast-notification i {
            font-size: 1.5rem;
        }
        
        .toast-notification .toast-content {
            flex: 1;
        }
        
        .toast-notification .toast-title {
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .toast-notification .toast-message {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        /* ========== ERROR MESSAGE ========== */
        .error-message {
            text-align: center;
            padding: 20px;
            background: #fee2e2;
            border-radius: 12px;
            color: #991b1b;
            margin-bottom: 20px;
        }

        /* ========== CONTACT INFO ========== */
        .contact-info {
            background: linear-gradient(135deg, #fef9e6, #fff9f0);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
        }

        .contact-info h3 {
            color: #0B1E3E;
            margin-bottom: 15px;
        }

        .contact-details {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .contact-details a {
            color: #0B1E3E;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s;
        }

        .contact-details a:hover {
            color: #fabc01;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .section {
                padding: 40px 0;
            }
            
            .section-title {
                font-size: 1.6rem;
                margin-bottom: 30px;
            }
            
            .application-form {
                padding: 25px;
            }
            
            .opportunities-grid {
                grid-template-columns: 1fr;
            }
            
            .toast-notification {
                top: 80px;
                right: 15px;
                left: 15px;
                min-width: auto;
                padding: 14px 20px;
            }
        }
    </style>
</head>
<body>



<div class="container">
    <!-- Why Volunteer Section -->
    <section class="section">
        <div class="section-title"><span>Why Volunteer With Us</span></div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Make a Difference</h3>
                <p>Your contribution directly impacts the lives of children and young mothers in need.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Meet Great People</h3>
                <p>Join a community of passionate individuals dedicated to creating positive change.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-chalkboard-user"></i>
                </div>
                <h3>Learn New Skills</h3>
                <p>Gain valuable experience and develop skills that enhance your personal and professional growth.</p>
            </div>
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Recognition</h3>
                <p>Receive a certificate of appreciation and letter of recommendation for your service.</p>
            </div>
        </div>
    </section>

    <!-- Volunteer Opportunities Section - ONLY SHOWS IF THERE ARE OPPORTUNITIES -->
    <?php if ($has_opportunities): ?>
    <section class="section section-alt">
        <div class="section-title"><span>Volunteer Opportunities</span></div>
        <div class="opportunities-grid">
            <?php while ($opp = $opportunities_result->fetch_assoc()): ?>
            <div class="opportunity-card">
                <div class="opportunity-header">
                    <h3><?php echo htmlspecialchars($opp['title']); ?></h3>
                    <span class="opportunity-badge"><?php echo $opp['slots_available']; ?> slots available</span>
                </div>
                <div class="opportunity-body">
                    <div class="opportunity-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($opp['location']); ?></span>
                    </div>
                    <div class="opportunity-info">
                        <i class="fas fa-clock"></i>
                        <span><?php echo htmlspecialchars($opp['commitment']); ?></span>
                    </div>
                    <p class="opportunity-description"><?php echo htmlspecialchars($opp['description']); ?></p>
                    <div class="opportunity-requirements">
                        <h4><i class="fas fa-check-circle"></i> Requirements:</h4>
                        <p><?php echo nl2br(htmlspecialchars($opp['requirements'])); ?></p>
                    </div>
                    <button onclick="scrollToForm('<?php echo htmlspecialchars($opp['title']); ?>')" class="apply-btn">
                        Apply Now <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Application Form Section -->
    <section id="application-form" class="section">
        <div class="section-title"><span>Apply to Volunteer</span></div>
        <div class="application-form">
            <?php if ($form_error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $form_error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="volunteerForm">
                <input type="hidden" name="apply_volunteer" value="1">
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone Number <span class="required">*</span></label>
                        <input type="tel" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Address</label>
                        <input type="text" name="address">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date of Birth</label>
                        <input type="date" name="date_of_birth">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-venus-mars"></i> Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-briefcase"></i> Occupation</label>
                        <input type="text" name="occupation">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-tools"></i> Skills/Qualifications</label>
                        <input type="text" name="skills" placeholder="e.g., Teaching, Coaching, Counseling...">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-heart"></i> Areas of Interest</label>
                    <textarea name="interests" rows="2" placeholder="Which volunteer activities interest you most?"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> Availability <span class="required">*</span></label>
                        <select name="availability" required>
                            <option value="">Select</option>
                            <option value="weekdays">Weekdays</option>
                            <option value="weekends">Weekends</option>
                            <option value="both">Both Weekdays & Weekends</option>
                            <option value="flexible">Flexible</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-week"></i> Available Days</label>
                        <input type="text" name="availability_days" placeholder="e.g., Monday, Wednesday, Friday">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-history"></i> Previous Volunteer Experience</label>
                    <textarea name="experience" rows="2" placeholder="Tell us about any previous volunteer work..."></textarea>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-quote-left"></i> Why do you want to volunteer with us? <span class="required">*</span></label>
                    <textarea name="motivation" rows="3" required placeholder="Share your motivation and what you hope to contribute..."></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user-friends"></i> Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone-alt"></i> Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-globe"></i> How did you hear about us?</label>
                    <select name="hear_about_us">
                        <option value="">Select</option>
                        <option value="social_media">Social Media</option>
                        <option value="friend">Friend/Family</option>
                        <option value="website">Website</option>
                        <option value="event">Community Event</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </form>
        </div>
    </section>

   
</div>

<!-- Success Toast Notification -->
<div id="successToast" class="toast-notification">
    <i class="fas fa-check-circle"></i>
    <div class="toast-content">
        <div class="toast-title">Success!</div>
        <div class="toast-message">Application submitted successfully! We'll review and get back to you soon.</div>
    </div>
</div>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>

<style>
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
</style>

<script>
    function scrollToForm(opportunityTitle) {
        const formSection = document.getElementById('application-form');
        if (formSection) {
            formSection.scrollIntoView({ behavior: 'smooth' });
            const interestsField = document.querySelector('textarea[name="interests"]');
            if (interestsField && opportunityTitle) {
                const currentValue = interestsField.value;
                if (!currentValue.includes(opportunityTitle)) {
                    interestsField.value = currentValue ? currentValue + ', ' + opportunityTitle : opportunityTitle;
                }
            }
        }
    }

    // Show success toast if form was submitted successfully
    <?php if ($form_submitted && !$form_error): ?>
    const successToast = document.getElementById('successToast');
    
    // Show the toast
    successToast.classList.add('show');
    
    // After 2 seconds, fade out
    setTimeout(() => {
        successToast.style.opacity = '0';
        successToast.style.visibility = 'hidden';
        setTimeout(() => {
            successToast.classList.remove('show');
            successToast.style.opacity = '';
            successToast.style.visibility = '';
        }, 300);
    }, 2000);
    
    // Reset the form
    document.getElementById('volunteerForm').reset();
    
    // Scroll to top of form to show toast
    document.getElementById('application-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    <?php endif; ?>

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