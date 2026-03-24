<?php
session_start();
require_once 'database.php';
require_once 'functions.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../index.php');
}

$page_title = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Gym Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar bg-dark text-white">
            <div class="p-3 text-center">
                <h4><i class="fas fa-dumbbell"></i> GYM Admin</h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="nav-item"><a href="members.php" class="nav-link"><i class="fas fa-users"></i> Members</a></li>
                <li class="nav-item"><a href="plans.php" class="nav-link"><i class="fas fa-tags"></i> Plans</a></li>
                <li class="nav-item"><a href="staff.php" class="nav-link"><i class="fas fa-user-tie"></i> Staff</a></li>
                <li class="nav-item"><a href="attendance.php" class="nav-link"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li class="nav-item"><a href="payments.php" class="nav-link"><i class="fas fa-credit-card"></i> Payments</a></li>
                <li class="nav-item"><a href="equipment.php" class="nav-link"><i class="fas fa-dumbbell"></i> Equipment</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <div class="content w-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <span class="navbar-brand"><?php echo $page_title; ?></span>
                <div class="ml-auto">
                    <span class="text-white">Welcome, Admin</span>
                </div>
            </nav>
            <main class="p-4">
