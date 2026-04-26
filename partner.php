<?php
// Start session
session_start();

// Include top bar
include 'top-bar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Partner With Us | Soka Toto Muda Initiative Trust</title>
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

        /* ========== INTRO SECTION ========== */
        .intro-section {
            padding: 60px 0 30px;
            text-align: center;
        }

        .intro-section h2 {
            font-size: 2rem;
            color: #0B1E3E;
            margin-bottom: 20px;
        }

        .intro-section h2 span {
            border-bottom: 4px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        .intro-section p {
            max-width: 800px;
            margin: 0 auto;
            color: #475569;
            font-size: 1.1rem;
            line-height: 1.7;
        }

        /* ========== PARTNERSHIP TYPES SECTION ========== */
        .partnership-types {
            padding: 40px 0 60px;
        }

        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .type-card {
            background: white;
            border-radius: 24px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .type-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
            border-color: #fabc01;
        }

        .type-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .type-icon i {
            font-size: 2rem;
            color: #fabc01;
        }

        .type-card h3 {
            font-size: 1.4rem;
            color: #0B1E3E;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .type-card p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* ========== CURRENT PARTNERS SECTION ========== */
        .current-partners {
            background: #f1f5f9;
            padding: 60px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h2 {
            font-size: 2rem;
            color: #0B1E3E;
            font-weight: 700;
        }

        .section-title h2 i {
            color: #fabc01;
            margin-right: 10px;
        }

        .section-title span {
            border-bottom: 4px solid #fabc01;
            display: inline-block;
            padding-bottom: 6px;
        }

        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            align-items: center;
            justify-items: center;
        }

        .partner-logo {
            background: white;
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .partner-logo:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .partner-logo i {
            font-size: 3rem;
            color: #0B1E3E;
        }

        .partner-logo h4 {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #475569;
        }

        /* ========== BENEFITS SECTION ========== */
        .benefits-section {
            padding: 60px 0;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .benefit-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
            border-color: #fabc01;
        }

        .benefit-icon {
            width: 70px;
            height: 70px;
            background: #fef9e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .benefit-icon i {
            font-size: 1.8rem;
            color: #fabc01;
        }

        .benefit-card h3 {
            font-size: 1.2rem;
            color: #0B1E3E;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .benefit-card p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ========== ENHANCED PARTNERSHIP FORM SECTION ========== */
        .form-section {
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            padding: 80px 0;
            position: relative;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(250,188,1,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            pointer-events: none;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 32px;
            padding: 50px;
            color: #1e293b;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 2;
        }

        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .form-header h2 {
            color: #0B1E3E;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .form-header h2 i {
            color: #fabc01;
            margin-right: 12px;
        }

        .form-header .form-subtitle {
            color: #64748b;
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .form-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 25px 0;
        }

        .form-divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        .form-divider-icon {
            width: 40px;
            height: 40px;
            background: #fef9e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #0B1E3E;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #e11d48;
            margin-left: 3px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-group input:hover,
        .form-group select:hover,
        .form-group textarea:hover {
            border-color: #cbd5e1;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #fabc01;
            box-shadow: 0 0 0 4px rgba(250, 188, 1, 0.1);
            background: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Input with icon */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .input-with-icon input,
        .input-with-icon select {
            padding-left: 45px;
        }

        .input-with-icon input:focus + i,
        .input-with-icon select:focus + i {
            color: #fabc01;
        }

        .submit-btn {
            background: linear-gradient(135deg, #0B1E3E 0%, #1a3a6e 100%);
            color: #fabc01;
            border: none;
            padding: 16px 32px;
            border-radius: 60px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(250, 188, 1, 0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #fabc01 0%, #ffd700 100%);
            color: #0B1E3E;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }

        /* Form Footer Note */
        .form-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .form-footer p {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .form-footer p i {
            color: #fabc01;
            margin-right: 5px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 16px;
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

        /* ========== CTA SECTION ========== */
        .cta-section {
            padding: 60px 0;
            text-align: center;
        }

        .cta-box {
            background: linear-gradient(135deg, #fef9e6, #fff9f0);
            border-radius: 24px;
            padding: 50px;
            border: 2px solid #fabc01;
        }

        .cta-box h3 {
            font-size: 1.8rem;
            color: #0B1E3E;
            margin-bottom: 15px;
        }

        .cta-box p {
            color: #475569;
            font-size: 1rem;
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

        .cta-btn-primary {
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

        .cta-btn-primary:hover {
            background: #fabc01;
            color: #0B1E3E;
            transform: translateY(-3px);
        }

        .cta-btn-secondary {
            background: transparent;
            color: #0B1E3E;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid #0B1E3E;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .cta-btn-secondary:hover {
            background: #0B1E3E;
            color: #fabc01;
            transform: translateY(-3px);
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
            .form-container {
                padding: 30px 25px;
                margin: 0 20px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
            .form-header h2 {
                font-size: 1.6rem;
            }
            .cta-box {
                padding: 30px 20px;
            }
            .cta-box h3 {
                font-size: 1.4rem;
            }
            .partners-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .partners-grid {
                grid-template-columns: 1fr;
            }
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            .types-grid {
                gap: 20px;
            }
            .type-card {
                padding: 25px 20px;
            }
            .form-container {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>



<!-- Intro Section -->
<section class="intro-section">
    <div class="container">
        <h2><span>Why Partner With Us?</span></h2>
        <p>At Soka Toto Muda Initiative Trust, we believe that meaningful change happens when we work together. By partnering with us, you become part of a movement that transforms lives through sports, creative arts, and psychosocial support.</p>
    </div>
</section>

<!-- Partnership Types Section -->
<section class="partnership-types">
    <div class="container">
        <div class="types-grid">
            <div class="type-card">
                <div class="type-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Corporate Partners</h3>
                <p>Join leading companies that are making a difference through corporate social responsibility.</p>
            </div>

            <div class="type-card">
                <div class="type-icon">
                    <i class="fas fa-church"></i>
                </div>
                <h3>Faith Partners</h3>
                <p>Partner with us in spreading hope and transforming communities through faith-based initiatives.</p>
            </div>

            <div class="type-card">
                <div class="type-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>International Partners</h3>
                <p>Global organizations and foundations supporting sustainable development in Kenya.</p>
            </div>

            <div class="type-card">
                <div class="type-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3>Individual Partners</h3>
                <p>Individuals passionate about making a difference in children's lives.</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-gift"></i> <span>Partnership Benefits</span></h2>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Measurable Impact</h3>
                <p>Regular reports showing how your contribution is transforming lives and communities.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Community Engagement</h3>
                <p>Opportunities to visit our programs and meet the children and families you're helping.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Recognition</h3>
                <p>Logo placement on our website, social media recognition, and annual partner appreciation events.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Tax Benefits</h3>
                <p>All donations are tax-deductible, and we provide official receipts for your records.</p>
            </div>
        </div>
    </div>
</section>

<!-- Current Partners Section - DYNAMIC FROM DATABASE -->
<section class="current-partners">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-handshake"></i> <span>Our Valued Partners</span></h2>
        </div>
        <div class="partners-grid">
            <?php
            // Database connection for frontend
            $conn_front = new mysqli('localhost', 'root', '', 'stmitrust2026');
            if (!$conn_front->connect_error) {
                $partners_result = $conn_front->query("SELECT name, logo_path, website_url FROM partners WHERE status = 'active' ORDER BY display_order ASC");
                if ($partners_result && $partners_result->num_rows > 0):
                    while ($partner = $partners_result->fetch_assoc()):
            ?>
            <div class="partner-logo">
                <?php if (!empty($partner['logo_path']) && file_exists($partner['logo_path'])): ?>
                    <img src="<?php echo $partner['logo_path']; ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>" style="max-width: 120px; max-height: 80px; object-fit: contain;">
                <?php else: ?>
                    <i class="fas fa-building"></i>
                <?php endif; ?>
                <h4><?php echo htmlspecialchars($partner['name']); ?></h4>
            </div>
            <?php 
                    endwhile;
                else:
            ?>
            <!-- Fallback static partners -->
            <div class="partner-logo">
                <i class="fas fa-building"></i>
                <h4>Global Aid</h4>
            </div>
            <div class="partner-logo">
                <i class="fas fa-church"></i>
                <h4>Hope Foundation</h4>
            </div>
            <div class="partner-logo">
                <i class="fas fa-graduation-cap"></i>
                <h4>EduAction</h4>
            </div>
            <div class="partner-logo">
                <i class="fas fa-leaf"></i>
                <h4>GreenFuture</h4>
            </div>
            <div class="partner-logo">
                <i class="fas fa-handshake"></i>
                <h4>UN Volunteers</h4>
            </div>
            <?php 
                endif;
                $conn_front->close();
            } 
            ?>
        </div>
    </div>
</section>

<!-- Partnership Form Section - ENHANCED STYLED -->
<section class="form-section">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-envelope"></i> Become a Partner</h2>
                
            </div>
            
            <div class="form-divider">
                <div class="form-divider-line"></div>
                <div class="form-divider-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="form-divider-line"></div>
            </div>
            
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Thank you for your interest in partnering with us! Our team will contact you shortly.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="process_partner.php" id="partnerForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="Enter your full name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Organization Name</label>
                        <div class="input-with-icon">
                            <i class="fas fa-building"></i>
                            <input type="text" name="organization" placeholder="Enter organization name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-phone-alt"></i>
                            <input type="tel" name="phone" placeholder="Enter your phone number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Partnership Type <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-tag"></i>
                            <select name="partnership_type" required>
                                <option value="">Select partnership type</option>
                                <option value="corporate">Corporate Partner</option>
                                <option value="faith">Faith Partner</option>
                                <option value="international">International Partner</option>
                                <option value="individual">Individual Partner</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Preferred Partnership Level</label>
                        <div class="input-with-icon">
                            <i class="fas fa-level-up-alt"></i>
                            <select name="partnership_level">
                                <option value="">Select level (optional)</option>
                                <option value="bronze">Bronze Partner</option>
                                <option value="silver">Silver Partner</option>
                                <option value="gold">Gold Partner</option>
                                <option value="platinum">Platinum Partner</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label>Message / How would you like to partner? <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <i class="fas fa-comment-dots" style="top: 20px; transform: none;"></i>
                            <textarea name="message" placeholder="Tell us how you'd like to support our mission..." required></textarea>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Partnership Inquiry
                    <i class="fas fa-arrow-right"></i>
                </button>
                
                <div class="form-footer">
                    <p><i class="fas fa-lock"></i> Your information is secure and will only be used to respond to your inquiry.</p>
                </div>
            </form>
        </div>
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

    // Form validation
    const partnerForm = document.getElementById('partnerForm');
    if (partnerForm) {
        partnerForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const phone = this.querySelector('input[name="phone"]').value.trim();
            const partnershipType = this.querySelector('select[name="partnership_type"]').value;
            const message = this.querySelector('textarea[name="message"]').value.trim();
            
            if (!name || !email || !phone || !partnershipType || !message) {
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