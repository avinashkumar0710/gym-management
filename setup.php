<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym_management";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
}

$conn->select_db($database);

$tables = [
    "CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS plans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        duration INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE,
        phone VARCHAR(20),
        address TEXT,
        plan_id INT,
        join_date DATE,
        status ENUM('active', 'expired', 'pending') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (plan_id) REFERENCES plans(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(50) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        salary DECIMAL(10,2),
        join_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        date DATE NOT NULL,
        status ENUM('present', 'absent') DEFAULT 'present',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT,
        amount DECIMAL(10,2) NOT NULL,
        payment_date DATE,
        payment_method VARCHAR(50),
        status ENUM('paid', 'pending') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS equipment (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        quantity INT DEFAULT 1,
        status ENUM('available', 'maintenance') DEFAULT 'available',
        purchase_date DATE,
        maintenance_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

$check_admin = $conn->query("SELECT id FROM admin WHERE username = 'admin'");
if ($check_admin->num_rows == 0) {
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admin (name, username, password) VALUES ('Administrator', 'admin', '$hashed_password')");
    echo "Admin user created (username: admin, password: admin123)<br>";
}

$check_plans = $conn->query("SELECT id FROM plans");
if ($check_plans->num_rows == 0) {
    $conn->query("INSERT INTO plans (name, duration, price, description) VALUES ('Basic', 30, 29.99, 'Access to gym floor and basic equipment')");
    $conn->query("INSERT INTO plans (name, duration, price, description) VALUES ('Standard', 90, 79.99, 'Full gym access + group classes')");
    $conn->query("INSERT INTO plans (name, duration, price, description) VALUES ('Premium', 365, 249.99, 'Unlimited access + personal trainer + sauna')");
    echo "Sample plans created<br>";
}

echo "<br><b>Setup Complete!</b><br>";
echo "Admin Login: <a href='admin/login.php'>admin/login.php</a><br>";
echo "Username: admin | Password: admin123";

$conn->close();
?>
