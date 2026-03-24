<?php
require_once 'config/database.php';
require_once 'config/functions.php';

$plans = $conn->query("SELECT * FROM plans ORDER BY price ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Gym - Transform Your Body</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="hero-section">
        <div class="overlay"></div>
        <div class="container hero-content">
            <h1>Transform Your Body, Transform Your Life</h1>
            <p>Join the best gym in town and achieve your fitness goals</p>
            <a href="#plans" class="btn btn-primary btn-lg">View Plans</a>
        </div>
    </div>

    <section class="features py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Us</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <i class="fas fa-dumbbell fa-3x mb-3 text-primary"></i>
                        <h4>Modern Equipment</h4>
                        <p>State-of-the-art gym equipment for the best workout experience</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <i class="fas fa-user-tie fa-3x mb-3 text-primary"></i>
                        <h4>Expert Trainers</h4>
                        <p>Certified personal trainers to guide you every step</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center p-4">
                        <i class="fas fa-clock fa-3x mb-3 text-primary"></i>
                        <h4>Flexible Hours</h4>
                        <p>Open 6 AM to 11 PM, 7 days a week</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="plans" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Our Membership Plans</h2>
            <div class="row">
                <?php while($plan = $plans->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="plan-card card">
                        <div class="card-header bg-primary text-white text-center">
                            <h4><?php echo $plan['name']; ?></h4>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-primary">$<?php echo number_format($plan['price'], 2); ?></h2>
                            <p class="text-muted"><?php echo $plan['duration']; ?> Days</p>
                            <p><?php echo $plan['description']; ?></p>
                            <a href="contact.php" class="btn btn-outline-primary">Join Now</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="gallery py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Facility</h2>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=300" class="img-fluid rounded" alt="Gym">
                </div>
                <div class="col-md-3 mb-3">
                    <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=300" class="img-fluid rounded" alt="Equipment">
                </div>
                <div class="col-md-3 mb-3">
                    <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=300" class="img-fluid rounded" alt="Workout">
                </div>
                <div class="col-md-3 mb-3">
                    <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=300" class="img-fluid rounded" alt="Training">
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
