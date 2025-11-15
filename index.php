<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentEase - Premium Property Rentals</title>
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
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            height: 250px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 40px;
            margin-bottom: 10px;
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
            margin-bottom: 10px;
        }
        
        .services h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #222;
            text-align: center;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 10px;
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
            text-decoration: none;
        }
        
        .get-started-btn:hover {
            background-color: #2941cb;
        }
        
        /* Categories Section */
        .categories {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 10px;
        }
        
        .categories h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #222;
            text-align: center;
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 10px;
        }
        
        .category {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
            text-align: center;
            min-height: 100px;
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
        }

        @media (max-width: 576px) {
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .hero h2 {
                font-size: 2rem;
            }
            
            nav ul li {
                margin-left: 15px;
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
 
            

                <!-- Hero Slider -->
                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Slide 1 -->
                        <div class="carousel-item active">
                            <div class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1505691723518-36a5ac3be353?auto=format&fit=crop&w=2070&q=80');">
                                <h2>Find Your Perfect Property</h2>
                                <p>Discover luxury apartments, cozy houses, and commercial spaces that fit your lifestyle and budget.</p>
                                <div class="hero-search">
                                    <input type="text" placeholder="Search by city, address, or ZIP code">
                                    <button>Search</button>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="carousel-item">
                            <div class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=2070&q=80');">
                                <h2>Hassle-Free Rentals</h2>
                                <p>Enjoy smooth rental applications, verified landlords, and trusted property management with RentEase.</p>
                                <div class="hero-search">
                                    <input type="text" placeholder="Search by city, address, or ZIP code">
                                    <button>Search</button>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="carousel-item">
                            <div class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=2070&q=80');">
                                <h2>Commercial Spaces for Growth</h2>
                                <p>Boost your business with prime retail, office, and warehouse spaces in the heart of the city.</p>
                                <div class="hero-search">
                                    <input type="text" placeholder="Search commercial spaces">
                                    <button>Search</button>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 4 -->
                        <div class="carousel-item">
                            <div class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1502005097973-6a7082348e28?auto=format&fit=crop&w=2070&q=80');">
                                <h2>24/7 Support & Protection</h2>
                                <p>Stay stress-free with around-the-clock support and secure rental agreements that protect your rights.</p>
                                <div class="hero-search">
                                    <input type="text" placeholder="Search properties with support">
                                    <button>Search</button>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 5 -->
                        <div class="carousel-item">
                            <div class="hero" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=2070&q=80');">
                                <h2>Smart Search Made Easy</h2>
                                <p>Filter by price, location, or amenities to quickly find the home or office that matches your needs.</p>
                                <div class="hero-search">
                                    <input type="text" placeholder="Search smarter today">
                                    <button>Search</button>
                                </div>
                            </div>
                        </div>

                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
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
  <!-- Footer -->

<?php include 'inc/tenant-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>