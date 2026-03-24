<?php
require_once 'config/database.php';
require_once 'config/functions.php';

if (isset($_POST['contact_submit'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $message = sanitize($_POST['message']);
    
    $sql = "INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $message);
    $stmt->execute();
    $success = "Thank you for contacting us! We'll get back to you soon.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FitZone Gym</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="contact-section py-5">
        <div class="container">
            <h1 class="text-center mb-5">Contact Us</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-info">
                        <h3>Get In Touch</h3>
                        <p><i class="fas fa-map-marker-alt"></i> 123 Fitness Street, Gym City</p>
                        <p><i class="fas fa-phone"></i> +1 234 567 890</p>
                        <p><i class="fas fa-envelope"></i> info@fitzone.com</p>
                        <p><i class="fas fa-clock"></i> Mon-Sun: 6:00 AM - 11:00 PM</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="contact_submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
