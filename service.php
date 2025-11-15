<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentEase - Premium Property Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px 0;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            height: 300px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 40px;
            margin-bottom: 30px;
            color: white;
        }
        
        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            max-width: 600px;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 25px;
            max-width: 500px;
        }
        
        .hero-search {
            display: flex;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            max-width: 600px;
        }
        
        .hero-search input {
            flex: 1;
            border: none;
            padding: 15px 20px;
            font-size: 16px;
        }
        
        .hero-search button {
            background: #3a57e8;
            color: white;
            border: none;
            padding: 15px 25px;
            cursor: pointer;
            font-weight: 600;
        }
        
        /* Services Section */
        .services {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .services h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #222;
            text-align: center;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .service {
            display: flex;
            align-items: flex-start;
        }
        
        .service i {
            font-size: 24px;
            color: #3a57e8;
            margin-right: 15px;
            margin-top: 5px;
        }
        
        .service div {
            flex: 1;
        }
        
        .service h4 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #222;
        }
        
        .service p {
            color: #666;
            line-height: 1.5;
        }
        
        .get-started {
            text-align: center;
            margin-top: 20px;
        }
        
        .get-started-btn {
            background-color: #3a57e8;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .get-started-btn:hover {
            background-color: #2941cb;
        }
        
        /* Categories Section */
        .categories {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .categories h3 {
            font-size: 24px;
            margin-bottom: 25px;
            color: #222;
            text-align: center;
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
        
        .category {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
            text-align: center;
            min-height: 150px;
            justify-content: center;
        }
        
        .category:hover {
            transform: translateY(-5px);
            background: #f1f3f5;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .category i {
            font-size: 32px;
            color: #3a57e8;
            margin-bottom: 15px;
        }
        
        .category span {
            font-weight: 600;
            color: #333;
            font-size: 16px;
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
            .category-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .category-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-section {
                min-width: 150px;
            }
            
            nav ul {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #333;
            }
        }

        @media (max-width: 576px) {
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .hero h2 {
                font-size: 2rem;
            }
            
            .footer-content {
                flex-direction: column;
            }
            
            .footer-section {
                margin-bottom: 25px;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            nav ul {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
            
            nav ul li {
                margin-left: 0;
            }
            
            .user-actions {
                justify-content: center;
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .user-actions a {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
  <?php
include('inc/tenant-header.php');
?>

    <div class="main-content">
        <div class="container">
            <div class="hero">
                <h2>Find Your Perfect Property</h2>
                <p>Discover the best properties in your desired location with our premium rental service.</p>
                <div class="hero-search">
                    <input type="text" placeholder="Search by city, address, or ZIP code">
                    <button>Search</button>
                </div>
            </div>
            
            <div class="services">
                <h3>Our Services for Renters</h3>
                <div class="services-grid">
                    <div class="service">
                        <i class="fas fa-search"></i>
                        <div>
                            <h4>Easy Property Search</h4>
                            <p>Find verified listings with detailed filters and high-quality photos to make your search easier.</p>
                        </div>
                    </div>
                    <div class="service">
                        <i class="fas fa-file-contract"></i>
                        <div>
                            <h4>Digital Applications</h4>
                            <p>Apply for properties online with secure document upload and electronic signature capabilities.</p>
                        </div>
                    </div>
                    <div class="service">
                        <i class="fas fa-tools"></i>
                        <div>
                            <h4>Maintenance Requests</h4>
                            <p>Submit and track repair requests online with real-time updates on resolution progress.</p>
                        </div>
                    </div>
                    <div class="service">
                        <i class="fas fa-credit-card"></i>
                        <div>
                            <h4>Online Payments</h4>
                            <p>Pay rent securely from anywhere with multiple payment options and automated reminders.</p>
                        </div>
                    </div>
                    <div class="service">
                        <i class="fas fa-headset"></i>
                        <div>
                            <h4>24/7 Support</h4>
                            <p>Get help whenever you need it with our round-the-clock customer support team.</p>
                        </div>
                    </div>
                    <div class="service">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <h4>Renter Protection</h4>
                            <p>Enjoy peace of mind with our verified listings and secure transaction processes.</p>
                        </div>
                    </div>
                </div>
                <div class="get-started">
                    <button class="get-started-btn">Get Started Now</button>
                </div>
            </div>
            
            <div class="categories">
                <h3>Browse by Category</h3>
                <div class="category-grid">
                    <div class="category">
                        <i class="fas fa-building"></i>
                        <span>Apartments</span>
                    </div>
                    <div class="category">
                        <i class="fas fa-home"></i>
                        <span>Houses</span>
                    </div>

                    <div class="category">
                        <i class="fas fa-city"></i>
                        <span>Townhomes</span>
                    </div>

                    <div class="category">
                        <i class="fas fa-mountain"></i>
                        <span>Land</span>
                    </div>

                    <div class="category">
                        <i class="fas fa-store"></i>
                        <span>Retail Space</span>
                    </div>
                    <div class="category">
                        <i class="fas fa-warehouse"></i>
                        <span>Warehouses</span>
                    </div>
                    <div class="category">
                        <i class="fas fa-utensils"></i>
                        <span>Restaurants</span>
                    </div>

                    <div class="category">
                        <i class="fas fa-tractor"></i>
                        <span>Farmland</span>
                    </div>

                    <div class="category">
                        <i class="fas fa-building"></i>
                        <span>Office Spaces</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <?php
include('inc/tenant-footer.php');
?>

    <script>
        // Simple JavaScript for demonstration purposes
        document.addEventListener('DOMContentLoaded', function() {
            const getStartedBtn = document.querySelector('.get-started-btn');
            if (getStartedBtn) {
                getStartedBtn.addEventListener('click', function() {
                    alert('Welcome to RentEase! This would take you to the registration page.');
                });
            }
            
            const searchBtn = document.querySelector('.hero-search button');
            if (searchBtn) {
                searchBtn.addEventListener('click', function() {
                    const searchInput = document.querySelector('.hero-search input');
                    if (searchInput.value.trim() === '') {
                        alert('Please enter a location to search for properties.');
                    } else {
                        alert('Searching for properties in: ' + searchInput.value);
                    }
                });
            }
        });
    </script>
</body>
</html>