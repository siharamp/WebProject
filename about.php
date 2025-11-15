<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About RentEase - Premium Property Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px 0;
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            height: 250px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 40px;
            margin-bottom: 30px;
            color: white;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* About Sections */
        .about-section {
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .about-section h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #3a57e8;
            text-align: center;
        }
        
        .about-content {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .about-content.reverse {
            flex-direction: row-reverse;
        }
        
        .about-text {
            flex: 1;
            padding: 0 30px;
        }
        
        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .about-content h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #222;
        }
        
        .about-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 40px;
        }
        
        .feature {
            display: flex;
            align-items: flex-start;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .feature i {
            font-size: 28px;
            color: #3a57e8;
            margin-right: 20px;
            margin-top: 5px;
        }
        
        .feature div {
            flex: 1;
        }
        
        .feature h4 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #222;
        }
        
        .feature p {
            color: #666;
            line-height: 1.5;
        }
        
        /* Team Section */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 30px;
        }
        
        .team-member {
            text-align: center;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .team-member h4 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #222;
        }
        
        .team-member p {
            color: #3a57e8;
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .team-member .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .team-member .social-links a {
            color: #666;
            font-size: 16px;
            transition: color 0.3s;
        }
        
        .team-member .social-links a:hover {
            color: #3a57e8;
        }
        
        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            text-align: center;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        
        .stat {
            padding: 30px 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat i {
            font-size: 40px;
            color: #3a57e8;
            margin-bottom: 15px;
        }
        
        .stat h3 {
            font-size: 40px;
            margin-bottom: 10px;
            color: #222;
        }
        
        .stat p {
            color: #666;
            font-weight: 500;
        }
        
        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 50px 0 20px;
        }
        
        .cta-section h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #222;
        }
        
        .cta-section p {
            font-size: 18px;
            color: #666;
            max-width: 700px;
            margin: 0 auto 30px;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .cta-btn {
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .cta-btn.primary {
            background-color: #3a57e8;
            color: white;
            border: none;
        }
        
        .cta-btn.primary:hover {
            background-color: #2941cb;
        }
        
        .cta-btn.secondary {
            background-color: transparent;
            color: #3a57e8;
            border: 2px solid #3a57e8;
        }
        
        .cta-btn.secondary:hover {
            background-color: #f0f2ff;
        }
        
        /* Review Section */
        .review-section {
            background-color: #fff;
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .review-section h2 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #3a57e8;
            text-align: center;
        }
        
        .review-form {
            /* max-width: 800px; */
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3a57e8;
            box-shadow: 0 0 0 2px rgba(58, 87, 232, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background-color: #3a57e8;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            margin: 0 auto;
        }
        
        .submit-btn:hover {
            background-color: #2941cb;
        }
        
        /* Footer */
        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 30px 0 15px;
            bottom: 0;
            z-index: 100;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .footer-section {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }
        
        .footer-section h4 {
            font-size: 16px;
            margin-bottom: 15px;
            color: white;
            border-bottom: 1px solid #4a6572;
            padding-bottom: 8px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            columns: 2; 
            -webkit-columns: 2;
            -moz-columns: 2;
        }

        .footer-links li {
            margin-bottom: 8px;
            break-inside: avoid; 
        }
        .footer-links a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #3a57e8;
        }
        
        .contact-info {
            flex: 2;
            min-width: 250px;
        }
        
        .contact-info p {
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-links a {
            color: #ecf0f1;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: #3a57e8;
        }
        
        .footer-bottom {
            width: 100%;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #4a6572;
            margin-top: 0;
        }
        
        .footer-bottom p {
            font-size: 12px;
            color: #a0b0b9;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .about-content {
                flex-direction: column;
            }
            
            .about-content.reverse {
                flex-direction: column;
            }
            
            .about-text {
                padding: 20px 0;
            }
            
            .team-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-section {
                min-width: 150px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 2.2rem;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                flex-direction: column;
            }
            
            .footer-section {
                margin-bottom: 25px;
            }
        }
    </style>
</head>
<body>
    <?php include 'inc/tenant-header.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>About RentEase</h1>
                <p>Transforming the rental experience for tenants with innovative technology and exceptional service</p>
            </div>
            
            <div class="about-section">
                <h2>Our Story</h2>
                
                <div class="about-content">
                    <div class="about-text">
                        <h3>Making Renting Simple and Enjoyable</h3>
                        <p>Founded in 2015, RentEase emerged from a simple idea: renting a property should be straightforward, transparent, and hassle-free. Our founders, experienced landlords and tenants themselves, recognized the pain points in the traditional rental process and set out to create a better solution.</p>
                        <p>Today, RentEase serves thousands of tenants across the country, providing a seamless platform that connects renters with quality properties while offering tools to simplify every aspect of the rental journey.</p>
                    </div>
                    <div class="about-image">
                        <img src="https://images.unsplash.com/photo-1560520031-3a4dc4e9de0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2073&q=80" alt="RentEase Team">
                    </div>
                </div>
                
                <div class="about-content reverse">
                    <div class="about-text">
                        <h3>Our Mission</h3>
                        <p>At RentEase, we're on a mission to empower tenants by providing transparent, efficient, and user-friendly rental experiences. We believe everyone deserves a place they can call home, and finding that place should be an exciting journey, not a stressful ordeal.</p>
                        <p>Through continuous innovation and a customer-first approach, we're setting new standards in the rental industry, making the process more accessible and enjoyable for everyone involved.</p>
                    </div>
                    <div class="about-image">
                        <img src="img/modern_apartment.jpg" alt="Modern apartment">
                    </div>
                </div>
            </div>
            
            <div class="about-section">
                <h2>Why Choose RentEase?</h2>
                
                <div class="features-grid">
                    <div class="feature">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h4>Verified Properties</h4>
                            <p>Every listing on our platform is thoroughly verified to ensure accuracy and prevent scams.</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-lock"></i>
                        <div>
                            <h4>Secure Transactions</h4>
                            <p>Our encrypted payment system ensures your financial information remains safe and protected.</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>24/7 Support</h4>
                            <p>Our dedicated support team is available around the clock to assist with any issues or questions.</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-mobile-alt"></i>
                        <div>
                            <h4>Mobile Accessibility</h4>
                            <p>Access your account, make payments, and submit requests from anywhere with our mobile app.</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-file-contract"></i>
                        <div>
                            <h4>Digital Documentation</h4>
                            <p>Sign leases and other documents electronically for a paperless, efficient process.</p>
                        </div>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-tools"></i>
                        <div>
                            <h4>Maintenance Tracking</h4>
                            <p>Submit, track, and receive updates on maintenance requests through our transparent system.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="about-section">
                <h2>Our Impact</h2>
                <center></center>
                <div class="stats align-items-center justify-content-center d-flex">
                    <div class="stat">
                        <i class="fas fa-home"></i>
                        <h3>50,000+</h3>
                        <p>Properties Listed</p>
                    </div>
                    
                    <div class="stat">
                        <i class="fas fa-users"></i>
                        <h3>120,000+</h3>
                        <p>Happy Tenants</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="about-section">
                <h2>Meet Our Team</h2>
                
                <div class="team-grid">
                    <div class="team-member">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Team Member">
                        <h4>Michael Johnson</h4>
                        <p>CEO & Founder</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=688&q=80" alt="Team Member">
                        <h4>Sarah Williams</h4>
                        <p>Head of Tenant Experience</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    
                    <div class="team-member">
                        <img src="https://images.unsplash.com/photo-1567532939604-b6b5b0db1604?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Team Member">
                        <h4>David Chen</h4>
                        <p>Chief Technology Officer</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tenant Review Section -->
            <div class="review-section">
                <h2>Share Your Experience</h2>
                <p style="text-align: center; margin-bottom: 30px; color: #666;">We value your feedback! Please share your experience with RentEase.</p>
                
                <form class="review-form" action="submit_review.php" method="POST">
                    <div class="form-group">
                        <label for="tenant-name">Your Name</label>
                        <input type="text" id="tenant-name" name="tenant_name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tenant-review">Your Review</label>
                        <textarea id="tenant-review" name="tenant_review" class="form-control" placeholder="Tell us about your experience with RentEase..." required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Submit Review</button>
                </form>
            </div>
            
            <div class="cta-section">
                <h2>Ready to Find Your Perfect Home?</h2>
                <p>Join thousands of satisfied tenants who have simplified their rental experience with RentEase</p>
                <div class="cta-buttons">
                    <a href="properties.php" class="cta-btn primary">Browse Properties</a>
                    <a href="register.php" class="cta-btn secondary">Create Account</a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Properties</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Property Types</h4>
                    <ul class="footer-links">
                        <li><a href="#">Apartments</a></li>
                        <li><a href="#">Houses</a></li>
                        <li><a href="#">Commercial</a></li>
                        <li><a href="#">Land</a></li>
                        <li><a href="#">Vacation Rentals</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Resources</h4>
                    <ul class="footer-links">
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Guides</a></li>
                        <li><a href="#">Testimonials</a></li>
                        <li><a href="#">Site Map</a></li>
                    </ul>
                </div>
                
                <div class="contact-info">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Rental Street, City, State 12345</p>
                    <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> info@rentease.com</p>
                    
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <script>document.write(new Date().getFullYear());</script> RentEase Premium Rentals. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
            </div>
        </div>
    </footer>
</body>
</html>