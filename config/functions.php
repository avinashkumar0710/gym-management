<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) || isset($_SESSION['member_id']);
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function getStatusClass($status) {
    $classes = [
        'active' => 'badge-success',
        'expired' => 'badge-danger',
        'pending' => 'badge-warning',
        'paid' => 'badge-success',
        'unpaid' => 'badge-danger',
        'available' => 'badge-success',
        'maintenance' => 'badge-warning',
        'rented' => 'badge-info'
    ];
    return $classes[$status] ?? 'badge-secondary';
}
?>
