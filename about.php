<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FitZone Gym</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="about-section py-5">
        <div class="container">
            <h1 class="text-center mb-5">About FitZone Gym</h1>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600" class="img-fluid rounded" alt="Gym Interior">
                </div>
                <div class="col-md-6">
                    <h2>Our Story</h2>
                    <p>Founded in 2010, FitZone Gym has been dedicated to helping our members achieve their fitness goals. With over a decade of experience, we've transformed thousands of lives through our comprehensive fitness programs.</p>
                    <h3>Our Mission</h3>
                    <p>To provide world-class fitness facilities and guidance to help everyone achieve their health and wellness goals.</p>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-4 text-center">
                    <div class="stat-box p-4">
                        <h3 class="text-primary">5000+</h3>
                        <p>Active Members</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="stat-box p-4">
                        <h3 class="text-primary">50+</h3>
                        <p>Expert Trainers</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="stat-box p-4">
                        <h3 class="text-primary">100+</h3>
                        <p>Equipment Units</p>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <h3 class="text-center mb-4">Our Services</h3>
                </div>
                <div class="col-md-3">
                    <div class="service-card p-3 text-center">
                        <i class="fas fa-heartbeat fa-2x mb-2 text-danger"></i>
                        <h5>Cardio Training</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card p-3 text-center">
                        <i class="fas fa-dumbbell fa-2x mb-2 text-primary"></i>
                        <h5>Weight Training</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card p-3 text-center">
                        <i class="fas fa-spa fa-2x mb-2 text-success"></i>
                        <h5>Yoga Classes</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="service-card p-3 text-center">
                        <i class="fas fa-running fa-2x mb-2 text-info"></i>
                        <h5>Personal Training</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
