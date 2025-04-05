<?php
require_once('db_connect.php');

$pdo = $connection;

if ($pdo) {
    echo "Database connection successful!";
    $query = $pdo->query("SELECT * FROM Member");
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    print_r($results);
} else {
    echo "Database connection failed.";
}
?>