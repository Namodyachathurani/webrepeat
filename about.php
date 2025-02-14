<?php require_once 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - T-Shirt Printing Sri Lanka</title>
    <meta name="description" content="Learn about our t-shirt printing company in Sri Lanka. Quality printing services, experienced team, and state-of-the-art technology.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .about-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .about-hero {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--light-gray);
            margin-bottom: 4rem;
        }

        .about-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .about-hero p {
            max-width: 800px;
            margin: 0 auto;
            color: #666;
        }

        .about-section {
            margin-bottom: 4rem;
        }

        .about-section h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .value-card {
            text-align: center;
            padding: 2rem;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .value-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
            text-align: center;
        }

        .stat-card {
            padding: 2rem;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 8px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .timeline {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 2rem;
            position: relative;
        }

        .timeline-year {
            flex: 0 0 100px;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .timeline-content {
            flex: 1;
            padding-left: 2rem;
            border-left: 2px solid var(--secondary-color);
        }

        .timeline-content h3 {
            margin-bottom: 0.5rem;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .equipment-card {
            text-align: center;
            padding: 2rem;
            background: var(--light-gray);
            border-radius: 8px;
        }

        .equipment-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .about-container {
                padding: 1rem;
            }

            .about-hero {
                padding: 2rem 1rem;
            }

            .about-hero h1 {
                font-size: 2rem;
            }

            .timeline-item {
                flex-direction: column;
            }

            .timeline-year {
                margin-bottom: 1rem;
            }

            .timeline-content {
                padding-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="/" class="logo">T-ShirtPrinting.lk</a>
            <div class="nav-links">
                <a href="/">Home</a>
                <a href="products.php">Products</a>
                <a href="about.php" class="active">About</a>
                <a href="contact.php">Contact</a>
            </div>
        </nav>
    </header>

    <!-- About Hero -->
    <div class="about-hero" style="margin-top: 60px;">
        <h1>About T-ShirtPrinting.lk</h1>
        <p>We are Sri Lanka's premier t-shirt printing service, dedicated to delivering high-quality custom prints for individuals, businesses, and events. With years of experience and state-of-the-art technology, we bring your ideas to life.</p>
    </div>

    <!-- Main Content -->
    <div class="about-container">
        <!-- Our Values -->
        <section class="about-section">
            <h2>Our Values</h2>
            <div class="about-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Quality First</h3>
                    <p>We never compromise on quality, using only the best materials and printing techniques.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Customer Satisfaction</h3>
                    <p>Your satisfaction is our priority. We work closely with you to ensure perfect results.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainability</h3>
                    <p>We use eco-friendly materials and processes to minimize our environmental impact.</p>
                </div>
            </div>
        </section>

        <!-- Company Stats -->
        <section class="about-section">
            <h2>Our Impact</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50000+</div>
                    <div class="stat-label">T-Shirts Printed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Custom Designs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Quality Guarantee</div>
                </div>
            </div>
        </section>

        <!-- Our Journey -->
        <section class="about-section">
            <h2>Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">2018</div>
                    <div class="timeline-content">
                        <h3>The Beginning</h3>
                        <p>Started our t-shirt printing business with a small setup in Colombo.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2019</div>
                    <div class="timeline-content">
                        <h3>Growth Phase</h3>
                        <p>Expanded our services and upgraded to advanced printing equipment.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2020</div>
                    <div class="timeline-content">
                        <h3>Digital Expansion</h3>
                        <p>Launched online ordering system and expanded our design services.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2023</div>
                    <div class="timeline-content">
                        <h3>Market Leader</h3>
                        <p>Became one of Sri Lanka's leading t-shirt printing services.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Equipment -->
        <section class="about-section">
            <h2>Our Equipment</h2>
            <div class="equipment-grid">
                <div class="equipment-card">
                    <div class="equipment-icon">
                        <i class="fas fa-print"></i>
                    </div>
                    <h3>DTG Printing</h3>
                    <p>Latest Direct to Garment printing technology for high-quality, detailed prints.</p>
                </div>
                <div class="equipment-card">
                    <div class="equipment-icon">
                        <i class="fas fa-vector-square"></i>
                    </div>
                    <h3>Screen Printing</h3>
                    <p>Professional screen printing setup for bulk orders and special effects.</p>
                </div>
                <div class="equipment-card">
                    <div class="equipment-icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h3>Heat Transfer</h3>
                    <p>Advanced heat press machines for vinyl and transfer printing.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> T-ShirtPrinting.lk. All rights reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>
</body>
</html> 