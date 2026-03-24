<?php
$connectionString = getenv('DATABASE_URL');

if ($connectionString) {
    $conn = pg_connect($connectionString);
} else {
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'postgres';
    $password = getenv('DB_PASSWORD') ?: '';
    $dbname = getenv('DB_NAME') ?: 'gym_management';
    $conn = pg_connect("host=$host user=$user password=$password dbname=$dbname");
}

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
