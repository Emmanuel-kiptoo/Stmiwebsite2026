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
    <title>Donate - Support Our Mission | Soka Toto Muda Initiative Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Donate Page Styles */
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

        /* Hero Section */
        .donate-hero {
            position: relative;
            width: 100%;
            height: 350px;
            background: linear-gradient(135deg, rgba(11, 30, 62, 0.85), rgba(26, 58, 110, 0.9)),
                url('images/donate-hero.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .donate-hero-content {
            max-width: 800px;
            padding: 0 20px;
        }

        .donate-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #fabc01;
        }

        .donate-hero p {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Main Container */
        .donate-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 24px;
        }

        /* Two Column Grid */
        .donate-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        .donate-column {
            display: flex;
            flex-direction: column;
        }

        /* Column Header */
        .column-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
            font-size: 1.5rem;
        }

        .column-header h2 {
            color: #0B1E3E;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        /* Column 1: Thanks Column */
        .thanks-column .header-icon {
            background: linear-gradient(135deg, #02012b, #02012b);
            color: white;
        }

        .thanks-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .greeting h3 {
            color: #0B1E3E;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .greeting p {
            color: #475569;
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Bible Verse */
        .bible-verse {
            background: linear-gradient(135deg, #fef9e6, #fff9f0);
            padding: 25px;
            border-radius: 16px;
            border-left: 4px solid #fabc01;
        }

        .verse-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .bible-verse blockquote {
            color: #1e293b;
            font-size: 1.3rem;
            line-height: 1.6;
            font-style: italic;
            margin: 0 0 10px 0;
            padding: 0;
        }

        .bible-verse blockquote::before {
            content: "“";
            font-size: 2em;
            color: #fabc01;
            line-height: 0.1em;
            margin-right: 0.1em;
            vertical-align: -0.3em;
        }

        .bible-verse blockquote::after {
            content: "”";
            font-size: 2em;
            color: #fabc01;
            line-height: 0.1em;
            margin-left: 0.1em;
            vertical-align: -0.3em;
        }

        .bible-verse cite {
            display: block;
            text-align: right;
            color: #64748b;
            font-style: normal;
            font-weight: 600;
            font-size: 0.95rem;
            margin-top: 10px;
        }

        /* Impact Statement */
        .impact-statement h4 {
            color: #0B1E3E;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .impact-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .impact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .impact-item:hover {
            transform: translateX(10px);
            border-color: #fabc01;
        }

        .impact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .impact-item:nth-child(2) .impact-icon {
            background: linear-gradient(135deg, #030224, #130241);
            color: #e3f304;
        }

        .impact-item:nth-child(3) .impact-icon {
            background: linear-gradient(135deg, #030224, #130241);
            color: white;
        }

        .impact-text h5 {
            color: #0B1E3E;
            margin: 0 0 8px 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .impact-text p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Transparency Note */
        .transparency-note {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 16px;
            border: 1px solid #bae6fd;
        }

        .transparency-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .transparency-text h5 {
            color: #0B1E3E;
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .transparency-text p {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Other Ways to Support */
        .other-ways {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            padding: 25px;
            border-radius: 16px;
            border: 2px dashed #0B1E3E;
            margin-top: 20px;
        }

        .other-ways h4 {
            color: #0B1E3E;
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
        }

        .ways-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .way-item {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .way-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #fabc01;
        }

        .way-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fabc01;
            font-size: 1.3rem;
            margin: 0 auto 15px;
        }

        .way-text h5 {
            color: #0B1E3E;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .way-text p {
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.4;
            margin: 0;
        }

        /* Column 2: Payment Methods Column */
        .methods-column .header-icon {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Payment Method Cards */
        .payment-method {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .method-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .method-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .mpesa-method .method-icon {
            background: linear-gradient(135deg, #ff6b00, #ff8c00);
        }

        .bank-method .method-icon {
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
        }

        .method-header h3 {
            color: #0B1E3E;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            flex: 1;
        }

        .method-badge {
            background: #c2940a;
            color: #0B1E3E;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* MPESA Instructions */
        .method-instructions h4 {
            color: #0B1E3E;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .mpesa-steps {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            counter-reset: step-counter;
        }

        .mpesa-steps li {
            counter-increment: step-counter;
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 12px;
            padding-left: 35px;
            position: relative;
        }

        .mpesa-steps li::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 25px;
            height: 25px;
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            color: #fabc01;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .mpesa-steps li strong {
            color: #0B1E3E;
            font-weight: 700;
        }

        /* Method Details */
        .method-details {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 10px;
        }

        .detail-label {
            color: #0B1E3E;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label i {
            color: #fabc01;
            font-size: 1rem;
        }

        .detail-value {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .highlight-number {
            background: #0B1E3E;
            color: #fabc01;
            padding: 6px 12px;
            border-radius: 8px;
            font-family: monospace;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .copy-btn {
            background: #fabc01;
            color: #0B1E3E;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .copy-btn:hover {
            background: #0B1E3E;
            color: #fabc01;
            transform: translateY(-2px);
        }

        /* Method Note */
        .method-note {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: #f0f9ff;
            border-radius: 12px;
            margin-top: 20px;
            border-left: 4px solid #0B1E3E;
        }

        .method-note i {
            color: #0B1E3E;
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .method-note p {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
            flex: 1;
        }

        /* Receipt Section */
        .receipt-section {
            background: linear-gradient(135deg, #0B1E3E, #1a3a6e);
            border-radius: 24px;
            padding: 40px;
            color: white;
            margin-bottom: 60px;
        }

        .receipt-content {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .receipt-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .receipt-icon i {
            font-size: 3rem;
            color: #fabc01;
        }

        .receipt-text h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fabc01;
        }

        .receipt-text p {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .receipt-text strong {
            color: #fabc01;
            font-weight: 700;
        }

        .receipt-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fabc01;
            color: #0B1E3E;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .receipt-btn:hover {
            background: white;
            color: #0B1E3E;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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

        /* Responsive Design */
        @media (max-width: 992px) {
            .donate-hero h1 {
                font-size: 3rem;
            }
            .donate-hero p {
                font-size: 1.2rem;
            }
            .donate-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .receipt-content {
                flex-direction: column;
                text-align: center;
                gap: 25px;
            }
            .ways-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .donate-hero {
                height: 300px;
            }
            .donate-hero h1 {
                font-size: 2.5rem;
            }
            .donate-container {
                margin: 40px auto;
                padding: 0 20px;
            }
            .column-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .header-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
            .column-header h2 {
                font-size: 1.5rem;
            }
            .payment-method {
                padding: 25px;
            }
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-value {
                justify-content: flex-start;
                width: 100%;
            }
            .ways-grid {
                grid-template-columns: 1fr;
            }
            .receipt-section {
                padding: 30px;
            }
            .receipt-text h3 {
                font-size: 1.6rem;
            }
            .bible-verse blockquote {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .donate-hero {
                height: 250px;
            }
            .donate-hero h1 {
                font-size: 2rem;
            }
            .donate-hero p {
                font-size: 1rem;
            }
            .method-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .method-badge {
                align-self: center;
            }
            .mpesa-steps li {
                font-size: 0.85rem;
            }
            .receipt-section {
                padding: 25px 20px;
            }
            .receipt-text h3 {
                font-size: 1.4rem;
            }
            .receipt-btn {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTopBtn">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- Hero Section -->

<!-- Main Donation Content -->
<main class="donate-container">
    <!-- Two Column Layout -->
    <div class="donate-grid">
        <!-- Column 1: Thanks Message -->
        <div class="donate-column thanks-column">
            <div class="column-header">
                <div class="header-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h2>Thanks For Your Continued Support</h2>
            </div>
            
            <div class="thanks-content">
                <div class="greeting">
                    <h3>Dear Partner,</h3>
                    <p>Your support plays a vital role in supporting the community. We deeply value and warmly welcome your contribution.</p>
                </div>
                
                <div class="bible-verse">
                    <div class="verse-icon">
                        <i class="fas fa-bible"></i>
                    </div>
                    <blockquote>
                        The generous will prosper; those who refresh others will themselves be refreshed.
                        <cite>Proverbs 11:25</cite>
                    </blockquote>
                </div>
                
                <div class="impact-statement">
                    <h4>Your Impact</h4>
                    <div class="impact-items">
                        <div class="impact-item">
                            <div class="impact-icon">
                                <i class="fas fa-child"></i>
                            </div>
                            <div class="impact-text">
                                <h5>Support a Child</h5>
                                <p>Provide education, meals, and mentorship for one child for a month</p>
                            </div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-icon">
                                <i class="fas fa-female"></i>
                            </div>
                            <div class="impact-text">
                                <h5>Empower a Teen Mother</h5>
                                <p>Give skills training and psychosocial support to a young mother</p>
                            </div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-icon">
                                <i class="fas fa-futbol"></i>
                            </div>
                            <div class="impact-text">
                                <h5>Fund Sports Equipment</h5>
                                <p>Provide sports gear for our SOKA TOTO program</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="transparency-note">
                    <div class="transparency-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="transparency-text">
                        <h5>Transparency Guaranteed</h5>
                        <p>We provide regular reports on how your donations are used to ensure accountability and build trust with our partners.</p>
                    </div>
                </div>
                
                <!-- Other Ways to Support -->
                <div class="other-ways">
                    <h4>Other Ways to Support</h4>
                    <div class="ways-grid">
                        <div class="way-item">
                            <div class="way-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            <div class="way-text">
                                <h5>In-Kind Donations</h5>
                                <p>Sports equipment, art supplies, computers</p>
                            </div>
                        </div>
                        <div class="way-item">
                            <div class="way-icon">
                                <i class="fas fa-hand-paper"></i>
                            </div>
                            <div class="way-text">
                                <h5>Volunteer</h5>
                                <p>Share your skills and time</p>
                            </div>
                        </div>
                        <div class="way-item">
                            <div class="way-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="way-text">
                                <h5>Advocate</h5>
                                <p>Spread the word about our work</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 2: Donation Methods -->
        <div class="donate-column methods-column">
         
            
            <div class="payment-methods">
                <!-- MPESA Method -->
                <div class="payment-method mpesa-method">
                    <div class="method-header">
                        <div class="method-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>MPESA</h3>
                        <span class="method-badge">Most Popular</span>
                    </div>
                    
                    <div class="method-instructions">
                        <h4>How to Donate via MPESA:</h4>
                        <ol class="mpesa-steps">
                            <li>Go to <strong>MPESA</strong> on your phone</li>
                            <li>Select <strong>Lipa Na MPESA</strong></li>
                            <li>Select <strong>Pay Bill</strong></li>
                            <li>Enter Business Number: <strong>522522</strong></li>
                            <li>Enter Account Number: <strong>7936016</strong></li>
                            <li>Enter Amount you wish to donate</li>
                            <li>Enter your MPESA PIN</li>
                            <li>Confirm details and send</li>
                        </ol>
                    </div>
                    
                    <div class="method-details">
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-hashtag"></i>
                                Paybill Number:
                            </div>
                            <div class="detail-value">
                                <span class="highlight-number">522522</span>
                                <button class="copy-btn" data-copy="522522">
                                    <i class="far fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-user"></i>
                                Account Number:
                            </div>
                            <div class="detail-value">
                                <span class="highlight-number">7936016</span>
                                <button class="copy-btn" data-copy="7936016">
                                    <i class="far fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Transfer Method -->
                <div class="payment-method bank-method">
                    <div class="method-header">
                        <div class="method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3>BANK TRANSFER</h3>
                    </div>
                    
                    <div class="method-details">
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-building"></i>
                                Account Name:
                            </div>
                            <div class="detail-value">SOKA TOTO MUDA INITIATIVE TRUST</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-credit-card"></i>
                                Account Number:
                            </div>
                            <div class="detail-value">
                                <span class="highlight-number">1335357998</span>
                                <button class="copy-btn" data-copy="1335357998">
                                    <i class="far fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-landmark"></i>
                                Bank Name:
                            </div>
                            <div class="detail-value">KCB Bank</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Branch:
                            </div>
                            <div class="detail-value">PRESTIGE PLAZA</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-code"></i>
                                Bank Code:
                            </div>
                            <div class="detail-value">01</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-code-branch"></i>
                                Branch Code:
                            </div>
                            <div class="detail-value">259</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-globe"></i>
                                Swift Code:
                            </div>
                            <div class="detail-value">KCBLKENX</div>
                        </div>
                    </div>
                    
                    <div class="method-note">
                        <i class="fas fa-info-circle"></i>
                        <p>For international transfers, please use the Swift Code provided above. All donations are tax-deductible.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</main>

<script>
    // Copy to Clipboard Functionality
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                this.style.background = '#10b981';
                this.style.color = 'white';
                
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.style.background = '';
                    this.style.color = '';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Press Ctrl+C to copy: ' + textToCopy);
            });
        });
    });

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