<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

if (isset($_POST['login'])) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM admin WHERE username = '$username'");
    
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password']) || $password == $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            redirect('index.php');
        }
    }
    $error = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gym Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card mt-5">
                    <div class="card-header text-center bg-primary text-white">
                        <h4><i class="fas fa-dumbbell"></i> Gym Admin</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
